<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $allProducts = Product::with(['category', 'supplier', 'brand'])
            ->withCount(['reviews as review_count' => fn($q) => $q->where('is_approved', true)])
            ->withAvg(['reviews as avg_rating' => fn($q) => $q->where('is_approved', true)], 'rating')
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function ($p) {
                $image = product_image_url($p->images ?? []);

                return [
                    'id'               => $p->id,
                    'name'             => $p->name,
                    'slug'             => $p->slug,
                    'price'            => (float) $p->price,
                    'discount'         => (float) ($p->discount ?? 0),
                    'is_on_promo'      => $p->isOnPromo(),
                    'display_price'    => $p->displayPrice(),
                    'discount_percent' => $p->discountPercent(),
                    'promo_label'      => $p->promo_label,
                    'description'      => $p->description,
                    'image'            => $image,
                    'category_id'      => $p->category_id,
                    'category'         => $p->category?->slug ?? '',
                    'category_name'    => $p->category?->name ?? '',
                    'supplier_id'      => $p->supplier_id,
                    'supplier'         => $p->supplier?->name ?? '',
                    'region'           => $p->supplier?->region ?? '',
                    'variant'          => $p->variant,
                    'variants'         => $p->variants,
                    'avg_rating'       => round((float) ($p->avg_rating ?? 0), 1),
                    'review_count'     => (int) ($p->review_count ?? 0),
                ];
            })
            ->values();

        return view('shop', compact('allProducts', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'supplier', 'brand'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Eager-load approved reviews with user info
        $product->load(['reviews' => fn($q) => $q->approved()->latest()->with('user')]);
        $supplierReviews = $product->supplier
            ? $product->supplier->reviews()->approved()->latest()->with('user')->get()
            : collect();

        $relatedProducts = Product::with(['category', 'supplier', 'brand'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('product', compact('product', 'relatedProducts', 'supplierReviews'));
    }
}
