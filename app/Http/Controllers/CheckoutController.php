<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Biaya layanan tetap (samain sama yang ditampilkan di create.blade.php)
     */
    const SERVICE_FEE = 5000;

    /**
     * Tampilkan halaman form checkout untuk 1 event.
     */
    public function create(Event $event)
    {
        return view('checkout.create', compact('event'));
    }

    /**
     * Proses data pemesan, buat transaksi, minta snap_token ke Midtrans,
     * lalu redirect ke halaman payment.
     */
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        // Cek stok tersedia
        if ($event->stock <= 0) {
            return redirect()
                ->route('events.show', $event->id)
                ->with('error', 'Maaf, tiket untuk event ini sudah habis.');
        }

        $totalPrice = $event->price + self::SERVICE_FEE;
        $orderId = 'ORDER-' . strtoupper(Str::random(8)) . '-' . time();

        // 1. Simpan transaksi dulu dengan status Pending
        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => $orderId,
            'customer_name'  => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'total_price'    => $totalPrice,
            'status'         => 'Pending',
        ]);

        // 2. Minta snap_token ke Midtrans
        $snapToken = $this->createMidtransSnapToken($transaction, $event);

        if (!$snapToken) {
            // Kalau gagal konek ke Midtrans, jangan lanjut ke halaman payment
            $transaction->update(['status' => 'Failed']);

            return redirect()
                ->route('events.show', $event->id)
                ->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
        }

        // 3. Simpan snap_token ke transaksi
        $transaction->update(['snap_token' => $snapToken]);

        return redirect()->route('checkout.payment', $transaction->order_id);
    }

    /**
     * Tampilkan halaman pembayaran (Snap.js popup).
     */
    public function payment($order_id)
    {
        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view('checkout.payment', compact('transaction'));
    }

    /**
     * Tampilkan halaman sukses setelah pembayaran.
     */
    public function success($order_id)
    {
        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view('checkout.success', compact('transaction'));
    }

    /**
     * Helper: panggil Midtrans Snap API buat dapetin snap_token.
     * Return string token, atau null kalau gagal.
     */
    private function createMidtransSnapToken(Transaction $transaction, Event $event): ?string
    {
        $isProduction = config('midtrans.is_production');

        $baseUrl = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $serverKey = config('midtrans.server_key');

        $payload = [
            'transaction_details' => [
                'order_id'     => $transaction->order_id,
                'gross_amount' => (int) $transaction->total_price,
            ],
            'customer_details' => [
                'first_name' => $transaction->customer_name,
                'email'      => $transaction->customer_email,
                'phone'      => $transaction->customer_phone,
            ],
            'item_details' => [
                [
                    'id'       => (string) $event->id,
                    'price'    => (int) $event->price,
                    'quantity' => 1,
                    'name'     => Str::limit($event->title, 50, ''),
                ],
                [
                    'id'       => 'SERVICE_FEE',
                    'price'    => self::SERVICE_FEE,
                    'quantity' => 1,
                    'name'     => 'Biaya Layanan',
                ],
            ],
        ];

        $response = Http::withBasicAuth($serverKey, '')
            ->withHeaders(['Accept' => 'application/json'])
            ->post($baseUrl, $payload);

        if ($response->successful()) {
            return $response->json('token');
        }

        // Log error biar bisa didebug lewat storage/logs/laravel.log
        logger()->error('Midtrans Snap Error', [
            'order_id' => $transaction->order_id,
            'response' => $response->body(),
        ]);

        return null;
    }
}