<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Struk Pembayaran - {{ $order->order_code }}</title>
        <style>
            :root { color-scheme: light; }
            * { box-sizing: border-box; }
            body {
                margin: 0;
                font-family: "Segoe UI", Arial, sans-serif;
                color: #111827;
                background: #f9fafb;
            }
            .page {
                max-width: 520px;
                margin: 24px auto;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                padding: 18px 18px 14px;
                box-shadow: 0 8px 24px rgba(17, 24, 39, 0.08);
            }
            .title {
                text-align: center;
                margin-bottom: 14px;
            }
            .title h1 {
                font-size: 18px;
                margin: 0 0 4px;
                letter-spacing: 0.5px;
                text-transform: uppercase;
            }
            .title p { margin: 0; font-size: 12px; color: #6b7280; }
            .meta, .totals {
                width: 100%;
                font-size: 12px;
                border-collapse: collapse;
            }
            .meta td { padding: 4px 0; }
            .meta td:last-child { text-align: right; }
            .divider {
                border-top: 1px dashed #d1d5db;
                margin: 10px 0;
            }
            .items {
                width: 100%;
                border-collapse: collapse;
                font-size: 12px;
            }
            .items th, .items td { padding: 6px 0; }
            .items th {
                text-align: left;
                border-bottom: 1px solid #e5e7eb;
                color: #6b7280;
                font-weight: 600;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: 0.4px;
            }
            .items td:last-child,
            .totals td:last-child { text-align: right; }
            .totals td { padding: 4px 0; }
            .totals tr:last-child td {
                border-top: 1px solid #e5e7eb;
                padding-top: 8px;
                font-weight: 700;
            }
            .badge {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 999px;
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                background: #ecfdf5;
                color: #047857;
            }
            .note {
                font-size: 11px;
                color: #6b7280;
                text-align: center;
                margin-top: 8px;
            }
            @media print {
                body { background: #ffffff; }
                .page {
                    margin: 0;
                    border: none;
                    border-radius: 0;
                    box-shadow: none;
                }
            }
        </style>
    </head>
    <body>
        @php
            $subtotal = $order->items->sum('subtotal');
            $shippingCost = $order->shipping_cost ?? 0;
            $paymentStatus = $order->payment ? $order->payment->verified_status : 'pending';
        @endphp
        <div class="page">
            <div class="title">
                <h1>Struk Pembayaran</h1>
                <p>Assyafiiyah Store</p>
            </div>
            <table class="meta">
                <tr>
                    <td>Kode Order</td>
                    <td>{{ $order->order_code }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                </tr>
                <tr>
                    <td>Pelanggan</td>
                    <td>{{ $order->customer_name ?? 'Tamu' }}</td>
                </tr>
                <tr>
                    <td>Metode</td>
                    <td>{{ $order->payment_method == 'cod' ? 'COD' : 'Transfer Bank' }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td><span class="badge">{{ ucfirst($paymentStatus) }}</span></td>
                </tr>
            </table>

            <div class="divider"></div>

            <table class="items">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div>{{ $item->product->name }}</div>
                                @if($item->variant_label)
                                    <div class="text-xs text-gray-600">{{ $item->variant_label }}</div>
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="divider"></div>

            <table class="totals">
                <tr>
                    <td>Subtotal</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Ongkir</td>
                    <td>Rp {{ number_format($shippingCost, 0, ',', '.') }}</td>
                </tr>
                @if($order->has_discount)
                    <tr>
                        <td>{{ $order->discount_label }}</td>
                        <td>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr>
                    <td>Total</td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                </tr>
            </table>

            <p class="note">Terima kasih atas pembelian Anda.</p>
        </div>
    </body>
</html>
