<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Komplain - Supervisor</title>
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
                <a href="{{ route('supervisor.complaints.index') }}" class="text-blue-500 hover:text-blue-600 text-sm mb-4 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Detail Komplain</h3>
                        <div class="space-y-3 text-sm">
                            <p><span class="text-gray-500">User:</span> {{ $complaint->user->name }}</p>
                            <p><span class="text-gray-500">Invoice:</span> {{ $complaint->transaction->invoice }}</p>
                            <p><span class="text-gray-500">Subjek:</span> <span class="font-semibold">{{ $complaint->subject }}</span></p>
                            <p><span class="text-gray-500">Status:</span> <span class="font-semibold">{{ ucfirst($complaint->status) }}</span></p>
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <p class="font-medium mb-2">Deskripsi:</p>
                                <p class="text-gray-600">{{ $complaint->description }}</p>
                            </div>
                            @if($complaint->response)
                                <div class="mt-4 p-4 bg-green-50 rounded-lg">
                                    <p class="font-medium mb-2 text-green-700">Respon:</p>
                                    <p class="text-green-600">{{ $complaint->response }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($complaint->status !== 'selesai')
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Beri Tanggapan</h3>
                            <form action="{{ route('supervisor.complaints.respond', $complaint) }}" method="POST">
                                @csrf
                                <textarea name="response" rows="5" class="w-full border rounded-lg p-3 text-sm mb-3" placeholder="Tulis tanggapan..." required></textarea>
                                <select name="status" class="w-full border rounded-lg p-2 text-sm mb-3">
                                    <option value="diproses">Diproses</option>
                                    <option value="selesai">Selesai</option>
                                    <option value="ditolak">Ditolak</option>
                                </select>
                                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg font-semibold hover:bg-blue-600">Kirim Tanggapan</button>
                            </form>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>
