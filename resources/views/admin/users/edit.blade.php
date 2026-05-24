<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Edit User</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div><label class="block text-sm font-medium text-gray-700">Nama</label><input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded-lg p-2 text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Username</label><input type="text" name="username" value="{{ $user->username }}" class="w-full border rounded-lg p-2 text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">Email</label><input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded-lg p-2 text-sm" required></div>
                    <div><label class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" name="phone" value="{{ $user->phone }}" class="w-full border rounded-lg p-2 text-sm"></div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role_id" class="w-full border rounded-lg p-2 text-sm">
                            @foreach(\App\Models\Role::all() as $role)
                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex space-x-3">
                    <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-600">Perbarui</button>
                    <a href="{{ route('admin.users.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-400">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
