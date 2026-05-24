<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Pesanan</p>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Transaction::where('user_id', auth()->id())->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-receipt text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Wishlist</p>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Wishlist::where('user_id', auth()->id())->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-heart text-red-600 text-xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Keranjang</p>
                            <p class="text-2xl font-bold text-gray-800">{{ \App\Models\Cart::where('user_id', auth()->id())->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center gap-5">
                    <div class="relative group cursor-pointer" onclick="openImagePreview('{{ auth()->user()->photo_url }}', '{{ auth()->user()->name }}')">
                        <img src="{{ auth()->user()->photo_url }}" alt=""
                             class="w-20 h-20 rounded-2xl object-cover border-4 border-orange-100 group-hover:border-orange-300 transition shadow-sm">
                        <div class="absolute inset-0 rounded-2xl bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                            <i class="fas fa-search-plus text-white text-lg"></i>
                        </div>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-gray-800 text-lg">Selamat Datang, {{ auth()->user()->name }}!</h3>
                        <p class="text-gray-500 text-sm">Anda login sebagai <span class="font-semibold text-orange-600">{{ auth()->user()->role->name ?? 'User' }}</span>.</p>
                    </div>
                </div>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="bg-orange-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition"><i class="fas fa-shopping-bag mr-2"></i>Belanja Sekarang</a>
                    <a href="{{ route('transactions.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition"><i class="fas fa-receipt mr-2"></i>Pesanan Saya</a>
                    <a href="{{ route('profile.edit') }}" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg font-semibold hover:bg-gray-300 transition"><i class="fas fa-user mr-2"></i>Profil Saya</a>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-purple-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-purple-600 transition"><i class="fas fa-shield-alt mr-2"></i>Panel Admin</a>
                    @endif
                    @if(auth()->user()->isSupervisor())
                        <a href="{{ route('supervisor.dashboard') }}" class="bg-blue-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-600 transition"><i class="fas fa-clipboard-list mr-2"></i>Panel Supervisor</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Image Preview Modal --}}
    <div id="imagePreviewModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/70 backdrop-blur-sm" onclick="closeImagePreview(event)">
        <div class="relative max-w-lg mx-4" onclick="event.stopPropagation()">
            <button onclick="closeImagePreview()" class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-100 transition z-10">
                <i class="fas fa-times text-gray-600"></i>
            </button>
            <img id="previewImage" src="" alt="" class="w-full rounded-2xl shadow-2xl border-4 border-white">
            <p id="previewCaption" class="text-white text-sm text-center mt-3 font-medium"></p>
        </div>
    </div>

    @push('scripts')
    <script>
        function openImagePreview(src, name) {
            document.getElementById('previewImage').src = src;
            document.getElementById('previewCaption').textContent = name || '';
            var modal = document.getElementById('imagePreviewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function closeImagePreview(e) {
            if (e && e.target !== e.currentTarget) return;
            var modal = document.getElementById('imagePreviewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    </script>
    @endpush
</x-app-layout>