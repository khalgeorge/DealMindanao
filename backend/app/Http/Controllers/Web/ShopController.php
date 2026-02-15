<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'company'])
            ->where('is_active', true);
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('company', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price-low':
                $query->orderByRaw('price * (1 - COALESCE(discount, 0) / 100) ASC');
                break;
            case 'price-high':
                $query->orderByRaw('price * (1 - COALESCE(discount, 0) / 100) DESC');
                break;
            case 'popular':
                $query->orderBy('is_featured', 'desc');
                break;
            default: // newest
                $query->latest();
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('shop', compact('products', 'categories'));
    }
    
    public function show($slug)
    {
        $product = Product::with(['category', 'company'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();
        
        // Get related products
        $relatedProducts = Product::with(['category', 'company'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();
        
        return view('product', compact('product', 'relatedProducts'));
    }
}
