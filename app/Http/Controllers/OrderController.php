<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    // Note: index method removed or unused for guests as they don't have account history.
    // If we wanted to, we could use session cookies to track recent orders.

    public function show(Order $order)
    {
        // For Guest checkout, we assume having the correct URL (with Order ID) is enough "security" for now.
        // In a production app, we should use signed URLs or UUIDs to prevent guessing IDs.
        // e.g. /orders/uuid-string instead of /orders/1
        $bankAccounts = BankAccount::active()->orderBy('bank_name')->get();
        return view('orders.show', compact('order', 'bankAccounts'));
    }

    private function normalizePhone(?string $phone): ?string
    {
        if (!$phone) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone);
        if (!$digits) {
            return null;
        }

        if (str_starts_with($digits, '62')) {
            if (strlen($digits) > 2 && $digits[2] === '0') {
                return '62'.substr($digits, 3);
            }
            return $digits;
        }

        if (str_starts_with($digits, '0')) {
            return '62'.substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62'.$digits;
        }

        return $digits;
    }

    public function trackForm()
    {
        return view('orders.track');
    }

    public function track(Request $request)
    {
        $data = $request->validate([
            'order_code' => 'required|string|max:50',
            'shipping_phone' => 'required|string|max:30',
        ]);

        $code = strtoupper(trim($data['order_code']));
        $order = Order::where('order_code', $code)->first();
        if (!$order) {
            return back()->with('error', 'Kode order tidak ditemukan.');
        }

        $inputPhone = $this->normalizePhone($data['shipping_phone']);
        $orderPhone = $this->normalizePhone($order->shipping_phone);
        if (!$inputPhone || !$orderPhone || $inputPhone !== $orderPhone) {
            return back()->with('error', 'Nomor WhatsApp tidak sesuai dengan order.');
        }

        return redirect()->route('orders.show', $order);
    }

    public function storePayment(Request $request, Order $order)
    {
        // Removed Auth check to allow guests to upload payment proof based on Order ID/URL
        
        $request->validate([
            'proof' => 'required|image|max:2048',
            'method' => 'required',
        ]);

        $path = $request->file('proof')->store('payments', 'public');

        Payment::create([
            'order_id' => $order->id,
            'method' => $request->method,
            'proof_path' => $path,
            'verified_status' => 'pending',
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    public function paymentReceipt(Order $order)
    {
        $pdf = Pdf::loadView('orders.payment-receipt', compact('order'));
        return $pdf->stream('struk-pembayaran-'.$order->order_code.'.pdf');
    }
}
