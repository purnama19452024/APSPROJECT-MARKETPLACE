<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pengembalian - {{ config('app.name') }}</title>
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
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4"><i class="fas fa-check-circle mr-1"></i>{{ session('success') }}</div>@endif

                <div class="max-w-5xl mx-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h1 class="text-xl font-bold text-gray-800">Detail Pengembalian</h1>
                        <a href="{{ route('supervisor.returns.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-300">Kembali</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-3">Informasi</h3>
                                <div class="flex items-center gap-3 mb-3">
                                    <img src="{{ $return->user->photo_url }}" alt="" class="w-10 h-10 rounded-full">
                                    <div>
                                        <p class="font-medium">{{ $return->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $return->user->email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between"><span class="text-gray-500">Alasan</span><span>{{ $return->reason }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Status</span><span>{{ $return->status }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Invoice</span><span>{{ $return->transaction->invoice }}</span></div>
                                </div>
                            </div>
                            <div class="bg-white rounded-xl shadow-sm p-6">
                                <h3 class="font-bold text-gray-800 mb-3">Deskripsi</h3>
                                <p class="text-sm text-gray-600">{{ $return->description }}</p>
                            </div>
                            @if($return->images && count($return->images))
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="font-bold text-gray-800 mb-3">Foto</h3>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($return->images as $img)
                                            <a href="{{ asset('storage/' . $img) }}" target="_blank"><img src="{{ asset('storage/' . $img) }}" alt="" class="w-full h-20 object-cover rounded-lg border"></a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div>
                            @if($return->status === 'pending')
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="font-bold text-gray-800 mb-4">Tanggapi</h3>
                                    <form action="{{ route('supervisor.returns.respond', $return) }}" method="POST">
                                        @csrf
                                        <select name="status" class="w-full border rounded-lg p-2 text-sm mb-3" required>
                                            <option value="approved">Setujui</option>
                                            <option value="rejected">Tolak</option>
                                        </select>
                                        <input type="number" name="refund_amount" placeholder="Jumlah refund" class="w-full border rounded-lg p-2 text-sm mb-3">
                                        <textarea name="response" rows="3" class="w-full border rounded-lg p-2 text-sm mb-3" placeholder="Tanggapan..."></textarea>
                                        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-600">Kirim</button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-white rounded-xl shadow-sm p-6">
                                    <h3 class="font-bold text-gray-800 mb-3">Tanggapan</h3>
                                    <p class="text-sm text-gray-600">{{ $return->response ?? 'Tidak ada tanggapan' }}</p>
                                    @if($return->refund_amount)
                                        <p class="text-sm mt-3">Refund: Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</p>
                                    @endif
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
