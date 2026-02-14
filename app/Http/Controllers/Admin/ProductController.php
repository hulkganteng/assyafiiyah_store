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
            'image' => 'nullable|image|max:2048',
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

        $this->syncVariants($product, $request);

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

        $this->syncVariants($product, $request);

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

    private function syncVariants(Product $product, Request $request): void
    {
        $existingIds = $product->variants()->pluck('id')->all();
        $option1Name = trim((string) $request->input('option1_name'));
        $option2Name = trim((string) $request->input('option2_name'));

        $values1 = $request->input('variant_value_1', []);
        $values2 = $request->input('variant_value_2', []);
        $prices = $request->input('variant_price', []);
        $stocks = $request->input('variant_stock', []);
        $ids = $request->input('variant_id', []);

        $keptIds = [];
        $variantPrices = [];
        $variantStocks = [];

        foreach ($values1 as $index => $value1) {
            $value1 = trim((string) $value1);
            $value2 = isset($values2[$index]) ? trim((string) $values2[$index]) : null;
            $price = $prices[$index] ?? null;
            $stock = $stocks[$index] ?? null;

            if ($value1 === '' || $price === null || $stock === null) {
                continue;
            }

            $data = [
                'option1_name' => $option1Name !== '' ? $option1Name : 'Varian 1',
                'option1_value' => $value1,
                'option2_name' => $value2 ? ($option2Name !== '' ? $option2Name : 'Varian 2') : null,
                'option2_value' => $value2 ?: null,
                'price' => $price,
                'stock' => (int) $stock,
            ];

            $variantId = $ids[$index] ?? null;
            if ($variantId) {
                $variant = $product->variants()->where('id', $variantId)->first();
                if ($variant) {
                    $variant->update($data);
                    $keptIds[] = $variant->id;
                    $variantPrices[] = (float) $variant->price;
                    $variantStocks[] = (int) $variant->stock;
                    continue;
                }
            }

            $variant = $product->variants()->create($data);
            $keptIds[] = $variant->id;
            $variantPrices[] = (float) $variant->price;
            $variantStocks[] = (int) $variant->stock;
        }

        if (!empty($existingIds)) {
            $product->variants()->whereNotIn('id', $keptIds)->delete();
        }

        if (count($variantPrices) > 0) {
            $product->update([
                'price' => min($variantPrices),
                'stock' => array_sum($variantStocks),
            ]);
        }
    }
}
