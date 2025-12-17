<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/login');
        }

        // Super admin dapat akses semua
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Role harus sesuai dengan yang disyaratkan
        if ($user->role === $role) {
            return $next($request);
        }

        // Jika tidak sesuai, redirect ke dashboard mereka
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
    }
}
