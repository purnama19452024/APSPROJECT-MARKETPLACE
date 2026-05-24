<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with('user', 'transaction')->latest()->paginate(15);
        return view('supervisor.complaints.index', compact('complaints'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load('user', 'transaction');
        return view('supervisor.complaints.show', compact('complaint'));
    }

    public function respond(Request $request, Complaint $complaint)
    {
        $request->validate(['response' => 'required|string']);
        $complaint->update([
            'response' => $request->response,
            'status' => $request->status ?? 'selesai',
            'responded_at' => now(),
        ]);
        return back()->with('success', 'Tanggapan berhasil dikirim');
    }
}
