<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = trim((string) $request->query('q', ''));
        $validStatuses = ['pending', 'contacted', 'processing', 'completed'];

        $ordersQuery = Order::with('items')->latest();

        if ($status && in_array($status, $validStatuses, true)) {
            $ordersQuery->where('status', $status);
        }

        if ($search !== '') {
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('order_number', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
        }

        $orders = $ordersQuery->paginate(10)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'activeStatus' => $status,
            'statuses' => $validStatuses,
            'search' => $search,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('admin.orders.show', [
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validStatuses = ['pending', 'contacted', 'processing', 'completed'];

        $data = $request->validate([
            'status' => 'required|string|in:' . implode(',', $validStatuses),
        ]);

        $order->update([
            'status' => $data['status'],
        ]);

        return back()->with('success', 'Order status updated.');
    }
}
