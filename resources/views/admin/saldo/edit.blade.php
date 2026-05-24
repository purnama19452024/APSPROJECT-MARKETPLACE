@extends('admin.layouts.app')

@section('title', 'Atur Saldo - ' . $user->name)

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.saldo.index') }}" class="text-orange-500 hover:text-orange-600 text-sm mb-2 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
    <h1 class="text-2xl font-bold text-gray-800">Atur Saldo: {{ $user->name }}</h1>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Informasi User</h3>
        <div class="flex items-center gap-4 mb-4">
            <img src="{{ $user->photo_url }}" alt="" class="w-16 h-16 rounded-full object-cover">
            <div>
                <p class="font-semibold text-lg">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
            <p class="text-sm text-green-600 font-medium">Saldo Saat Ini</p>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($balance->balance, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="font-bold text-gray-800 mb-4">Atur Saldo</h3>
        <form action="{{ route('admin.saldo.update', $user) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tindakan</label>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        <input type="radio" name="action" value="add" class="text-blue-500" checked>
                        <div><p class="font-medium text-sm">Tambah Saldo</p><p class="text-xs text-gray-500">Menambahkan saldo ke user</p></div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-red-50 transition">
                        <input type="radio" name="action" value="subtract" class="text-red-500">
                        <div><p class="font-medium text-sm">Kurangi Saldo</p><p class="text-xs text-gray-500">Mengurangi saldo user</p></div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-green-50 transition">
                        <input type="radio" name="action" value="set" class="text-green-500">
                        <div><p class="font-medium text-sm">Set Saldo</p><p class="text-xs text-gray-500">Menentukan jumlah saldo secara langsung</p></div>
                    </label>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-medium">Rp</span>
                    <input type="number" name="amount" min="0" step="500" class="w-full border border-gray-300 rounded-lg pl-10 pr-3 py-2.5 text-sm" placeholder="0" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan (opsional)</label>
                <input type="text" name="description" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm" placeholder="Alasan penambahan/pengurangan saldo...">
            </div>
            <button type="submit" class="w-full bg-orange-500 text-white py-2.5 rounded-lg font-semibold hover:bg-orange-600 transition">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </form>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm p-6 mt-6">
    <h3 class="font-bold text-gray-800 mb-4">Riwayat Saldo</h3>
    @if($histories->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-4 py-3 font-semibold text-gray-600">Tanggal</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Tipe</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Jumlah</th>
                        <th class="px-4 py-3 font-semibold text-gray-600">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($histories as $h)
                        <tr>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $h->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                    @if(in_array($h->type, ['topup', 'admin_add', 'refund'])) bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $h->type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium {{ $h->amount >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $h->amount >= 0 ? '+' : '' }}Rp {{ number_format(abs($h->amount), 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $h->description ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-sm text-gray-400 text-center py-8">Belum ada riwayat saldo</p>
    @endif
</div>
@endsection
