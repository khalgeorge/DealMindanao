<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     */
    public function index(Request $request)
    {
        $query = Order::query()
            ->where('user_id', $request->user()->id)
            ->with(['items.product']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Paginate
        $perPage = $request->input('per_page', 15);
        $orders = $query->paginate($perPage);

        return response()->json($orders);
    }

    /**
     * Display the specified order
     */
    public function show(Request $request, Order $order)
    {
        // Ensure user can only view their own orders
        if ($order->user_id !== $request->user()->id && !$request->user()->is_admin) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $order->load(['items.product', 'user']);
        
        return response()->json($order);
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_province' => 'required|string',
            'shipping_postal_code' => 'required|string',
            'phone' => 'required|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:gcash,cod,bank_transfer',
        ]);

        // Calculate total
        $total = collect($validated['items'])->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Create order
        $order = Order::create([
            'user_id' => $request->user()->id,
            'total' => $total,
            'status' => 'pending',
            'shipping_address' => $validated['shipping_address'],
            'shipping_city' => $validated['shipping_city'],
            'shipping_province' => $validated['shipping_province'],
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'phone' => $validated['phone'],
            'notes' => $validated['notes'] ?? null,
            'payment_method' => $validated['payment_method'],
        ]);

        // Create order items
        foreach ($validated['items'] as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $order->load(['items.product']);

        // TODO: Send email notifications to admin and user

        return response()->json($order, 201);
    }

    /**
     * Update the specified order (admin only - status updates)
     */
    public function update(Request $request, Order $order)
    {
        // Only admin can update orders
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string',
        ]);

        $order->update($validated);
        $order->load(['items.product', 'user']);

        // TODO: Send status update email to user

        return response()->json($order);
    }

    /**
     * Remove the specified order (soft delete, user can cancel pending orders)
     */
    public function destroy(Request $request, Order $order)
    {
        // User can only cancel their own pending orders
        if ($order->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot cancel order that is already being processed',
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Order cancelled successfully',
        ]);
    }
}
