<?php
// app/Http/Controllers/Admin/AdminOrganizerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organizer;

class AdminOrganizerController extends Controller
{
    public function index()
    {
        $organizers = Organizer::with('user')->latest()->get();
        return view('admin.organizers.index', compact('organizers'));
    }

    public function approve(Organizer $organizer)
    {
        $organizer->update([
            'status'      => 'approved',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Organizer berhasil disetujui.');
    }

    public function reject(Organizer $organizer)
    {
        $organizer->update(['status' => 'rejected']);
        return back()->with('success', 'Organizer ditolak.');
    }
}