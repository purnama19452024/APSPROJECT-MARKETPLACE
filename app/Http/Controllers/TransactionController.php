<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Payment;
use App\Models\UserBalance;
use App\Models\BalanceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function checkout()
    {
        $cartItems = Cart::with('product.primaryImage')->where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }
        $subtotal = $cartItems->sum(fn($item) => $item->product->final_price * $item->quantity);
        $addresses = Address::where('user_id', auth()->id())->latest()->get();
        $saldo = auth()->user()->saldo;
        return view('checkout.index', compact('cartItems', 'subtotal', 'addresses', 'saldo'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_courier' => 'required|string',
            'payment_method' => 'required|in:transfer_bank,cod,qris,saldo',
            'notes' => 'nullable|string',
        ]);

        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja kosong');
        }

        if ($request->address_id) {
            $address = Address::where('id', $request->address_id)->where('user_id', auth()->id())->firstOrFail();
            $shippingAddress = $address->full_address . ' (' . $address->recipient_name . ' - ' . $address->recipient_phone . ')';
        } elseif ($request->shipping_address) {
            $shippingAddress = $request->shipping_address;
        } else {
            return back()->with('error', 'Pilih alamat pengiriman');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->product->final_price * $item->quantity);
        $shippingCost = 10000;
        $grandTotal = $subtotal + $shippingCost;

        if ($request->payment_method === 'saldo') {
            $balance = auth()->user()->balanceRelation ?? UserBalance::firstOrCreate(
                ['user_id' => auth()->id()],
                ['balance' => 0]
            );
            if ($balance->balance < $grandTotal) {
                return back()->with('error', 'Saldo tidak mencukupi. Saldo Anda: Rp ' . number_format($balance->balance, 0, ',', '.'));
            }
        }

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'invoice' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(8)),
            'total' => $subtotal,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'shipping_address' => $shippingAddress,
            'shipping_courier' => $request->shipping_courier,
            'notes' => $request->notes,
        ]);

        foreach ($cartItems as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->final_price,
                'subtotal' => $item->product->final_price * $item->quantity,
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        Cart::where('user_id', auth()->id())->delete();

        if ($request->payment_method === 'cod') {
            Payment::create([
                'transaction_id' => $transaction->id,
                'method' => 'cod',
                'status' => 'verified',
                'paid_at' => null,
            ]);
            return redirect()->route('transactions.show', $transaction)->with('success', 'Pesanan berhasil dibuat. Silakan siapkan uang tunai saat barang diterima.');
        }

        if ($request->payment_method === 'saldo') {
            $balance = auth()->user()->balanceRelation;
            $balance->decrement('balance', $grandTotal);
            BalanceHistory::create([
                'user_id' => auth()->id(),
                'amount' => -$grandTotal,
                'type' => 'payment',
                'description' => 'Pembayaran pesanan ' . $transaction->invoice,
                'reference_type' => Transaction::class,
                'reference_id' => $transaction->id,
            ]);
            Payment::create([
                'transaction_id' => $transaction->id,
                'method' => 'saldo',
                'status' => 'verified',
                'paid_at' => now(),
            ]);
            $transaction->update(['status' => 'diproses']);
            return redirect()->route('transactions.show', $transaction)->with('success', 'Pembayaran berhasil menggunakan saldo. Pesanan sedang diproses.');
        }

        if ($request->payment_method === 'qris') {
            Payment::create([
                'transaction_id' => $transaction->id,
                'method' => 'qris',
                'status' => 'pending',
                'paid_at' => null,
            ]);
            return redirect()->route('transactions.qris', $transaction);
        }

        return redirect()->route('transactions.show', $transaction)->with('success', 'Pesanan berhasil dibuat. Silakan upload bukti pembayaran.');
    }

    public function qrisPage(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);
        if ($transaction->payment_method !== 'qris') {
            return redirect()->route('transactions.show', $transaction);
        }
        return view('checkout.qris', compact('transaction'));
    }

    public function qrisConfirm(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);
        if ($transaction->payment_method !== 'qris') abort(400);

        if ($transaction->payment) {
            $transaction->payment->update([
                'status' => 'verified',
                'paid_at' => now(),
            ]);
        }
        $transaction->update(['status' => 'diproses']);

        return redirect()->route('transactions.show', $transaction)->with('success', 'Pembayaran QRIS berhasil dikonfirmasi. Pesanan sedang diproses.');
    }

    public function index()
    {
        $transactions = Transaction::with('items.product')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);
        $transaction->load('items.product.images', 'payment');
        return view('transactions.show', compact('transaction'));
    }

    public function uploadPayment(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);
        $request->validate(['proof' => 'required|image|mimes:jpg,jpeg,png|max:2048']);

        $proof = $request->file('proof')->store('payments', 'public');

        Payment::updateOrCreate(
            ['transaction_id' => $transaction->id],
            ['proof' => $proof, 'method' => 'transfer_bank', 'status' => 'pending', 'paid_at' => now()]
        );

        return back()->with('success', 'Bukti pembayaran berhasil diupload');
    }

    public function confirmReceived(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);

        if ($transaction->payment_method === 'cod' && $transaction->payment && !$transaction->payment->paid_at) {
            $transaction->payment->update(['paid_at' => now()]);
        }

        if ($transaction->status !== 'selesai') {
            $transaction->update(['status' => 'selesai']);
        }

        $transaction->update(['received_at' => now()]);

        return back()->with('success', 'Pesanan telah dikonfirmasi selesai');
    }
}
