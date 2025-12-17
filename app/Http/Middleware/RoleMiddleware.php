<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * 
     * Supports variadic roles: role:super_admin OR role:admin,super_admin
     * Super_admin otomatis bypass semua role checks
     * Contoh: middleware('role:super_admin') atau middleware('role:admin,super_admin')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        // Cek jika user tidak terautentikasi
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userRole = strtolower((string)$user->role);

        // Super_admin otomatis bypass semua role checks
        if ($userRole === 'super_admin') {
            return $next($request);
        }

        // Convert roles to lowercase untuk case-insensitive comparison
        $allowedRoles = array_map('strtolower', $roles);

        // Check if user's role ada di allowed roles
        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
