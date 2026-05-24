<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Banner - {{ config('app.name') }}</title>
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
                    <h1 class="text-xl font-bold text-gray-800">Banner</h1>
                    <a href="{{ route('admin.banners.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600"><i class="fas fa-plus mr-1"></i>Tambah Banner</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">Gambar</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Judul</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Tautan</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Urutan</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Status</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @foreach($banners as $banner)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        <img src="{{ $banner->image_url }}" alt="" class="w-24 h-14 rounded-lg object-cover">
                                    </td>
                                    <td class="p-3 font-medium">{{ $banner->title }}</td>
                                    <td class="p-3 text-gray-500 text-xs">{{ $banner->link ?? '-' }}</td>
                                    <td class="p-3 text-center">{{ $banner->order }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $banner->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.banners.edit', $banner) }}" class="text-yellow-500 hover:text-yellow-600"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Hapus banner ini?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $banners->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
