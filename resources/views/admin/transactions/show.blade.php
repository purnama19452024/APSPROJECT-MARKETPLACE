<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pesanan - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .status-flow { display: flex; align-items: center; gap: 0; }
        .status-step { flex: 1; text-align: center; padding: 8px 4px; font-size: 11px; position: relative; }
        .status-step:not(:last-child)::after { content: ''; position: absolute; right: -8px; top: 50%; width: 16px; height: 2px; background: #e5e7eb; }
        .status-step.active { color: #f97316; font-weight: 600; }
        .status-step.active:not(:last-child)::after { background: #f97316; }
        .status-step.done { color: #22c55e; }
        .status-step.done:not(:last-child)::after { background: #22c55e; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen">
        @include('admin.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header')
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>@endif
                @if(session('error'))<div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i>{{ session('error') }}</div>@endif

                <div class="max-w-5xl mx-auto space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Detail Pesanan</h1>
                            <p class="text-sm text-gray-500 mt-1">Invoice: <strong>{{ $transaction->invoice }}</strong></p>
                        </div>
                        <a href="{{ route('admin.transactions.index') }}" class="bg-white text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 border border-gray-200 inline-flex items-center gap-2">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                    {{-- Status Timeline --}}
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="font-bold text-gray-800 mb-4">Status Pesanan</h3>
                        @php
                            $statuses = ['pending', 'diproses', 'dikirim', 'dalam_perjalanan', 'selesai'];
                            $currentIdx = array_search($transaction->status, $statuses);
                        @endphp
                        <div class="status-flow mb-6">
                            @foreach($statuses as $i => $s)
                                <div class="status-step {{ $currentIdx === false ? '' : ($i < $currentIdx ? 'done' : ($i === $currentIdx ? 'active' : '')) }}">
                                    <div class="w-8 h-8 mx-auto mb-1 rounded-full flex items-center justify-center text-xs font-bold
                                        {{ $currentIdx === false ? 'bg-gray-200 text-gray-400' : ($i < $currentIdx ? 'bg-green-100 text-green-600' : ($i === $currentIdx ? 'bg-orange-100 text-orange-600' : 'bg-gray-100 text-gray-400')) }}">
                                        @if($i < $currentIdx) <i class="fas fa-check"></i> @elseif($i === $currentIdx) <i class="fas fa-circle"></i> @else {{ $i + 1 }} @endif
                                    </div>
                                    <span class="block capitalize">{{ $s }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <form action="{{ route('admin.transactions.update-status', $transaction) }}" method="POST">
                                @csrf
                                <div class="flex gap-2 mb-3">
                                    <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                                        <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="diproses" {{ $transaction->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="dikirim" {{ $transaction->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                        <option value="dalam_perjalanan" {{ $transaction->status == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                        <option value="selesai" {{ $transaction->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="dibatalkan" {{ $transaction->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-orange-600 transition">
                                        <i class="fas fa-save mr-1"></i> Update
                                    </button>
                                </div>

                                <div class="border border-gray-200 rounded-xl p-4 bg-gray-50/50">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2"><i class="fas fa-truck text-orange-500"></i> Input Resi Pengiriman</h4>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Kurir</label>
                                            <input type="text" value="{{ strtoupper($transaction->shipping_courier ?? '-') }}" readonly
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm bg-gray-100 text-gray-700 font-medium">
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">Layanan</label>
                                            <select name="shipping_service" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400">
                                                <option value="">Pilih</option>
                                                <option value="REG" {{ $transaction->shipping_service == 'REG' ? 'selected' : '' }}>REG</option>
                                                <option value="YES" {{ $transaction->shipping_service == 'YES' ? 'selected' : '' }}>YES</option>
                                                <option value="ECO" {{ $transaction->shipping_service == 'ECO' ? 'selected' : '' }}>ECO</option>
                                                <option value="OKE" {{ $transaction->shipping_service == 'OKE' ? 'selected' : '' }}>OKE</option>
                                                <option value="Express" {{ $transaction->shipping_service == 'Express' ? 'selected' : '' }}>Express</option>
                                                <option value="Standard" {{ $transaction->shipping_service == 'Standard' ? 'selected' : '' }}>Standard</option>
                                                <option value="Cargo" {{ $transaction->shipping_service == 'Cargo' ? 'selected' : '' }}>Cargo</option>
                                                <option value="Kargo" {{ $transaction->shipping_service == 'Kargo' ? 'selected' : '' }}>Kargo</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs text-gray-500 mb-1 block">No. Resi</label>
                                            <input type="text" name="shipping_receipt" placeholder="JP0000XXXXXX" value="{{ $transaction->shipping_receipt }}"
                                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-orange-400 font-mono">
                                        </div>
                                    </div>
                                    @if($transaction->shipping_receipt)
                                        <div class="mt-3 bg-white border border-dashed border-orange-300 rounded-lg p-3 text-center">
                                            <div class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Stiker Resi</div>
                                            <div class="inline-block bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg px-5 py-3 text-center">
                                                <div class="text-xs text-gray-500 mb-1">{{ strtoupper($transaction->shipping_courier ?? 'KURIR') }} — {{ $transaction->shipping_service ?? 'REG' }}</div>
                                                <div class="text-lg font-bold text-orange-600 font-mono tracking-widest">{{ $transaction->shipping_receipt }}</div>
                                                <div class="mt-1.5 flex justify-center gap-1">
                                                    @for($i = 0; $i < 15; $i++)
                                                        <div class="w-2 h-0.5 bg-gray-400"></div>
                                                    @endfor
                                                </div>
                                                <div class="flex justify-center gap-2 mt-1">
                                                    @for($i = 0; $i < 8; $i++)
                                                        <div class="w-1 h-3 rounded-sm {{ $i % 2 == 0 ? 'bg-gray-800' : 'bg-gray-400' }}"></div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>

                            @if($transaction->status == 'pending' && $transaction->payment)
                                @if($transaction->payment_method === 'transfer_bank')
                                    <form action="{{ route('admin.transactions.verify-payment', $transaction) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <span class="text-sm text-gray-500">Verifikasi Pembayaran:</span>
                                        <button type="submit" name="status" value="verified" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition">
                                            <i class="fas fa-check mr-1"></i> Setuju
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                @elseif($transaction->payment_method === 'cod')
                                    <form action="{{ route('admin.transactions.confirm-cod', $transaction) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition">
                                            <i class="fas fa-check mr-1"></i> Konfirmasi COD & Proses Pesanan
                                        </button>
                                    </form>
                                @elseif($transaction->payment_method === 'qris')
                                    <form action="{{ route('admin.transactions.verify-payment', $transaction) }}" method="POST" class="flex gap-2 items-center">
                                        @csrf
                                        <span class="text-sm text-gray-500">Verifikasi Pembayaran QRIS:</span>
                                        <button type="submit" name="status" value="verified" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition">
                                            <i class="fas fa-check mr-1"></i> Setuju
                                        </button>
                                        <button type="submit" name="status" value="rejected" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                @elseif($transaction->payment_method === 'saldo')
                                    <span class="text-sm text-green-600 font-medium"><i class="fas fa-check-circle mr-1"></i> Pembayaran via Saldo (otomatis terverifikasi)</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-file-invoice text-orange-500"></i> Informasi Transaksi</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between"><span class="text-gray-500">Invoice</span><span class="font-medium">{{ $transaction->invoice }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Status</span>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                        @if($transaction->status == 'pending') bg-yellow-100 text-yellow-700
                                        @elseif($transaction->status == 'diproses') bg-blue-100 text-blue-700
                                        @elseif($transaction->status == 'dikirim') bg-purple-100 text-purple-700
                                        @elseif($transaction->status == 'dalam_perjalanan') bg-pink-100 text-pink-700
                                        @elseif($transaction->status == 'selesai') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-700 @endif">{{ $transaction->status }}</span>
                                </div>
                                <div class="flex justify-between"><span class="text-gray-500">Tanggal</span><span>{{ $transaction->created_at->format('d M Y H:i') }}</span></div>
                                @if($transaction->shipping_courier)
                                    <div class="flex justify-between"><span class="text-gray-500">Kurir</span><span>{{ $transaction->shipping_courier }} - {{ $transaction->shipping_service }}</span></div>
                                @endif
                                @if($transaction->shipping_receipt)
                                    <div class="flex justify-between"><span class="text-gray-500">No. Resi</span><span class="font-medium">{{ $transaction->shipping_receipt }}</span></div>
                                @endif
                                <div class="flex justify-between"><span class="text-gray-500">Pembayaran</span>
                                    <span class="flex items-center gap-1.5">
                                        @if($transaction->payment_method === 'cod')
                                            <i class="fas fa-money-bill-wave text-green-500"></i> COD
                                        @elseif($transaction->payment_method === 'qris')
                                            <i class="fas fa-qrcode text-purple-500"></i> QRIS
                                        @elseif($transaction->payment_method === 'saldo')
                                            <i class="fas fa-wallet text-yellow-500"></i> Saldo
                                        @else
                                            <i class="fas fa-university text-blue-500"></i> Transfer
                                        @endif
                                    </span>
                                </div>
                                <hr>
                                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">Ongkir</span><span>Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</span></div>
                                <div class="flex justify-between border-t pt-2"><span class="font-semibold">Grand Total</span><span class="font-bold text-lg text-orange-600">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span></div>
                            </div>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-user text-blue-500"></i> Informasi Pembeli</h3>
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $transaction->user->photo_url }}" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-gray-100">
                                        <div>
                                            <span class="font-medium block">{{ $transaction->user->name }}</span>
                                            <span class="text-gray-400 text-xs">{{ $transaction->user->email }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 text-gray-600"><i class="fas fa-phone text-gray-400 w-4"></i> {{ $transaction->user->phone ?? '-' }}</div>
                                </div>
                            </div>

                            @if($transaction->shipping_address)
                                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                    <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-map-marker-alt text-red-400"></i> Alamat Pengiriman</h3>
                                    <p class="text-sm text-gray-600">{{ $transaction->shipping_address }}</p>
                                </div>
                            @endif

                            @if($transaction->payment)
                                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                                    <h3 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class="fas fa-credit-card text-green-500"></i> Pembayaran</h3>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between"><span class="text-gray-500">Metode</span>
                                            <span class="flex items-center gap-1.5">
                                                @if($transaction->payment_method === 'cod')
                                                    <i class="fas fa-money-bill-wave text-green-500"></i>
                                                @elseif($transaction->payment_method === 'qris')
                                                    <i class="fas fa-qrcode text-purple-500"></i>
                                                @elseif($transaction->payment_method === 'saldo')
                                                    <i class="fas fa-wallet text-yellow-500"></i>
                                                @else
                                                    <i class="fas fa-university text-blue-500"></i>
                                                @endif
                                                {{ $transaction->payment_method === 'transfer_bank' ? 'Transfer Bank' : strtoupper($transaction->payment_method) }}
                                            </span>
                                        </div>
                                        <div class="flex justify-between"><span class="text-gray-500">Status</span>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                                @if($transaction->payment->status == 'verified') bg-green-100 text-green-700
                                                @elseif($transaction->payment->status == 'rejected') bg-red-100 text-red-700
                                                @else bg-yellow-100 text-yellow-700 @endif">
                                                {{ $transaction->payment->status ?? 'menunggu' }}
                                            </span>
                                        </div>
                                        @if($transaction->payment->proof)
                                            <div class="mt-2">
                                                <p class="text-gray-500 mb-1">Bukti Pembayaran:</p>
                                                <a href="{{ asset('storage/' . $transaction->payment->proof) }}" target="_blank" class="block">
                                                    <img src="{{ asset('storage/' . $transaction->payment->proof) }}" alt="Bukti Bayar" class="w-full rounded-lg border border-gray-200 hover:opacity-90 transition">
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2"><i class="fas fa-shopping-bag text-orange-500"></i> Item Pesanan</h3>
                        </div>
                        <div class="p-6">
                            <table class="w-full text-sm">
                                <thead><tr class="border-b text-gray-500">
                                    <th class="text-left p-2 font-semibold">Produk</th>
                                    <th class="text-center p-2 font-semibold">Qty</th>
                                    <th class="text-right p-2 font-semibold">Harga</th>
                                    <th class="text-right p-2 font-semibold">Subtotal</th>
                                </tr></thead>
                                <tbody>
                                    @foreach($transaction->items as $item)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-2">
                                                <div class="flex items-center gap-2">
                                                    @if($item->product->primaryImage)
                                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image) }}" alt="" class="w-10 h-10 rounded-lg object-cover">
                                                    @endif
                                                    <span class="font-medium">{{ $item->product->name }}</span>
                                                </div>
                                            </td>
                                            <td class="p-2 text-center">{{ $item->quantity }}</td>
                                            <td class="p-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="p-2 text-right font-medium">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
