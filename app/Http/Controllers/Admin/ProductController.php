<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048'
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->input('description'),
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->has('is_active') ? true : false,
            'is_preorder' => $request->input('is_preorder', 0),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images()->create(['path' => $path]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk dibuat.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
         $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->input('description'),
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => $request->has('is_active') ? true : false,
            'is_preorder' => $request->input('is_preorder', 0),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->images()->create(['path' => $path]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk dihapus.');
    }

    public function bulkDiscount(Request $request)
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

            return redirect()->route('admin.products.index')->with('success', 'Potongan harga dihapus dari produk terpilih.');
        }

        $type = $request->input('discount_type');
        $value = $request->input('discount_value');

        if (!$type || $value === null || $value <= 0) {
            return redirect()->route('admin.products.index')->with('error', 'Pilih tipe dan isi nominal potongan harga.');
        }

        Product::whereIn('id', $productIds)->update([
            'discount_type' => $type,
            'discount_value' => $value,
            'discount_active' => true,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Potongan harga berhasil diterapkan ke produk terpilih.');
    }
}
