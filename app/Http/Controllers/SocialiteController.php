<?php
// app/Http/Controllers/SocialiteController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Cek dulu apakah user sudah pernah daftar via Google
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Belum ada? cek juga berdasarkan email (siapa tau daftar manual sebelumnya)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User lama, tinggal tempelkan google_id-nya
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                // User benar-benar baru
                $user = User::create([
                    'name'      => $googleUser->getName(),
                    'email'     => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                    'password'  => null,
                    'role'      => 'user',
                ]);
            }
        }

        Auth::login($user, true);

        return redirect()->intended(route('home'))->with('success', 'Berhasil login sebagai ' . $user->name);
    }
}