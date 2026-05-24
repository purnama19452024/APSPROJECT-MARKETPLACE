@extends('layouts.landing')

@section('title', 'Tarik Saldo')

@section('content')
<section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tarik Saldo</h1>
            <p class="text-sm text-gray-500 mt-1">Cairkan saldo ke rekening Anda</p>
        </div>
        <a href="{{ route('saldo.index') }}" class="text-sm text-gray-400 hover:text-orange-500 flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        {{-- Saldo Info --}}
        <div class="bg-blue-600 px-5 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-xs font-medium uppercase tracking-wider">Saldo Tersedia</p>
                    <p class="text-white text-xl font-bold mt-0.5">Rp {{ number_format($balance->balance, 0, ',', '.') }}</p>
                    @if($pendingTotal > 0)
                        <p class="text-blue-200 text-[11px] mt-1">Dalam penarikan: Rp {{ number_format($pendingTotal, 0, ',', '.') }}</p>
                    @endif
                </div>
                <div class="w-10 h-10 bg-white/15 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
            </div>
        </div>

        <form action="{{ route('saldo.withdraw.store') }}" method="POST" class="p-5">
            @csrf

            {{-- Amount --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    <i class="fas fa-money-bill-wave text-gray-400 mr-1.5"></i>Jumlah Penarikan
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                    <input type="number" name="amount" id="amount" min="10000" max="{{ $maxWithdraw }}" step="1000"
                        class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="0" value="{{ old('amount') }}">
                </div>
                @error('amount')
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1.5">Minimal Rp 10.000. Maksimal penarikan: Rp {{ number_format($maxWithdraw, 0, ',', '.') }}</p>
            </div>

            {{-- Bank Data --}}
            <div class="mb-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="h-px flex-1 bg-gray-100"></div>
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Rekening</span>
                    <div class="h-px flex-1 bg-gray-100"></div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-university text-gray-400 mr-1.5"></i>Bank Tujuan
                        </label>
                        <select name="bank_name"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white">
                            <option value="">Pilih bank</option>
                            @foreach(['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'BSI', 'BTN', 'Danamon', 'Permata', 'Maybank', 'Jenius', 'GoPay', 'OVO', 'DANA', 'Lainnya'] as $bank)
                                <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                        @error('bank_name')
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-hashtag text-gray-400 mr-1.5"></i>Nomor Rekening
                        </label>
                        <input type="text" name="bank_account_number" value="{{ old('bank_account_number') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Masukkan nomor rekening">
                        @error('bank_account_number')
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            <i class="fas fa-user text-gray-400 mr-1.5"></i>Nama Pemilik Rekening
                        </label>
                        <input type="text" name="bank_account_name" value="{{ old('bank_account_name') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                            placeholder="Sesuai dengan nama rekening">
                        @error('bank_account_name')
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 mb-5 flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-400 text-sm mt-0.5"></i>
                <p class="text-xs text-blue-700 leading-relaxed">Penarikan akan diproses oleh admin dalam 1x24 jam. Saldo akan otomatis berkurang setelah permintaan disetujui.</p>
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-blue-500 text-white py-3 rounded-xl font-semibold text-sm hover:bg-blue-600 transition shadow-lg flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i> Ajukan Penarikan
            </button>
        </form>
    </div>
</section>
@endSection
