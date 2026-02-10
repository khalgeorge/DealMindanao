<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('q');
        $companyId = $request->query('company');
        $categoryId = $request->query('category');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $discountOnly = $request->boolean('discount_only');

        $sort = $request->query('sort', 'latest');

        $query = Product::where('is_active', true)
            ->with('company');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if (is_numeric($minPrice)) {
            $query->where('price', '>=', (float) $minPrice);
        }

        if (is_numeric($maxPrice)) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        if ($discountOnly) {
            $query->where('discount', '>', 0);
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'name') {
            $query->orderBy('name');
        } else {
            $query->latest();
        }

        $products = $query->paginate(9)->appends($request->query());
        $companies = Company::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('shop', [
            'products' => $products,
            'companies' => $companies,
            'categories' => $categories,
            'search' => $search,
            'companyId' => $companyId,
            'categoryId' => $categoryId,
            'sort' => $sort,
        ]);
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('company')
            ->firstOrFail();

        return view('product', compact('product'));
    }

    public function search(Request $request)
    {
        $search = $request->query('q');
        $companyId = $request->query('company');
        $categoryId = $request->query('category');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $discountOnly = $request->boolean('discount_only');

        $sort = $request->query('sort', 'latest');

        $query = Product::where('is_active', true)
            ->with('company');

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if (is_numeric($minPrice)) {
            $query->where('price', '>=', (float) $minPrice);
        }

        if (is_numeric($maxPrice)) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        if ($discountOnly) {
            $query->where('discount', '>', 0);
        }

        if ($sort === 'price_asc') {
            $query->orderBy('price');
        } elseif ($sort === 'price_desc') {
            $query->orderByDesc('price');
        } elseif ($sort === 'name') {
            $query->orderBy('name');
        } else {
            $query->latest();
        }

        $products = $query->paginate(9);

        $items = $products->getCollection()->map(function (Product $product) {
            $imageUrl = null;
            if ($product->images && count($product->images)) {
                $imageUrl = \Illuminate\Support\Facades\Storage::url($product->images[0]);
            }

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'discount' => $product->discount,
                'company' => $product->company?->name,
                'image_url' => $imageUrl,
            ];
        });

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }
}
