<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Company;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
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

        $query = Product::with('company', 'category');

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

        $products = $query->paginate(10)->appends($request->query());
        $companies = Company::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', [
            'products' => $products,
            'companies' => $companies,
            'categories' => $categories,
            'search' => $search,
            'companyId' => $companyId,
            'categoryId' => $categoryId,
            'sort' => $sort,
        ]);
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', [
            'companies' => $companies,
            'categories' => $categories,
            'product' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']).'-'.Str::random(5);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        // Handle images (optional)
        if ($request->hasFile('images')) {
            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('products', 'public');
            }
            $data['images'] = $paths;
        }

        Product::create($data);

        return redirect('/admin/products')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $companies = Company::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.form', [
            'companies' => $companies,
            'categories' => $categories,
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'company_id' => 'required|exists:companies,id',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('images')) {
            if ($product->images) {
                foreach ($product->images as $path) {
                    Storage::disk('public')->delete($path);
                }
            }

            $paths = [];
            foreach ($request->file('images') as $img) {
                $paths[] = $img->store('products', 'public');
            }
            $data['images'] = $paths;
        }

        $product->update($data);

        return redirect('/admin/products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->images) {
            foreach ($product->images as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $product->delete();

        return redirect('/admin/products')->with('success', 'Product deleted successfully.');
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

        $query = Product::with('company', 'category');

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

        $products = $query->paginate(10);

        $items = $products->getCollection()->map(function (Product $product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'company' => $product->company?->name,
                'is_active' => (bool) $product->is_active,
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
