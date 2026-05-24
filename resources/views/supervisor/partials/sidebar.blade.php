<aside class="w-64 bg-gray-900 text-white flex-shrink-0 overflow-y-auto">
    <div class="p-4 border-b border-gray-800">
        <a href="{{ route('supervisor.dashboard') }}" class="flex items-center space-x-2">
            <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white px-2 py-1 rounded-lg font-bold text-sm">
                <i class="fas fa-store mr-1"></i>APSPROJECT
            </div>
            <span class="text-xs bg-blue-500 px-1.5 py-0.5 rounded font-medium">Supervisor</span>
        </a>
    </div>
    <nav class="p-3 space-y-1">
        <a href="{{ route('supervisor.dashboard') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('supervisor.dashboard') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span>
        </a>
        <a href="{{ route('supervisor.analytics') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('supervisor.analytics') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-chart-bar w-5"></i><span>Analytics</span>
        </a>
        <a href="{{ route('supervisor.transactions.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('supervisor.transactions.*') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-receipt w-5"></i><span>Transaksi</span>
        </a>
        <a href="{{ route('supervisor.complaints.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('supervisor.complaints.*') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-exclamation-circle w-5"></i><span>Komplain</span>
        </a>
        <a href="{{ route('supervisor.returns.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('supervisor.returns.*') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-undo-alt w-5"></i><span>Pengembalian</span>
        </a>
        <a href="{{ route('supervisor.reviews.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('supervisor.reviews.*') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-star w-5"></i><span>Ulasan</span>
        </a>
        <a href="{{ route('supervisor.reports') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm {{ request()->routeIs('supervisor.reports') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
            <i class="fas fa-file-alt w-5"></i><span>Laporan</span>
        </a>
        <div class="border-t border-gray-800 my-2"></div>
        <a href="{{ route('home') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-gray-300 hover:bg-gray-800">
            <i class="fas fa-store w-5"></i><span>Ke APSPROJECT</span>
        </a>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-lg text-sm text-red-400 hover:bg-gray-800">
                <i class="fas fa-sign-out-alt w-5"></i><span>Logout</span>
            </button>
        </form>
    </nav>
</aside>
