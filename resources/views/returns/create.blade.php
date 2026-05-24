@extends('layouts.landing')

@section('title', 'Ajukan Pengembalian')

@section('content')
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('transactions.show', $transaction) }}" class="text-orange-500 hover:text-orange-600 text-sm mb-4 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Pesanan</a>
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-undo-alt text-orange-500 mr-2"></i>Ajukan Pengembalian</h1>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
        <p class="text-sm text-gray-500">Invoice: <strong>{{ $transaction->invoice }}</strong></p>
        <p class="text-sm text-gray-500 mt-1">Total: <strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong></p>
    </div>

    <form action="{{ route('returns.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        @csrf
        <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Produk (opsional)</label>
            <select name="transaction_item_id" class="w-full border rounded-lg p-2 text-sm @error('transaction_item_id') border-red-400 @enderror">
                <option value="">Semua produk dalam pesanan</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ old('transaction_item_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->product->name }} ({{ $item->quantity }}x)
                    </option>
                @endforeach
            </select>
            @error('transaction_item_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Pengembalian</label>
            <select name="reason" class="w-full border rounded-lg p-2 text-sm @error('reason') border-red-400 @enderror" required>
                <option value="">Pilih alasan</option>
                <option value="Produk cacat/rusak" {{ old('reason') == 'Produk cacat/rusak' ? 'selected' : '' }}>Produk cacat/rusak</option>
                <option value="Produk tidak sesuai pesanan" {{ old('reason') == 'Produk tidak sesuai pesanan' ? 'selected' : '' }}>Produk tidak sesuai pesanan</option>
                <option value="Produk berbeda dengan foto" {{ old('reason') == 'Produk berbeda dengan foto' ? 'selected' : '' }}>Produk berbeda dengan foto</option>
                <option value="Ukuran tidak sesuai" {{ old('reason') == 'Ukuran tidak sesuai' ? 'selected' : '' }}>Ukuran tidak sesuai</option>
                <option value="Barang sudah tidak berfungsi" {{ old('reason') == 'Barang sudah tidak berfungsi' ? 'selected' : '' }}>Barang sudah tidak berfungsi</option>
                <option value="Lainnya" {{ old('reason') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>
            @error('reason')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="4" class="w-full border rounded-lg p-2 text-sm @error('description') border-red-400 @enderror" required placeholder="Jelaskan alasan pengembalian secara detail...">{{ old('description') }}</textarea>
            @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Barang (maks 5 foto)</label>
            <input type="file" name="images[]" multiple accept="image/*" class="w-full border rounded-lg p-2 text-sm @error('images') border-red-400 @enderror">
            <p class="text-xs text-gray-400 mt-1">Upload foto barang yang akan dikembalikan untuk memperkuat alasan</p>
            @error('images')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
            @error('images.*')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-xl font-semibold hover:bg-orange-600 transition"><i class="fas fa-paper-plane mr-2"></i>Ajukan Pengembalian</button>
    </form>
</section>
@endsection
