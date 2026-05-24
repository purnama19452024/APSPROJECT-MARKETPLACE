@extends('layouts.landing')

@section('title', 'Detail Pengembalian')

@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('returns.index') }}" class="text-orange-500 hover:text-orange-600 text-sm mb-4 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pengembalian</h1>
            <p class="text-sm text-gray-500 mt-1">Invoice: #{{ $return->transaction->invoice }}</p>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-medium
            @if($return->status === 'pending') bg-yellow-100 text-yellow-700
            @elseif($return->status === 'approved') bg-blue-100 text-blue-700
            @elseif($return->status === 'rejected') bg-red-100 text-red-700
            @else bg-green-100 text-green-700 @endif">
            {{ $return->status }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-info-circle text-orange-500"></i> Informasi Pengembalian</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Alasan</span><span class="font-medium">{{ $return->reason }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Status</span><span>{{ $return->status }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Diajukan</span><span>{{ $return->created_at->format('d M Y H:i') }}</span></div>
                    @if($return->responded_at)
                        <div class="flex justify-between"><span class="text-gray-500">Ditanggapi</span><span>{{ $return->responded_at->format('d M Y H:i') }}</span></div>
                    @endif
                    @if($return->refund_amount)
                        <div class="flex justify-between border-t pt-2"><span class="font-semibold">Nilai Refund</span><span class="font-bold text-green-600">Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</span></div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-align-left text-gray-500"></i> Deskripsi</h3>
                <p class="text-sm text-gray-600">{{ $return->description }}</p>
            </div>
        </div>

        <div class="space-y-6">
            @if($return->images && count($return->images))
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-images text-blue-500"></i> Foto Barang</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($return->images as $img)
                            <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                <img src="{{ asset('storage/' . $img) }}" alt="Foto barang" class="w-full h-32 object-cover rounded-lg border hover:opacity-90 transition">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($return->response)
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-reply text-green-500"></i> Tanggapan</h3>
                    <p class="text-sm text-gray-600">{{ $return->response }}</p>
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-3">Pesanan Terkait</h3>
                <a href="{{ route('transactions.show', $return->transaction) }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium flex items-center gap-1">
                    <i class="fas fa-external-link-alt"></i> Lihat detail pesanan
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
