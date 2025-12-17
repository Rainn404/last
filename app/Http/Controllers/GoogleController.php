<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\GoogleRoleMapping;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $email = $googleUser->getEmail();

            // Tentukan role berdasarkan mapping (database)
            $role = GoogleRoleMapping::findRoleForEmail($email);

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $googleUser->getName() ?? $email,
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                    'password' => bcrypt('google_login'),
                    'role' => $role,
                ]
            );

            // Ensure role is synced (in case it changed in mapping)
            if ($user->role !== $role) {
                $user->update(['role' => $role]);
            }

            // Login user
            Auth::login($user);
            request()->session()->regenerate();

            // Redirect berdasarkan role
            return match($role) {
                'super_admin' => redirect('/admin/pendaftaran'),
                'admin' => redirect('/admin/dashboard'),
                default => redirect('/'),
            };

        } catch (\Exception $e) {
            logger()->error('Google Login Error', ['exception' => $e]);
            return redirect('/login')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }
}
