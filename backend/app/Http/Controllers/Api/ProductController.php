<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'company']);

        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by company
        if ($request->has('company')) {
            $query->where('company_id', $request->company);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere(function ($q) use ($request) {
                      $q->whereNull('sale_price')
                        ->where('price', '>=', $request->min_price);
                  });
            });
        }

        if ($request->has('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere(function ($q) use ($request) {
                      $q->whereNull('sale_price')
                        ->where('price', '<=', $request->max_price);
                  });
            });
        }

        // Filter discounted only
        if ($request->has('discount_only') && $request->discount_only) {
            $query->whereNotNull('sale_price')
                  ->whereRaw('sale_price < price');
        }

        // Filter featured
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = $request->input('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'company']);
        
        return response()->json($product);
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'company_id' => 'required|exists:companies,id',
            'image_url' => 'nullable|url',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $product = Product::create($validated);
        $product->load(['category', 'company']);

        return response()->json($product, 201);
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'sometimes|required|exists:categories,id',
            'company_id' => 'sometimes|required|exists:companies,id',
            'image_url' => 'nullable|url',
            'stock' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $product->update($validated);
        $product->load(['category', 'company']);

        return response()->json($product);
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
