@extends('layouts.admin')

@section('title', 'Penarikan Saldo')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Penarikan Saldo</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola permintaan penarikan saldo dari user</p>
        </div>
        <a href="{{ route('admin.saldo.index') }}" class="text-sm text-gray-400 hover:text-orange-500">
            <i class="fas fa-arrow-left mr-1"></i>Kembali ke Saldo User
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-500">Menunggu</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-500">Disetujui</p>
            <p class="text-2xl font-bold text-green-500">{{ $stats['approved'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-500">Total Penarikan</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Requests List --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Daftar Permintaan</h3>
        </div>
        @if($requests->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b text-left">
                            <th class="p-3 font-semibold text-gray-600">User</th>
                            <th class="p-3 font-semibold text-gray-600">Tanggal</th>
                            <th class="p-3 font-semibold text-gray-600">Jumlah</th>
                            <th class="p-3 font-semibold text-gray-600">Rekening Tujuan</th>
                            <th class="p-3 font-semibold text-gray-600">Status</th>
                            <th class="p-3 font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $w)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($w->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium">{{ $w->user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $w->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3 text-gray-600">{{ $w->created_at->format('d M Y H:i') }}</td>
                                <td class="p-3 font-semibold">Rp {{ number_format($w->amount, 0, ',', '.') }}</td>
                                <td class="p-3">
                                    <p class="font-medium">{{ $w->bank_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $w->bank_account_number }} ({{ $w->bank_account_name }})</p>
                                </td>
                                <td class="p-3">
                                    @if($w->status == 'pending')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Menunggu</span>
                                    @elseif($w->status == 'approved')
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Disetujui</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Ditolak</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if($w->status == 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.saldo.withdrawals.approve', $w) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-green-500 text-white rounded-lg text-xs font-medium hover:bg-green-600 transition" onclick="return confirm('Setujui penarikan Rp {{ number_format($w->amount, 0, ',', '.') }}? Saldo user akan otomatis berkurang.')">
                                                    <i class="fas fa-check mr-1"></i>Setujui
                                                </button>
                                            </form>
                                            <button type="button" onclick="showRejectModal({{ $w->id }})" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-xs font-medium hover:bg-red-600 transition">
                                                <i class="fas fa-times mr-1"></i>Tolak
                                            </button>
                                        </div>
                                    @elseif($w->status == 'rejected' && $w->admin_notes)
                                        <p class="text-xs text-gray-400">Catatan: {{ $w->admin_notes }}</p>
                                    @elseif($w->processed_at)
                                        <p class="text-xs text-gray-400">{{ $w->processed_at->format('d M Y H:i') }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($requests->hasPages())
                <div class="p-5 border-t border-gray-100">
                    {{ $requests->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12 text-gray-400">
                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-arrow-up-from-bracket text-3xl text-gray-300"></i>
                </div>
                <p class="font-medium text-gray-500">Belum ada permintaan penarikan</p>
                <p class="text-xs mt-1">Permintaan penarikan dari user akan muncul di sini</p>
            </div>
        @endif
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Tolak Penarikan</h3>
        <p class="text-sm text-gray-500 mb-4">Berikan alasan penolakan (opsional)</p>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="admin_notes" rows="3" class="w-full border border-gray-200 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="Alasan penolakan..."></textarea>
            <div class="flex gap-3 mt-4">
                <button type="button" onclick="closeRejectModal()" class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm font-medium text-gray-600 hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-red-500 text-white rounded-xl text-sm font-medium hover:bg-red-600 transition">Tolak</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function showRejectModal(id) {
        document.getElementById('rejectForm').action = '{{ route("admin.saldo.withdrawals.reject", ":id") }}'.replace(':id', id);
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
    document.getElementById('rejectModal')?.addEventListener('click', function (e) {
        if (e.target === this) closeRejectModal();
    });
</script>
@endpush
@stop
