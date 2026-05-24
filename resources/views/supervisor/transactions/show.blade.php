<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Transaksi - Supervisor</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex h-screen">
        @include('supervisor.partials.sidebar')
        <div class="flex-1 flex flex-col overflow-hidden">
            @include('supervisor.partials.header')
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))<div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-4">{{ session('success') }}</div>@endif
                <a href="{{ route('supervisor.transactions.index') }}" class="text-blue-500 hover:text-blue-600 text-sm mb-4 inline-block"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Informasi Pesanan</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div><span class="text-gray-500">Invoice:</span> <span class="font-semibold">{{ $transaction->invoice }}</span></div>
                                <div><span class="text-gray-500">Status:</span> <span class="font-semibold">{{ ucfirst($transaction->status) }}</span></div>
                                <div><span class="text-gray-500">User:</span> {{ $transaction->user->name }}</div>
                                <div><span class="text-gray-500">Tanggal:</span> {{ $transaction->created_at->format('d M Y H:i') }}</div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Produk</h3>
                            <table class="w-full text-sm">
                                <thead><tr class="border-b"><th class="text-left py-2">Produk</th><th class="text-center py-2">Qty</th><th class="text-right py-2">Harga</th><th class="text-right py-2">Subtotal</th></tr></thead>
                                <tbody>
                                    @foreach($transaction->items as $item)
                                        <tr class="border-b">
                                            <td class="py-2">{{ $item->product->name }}</td>
                                            <td class="py-2 text-center">{{ $item->quantity }}</td>
                                            <td class="py-2 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                            <td class="py-2 text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr><td colspan="3" class="py-2 text-right font-semibold">Total Produk</td><td class="py-2 text-right">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td></tr>
                                    <tr><td colspan="3" class="py-2 text-right font-semibold">Ongkir</td><td class="py-2 text-right">Rp {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td></tr>
                                    <tr><td colspan="3" class="py-2 text-right font-bold text-orange-500">Grand Total</td><td class="py-2 text-right font-bold text-orange-500">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td></tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Pengiriman</h3>
                            <div class="text-sm space-y-2">
                                <p><span class="text-gray-500">Alamat:</span> {{ $transaction->shipping_address }}</p>
                                <p><span class="text-gray-500">Kurir:</span> {{ $transaction->shipping_courier }}</p>
                                <p><span class="text-gray-500">Resi:</span> {{ $transaction->shipping_receipt ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Pembayaran</h3>
                            <div class="text-sm space-y-2 mb-3">
                                <p><span class="text-gray-500">Metode:</span>
                                    <span class="font-medium flex items-center gap-1.5">
                                        @if($transaction->payment_method === 'cod')
                                            <i class="fas fa-money-bill-wave text-green-500"></i> COD
                                        @elseif($transaction->payment_method === 'qris')
                                            <i class="fas fa-qrcode text-purple-500"></i> QRIS
                                        @elseif($transaction->payment_method === 'saldo')
                                            <i class="fas fa-wallet text-yellow-500"></i> Saldo
                                        @else
                                            <i class="fas fa-university text-blue-500"></i> Transfer Bank
                                        @endif
                                    </span>
                                </p>
                            </div>
                            @if($transaction->payment)
                                @if($transaction->payment->proof)
                                    <img src="{{ asset('storage/' . $transaction->payment->proof) }}" alt="Bukti Bayar" class="w-full rounded-lg mb-3">
                                @endif
                                <div class="text-sm">
                                    <p><span class="text-gray-500">Status:</span>
                                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                            @if($transaction->payment->status === 'verified') bg-green-100 text-green-700
                                            @elseif($transaction->payment->status === 'rejected') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700 @endif">{{ $transaction->payment->status }}</span>
                                    </p>
                                </div>
                                @if($transaction->payment->status === 'pending' && $transaction->payment_method === 'transfer_bank')
                                    <div class="mt-4 space-y-2">
                                        <form action="{{ route('supervisor.transactions.verify-payment', $transaction) }}" method="POST">@csrf
                                            <input type="hidden" name="status" value="verified">
                                            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-green-600"><i class="fas fa-check mr-1"></i>Verifikasi</button>
                                        </form>
                                        <form action="{{ route('supervisor.transactions.verify-payment', $transaction) }}" method="POST">@csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="w-full bg-red-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-red-600"><i class="fas fa-times mr-1"></i>Tolak</button>
                                        </form>
                                    </div>
                                @elseif($transaction->payment_method === 'cod' && $transaction->payment->status === 'verified')
                                    <span class="text-sm text-green-600 font-medium mt-2 block"><i class="fas fa-check-circle mr-1"></i>COD - Bayar di Tempat</span>
                                @elseif($transaction->payment_method === 'saldo' && $transaction->payment->status === 'verified')
                                    <span class="text-sm text-green-600 font-medium mt-2 block"><i class="fas fa-check-circle mr-1"></i>Pembayaran via Saldo</span>
                                @endif
                            @else
                                <p class="text-sm text-gray-500">-</p>
                            @endif
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="font-bold text-gray-800 mb-4">Update Status</h3>
                            <form action="{{ route('supervisor.transactions.update-status', $transaction) }}" method="POST">
                                @csrf
                                <select name="status" class="w-full border rounded-lg p-2 text-sm mb-3">
                                    <option value="pending" {{ $transaction->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="diproses" {{ $transaction->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $transaction->status === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="dalam_perjalanan" {{ $transaction->status === 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                                    <option value="selesai" {{ $transaction->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="dibatalkan" {{ $transaction->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>

                                <h4 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2 mt-4"><i class="fas fa-truck text-blue-500"></i> Input Resi</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-3">
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">Layanan</label>
                                        <select name="shipping_service" class="w-full border rounded-lg p-2 text-sm">
                                            <option value="">Pilih</option>
                                            <option value="REG" {{ ($transaction->shipping_service ?? '') == 'REG' ? 'selected' : '' }}>REG</option>
                                            <option value="YES" {{ ($transaction->shipping_service ?? '') == 'YES' ? 'selected' : '' }}>YES</option>
                                            <option value="ECO" {{ ($transaction->shipping_service ?? '') == 'ECO' ? 'selected' : '' }}>ECO</option>
                                            <option value="OKE" {{ ($transaction->shipping_service ?? '') == 'OKE' ? 'selected' : '' }}>OKE</option>
                                            <option value="Express" {{ ($transaction->shipping_service ?? '') == 'Express' ? 'selected' : '' }}>Express</option>
                                            <option value="Cargo" {{ ($transaction->shipping_service ?? '') == 'Cargo' ? 'selected' : '' }}>Cargo</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500 mb-1 block">No. Resi</label>
                                        <input type="text" name="shipping_receipt" placeholder="JP0000XXXXXX" class="w-full border rounded-lg p-2 text-sm font-mono" value="{{ $transaction->shipping_receipt }}">
                                    </div>
                                </div>
                                @if($transaction->shipping_receipt)
                                    <div class="bg-white border border-dashed border-blue-300 rounded-lg p-3 text-center mb-3">
                                        <div class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Stiker Resi</div>
                                        <div class="inline-block bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg px-5 py-3 text-center">
                                            <div class="text-xs text-gray-500 mb-1">{{ strtoupper($transaction->shipping_courier ?? 'KURIR') }} — {{ $transaction->shipping_service ?? 'REG' }}</div>
                                            <div class="text-lg font-bold text-blue-600 font-mono tracking-widest">{{ $transaction->shipping_receipt }}</div>
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
                                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg text-sm font-semibold hover:bg-blue-600">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
