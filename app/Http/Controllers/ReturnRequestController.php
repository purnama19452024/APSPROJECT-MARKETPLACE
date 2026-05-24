<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReturnRequestController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with('transaction')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
        return view('returns.index', compact('returns'));
    }

    public function create(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);
        if ($transaction->status !== 'selesai') {
            return back()->with('error', 'Pengembalian hanya dapat diajukan untuk pesanan yang sudah selesai');
        }
        $items = $transaction->items()->with('product')->get();
        return view('returns.create', compact('transaction', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'transaction_item_id' => 'nullable|exists:transaction_items,id',
            'reason' => 'required|string|max:255',
            'description' => 'required|string',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $transaction = Transaction::findOrFail($validated['transaction_id']);
        if ($transaction->user_id !== auth()->id()) abort(403);
        if ($transaction->status !== 'selesai') {
            return back()->with('error', 'Pengembalian hanya dapat diajukan untuk pesanan selesai');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('returns', 'public');
            }
        }

        ReturnRequest::create([
            'user_id' => auth()->id(),
            'transaction_id' => $validated['transaction_id'],
            'transaction_item_id' => $validated['transaction_item_id'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'images' => $imagePaths,
            'status' => 'pending',
        ]);

        return redirect()->route('returns.index')
            ->with('success', 'Permintaan pengembalian berhasil diajukan');
    }

    public function show(ReturnRequest $return)
    {
        if ($return->user_id !== auth()->id()) abort(403);
        $return->load('transaction', 'item.product');
        return view('returns.show', compact('return'));
    }
}
