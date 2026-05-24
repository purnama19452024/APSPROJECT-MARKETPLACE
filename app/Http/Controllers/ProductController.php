<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::active()->with(['category', 'primaryImage', 'reviews'])->latest()->paginate(12);
        $categories = Category::where('is_active', true)->get();
        return view('products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::active()->with(['category', 'images', 'reviews.user', 'primaryImage'])->where('slug', $slug)->firstOrFail();
        $relatedProducts = Product::active()->where('category_id', $product->category_id)->where('id', '!=', $product->id)->take(5)->get();

        $isWishlisted = auth()->check() && auth()->user()->wishlists()->where('product_id', $product->id)->exists();

        return view('products.show', compact('product', 'relatedProducts', 'isWishlisted'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::active()->where('category_id', $category->id)->with(['category', 'primaryImage'])->paginate(12);
        $categories = Category::where('is_active', true)->get();
        return view('products.index', compact('products', 'categories', 'category'));
    }

    public function search()
    {
        $query = request('q');
        $products = Product::active()->where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with(['category', 'primaryImage'])
            ->paginate(12);
        $categories = Category::where('is_active', true)->get();
        return view('products.index', compact('products', 'categories', 'query'));
    }
}
