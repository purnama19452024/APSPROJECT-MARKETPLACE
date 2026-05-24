<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Banner - {{ config('app.name') }}</title>
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
                <div class="max-w-lg mx-auto bg-white rounded-xl shadow-sm p-6">
                    <h1 class="text-xl font-bold text-gray-800 mb-4">Edit Banner</h1>
                    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Judul</label>
                                <input type="text" name="title" value="{{ $banner->title }}" class="w-full border rounded-lg p-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Subjudul</label>
                                <input type="text" name="subtitle" value="{{ $banner->subtitle }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gambar</label>
                                <input type="file" name="image" class="w-full border rounded-lg p-2 text-sm">
                                @if($banner->image_url)
                                    <img src="{{ $banner->image_url }}" alt="" class="w-full h-32 mt-2 rounded-lg object-cover">
                                @endif
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tautan</label>
                                <input type="url" name="link" value="{{ $banner->link }}" class="w-full border rounded-lg p-2 text-sm" placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Urutan</label>
                                <input type="number" name="order" value="{{ $banner->order }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="is_active" value="1" {{ $banner->is_active ? 'checked' : '' }} class="rounded border-gray-300">
                                    <span class="text-sm text-gray-700">Aktif</span>
                                </label>
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-3">
                            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600">Perbarui</button>
                            <a href="{{ route('admin.banners.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">Batal</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
