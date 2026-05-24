<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('product.primaryImage')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);
        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'images' => 'nullable|array|max:3',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $exists = Review::where('user_id', auth()->id())
            ->where('product_id', $request->product_id)
            ->where('transaction_id', $transaction->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini');
        }

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $imagePaths[] = $img->store('reviews', 'public');
            }
        }

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $request->product_id,
            'transaction_id' => $transaction->id,
            'rating' => $request->rating,
            'review' => $request->review,
            'images' => $imagePaths,
        ]);

        return back()->with('success', 'Ulasan berhasil diberikan');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) abort(403);
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus');
    }
}
