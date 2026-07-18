<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Transaction;

class EventController extends Controller
{
    public function show($id)
    {
        // FIX: 'partner' diganti 'organizer', relasi ini yang beneran ada
        $event = Event::with(['category', 'organizer'])->findOrFail($id);

        return view('event-detail', compact('event'));
    }

    public function ticket($id)
    {
        $transaction = Transaction::with('event')->findOrFail($id);

        return view('ticket', compact('transaction'));
    }
}