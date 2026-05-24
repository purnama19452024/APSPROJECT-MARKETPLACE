@extends('layouts.landing')

@section('title', 'Saldo Saya')

@push('styles')
<style>
.credit-card {
    aspect-ratio: 1.586 / 1;
    background: #1d4ed8;
    border-radius: 16px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(29, 78, 216, 0.35);
}
.credit-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(ellipse at 20% 30%, rgba(255,255,255,0.08) 0%, transparent 50%);
    pointer-events: none;
}
.card-chip {
    width: 36px;
    height: 28px;
    background: #ea580c;
    border-radius: 4px;
    position: relative;
    box-shadow: inset 0 1px 3px rgba(255,255,255,0.3), 0 3px 8px rgba(234,88,12,0.3);
}
.card-chip::before {
    content: '';
    position: absolute;
    inset: 4px;
    border: 1.5px solid rgba(255,255,255,0.2);
    border-radius: 2px;
}
.card-chip::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 10px;
    height: 10px;
    border: 1.5px solid rgba(255,255,255,0.15);
    border-radius: 50%;
}
.card-number {
    letter-spacing: 4px;
    font-family: 'Courier New', 'Courier', monospace;
    text-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
.history-item:last-child {
    border-bottom: none !important;
}
</style>
@endpush

@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Saldo Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola saldo dan lihat riwayat transaksi</p>
        </div>
        <a href="{{ route('transactions.index') }}" class="text-sm text-orange-500 hover:text-orange-600 font-medium">
            <i class="fas fa-arrow-left mr-1"></i>Kembali
        </a>
    </div>

    {{-- Credit Card --}}
    <div class="mb-6 w-80 mx-auto md:mx-0">
        <div class="credit-card p-4 flex flex-col justify-between select-none">
            {{-- Top: chip + brand --}}
            <div class="flex items-start justify-between relative z-10">
                <div class="card-chip"></div>
                <div class="flex flex-col items-end gap-0.5">
                    <div class="flex items-center gap-1.5">
                        <div class="w-8 h-5 rounded bg-orange-500 flex items-center justify-center shadow">
                            <span class="text-white text-[8px] font-black tracking-tight">AP</span>
                        </div>
                        <span class="text-white/70 text-[8px] font-bold tracking-[1.5px]">PLATINUM</span>
                    </div>
                    <span class="text-white/40 text-[6px] tracking-[1px]">DEBIT CARD</span>
                </div>
            </div>

            {{-- Middle: card number --}}
            <div class="relative z-10">
                <div class="card-number text-white text-base md:text-lg font-bold">
                    <span class="opacity-50">••••</span>
                    <span class="opacity-50">••••</span>
                    <span class="opacity-50">••••</span>
                    <span class="opacity-90">{{ substr(auth()->user()->phone ?? '0000', -4) }}</span>
                </div>
            </div>

            {{-- Bottom: saldo, name, expiry --}}
            <div class="relative z-10 flex flex-col gap-2">
                <div>
                    <p class="text-[8px] text-white/50 font-semibold tracking-[1.2px] mb-0.5">Saldo</p>
                    <p class="text-white text-xl md:text-2xl font-bold tracking-tight drop-shadow-sm">
                        Rp {{ number_format($balance->balance, 0, ',', '.') }}
                    </p>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-[8px] text-white/50 font-semibold tracking-[1.2px] mb-0.5">Pemegang Kartu</p>
                        <p class="text-white/90 text-xs md:text-sm font-bold uppercase tracking-wider">{{ auth()->user()->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] text-white/50 font-semibold tracking-[1.2px] mb-0.5">Masa Berlaku</p>
                        <p class="text-white/90 text-xs md:text-sm font-bold">{{ now()->format('m/y') }}</p>
                    </div>
                </div>
            </div>

            {{-- Bottom-right logos --}}
            <div class="absolute bottom-3 right-4 flex items-center gap-1 z-10">
                <div class="w-7 h-4.5 bg-orange-400 rounded-[2px] shadow-sm"></div>
                <div class="w-7 h-4.5 bg-orange-500 rounded-[2px] -ml-2.5 shadow-sm"></div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-down text-blue-500"></i>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500">Total Top Up</p>
                    <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($totalTopup, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-orange-50 rounded-xl p-4 border border-orange-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-up text-orange-500"></i>
                </div>
                <div>
                    <p class="text-[11px] text-gray-500">Total Belanja</p>
                    <p class="font-bold text-gray-800 text-sm">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Top Up & Tarik Saldo --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        {{-- Top Up --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-plus-circle text-blue-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Top Up Saldo</h3>
            </div>
            <form action="{{ route('saldo.topup.store') }}" method="POST" class="p-4">
                @csrf
                <div class="grid grid-cols-2 gap-2 mb-3">
                    @foreach([10000, 25000, 50000, 100000] as $nominal)
                        <button type="button" data-amount="{{ $nominal }}"
                            class="quick-amount border border-gray-200 rounded-lg py-2 text-center hover:border-orange-400 hover:bg-orange-50 transition text-xs font-medium text-gray-600">
                            Rp{{ number_format($nominal, 0, ',', '.') }}
                        </button>
                    @endforeach
                </div>
                <div class="relative mb-2">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-xs">Rp</span>
                    <input type="number" name="amount" id="topupAmount" min="1000" max="10000000" step="1000"
                        class="w-full pl-8 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                        placeholder="Jumlah nominal">
                </div>
                <div class="flex gap-1.5 mb-3">
                    <label class="flex-1 flex items-center justify-center gap-1 p-2 border border-gray-200 rounded-lg cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-600 text-xs font-medium text-gray-500 hover:border-blue-300 transition">
                        <input type="radio" name="payment_method" value="dana" class="accent-blue-600 hidden">
                        <span style="color:#0085D0" class="text-xs font-black">D</span> DANA
                    </label>
                    <label class="flex-1 flex items-center justify-center gap-1 p-2 border border-gray-200 rounded-lg cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-600 text-xs font-medium text-gray-500 hover:border-blue-300 transition">
                        <input type="radio" name="payment_method" value="gopay" class="accent-blue-600 hidden">
                        <span style="color:#00AEE8" class="text-xs font-black">G</span> GoPay
                    </label>
                    <label class="flex-1 flex items-center justify-center gap-1 p-2 border border-gray-200 rounded-lg cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-600 text-xs font-medium text-gray-500 hover:border-blue-300 transition">
                        <input type="radio" name="payment_method" value="qris" class="accent-blue-600 hidden" checked>
                        <i class="fas fa-qrcode"></i> QRIS
                    </label>
                    <label class="flex-1 flex items-center justify-center gap-1 p-2 border border-gray-200 rounded-lg cursor-pointer has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:text-blue-600 text-xs font-medium text-gray-500 hover:border-blue-300 transition">
                        <input type="radio" name="payment_method" value="bank_transfer" class="accent-blue-600 hidden">
                        <i class="fas fa-university"></i> Bank
                    </label>
                </div>
                <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded-lg font-medium text-xs hover:bg-black transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-arrow-right text-[10px]"></i> Lanjutkan
                </button>
            </form>
        </div>

        {{-- Tarik Saldo --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                <i class="fas fa-arrow-up-from-bracket text-orange-500 text-sm"></i>
                <h3 class="font-semibold text-gray-800 text-sm">Tarik Saldo</h3>
            </div>
            <div class="p-4 flex flex-col h-full">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-university text-orange-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Saldo Tersedia</p>
                        <p class="text-lg font-bold text-gray-800">Rp {{ number_format($balance->balance, 0, ',', '.') }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mb-4 leading-relaxed">Cairkan saldo ke rekening bank Anda. Diproses admin dalam 1x24 jam.</p>
                <a href="{{ route('saldo.withdraw') }}" class="mt-auto w-full bg-gray-900 text-white py-2 rounded-lg font-medium text-xs hover:bg-black transition flex items-center justify-center gap-1.5">
                    <i class="fas fa-paper-plane text-[10px]"></i> Ajukan Penarikan
                </a>
            </div>
        </div>
    </div>

    {{-- Riwayat Transaksi --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fas fa-receipt text-gray-400 text-sm"></i> Riwayat Transaksi
            </h3>
            @if($histories->count() > 0)
                <span class="text-xs text-gray-400">{{ $histories->total() }} transaksi</span>
            @endif
        </div>
        @if($histories->count() > 0)
            @foreach($histories as $h)
                @php
                    $isInflow = in_array($h->type, ['topup', 'admin_add', 'refund']);
                    $icon = $isInflow ? 'fa-arrow-down' : 'fa-arrow-up';
                    $bgClass = $isInflow ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600';
                    $typeLabels = [
                        'topup' => 'Top Up',
                        'admin_add' => 'Tambahan Admin',
                        'admin_subtract' => 'Kurang Admin',
                        'payment' => 'Pembayaran',
                        'refund' => 'Refund',
                    ];
                @endphp
                <div class="history-item flex items-center justify-between px-5 py-3.5 border-b border-gray-50 hover:bg-gray-50/50 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs flex-shrink-0 {{ $bgClass }}">
                            <i class="fas {{ $icon }}"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-800">{{ $typeLabels[$h->type] ?? ucfirst(str_replace('_', ' ', $h->type)) }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-400">
                                <span>{{ $h->created_at->format('d M Y H:i') }}</span>
                                @if($h->description)
                                    <span class="text-gray-300">|</span>
                                    <span class="truncate max-w-[180px]">{{ $h->description }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <span class="font-semibold text-sm flex-shrink-0 ml-3 {{ $h->amount >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                        {{ $h->amount >= 0 ? '+' : '-' }}Rp {{ number_format(abs($h->amount), 0, ',', '.') }}
                    </span>
                </div>
            @endforeach
            @if($histories->hasPages())
                <div class="px-5 py-3 border-t border-gray-100">
                    {{ $histories->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12 text-gray-400">
                <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-wallet text-2xl text-gray-300"></i>
                </div>
                <p class="font-medium text-gray-500">Belum ada riwayat saldo</p>
                <p class="text-xs mt-1">Lakukan top up untuk mulai menggunakan saldo</p>
                <a href="{{ route('saldo.topup') }}" class="inline-block mt-3 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-black transition">
                    <i class="fas fa-plus-circle mr-1"></i>Top Up Sekarang
                </a>
            </div>
        @endif
    </div>
</section>
@endSection

@push('scripts')
<script>
    document.querySelectorAll('.quick-amount').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.quick-amount').forEach(b => {
                b.classList.remove('border-orange-500', 'bg-orange-50', 'text-orange-600');
                b.classList.add('border-gray-200', 'text-gray-600');
            });
            this.classList.remove('border-gray-200', 'text-gray-600');
            this.classList.add('border-orange-500', 'bg-orange-50', 'text-orange-600');
            document.getElementById('topupAmount').value = this.dataset.amount;
        });
    });
</script>
@endpush
