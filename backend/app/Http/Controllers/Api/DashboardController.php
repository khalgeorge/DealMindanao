<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function statistics()
    {
        // Total revenue from all order items
        $totalRevenue = DB::table('order_items')
            ->selectRaw('SUM(price * quantity) as total')
            ->value('total') ?? 0;
        
        // Total orders count
        $totalOrders = Order::count();
        
        // Active suppliers/partners count
        $activePartners = Supplier::where('is_active', true)->count();
        
        // Pending orders count
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Revenue comparison (last week vs this week)
        $lastWeekRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [
                now()->subWeeks(2)->startOfWeek(),
                now()->subWeek()->endOfWeek()
            ])
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total')
            ->value('total') ?? 1;
        
        $thisWeekRevenue = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [
                now()->startOfWeek(),
                now()
            ])
            ->selectRaw('SUM(order_items.price * order_items.quantity) as total')
            ->value('total') ?? 0;
        
        $revenueChange = $lastWeekRevenue > 0 
            ? round((($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100, 1)
            : 0;
        
        // Orders today
        $ordersToday = Order::whereDate('created_at', today())->count();
        
        return response()->json([
            'total_revenue' => round($totalRevenue, 2),
            'total_orders' => $totalOrders,
            'active_partners' => $activePartners,
            'pending_orders' => $pendingOrders,
            'revenue_change_percent' => $revenueChange,
            'orders_today' => $ordersToday,
        ]);
    }
    
    /**
     * Get recent orders for dashboard
     */
    public function recentOrders(Request $request)
    {
        $limit = $request->input('limit', 5);
        
        $orders = Order::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($order) {
                // Calculate total from order items
                $total = DB::table('order_items')
                    ->where('order_id', $order->id)
                    ->selectRaw('SUM(price * quantity) as total')
                    ->value('total') ?? 0;
                
                // Parse address to get city
                $addressParts = explode(',', $order->address);
                $city = trim(end($addressParts));
                
                return [
                    'id' => $order->id,
                    'order_number' => '#DM-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->email,
                    'shipping_city' => $city,
                    'total_amount' => round($total, 2),
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                ];
            });
        
        return response()->json($orders);
    }
}
