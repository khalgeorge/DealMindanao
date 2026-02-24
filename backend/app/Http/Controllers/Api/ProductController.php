<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * Public visitors only see status=published + is_active=true.
     * Authenticated admins can see all products (needed for the admin panel).
     */
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'company']);

        // ─── Visibility filter ────────────────────────────────────────────────
        $user    = auth('api')->user();
        $isAdmin = $user && $user->is_admin;

        if (! $isAdmin) {
            // Public storefront: only show live, published products.
            $query->where('status', 'published')->where('is_active', true);
        }

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
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter discounted only
        if ($request->boolean('discount_only')) {
            $query->where('discount', '>', 0);
        }

        // Filter featured
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Admin-only status filter (draft | published)
        if ($isAdmin && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy    = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage  = $request->input('per_page', 15);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Display the specified product.
     * Public visitors can only view published + active products.
     */
    public function show(Product $product)
    {
        $user    = auth('api')->user();
        $isAdmin = $user && $user->is_admin;

        if (! $isAdmin && (! $product->is_active || $product->status !== 'published')) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->load(['category', 'company']);

        return response()->json($product);
    }

    /**
     * Store a newly created product (admin only).
     * Defaults to 'draft' so products are reviewed before going live.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        // Default new products to draft – admin must explicitly publish.
        $data['status']     = $data['status'] ?? 'draft';
        $data['is_active']  = $data['is_active'] ?? false;
        $data['is_featured'] = $data['is_featured'] ?? false;

        // Auto-generate unique slug from name if not provided.
        if (empty($data['slug'])) {
            $base = \Illuminate\Support\Str::slug($data['name']);
            $slug = $base;
            $i    = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $data['slug'] = $slug;
        }

        // Merge uploaded_images (comma-separated paths from blade admin) into images array.
        $images = array_filter((array) ($data['images'] ?? []));
        if (! empty($data['uploaded_images'])) {
            $uploaded = array_filter(explode(',', $data['uploaded_images']));
            $images   = array_values(array_unique(array_merge($images, $uploaded)));
        }
        $data['images'] = array_values($images) ?: null;
        unset($data['uploaded_images']);

        $product = Product::create($data);
        $product->load(['category', 'company']);

        return response()->json($product, 201);
    }

    /**
     * Update the specified product (admin only).
     */
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();

        // If status changes to published, ensure is_active is also true.
        if (isset($data['status']) && $data['status'] === 'published') {
            $data['is_active'] = $data['is_active'] ?? true;
        }

        // Regenerate slug only when name changes.
        if (isset($data['name']) && $data['name'] !== $product->name) {
            $base = \Illuminate\Support\Str::slug($data['name']);
            $slug = $base;
            $i    = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $data['slug'] = $slug;
        }

        // Merge uploaded_images into images array.
        if (array_key_exists('images', $data) || array_key_exists('uploaded_images', $data)) {
            $images = array_filter((array) ($data['images'] ?? []));
            if (! empty($data['uploaded_images'])) {
                $uploaded = array_filter(explode(',', $data['uploaded_images']));
                $images   = array_values(array_unique(array_merge($images, $uploaded)));
            }
            $data['images'] = array_values($images) ?: null;
        }
        unset($data['uploaded_images']);

        $product->update($data);
        $product->load(['category', 'company']);

        return response()->json($product);
    }

    /**
     * Remove the specified product (admin only).
     * Products referenced by orders cannot be deleted.
     */
    public function destroy(Product $product)
    {
        if ($product->orderItems()->count() > 0) {
            return response()->json([
                'message' => 'This product cannot be deleted because it is referenced by existing orders.',
            ], 422);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }
}
