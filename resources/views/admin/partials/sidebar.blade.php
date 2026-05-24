<aside class="w-64 bg-gray-900 text-white flex-shrink-0 overflow-y-auto">
    <div class="p-4 border-b border-gray-800">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-2 py-1 rounded-lg font-bold text-sm">
                <i class="fas fa-store mr-1"></i>APSPROJECT
            </div>
            <span class="text-xs bg-orange-500 px-1.5 py-0.5 rounded font-medium">Admin</span>
        </a>
    </div>
    <nav class="p-3 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('admin.analytics') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.analytics') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-chart-bar w-5"></i><span>Analytics</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.users.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-users w-5"></i><span>Kelola User</span>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-tags w-5"></i><span>Kategori</span>
        </a>
        <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.products.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-box w-5"></i><span>Produk</span>
        </a>
        <a href="{{ route('admin.transactions.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.transactions.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-receipt w-5"></i><span>Pesanan Pelanggan</span>
        </a>
        <a href="{{ route('admin.complaints.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.complaints.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-exclamation-circle w-5"></i><span>Komplain</span>
        </a>
        <a href="{{ route('admin.returns.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.returns.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-undo-alt w-5"></i><span>Pengembalian</span>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.reviews.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-star w-5"></i><span>Ulasan</span>
        </a>
        <a href="{{ route('admin.saldo.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.saldo.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-wallet w-5"></i><span>Saldo</span>
            @php $pendingCount = \App\Models\WithdrawalRequest::where('status', 'pending')->count(); @endphp
            @if($pendingCount > 0 && !request()->routeIs('admin.saldo.*'))
                <span class="ml-auto bg-yellow-400 text-gray-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.banners.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.banners.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-images w-5"></i><span>Banner</span>
        </a>
        <a href="{{ route('admin.settings.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.settings.*') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-cog w-5"></i><span>Pengaturan</span>
        </a>
        <a href="{{ route('admin.backup') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.backup') ? 'bg-orange-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-database w-5"></i><span>Backup</span>
        </a>
        <div class="border-t border-gray-800 my-2"></div>
        <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800">
            <i class="fas fa-store w-5"></i><span>Ke APSPROJECT</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-red-400 hover:bg-gray-800">
                <i class="fas fa-sign-out-alt w-5"></i><span>Logout</span>
            </button>
        </form>
    </nav>
</aside>
