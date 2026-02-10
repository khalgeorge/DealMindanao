<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('company')
            ->latest()
            ->take(6)
            ->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
