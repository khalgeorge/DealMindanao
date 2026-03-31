<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_suppliers' => Supplier::where('is_active', true)->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
        ];
        // Net profit = revenue collected − partner cost for fulfilled order items
        $profitData = DB::selectOne("
            SELECT
                COALESCE(SUM(oi.price * oi.quantity), 0)           AS revenue,
                COALESCE(SUM(p.supplier_price * oi.quantity), 0)   AS partner_cost
            FROM order_items oi
            JOIN orders   o ON o.id  = oi.order_id
            JOIN products p ON p.id  = oi.product_id
            WHERE o.status != 'cancelled'
        ");
        $stats['net_profit']    = ($profitData->revenue ?? 0) - ($profitData->partner_cost ?? 0);
        $stats['partner_cost']  = $profitData->partner_cost ?? 0;
        $stats['low_stock_count'] = Product::where('stock_quantity', '<', 100)->where('is_active', true)->count();        
        $recentOrders = Order::with(['user', 'items'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
