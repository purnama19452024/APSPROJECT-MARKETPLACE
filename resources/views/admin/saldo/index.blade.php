@extends('admin.layouts.app')

@section('title', 'Kelola Saldo')

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-500 font-medium">Total User</p>
            <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-500 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-500 font-medium">Total Saldo</p>
            <div class="w-8 h-8 bg-green-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-wallet text-green-500 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-green-600">Rp {{ number_format($stats['total_saldo'], 0, ',', '.') }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-500 font-medium">Penarikan Menunggu</p>
            <div class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-yellow-500">{{ $stats['pending_withdrawals'] }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <p class="text-xs text-gray-500 font-medium">Total Cair</p>
            <div class="w-8 h-8 bg-orange-50 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-up-from-bracket text-orange-500 text-sm"></i>
            </div>
        </div>
        <p class="text-2xl font-bold text-orange-500">Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}</p>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 text-sm"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 flex items-center gap-2 text-sm"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
@endif

{{-- Pending Withdrawals --}}
@if($pendingRequests->count() > 0)
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-6">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-arrow-up-from-bracket text-yellow-500 text-sm"></i>
            Permintaan Penarikan Baru
            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-0.5 rounded-full font-medium">{{ $stats['pending_withdrawals'] }}</span>
        </h3>
        <a href="{{ route('admin.saldo.withdrawals') }}" class="text-xs text-orange-500 hover:text-orange-600 font-medium">Lihat Semua</a>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($pendingRequests as $w)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50/50 transition">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 bg-gray-100 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0">
                        {{ substr($w->user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $w->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $w->bank_name }} - {{ $w->bank_account_number }}</p>
                        <p class="text-[11px] text-gray-300">{{ $w->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0 ml-3">
                    <span class="font-semibold text-sm text-gray-800">Rp {{ number_format($w->amount, 0, ',', '.') }}</span>
                    <form action="{{ route('admin.saldo.withdrawals.approve', $w) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="w-8 h-8 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition flex items-center justify-center" title="Setujui">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                    </form>
                    <button type="button" onclick="showRejectModal({{ $w->id }})" class="w-8 h-8 bg-red-100 text-red-500 rounded-lg hover:bg-red-200 transition flex items-center justify-center" title="Tolak">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- User Saldo Table --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-wallet text-gray-400 text-sm"></i>
            Daftar Saldo User
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 font-semibold text-gray-600">User</th>
                    <th class="px-5 py-3 font-semibold text-gray-600">Saldo</th>
                    <th class="px-5 py-3 font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->photo_url }}" alt="" class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-800 text-sm truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-semibold text-green-600">Rp {{ number_format($user->saldo, 0, ',', '.') }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.saldo.edit', $user) }}"
                               class="bg-orange-500 text-white px-3.5 py-1.5 rounded-lg text-xs font-medium hover:bg-orange-600 transition inline-flex items-center gap-1.5 shadow-sm">
                                <i class="fas fa-wallet text-[10px]"></i> Atur Saldo
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-5 py-12 text-center text-gray-400">Tidak ada user</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
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
@endsection