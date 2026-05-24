<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesanan Pelanggan - {{ config('app.name') }}</title>
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
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>@endif
                @if(session('error'))<div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>@endif

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Pesanan Pelanggan</h1>
                        <p class="text-sm text-gray-500 mt-1">Kelola semua pesanan dari pelanggan</p>
                    </div>
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" placeholder="Cari invoice atau nama..." value="{{ request('search') }}"
                            class="px-4 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-orange-400 w-48">
                        <button type="submit" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200"><i class="fas fa-search"></i></button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.transactions.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200"><i class="fas fa-times"></i></a>
                        @endif
                    </form>
                </div>

                {{-- Status Filter --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    <a href="{{ route('admin.transactions.index', array_merge(request()->except('status'), ['status' => ''])) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-orange-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                        Semua <span class="ml-1 text-xs opacity-75">({{ $statusCounts['all'] }})</span>
                    </a>
                    @foreach(['pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'dalam_perjalanan' => 'Dalam Perjalanan', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $val => $label)
                        <a href="{{ route('admin.transactions.index', array_merge(request()->except('status'), ['status' => $val])) }}"
                           class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') == $val ? 'bg-orange-500 text-white shadow-md' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200' }}">
                            {{ $label }} <span class="ml-1 text-xs opacity-75">({{ $statusCounts[$val] }})</span>
                        </a>
                    @endforeach
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">Invoice</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Pembeli</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Total</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Status</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Tanggal</th>
                            <th class="text-center p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @forelse($transactions as $t)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="p-3 font-medium">{{ $t->invoice }}</td>
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $t->user->photo_url }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                            <span>{{ $t->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 font-medium">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</td>
                                    <td class="p-3">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($t->status == 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($t->status == 'diproses') bg-blue-100 text-blue-700
                                            @elseif($t->status == 'dikirim') bg-purple-100 text-purple-700
                                            @elseif($t->status == 'dalam_perjalanan') bg-pink-100 text-pink-700
                                            @elseif($t->status == 'selesai') bg-green-100 text-green-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ $t->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-500 text-xs">{{ $t->created_at->format('d M Y H:i') }}</td>
                                    <td class="p-3 text-center">
                                        <a href="{{ route('admin.transactions.show', $t) }}"
                                           class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                            <i class="fas fa-eye text-xs"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-10 text-center text-gray-400">
                                    <i class="fas fa-receipt text-4xl mb-3"></i>
                                    <p class="font-medium">Tidak ada pesanan</p>
                                    <p class="text-sm mt-1">Belum ada transaksi dengan status ini</p>
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $transactions->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
