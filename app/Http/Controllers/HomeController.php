<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::active()
            ->with('category')
            ->withCount('variants')
            ->withMin('variants', 'price')
            ->withSum('variants', 'stock');

        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);

        return view('home', compact('categories', 'products'));
    }

    public function dashboard()
    {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        $recentOrders = auth()->user()->orders()->latest()->take(5)->get();
        return view('dashboard', compact('recentOrders'));
    }
}
