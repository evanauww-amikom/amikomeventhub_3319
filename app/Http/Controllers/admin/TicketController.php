<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    // Halaman riwayat tiket milik user yang login + form review
    public function index()
    {
        $transactions = Auth::user()->transactions()
            ->with('event')
            ->where('status', 'Success')
            ->latest()
            ->get();

        // event_id yang sudah pernah aku-review, buat sembunyikan form-nya di view
        $reviewedEventIds = Review::where('user_id', Auth::id())
            ->pluck('event_id')
            ->toArray();

        return view('riwayat-tiket', compact('transactions', 'reviewedEventIds'));
    }
}