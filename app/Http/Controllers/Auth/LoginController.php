<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Coba login dengan guard web
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            // Redirect berdasarkan role - JANGAN gunakan intended() karena bisa redirect ke URL berbahaya
            return match($user->role) {
                'super_admin' => redirect('/admin/pendaftaran'),
                'admin' => redirect('/admin/dashboard'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
