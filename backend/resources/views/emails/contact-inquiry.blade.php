<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Inquiry</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f4f4f5; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        .header { background: #16a34a; padding: 32px 40px; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 800; margin: 0; letter-spacing: -0.3px; }
        .header p { color: #bbf7d0; font-size: 13px; margin: 6px 0 0; }
        .body { padding: 36px 40px; }
        .badge { display: inline-block; background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 4px 10px; margin-bottom: 24px; }
        .field { margin-bottom: 20px; }
        .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #9ca3af; margin-bottom: 4px; }
        .field-value { font-size: 15px; color: #111827; font-weight: 500; }
        .message-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-top: 24px; }
        .message-box .field-label { margin-bottom: 10px; }
        .message-box .message-text { font-size: 15px; color: #374151; line-height: 1.7; white-space: pre-wrap; }
        .divider { border: none; border-top: 1px solid #f3f4f6; margin: 28px 0; }
        .reply-hint { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 14px 18px; font-size: 13px; color: #1d4ed8; }
        .footer { padding: 20px 40px; background: #f9fafb; border-top: 1px solid #f3f4f6; font-size: 12px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>New Contact Form Inquiry</h1>
        <p>Received via dealmindanao.com/contact</p>
    </div>
    <div class="body">
        @php
            $subjectLabels = [
                'general'          => 'General Inquiry',
                'order_status'     => 'Order Status',
                'payment_delivery' => 'Payment & Delivery',
                'returns'          => 'Returns & Refunds',
                'partner'          => 'Partnership',
                'report'           => 'Report an Issue',
            ];
            $subjectLabel = $subjectLabels[$data['subject'] ?? 'general'] ?? ucfirst($data['subject'] ?? '');
        @endphp

        <span class="badge">{{ $subjectLabel }}</span>

        <div class="field">
            <div class="field-label">Full Name</div>
            <div class="field-value">{{ $data['name'] }}</div>
        </div>

        <div class="field">
            <div class="field-label">Email Address</div>
            <div class="field-value">
                <a href="mailto:{{ $data['email'] }}" style="color: #16a34a;">{{ $data['email'] }}</a>
            </div>
        </div>

        @if(!empty($data['phone']))
        <div class="field">
            <div class="field-label">Phone Number</div>
            <div class="field-value">{{ $data['phone'] }}</div>
        </div>
        @endif

        <div class="message-box">
            <div class="field-label">Message</div>
            <div class="message-text">{{ $data['message'] }}</div>
        </div>

        <hr class="divider">

        <div class="reply-hint">
            💡 <strong>Tip:</strong> Hit <em>Reply</em> to respond directly to {{ $data['name'] }} at {{ $data['email'] }}.
        </div>
    </div>
    <div class="footer">
        DealMindanao &mdash; Contact form submission &mdash; {{ now()->format('F j, Y g:i A') }}
    </div>
</div>
</body>
</html>
