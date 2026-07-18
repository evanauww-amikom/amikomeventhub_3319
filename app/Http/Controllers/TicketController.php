<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $transactions = Auth::user()->transactions()
            ->with('event')
            ->where('status', 'Success')
            ->latest()
            ->get();

        $reviewedEventIds = Review::where('user_id', Auth::id())
            ->pluck('event_id')
            ->toArray();

        return view('riwayat-tiket', compact('transactions', 'reviewedEventIds'));
    }
}