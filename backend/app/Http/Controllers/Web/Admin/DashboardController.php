<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Company;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_companies' => Company::count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
        ];
        
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
