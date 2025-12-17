<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPanel
{
    /**
     * Handle an incoming request untuk Admin Panel (Pengurus HIMA)
     * Hanya user dengan role 'admin' yang bisa akses
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Cek user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Cek user punya role 'admin' atau 'super_admin' (super admin juga boleh mengakses panel)
        $userRole = strtolower((string)Auth::user()->role);
        if (!in_array($userRole, ['admin', 'super_admin'])) {
            abort(403, 'Anda tidak memiliki akses ke Admin Panel. Hanya Pengurus HIMA yang diizinkan.');
        }

        return $next($request);
    }
}
