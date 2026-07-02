<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;

class EventController extends Controller
{
    /**
     * Tampilkan detail satu event untuk halaman customer.
     */
    public function show($id)
    {
        $event = Event::with(['category', 'partner'])->findOrFail($id);

        return view('event-detail', compact('event'));
    }

    /**
     * Tampilkan halaman tiket berdasarkan id transaksi.
     * ASUMSI: Transaction punya relasi ke Event (method event()).
     * Kalau nama relasinya beda, kasih tau aku modelnya.
     */
    public function ticket($id)
    {
        $transaction = Transaction::with('event')->findOrFail($id);

        return view('ticket', compact('transaction'));
    }
}