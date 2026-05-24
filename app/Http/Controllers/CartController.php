<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product.primaryImage')->where('user_id', auth()->id())->get();
        $subtotal = $cartItems->sum(fn($item) => $item->product->final_price * $item->quantity);
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function add(Product $product)
    {
        $qty = request('quantity', 1);
        $existing = Cart::where('user_id', auth()->id())->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->increment('quantity', $qty);
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $qty,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang');
    }

    public function update(Cart $cart, Request $request)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->update(['quantity' => $request->quantity]);
        return back()->with('success', 'Jumlah produk berhasil diperbarui');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Produk berhasil dihapus dari keranjang');
    }

    public function buyNow(Product $product)
    {
        $qty = request('quantity', 1);

        Cart::where('user_id', auth()->id())->delete();

        Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $qty,
        ]);

        return redirect()->route('checkout');
    }
}
