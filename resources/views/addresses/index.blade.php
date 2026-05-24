@extends('layouts.landing')

@section('title', 'Alamat Saya')

@section('content')
<section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>Alamat Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola alamat pengiriman (maksimal 5 alamat)</p>
        </div>
        @if($addresses->count() < 5)
            <a href="{{ route('addresses.create') }}" class="bg-orange-500 text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-orange-600 transition inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Alamat
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>
    @endif

    @forelse($addresses as $address)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-3 hover:shadow-md transition {{ $address->is_default ? 'ring-2 ring-orange-300' : '' }}">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2.5 py-0.5 bg-orange-50 text-orange-600 rounded-full text-xs font-semibold">{{ $address->label }}</span>
                        @if($address->is_default)
                            <span class="px-2.5 py-0.5 bg-blue-50 text-blue-600 rounded-full text-xs font-semibold"><i class="fas fa-check-circle mr-1"></i>Utama</span>
                        @endif
                    </div>
                    <p class="font-semibold text-gray-800">{{ $address->recipient_name }} @if($address->recipient_phone)<span class="font-normal text-gray-500 text-sm"> - {{ $address->recipient_phone }}</span>@endif</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $address->address }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $address->district ? $address->district . ', ' : '' }}
                        {{ $address->city ? $address->city . ', ' : '' }}
                        {{ $address->province }}
                    </p>
                    @if($address->postal_code)
                        <span class="text-xs text-gray-400">{{ $address->postal_code }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2 ml-4">
                    <a href="{{ route('addresses.edit', $address) }}" class="text-blue-500 hover:text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1"><i class="fas fa-edit"></i> Ubah</a>
                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Hapus alamat ini?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:text-red-600 bg-red-50 px-3 py-1.5 rounded-lg text-xs font-medium inline-flex items-center gap-1"><i class="fas fa-trash"></i> Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-map-marker-alt text-6xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Alamat</h3>
            <p class="text-gray-400 mb-6">Tambahkan alamat pengiriman untuk memudahkan checkout</p>
            <a href="{{ route('addresses.create') }}" class="inline-block bg-orange-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-orange-600 transition">Tambah Alamat</a>
        </div>
    @endforelse

    <div class="mt-6 text-center">
        <a href="{{ route('profile.edit') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Profil</a>
    </div>
</section>
@endsection
