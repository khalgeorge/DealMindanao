<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    private string $botToken;
    private string $chatId;

    public function __construct()
    {
        $this->botToken = config('services.telegram.bot_token', '');
        $this->chatId   = config('services.telegram.chat_id', '');
    }

    public function isConfigured(): bool
    {
        return !empty($this->botToken) && !empty($this->chatId);
    }

    public function sendMessage(string $message): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::timeout(5)->post(
                "https://api.telegram.org/bot{$this->botToken}/sendMessage",
                [
                    'chat_id'    => $this->chatId,
                    'text'       => $message,
                    'parse_mode' => 'HTML',
                ]
            );

            if (!$response->successful()) {
                Log::warning('Telegram notification failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Telegram notification exception: ' . $e->getMessage());
            return false;
        }
    }

    public function notifyNewOrder(\App\Models\Order $order): bool
    {
        $items = $order->items->map(function ($item) {
            $variant = $item->variant ? " ({$item->variant})" : '';
            return "  • {$item->product_name}{$variant} x{$item->quantity} — ₱" . number_format($item->price * $item->quantity, 2);
        })->implode("\n");

        $paymentMethod = match ($order->payment_method) {
            'gcash'         => 'GCash',
            'cod'           => 'Cash on Delivery',
            'bank_transfer' => 'Bank Transfer',
            default         => ucfirst($order->payment_method),
        };

        $message = "🛒 <b>New Order Received!</b>\n\n"
            . "📦 <b>Order:</b> {$order->order_number}\n"
            . "👤 <b>Customer:</b> {$order->customer_name}\n"
            . "📧 <b>Email:</b> {$order->email}\n"
            . "📞 <b>Phone:</b> " . ($order->phone ?: 'N/A') . "\n\n"
            . "🛍 <b>Items:</b>\n{$items}\n\n"
            . "💰 <b>Total:</b> ₱" . number_format($order->total, 2) . "\n"
            . "💳 <b>Payment:</b> {$paymentMethod}\n\n"
            . "📍 <b>Ship to:</b>\n"
            . "  {$order->shipping_address}\n"
            . "  {$order->shipping_city}, {$order->shipping_province}"
            . ($order->shipping_postal_code ? " {$order->shipping_postal_code}" : '') . "\n\n"
            . "🔗 <a href=\"https://dealmindanao.com/admin/orders.html\">View in Admin →</a>";

        return $this->sendMessage($message);
    }
}
