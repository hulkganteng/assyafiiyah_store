<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Struk Pengiriman - {{ $order->order_code }}</title>
        <style>
            :root { color-scheme: light; }
            * { box-sizing: border-box; }
            @page { size: 5.8cm auto; margin: 0.2cm; }
            body {
                margin: 0;
                font-family: "Segoe UI", Arial, sans-serif;
                font-size: 10px;
                color: #111827;
                width: 5.8cm;
                background: #ffffff;
            }
            .receipt {
                width: 5.8cm;
                padding: 4px;
            }
            h1 {
                font-size: 12px;
                margin: 0 0 6px;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                text-align: center;
            }
            .line { border-top: 1px dashed #9ca3af; margin: 6px 0; }
            .row { display: flex; justify-content: space-between; gap: 6px; }
            .label { color: #6b7280; }
            .items { margin-top: 6px; }
            .items table { width: 100%; border-collapse: collapse; }
            .items th, .items td { padding: 2px 0; }
            .items th {
                text-align: left;
                font-size: 9px;
                color: #6b7280;
                border-bottom: 1px solid #e5e7eb;
            }
            .items td:last-child { text-align: right; }
            .total { margin-top: 6px; font-weight: 700; }
            @media print {
            }
        </style>
    </head>
    <body>
        @php
            $subtotal = $order->items->sum('subtotal');
            $shippingCost = $order->shipping_cost ?? 0;
        @endphp
        <div class="receipt">
            <h1>Struk Pengiriman</h1>
            <div class="row">
                <div class="label">Order</div>
                <div>{{ $order->order_code }}</div>
            </div>
            <div class="row">
                <div class="label">Tanggal</div>
                <div>{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <div class="line"></div>
            <div>
                <div class="label">Penerima</div>
                <div>{{ $order->customer_name ?? $order->user->name ?? 'Tamu' }}</div>
                <div>{{ $order->shipping_phone }}</div>
            </div>
            <div class="line"></div>
            <div>
                <div class="label">Alamat</div>
                @php
                    $addressLines = [];
                    if ($order->shipping_address_detail) {
                        $addressLines[] = $order->shipping_address_detail;
                    }
                    $regionParts = array_filter([
                        $order->shipping_village,
                        $order->shipping_district,
                        $order->shipping_city,
                        $order->shipping_province,
                    ]);
                    if (!empty($regionParts)) {
                        $addressLines[] = implode(', ', $regionParts);
                    }
                    if ($order->shipping_postal_code) {
                        $addressLines[] = 'Kode Pos ' . $order->shipping_postal_code;
                    }
                    if (empty($addressLines) && $order->shipping_address) {
                        $addressLines[] = $order->shipping_address;
                    }
                @endphp
                @foreach($addressLines as $line)
                    <div>{{ $line }}</div>
                @endforeach
            </div>
            <div class="line"></div>
            <div class="row">
                <div class="label">Metode</div>
                <div>{{ $order->shipping_method == 'pickup' ? 'Ambil Sendiri' : 'Kurir Ekspedisi' }}</div>
            </div>
            <div class="items">
                <table>
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
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="line"></div>
            <div class="row">
                <div class="label">Subtotal</div>
                <div>Rp {{ number_format($subtotal, 0, ',', '.') }}</div>
            </div>
            <div class="row">
                <div class="label">Ongkir</div>
                <div>Rp {{ number_format($shippingCost, 0, ',', '.') }}</div>
            </div>
            <div class="row total">
                <div>Total</div>
                <div>Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
            </div>
        </div>
    </body>
</html>
