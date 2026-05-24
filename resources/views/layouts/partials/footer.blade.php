<footer class="bg-gray-900 text-white mt-16 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-20 w-64 h-64 bg-orange-500 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-red-500 rounded-full blur-3xl"></div>
    </div>

    {{-- Newsletter --}}
    <div class="relative z-10 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-orange-500/20 p-3 rounded-xl hidden md:block">
                        <i class="fas fa-envelope-open-text text-orange-400 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Dapatkan Info Promo Terbaru</h3>
                        <p class="text-gray-400 text-sm">Berlangganan dan dapatkan diskon eksklusif!</p>
                    </div>
                </div>
                <form class="flex w-full md:w-auto gap-2" onsubmit="alert('Terima kasih telah berlangganan!'); return false;">
                    <input type="email" placeholder="Masukkan email Anda"
                        class="flex-1 md:w-72 px-4 py-3 bg-gray-800 border border-gray-700 rounded-xl focus:outline-none focus:border-orange-500 text-sm text-white placeholder-gray-500 transition">
                    <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 text-white px-5 py-3 rounded-xl hover:from-orange-500 hover:to-orange-600 transition-all font-semibold text-sm shadow-lg hover:shadow-orange-500/30 whitespace-nowrap">
                        <i class="fas fa-paper-plane mr-1"></i> Kirim
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14 relative z-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
            <div>
                <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-3 py-1.5 rounded-xl font-bold text-lg inline-block mb-5">
                    <i class="fas fa-store mr-1"></i>APSPROJECT
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-5">Platform jual beli online terpercaya dengan produk berkualitas dan harga terbaik. Belanja aman, mudah, dan menyenangkan.</p>
                <div class="flex gap-2">
                    <a href="#" class="w-9 h-9 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-orange-500 transition-all hover:scale-110 hover:shadow-lg hover:shadow-orange-500/30"><i class="fab fa-facebook-f text-sm"></i></a>
                    <a href="#" class="w-9 h-9 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-orange-500 transition-all hover:scale-110 hover:shadow-lg hover:shadow-orange-500/30"><i class="fab fa-twitter text-sm"></i></a>
                    <a href="#" class="w-9 h-9 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-orange-500 transition-all hover:scale-110 hover:shadow-lg hover:shadow-orange-500/30"><i class="fab fa-instagram text-sm"></i></a>
                    <a href="#" class="w-9 h-9 bg-gray-800 rounded-xl flex items-center justify-center hover:bg-orange-500 transition-all hover:scale-110 hover:shadow-lg hover:shadow-orange-500/30"><i class="fab fa-youtube text-sm"></i></a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-5 flex items-center gap-2">
                    <i class="fas fa-th-large text-orange-400 text-sm"></i> Kategori
                </h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    @php $footerCategories = \App\Models\Category::where('is_active', true)->take(6)->get(); @endphp
                    @foreach($footerCategories as $cat)
                        <li><a href="{{ route('products.category', $cat->slug) }}" class="hover:text-orange-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-gray-600"></i>{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-5 flex items-center gap-2">
                    <i class="fas fa-headset text-orange-400 text-sm"></i> Layanan
                </h4>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-orange-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-gray-600"></i>Pusat Bantuan</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-gray-600"></i>Syarat & Ketentuan</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-gray-600"></i>Kebijakan Privasi</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-gray-600"></i>Pengembalian Barang</a></li>
                    <li><a href="#" class="hover:text-orange-400 transition flex items-center gap-2"><i class="fas fa-chevron-right text-[8px] text-gray-600"></i>Cara Berbelanja</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-lg mb-5 flex items-center gap-2">
                    <i class="fas fa-phone-alt text-orange-400 text-sm"></i> Hubungi Kami
                </h4>
                <ul class="space-y-3.5 text-sm text-gray-400">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-orange-400 mt-0.5"></i>
                        <span>Jl. Contoh No. 123, Bandung Kota, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-phone text-orange-400"></i>
                        <span>021-1234-5678</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-envelope text-orange-400"></i>
                        <span>support@marketplace.test</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fas fa-clock text-orange-400"></i>
                        <span>Sen - Sab: 08:00 - 20:00</span>
                    </li>
                </ul>
                <div class="mt-6">
                    <p class="text-sm text-gray-400 mb-3 font-medium">Metode Pembayaran</p>
                    <div class="flex gap-3 text-2xl text-gray-600">
                        <i class="fab fa-cc-visa hover:text-orange-400 transition"></i>
                        <i class="fab fa-cc-mastercard hover:text-orange-400 transition"></i>
                        <i class="fab fa-cc-paypal hover:text-orange-400 transition"></i>
                        <i class="fab fa-cc-stripe hover:text-orange-400 transition"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-800 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-gray-500">
            <p>&copy; {{ date('Y') }} APSPROJECT. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-orange-400 transition">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-orange-400 transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-orange-400 transition">FAQ</a>
            </div>
        </div>
    </div>
</footer>
