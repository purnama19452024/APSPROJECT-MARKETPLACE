@extends('layouts.landing')

@section('title', 'Keranjang Belanja')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-shopping-cart text-orange-500 mr-2"></i>Keranjang Belanja</h1>

    @if($cartItems->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                    <div class="bg-white rounded-xl border border-gray-100 p-4 flex items-center space-x-4">
                        @if($item->product->primaryImage)
                            <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}" alt="" class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-image text-gray-300"></i></div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-sm font-semibold text-gray-800 hover:text-orange-500 truncate block">{{ $item->product->name }}</a>
                            <p class="text-sm font-bold text-orange-500 mt-1">Rp {{ number_format($item->product->final_price, 0, ',', '.') }}</p>
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="flex items-center space-x-2 mt-2">
                                @csrf @method('PATCH')
                                <button type="button" onclick="var q = this.parentNode.querySelector('input'); if(parseInt(q.value) > 1) { q.value = parseInt(q.value) - 1; this.parentNode.submit(); }" class="px-2 py-1 border rounded hover:bg-gray-100"><i class="fas fa-minus text-xs"></i></button>
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-14 text-center border rounded text-sm py-1">
                                <button type="button" onclick="var q = this.parentNode.querySelector('input'); q.value = parseInt(q.value) + 1; this.parentNode.submit();" class="px-2 py-1 border rounded hover:bg-gray-100"><i class="fas fa-plus text-xs"></i></button>
                            </form>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            <form action="{{ route('cart.remove', $item) }}" method="POST" class="mt-2">
                                @csrf @method('DELETE')
                                <button class="text-red-500 text-sm hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-6 h-fit sticky top-24">
                <h3 class="font-bold text-gray-800 mb-4">Ringkasan Belanja</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Total Harga</span><span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    <div class="border-t pt-2 flex justify-between"><span class="font-semibold">Total</span><span class="font-bold text-orange-500 text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                </div>
                <a href="{{ route('checkout') }}" class="block w-full bg-orange-500 text-white text-center py-3 rounded-lg font-semibold hover:bg-orange-600 transition mt-6">
                    Checkout
                </a>
                <a href="{{ route('products.index') }}" class="block w-full text-center py-2 text-gray-500 hover:text-orange-500 text-sm mt-2">Lanjut Belanja</a>
            </div>
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl">
            <i class="fas fa-shopping-cart text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Keranjang Belanja Kosong</h3>
            <p class="text-gray-400 mb-6">Yuk, mulai belanja sekarang!</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-orange-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">Mulai Belanja</a>
        </div>
    @endif
</section>
@endsection
