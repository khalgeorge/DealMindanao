<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewOrderMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1', 'max:9999'],
            'items.*.price'      => ['required', 'numeric', 'min:0'],
            'shipping_name'      => ['required', 'string', 'max:255'],
            'shipping_phone'     => ['required', 'string', 'max:20'],
            'shipping_address'   => ['required', 'string', 'max:500'],
            'shipping_city'      => ['required', 'string', 'max:100'],
            'shipping_province'  => ['required', 'string', 'max:100'],
            'payment_method'     => ['required', 'in:cod,gcash,bank_transfer'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ]);

        // Calculate total from submitted items
        $total = collect($validated['items'])
            ->sum(fn ($item) => $item['price'] * $item['quantity']);

        do {
            $orderNumber = 'DM-' . Str::upper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        $order = Order::create([
            'user_id'          => auth()->id(),
            'order_number'     => $orderNumber,
            'total'            => $total,
            'status'           => 'pending',
            'payment_method'   => $validated['payment_method'],
            'shipping_address' => $validated['shipping_address'],
            'shipping_city'    => $validated['shipping_city'],
            'shipping_province'=> $validated['shipping_province'],
            'phone'            => $validated['shipping_phone'],
            'notes'            => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['product_id'],
                'product_name' => $product?->name ?? 'Unknown Product',
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
            ]);
        }

        $order->load('items');

        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new AdminNewOrderMail($order));
        }

        if (auth()->user()?->email) {
            Mail::to(auth()->user()->email)->send(new OrderConfirmationMail($order));
        }

        return redirect()->route('checkout.success', $order)
            ->with('order_number', $orderNumber);
    }

    public function success(Order $order)
    {
        return view('checkout-success', compact('order'));
    }
}
