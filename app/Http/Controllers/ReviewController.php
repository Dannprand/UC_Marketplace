<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|max:1000',
        'order_id' => 'required|exists:orders,id'
    ]);

    // Verify user has a delivered order for this product
    $order = Order::where('id', $request->order_id)
        ->where('user_id', auth()->id())
        ->where('status', 'delivered')
        ->whereHas('items', function ($query) use ($product) {
            $query->where('product_id', $product->id);
        })
        ->first();

    if (!$order) {
        return back()->with('error', 'You can only review products from delivered orders');
    }

    // Check for existing review
    $existingReview = Review::where('user_id', auth()->id())
        ->where('product_id', $product->id)
        ->where('order_id', $order->id)
        ->exists();

    if ($existingReview) {
        return back()->with('error', 'You have already reviewed this product');
    }

    // Create review
    Review::create([
        'user_id' => auth()->id(),
        'product_id' => $product->id,
        'order_id' => $request->order_id,
        'rating' => $request->rating,
        'comment' => $request->comment
    ]);

    // Recalculate average rating
    $averageRating = Review::where('product_id', $product->id)->avg('rating');
    $reviewCount = Review::where('product_id', $product->id)->count();

    // Update product
    $product->update([
        'average_rating' => $averageRating,
        'review_count' => $reviewCount
    ]);

    return back()->with('success', 'Review submitted successfully');
}
}