<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())->whereIn('status', ['paid', 'processing', 'shipped', 'completed'])->sum('total_price');
        $lowStockProducts = Product::where('stock', '<', 5)->get();

        return view('admin.dashboard', compact('todayOrders', 'todayRevenue', 'lowStockProducts'));
    }
}
