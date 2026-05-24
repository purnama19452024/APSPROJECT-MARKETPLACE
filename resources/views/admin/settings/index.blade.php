<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengaturan - {{ config('app.name') }}</title>
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

                <div class="max-w-2xl mx-auto">
                    <h1 class="text-xl font-bold text-gray-800 mb-4">Pengaturan Aplikasi</h1>
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="bg-white rounded-xl shadow-sm p-6 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Aplikasi</label>
                                <input type="text" name="app_name" value="{{ $settings['app_name'] ?? config('app.name') }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="app_description" rows="3" class="w-full border rounded-lg p-2 text-sm">{{ $settings['app_description'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email Admin</label>
                                <input type="email" name="admin_email" value="{{ $settings['admin_email'] ?? '' }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input type="text" name="admin_phone" value="{{ $settings['admin_phone'] ?? '' }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <textarea name="address" rows="2" class="w-full border rounded-lg p-2 text-sm">{{ $settings['address'] ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Logo</label>
                                <input type="file" name="logo" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <hr class="my-4">
                            <h4 class="font-semibold text-gray-700">Pengaturan Sosial Media</h4>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Facebook</label>
                                <input type="url" name="facebook_url" value="{{ $settings['facebook_url'] ?? '' }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Instagram</label>
                                <input type="url" name="instagram_url" value="{{ $settings['instagram_url'] ?? '' }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                                <input type="text" name="whatsapp_number" value="{{ $settings['whatsapp_number'] ?? '' }}" class="w-full border rounded-lg p-2 text-sm">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600">Simpan Pengaturan</button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
