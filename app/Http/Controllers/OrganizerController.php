<?php
// app/Http/Controllers/OrganizerController.php

namespace App\Http\Controllers;

use App\Models\Organizer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OrganizerController extends Controller
{
    // Form pendaftaran jadi organizer/kepanitiaan
    public function showRegister()
    {
        return view('organizer.register');
    }

    // Proses pendaftaran: bikin User (role organizer) + profil Organizer (status pending)
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8|confirmed',
            'organization_name' => 'required|string|max:255',
            'description'       => 'nullable|string',
            'logo'              => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'organizer',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('organizer-logos', 'public');
        }

        Organizer::create([
            'user_id'           => $user->id,
            'organization_name' => $data['organization_name'],
            'description'       => $data['description'] ?? null,
            'logo_path'         => $logoPath,
            'status'            => 'pending',
        ]);

        Auth::login($user);

        return redirect()->route('organizer.pending');
    }

    // Halaman "nunggu diverifikasi superadmin"
    public function pending()
    {
        $organizer = Auth::user()->organizer;

        // kalau ternyata udah di-approve, langsung lempar ke dashboard
        if ($organizer && $organizer->isApproved()) {
            return redirect()->route('organizer.dashboard');
        }

        return view('organizer.pending', compact('organizer'));
    }

    // Dashboard organizer: event miliknya + ringkasan pendapatan
// Dashboard organizer: event miliknya + ringkasan pendapatan
    public function dashboard()
    {
        $organizer = Auth::user()->organizer;

        $events = $organizer->events()
            ->withCount([
                'transactions as tickets_sold' => function ($q) {
                    $q->where('status', 'Success');
                }
            ])
            ->withSum([
                'transactions as revenue' => function ($q) {
                    $q->where('status', 'Success');
                }
            ], 'total_price')
            ->latest()
            ->get();

        $totalRevenue = \App\Models\Transaction::whereIn('event_id', $organizer->events()->pluck('id'))
            ->where('status', 'Success')
            ->sum('total_price');

        return view('organizer.dashboard', compact('organizer', 'events', 'totalRevenue'));
    }
}