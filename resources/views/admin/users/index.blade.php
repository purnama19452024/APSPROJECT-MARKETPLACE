<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola User - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen">
        @include('admin.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header')
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4">{{ session('error') }}</div>@endif

                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-xl font-bold text-gray-800">Kelola User</h1>
                    <a href="{{ route('admin.users.create') }}" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600"><i class="fas fa-plus mr-1"></i>Tambah User</a>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">User</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Email</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Role</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Status</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ $user->photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover">
                                            <span class="font-medium">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-gray-600">{{ $user->email }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($user->role_id == 1) bg-red-100 text-red-700
                                            @elseif($user->role_id == 2) bg-blue-100 text-blue-700
                                            @else bg-gray-100 text-gray-700 @endif">
                                            {{ $user->role->name ?? 'User' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $user->is_active ? 'Aktif' : 'Suspend' }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-blue-500 hover:text-blue-600"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-500 hover:text-yellow-600"><i class="fas fa-edit"></i></a>
                                            @if($user->is_active)
                                                <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">@csrf<button class="text-red-500 hover:text-red-600"><i class="fas fa-ban"></i></button></form>
                                            @else
                                                <form action="{{ route('admin.users.activate', $user) }}" method="POST" class="inline">@csrf<button class="text-green-500 hover:text-green-600"><i class="fas fa-check"></i></button></form>
                                            @endif
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">@csrf @method('DELETE')<button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button></form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $users->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
