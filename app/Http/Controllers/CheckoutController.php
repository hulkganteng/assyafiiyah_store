<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\BankAccount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\GoogleSheetsWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Laravolt\Indonesia\Models\Province;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $hasUpdates = false;
        foreach ($cart as $id => $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                unset($cart[$id]);
                $hasUpdates = true;
                continue;
            }
            $variant = null;
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::where('product_id', $product->id)
                    ->where('id', $item['variant_id'])
                    ->first();
                if (!$variant) {
                    unset($cart[$id]);
                    $hasUpdates = true;
                    continue;
                }
            }

            $basePrice = $variant ? (float) $variant->price : (float) $product->price;
            $cart[$id]['price'] = $product->has_active_discount
                ? $product->discountPrice($basePrice)
                : $basePrice;
            $cart[$id]['original_price'] = $basePrice;
            $cart[$id]['has_discount'] = $product->has_active_discount;
            $cart[$id]['discount_label'] = $product->discount_label;
            $cart[$id]['variant_label'] = $variant ? $variant->label : null;
            $hasUpdates = true;
        }
        if ($hasUpdates) {
            session()->put('cart', $cart);
        }
        if(count($cart) < 1) {
            return redirect()->route('cart.index');
        }
        $bankAccounts = BankAccount::active()->orderBy('bank_name')->get();
        $provinces = collect();
        try {
            $provinces = Province::query()
                ->selectRaw('code as id, name')
                ->orderBy('name')
                ->get();
        } catch (\Throwable $e) {
            // If indonesia tables aren't ready, keep empty list to avoid breaking checkout.
        }
        return view('checkout.index', compact('cart', 'bankAccounts', 'provinces'));
    }

    public function store(Request $request)
    {
        $shippingSelection = $request->input('shipping_method_selection');
        $isPickup = $shippingSelection === 'pickup';

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'shipping_phone' => 'required',
            'shipping_method_selection' => 'required|in:pickup,delivery',
            'shipping_method' => 'required',
            'payment_method' => 'required|in:bank_transfer',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_province_id' => $isPickup ? 'nullable' : 'required|string',
            'shipping_province' => $isPickup ? 'nullable' : 'required|string',
            'shipping_city_id' => $isPickup ? 'nullable' : 'required|string',
            'shipping_city' => $isPickup ? 'nullable' : 'required|string',
            'shipping_district_id' => $isPickup ? 'nullable' : 'required|string',
            'shipping_district' => $isPickup ? 'nullable' : 'required|string',
            'shipping_village_id' => $isPickup ? 'nullable' : 'required|string',
            'shipping_village' => $isPickup ? 'nullable' : 'required|string',
            'shipping_postal_code' => $isPickup ? 'nullable' : 'required|string|max:10',
            'shipping_address_detail' => $isPickup ? 'nullable' : 'required|string',
        ]);

        $cart = session()->get('cart', []);
        if(empty($cart)) {
             return redirect()->route('cart.index')->with('error', 'Keranjang kosong');
        }

        $totalItemPrice = 0;
        foreach($cart as $id => $details) {
            $product = Product::find($details['id']);
            if (!$product) {
                return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan.');
            }

            $variant = null;
            if (!empty($details['variant_id'])) {
                $variant = ProductVariant::where('product_id', $product->id)
                    ->where('id', $details['variant_id'])
                    ->first();
                if (!$variant) {
                    return redirect()->route('cart.index')->with('error', 'Varian tidak ditemukan.');
                }
            }

            $stock = $variant ? $variant->stock : $product->stock;
            if ($details['quantity'] > $stock) {
                return redirect()->route('cart.index')->with('error', 'Stok tidak mencukupi untuk salah satu produk.');
            }
            $basePrice = $variant ? (float) $variant->price : (float) $product->price;
            $unitPrice = $product->has_active_discount ? $product->discountPrice($basePrice) : $basePrice;
            $totalItemPrice += $unitPrice * $details['quantity'];
        }

        // Calculate Grand Total
        $shippingCost = $request->shipping_cost;
        $grandTotal = $totalItemPrice + $shippingCost;

        $shippingAddress = null;
        if (!$isPickup) {
            $addressParts = array_filter([
                $request->shipping_address_detail,
                $request->shipping_village ? 'Kel/Desa ' . $request->shipping_village : null,
                $request->shipping_district ? 'Kec. ' . $request->shipping_district : null,
                $request->shipping_city,
                $request->shipping_province,
                $request->shipping_postal_code ? 'Kode Pos ' . $request->shipping_postal_code : null,
            ]);
            $shippingAddress = implode(', ', $addressParts);
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => null, // Guest checkout
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'order_code' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'pending_payment',
                'total_price' => $grandTotal,
                'shipping_method' => $request->shipping_method,
                'shipping_cost' => $shippingCost,
                'shipping_address' => $shippingAddress,
                'shipping_province_id' => $isPickup ? null : $request->shipping_province_id,
                'shipping_province' => $isPickup ? null : $request->shipping_province,
                'shipping_city_id' => $isPickup ? null : $request->shipping_city_id,
                'shipping_city' => $isPickup ? null : $request->shipping_city,
                'shipping_district_id' => $isPickup ? null : $request->shipping_district_id,
                'shipping_district' => $isPickup ? null : $request->shipping_district,
                'shipping_village_id' => $isPickup ? null : $request->shipping_village_id,
                'shipping_village' => $isPickup ? null : $request->shipping_village,
                'shipping_postal_code' => $isPickup ? null : $request->shipping_postal_code,
                'shipping_address_detail' => $isPickup ? null : $request->shipping_address_detail,
                'shipping_phone' => $request->shipping_phone,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach($cart as $id => $details) {
                $product = Product::find($details['id']);
                if (!$product) {
                    continue;
                }
                $variant = null;
                if (!empty($details['variant_id'])) {
                    $variant = ProductVariant::where('product_id', $product->id)
                        ->where('id', $details['variant_id'])
                        ->first();
                    if (!$variant) {
                        continue;
                    }
                }
                $basePrice = $variant ? (float) $variant->price : (float) $product->price;
                $unitPrice = $product->has_active_discount ? $product->discountPrice($basePrice) : $basePrice;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $details['id'],
                    'product_variant_id' => $variant?->id,
                    'variant_label' => $variant ? $variant->label : null,
                    'quantity' => $details['quantity'],
                    'price' => $unitPrice,
                    'subtotal' => $unitPrice * $details['quantity'],
                ]);

                if ($variant) {
                    ProductVariant::where('id', $variant->id)->decrement('stock', $details['quantity']);
                    $freshStock = ProductVariant::where('product_id', $product->id)->sum('stock');
                    Product::where('id', $product->id)->update(['stock' => $freshStock]);
                } else {
                    Product::where('id', $details['id'])->decrement('stock', $details['quantity']);
                }
            }

            // Removed COD Logic block

            DB::commit();
            session()->forget('cart');

            $adminNumber = config('services.fonnte.admin_number');
            $botNumber = config('services.fonnte.bot_number');
            $fonnteToken = config('services.fonnte.token');
            if ($adminNumber && $fonnteToken && (!$botNumber || $adminNumber !== $botNumber)) {
                try {
                    $itemsCount = $order->items->sum('quantity');
                    $message = "Pesanan baru masuk\n".
                        "Order: {$order->order_code}\n".
                        "Nama: ".($order->customer_name ?? 'Tamu')."\n".
                        "WhatsApp: {$order->shipping_phone}\n".
                        "Alamat: {$order->shipping_address}\n".
                        "Metode Kirim: {$order->shipping_method}\n".
                        "Ongkir: Rp ".number_format($order->shipping_cost ?? 0, 0, ',', '.')."\n".
                        "Jumlah Item: {$itemsCount}\n".
                        "Total: Rp ".number_format($order->total_price, 0, ',', '.')."\n".
                        "Status: {$order->status}\n".
                        "Lihat: ".route('admin.orders.show', $order);
                    Http::withHeaders([
                        'Authorization' => $fonnteToken,
                    ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $adminNumber,
                        'message' => $message,
                    ]);
                } catch (\Throwable $e) {
                    // Silently ignore notification errors to avoid blocking checkout.
                }
            }

            app(GoogleSheetsWebhook::class)->sendOrderSnapshot($order, 'order_created');

            return redirect()->to(route('orders.show', $order).'#payment-section')
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
