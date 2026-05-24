@extends('layouts.landing')

@section('title', 'Wishlist')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-heart text-red-500 mr-2"></i>Wishlist Saya</h1>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($wishlists as $wish)
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden group relative">
                    <a href="{{ route('products.show', $wish->product->slug) }}" class="block">
                        @if($wish->product->primaryImage)
                            <img src="{{ asset('storage/' . $wish->product->primaryImage->image) }}" alt="" class="w-full h-44 object-cover">
                        @else
                            <div class="w-full h-44 bg-gray-100 flex items-center justify-center"><i class="fas fa-image text-3xl text-gray-300"></i></div>
                        @endif
                    </a>
                    <div class="p-3">
                        <h3 class="text-sm font-semibold text-gray-800 truncate">{{ $wish->product->name }}</h3>
                        <div class="flex items-center text-yellow-400 text-[10px] mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= round($wish->product->rating_average) ? '' : '-o text-gray-300' }}"></i>
                            @endfor
                            <span class="text-gray-400 ml-1">({{ $wish->product->rating_count }})</span>
                        </div>
                        <p class="text-sm font-bold text-orange-500 mt-1">Rp {{ number_format($wish->product->final_price, 0, ',', '.') }}</p>
                    </div>
                    <form action="{{ route('wishlist.toggle', $wish->product) }}" method="POST" class="absolute top-2 right-2">
                        @csrf
                        <button class="bg-white w-8 h-8 rounded-full shadow flex items-center justify-center text-red-500 hover:bg-red-50"><i class="fas fa-heart"></i></button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-2xl">
            <i class="fas fa-heart text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Wishlist Kosong</h3>
            <p class="text-gray-400 mb-6">Simpan produk favorit Anda di sini</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-orange-500 text-white px-8 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">Jelajahi Produk</a>
        </div>
    @endif
</section>
@endsection
