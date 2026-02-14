<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
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

    private function sendFonnteMessage(?string $target, string $message): void
    {
        $fonnteToken = trim((string) config('services.fonnte.token'));
        $normalized = $this->normalizePhone($target);
        if (!$normalized || $fonnteToken === '') {
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $fonnteToken,
            ])->asForm()->post('https://api.fonnte.com/send', [
                'target' => $normalized,
                'message' => $message,
            ]);

            if (!$response->ok()) {
                Log::warning('Fonnte send failed.', [
                    'target' => $normalized,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Fonnte send error.', [
                'target' => $normalized,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function index()
    {
        $payments = Payment::with('order.user')->latest()->paginate(20); // Show all mostly, or filter pending
        return view('admin.payments.index', compact('payments'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);

        $verifier = auth()->user();
        if (!$verifier) {
            return redirect()->back()->with('error', 'Silakan login ulang untuk memverifikasi pembayaran.');
        }
        $verifierId = $verifier->id;
        $previousStatus = $payment->verified_status;
        $payment->update([
            'verified_status' => $request->status,
            'verified_by' => $verifierId,
            'verified_at' => now(),
        ]);

        if ($request->status == 'approved') {
            $order = $payment->order;
            if (!$order) {
                return redirect()->back()->with('error', 'Order tidak ditemukan untuk pembayaran ini.');
            }
            if($order->status == 'pending_payment'){
                $order->update(['status' => 'paid']);
            }

            // Send WhatsApp feedback to customer after approval
            if ($previousStatus !== 'approved') {
                $message = "Pembayaran kamu sudah disetujui.\n".
                    "Order: {$order->order_code}\n".
                    "Status: {$order->status}\n".
                    "Terima kasih, pesanan akan segera diproses.";
                $this->sendFonnteMessage($order->shipping_phone, $message);
            }
        } elseif ($request->status == 'rejected') {
            $order = $payment->order;
            if ($order && $previousStatus !== 'rejected') {
                $message = "Pembayaran kamu ditolak.\n".
                    "Order: {$order->order_code}\n".
                    "Silakan cek kembali bukti pembayaran atau hubungi admin.";
                $this->sendFonnteMessage($order->shipping_phone, $message);
            }
        }

        return redirect()->back()->with('success', 'Pembayaran diverifikasi.');
    }
}
