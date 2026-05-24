<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan - Supervisor</title>
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
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-800">Laporan Transaksi (Bulan Ini)</h1>
                    <a href="{{ route('supervisor.reports.export') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-600"><i class="fas fa-download mr-1"></i>Export CSV</a>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 mb-4">
                    <p class="text-lg"><span class="text-gray-500">Total Pendapatan:</span> <span class="font-bold text-orange-500">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span></p>
                </div>
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3">Invoice</th><th class="text-left p-3">User</th>
                            <th class="text-right p-3">Total</th><th class="text-left p-3">Status</th><th class="text-left p-3">Tanggal</th>
                        </tr></thead>
                        <tbody>
                            @foreach($transactions as $t)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-medium">{{ $t->invoice }}</td>
                                    <td class="p-3">{{ $t->user->name }}</td>
                                    <td class="p-3 text-right font-medium">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                                    <td class="p-3">{{ $t->status }}</td>
                                    <td class="p-3 text-gray-500">{{ $t->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
