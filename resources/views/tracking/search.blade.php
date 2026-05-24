@extends('layouts.landing')

@section('title', 'Lacak Pesanan')

@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-10">
        <a href="{{ route('transactions.index') }}" class="text-orange-500 hover:text-orange-600 text-sm inline-flex items-center gap-1.5 transition">
            <i class="fas fa-arrow-left text-xs"></i>Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-10">
        <div class="w-16 h-16 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
            <i class="fas fa-search text-white text-xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-2">Lacak Pesanan</h1>
        <p class="text-sm text-gray-400 text-center mb-8 max-w-xs mx-auto">Masukkan nomor resi untuk melacak pesanan Anda</p>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('tracking.search') }}" method="GET">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2.5">Nomor Resi</label>
                <div class="relative">
                    <input type="text" name="resi" value="{{ request('resi') }}"
                        class="w-full px-5 py-3.5 pl-12 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-orange-400 focus:ring-4 focus:ring-orange-100 text-sm transition-all text-center font-mono tracking-widest uppercase placeholder:tracking-normal placeholder:font-sans"
                        placeholder="Masukkan nomor resi" required autofocus>
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
            </div>
            <button type="submit"
                class="w-full bg-orange-500 text-white py-3.5 rounded-xl font-semibold text-sm hover:bg-orange-600 transition-all flex items-center justify-center gap-2 shadow-lg">
                <i class="fas fa-truck"></i> Lacak Pesanan
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-100 text-center">
            <p class="text-sm text-gray-400">Atau lihat <a href="{{ route('transactions.index') }}" class="text-orange-500 hover:text-orange-600 font-medium">semua pesanan Anda</a></p>
        </div>
    </div>

    <div class="mt-8 bg-blue-50 border border-blue-100 rounded-2xl p-6">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-info-circle text-blue-500"></i>
            </div>
            <div class="text-sm text-blue-700 leading-relaxed">
                <p class="font-semibold mb-1.5">Cara mendapatkan nomor resi</p>
                <p>Nomor resi akan diberikan oleh penjual setelah pesanan dikirim. Anda bisa melihat nomor resi di halaman <a href="{{ route('transactions.index') }}" class="text-blue-600 underline font-medium">detail pesanan</a> pada bagian Informasi Pengiriman.</p>
            </div>
        </div>
    </div>
</section>
@endsection