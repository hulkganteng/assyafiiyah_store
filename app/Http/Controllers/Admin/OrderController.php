<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function shippingReceipt(Order $order)
    {
        $pdf = Pdf::loadView('admin.orders.shipping-receipt', compact('order'))
            ->setPaper([0, 0, 165, 1000]);
        return $pdf->stream('struk-pengiriman-'.$order->order_code.'.pdf');
    }

    public function paymentReceipt(Order $order)
    {
        $pdf = Pdf::loadView('orders.payment-receipt', compact('order'));
        return $pdf->stream('struk-pembayaran-'.$order->order_code.'.pdf');
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required']);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Restore stock if cancelled (stock is deducted on checkout)
        if ($newStatus == 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status order diperbarui');
    }

    public function applyDiscount(Request $request, Order $order)
    {
        $request->validate([
            'discount_type' => 'nullable|in:percent,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'clear_discount' => 'nullable|boolean',
        ]);

        $itemsCount = $order->items->sum('quantity');
        if ($itemsCount < 2) {
            return redirect()->back()->with('error', 'Diskon admin hanya untuk pembelian 2 item atau lebih.');
        }

        $subtotal = $order->items->sum('subtotal');
        $shippingCost = $order->shipping_cost ?? 0;

        if ($request->boolean('clear_discount')) {
            $order->update([
                'discount_type' => null,
                'discount_value' => null,
                'discount_amount' => 0,
                'total_price' => max(0, $subtotal + $shippingCost),
            ]);

            return redirect()->back()->with('success', 'Diskon pesanan dihapus.');
        }

        $type = $request->input('discount_type');
        $value = $request->input('discount_value');
        if (!$type || $value === null || $value <= 0) {
            return redirect()->back()->with('error', 'Pilih tipe dan isi nominal diskon.');
        }

        $discountAmount = 0;
        if ($type === 'percent') {
            $discountAmount = $subtotal * ($value / 100);
        } elseif ($type === 'fixed') {
            $discountAmount = $value;
        }

        if ($discountAmount > $subtotal) {
            $discountAmount = $subtotal;
        }

        $total = max(0, $subtotal + $shippingCost - $discountAmount);

        $order->update([
            'discount_type' => $type,
            'discount_value' => $value,
            'discount_amount' => $discountAmount,
            'total_price' => $total,
        ]);

        return redirect()->back()->with('success', 'Diskon pesanan berhasil diterapkan.');
    }

    public function export()
    {
        $orders = Order::with('user')->latest()->get();
        $csvFileName = 'orders_export_'.date('Y-m-d').'.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order Code', 'Date', 'Customer', 'Total', 'Status', 'Message/Notes']);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_code,
                    $order->created_at,
                    $order->user->name,
                    $order->total_price,
                    $order->status,
                    $order->notes
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
