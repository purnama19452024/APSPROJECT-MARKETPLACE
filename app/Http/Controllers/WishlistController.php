<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('product.primaryImage', 'product.reviews')->where('user_id', auth()->id())->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Product $product)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->first();
        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Produk dihapus dari wishlist');
        } else {
            Wishlist::create(['user_id' => auth()->id(), 'product_id' => $product->id]);
            return back()->with('success', 'Produk ditambahkan ke wishlist');
        }
    }
}
