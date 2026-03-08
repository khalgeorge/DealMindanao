<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Mail\OrderConfirmationMail;
use App\Mail\AdminNewOrderMail;
use App\Http\Requests\CheckoutRequest;

class CheckoutController extends Controller
{
    public function index()
    {
        $defaultAddress = \App\Models\Address::where('user_id', Auth::id())
            ->where('is_default', true)
            ->first();

        $user = Auth::user();

        return view('checkout', compact('defaultAddress', 'user'));
    }
    
    public function store(CheckoutRequest $request)
    {
        $validated = $request->validated();
        
        // Calculate total
        $total = collect($validated['items'])->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        
        // Generate unique order number
        $orderNumber = 'DM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        
        // Create order
        $order = Order::create([
            'user_id'          => Auth::id(),
            'order_number'     => $orderNumber,
            'total'            => $total,
            'status'           => 'pending',
            'shipping_address' => $validated['shipping_address'],
            'shipping_city'    => $validated['shipping_city'],
            'shipping_province'=> $validated['shipping_province'],
            'phone'            => $validated['shipping_phone'],
            'notes'            => $validated['notes'] ?? null,
            'payment_method'   => $validated['payment_method'],
        ]);
        
        // Create order items
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $order->items()->create([
                'product_id' => $item['product_id'],
                'product_name' => $product->name,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
        
        $order->load(['items.product', 'user']);
        
        // Send emails
        try {
            $user = Auth::user();
            if ($user) {
                Mail::to($user->email)->send(new OrderConfirmationMail($order));
            }
            
            if ($adminEmail = config('mail.admin_email')) {
                Mail::to($adminEmail)->send(new AdminNewOrderMail($order));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order emails: ' . $e->getMessage());
        }
        
        return redirect()->route('checkout.success', $order->id)
            ->with('success', 'Order placed successfully!');
    }
    
    public function success($orderId)
    {
        $user = Auth::user();
        
        $order = Order::with(['items.product'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        return view('checkout-success', compact('order'));
    }
}
