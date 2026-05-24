@extends('layouts.landing')

@section('title', isset($category) ? $category->name : (isset($query) ? 'Pencarian: ' . $query : 'Semua Produk'))

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row gap-6">
        {{-- Sidebar --}}
        <div class="w-full md:w-56 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-24">
                <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-list text-gray-400 text-sm"></i> Kategori
                </h3>
                <ul class="space-y-0.5">
                    <li>
                        <a href="{{ route('products.index') }}"
                            class="flex items-center gap-2 px-3 py-2 text-sm rounded-xl transition {{ !isset($category) ? 'bg-orange-50 text-orange-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                            <i class="fas fa-th-large text-xs {{ !isset($category) ? 'text-orange-500' : 'text-gray-300' }}"></i>
                            Semua Produk
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('products.category', $cat->slug) }}"
                                class="flex items-center gap-2 px-3 py-2 text-sm rounded-xl transition {{ isset($category) && $category->id === $cat->id ? 'bg-orange-50 text-orange-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                                <i class="fas fa-folder text-xs {{ isset($category) && $category->id === $cat->id ? 'text-orange-500' : 'text-gray-300' }}"></i>
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-1 min-w-0">
            {{-- Header --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">
                            @if(isset($query))
                                Hasil Pencarian
                            @elseif(isset($category))
                                {{ $category->name }}
                            @else
                                Semua Produk
                            @endif
                        </h1>
                        <p class="text-sm text-gray-400 mt-0.5">{{ $products->total() }} produk ditemukan</p>
                    </div>
                    @if(isset($query))
                        <div class="flex items-center gap-2 bg-orange-50 border border-orange-200 rounded-xl px-4 py-2">
                            <i class="fas fa-search text-orange-400 text-sm"></i>
                            <span class="text-sm text-orange-700 font-medium">"{{ $query }}"</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Products Grid --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}"
                            class="bg-white rounded-2xl border border-gray-100 overflow-hidden group hover:shadow-lg hover:border-gray-200 transition-all duration-200">
                            <div class="relative overflow-hidden aspect-square bg-gray-50">
                                @if($product->primaryImage)
                                    <img src="{{ asset('storage/' . $product->primaryImage->image) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-gray-200"></i>
                                    </div>
                                @endif
                                @if($product->discount_price)
                                    <span class="absolute top-2 left-2 bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-sm">
                                        -{{ $product->discount_percentage }}%
                                    </span>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-all duration-200"></div>
                            </div>
                            <div class="p-3.5">
                                <p class="text-[11px] text-gray-400 mb-1 truncate">{{ $product->category->name ?? '' }}</p>
                                <h3 class="text-sm font-semibold text-gray-800 truncate group-hover:text-orange-500 transition-colors">{{ $product->name }}</h3>
                                <div class="flex items-center mt-1.5 text-[11px] text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= round($product->rating_average) ? '' : '-o text-gray-300' }}"></i>
                                    @endfor
                                    <span class="text-gray-400 ml-1">({{ $product->rating_count }})</span>
                                </div>
                                <div class="mt-2 flex items-baseline gap-1.5">
                                    <span class="text-base font-bold text-orange-500">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                                    @if($product->discount_price)
                                        <span class="text-[11px] text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif

            {{-- Empty State --}}
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box-open text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mb-1">Produk Tidak Ditemukan</h3>
                    <p class="text-sm text-gray-400 mb-6">Coba gunakan kata kunci pencarian yang berbeda</p>
                    <div class="max-w-md mx-auto">
                        <form action="{{ route('products.search') }}" method="GET" class="relative">
                            <input type="text" name="q" placeholder="Coba kata kunci lain..."
                                class="w-full pl-5 pr-12 py-3 bg-gray-100 border-2 border-transparent rounded-full focus:outline-none focus:border-orange-400 focus:bg-white text-sm transition-all">
                            <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 bg-orange-500 text-white w-8 h-8 rounded-full hover:bg-orange-600 transition-all text-xs flex items-center justify-center">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    @if(isset($query))
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <p class="text-xs text-gray-400 mb-3">Saran pencarian:</p>
                            <div class="flex flex-wrap justify-center gap-2">
                                <a href="{{ route('products.index') }}" class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 border border-transparent transition">Lihat semua produk</a>
                                @foreach($categories->take(5) as $cat)
                                    <a href="{{ route('products.category', $cat->slug) }}" class="text-xs bg-gray-100 text-gray-600 px-3 py-1.5 rounded-full hover:bg-orange-50 hover:text-orange-600 hover:border-orange-200 border border-transparent transition">{{ $cat->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
