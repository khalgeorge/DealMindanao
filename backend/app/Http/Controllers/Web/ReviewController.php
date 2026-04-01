<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Submit a product review.
     */
    public function storeProduct(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title'  => ['nullable', 'string', 'max:150'],
            'body'   => ['nullable', 'string', 'max:2000'],
        ]);

        // One review per user per product
        $existing = Review::where('reviewable_type', Product::class)
            ->where('reviewable_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        Review::create([
            'reviewable_type' => Product::class,
            'reviewable_id'   => $product->id,
            'user_id'         => Auth::id(),
            'rating'          => $request->rating,
            'title'           => $request->title,
            'body'            => $request->body,
            'is_approved'     => false,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is pending approval.');
    }

    /**
     * Submit a seller/supplier review.
     */
    public function storeSupplier(Request $request, Supplier $supplier)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title'  => ['nullable', 'string', 'max:150'],
            'body'   => ['nullable', 'string', 'max:2000'],
        ]);

        // One review per user per supplier
        $existing = Review::where('reviewable_type', Supplier::class)
            ->where('reviewable_id', $supplier->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this seller.');
        }

        Review::create([
            'reviewable_type' => Supplier::class,
            'reviewable_id'   => $supplier->id,
            'user_id'         => Auth::id(),
            'rating'          => $request->rating,
            'title'           => $request->title,
            'body'            => $request->body,
            'is_approved'     => false,
        ]);

        return back()->with('success', 'Thank you! Your seller review has been submitted and is pending approval.');
    }
}
