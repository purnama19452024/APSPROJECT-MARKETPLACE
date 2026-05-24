@extends('layouts.landing')

@section('title', 'Ulasan Saya')

@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-star text-yellow-400 mr-2"></i>Ulasan Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Semua ulasan yang pernah Anda berikan</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Pesanan</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif

    @forelse($reviews as $review)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-3 hover:shadow-md transition">
            <div class="flex items-start gap-4">
                @if($review->product->primaryImage)
                    <img src="{{ asset('storage/' . $review->product->primaryImage->image) }}" alt="" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                @else
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0"><i class="fas fa-image text-gray-300"></i></div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <a href="{{ route('products.show', $review->product->slug) }}" class="font-semibold text-sm text-gray-800 hover:text-orange-500">{{ $review->product->name }}</a>
                            <div class="flex text-yellow-400 text-xs mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o text-gray-300' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $review->created_at->format('d M Y') }}</span>
                    </div>
                    @if($review->review)
                        <p class="text-sm text-gray-600 mt-2">{{ $review->review }}</p>
                    @endif
                    @if($review->images && count($review->images))
                        <div class="flex gap-2 mt-2">
                            @foreach($review->images as $img)
                                <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $img) }}" alt="" class="w-12 h-12 object-cover rounded border hover:opacity-80 transition">
                                </a>
                            @endforeach
                        </div>
                    @endif
                    <div class="mt-2 flex items-center gap-3">
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-600 text-xs font-medium">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
            <i class="far fa-star text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Ulasan</h3>
            <p class="text-gray-400 mb-6">Anda belum memberikan ulasan untuk produk apapun</p>
            <a href="{{ route('transactions.index') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">Lihat Pesanan Selesai</a>
        </div>
    @endforelse

    <div class="mt-4">{{ $reviews->links() }}</div>
</section>
@endsection
