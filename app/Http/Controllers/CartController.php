<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
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

         return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $variant = null;
        if ($product->variants()->count() > 0) {
            $request->validate([
                'variant_id' => 'required|integer',
            ]);
            $variant = $product->variants()->where('id', $request->variant_id)->firstOrFail();
        }
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);
        $stock = $variant ? $variant->stock : $product->stock;
        $basePrice = $variant ? (float) $variant->price : (float) $product->price;
        $unitPrice = $product->has_active_discount ? $product->discountPrice($basePrice) : $basePrice;
        $cartKey = $variant ? $product->id . ':' . $variant->id : (string) $product->id;

        if(isset($cart[$cartKey])) {
            $newQty = $cart[$cartKey]['quantity'] + $quantity;
            if ($newQty > $stock) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart[$cartKey]['quantity'] = $newQty;
            $cart[$cartKey]['price'] = $unitPrice;
            $cart[$cartKey]['original_price'] = $basePrice;
            $cart[$cartKey]['has_discount'] = $product->has_active_discount;
            $cart[$cartKey]['discount_label'] = $product->discount_label;
            $cart[$cartKey]['variant_label'] = $variant ? $variant->label : null;
        } else {
            if ($quantity > $stock) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart[$cartKey] = [
                'id' => $product->id,
                'variant_id' => $variant?->id,
                'variant_label' => $variant ? $variant->label : null,
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $unitPrice,
                'original_price' => $basePrice,
                'has_discount' => $product->has_active_discount,
                'discount_label' => $product->discount_label,
                'image' => $product->images->first()?->path
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        if(!$request->cart_key || !$request->quantity){
            return redirect()->back()->with('error', 'Permintaan tidak valid.');
        }

        $cart = session()->get('cart');
        if (!isset($cart[$request->cart_key])) {
            return redirect()->back()->with('error', 'Item tidak ditemukan di keranjang.');
        }

        $item = $cart[$request->cart_key];
        $product = Product::find($item['id']);
        if (!$product) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan.');
        }

        $variant = null;
        if (!empty($item['variant_id'])) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('id', $item['variant_id'])
                ->first();
            if (!$variant) {
                return redirect()->back()->with('error', 'Varian tidak ditemukan.');
            }
        }

        $stock = $variant ? $variant->stock : $product->stock;
        if ($request->quantity > $stock) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $basePrice = $variant ? (float) $variant->price : (float) $product->price;
        $cart[$request->cart_key]['quantity'] = $request->quantity;
        $cart[$request->cart_key]['price'] = $product->has_active_discount
            ? $product->discountPrice($basePrice)
            : $basePrice;
        $cart[$request->cart_key]['original_price'] = $basePrice;
        $cart[$request->cart_key]['has_discount'] = $product->has_active_discount;
        $cart[$request->cart_key]['discount_label'] = $product->discount_label;
        $cart[$request->cart_key]['variant_label'] = $variant ? $variant->label : null;

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Keranjang diperbarui');
    }

    public function remove(Request $request)
    {
        if($request->cart_key) {
            $cart = session()->get('cart');
            if(isset($cart[$request->cart_key])) {
                unset($cart[$request->cart_key]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
        }
    }
}
