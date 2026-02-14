<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $query = Product::with(['category', 'images'])->latest();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(15)->withQueryString();

        return view('admin.discounts.index', compact('products', 'categories'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'integer|exists:products,id',
            'discount_type' => 'nullable|in:percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'clear_discount' => 'nullable|boolean',
        ]);

        $productIds = $request->input('product_ids', []);
        $clear = $request->boolean('clear_discount');

        if ($clear) {
            Product::whereIn('id', $productIds)->update([
                'discount_type' => null,
                'discount_value' => null,
                'discount_active' => false,
            ]);

            return redirect()->route('admin.discounts.index')->with('success', 'Potongan harga dihapus dari produk terpilih.');
        }

        $type = $request->input('discount_type');
        $value = $request->input('discount_value');

        if (!$type || $value === null || $value <= 0) {
            return redirect()->route('admin.discounts.index')->with('error', 'Pilih tipe dan isi nominal potongan harga.');
        }

        Product::whereIn('id', $productIds)->update([
            'discount_type' => $type,
            'discount_value' => $value,
            'discount_active' => true,
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Potongan harga berhasil diterapkan ke produk terpilih.');
    }
}
