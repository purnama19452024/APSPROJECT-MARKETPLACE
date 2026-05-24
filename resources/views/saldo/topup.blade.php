@extends('layouts.landing')

@section('title', 'Top Up Saldo')

@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Top Up Saldo</h1>
            <p class="text-sm text-gray-500 mt-1">Isi saldo dompet digital Anda</p>
        </div>
        <a href="{{ route('saldo.index') }}" class="text-sm text-gray-400 hover:text-orange-500 flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <form action="{{ route('saldo.topup.store') }}" method="POST">
            @csrf

            {{-- Nominal --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Top Up</label>
                <div class="grid grid-cols-3 gap-2 mb-3">
                    @foreach([10000, 25000, 50000, 100000, 250000, 500000] as $nominal)
                        <button type="button" data-amount="{{ $nominal }}"
                            class="quick-amount border border-gray-200 rounded-lg py-2.5 text-center hover:border-blue-400 hover:bg-blue-50 transition text-sm font-medium text-gray-600">
                            Rp{{ number_format($nominal, 0, ',', '.') }}
                        </button>
                    @endforeach
                </div>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-semibold text-sm">Rp</span>
                    <input type="number" name="amount" id="customAmount" min="1000" max="10000000" step="1000"
                        class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Masukkan jumlah" value="{{ old('amount') }}">
                </div>
                @error('amount')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1.5">Minimal Rp 1.000, maksimal Rp 10.000.000</p>
            </div>

            {{-- Payment Method --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-3">Pilih Metode Pembayaran</label>

                {{-- DANA --}}
                <label class="pay-option relative flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition mb-2">
                    <input type="radio" name="payment_method" value="dana" class="hidden" @checked(old('payment_method', 'qris') == 'dana')>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #0085D0, #0066A0);">
                            <span class="text-white text-xs font-black tracking-tight">D</span>
                        </div>
                        <div class="check-icon absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full hidden items-center justify-center ring-2 ring-white">
                            <i class="fas fa-check text-white text-[8px]"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">DANA</p>
                        <p class="text-[11px] text-gray-400">Bayar pakai dompet DANA</p>
                    </div>
                    <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md flex-shrink-0">QRIS</span>
                </label>

                {{-- GoPay --}}
                <label class="pay-option relative flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition mb-2">
                    <input type="radio" name="payment_method" value="gopay" class="hidden" @checked(old('payment_method') == 'gopay')>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #00AEE8, #008EC0);">
                            <span class="text-white text-xs font-black tracking-tight">G</span>
                        </div>
                        <div class="check-icon absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full hidden items-center justify-center ring-2 ring-white">
                            <i class="fas fa-check text-white text-[8px]"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">GoPay</p>
                        <p class="text-[11px] text-gray-400">Bayar pakai GoPay</p>
                    </div>
                    <span class="text-[10px] font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded-md flex-shrink-0">QRIS</span>
                </label>

                {{-- QRIS --}}
                <label class="pay-option relative flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition mb-2">
                    <input type="radio" name="payment_method" value="qris" class="hidden" @checked(old('payment_method', 'qris') == 'qris')>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #1a1a1a, #333);">
                            <i class="fas fa-qrcode text-white text-sm"></i>
                        </div>
                        <div class="check-icon absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full hidden items-center justify-center ring-2 ring-white">
                            <i class="fas fa-check text-white text-[8px]"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">QRIS</p>
                        <p class="text-[11px] text-gray-400">OVO, ShopeePay, LinkAja, Mobile Banking</p>
                    </div>
                    <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md flex-shrink-0">All-in-One</span>
                </label>

                {{-- Bank Transfer --}}
                <label class="pay-option relative flex items-center gap-3 p-3 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition mb-2">
                    <input type="radio" name="payment_method" value="bank_transfer" class="hidden" @checked(old('payment_method') == 'bank_transfer')>
                    <div class="relative">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm" style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                            <i class="fas fa-building-columns text-white text-sm"></i>
                        </div>
                        <div class="check-icon absolute -top-1 -right-1 w-5 h-5 bg-emerald-500 rounded-full hidden items-center justify-center ring-2 ring-white">
                            <i class="fas fa-check text-white text-[8px]"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800">Transfer Bank</p>
                        <p class="text-[11px] text-gray-400">BCA, Mandiri, BNI, BRI, dan 5 bank lainnya</p>
                    </div>
                    <span class="text-[10px] font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-md flex-shrink-0">Manual</span>
                </label>

                {{-- Bank grid when transfer selected --}}
                <div id="bankOptions" class="{{ old('payment_method') == 'bank_transfer' ? '' : 'hidden' }} mt-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-xs font-medium text-gray-500 mb-2.5 uppercase tracking-wider">Pilih Bank Tujuan</p>
                    <div class="grid grid-cols-3 gap-2">
                        @php
                            $banks = [
                                'BCA' => '#0066AE', 'Mandiri' => '#003E7E', 'BNI' => '#003C71',
                                'BRI' => '#00529C', 'CIMB Niaga' => '#003D7A', 'BSI' => '#044D8C',
                                'Permata' => '#003C71', 'Danamon' => '#003B5C', 'BTN' => '#003E7E',
                            ];
                        @endphp
                        @foreach($banks as $name => $color)
                            <label class="bank-option relative flex flex-col items-center gap-1.5 p-2.5 border-2 rounded-lg cursor-pointer hover:border-blue-400 hover:shadow-sm transition bg-white {{ old('bank', 'BCA') == $name ? 'ring-2 ring-blue-500 border-blue-500 bg-white' : 'border-gray-200' }}">
                                <input type="radio" name="bank" value="{{ $name }}" class="hidden" @checked(old('bank', 'BCA') == $name)>
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-[11px] font-bold shadow-sm" style="background: {{ $color }}">
                                    {{ substr($name, 0, 3) }}
                                </div>
                                <div class="bank-check-icon absolute -top-1.5 -right-1.5 w-5 h-5 bg-emerald-500 rounded-full {{ old('bank', 'BCA') == $name ? 'flex' : 'hidden' }} items-center justify-center ring-2 ring-white">
                                    <i class="fas fa-check text-white text-[8px]"></i>
                                </div>
                                <span class="text-[11px] font-medium text-gray-600">{{ $name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('bank')
                        <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button type="submit" class="w-full bg-gray-900 text-white py-3 rounded-xl font-semibold text-sm hover:bg-black transition shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-arrow-right"></i> Lanjutkan Pembayaran
            </button>
        </form>
    </div>
</section>

@push('scripts')
<script>
    document.querySelectorAll('.quick-amount').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.quick-amount').forEach(b => {
                b.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-600');
                b.classList.add('border-gray-200', 'text-gray-600');
            });
            this.classList.remove('border-gray-200', 'text-gray-600');
            this.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-600');
            document.getElementById('customAmount').value = this.dataset.amount;
        });
    });

    const bankOptions = document.getElementById('bankOptions');

    function selectPayment(value) {
        document.querySelectorAll('input[name="payment_method"]').forEach(r => {
            r.checked = r.value === value;
        });
        bankOptions.classList.toggle('hidden', value !== 'bank_transfer');
        document.querySelectorAll('.pay-option').forEach(l => {
            l.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-50', 'bg-emerald-50');
            l.classList.add('border-gray-200');
            l.querySelector('.check-icon')?.classList.add('hidden');
        });
        const selected = document.querySelector('.pay-option input[value="' + value + '"]')?.closest('.pay-option');
        if (selected) {
            selected.classList.remove('border-gray-200');
            selected.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-blue-50');
            selected.querySelector('.check-icon')?.classList.remove('hidden');
            selected.querySelector('.check-icon')?.classList.add('flex');
        }
    }

    document.querySelectorAll('.pay-option').forEach(label => {
        label.addEventListener('click', function (e) {
            const input = this.querySelector('input[name="payment_method"]');
            if (input) selectPayment(input.value);
        });
    });

    document.querySelectorAll('input[name="payment_method"]').forEach(r => {
        r.addEventListener('change', function () {
            selectPayment(this.value);
        });
    });

    selectPayment(document.querySelector('input[name="payment_method"]:checked')?.value || '{{ old('payment_method', 'qris') }}');

    document.querySelectorAll('.bank-option').forEach(label => {
        label.addEventListener('click', function (e) {
            const input = this.querySelector('input[name="bank"]');
            if (input) input.checked = true;
            document.querySelectorAll('.bank-option').forEach(b => {
                b.classList.remove('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-white');
                b.classList.add('border-gray-200');
                b.querySelector('.bank-check-icon')?.classList.add('hidden');
                b.querySelector('.bank-check-icon')?.classList.remove('flex');
            });
            this.classList.remove('border-gray-200');
            this.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-white');
            this.querySelector('.bank-check-icon')?.classList.remove('hidden');
            this.querySelector('.bank-check-icon')?.classList.add('flex');
        });
        const input = label.querySelector('input[name="bank"]');
        if (input && input.checked) {
            label.classList.remove('border-gray-200');
            label.classList.add('ring-2', 'ring-blue-500', 'border-blue-500', 'bg-white');
            label.querySelector('.bank-check-icon')?.classList.remove('hidden');
            label.querySelector('.bank-check-icon')?.classList.add('flex');
        }
    });
</script>
@endpush
@stop
