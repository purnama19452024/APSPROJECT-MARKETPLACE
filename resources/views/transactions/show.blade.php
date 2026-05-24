@extends('layouts.landing')

@section('title', 'Detail Pesanan')

@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('transactions.index') }}" class="text-orange-500 hover:text-orange-600 text-sm mb-2 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
        </div>
        @if($transaction->status !== 'dibatalkan')
            <a href="{{ route('tracking.show', $transaction) }}"
               class="bg-orange-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-orange-600 transition inline-flex items-center gap-2 shadow-lg">
                <i class="fas fa-truck"></i> Lacak Pesanan
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <span class="text-sm text-gray-500">Invoice: </span>
                <span class="font-semibold">{{ $transaction->invoice }}</span>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold
                @if($transaction->status === 'pending') bg-yellow-100 text-yellow-700
                @elseif($transaction->status === 'diproses') bg-blue-100 text-blue-700
                @elseif($transaction->status === 'dikirim') bg-purple-100 text-purple-700
                @elseif($transaction->status === 'dalam_perjalanan') bg-pink-100 text-pink-700
                @elseif($transaction->status === 'selesai') bg-green-100 text-green-700
                @else bg-red-100 text-red-700 @endif">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>
        <p class="text-sm text-gray-500">Tanggal: {{ $transaction->created_at->format('d M Y H:i') }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="font-bold text-gray-800 mb-4">Produk</h3>
        <div class="space-y-3">
            @foreach($transaction->items as $item)
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        @if($item->product->primaryImage)
                            <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}" alt="" class="w-12 h-12 object-cover rounded-lg">
                        @endif
                        <div>
                            <p class="text-sm font-medium">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="font-bold text-gray-800 mb-4">Informasi Pengiriman</h3>
        <div class="text-sm space-y-2">
            <p><span class="text-gray-500">Alamat:</span> {{ $transaction->shipping_address }}</p>
            <p><span class="text-gray-500">Kurir:</span> {{ $transaction->shipping_courier }}</p>
            @if($transaction->shipping_receipt)
                <div class="mt-4 bg-orange-50 border border-orange-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-receipt text-orange-500 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase tracking-wider">Resi Pengiriman</p>
                            <p class="text-xs font-bold text-gray-800">
                                {{ strtoupper($transaction->shipping_courier) }}
                                <span class="font-normal text-gray-400 mx-0.5">•</span>
                                <span class="text-orange-500 font-semibold">{{ $transaction->shipping_service ?? 'REG' }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="bg-white border border-orange-200/60 rounded-lg px-4 py-3 text-center">
                        <p class="text-lg font-bold text-orange-600 font-mono tracking-[0.15em]">{{ $transaction->shipping_receipt }}</p>
                        <div class="flex justify-center gap-1 mt-2">
                            @for($i = 0; $i < 12; $i++)
                                <div class="w-1.5 h-0.5 rounded-sm {{ $i % 2 == 0 ? 'bg-gray-800' : 'bg-gray-200' }}"></div>
                            @endfor
                        </div>
                    </div>
                    <button onclick="copyResi('{{ $transaction->shipping_receipt }}')"
                            class="mt-3 w-full bg-white text-gray-600 px-3 py-2 rounded-lg text-xs font-medium hover:bg-gray-50 transition inline-flex items-center justify-center gap-2 border border-gray-100">
                        <i class="fas fa-copy text-[10px]"></i> Salin No. Resi
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
        <h3 class="font-bold text-gray-800 mb-4">Rincian Pembayaran</h3>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-gray-500">Metode Pembayaran</span>
                <span class="font-medium flex items-center gap-1.5">
                    @if($transaction->payment_method === 'cod')
                        <i class="fas fa-money-bill-wave text-green-500"></i> COD (Bayar di Tempat)
                    @elseif($transaction->payment_method === 'qris')
                        <i class="fas fa-qrcode text-purple-500"></i> QRIS
                    @elseif($transaction->payment_method === 'saldo')
                        <i class="fas fa-wallet text-yellow-500"></i> Saldo APSPROJECT
                    @else
                        <i class="fas fa-university text-blue-500"></i> Transfer Bank
                    @endif
                </span>
            </div>
            <div class="flex justify-between"><span class="text-gray-500">Total Produk</span><span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span class="text-gray-500">Ongkos Kirim</span><span>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span></div>
            <div class="border-t pt-2 flex justify-between"><span class="font-semibold">Grand Total</span><span class="font-bold text-orange-500 text-lg">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span></div>
        </div>
    </div>

    @if($transaction->status === 'pending')
        @if($transaction->payment_method === 'transfer_bank')
            <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
                <h3 class="font-bold text-gray-800 mb-4">Upload Bukti Pembayaran</h3>
                <form action="{{ route('transactions.payment', $transaction) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="proof" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100" required>
                    <button type="submit" class="mt-3 bg-orange-500 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600">Upload</button>
                </form>
            </div>
        @elseif($transaction->payment_method === 'qris')
            <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
                <h3 class="font-bold text-gray-800 mb-3"><i class="fas fa-qrcode text-purple-500 mr-2"></i>Pembayaran QRIS</h3>
                <p class="text-sm text-gray-500 mb-4">Scan QR code untuk menyelesaikan pembayaran</p>
                <a href="{{ route('transactions.qris', $transaction) }}" class="inline-flex items-center gap-2 bg-purple-500 text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-purple-600 transition">
                    <i class="fas fa-qrcode"></i> Lihat QR Code
                </a>
            </div>
        @elseif($transaction->payment_method === 'cod')
            <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
                <div class="flex items-center gap-3 text-green-600">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                    <div>
                        <p class="font-semibold">COD (Bayar di Tempat)</p>
                        <p class="text-sm text-green-500">Siapkan uang tunai sebesar <strong>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</strong> saat pesanan diterima</p>
                    </div>
                </div>
            </div>
        @endif
    @endif

    @if(($transaction->status === 'dikirim' || $transaction->status === 'dalam_perjalanan' || $transaction->status === 'selesai') && !$transaction->received_at)
        <div class="bg-white rounded-xl border border-gray-100 p-6 mb-4">
            @if($transaction->status === 'dalam_perjalanan')
                <div class="flex items-center gap-3 mb-4 text-pink-600 bg-pink-50 rounded-lg px-4 py-3">
                    <i class="fas fa-truck text-xl animate-bounce-subtle"></i>
                    <div>
                        <p class="font-semibold">Paket Dalam Perjalanan</p>
                        <p class="text-xs text-pink-500">Paket sedang menuju ke alamat tujuan Anda</p>
                    </div>
                </div>
            @endif
            @if($transaction->status === 'dikirim')
                <div class="flex items-center gap-3 mb-4 text-purple-600 bg-purple-50 rounded-lg px-4 py-3">
                    <i class="fas fa-box text-xl"></i>
                    <div>
                        <p class="font-semibold">Paket Sudah Dikirim</p>
                        <p class="text-xs text-purple-500">Paket sudah dikirim oleh penjual</p>
                    </div>
        </div>
    @endif

    @if($transaction->status === 'selesai')
                <div class="flex items-center gap-3 mb-4 text-green-600 bg-green-50 rounded-lg px-4 py-3">
                    <i class="fas fa-check-circle text-xl"></i>
                    <div>
                        <p class="font-semibold">Pesanan Selesai</p>
                        <p class="text-xs text-green-500">Konfirmasi bahwa pesanan sudah Anda terima</p>
                    </div>
                </div>
            @endif
            <form action="{{ route('transactions.confirm', $transaction) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-lg font-semibold hover:bg-green-600 transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i> Pesanan Diterima
                </button>
            </form>
        </div>
    @endif

    @if($transaction->status === 'selesai')
        <div class="bg-white rounded-xl border border-gray-100 p-6 mt-4">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-star text-yellow-400"></i>Beri Ulasan</h3>
            @foreach($transaction->items as $item)
                @php $hasReview = $item->product->reviews->where('user_id', auth()->id())->where('transaction_id', $transaction->id)->first(); @endphp
                <div class="border rounded-xl p-4 mb-3 {{ $hasReview ? 'bg-green-50 border-green-200' : 'hover:border-orange-200' }}">
                    <div class="flex items-center gap-3 mb-3">
                        @if($item->product->primaryImage)
                            <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}" alt="" class="w-14 h-14 object-cover rounded-lg">
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm text-gray-800 truncate">{{ $item->product->name }}</p>
                            <p class="text-xs text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($hasReview)
                        <div class="flex items-center gap-2 text-sm text-green-600">
                            <i class="fas fa-check-circle"></i>
                            <span>Sudah diulas -
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $hasReview->rating ? '' : '-o' }} text-yellow-400 text-xs"></i>
                                @endfor
                            </span>
                        </div>
                    @else
                        <form action="{{ route('reviews.store', $transaction) }}" method="POST" enctype="multipart/form-data" class="review-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                            <div class="mb-3">
                                <label class="block text-xs font-medium text-gray-600 mb-1.5">Rating</label>
                                <div class="star-rating flex flex-row-reverse justify-end gap-1 text-2xl" data-input="rating_{{ $item->id }}">
                                    @for($s = 5; $s >= 1; $s--)
                                        <input type="radio" name="rating" id="star{{ $item->id }}_{{ $s }}" value="{{ $s }}" class="hidden" required>
                                        <label for="star{{ $item->id }}_{{ $s }}" class="cursor-pointer text-gray-300 hover:text-yellow-400 transition-colors star-label" data-value="{{ $s }}">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    @endfor
                                </div>
                            </div>

                            <div class="mb-3">
                                <textarea name="review" rows="2" class="w-full border border-gray-300 rounded-lg p-2.5 text-sm" placeholder="Tulis ulasan Anda..."></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Foto (opsional, maks 3)</label>
                                <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                            </div>

                            <button type="submit" class="bg-orange-500 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600 transition"><i class="fas fa-paper-plane mr-1"></i>Kirim Ulasan</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <div class="flex items-center gap-4 mt-6">
        @if($transaction->status === 'selesai')
            <a href="{{ route('returns.create', $transaction) }}"
               class="bg-orange-50 text-orange-600 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-orange-100 transition inline-flex items-center gap-2 border border-orange-200">
                <i class="fas fa-undo-alt"></i> Ajukan Pengembalian
            </a>
        @endif
        @if($transaction->status !== 'selesai' && $transaction->status !== 'dibatalkan')
            <a href="{{ route('complaints.create', $transaction) }}" class="text-red-500 hover:text-red-600 text-sm inline-flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>Ajukan Komplain</a>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #facc15 !important;
    }
    .star-rating label {
        transition: color 0.15s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    function copyResi(resi) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(resi).then(function() {
                var btns = document.querySelectorAll('[onclick*="copyResi"]');
                btns.forEach(function(btn) {
                    var orig = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check text-[10px]"></i> Tersalin!';
                    btn.classList.remove('bg-white', 'text-gray-600', 'hover:bg-gray-50');
                    btn.classList.add('bg-green-100', 'text-green-600');
                    setTimeout(function() {
                        btn.innerHTML = orig;
                        btn.classList.add('bg-white', 'text-gray-600', 'hover:bg-gray-50');
                        btn.classList.remove('bg-green-100', 'text-green-600');
                    }, 2000);
                });
            });
        } else {
            prompt('Salin No. Resi:', resi);
        }
    }
</script>
@endpush
