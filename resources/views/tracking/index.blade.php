@extends('layouts.landing')

@section('title', 'Lacak Pesanan - ' . $transaction->invoice)

@section('content')
@php
    $statusColors = [
        'pending' => ['bg' => 'bg-yellow-400', 'text' => 'text-yellow-600', 'bgSoft' => 'bg-yellow-50', 'border' => 'border-yellow-200', 'ring' => 'ring-yellow-100'],
        'diproses' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600', 'bgSoft' => 'bg-blue-50', 'border' => 'border-blue-200', 'ring' => 'ring-blue-100'],
        'dikirim' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600', 'bgSoft' => 'bg-purple-50', 'border' => 'border-purple-200', 'ring' => 'ring-purple-100'],
        'dalam_perjalanan' => ['bg' => 'bg-pink-500', 'text' => 'text-pink-600', 'bgSoft' => 'bg-pink-50', 'border' => 'border-pink-200', 'ring' => 'ring-pink-100'],
        'selesai' => ['bg' => 'bg-green-500', 'text' => 'text-green-600', 'bgSoft' => 'bg-green-50', 'border' => 'border-green-200', 'ring' => 'ring-green-100'],
        'dibatalkan' => ['bg' => 'bg-red-500', 'text' => 'text-red-600', 'bgSoft' => 'bg-red-50', 'border' => 'border-red-200', 'ring' => 'ring-red-100'],
    ];
    $sc = $statusColors[$transaction->status] ?? $statusColors['pending'];

    $statusIcons = [
        'pending' => 'fas fa-file-invoice',
        'diproses' => 'fas fa-box',
        'dikirim' => 'fas fa-truck',
        'dalam_perjalanan' => 'fas fa-shipping-fast',
        'selesai' => 'fas fa-check-circle',
        'dibatalkan' => 'fas fa-times-circle',
    ];

    $statusLabels = [
        'pending' => 'Menunggu Konfirmasi',
        'diproses' => 'Sedang Diproses',
        'dikirim' => 'Telah Dikirim',
        'dalam_perjalanan' => 'Dalam Perjalanan',
        'selesai' => 'Pesanan Selesai',
        'dibatalkan' => 'Pesanan Dibatalkan',
    ];

    $statusDesc = [
        'pending' => 'Pesanan Anda sedang menunggu konfirmasi dari penjual',
        'diproses' => 'Penjual sedang memproses dan menyiapkan pesanan Anda',
        'dikirim' => 'Pesanan telah dikirim oleh penjual dan dalam perjalanan menuju hub',
        'dalam_perjalanan' => 'Paket Anda sedang dalam perjalanan menuju alamat tujuan',
        'selesai' => 'Pesanan telah diterima dan selesai. Terima kasih telah berbelanja!',
        'dibatalkan' => 'Pesanan ini telah dibatalkan',
    ];

    $allStatuses = ['pending', 'diproses', 'dikirim', 'dalam_perjalanan', 'selesai'];
    $currentIdx = array_search($transaction->status, $allStatuses);
    if ($currentIdx === false) $currentIdx = -1;

    $courier = strtolower($transaction->shipping_courier ?? '');
    $trackingUrls = [
        'jne' => 'https://cektracking.jne.co.id/',
        'jnt' => 'https://www.jet.co.id/tracking',
        'tiki' => 'https://www.tiki.id/id/tracking',
        'sicepat' => 'https://www.sicepat.com/tracking',
        'pos' => 'https://tracking.posindonesia.co.id/',
        'wahana' => 'https://www.wahana.com/tracking',
    ];
@endphp

