<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user')->latest()->paginate(15);
        return view('supervisor.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load('user', 'items.product', 'payment');
        return view('supervisor.transactions.show', compact('transaction'));
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
        return back()->with('success', 'Pembayaran berhasil ' . ($request->status === 'verified' ? 'diverifikasi' : 'ditolak'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate(['status' => 'required|in:pending,diproses,dikirim,dalam_perjalanan,selesai,dibatalkan']);
        $transaction->update(['status' => $request->status]);

        if ($request->filled('shipping_receipt')) {
            $transaction->update(['shipping_receipt' => $request->shipping_receipt]);
        }

        if ($request->filled('shipping_service')) {
            $transaction->update(['shipping_service' => $request->shipping_service]);
        }

        return back()->with('success', 'Status transaksi berhasil diperbarui');
    }
}
