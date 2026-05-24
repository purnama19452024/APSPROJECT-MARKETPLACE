<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function create(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);
        return view('complaints.create', compact('transaction'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $transaction = Transaction::findOrFail($request->transaction_id);
        if ($transaction->user_id !== auth()->id()) abort(403);

        Complaint::create([
            'user_id' => auth()->id(),
            'transaction_id' => $request->transaction_id,
            'subject' => $request->subject,
            'description' => $request->description,
        ]);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Komplain berhasil dikirim');
    }
}
