@extends('layouts.landing')

@section('title', 'Ajukan Komplain')

@section('content')
<section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('transactions.show', $transaction) }}" class="text-orange-500 hover:text-orange-600 text-sm mb-4 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Ajukan Komplain</h1>

    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <p class="text-sm text-gray-500 mb-4">Invoice: <span class="font-semibold">{{ $transaction->invoice }}</span></p>
        <form action="{{ route('complaints.store') }}" method="POST">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                <input type="text" name="subject" class="w-full border border-gray-300 rounded-lg p-2 text-sm" required placeholder="Contoh: Produk rusak / tidak sesuai">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="5" class="w-full border border-gray-300 rounded-lg p-2 text-sm" required placeholder="Jelaskan masalah Anda secara detail..."></textarea>
            </div>
            <button type="submit" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-600">Kirim Komplain</button>
        </form>
    </div>
</section>
@endsection
