<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TrackingController extends Controller
{
    public function search()
    {
        $receipt = request('resi');
        $transaction = null;

        if ($receipt) {
            $transaction = Transaction::where('shipping_receipt', $receipt)
                ->where('user_id', auth()->id())
                ->with('items.product')
                ->first();

            if (!$transaction) {
                return back()->with('error', 'Resi tidak ditemukan. Periksa kembali nomor resi Anda.');
            }

            return redirect()->route('tracking.show', $transaction);
        }

        return view('tracking.search');
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);
        $transaction->load('items.product');

        $locations = [
            'Bandung, Jawa Barat',
            'Cimahi, Jawa Barat',
            'Bekasi, Jawa Barat',
            'Bogor, Jawa Barat',
            'Depok, Jawa Barat',
            'Tangerang, Banten',
            'Cirebon, Jawa Barat',
            'Purwakarta, Jawa Barat',
            'Karawang, Jawa Barat',
            'Subang, Jawa Barat',
            'Sumedang, Jawa Barat',
            'Garut, Jawa Barat',
            'Tasikmalaya, Jawa Barat',
            'Ciamis, Jawa Barat',
            'Banjar, Jawa Barat',
            'Sukabumi, Jawa Barat',
            'Cianjur, Jawa Barat',
            'Lembang, Bandung Barat',
            'Soreang, Bandung',
            'Majalaya, Bandung',
        ];
        $location = $locations[array_rand($locations)];

        return view('tracking.index', compact('transaction', 'location'));
    }
}
