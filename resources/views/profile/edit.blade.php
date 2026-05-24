<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profil Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">Alamat Pengiriman</h2>
                    <p class="mt-1 text-sm text-gray-600">Kelola alamat pengiriman untuk checkout cepat</p>
                    <div class="mt-4">
                        <a href="{{ route('addresses.index') }}" class="inline-flex items-center gap-2 bg-orange-500 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-orange-600 transition">
                            <i class="fas fa-map-marker-alt"></i> Kelola Alamat
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">Ulasan Saya</h2>
                    <p class="mt-1 text-sm text-gray-600">Lihat dan kelola ulasan produk yang Anda berikan</p>
                    <div class="mt-4">
                        <a href="{{ route('reviews.index') }}" class="inline-flex items-center gap-2 bg-yellow-500 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition">
                            <i class="fas fa-star"></i> Lihat Ulasan
                        </a>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
