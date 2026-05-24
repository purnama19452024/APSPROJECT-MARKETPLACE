<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Produk - {{ config('app.name') }}</title>
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
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>@endif

                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-800">Produk</h1>
                    <a href="{{ route('admin.products.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600"><i class="fas fa-plus mr-1"></i>Tambah Produk</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">Produk</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Kategori</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Harga</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Stok</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Kondisi</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Unggulan</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Flash Sale</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Status</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="flex items-center space-x-2 hover:opacity-75 transition">
                                            @if($product->primaryImage)
                                                <img src="{{ $product->primaryImage->image_url }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                            @else
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-box text-gray-400"></i></div>
                                            @endif
                                            <span class="font-medium">{{ $product->name }}</span>
                                        </a>
                                    </td>
                                    <td class="p-3 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                                    <td class="p-3 font-medium">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="p-3">{{ $product->stock }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $product->condition == 'baru' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700' }}">
                                            {{ $product->condition }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        @if($product->is_featured)
                                            <span class="text-green-500" title="Produk Unggulan"><i class="fas fa-check-circle"></i></span>
                                        @else
                                            <span class="text-gray-300"><i class="fas fa-times-circle"></i></span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        @if($product->is_flash_sale)
                                            <span class="text-yellow-500" title="Flash Sale"><i class="fas fa-bolt"></i></span>
                                        @else
                                            <span class="text-gray-300"><i class="fas fa-minus"></i></span>
                                        @endif
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $product->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-yellow-500 hover:text-yellow-600"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus produk ini?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $products->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
