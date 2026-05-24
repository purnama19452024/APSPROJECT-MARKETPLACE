<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kategori - {{ config('app.name') }}</title>
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
                    <h1 class="text-xl font-bold text-gray-800">Kategori</h1>
                    <a href="{{ route('admin.categories.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600"><i class="fas fa-plus mr-1"></i>Tambah Kategori</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">Ikon</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Nama</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Jumlah Produk</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Status</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @foreach($categories as $category)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        @if($category->icon)
                                            <img src="{{ $category->icon }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-tag text-gray-400"></i></div>
                                        @endif
                                    </td>
                                    <td class="p-3 font-medium">{{ $category->name }}</td>
                                    <td class="p-3 text-gray-600">{{ $category->products_count ?? $category->products->count() }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-yellow-500 hover:text-yellow-600"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $categories->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
