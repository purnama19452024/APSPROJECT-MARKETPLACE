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
        @include('admin.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header')
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>@endif

                <div class="max-w-5xl mx-auto">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Detail Pengembalian</h1>
                            <p class="text-sm text-gray-500 mt-1">Invoice: <strong>{{ $return->transaction->invoice }}</strong></p>
                        </div>
                        <a href="{{ route('admin.returns.index') }}" class="bg-white text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 border border-gray-200 inline-flex items-center gap-2"><i class="fas fa-arrow-left"></i> Kembali</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                <h3 class="font-bold text-gray-800 mb-3">Informasi Pelanggan</h3>
                                <div class="flex items-center gap-3 mb-3">
                                    <img src="{{ $return->user->photo_url }}" alt="" class="w-10 h-10 rounded-full object-cover">
                                    <div>
                                        <p class="font-medium">{{ $return->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $return->user->email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between"><span class="text-gray-500">Alasan</span><span>{{ $return->reason }}</span></div>
                                    <div class="flex justify-between"><span class="text-gray-500">Status</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($return->status == 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($return->status == 'approved') bg-blue-100 text-blue-700
                                            @elseif($return->status == 'rejected') bg-red-100 text-red-700
                                            @else bg-green-100 text-green-700 @endif">{{ $return->status }}</span>
                                    </div>
                                    <div class="flex justify-between"><span class="text-gray-500">Diajukan</span><span>{{ $return->created_at->format('d M Y H:i') }}</span></div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                <h3 class="font-bold text-gray-800 mb-3">Deskripsi</h3>
                                <p class="text-sm text-gray-600">{{ $return->description }}</p>
                            </div>

                            @if($return->images && count($return->images))
                                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                    <h3 class="font-bold text-gray-800 mb-3">Foto Barang</h3>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($return->images as $img)
                                            <a href="{{ asset('storage/' . $img) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $img) }}" alt="" class="w-full h-24 object-cover rounded-lg border hover:opacity-90 transition">
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-6">
                            @if($return->status === 'pending')
                                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                    <h3 class="font-bold text-gray-800 mb-4">Tanggapi Permintaan</h3>
                                    <form action="{{ route('admin.returns.respond', $return) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Keputusan</label>
                                            <select name="status" class="w-full border rounded-lg p-2 text-sm" required>
                                                <option value="approved">Setujui</option>
                                                <option value="rejected">Tolak</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Refund</label>
                                            <input type="number" name="refund_amount" placeholder="Rp" class="w-full border rounded-lg p-2 text-sm">
                                        </div>
                                        <div class="mb-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggapan</label>
                                            <textarea name="response" rows="4" class="w-full border rounded-lg p-2 text-sm" placeholder="Tulis tanggapan untuk pelanggan..."></textarea>
                                        </div>
                                        <button type="submit" class="w-full bg-orange-500 text-white py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition"><i class="fas fa-paper-plane mr-1"></i> Kirim Tanggapan</button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                    <h3 class="font-bold text-gray-800 mb-3">Tanggapan Admin</h3>
                                    @if($return->response)
                                        <p class="text-sm text-gray-600 mb-3">{{ $return->response }}</p>
                                    @else
                                        <p class="text-sm text-gray-400 italic">Tidak ada tanggapan</p>
                                    @endif
                                    <div class="text-sm text-gray-500 mt-3 pt-3 border-t">
                                        <span>Diputuskan: <strong>{{ $return->status }}</strong></span>
                                        @if($return->refund_amount)
                                            <span class="ml-4">Refund: <strong>Rp {{ number_format($return->refund_amount, 0, ',', '.') }}</strong></span>
                                        @endif
                                    </div>
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
