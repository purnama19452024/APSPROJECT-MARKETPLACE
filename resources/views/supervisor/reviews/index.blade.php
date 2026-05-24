<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ulasan - {{ config('app.name') }}</title>
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

                <h1 class="text-xl font-bold text-gray-800 mb-6">Ulasan</h1>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">Produk</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Pelanggan</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Rating</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Ulasan</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Tanggal</th>
                            <th class="text-center p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @forelse($reviews as $r)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        <div class="flex items-center gap-2">
                                            @if($r->product->primaryImage)
                                                <img src="{{ asset('storage/' . $r->product->primaryImage->image) }}" alt="" class="w-10 h-10 object-cover rounded">
                                            @endif
                                            <span class="text-xs font-medium truncate max-w-[150px]">{{ $r->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3">{{ $r->user->name }}</td>
                                    <td class="p-3">
                                        <div class="flex text-yellow-400 text-xs">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= $r->rating ? '' : '-o text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="p-3 text-gray-600 max-w-[200px] truncate">{{ $r->review ?? '-' }}</td>
                                    <td class="p-3 text-gray-500 text-xs">{{ $r->created_at->format('d M Y') }}</td>
                                    <td class="p-3 text-center">
                                        <form action="{{ route('supervisor.reviews.destroy', $r) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-500 hover:text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1"><i class="fas fa-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="p-10 text-center text-gray-400">
                                    <i class="far fa-star text-4xl mb-3"></i>
                                    <p class="font-medium">Belum ada ulasan</p>
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $reviews->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
