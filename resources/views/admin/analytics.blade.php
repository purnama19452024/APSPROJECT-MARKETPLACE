<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Analytics - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen">
        @include('admin.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header')
            <main class="flex-1 overflow-y-auto p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Analytics</h1>
                    <p class="text-sm text-gray-500 mt-1">Statistik dan analisis data marketplace</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total User</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalUsers }}</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-orange-500 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-green-500 mt-3 flex items-center gap-1">
                            <i class="fas fa-arrow-up"></i> {{ $userGrowth }}% bulan ini
                        </p>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Rata-rata Transaksi</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($averageTransaction, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Produk Terjual</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSold }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-box text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4">Grafik Pertumbuhan User</h3>
                        <canvas id="userGrowthChart" height="120"></canvas>
                    </div>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4">Produk Terlaris</h3>
                        <div class="space-y-3">
                            @forelse($topProducts as $product)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center gap-2">
                                        @if($product->images->first())
                                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" alt="" class="w-8 h-8 rounded-lg object-cover">
                                        @else
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-gray-400 text-xs"></i></div>
                                        @endif
                                        <span class="font-medium text-gray-700 truncate max-w-[180px]">{{ $product->name }}</span>
                                    </div>
                                    <span class="text-gray-500 font-medium">{{ $product->sales_count ?? 0 }} terjual</span>
                                </div>
                            @empty
                                <p class="text-gray-400 text-sm text-center py-4">Belum ada produk terjual</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! $userGrowthData->pluck('month')->toJson() !!},
                    datasets: [{
                        label: 'User Baru',
                        data: {!! $userGrowthData->pluck('total')->map(fn($v) => (int) $v)->toJson() !!},
                        backgroundColor: '#f97316',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                }
            });
        });
    </script>
</body>
</html>
