<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleSheetsWebhook
{
    public function sendOrderSnapshot(Order $order, string $event): void
    {
        $endpoint = trim((string) config('services.google_sheets.webhook_url'));
        if ($endpoint === '') {
            return;
        }

        try {
            $order->loadMissing(['items.product', 'payment']);

            $items = $order->items->map(function ($item) {
                $name = $item->product ? $item->product->name : 'Produk';
                return "{$name} x{$item->quantity} (Rp ".number_format($item->subtotal, 0, ',', '.').")";
            })->implode(' | ');

            $payload = [
                'event' => $event,
                'order_code' => $order->order_code,
                'created_at' => $order->created_at?->format('Y-m-d H:i:s'),
                'status' => $order->status,
                'payment_status' => $order->payment ? $order->payment->verified_status : 'pending',
                'customer_name' => $order->customer_name ?? 'Tamu',
                'customer_email' => $order->customer_email ?? '',
                'shipping_phone' => $order->shipping_phone ?? '',
                'shipping_method' => $order->shipping_method ?? '',
                'shipping_address' => $order->shipping_address ?? '',
                'shipping_cost' => $order->shipping_cost ?? 0,
                'subtotal' => $order->items->sum('subtotal'),
                'discount_amount' => $order->discount_amount ?? 0,
                'total_price' => $order->total_price ?? 0,
                'items' => $items,
                'notes' => $order->notes ?? '',
            ];

            $response = Http::timeout(5)->post($endpoint, $payload);
            if (!$response->ok()) {
                Log::warning('Google Sheets webhook failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Google Sheets webhook error.', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
