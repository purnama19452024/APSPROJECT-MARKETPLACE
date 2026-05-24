<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->get();
        $categories = Category::where('is_active', true)->get();
        $flashSaleProducts = Product::active()->flashSale()->take(10)->get();
        $featuredProducts = Product::active()->featured()->take(8)->get();
        $newProducts = Product::active()->latest()->take(12)->get();
        $popularProducts = Product::active()->withCount('reviews')->orderBy('reviews_count', 'desc')->take(8)->get();

        return view('landing', compact(
            'banners', 'categories', 'flashSaleProducts',
            'featuredProducts', 'newProducts', 'popularProducts'
        ));
    }
}
