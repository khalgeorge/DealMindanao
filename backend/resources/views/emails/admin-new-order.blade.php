<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Order</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827;">
    <h2 style="margin-bottom: 6px;">New Order Received</h2>
    <p style="margin-top: 0;">Order Number: <strong>{{ $order->order_number }}</strong></p>

    <h3 style="margin-bottom: 6px;">Customer Details</h3>
    <p style="margin: 0;">Name: {{ $order->customer_name }}</p>
    <p style="margin: 0;">Email: {{ $order->email }}</p>
    <p style="margin: 0;">Phone: {{ $order->phone }}</p>
    <p style="margin: 0 0 12px;">Address: {{ $order->address }}</p>

    @if($order->notes)
        <h3 style="margin-bottom: 6px;">Notes</h3>
        <p style="margin-top: 0;">{{ $order->notes }}</p>
    @endif

    <h3 style="margin-bottom: 6px;">Items</h3>
    <table style="width: 100%; border-collapse: collapse;" cellspacing="0" cellpadding="6">
        <thead>
            <tr style="background: #f3f4f6; text-align: left;">
                <th style="border: 1px solid #e5e7eb;">Item</th>
                <th style="border: 1px solid #e5e7eb;">Qty</th>
                <th style="border: 1px solid #e5e7eb;">Price</th>
                <th style="border: 1px solid #e5e7eb;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td style="border: 1px solid #e5e7eb;">{{ $item->product_name }}</td>
                    <td style="border: 1px solid #e5e7eb;">{{ $item->quantity }}</td>
                    <td style="border: 1px solid #e5e7eb;">{{ number_format($item->price, 2) }}</td>
                    <td style="border: 1px solid #e5e7eb;">{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
