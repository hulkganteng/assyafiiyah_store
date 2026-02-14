<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
                continue;
            }

            $cart[$id]['price'] = $product->discounted_price;
            $cart[$id]['original_price'] = $product->price;
            $cart[$id]['has_discount'] = $product->has_active_discount;
            $cart[$id]['discount_label'] = $product->discount_label;
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
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if(isset($cart[$request->product_id])) {
            $newQty = $cart[$request->product_id]['quantity'] + $quantity;
            if ($newQty > $product->stock) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart[$request->product_id]['quantity'] = $newQty;
            $cart[$request->product_id]['price'] = $product->discounted_price;
            $cart[$request->product_id]['original_price'] = $product->price;
            $cart[$request->product_id]['has_discount'] = $product->has_active_discount;
            $cart[$request->product_id]['discount_label'] = $product->discount_label;
        } else {
            if ($quantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart[$request->product_id] = [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $quantity,
                'price' => $product->discounted_price,
                'original_price' => $product->price,
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
        if($request->id && $request->quantity){
            $product = Product::find($request->id);
            if ($product && $request->quantity > $product->stock) {
                return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
            }
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] = $request->quantity;
            if ($product) {
                $cart[$request->id]['price'] = $product->discounted_price;
                $cart[$request->id]['original_price'] = $product->price;
                $cart[$request->id]['has_discount'] = $product->has_active_discount;
                $cart[$request->id]['discount_label'] = $product->discount_label;
            }
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Keranjang diperbarui');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Produk dihapus dari keranjang');
        }
    }
}
