<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Detail User</h2></x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ $user->photo_url }}" alt="" class="w-20 h-20 rounded-full object-cover">
                    <div>
                        <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                        <p class="text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Username:</span> {{ $user->username }}</div>
                    <div><span class="text-gray-500">Telepon:</span> {{ $user->phone ?? '-' }}</div>
                    <div><span class="text-gray-500">Role:</span> {{ $user->role->name ?? '-' }}</div>
                    <div><span class="text-gray-500">Status:</span> {{ $user->is_active ? 'Aktif' : 'Suspend' }}</div>
                    <div><span class="text-gray-500">Bergabung:</span> {{ $user->created_at->format('d M Y') }}</div>
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Transaksi ({{ $user->transactions->count() }})</h3>
                @foreach($user->transactions as $t)
                    <div class="flex justify-between text-sm border-b py-2">
                        <span>{{ $t->invoice }}</span>
                        <span class="font-medium">Rp {{ number_format($t->grand_total, 0, ',', '.') }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100">{{ $t->status }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
