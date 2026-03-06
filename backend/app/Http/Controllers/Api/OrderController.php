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
            'items'                  => 'required|array|min:1',
            'items.*.product_id'     => 'required|exists:products,id',
            'items.*.quantity'       => 'required|integer|min:1',
            'items.*.price'          => 'required|numeric|min:0',
            'items.*.variant'        => 'nullable|string|max:255',
            'shipping_address'       => 'required|string',
            'shipping_city'          => 'required|string',
            'shipping_province'      => 'required|string',
            'shipping_postal_code'   => 'nullable|string',
            'phone'                  => 'nullable|string',
            'notes'                  => 'nullable|string',
            'payment_method'         => 'required|in:gcash,cod,bank_transfer',
        ]);

        // Calculate total
        $total = collect($validated['items'])->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Generate unique order number
        $orderNumber = 'DM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

        // Create order
        $order = Order::create([
            'user_id' => $request->user()->id,
            'order_number' => $orderNumber,
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
            $product = \App\Models\Product::find($item['product_id']);
            $order->items()->create([
                'product_id'   => $item['product_id'],
                'product_name' => $product->name,
                'variant'      => $item['variant'] ?? null,
                'quantity'     => $item['quantity'],
                'price'        => $item['price'],
            ]);
        }

        $order->load(['items.product']);

        // Send email notification to customer
        try {
            \Illuminate\Support\Facades\Mail::to($request->user()->email)
                ->send(new \App\Mail\OrderConfirmationMail($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }

        // Send email notification to admin (if configured)
        try {
            $adminEmail = env('ADMIN_EMAIL');
            if ($adminEmail) {
                \Illuminate\Support\Facades\Mail::to($adminEmail)
                    ->send(new \App\Mail\AdminNewOrderMail($order));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send admin order notification email: ' . $e->getMessage());
        }

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

    /**
     * Display all orders (admin only)
     */
    public function adminIndex(Request $request)
    {
        $query = Order::query()->with(['items.product', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by order ID, customer name, or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                  });
            });
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
}
