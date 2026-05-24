@extends('layouts.landing')

@section('title', $product->name)

@push('styles')
<style>
    .thumb-img { cursor: pointer; border: 2px solid transparent; transition: all 0.2s; }
    .thumb-img.active, .thumb-img:hover { border-color: #f97316; }
    .star-rating .fa-star, .star-rating .fa-star-o { cursor: pointer; }
</style>
@endpush

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-orange-500">Beranda</a>
        <span class="mx-2">/</span>
        <a href="{{ route('products.category', $product->category->slug) }}" class="hover:text-orange-500">{{ $product->category->name }}</a>
        <span class="mx-2">/</span>
        <span class="text-gray-800">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <div class="relative rounded-xl overflow-hidden bg-gray-50 mb-4">
                    @if($product->primaryImage)
                        <img id="mainImage" src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->name }}" class="w-full h-96 object-contain">
                    @else
                        <div class="w-full h-96 flex items-center justify-center"><i class="fas fa-image text-6xl text-gray-300"></i></div>
                    @endif
                    @if($product->discount_price)
                        <span class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>
                <div class="flex space-x-2 overflow-x-auto">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/' . $img->image) }}" onclick="changeImage(this, '{{ asset('storage/' . $img->image) }}')" class="thumb-img w-16 h-16 object-cover rounded-lg {{ $img->is_primary ? 'active' : '' }}">
                    @endforeach
                </div>
            </div>

            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                <div class="flex items-center space-x-4 mb-4">
                    <div class="flex items-center text-yellow-400 text-sm">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($product->rating_average) ? '' : '-o text-gray-300' }}"></i>
                        @endfor
                        <span class="text-gray-500 ml-2">{{ number_format($product->rating_average, 1) }} ({{ $product->rating_count }} ulasan)</span>
                    </div>
                    <span class="text-gray-300">|</span>
                    <span class="text-sm text-gray-500">Terjual {{ $product->rating_count }}</span>
                </div>

                <div class="bg-orange-50 rounded-xl p-4 mb-6">
                    @if($product->discount_price)
                        <div class="flex items-baseline space-x-2">
                            <span class="text-3xl font-bold text-orange-500">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                            <span class="text-lg text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full font-bold">-{{ $product->discount_percentage }}%</span>
                        </div>
                    @else
                        <span class="text-3xl font-bold text-orange-500">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                    <div>
                        <span class="text-gray-500">Kondisi: </span>
                        <span class="font-medium {{ $product->condition === 'baru' ? 'text-green-600' : 'text-orange-600' }}">{{ ucfirst($product->condition) }}</span>
                    </div>
                    <div><span class="text-gray-500">Berat: </span><span class="font-medium">{{ $product->weight }} gr</span></div>
                    <div><span class="text-gray-500">Stok: </span><span class="font-medium {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">{{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}</span></div>
                    <div><span class="text-gray-500">Kategori: </span><span class="font-medium">{{ $product->category->name }}</span></div>
                </div>

                <div class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button type="button" onclick="decrementQty()" class="px-3 py-2 text-gray-600 hover:bg-gray-100"><i class="fas fa-minus"></i></button>
                            <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $product->stock }}" class="w-16 text-center border-x border-gray-300 py-2 text-sm">
                            <button type="button" onclick="incrementQty({{ $product->stock }})" class="px-3 py-2 text-gray-600 hover:bg-gray-100"><i class="fas fa-plus"></i></button>
                        </div>
                        @auth
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="quantity" id="cartQty" value="1">
                                <button type="submit" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart mr-2"></i>Keranjang
                                </button>
                            </form>
                            <form action="{{ route('cart.buy-now', $product) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="quantity" id="buyQty" value="1">
                                <button type="submit" class="bg-red-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-red-600 transition" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-bolt mr-2"></i>Beli Sekarang
                                </button>
                            </form>
                            <a href="{{ route('wishlist.toggle', $product) }}" class="p-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-500 hover:text-red-500 transition inline-flex items-center">
                                <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart text-lg {{ $isWishlisted ? 'text-red-500' : '' }}"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="flex-1 bg-orange-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition text-center">
                                <i class="fas fa-shopping-cart mr-2"></i>Masuk untuk Membeli
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Deskripsi Produk</h3>
                <div class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-6">
                <h3 class="text-lg font-bold text-gray-800 mb-6">Ulasan Produk ({{ $product->rating_count }})</h3>

                @php
                    $ratingCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                    foreach ($product->reviews as $r) { $ratingCounts[$r->rating]++; }
                @endphp

                {{-- Summary --}}
                @if($product->rating_count > 0)
                    <div class="flex items-start gap-8 p-4 bg-gray-50 rounded-xl mb-6">
                        <div class="text-center">
                            <div class="text-4xl font-bold text-gray-800">{{ number_format($product->rating_average, 1) }}</div>
                            <div class="flex text-yellow-400 text-sm mt-1 justify-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= round($product->rating_average) ? '' : '-o text-gray-300' }}"></i>
                                @endfor
                            </div>
                            <div class="text-xs text-gray-400 mt-1">{{ $product->rating_count }} ulasan</div>
                        </div>
                        <div class="flex-1 space-y-1.5">
                            @foreach([5,4,3,2,1] as $s)
                                @php $pct = $product->rating_count > 0 ? ($ratingCounts[$s] / $product->rating_count) * 100 : 0; @endphp
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="text-gray-500 w-8 text-right">{{ $s }}</span>
                                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                                    <div class="flex-1 h-2.5 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-gray-400 text-xs w-6">{{ $ratingCounts[$s] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Reviews --}}
                @forelse($product->reviews as $review)
                    <div class="border-b border-gray-100 pb-5 mb-5 last:border-0 last:pb-0 last:mb-0">
                        <div class="flex items-start gap-3 mb-2">
                            <img src="{{ $review->user->photo_url }}" alt="" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="font-medium text-sm text-gray-800">{{ $review->user->name }}</p>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <div class="flex text-yellow-400 text-xs">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] bg-green-50 text-green-600 px-1.5 py-0.5 rounded font-medium"><i class="fas fa-check-circle mr-0.5"></i>Pembelian Terverifikasi</span>
                                </div>
                            </div>
                        </div>
                        @if($review->review)
                            <p class="text-sm text-gray-600 mt-2">{{ $review->review }}</p>
                        @endif
                        @if($review->images && count($review->images))
                            <div class="flex gap-2 mt-3">
                                @foreach($review->images as $img)
                                    <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $img) }}" alt="" class="w-16 h-16 object-cover rounded-lg border hover:opacity-80 transition">
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="far fa-comment-dots text-4xl text-gray-200 mb-3"></i>
                        <p class="text-gray-400">Belum ada ulasan untuk produk ini</p>
                        <p class="text-xs text-gray-300 mt-1">Jadilah yang pertama memberikan ulasan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Produk Terkait</h3>
                <div class="space-y-4">
                    @foreach($relatedProducts as $rp)
                        <a href="{{ route('products.show', $rp->slug) }}" class="flex space-x-3 group">
                            @if($rp->primaryImage)
                                <img src="{{ asset('storage/' . $rp->primaryImage->image) }}" alt="" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-image text-gray-300"></i></div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-800 truncate group-hover:text-orange-500">{{ $rp->name }}</h4>
                                <p class="text-sm font-bold text-orange-500">Rp {{ number_format($rp->final_price, 0, ',', '.') }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function changeImage(el, src) {
    document.querySelectorAll('.thumb-img').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('mainImage').src = src;
}
function decrementQty() {
    const qty = document.getElementById('qty');
    if(parseInt(qty.value) > 1) {
        qty.value = parseInt(qty.value) - 1;
        document.getElementById('cartQty').value = qty.value;
        document.getElementById('buyQty').value = qty.value;
    }
}
function incrementQty(max) {
    const qty = document.getElementById('qty');
    if(parseInt(qty.value) < max) {
        qty.value = parseInt(qty.value) + 1;
        document.getElementById('cartQty').value = qty.value;
        document.getElementById('buyQty').value = qty.value;
    }
}
</script>
@endpush
