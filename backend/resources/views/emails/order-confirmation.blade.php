<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; background: #f9fafb; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="text-align: center; margin-bottom: 32px;">
            <h1 style="color: #059669; margin: 0; font-size: 28px;">Order Confirmed!</h1>
            <p style="color: #6b7280; margin: 8px 0 0;">Thank you for your order</p>
        </div>

        <div style="background: #f3f4f6; padding: 16px; border-radius: 6px; margin-bottom: 24px;">
            <p style="margin: 0; color: #6b7280; font-size: 14px;">Order Number</p>
            <p style="margin: 4px 0 0; font-size: 20px; font-weight: bold; color: #111827;">{{ $order->order_number }}</p>
        </div>

        <h2 style="font-size: 18px; margin-bottom: 12px; color: #111827;">Order Details</h2>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;" cellspacing="0" cellpadding="8">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                    <th style="text-align: left; padding: 12px; font-size: 14px; color: #6b7280;">Item</th>
                    <th style="text-align: center; padding: 12px; font-size: 14px; color: #6b7280;">Qty</th>
                    <th style="text-align: right; padding: 12px; font-size: 14px; color: #6b7280;">Price</th>
                    <th style="text-align: right; padding: 12px; font-size: 14px; color: #6b7280;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px; font-size: 14px;">
                            {{ $item->product_name }}
                            @if($item->variant)
                                <br><span style="font-size: 12px; color: #059669; font-weight: 600;">{{ $item->variant }}</span>
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
                    <td colspan="3" style="padding: 16px 12px 12px; text-align: right; font-weight: bold; font-size: 16px; color: #111827;">Total</td>
                    <td style="padding: 16px 12px 12px; text-align: right; font-weight: bold; font-size: 18px; color: #059669;">₱{{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; margin-bottom: 8px; color: #111827;">Shipping Address</h3>
            <p style="margin: 0; color: #6b7280; line-height: 1.6;">
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_province }}<br>
                Phone: {{ $order->phone ?? $order->user->email }}
            </p>
        </div>

        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 16px; margin-bottom: 8px; color: #111827;">Payment Method</h3>
            <p style="margin: 0; color: #6b7280;">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>
        </div>

        <div style="background: #f9fafb; padding: 16px; border-radius: 6px; margin-top: 32px;">
            <p style="margin: 0; color: #6b7280; font-size: 14px; text-align: center;">
                We'll send you a shipping confirmation email as soon as your order ships.
            </p>
        </div>

        <div style="text-align: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                DealMindanao - Authentic Products from Mindanao<br>
                If you have any questions, please contact us.
            </p>
        </div>
    </div>
</body>
</html>
