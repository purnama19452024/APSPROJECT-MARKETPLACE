<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengembalian Barang - {{ config('app.name') }}</title>
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
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>@endif

                <h1 class="text-xl font-bold text-gray-800 mb-6">Pengembalian Barang</h1>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">Pelanggan</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Invoice</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Alasan</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Status</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Tanggal</th>
                            <th class="text-center p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @forelse($returns as $r)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">{{ $r->user->name }}</td>
                                    <td class="p-3 font-medium">{{ $r->transaction->invoice }}</td>
                                    <td class="p-3 text-gray-600 max-w-[200px] truncate">{{ $r->reason }}</td>
                                    <td class="p-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($r->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($r->status == 'approved') bg-blue-100 text-blue-700
                                        @elseif($r->status == 'rejected') bg-red-100 text-red-700
                                        @else bg-green-100 text-green-700 @endif">{{ $r->status }}</span></td>
                                    <td class="p-3 text-gray-500 text-xs">{{ $r->created_at->format('d M Y') }}</td>
                                    <td class="p-3 text-center"><a href="{{ route('supervisor.returns.show', $r) }}" class="text-blue-500 hover:text-blue-600"><i class="fas fa-eye"></i></a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-10 text-center text-gray-400">
                                    <i class="fas fa-undo-alt text-4xl mb-3"></i><p class="font-medium">Belum ada pengembalian</p>
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $returns->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
