@extends('layouts.landing')

@section('title', 'Checkout')

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6"><i class="fas fa-credit-card text-orange-500 mr-2"></i>Checkout</h1>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-800">Alamat Pengiriman</h3>
                        <a href="{{ route('addresses.index') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium"><i class="fas fa-plus mr-1"></i>Kelola Alamat</a>
                    </div>

                    @if($addresses->count() > 0)
                        <div class="space-y-3 mb-4">
                            @foreach($addresses as $addr)
                                <label class="block border rounded-xl p-4 cursor-pointer transition hover:border-orange-300 address-label" data-id="{{ $addr->id }}" onclick="selectAddress(this, {{ $addr->id }})">
                                    <input type="radio" name="address_id" value="{{ $addr->id }}" {{ $loop->first ? 'checked' : '' }} class="hidden address-radio">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-0.5 w-4 h-4 rounded-full border-2 flex-shrink-0 flex items-center justify-center address-dot {{ $loop->first ? 'border-orange-500' : 'border-gray-300' }}">
                                            <div class="w-2 h-2 rounded-full address-inner {{ $loop->first ? 'bg-orange-500' : '' }}"></div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 bg-orange-50 text-orange-600 rounded-full text-xs font-semibold">{{ $addr->label }}</span>
                                                @if($addr->is_default)
                                                    <span class="text-xs text-blue-500 font-medium">Utama</span>
                                                @endif
                                            </div>
                                            <p class="font-medium text-gray-800 text-sm mt-1">{{ $addr->recipient_name }} @if($addr->recipient_phone)<span class="font-normal text-gray-500"> - {{ $addr->recipient_phone }}</span>@endif</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $addr->full_address }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-lg p-4 mb-4 text-center">
                            <p class="text-sm text-gray-500 mb-2">Belum ada alamat tersimpan</p>
                            <a href="{{ route('addresses.create') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">Tambah Alamat Baru</a>
                        </div>
                    @endif

                    <details class="group">
                        <summary class="text-sm text-gray-500 cursor-pointer hover:text-orange-500 font-medium"><i class="fas fa-pen mr-1"></i>Atau gunakan alamat baru</summary>
                        <textarea name="shipping_address" rows="3" class="w-full border border-gray-300 rounded-lg p-3 text-sm mt-3" placeholder="Masukkan alamat lengkap...">{{ old('shipping_address') }}</textarea>
                    </details>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Kurir Pengiriman</h3>
                    <select name="shipping_courier" class="w-full border border-gray-300 rounded-lg p-3 text-sm" required>
                        <option value="">Pilih Kurir</option>
                        <option value="JNE">JNE</option>
                        <option value="J&T">J&T Express</option>
                        <option value="J&T Cargo">J&T Cargo</option>
                        <option value="SiCepat">SiCepat</option>
                        <option value="Pos Indonesia">Pos Indonesia</option>
                        <option value="AnterAja">AnterAja</option>
                        <option value="SPX Express">SPX Express</option>
                    </select>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-credit-card text-green-500"></i>Metode Pembayaran</h3>
                    <div class="space-y-3">
                        <label class="payment-label block border rounded-xl p-4 cursor-pointer transition hover:border-orange-300 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/50">
                            <input type="radio" name="payment_method" value="transfer_bank" class="hidden" checked>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0"><i class="fas fa-university text-blue-600"></i></div>
                                <div>
                                    <p class="font-medium text-sm text-gray-800">Transfer Bank</p>
                                    <p class="text-xs text-gray-500">Bayar via transfer bank, upload bukti pembayaran</p>
                                </div>
                            </div>
                        </label>

                        <label class="payment-label block border rounded-xl p-4 cursor-pointer transition hover:border-orange-300 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/50">
                            <input type="radio" name="payment_method" value="cod" class="hidden">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0"><i class="fas fa-money-bill-wave text-green-600"></i></div>
                                <div>
                                    <p class="font-medium text-sm text-gray-800">COD (Bayar di Tempat)</p>
                                    <p class="text-xs text-gray-500">Bayar tunai saat barang diterima</p>
                                </div>
                            </div>
                        </label>

                        <label class="payment-label block border rounded-xl p-4 cursor-pointer transition hover:border-orange-300 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/50">
                            <input type="radio" name="payment_method" value="qris" class="hidden">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0"><i class="fas fa-qrcode text-purple-600"></i></div>
                                <div>
                                    <p class="font-medium text-sm text-gray-800">QRIS</p>
                                    <p class="text-xs text-gray-500">Scan QR code via e-wallet atau mobile banking</p>
                                </div>
                            </div>
                        </label>

                        <label class="payment-label block border rounded-xl p-4 cursor-pointer transition hover:border-orange-300 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/50">
                            <input type="radio" name="payment_method" value="saldo" class="hidden">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0"><i class="fas fa-wallet text-yellow-600"></i></div>
                                <div>
                                    <p class="font-medium text-sm text-gray-800">Saldo APSPROJECT</p>
                                    <p class="text-xs text-gray-500">Bayar menggunakan saldo akun Anda</p>
                                    <p class="text-xs font-semibold mt-1">Saldo: <span class="text-green-600">Rp {{ number_format($saldo, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Catatan (opsional)</h3>
                    <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg p-3 text-sm" placeholder="Catatan untuk penjual...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div>
                <div class="bg-white rounded-xl border border-gray-100 p-6 sticky top-24">
                    <h3 class="font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>
                    <div class="space-y-3">
                        @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3 text-sm">
                                <span class="text-gray-500">{{ $item->product->name }} x{{ $item->quantity }}</span>
                                <span class="ml-auto font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t mt-4 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Ongkir</span><span class="text-green-600">Rp 10.000</span></div>
                        <div class="border-t pt-2 flex justify-between"><span class="font-semibold">Total</span><span class="font-bold text-orange-500 text-lg">Rp {{ number_format($subtotal + 10000, 0, ',', '.') }}</span></div>
                    </div>
                    <button type="submit" id="submitBtn" class="w-full bg-orange-500 text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition mt-6">
                        <i class="fas fa-check mr-2"></i>Buat Pesanan
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>
@endsection

@push('styles')
<style>
    .payment-label input:checked ~ div .payment-check {
        background: #f97316;
        border-color: #f97316;
    }
</style>
@endpush

@push('scripts')
<script>
    function selectAddress(labelEl, id) {
        document.querySelectorAll('.address-label').forEach(function(el) {
            el.classList.remove('border-orange-500', 'bg-orange-50/50');
            el.classList.add('border-gray-200');
            var dot = el.querySelector('.address-dot');
            dot.className = 'mt-0.5 w-4 h-4 rounded-full border-2 flex-shrink-0 flex items-center justify-center address-dot border-gray-300';
            var inner = el.querySelector('.address-inner');
            inner.className = 'w-2 h-2 rounded-full address-inner';
        });
        labelEl.classList.remove('border-gray-200');
        labelEl.classList.add('border-orange-500', 'bg-orange-50/50');
        var dot = labelEl.querySelector('.address-dot');
        dot.className = 'mt-0.5 w-4 h-4 rounded-full border-2 flex-shrink-0 flex items-center justify-center address-dot border-orange-500';
        var inner = labelEl.querySelector('.address-inner');
        inner.className = 'w-2 h-2 rounded-full address-inner bg-orange-500';
        labelEl.querySelector('.address-radio').checked = true;
    }

    document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-label').forEach(function(el) {
                el.classList.remove('border-orange-500', 'bg-orange-50/50');
            });
            this.closest('.payment-label').classList.add('border-orange-500', 'bg-orange-50/50');

            var btn = document.getElementById('submitBtn');
            if (this.value === 'saldo') {
                var saldoText = document.querySelector('input[name="payment_method"][value="saldo"]').closest('.payment-label').querySelector('.text-green-600');
                var saldo = {{ $saldo }};
                var total = {{ $subtotal + 10000 }};
                if (saldo < total) {
                    btn.innerHTML = '<i class="fas fa-exclamation-circle mr-2"></i>Saldo Tidak Cukup';
                    btn.disabled = true;
                    btn.classList.remove('bg-orange-500', 'hover:bg-orange-600');
                    btn.classList.add('bg-gray-400', 'cursor-not-allowed');
                } else {
                    btn.innerHTML = '<i class="fas fa-check mr-2"></i>Bayar dengan Saldo';
                    btn.disabled = false;
                    btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    btn.classList.add('bg-orange-500', 'hover:bg-orange-600');
                }
            } else {
                btn.innerHTML = '<i class="fas fa-check mr-2"></i>Buat Pesanan';
                btn.disabled = false;
                btn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                btn.classList.add('bg-orange-500', 'hover:bg-orange-600');
            }
        });
    });
</script>
@endpush
