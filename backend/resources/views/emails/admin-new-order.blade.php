<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Order</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; background: #f9fafb; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom: 6px; color: #059669;">New Order Received</h2>
        <p style="margin-top: 0;">Order Number: <strong>{{ $order->order_number }}</strong></p>

        <h3 style="margin-bottom: 12px; font-size: 16px; color: #111827;">Customer Details</h3>
        <div style="background: #f9fafb; padding: 16px; border-radius: 6px; margin-bottom: 24px;">
            <p style="margin: 0 0 8px;">Name: <strong>{{ $order->user->name }}</strong></p>
            <p style="margin: 0 0 8px;">Email: <strong>{{ $order->user->email }}</strong></p>
            <p style="margin: 0;">Phone: <strong>{{ $order->phone ?? 'N/A' }}</strong></p>
        </div>

        <h3 style="margin-bottom: 12px; font-size: 16px; color: #111827;">Shipping Address</h3>
        <div style="background: #f9fafb; padding: 16px; border-radius: 6px; margin-bottom: 24px;">
            <p style="margin: 0;">
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_province }}
            </p>
        </div>

        @if($order->notes)
            <h3 style="margin-bottom: 6px; font-size: 16px; color: #111827;">Notes</h3>
            <p style="margin-top: 0; background: #fef3c7; padding: 12px; border-radius: 6px; color: #92400e;">{{ $order->notes }}</p>
        @endif

        <h3 style="margin-bottom: 12px; font-size: 16px; color: #111827;">Order Items</h3>
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;" cellspacing="0" cellpadding="8">
            <thead>
                <tr style="background: #f3f4f6; text-align: left; border-bottom: 2px solid #e5e7eb;">
                    <th style="padding: 12px; font-size: 14px; color: #6b7280;">Item</th>
                    <th style="padding: 12px; font-size: 14px; color: #6b7280; text-align: center;">Qty</th>
                    <th style="padding: 12px; font-size: 14px; color: #6b7280; text-align: right;">Price</th>
                    <th style="padding: 12px; font-size: 14px; color: #6b7280; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px; font-size: 14px;">
                            {{ $item->product_name }}
                            @if($item->variant)
                                <br><span style="font-size: 12px; color: #059669; font-weight: 600; background: #d1fae5; padding: 1px 6px; border-radius: 4px;">{{ $item->variant }}</span>
                            @endif
                        </td>
                        <td style="padding: 12px; text-align: center; font-size: 14px;">{{ $item->quantity }}</td>
                        <td style="padding: 12px; text-align: right; font-size: 14px;">₱{{ number_format($item->price, 2) }}</td>
                        <td style="padding: 12px; text-align: right; font-size: 14px; font-weight: 600;">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="padding: 16px 12px 12px; text-align: right; font-weight: bold; font-size: 16px;">Total</td>
                    <td style="padding: 16px 12px 12px; text-align: right; font-weight: bold; font-size: 18px; color: #059669;">₱{{ number_format($order->total, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="background: #f9fafb; padding: 16px; border-radius: 6px;">
            <p style="margin: 0 0 8px;"><strong>Payment Method:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
            <p style="margin: 0;"><strong>Order Status:</strong> <span style="color: #d97706;">{{ ucfirst($order->status) }}</span></p>
        </div>
    </div>
</body>
</html>
