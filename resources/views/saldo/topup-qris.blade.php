@extends('layouts.landing')

@section('title', 'Pembayaran Top Up')

@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran Top Up</h1>
            <p class="text-sm text-gray-500 mt-1">Scan QR code untuk menyelesaikan top up</p>
        </div>
        <a href="{{ route('saldo.index') }}" class="text-sm text-gray-400 hover:text-orange-500 flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Amount --}}
        <div class="bg-emerald-50 border-b border-emerald-100 px-5 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-emerald-600 font-medium uppercase tracking-wider">Nominal Top Up</p>
                    <p class="text-2xl font-bold text-emerald-700 mt-0.5">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-emerald-500"></i>
                </div>
            </div>
        </div>

        {{-- QR Code --}}
        <div class="px-5 py-6 flex flex-col items-center">
            <div class="bg-white p-3 rounded-xl shadow-md border border-gray-200 mb-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=APSPROJECT-TOPUP-{{ $invoice }}&format=png"
                     alt="QRIS" class="w-56 h-56">
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
                <i class="fas fa-qrcode text-emerald-500"></i>
                <span>Scan dengan <strong class="text-gray-700">e-wallet</strong> atau <strong class="text-gray-700">mobile banking</strong></span>
            </div>

            {{-- Confirm --}}
            <form action="{{ route('saldo.topup.confirm') }}" method="POST" class="w-full">
                @csrf
                <input type="hidden" name="amount" value="{{ $amount }}">
                <p class="text-xs text-gray-400 text-center mb-3">Setelah transfer berhasil, klik tombol di bawah untuk konfirmasi</p>
                <button type="submit" class="w-full bg-emerald-500 text-white py-3 rounded-xl font-semibold text-sm hover:bg-emerald-600 transition shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Saya Sudah Bayar
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('saldo.topup') }}" class="text-xs text-gray-400 hover:text-emerald-500">Batalkan dan pilih nominal lain</a>
            </div>
        </div>
    </div>
</section>
@endSection
