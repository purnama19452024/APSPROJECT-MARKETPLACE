<?php

namespace App\Http\Controllers;

use App\Models\UserBalance;
use App\Models\BalanceHistory;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    public function index()
    {
        $balance = auth()->user()->balanceRelation ?? UserBalance::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );
        $histories = BalanceHistory::where('user_id', auth()->id())->latest()->paginate(15);
        $pendingWithdrawals = WithdrawalRequest::where('user_id', auth()->id())->where('status', 'pending')->latest()->get();
        $totalTopup = BalanceHistory::where('user_id', auth()->id())->whereIn('type', ['topup', 'admin_add'])->sum('amount');
        $totalSpent = BalanceHistory::where('user_id', auth()->id())->where('type', 'payment')->sum(DB::raw('ABS(amount)'));
        return view('saldo.index', compact('balance', 'histories', 'pendingWithdrawals', 'totalTopup', 'totalSpent'));
    }

    public function topup()
    {
        return view('saldo.topup');
    }

    public function topupStore(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000|max:10000000',
            'payment_method' => 'required|in:qris,bank_transfer,dana,gopay',
            'bank' => 'required_if:payment_method,bank_transfer|string|max:30',
        ]);

        session([
            'topup_amount' => (float) $request->amount,
            'topup_method' => $request->payment_method,
            'topup_bank' => $request->bank,
        ]);

        $invoice = 'TP-' . strtoupper(auth()->user()->username) . '-' . time();

        if ($request->payment_method === 'bank_transfer') {
            $banks = [
                'BCA' => ['account' => '1234567890', 'name' => 'PT APSPROJECT'],
                'Mandiri' => ['account' => '9876543210', 'name' => 'PT APSPROJECT'],
                'BNI' => ['account' => '5556667770', 'name' => 'PT APSPROJECT'],
                'BRI' => ['account' => '4445556660', 'name' => 'PT APSPROJECT'],
                'CIMB Niaga' => ['account' => '3334445550', 'name' => 'PT APSPROJECT'],
                'BSI' => ['account' => '2223334440', 'name' => 'PT APSPROJECT'],
                'Permata' => ['account' => '1112223330', 'name' => 'PT APSPROJECT'],
                'Danamon' => ['account' => '9998887770', 'name' => 'PT APSPROJECT'],
                'BTN' => ['account' => '7778889990', 'name' => 'PT APSPROJECT'],
            ];
            $bankDetail = $banks[$request->bank] ?? $banks['BCA'];
            return view('saldo.topup-bank', [
                'amount' => (float) $request->amount,
                'invoice' => $invoice,
                'bank' => $request->bank,
                'account' => $bankDetail['account'],
                'accountName' => $bankDetail['name'],
            ]);
        }

        return view('saldo.topup-qris', [
            'amount' => (float) $request->amount,
            'invoice' => $invoice,
        ]);
    }

    public function topupConfirm(Request $request)
    {
        $amount = session('topup_amount');
        $method = session('topup_method', 'qris');
        $bank = session('topup_bank');

        if (!$amount) {
            return redirect()->route('saldo.topup')->with('error', 'Sesi top up tidak valid. Silakan ulangi.');
        }

        $labels = ['qris' => 'QRIS', 'bank_transfer' => 'Transfer ' . $bank, 'dana' => 'DANA', 'gopay' => 'GoPay'];
        $desc = 'Top up saldo via ' . ($labels[$method] ?? strtoupper($method));

        DB::transaction(function () use ($amount, $desc) {
            $balance = auth()->user()->balanceRelation ?? UserBalance::firstOrCreate(
                ['user_id' => auth()->id()],
                ['balance' => 0]
            );
            $balance->increment('balance', $amount);
            BalanceHistory::create([
                'user_id' => auth()->id(),
                'amount' => $amount,
                'type' => 'topup',
                'description' => $desc,
            ]);
        });

        session()->forget(['topup_amount', 'topup_method', 'topup_bank']);

        return redirect()->route('saldo.index')->with('success', 'Top up sebesar Rp ' . number_format($amount, 0, ',', '.') . ' berhasil!');
    }

    public function withdraw()
    {
        $balance = auth()->user()->balanceRelation ?? UserBalance::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );
        $pendingTotal = WithdrawalRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->sum('amount');
        $maxWithdraw = max(0, $balance->balance - $pendingTotal);
        return view('saldo.withdraw', compact('balance', 'pendingTotal', 'maxWithdraw'));
    }

    public function withdrawStore(Request $request)
    {
        $balance = auth()->user()->balanceRelation ?? UserBalance::firstOrCreate(
            ['user_id' => auth()->id()],
            ['balance' => 0]
        );
        $pendingTotal = WithdrawalRequest::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->sum('amount');
        $maxWithdraw = max(0, $balance->balance - $pendingTotal);

        $request->validate([
            'amount' => 'required|numeric|min:10000|max:' . $maxWithdraw,
            'bank_name' => 'required|string|max:50',
            'bank_account_number' => 'required|string|max:30',
            'bank_account_name' => 'required|string|max:100',
        ]);

        WithdrawalRequest::create([
            'user_id' => auth()->id(),
            'amount' => (float) $request->amount,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
        ]);

        return redirect()->route('saldo.index')->with('success', 'Permintaan penarikan saldo telah diajukan. Menunggu konfirmasi admin.');
    }
}
