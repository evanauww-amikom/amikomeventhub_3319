<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // 1. Event harus sudah selesai
        if (!$event->hasEnded()) {
            return back()->with('error', 'Review baru bisa diisi setelah event selesai.');
        }

        // 2. User harus pernah beli tiket event ini (status Success)
        $sudahBeli = $event->transactions()
            ->where('user_id', $user->id)
            ->where('status', 'Success')
            ->exists();

        if (!$sudahBeli) {
            return back()->with('error', 'Anda belum pernah membeli tiket event ini.');
        }

        // 3. Belum pernah review event ini
        $sudahReview = Review::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->exists();

        if ($sudahReview) {
            return back()->with('error', 'Anda sudah memberi review untuk event ini.');
        }

        Review::create([
            'user_id'  => $user->id,
            'event_id' => $event->id,
            'rating'   => $request->rating,
            'comment'  => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih atas review Anda!');
    }
}