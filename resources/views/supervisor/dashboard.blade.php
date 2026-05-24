<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Supervisor Dashboard - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen">
        @include('supervisor.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('supervisor.partials.header')
            <main class="flex-1 overflow-y-auto p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Supervisor</h1>
                    <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ auth()->user()->name }}!</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-blue-500">
                        <div class="flex items-center justify-between">
                            <div><p class="text-sm text-gray-500">Total Transaksi</p><p class="text-2xl font-bold text-gray-800">{{ $totalTransactions }}</p></div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-receipt text-blue-600 text-xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between">
                            <div><p class="text-sm text-gray-500">Menunggu Pembayaran</p><p class="text-2xl font-bold text-gray-800">{{ $pendingPayments }}</p></div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center"><i class="fas fa-clock text-yellow-600 text-xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-purple-500">
                        <div class="flex items-center justify-between">
                            <div><p class="text-sm text-gray-500">Diproses</p><p class="text-2xl font-bold text-gray-800">{{ $processingOrders }}</p></div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-purple-600 text-xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-5 border-l-4 border-green-500">
                        <div class="flex items-center justify-between">
                            <div><p class="text-sm text-gray-500">Selesai</p><p class="text-2xl font-bold text-gray-800">{{ $completedOrders }}</p></div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-check-circle text-green-600 text-xl"></i></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Transaksi Terbaru</h3>
                        <div class="space-y-2">
                            @foreach($recentTransactions as $t)
                                <div class="flex items-center justify-between text-sm border-b pb-2">
                                    <div><p class="font-medium">{{ $t->invoice }}</p><p class="text-gray-500 text-xs">{{ $t->user->name }}</p></div>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($t->status === 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($t->status === 'selesai') bg-green-100 text-green-700
                                        @else bg-blue-100 text-blue-700 @endif">{{ $t->status }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Penjualan Mingguan</h3>
                        <canvas id="weeklyChart" height="200"></canvas>
                    </div>
                </div>

                {{-- Flash Sale --}}
                <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-bolt text-yellow-500"></i> Produk Flash Sale</h3>
                        <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-semibold">{{ $flashSaleProducts->count() }} produk</span>
                    </div>
                    @if($flashSaleProducts->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                            @foreach($flashSaleProducts as $fp)
                                <a href="{{ route('supervisor.transactions.index') }}" class="border rounded-lg p-2 hover:shadow-md transition text-center group">
                                    @if($fp->primaryImage)
                                        <img src="{{ asset('storage/' . $fp->primaryImage->image) }}" alt="" class="w-full h-20 object-cover rounded-lg mb-2">
                                    @else
                                        <div class="w-full h-20 bg-gray-100 rounded-lg mb-2 flex items-center justify-center"><i class="fas fa-image text-gray-300"></i></div>
                                    @endif
                                    <p class="text-xs font-medium text-gray-800 truncate group-hover:text-blue-500">{{ $fp->name }}</p>
                                    <p class="text-xs font-bold text-orange-500">Rp {{ number_format($fp->final_price, 0, ',', '.') }}</p>
                                    <span class="text-[10px] bg-red-500 text-white px-1.5 py-0.5 rounded-full font-bold">-{{ $fp->discount_percentage }}%</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-400 text-center py-4">Tidak ada produk flash sale saat ini</p>
                    @endif
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                    <h3 class="font-bold text-gray-800 mb-4">Ringkasan Pendapatan</h3>
                    <p class="text-3xl font-bold text-orange-500">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-sm text-gray-500">Total pendapatan dari pesanan selesai</p>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [@foreach($weeklySales as $s) '{{ $s->day }}', @endforeach],
                datasets: [{
                    label: 'Penjualan',
                    data: [@foreach($weeklySales as $s) {{ $s->total }}, @endforeach],
                    backgroundColor: '#f97316',
                    borderRadius: 8
                }]
            }
        });
    </script>
</body>
</html>
