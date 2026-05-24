<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Backup - {{ config('app.name') }}</title>
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

                <div class="max-w-3xl mx-auto">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Backup Database</h1>
                    <p class="text-sm text-gray-500 mb-6">Kelola file backup database marketplace</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="text-center py-6">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-database text-orange-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Buat Backup Baru</h3>
                                <p class="text-sm text-gray-500 mb-6">Buat file backup database SQL yang bisa diunduh kapan saja.</p>
                                <form action="{{ route('admin.backup.run') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 text-white px-6 py-3 rounded-xl font-semibold hover:from-orange-500 hover:to-orange-600 transition-all shadow-lg hover:shadow-orange-500/30 inline-flex items-center gap-2">
                                        <i class="fas fa-play"></i> Jalankan Backup
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <div class="text-center py-6">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-info-circle text-green-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Informasi</h3>
                                <p class="text-sm text-gray-500 mb-2">Total backup: <strong>{{ count($files) }}</strong></p>
                                <p class="text-sm text-gray-500">Backup terbaru akan otomatis menimpa yang lama jika menjalankan ulang.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-800">Riwayat Backup</h3>
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{{ count($files) }} file</span>
                        </div>
                        <div class="p-6">
                            @if(count($files))
                                <div class="space-y-2">
                                    @foreach($files as $backup)
                                        <div class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition border border-gray-100">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-file-code text-orange-500"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-800">{{ $backup['filename'] }}</p>
                                                    <p class="text-xs text-gray-400">{{ $backup['created_at'] }} &middot; {{ number_format($backup['size'] / 1024, 1) }} KB</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.backup.download', $backup['filename']) }}"
                                                   class="text-blue-500 hover:text-blue-600 p-2 hover:bg-blue-50 rounded-lg transition"
                                                   title="Download"><i class="fas fa-download"></i></a>
                                                <form action="{{ route('admin.backup.delete', $backup['filename']) }}" method="POST"
                                                      onsubmit="return confirm('Hapus file backup ini?')" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-600 p-2 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10 text-gray-400">
                                    <i class="fas fa-database text-5xl mb-3"></i>
                                    <p class="font-medium">Belum ada backup</p>
                                    <p class="text-sm mt-1">Klik "Jalankan Backup" untuk membuat backup pertama</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
