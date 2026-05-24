<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Komplain - {{ config('app.name') }}</title>
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
                <div class="max-w-3xl mx-auto space-y-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl font-bold text-gray-800">Detail Komplain</h1>
                        <a href="{{ route('admin.complaints.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-400"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-800">{{ $complaint->subject }}</h3>
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                @if($complaint->status == 'pending') bg-yellow-100 text-yellow-700
                                @elseif($complaint->status == 'diproses') bg-blue-100 text-blue-700
                                @elseif($complaint->status == 'selesai') bg-green-100 text-green-700
                                @else bg-red-100 text-red-700 @endif">{{ $complaint->status }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                            <img src="{{ $complaint->user->photo_url }}" alt="" class="w-6 h-6 rounded-full object-cover">
                            <span>{{ $complaint->user->name }}</span>
                            <span>&middot;</span>
                            <span>{{ $complaint->created_at->format('d M Y H:i') }}</span>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $complaint->message }}</p>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Balasan</h3>
                        @if($complaint->response)
                            <div class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700 mb-4">
                                <p class="font-medium text-gray-800 mb-1">Admin:</p>
                                <p>{{ $complaint->response }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $complaint->responded_at ? $complaint->responded_at->format('d M Y H:i') : '' }}</p>
                            </div>
                        @endif
                        @if($complaint->status != 'selesai')
                            <form action="{{ route('admin.complaints.respond', $complaint) }}" method="POST">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tulis Balasan</label>
                                    <textarea name="response" rows="4" class="w-full border rounded-lg p-2 text-sm" required></textarea>
                                </div>
                                <div class="mt-3 flex space-x-3">
                                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600">Kirim Balasan</button>
                                    <button type="submit" name="mark_done" value="1" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-600">Tandai Selesai</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