<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Back --}}
    <a href="{{ route('tracking.search') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-orange-500 transition mb-6">
        <i class="fas fa-arrow-left text-xs"></i>Kembali
    </a>

    {{-- Header --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
        <div class="flex items-start justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm {{ $sc['bgSoft'] }} {{ $sc['text'] }}">
                    <i class="{{ $statusIcons[$transaction->status] ?? 'fas fa-truck' }} text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Lacak Pesanan</h1>
                    <p class="text-sm text-gray-400">Invoice: <span class="font-medium text-gray-600">{{ $transaction->invoice }}</span></p>
                    @if($transaction->shipping_receipt)
                        <p class="text-xs text-gray-400 mt-0.5">
                            Resi: <span class="font-mono font-semibold text-orange-600 tracking-wider">{{ $transaction->shipping_receipt }}</span>
                        </p>
                    @endif
                </div>
            </div>
            <span class="px-3 py-1.5 rounded-full text-xs font-semibold {{ $sc['bgSoft'] }} {{ $sc['text'] }} {{ $sc['border'] }} border whitespace-nowrap">
                {{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}
            </span>
        </div>
    </div>

    {{-- Status Banner --}}
    <div class="{{ $sc['bgSoft'] }} {{ $sc['border'] }} border rounded-2xl p-5 mb-5">
        <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 {{ $sc['bg'] }} text-white shadow-sm">
                <i class="{{ $statusIcons[$transaction->status] ?? 'fas fa-truck' }} text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-sm {{ $sc['text'] }}">{{ $statusLabels[$transaction->status] ?? ucfirst($transaction->status) }}</h3>
                <p class="text-xs {{ $sc['text'] }} opacity-80 mt-0.5">{{ $statusDesc[$transaction->status] ?? '' }}</p>
                @if($transaction->status === 'dalam_perjalanan')
                    <p class="text-xs {{ $sc['text'] }} mt-2 flex items-center gap-1.5">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="locationText">Paket sedang berada di {{ $location }}</span>
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Timeline + Shipping Info --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-5">
        {{-- Timeline --}}
        <div class="lg:col-span-3 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-800 text-sm mb-5 flex items-center gap-2">
                <i class="fas fa-clock text-gray-400 text-xs"></i>
                Riwayat Status
            </h3>
            <div class="relative">
                @foreach($allStatuses as $i => $s)
                    @php
                        $isDone = $currentIdx !== -1 && $i < $currentIdx;
                        $isCurrent = $s === $transaction->status;
                        $isFuture = $currentIdx === -1 || $i > $currentIdx;
                    @endphp
                    <div class="flex items-start gap-4 pb-7 relative last:pb-0">
                        @if(!$loop->last)
                            <div class="absolute left-[15px] top-8 w-0.5 h-[calc(100%-16px)] {{ $isDone || $isCurrent ? 'bg-orange-400' : 'bg-gray-100' }}"></div>
                        @endif
                        <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 relative z-10 text-xs
                            {{ $isDone ? 'bg-green-500 text-white shadow-sm' : ($isCurrent ? 'bg-orange-500 text-white ring-4 ' . $sc['ring'] . ' shadow-sm' : 'bg-gray-100 text-gray-300') }}">
                            @if($isDone)
                                <i class="fas fa-check text-[10px]"></i>
                            @elseif($isCurrent)
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                            @else
                                <i class="fas fa-circle text-[6px]"></i>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 pt-1">
                            <h4 class="font-medium text-sm {{ $isFuture ? 'text-gray-300' : 'text-gray-800' }}">
                                {{ $statusLabels[$s] }}
                            </h4>
                            <p class="text-xs {{ $isFuture ? 'text-gray-200' : 'text-gray-400' }}">{{ $statusDesc[$s] }}</p>
                        </div>
                        @if($isCurrent)
                            <span class="text-[10px] font-semibold {{ $sc['text'] }} {{ $sc['bgSoft'] }} px-2 py-0.5 rounded-full whitespace-nowrap flex-shrink-0 mt-1">
                                {{ $transaction->status === 'selesai' ? 'Selesai' : 'Saat Ini' }}
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="lg:col-span-2 space-y-5">
            @if($transaction->shipping_receipt)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h3 class="font-bold text-gray-800 text-xs mb-4 flex items-center gap-2 uppercase tracking-wider">
                        <i class="fas fa-truck text-gray-400 text-[10px]"></i>
                        Info Pengiriman
                    </h3>

                    {{-- Sticker --}}
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-receipt text-orange-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-wider">Resi Pengiriman</p>
                                <p class="text-xs font-bold text-gray-800">
                                    {{ strtoupper($transaction->shipping_courier ?? 'KURIR') }}
                                    <span class="font-normal text-gray-400 mx-0.5">•</span>
                                    <span class="text-orange-500 font-semibold">{{ $transaction->shipping_service ?? 'REG' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="bg-white border border-orange-200/60 rounded-lg px-4 py-3 text-center">
                            <p class="text-base font-bold text-orange-600 font-mono tracking-[0.15em]">{{ $transaction->shipping_receipt }}</p>
                            <div class="flex justify-center gap-1 mt-2">
                                @for($i = 0; $i < 12; $i++)
                                    <div class="w-1.5 h-0.5 rounded-sm {{ $i % 2 == 0 ? 'bg-gray-800' : 'bg-gray-200' }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400">Kurir</span>
                            <span class="font-medium text-gray-700 text-right">{{ strtoupper($transaction->shipping_courier ?? '-') }}</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-gray-400">Layanan</span>
                            <span class="font-medium text-gray-700">{{ $transaction->shipping_service ?? '-' }}</span>
                        </div>
                        <div class="pt-2 border-t border-gray-50">
                            <span class="text-gray-400 block mb-1">Alamat</span>
                            <p class="font-medium text-gray-700 text-xs leading-relaxed">{{ $transaction->shipping_address }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2">
                        @if(isset($trackingUrls[$courier]))
                            <a href="{{ $trackingUrls[$courier] }}" target="_blank"
                               class="bg-blue-500 text-white px-3.5 py-2 rounded-lg text-xs font-medium hover:bg-blue-600 transition inline-flex items-center justify-center gap-2">
                                <i class="fas fa-external-link-alt text-[10px]"></i> Lacak di {{ strtoupper($courier) }}
                            </a>
                        @endif
                        <button onclick="copyResi('{{ $transaction->shipping_receipt }}')"
                                class="bg-gray-50 text-gray-600 px-3.5 py-2 rounded-lg text-xs font-medium hover:bg-gray-100 transition inline-flex items-center justify-center gap-2 border border-gray-100">
                            <i class="fas fa-copy text-[10px]"></i> Salin No. Resi
                        </button>
                    </div>
                </div>
            @endif

            {{-- Payment Summary --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-bold text-gray-800 text-xs mb-4 flex items-center gap-2 uppercase tracking-wider">
                    <i class="fas fa-credit-card text-gray-400 text-[10px]"></i>
                    Pembayaran
                </h3>
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Subtotal</span>
                        <span class="text-gray-700">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Ongkos Kirim</span>
                        <span class="text-gray-700">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between pt-2.5 border-t border-gray-50">
                        <span class="font-semibold text-gray-800 text-sm">Total</span>
                        <span class="font-bold text-orange-600">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Products --}}
    @if($transaction->items && $transaction->items->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
            <h3 class="font-bold text-gray-800 text-sm mb-4 flex items-center gap-2">
                <i class="fas fa-box text-gray-400 text-xs"></i>
                Produk dalam Pesanan
                <span class="text-xs font-normal text-gray-400">({{ $transaction->items->count() }})</span>
            </h3>
            <div class="divide-y divide-gray-50">
                @foreach($transaction->items as $item)
                    <div class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
                        <img src="{{ $item->product->photo_url ?? '' }}" alt="{{ $item->product->name ?? '' }}"
                             class="w-12 h-12 rounded-xl object-cover flex-shrink-0 bg-gray-50 border border-gray-50">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $item->product->name ?? 'Produk' }}</p>
                            <p class="text-xs text-gray-400">
                                {{ $item->qty ?? $item->quantity }}x &times; Rp {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 flex-shrink-0">
                            Rp {{ number_format(($item->qty ?? $item->quantity) * $item->price, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Help --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-headset text-blue-500 text-xs"></i>
            </div>
            <div class="text-xs text-blue-700 leading-relaxed">
                <p class="font-semibold mb-1">Butuh bantuan?</p>
                <p>Jika ada kendala dengan pesanan Anda, silakan hubungi <a href="#" class="text-blue-600 underline font-medium">Customer Service</a> atau buka halaman <a href="{{ route('transactions.index') }}" class="text-blue-600 underline font-medium">detail pesanan</a> untuk informasi lebih lanjut.</p>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    @keyframes locationPulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.6; }
    }
    #locationText {
        animation: locationPulse 2s ease-in-out infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyResi(resi) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(resi).then(function() {
                var btn = event.target.closest('button');
                var orig = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check text-[10px]"></i> Tersalin!';
                btn.classList.remove('bg-gray-50', 'text-gray-600', 'hover:bg-gray-100');
                btn.classList.add('bg-green-100', 'text-green-600');
                setTimeout(function() {
                    btn.innerHTML = orig;
                    btn.classList.add('bg-gray-50', 'text-gray-600', 'hover:bg-gray-100');
                    btn.classList.remove('bg-green-100', 'text-green-600');
                }, 2000);
            });
        } else {
            alert('No. Resi: ' + resi);
        }
    }

    @if($transaction->status === 'dalam_perjalanan')
    var locations = {!! json_encode([
        'Bandung, Jawa Barat', 'Cimahi, Jawa Barat', 'Bekasi, Jawa Barat',
        'Bogor, Jawa Barat', 'Depok, Jawa Barat', 'Tangerang, Banten',
        'Cirebon, Jawa Barat', 'Purwakarta, Jawa Barat', 'Karawang, Jawa Barat',
        'Subang, Jawa Barat', 'Sumedang, Jawa Barat', 'Garut, Jawa Barat',
        'Tasikmalaya, Jawa Barat', 'Ciamis, Jawa Barat', 'Banjar, Jawa Barat',
        'Sukabumi, Jawa Barat', 'Cianjur, Jawa Barat', 'Lembang, Bandung Barat',
        'Soreang, Bandung', 'Majalaya, Bandung',
    ]) !!};
    var locEl = document.getElementById('locationText');
    if (locEl) {
        setInterval(function() {
            locEl.textContent = 'Paket sedang berada di ' + locations[Math.floor(Math.random() * locations.length)];
        }, 5000);
    }
    @endif
</script>
@endpush
@endsection