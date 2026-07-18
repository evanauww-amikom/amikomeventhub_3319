<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'superadmin') {
            auth()->logout();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Akses ditolak. Anda bukan superadmin.']);
        }

        return $next($request);
    }
}