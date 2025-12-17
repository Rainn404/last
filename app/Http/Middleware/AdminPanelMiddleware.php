<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminPanelMiddleware
{
    /**
     * Handle an incoming request.
     * Middleware untuk Admin Panel (Pengurus HIMA)
     * Hanya user dengan role 'admin' yang bisa akses
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user tidak login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek jika user bukan admin atau super_admin
        $userRole = strtolower((string)Auth::user()->role);
        if (!in_array($userRole, ['admin', 'super_admin'])) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki akses ke Admin Panel. Hanya Pengurus HIMA yang dapat mengakses.');
        }

        return $next($request);
    }
}
