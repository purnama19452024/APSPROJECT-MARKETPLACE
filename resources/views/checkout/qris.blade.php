@extends('layouts.landing')

@section('title', 'Pembayaran QRIS')

@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-2"><i class="fas fa-qrcode text-orange-500 mr-2"></i>Pembayaran QRIS</h1>
        <p class="text-sm text-gray-500">Scan QR code berikut untuk melakukan pembayaran</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 p-8 shadow-sm mb-6">
        <div class="flex flex-col items-center">
            <div class="bg-white p-4 rounded-2xl shadow-lg mb-4 border-2 border-orange-200">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=APSPROJECT-QRIS-{{ $transaction->invoice }}&format=png"
                     alt="QRIS Payment"
                     class="w-64 h-64">
            </div>
            <p class="text-sm text-gray-400 mb-2">Scan dengan aplikasi e-wallet atau mobile banking</p>
            <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 w-full max-w-sm text-center mb-4">
                <p class="text-sm text-gray-600 mb-1">Total Pembayaran</p>
                <p class="text-3xl font-bold text-orange-500">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-6">
        <h3 class="font-bold text-gray-800 mb-3">Detail Pesanan</h3>
        <div class="text-sm space-y-2">
            <div class="flex justify-between"><span class="text-gray-500">Invoice</span><span class="font-medium">{{ $transaction->invoice }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Total Produk</span><span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Ongkir</span><span>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span></div>
            <div class="border-t pt-2 flex justify-between"><span class="font-semibold">Total</span><span class="font-bold text-orange-500">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span></div>
        </div>
    </div>

    <form action="{{ route('transactions.qris.confirm', $transaction) }}" method="POST" class="text-center">
        @csrf
        <p class="text-xs text-gray-400 mb-4">Setelah melakukan pembayaran, klik tombol di bawah untuk konfirmasi</p>
        <button type="submit" class="bg-green-500 text-white px-8 py-3 rounded-xl font-semibold hover:bg-green-600 transition text-lg shadow-lg hover:shadow-green-500/30">
            <i class="fas fa-check-circle mr-2"></i>Saya Sudah Bayar
        </button>
    </form>

    <div class="text-center mt-4">
        <a href="{{ route('transactions.show', $transaction) }}" class="text-sm text-gray-400 hover:text-orange-500">Nanti, lihat nanti</a>
    </div>
</section>
@endsection
