@extends('layouts.landing')

@section('title', 'Pesanan Saya')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-receipt text-orange-500 mr-2"></i>Pesanan Saya</h1>

    @if($transactions->count() > 0)
        <div class="space-y-4">
            @foreach($transactions as $transaction)
                <div class="bg-white rounded-xl border border-gray-100 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-sm text-gray-500">Invoice: </span>
                            <span class="text-sm font-semibold">{{ $transaction->invoice }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($transaction->status === 'pending') bg-yellow-100 text-yellow-700
                            @elseif($transaction->status === 'diproses') bg-blue-100 text-blue-700
                            @elseif($transaction->status === 'dikirim') bg-purple-100 text-purple-700
                            @elseif($transaction->status === 'dalam_perjalanan') bg-pink-100 text-pink-700
                            @elseif($transaction->status === 'selesai') bg-green-100 text-green-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600">{{ $transaction->created_at->format('d M Y H:i') }}</p>
                            <p class="font-bold text-orange-500 mt-1">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</p>
                        </div>
                        <a href="{{ route('transactions.show', $transaction) }}" class="text-orange-500 hover:text-orange-600 text-sm font-semibold">Detail <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $transactions->links() }}</div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl">
            <i class="fas fa-receipt text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Pesanan</h3>
            <p class="text-gray-400 mb-6">Ayo mulai belanja sekarang</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-orange-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">Mulai Belanja</a>
        </div>
    @endif
</section>
@endsection
