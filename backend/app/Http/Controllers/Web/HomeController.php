<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::with(['category', 'company'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->take(8)
            ->get();
        
        // Get all categories
        $categories = Category::withCount('products')->get();
        
        return view('home', compact('featuredProducts', 'categories'));
    }
}
