<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Komplain - {{ config('app.name') }}</title>
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
                    <h1 class="text-xl font-bold text-gray-800">Kelola Komplain</h1>
                </div>

                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead><tr class="bg-gray-50 border-b">
                            <th class="text-left p-3 font-semibold text-gray-600">Pelapor</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Subjek</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Status</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Tanggal</th>
                            <th class="text-left p-3 font-semibold text-gray-600">Aksi</th>
                        </tr></thead>
                        <tbody>
                            @foreach($complaints as $complaint)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3">
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ $complaint->user->photo_url }}" alt="" class="w-7 h-7 rounded-full object-cover">
                                            <span>{{ $complaint->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="p-3 font-medium">{{ $complaint->subject }}</td>
                                    <td class="p-3">
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($complaint->status == 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($complaint->status == 'diproses') bg-blue-100 text-blue-700
                                            @elseif($complaint->status == 'selesai') bg-green-100 text-green-700
                                            @else bg-red-100 text-red-700 @endif">
                                            {{ $complaint->status }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-500">{{ $complaint->created_at->format('d M Y') }}</td>
                                    <td class="p-3">
                                        <a href="{{ route('admin.complaints.show', $complaint) }}" class="text-blue-500 hover:text-blue-600"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $complaints->links() }}</div>
            </main>
        </div>
    </div>
</body>
</html>
