<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBalance;
use App\Models\BalanceHistory;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    public function index()
    {
        $users = User::with('balanceRelation')->whereHas('role', fn($q) => $q->where('slug', 'user'))->latest()->paginate(20);
        $stats = [
            'total_users' => User::whereHas('role', fn($q) => $q->where('slug', 'user'))->count(),
            'total_saldo' => UserBalance::sum('balance'),
            'pending_withdrawals' => WithdrawalRequest::where('status', 'pending')->count(),
            'total_disbursed' => WithdrawalRequest::where('status', 'approved')->sum('amount'),
        ];
        $pendingRequests = WithdrawalRequest::with('user')->where('status', 'pending')->latest()->take(10)->get();
        return view('admin.saldo.index', compact('users', 'stats', 'pendingRequests'));
    }

    public function edit(User $user)
    {
        $balance = $user->balanceRelation ?? UserBalance::firstOrCreate(['user_id' => $user->id]);
        $histories = BalanceHistory::where('user_id', $user->id)->latest()->take(50)->get();
        return view('admin.saldo.edit', compact('user', 'balance', 'histories'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'action' => 'required|in:add,subtract,set',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
        ]);

        $balance = $user->balanceRelation ?? UserBalance::firstOrCreate(['user_id' => $user->id, 'balance' => 0]);
        $amount = (float) $request->amount;

        if ($request->action === 'add') {
            $balance->increment('balance', $amount);
            BalanceHistory::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'type' => 'admin_add',
                'description' => $request->description ?? 'Penambahan saldo oleh admin',
            ]);
            $msg = "Saldo Rp " . number_format($amount, 0, ',', '.') . " berhasil ditambahkan";
        } elseif ($request->action === 'subtract') {
            if ($balance->balance < $amount) {
                return back()->with('error', 'Saldo user tidak mencukupi');
            }
            $balance->decrement('balance', $amount);
            BalanceHistory::create([
                'user_id' => $user->id,
                'amount' => -$amount,
                'type' => 'admin_subtract',
                'description' => $request->description ?? 'Pengurangan saldo oleh admin',
            ]);
            $msg = "Saldo Rp " . number_format($amount, 0, ',', '.') . " berhasil dikurangi";
        } else {
            $diff = $amount - $balance->balance;
            $balance->update(['balance' => $amount]);
            BalanceHistory::create([
                'user_id' => $user->id,
                'amount' => $diff,
                'type' => $diff >= 0 ? 'admin_add' : 'admin_subtract',
                'description' => $request->description ?? 'Penyesuaian saldo oleh admin',
            ]);
            $msg = "Saldo berhasil diset menjadi Rp " . number_format($amount, 0, ',', '.');
        }

        return redirect()->route('admin.saldo.index')->with('success', $msg);
    }

    public function withdrawals()
    {
        $requests = WithdrawalRequest::with('user')->latest()->paginate(20);
        $stats = [
            'pending' => WithdrawalRequest::where('status', 'pending')->count(),
            'approved' => WithdrawalRequest::where('status', 'approved')->count(),
            'total' => WithdrawalRequest::sum('amount'),
        ];
        return view('admin.saldo.withdrawals', compact('requests', 'stats'));
    }

    public function withdrawalsApprove(WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses');
        }

        DB::transaction(function () use ($withdrawal) {
            $balance = $withdrawal->user->balanceRelation;
            if (!$balance || $balance->balance < $withdrawal->amount) {
                throw new \Exception('Saldo user tidak mencukupi');
            }

            $balance->decrement('balance', $withdrawal->amount);
            $withdrawal->update([
                'status' => 'approved',
                'processed_at' => now(),
            ]);

            BalanceHistory::create([
                'user_id' => $withdrawal->user_id,
                'amount' => -$withdrawal->amount,
                'type' => 'admin_subtract',
                'description' => 'Penarikan saldo ke ' . $withdrawal->bank_name . ' (' . $withdrawal->bank_account_number . ')',
            ]);
        });

        return redirect()->route('admin.saldo.index')->with('success', 'Penarikan saldo disetujui');
    }

    public function withdrawalsReject(Request $request, WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses');
        }

        $request->validate(['admin_notes' => 'nullable|string|max:255']);

        $withdrawal->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.saldo.index')->with('success', 'Penarikan saldo ditolak');
    }
}
