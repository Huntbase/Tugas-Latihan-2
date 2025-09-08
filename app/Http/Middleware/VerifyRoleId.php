<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRoleId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roleIds): Response
    {
        $user = $request->user();

        // Jika belum login
        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah role_id user termasuk yang diizinkan
        if (!in_array($user->role_id, $roleIds)) {
            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
