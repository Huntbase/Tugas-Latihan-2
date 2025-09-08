<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class VerifyIsSupervisor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            // Kalau belum login
            return redirect()->route('login');
        }

        $supervisorRoleId = Role::where('role_name', 'supervisor')->first()->id;

        if ($user->role_id != $supervisorRoleId) {
            // Kalau bukan supervisor
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }

        // Kalau user adalah supervisor, lanjutkan request
        return $next($request);
    }
}
