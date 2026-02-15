<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'company']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->filled('company')) {
            $query->where('company_id', $request->company);
        }
        
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $products = $query->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        
        return view('admin.products.index', compact('products', 'categories', 'companies'));
    }
    
    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $companies = Company::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'companies'));
    }
    
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        
        $product = Product::create($data);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }
    
    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'companies'));
    }
    
    public function update(ProductRequest $request, Product $product)
    {
        $data = $request->validated();
        
        $product->update($data);
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }
    
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
    
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        return back()->with('success', 'Product status updated!');
    }
    
    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        
        return back()->with('success', 'Featured status updated!');
    }
}
