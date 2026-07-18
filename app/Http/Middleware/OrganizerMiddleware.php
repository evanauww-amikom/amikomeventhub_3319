<?php
// app/Http/Middleware/OrganizerMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrganizerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!auth()->check() || $user->role !== 'organizer') {
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Akses ditolak. Anda bukan organizer.']);
        }

        if (!$user->organizer || !$user->organizer->isApproved()) {
            return redirect()->route('organizer.pending');
        }

        return $next($request);
    }
}