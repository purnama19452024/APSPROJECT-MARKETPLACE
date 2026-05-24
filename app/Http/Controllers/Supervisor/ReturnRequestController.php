<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnRequestController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with('user', 'transaction')->latest()->paginate(15);
        return view('supervisor.returns.index', compact('returns'));
    }

    public function show(ReturnRequest $return)
    {
        $return->load('user', 'transaction', 'item.product');
        return view('supervisor.returns.show', compact('return'));
    }

    public function respond(Request $request, ReturnRequest $return)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed',
            'response' => 'nullable|string',
            'refund_amount' => 'nullable|numeric|min:0',
        ]);

        $return->update([
            'status' => $request->status,
            'response' => $request->response,
            'refund_amount' => $request->status === 'approved' ? $request->refund_amount : null,
            'responded_at' => now(),
        ]);

        $label = match ($request->status) {
            'approved' => 'disetujui',
            'rejected' => 'ditolak',
            'completed' => 'selesai',
            default => $request->status,
        };

        return back()->with('success', 'Permintaan pengembalian berhasil ' . $label);
    }
}
