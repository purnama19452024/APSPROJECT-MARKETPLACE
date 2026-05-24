@extends('layouts.landing')

@section('title', 'Belanja Online Murah dan Terpercaya')

@section('content')
{{-- Hero Banner Slider --}}
<section class="relative overflow-hidden bg-gradient-to-br from-orange-500 via-red-500 to-pink-600">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-yellow-300 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10">
        <div id="heroCarousel" class="relative overflow-hidden rounded-3xl shadow-2xl hero-slider group">
            <div class="carousel-inner h-full relative">
                @forelse($banners as $key => $banner)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }} h-full absolute inset-0 transition-all duration-700 ease-in-out"
                         style="opacity: {{ $key === 0 ? '1' : '0' }}; transform: scale({{ $key === 0 ? '1' : '1.05' }});">
                        <div class="relative h-full">
                            <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent flex items-center">
                                <div class="px-8 md:px-16 lg:px-20 max-w-2xl">
                                    <div class="carousel-text">
                                        <p class="text-orange-300 text-sm md:text-base font-semibold uppercase tracking-wider mb-2 animate-fade-in-down">Promo Spesial</p>
                                        <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-3 leading-tight animate-fade-in-up">{{ $banner->title }}</h2>
                                        <p class="text-lg md:text-xl text-white/80 mb-6 animate-fade-in-up" style="animation-delay: 0.1s">{{ $banner->subtitle }}</p>
                                        @if($banner->link)
                                            <a href="{{ $banner->link }}" class="inline-flex items-center gap-2 bg-white text-orange-600 px-6 py-3 md:px-8 md:py-4 rounded-xl font-bold hover:bg-orange-50 transition-all hover:shadow-2xl hover:scale-105 animate-fade-in-up" style="animation-delay: 0.2s">
                                                Belanja Sekarang
                                                <i class="fas fa-arrow-right text-sm"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="carousel-item active h-full">
                        <div class="h-full bg-gradient-to-r from-orange-400 via-red-500 to-pink-600 flex items-center px-8 md:px-16 lg:px-20">
                            <div class="max-w-2xl">
                                <p class="text-orange-200 text-sm md:text-base font-semibold uppercase tracking-wider mb-2">Selamat Datang</p>
                                <h2 class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-3 leading-tight">Selamat Datang di APSPROJECT</h2>
                                <p class="text-lg md:text-xl text-white/80 mb-6">Temukan produk terbaik dengan harga termurah</p>
                                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-white text-orange-600 px-6 py-3 md:px-8 md:py-4 rounded-xl font-bold hover:bg-orange-50 transition-all hover:shadow-2xl hover:scale-105">
                                    Mulai Belanja
                                    <i class="fas fa-arrow-right text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <button onclick="prevSlide()" class="absolute left-3 md:left-5 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white w-10 h-10 md:w-12 md:h-12 rounded-full shadow-xl flex items-center justify-center transition-all hover:scale-110 opacity-0 group-hover:opacity-100 z-20">
                <i class="fas fa-chevron-left text-gray-700"></i>
            </button>
            <button onclick="nextSlide()" class="absolute right-3 md:right-5 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white w-10 h-10 md:w-12 md:h-12 rounded-full shadow-xl flex items-center justify-center transition-all hover:scale-110 opacity-0 group-hover:opacity-100 z-20">
                <i class="fas fa-chevron-right text-gray-700"></i>
            </button>

            <div id="heroDots" class="absolute bottom-4 md:bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
                @forelse($banners as $key => $banner)
                    <button onclick="goToSlide({{ $key }})" class="w-2 h-2 md:w-3 md:h-3 rounded-full transition-all duration-300 hero-dot {{ $key === 0 ? 'bg-white w-6 md:w-8' : 'bg-white/50 hover:bg-white/80' }}"></button>
                @empty
                    <button class="w-3 h-3 rounded-full bg-white w-8 hero-dot"></button>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- Kategori Produk --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-7 relative z-20 reveal">
    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-white/20 p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-gray-800 text-lg"><i class="fas fa-th-large text-orange-500 mr-2"></i>Kategori</h3>
            <a href="{{ route('products.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-semibold">Lihat Semua <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
        </div>
        <div class="overflow-x-auto pb-2 -mx-2 px-2 scrollbar-hide">
            <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-12 gap-2 md:gap-3 min-w-max md:min-w-0">
                @foreach($categories as $cat)
                    <a href="{{ route('products.category', $cat->slug) }}"
                       class="category-card flex flex-col items-center p-2 md:p-3 rounded-xl hover:bg-gradient-to-b hover:from-orange-50 hover:to-orange-100 transition-all duration-300 text-center group">
                        <div class="w-10 h-10 md:w-14 md:h-14 bg-gradient-to-br from-orange-100 to-orange-200 rounded-2xl flex items-center justify-center mb-2 group-hover:from-orange-400 group-hover:to-orange-500 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-sm">
                            <i class="{{ $cat->icon ?? 'fas fa-tag' }} text-orange-500 text-base md:text-xl group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <span class="text-[10px] md:text-xs text-gray-600 font-medium leading-tight group-hover:text-orange-600 transition-colors line-clamp-2">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- Flash Sale --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 reveal">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="flash-sale-timer px-4 md:px-6 py-4 md:py-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent animate-shimmer"></div>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4 relative z-10">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-xl animate-bounce-subtle">
                        <i class="fas fa-bolt text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white">Flash Sale</h2>
                        <p class="text-white/70 text-xs">Diskon spesial hari ini</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-white bg-black/20 rounded-xl px-3 py-2">
                    <i class="fas fa-clock text-yellow-300 text-sm"></i>
                    <span class="text-xs font-semibold uppercase tracking-wider">Berakhir dalam</span>
                    <div class="flex items-center gap-1 text-sm font-bold tabular-nums">
                        <span class="countdown-item bg-white/20 text-white px-2 py-1 rounded-lg min-w-[32px] text-center" id="hours">02</span>
                        <span class="text-white/60">:</span>
                        <span class="countdown-item bg-white/20 text-white px-2 py-1 rounded-lg min-w-[32px] text-center" id="minutes">00</span>
                        <span class="text-white/60">:</span>
                        <span class="countdown-item bg-white/20 text-white px-2 py-1 rounded-lg min-w-[32px] text-center animate-pulse" id="seconds">00</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('products.index') }}" class="text-white/80 hover:text-white text-sm font-semibold flex items-center gap-1 relative z-10 hover:gap-2 transition-all">
                Lihat Semua <i class="fas fa-chevron-right text-xs"></i>
            </a>
        </div>
        <div class="p-4 md:p-6 overflow-x-auto">
            <div class="flex gap-3 md:gap-4">
                @forelse($flashSaleProducts as $product)
                    <a href="{{ route('products.show', $product->slug) }}"
                       class="product-card flex-shrink-0 w-40 md:w-48 bg-white rounded-xl border border-gray-100 overflow-hidden group hover:border-orange-200">
                        <div class="relative overflow-hidden">
                            @if($product->primaryImage)
                                <img src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->name }}"
                                     class="w-full h-40 md:h-44 object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-40 md:h-44 bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center"><i class="fas fa-image text-4xl text-gray-300"></i></div>
                            @endif
                            <span class="discount-badge absolute top-2 left-2 text-white text-[10px] md:text-xs px-2 py-1 rounded-full font-bold shadow-lg animate-pulse-subtle">-{{ $product->discount_percentage }}%</span>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-3">
                                <span class="text-white text-xs font-semibold bg-white/20 backdrop-blur-sm px-3 py-1.5 rounded-lg">Lihat Detail</span>
                            </div>
                        </div>
                        <div class="p-2 md:p-3">
                            <h3 class="text-xs md:text-sm font-medium text-gray-800 truncate">{{ $product->name }}</h3>
                            <div class="mt-1 flex items-baseline gap-1">
                                <span class="text-sm md:text-base font-bold text-orange-500">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                                @if($product->discount_price)
                                    <span class="text-[10px] md:text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                            <div class="flex items-center text-yellow-400 text-[10px] mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= round($product->rating_average) ? '' : '-o text-gray-300' }}"></i>
                                @endfor
                                <span class="text-gray-400 ml-1">({{ $product->rating_count }})</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8 w-full text-gray-400">
                        <i class="fas fa-bolt text-4xl mb-3"></i>
                        <p class="font-medium">Belum ada produk flash sale</p>
                        <p class="text-sm mt-1">Nantikan promo menarik lainnya</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- Produk Unggulan --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 reveal">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                <i class="fas fa-crown text-yellow-500 mr-2"></i>Produk Unggulan
            </h2>
            <p class="text-sm text-gray-500 mt-1">Produk pilihan terbaik untuk Anda</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-orange-500 hover:text-orange-600 font-semibold text-sm flex items-center gap-1 hover:gap-2 transition-all">
            Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
        @forelse($featuredProducts as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="product-card bg-white rounded-xl border border-gray-100 overflow-hidden group hover:shadow-xl hover:border-orange-200 transition-all duration-300">
                <div class="relative overflow-hidden aspect-square">
                    @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center"><i class="fas fa-image text-5xl text-gray-300"></i></div>
                    @endif
                    @if($product->discount_price)
                        <span class="discount-badge absolute top-2 left-2 text-white text-xs px-2 py-1 rounded-full font-bold shadow-lg">-{{ $product->discount_percentage }}%</span>
                    @endif
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                        <div class="bg-white/90 backdrop-blur-sm rounded-full p-2 shadow-lg hover:bg-white">
                            <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors text-sm"></i>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                        <span class="text-white text-sm font-semibold bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl border border-white/30">Lihat Detail</span>
                    </div>
                </div>
                <div class="p-3 md:p-4">
                    <p class="text-[10px] md:text-xs text-gray-400 mb-1 uppercase tracking-wider">{{ $product->category->name ?? 'Kategori' }}</p>
                    <h3 class="text-xs md:text-sm font-semibold text-gray-800 truncate group-hover:text-orange-600 transition-colors">{{ $product->name }}</h3>
                    <div class="flex items-center mt-1.5 text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($product->rating_average) ? '' : '-o text-gray-300' }} text-[10px] md:text-xs"></i>
                        @endfor
                        <span class="text-gray-400 text-[10px] md:text-xs ml-1">({{ $product->rating_count }})</span>
                    </div>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-sm md:text-base font-bold text-orange-500">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                        @if($product->discount_price)
                            <span class="text-[10px] md:text-xs text-gray-400 line-through">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-4 text-center py-12 text-gray-400">
                <i class="fas fa-box-open text-5xl mb-3"></i>
                <p class="font-medium">Belum ada produk unggulan</p>
            </div>
        @endforelse
    </div>
</section>

{{-- Rekomendasi Harian --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 mb-12 reveal">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                <i class="fas fa-thumbs-up text-blue-500 mr-2"></i>Rekomendasi Hari Ini
            </h2>
            <p class="text-sm text-gray-500 mt-1">Produk baru yang mungkin Anda suka</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-orange-500 hover:text-orange-600 font-semibold text-sm flex items-center gap-1 hover:gap-2 transition-all">
            Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
        @forelse($newProducts as $product)
            <a href="{{ route('products.show', $product->slug) }}"
               class="product-card bg-white rounded-xl border border-gray-100 overflow-hidden group hover:shadow-xl hover:border-blue-200 transition-all duration-300">
                <div class="relative overflow-hidden aspect-square">
                    @if($product->primaryImage)
                        <img src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center"><i class="fas fa-image text-5xl text-gray-300"></i></div>
                    @endif
                    <div class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-cyan-400 text-white text-[10px] px-2 py-0.5 rounded-full font-bold shadow-lg flex items-center gap-1">
                        <i class="fas fa-sparkles text-[8px]"></i> Baru
                    </div>
                    @if($product->discount_price)
                        <span class="discount-badge absolute top-2 right-2 text-white text-xs px-2 py-0.5 rounded-full font-bold shadow-lg">-{{ $product->discount_percentage }}%</span>
                    @endif
                    <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                        <div class="bg-white/90 backdrop-blur-sm rounded-full p-2 shadow-lg hover:bg-white cursor-pointer">
                            <i class="fas fa-shopping-cart text-orange-500 text-sm"></i>
                        </div>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-4">
                        <span class="text-white text-sm font-semibold bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl border border-white/30">Cepat Lihat</span>
                    </div>
                </div>
                <div class="p-3 md:p-4">
                    <p class="text-[10px] md:text-xs text-gray-400 mb-1 uppercase tracking-wider">{{ $product->category->name ?? 'Kategori' }}</p>
                    <h3 class="text-xs md:text-sm font-semibold text-gray-800 truncate group-hover:text-blue-600 transition-colors">{{ $product->name }}</h3>
                    <div class="flex items-center mt-1.5 text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= round($product->rating_average) ? '' : '-o text-gray-300' }} text-[10px] md:text-xs"></i>
                        @endfor
                    </div>
                    <div class="mt-2">
                        <span class="text-sm md:text-base font-bold text-orange-500">Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                        @if($product->discount_price)
                            <span class="text-[10px] md:text-xs text-gray-400 line-through ml-1">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-4 text-center py-12 text-gray-400">
                <i class="fas fa-box-open text-5xl mb-3"></i>
                <p class="font-medium">Belum ada rekomendasi</p>
            </div>
        @endforelse
    </div>
</section>

{{-- Back to Top --}}
<button id="backToTop"
    class="fixed bottom-6 right-6 w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-300 hover:scale-110 hover:shadow-2xl z-50 opacity-0 invisible translate-y-4">
    <i class="fas fa-arrow-up"></i>
</button>
@endsection

@push('scripts')
<script>
    // ===== HERO CAROUSEL =====
    (function() {
        const carousel = document.getElementById('heroCarousel');
        if (!carousel) return;
        const slides = carousel.querySelectorAll('.carousel-item');
        const dots = carousel.querySelectorAll('.hero-dot');
        if (!slides.length) return;

        let current = 0;
        let interval;
        let isTransitioning = false;

        function goTo(n) {
            if (isTransitioning) return;
            isTransitioning = true;
            slides.forEach((s, i) => {
                s.style.opacity = i === n ? '1' : '0';
                s.style.transform = i === n ? 'scale(1)' : 'scale(1.05)';
                if (i === n) s.style.zIndex = '1';
                else s.style.zIndex = '0';
            });
            dots.forEach((d, i) => {
                d.className = i === n
                    ? 'hero-dot bg-white w-6 md:w-8 rounded-full transition-all duration-300'
                    : 'hero-dot bg-white/50 hover:bg-white/80 w-2 md:w-3 h-2 md:h-3 rounded-full transition-all duration-300';
            });
            current = n;
            setTimeout(() => { isTransitioning = false; }, 700);
        }

        window.goToSlide = function(n) { goTo(n); resetInterval(); };
        window.nextSlide = function() { goTo((current + 1) % slides.length); resetInterval(); };
        window.prevSlide = function() { goTo((current - 1 + slides.length) % slides.length); resetInterval(); };

        function resetInterval() {
            clearInterval(interval);
            interval = setInterval(() => goTo((current + 1) % slides.length), 6000);
        }

        goTo(0);
        interval = setInterval(() => goTo((current + 1) % slides.length), 6000);

        carousel.addEventListener('mouseenter', () => clearInterval(interval));
        carousel.addEventListener('mouseleave', () => { interval = setInterval(() => goTo((current + 1) % slides.length), 6000); });

        // Touch support
        let touchStartX = 0;
        carousel.addEventListener('touchstart', (e) => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
        carousel.addEventListener('touchend', (e) => {
            const diff = touchStartX - e.changedTouches[0].screenX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) nextSlide();
                else prevSlide();
            }
        }, { passive: true });
    })();

    // ===== COUNTDOWN TIMER =====
    (function() {
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        if (!hoursEl) return;

        let totalSeconds = 7200; // 2 hours

        function updateTimer() {
            const h = Math.floor(totalSeconds / 3600);
            const m = Math.floor((totalSeconds % 3600) / 60);
            const s = totalSeconds % 60;
            hoursEl.textContent = String(h).padStart(2, '0');
            minutesEl.textContent = String(m).padStart(2, '0');
            secondsEl.textContent = String(s).padStart(2, '0');
            if (totalSeconds > 0) totalSeconds--;
        }
        updateTimer();
        setInterval(updateTimer, 1000);
    })();

    // ===== SCROLL REVEAL =====
    (function() {
        const revealElements = document.querySelectorAll('.reveal');
        if (!revealElements.length) return;

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

        revealElements.forEach(el => observer.observe(el));
    })();

    // ===== BACK TO TOP =====
    (function() {
        const btn = document.getElementById('backToTop');
        if (!btn) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 500) {
                btn.classList.remove('opacity-0', 'invisible', 'translate-y-4');
                btn.classList.add('opacity-100', 'visible', 'translate-y-0');
            } else {
                btn.classList.add('opacity-0', 'invisible', 'translate-y-4');
                btn.classList.remove('opacity-100', 'visible', 'translate-y-0');
            }
        }, { passive: true });

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();

    // ===== NAVBAR SCROLL EFFECT =====
    (function() {
        const nav = document.querySelector('nav.fixed');
        if (!nav) return;

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        }, { passive: true });
    })();

    // ===== PROGRESS BAR ANIMATION =====
    (function() {
        document.querySelectorAll('.progress-bar').forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => { bar.style.width = width; }, 300);
        });
    })();

    // ===== CATEGORY CARDS PARALLAX TILT =====
    (function() {
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;
                card.style.transform = `perspective(300px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(300px) rotateX(0deg) rotateY(0deg)';
            });
        });
    })();

    // ===== PRODUCT CARDS HOVER GLOW =====
    (function() {
        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', x + 'px');
                card.style.setProperty('--mouse-y', y + 'px');
            });
        });
    })();
</script>
@endpush
