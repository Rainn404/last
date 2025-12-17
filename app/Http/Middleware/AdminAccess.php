<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAccess
{
    /**
     * Handle an incoming request.
     * 
     * Hanya admin dan super_admin yang bisa akses /admin/*
     * Mahasiswa akan di-redirect ke home
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Hanya super_admin dan admin yang bisa akses
        if ($user->role !== 'super_admin' && $user->role !== 'admin') {
            return redirect('/home')->with('error', 'Anda tidak memiliki akses ke halaman admin');
        }

        return $next($request);
    }
}
