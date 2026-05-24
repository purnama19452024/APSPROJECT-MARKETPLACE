<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tambah Kategori - {{ config('app.name') }}</title>
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
                    <h1 class="text-xl font-bold text-gray-800 mb-4">Tambah Kategori</h1>
                    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                                <input type="text" name="name" class="w-full border rounded-lg p-2 text-sm" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Ikon</label>
                                <input type="file" name="icon" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                        </div>
                        <div class="mt-6 flex space-x-3">
                            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600">Simpan</button>
                            <a href="{{ route('admin.categories.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">Batal</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
