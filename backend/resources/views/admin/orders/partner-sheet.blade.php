<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order {{ $order->order_number }} – Partner Sheet</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: Arial, sans-serif; font-size: 14px; color: #111; padding: 32px; }
    h1 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
    .meta { font-size: 12px; color: #555; margin-bottom: 24px; }
    .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase;
                     letter-spacing: .05em; color: #555; margin-bottom: 8px; }
    table { width: 100%; border-collapse: collapse; }
    thead tr { background: #f3f4f6; }
    th { text-align: left; padding: 8px 12px; font-size: 12px;
         font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
         border-bottom: 2px solid #e5e7eb; }
    td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }
    .qty { font-weight: 700; text-align: center; }
    .variant { font-size: 12px; color: #6b7280; margin-top: 2px; }
    .shipping { margin-top: 28px; padding: 16px; background: #f9fafb;
                border: 1px solid #e5e7eb; border-radius: 6px; }
    .shipping p { margin-bottom: 4px; font-size: 13px; }
    .shipping strong { display: inline-block; min-width: 90px; color: #374151; }
    .notes { margin-top: 16px; padding: 12px 16px; background: #fffbeb;
             border: 1px solid #fde68a; border-radius: 6px; font-size: 13px; }
    .footer { margin-top: 32px; font-size: 11px; color: #9ca3af; text-align: center; }
    @media print {
      body { padding: 16px; }
      .no-print { display: none !important; }
      @page { margin: 1cm; }
    }
  </style>
</head>
<body>

  {{-- Print / Close buttons (hidden when printing) --}}
  <div class="no-print" style="margin-bottom:20px; display:flex; gap:10px;">
    <button onclick="window.print()"
            style="padding:8px 18px; background:#16a34a; color:#fff; border:none;
                   border-radius:6px; cursor:pointer; font-size:14px; font-weight:600;">
      🖨 Print / Save PDF
    </button>
    <button onclick="window.close()"
            style="padding:8px 18px; background:#f3f4f6; color:#374151; border:1px solid #d1d5db;
                   border-radius:6px; cursor:pointer; font-size:14px;">
      Close
    </button>
  </div>

  <h1>Order {{ $order->order_number }}</h1>
  <p class="meta">Placed on {{ $order->created_at->format('F j, Y, g:i A') }}</p>

  {{-- Items table (no price columns) --}}
  <p class="section-title">Items to Prepare</p>
  <table>
    <thead>
      <tr>
        <th style="width:35%">Product</th>
        <th style="width:15%">Brand</th>
        <th style="width:15%">Model Code</th>
        <th style="width:20%">Variant</th>
        <th class="qty" style="width:8%">Qty</th>
        <th style="width:7%">SKU</th>
      </tr>
    </thead>
    <tbody>
      @foreach($order->items as $item)
      <tr>
        <td>{{ $item->product_name }}</td>
        <td style="font-size:12px;">{{ $item->product?->brand?->name ?? '—' }}</td>
        <td style="font-size:12px;">{{ $item->product?->model_code ?? '—' }}</td>
        <td style="font-size:12px;">{{ $item->variant ?? '—' }}</td>
        <td class="qty">{{ $item->quantity }}</td>
        <td style="font-size:12px; color:#6b7280;">{{ $item->product?->sku ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  {{-- Shipping destination (partner needs to know where to send / pack for) --}}
  <div class="shipping">
    <p class="section-title" style="margin-bottom:10px;">Ship To</p>
    <p><strong>Name:</strong> {{ $order->customer_name ?? $order->user?->name ?? '—' }}</p>
    <p><strong>Phone:</strong> {{ $order->phone ?? $order->user?->phone ?? '—' }}</p>
    <p><strong>Address:</strong> {{ $order->shipping_address ?? '—' }}</p>
    <p><strong>City:</strong> {{ $order->shipping_city ?? '—' }}</p>
    <p><strong>Province:</strong> {{ $order->shipping_province ?? '—' }}</p>
    @if($order->shipping_postal_code)
    <p><strong>ZIP:</strong> {{ $order->shipping_postal_code }}</p>
    @endif
  </div>

  @if($order->notes)
  <div class="notes">
    <strong>Order Notes:</strong> {{ $order->notes }}
  </div>
  @endif

  <p class="footer">Generated {{ now()->format('F j, Y') }} &middot; For partner use only &middot; Prices omitted</p>

</body>
</html>
