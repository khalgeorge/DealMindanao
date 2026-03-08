<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\OrderUpdateRequest;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        $orders = $query->latest()->paginate(15);
        $statuses = ['pending', 'contacted', 'processing', 'completed', 'cancelled'];
        $search = $request->search ?? '';
        
        return view('admin.orders.index', compact('orders', 'statuses', 'search'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'items.product.supplier', 'items.product.category']);
        
        return view('admin.orders.show', compact('order'));
    }
    
    public function showJson(Order $order)
    {
        $order->load(['user', 'items.product']);
        
        return response()->json($order);
    }
    
    public function update(OrderUpdateRequest $request, Order $order)
    {
        $order->update($request->validated());
        
        // TODO: Send email notification to customer about status change
        
        return back()->with('success', 'Order updated successfully!');
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,processing,completed,cancelled',
        ]);
        
        $order->update(['status' => $request->status]);
        
        // Return JSON for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Order status updated to ' . $request->status,
                'order' => $order->load(['items'])
            ]);
        }
        
        return back()->with('success', 'Order status updated to ' . $request->status . '!');
    }
}
