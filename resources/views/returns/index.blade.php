@extends('layouts.landing')

@section('title', 'Pengembalian Barang')

@section('content')
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-undo-alt text-orange-500 mr-2"></i>Pengembalian Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar permintaan return Anda</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-sm text-orange-500 hover:text-orange-600 font-medium"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Pesanan</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
    @endif

    @forelse($returns as $return)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-3 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center">
                        <i class="fas fa-undo-alt text-orange-500"></i>
                    </div>
                    <div>
                        <p class="font-medium text-sm">#{{ $return->transaction->invoice }}</p>
                        <p class="text-xs text-gray-400">{{ $return->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        @if($return->status === 'pending') bg-yellow-100 text-yellow-700
                        @elseif($return->status === 'approved') bg-blue-100 text-blue-700
                        @elseif($return->status === 'rejected') bg-red-100 text-red-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ $return->status }}
                    </span>
                    <a href="{{ route('returns.show', $return) }}" class="text-orange-500 hover:text-orange-600 text-sm"><i class="fas fa-eye"></i></a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-undo-alt text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Pengembalian</h3>
            <p class="text-gray-400 mb-6">Anda belum mengajukan pengembalian barang</p>
            <a href="{{ route('transactions.index') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">Lihat Pesanan</a>
        </div>
    @endforelse

    <div class="mt-4">{{ $returns->links() }}</div>
</section>
@endsection
