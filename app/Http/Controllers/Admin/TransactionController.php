<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"));
            });
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();
        $statusCounts = [
            'all' => Transaction::count(),
            'pending' => Transaction::where('status', 'pending')->count(),
            'diproses' => Transaction::where('status', 'diproses')->count(),
            'dikirim' => Transaction::where('status', 'dikirim')->count(),
            'dalam_perjalanan' => Transaction::where('status', 'dalam_perjalanan')->count(),
            'selesai' => Transaction::where('status', 'selesai')->count(),
            'dibatalkan' => Transaction::where('status', 'dibatalkan')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'statusCounts'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'items.product', 'payment');
        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,diproses,dikirim,dalam_perjalanan,selesai,dibatalkan',
        ]);

        $transaction->update(['status' => $request->status]);

        if ($request->filled('shipping_receipt')) {
            $transaction->update(['shipping_receipt' => $request->shipping_receipt]);
        }

        if ($request->filled('shipping_service')) {
            $transaction->update(['shipping_service' => $request->shipping_service]);
        }

        $statusLabels = [
            'pending' => 'pending',
            'diproses' => 'diproses',
            'dikirim' => 'dikirim',
            'dalam_perjalanan' => 'dalam perjalanan',
            'selesai' => 'selesai',
            'dibatalkan' => 'dibatalkan',
        ];

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi "' . ($statusLabels[$request->status] ?? $request->status) . '"');
    }

    public function verifyPayment(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|in:verified,rejected']);

        if ($transaction->payment) {
            $transaction->payment->update(['status' => $request->status]);
        }

        if ($request->status === 'verified') {
            $transaction->update(['status' => 'diproses']);
        }

        $msg = $request->status === 'verified' ? 'diverifikasi' : 'ditolak';
        return back()->with('success', 'Pembayaran berhasil ' . $msg);
    }

    public function confirmCod(Transaction $transaction)
    {
        if ($transaction->payment_method !== 'cod') {
            return back()->with('error', 'Bukan metode COD');
        }
        $transaction->update(['status' => 'diproses']);
        if ($transaction->payment) {
            $transaction->payment->update(['status' => 'verified']);
        }
        return back()->with('success', 'Pesanan COD dikonfirmasi dan siap diproses');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->update(['status' => 'dibatalkan']);
        return back()->with('success', 'Transaksi berhasil dibatalkan');
    }
}
