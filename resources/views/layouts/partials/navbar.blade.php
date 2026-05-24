<nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">
            <div class="flex items-center gap-3">
                <button id="mobileMenuBtn" class="md:hidden text-gray-600 hover:text-orange-500 p-2 -ml-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1.5 rounded-xl font-bold text-xl tracking-tight group-hover:shadow-lg group-hover:shadow-orange-500/30 transition-all">
                        <i class="fas fa-store mr-1"></i>APSPROJECT
                    </div>
                </a>
            </div>

            <div class="hidden md:flex flex-1 max-w-xl mx-6">
                <form action="{{ route('products.search') }}" method="GET" class="relative group/search w-full">
                    <input type="text" name="q" placeholder="Cari produk, kategori, brand..." value="{{ request('q') }}"
                        class="w-full pl-5 pr-12 py-2.5 bg-gray-100 border-2 border-transparent rounded-full focus:outline-none focus:border-orange-400 focus:bg-white focus:shadow-lg focus:shadow-orange-500/10 text-sm transition-all group-hover/search:border-orange-300 group-hover/search:bg-gray-50">
                    <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 bg-orange-500 text-white w-8 h-8 rounded-full hover:bg-orange-600 transition-all text-sm flex items-center justify-center hover:shadow-lg hover:shadow-orange-500/30">
                        <i class="fas fa-search text-xs"></i>
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-2 md:gap-4 text-sm">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition group/cart">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count(); @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center animate-bounce-in">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <div class="relative group/profile hidden md:block">
                        <button class="flex items-center gap-2 p-1.5 pr-3 text-gray-600 hover:text-orange-500 hover:bg-orange-50 rounded-xl transition">
                            <img src="{{ auth()->user()->photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover border-2 border-gray-200 group-hover/profile:border-orange-300 transition">
                            <span class="font-medium hidden lg:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-400 group-hover/profile:rotate-180 transition-transform"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 opacity-0 invisible group-hover/profile:opacity-100 group-hover/profile:visible transition-all duration-200 translate-y-2 group-hover/profile:translate-y-0 z-50">
                            <div class="p-4 border-b bg-gradient-to-r from-orange-50 to-transparent rounded-t-2xl">
                                <p class="font-semibold text-sm text-gray-800">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                                @php $userSaldo = auth()->user()->saldo; @endphp
                                <div class="mt-2 flex items-center gap-1.5 text-xs bg-green-50 text-green-700 px-2.5 py-1 rounded-full w-fit font-medium">
                                    <i class="fas fa-wallet"></i> Rp {{ number_format($userSaldo, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <i class="fas fa-tachometer-alt w-5 text-center text-gray-400"></i><span>Dashboard</span>
                                </a>
                                <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <i class="fas fa-receipt w-5 text-center text-gray-400"></i><span>Pesanan Saya</span>
                                </a>
                                <a href="{{ route('tracking.search') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <i class="fas fa-truck w-5 text-center text-gray-400"></i><span>Lacak Resi</span>
                                </a>
                                <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <i class="fas fa-heart w-5 text-center text-gray-400"></i><span>Wishlist</span>
                                </a>
                                <a href="{{ route('reviews.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <i class="fas fa-star w-5 text-center text-yellow-400"></i><span>Ulasan Saya</span>
                                </a>
                                <a href="{{ route('saldo.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <i class="fas fa-wallet w-5 text-center text-green-500"></i><span>Saldo Saya</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <i class="fas fa-user-cog w-5 text-center text-gray-400"></i><span>Pengaturan Akun</span>
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <div class="border-t my-1 mx-4"></div>
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-purple-600 hover:bg-purple-50 transition">
                                        <i class="fas fa-shield-alt w-5 text-center"></i><span>Panel Admin</span>
                                    </a>
                                @endif
                                @if(auth()->user()->isSupervisor())
                                    <div class="border-t my-1 mx-4"></div>
                                    <a href="{{ route('supervisor.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-blue-600 hover:bg-blue-50 transition">
                                        <i class="fas fa-clipboard-list w-5 text-center"></i><span>Panel Supervisor</span>
                                    </a>
                                @endif
                                <div class="border-t my-1 mx-4"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition w-full text-left">
                                        <i class="fas fa-sign-out-alt w-5 text-center"></i><span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="md:hidden p-2 text-gray-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition">
                        <i class="fas fa-user text-xl"></i>
                    </a>
                @else
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition">
                        <i class="fas fa-shopping-cart text-xl"></i>
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-orange-500 font-medium px-3 py-2 hover:bg-orange-50 rounded-lg transition hidden sm:inline-block">Masuk</a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-orange-400 to-orange-500 text-white px-4 py-2 rounded-xl hover:from-orange-500 hover:to-orange-600 transition-all font-medium shadow-md hover:shadow-lg hover:shadow-orange-500/30">Daftar</a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Mobile Search --}}
    <div class="md:hidden px-4 pb-3">
        <form action="{{ route('products.search') }}" method="GET" class="relative">
            <input type="text" name="q" placeholder="Cari produk..." value="{{ request('q') }}"
                class="w-full pl-4 pr-12 py-2.5 bg-gray-100 border-2 border-transparent rounded-full focus:outline-none focus:border-orange-400 focus:bg-white text-sm transition-all text-gray-600">
            <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 bg-orange-500 text-white w-7 h-7 rounded-full hover:bg-orange-600 transition-all text-xs flex items-center justify-center">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="md:hidden fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" id="mobileMenuOverlay"></div>
        <div class="absolute top-0 left-0 w-72 h-full bg-white shadow-2xl transform -translate-x-full transition-transform duration-300">
            <div class="p-4 border-b">
                <div class="flex items-center justify-between">
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1.5 rounded-xl font-bold text-lg">
                        <i class="fas fa-store mr-1"></i>APSPROJECT
                    </div>
                    <button id="mobileMenuClose" class="text-gray-400 hover:text-gray-600 p-1">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <div class="p-4">
                @auth
                    <div class="flex items-center gap-3 mb-6 p-3 bg-orange-50 rounded-xl">
                        <img src="{{ auth()->user()->photo_url }}" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-orange-200">
                        <div>
                            <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                @endauth
                <nav class="space-y-1">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                        <i class="fas fa-home w-5 text-center"></i> Beranda
                    </a>
                    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                        <i class="fas fa-store w-5 text-center"></i> Semua Produk
                    </a>
                    @auth
                        <a href="{{ route('cart.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-shopping-cart w-5 text-center"></i> Keranjang
                            @if($cartCount > 0)
                                <span class="bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full ml-auto">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-heart w-5 text-center"></i><span>Wishlist</span>
                        </a>
                        <a href="{{ route('reviews.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-star w-5 text-center"></i><span>Ulasan Saya</span>
                        </a>
                        <a href="{{ route('saldo.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-wallet w-5 text-center text-green-500"></i><span>Saldo Saya</span>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-receipt w-5 text-center"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('tracking.search') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-truck w-5 text-center"></i> Lacak Resi
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-tachometer-alt w-5 text-center"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-user-cog w-5 text-center"></i> Pengaturan Akun
                        </a>
                        <hr class="my-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 text-red-600 hover:bg-red-50 rounded-xl transition w-full">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i> Logout
                            </button>
                        </form>
                    @else
                        <hr class="my-2">
                        <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-700 hover:bg-orange-50 hover:text-orange-600 rounded-xl transition">
                            <i class="fas fa-sign-in-alt w-5 text-center"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-2.5 bg-gradient-to-r from-orange-400 to-orange-500 text-white rounded-xl transition hover:from-orange-500 hover:to-orange-600">
                            <i class="fas fa-user-plus w-5 text-center"></i> Daftar
                        </a>
                    @endauth
                </nav>
            </div>
        </div>
    </div>
</nav>

{{-- Spacer for fixed nav --}}
<div class="h-16 md:h-20"></div>

<script>
    // Mobile Menu
    const menuBtn = document.getElementById('mobileMenuBtn');
    const menu = document.getElementById('mobileMenu');
    const menuClose = document.getElementById('mobileMenuClose');
    const menuOverlay = document.getElementById('mobileMenuOverlay');
    const menuPanel = menu?.querySelector('.transform');

    if (menuBtn && menu) {
        function openMenu() {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menuPanel.classList.remove('-translate-x-full');
                document.body.style.overflow = 'hidden';
            }, 10);
        }
        function closeMenu() {
            menuPanel.classList.add('-translate-x-full');
            document.body.style.overflow = '';
            setTimeout(() => { menu.classList.add('hidden'); }, 300);
        }
        menuBtn.addEventListener('click', openMenu);
        menuClose?.addEventListener('click', closeMenu);
        menuOverlay?.addEventListener('click', closeMenu);
    }
</script>
