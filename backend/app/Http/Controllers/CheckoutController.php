<?php

namespace App\Http\Controllers;

use App\Mail\AdminNewOrderMail;
use App\Models\Order;
use App\Models\OrderItem;
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
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Your cart is empty.');
        }

        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:30',
            'address' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        do {
            $orderNumber = 'DM-' . Str::upper(Str::random(8));
        } while (Order::where('order_number', $orderNumber)->exists());

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'status' => 'pending',
            'notes' => $data['notes'] ?? null,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        $order->load('items');
        $adminEmail = env('ADMIN_EMAIL', config('mail.from.address'));
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new AdminNewOrderMail($order));
        }

        session()->forget('cart');

        return redirect('/checkout/success')->with('order_number', $orderNumber);
    }
}
