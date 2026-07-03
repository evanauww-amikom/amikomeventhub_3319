<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    const SERVICE_FEE = 5000;

    public function create(Event $event)
    {
        return view('checkout.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        if ($event->stock <= 0) {
            return redirect()
                ->route('events.show', $event->id)
                ->with('error', 'Maaf, tiket untuk event ini sudah habis.');
        }

        $totalPrice = $event->price + self::SERVICE_FEE;
        $orderId = 'ORDER-' . strtoupper(Str::random(8)) . '-' . time();

        $transaction = Transaction::create([
            'event_id'       => $event->id,
            'order_id'       => $orderId,
            'customer_name'  => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'total_price'    => $totalPrice,
            'status'         => 'Pending',
        ]);

        $snapToken = $this->createMidtransSnapToken($transaction, $event);

        if (!$snapToken) {
            $transaction->update(['status' => 'Failed']);

            return redirect()
                ->route('events.show', $event->id)
                ->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
        }

        $transaction->update(['snap_token' => $snapToken]);

        return redirect()->route('checkout.payment', $transaction->order_id);
    }

    public function payment($order_id)
    {
        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view('checkout.payment', compact('transaction'));
    }

    public function success($order_id)
    {
        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        return view('checkout.success', compact('transaction'));
    }

    /**
     * BARU: Handler notifikasi/webhook dari server Midtrans.
     * Endpoint ini dipanggil Midtrans, bukan browser user.
     */
    public function notification(Request $request)
    {
        $payload = $request->all();

        $orderId      = $payload['order_id'] ?? null;
        $statusCode   = $payload['status_code'] ?? null;
        $grossAmount  = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if (!$orderId || !$statusCode || !$grossAmount || !$signatureKey) {
            return response()->json(['message' => 'Payload tidak lengkap'], 400);
        }

        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            logger()->warning('Midtrans notification: signature tidak valid', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('order_id', $orderId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        $previousStatus = $transaction->status;

        if ($transactionStatus === 'capture') {
            $transaction->status = ($fraudStatus === 'accept') ? 'Success' : 'Pending';
        } elseif ($transactionStatus === 'settlement') {
            $transaction->status = 'Success';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'Failed';
        } elseif ($transactionStatus === 'pending') {
            $transaction->status = 'Pending';
        }

        $transaction->save();

        // Kurangi stok event HANYA sekali, saat pertama kali jadi Success
        if ($transaction->status === 'Success' && $previousStatus !== 'Success') {
            $event = $transaction->event;
            if ($event && $event->stock > 0) {
                $event->decrement('stock');
            }
        }

        logger()->info('Midtrans notification diproses', [
            'order_id' => $orderId,
            'status_lama' => $previousStatus,
            'status_baru' => $transaction->status,
        ]);

        return response()->json(['message' => 'OK']);
    }

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

        logger()->error('Midtrans Snap Error', [
            'order_id' => $transaction->order_id,
            'response' => $response->body(),
        ]);

        return null;
    }
}