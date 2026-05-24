@extends('layouts.landing')

@section('title', 'Pembayaran Top Up')

@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran Top Up</h1>
            <p class="text-sm text-gray-500 mt-1">Transfer ke rekening tujuan</p>
        </div>
        <a href="{{ route('saldo.index') }}" class="text-sm text-gray-400 hover:text-orange-500 flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Amount --}}
        <div class="bg-blue-600 px-5 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-blue-100 font-medium uppercase tracking-wider">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-white mt-0.5">Rp {{ number_format($amount, 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center">
                    <i class="fas fa-university text-white"></i>
                </div>
            </div>
        </div>

        {{-- Bank Detail --}}
        <div class="p-5">
            <div class="border border-blue-200 bg-blue-50 rounded-xl p-4 mb-5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-sm font-semibold text-gray-700">Rekening Tujuan</p>
                    <span class="text-xs font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded">{{ $bank }}</span>
                </div>
                <div class="bg-white rounded-lg p-3 border border-blue-100 mb-3">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Nomor Rekening</p>
                    <p class="text-xl font-bold text-gray-900 tracking-wider">{{ $account }}</p>
                </div>
                <div class="bg-white rounded-lg p-3 border border-blue-100">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider mb-0.5">Atas Nama</p>
                    <p class="text-base font-bold text-gray-900">{{ $accountName }}</p>
                </div>
            </div>

            {{-- Instructions --}}
            <div class="space-y-3 mb-5">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="text-white text-[10px] font-bold">1</span>
                    </div>
                    <p class="text-xs text-gray-600">Transfer sejumlah <strong>Rp {{ number_format($amount, 0, ',', '.') }}</strong> ke rekening <strong>{{ $bank }}</strong> di atas</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="text-white text-[10px] font-bold">2</span>
                    </div>
                    <p class="text-xs text-gray-600">Pastikan jumlah transfer <strong>sama persis</strong> agar sistem dapat memproses secara otomatis</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-gray-900 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                        <span class="text-white text-[10px] font-bold">3</span>
                    </div>
                    <p class="text-xs text-gray-600">Klik tombol <strong>"Saya Sudah Transfer"</strong> setelah melakukan transfer</p>
                </div>
            </div>

            <div class="bg-orange-50 border border-orange-200 rounded-xl px-4 py-3 mb-5 flex items-start gap-3">
                <i class="fas fa-info-circle text-orange-400 text-sm mt-0.5"></i>
                <p class="text-xs text-orange-700">Saldo akan otomatis bertambah setelah Anda konfirmasi. Jika tidak masuk dalam 1x24 jam, hubungi admin.</p>
            </div>

            <form action="{{ route('saldo.topup.confirm') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-xl font-semibold text-sm hover:bg-black transition shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Saya Sudah Transfer
                </button>
            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('saldo.topup') }}" class="text-xs text-gray-400 hover:text-orange-500">Batalkan transaksi</a>
            </div>
        </div>
    </div>
</section>
@endSection
