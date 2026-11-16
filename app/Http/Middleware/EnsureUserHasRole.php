<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Contoh pemakaian di route:
     * Route::get('/admin/users', ...)->middleware('role:superadmin');
     * Route::get('/verifikasi', ...)->middleware('role:superadmin,admin_dinas');
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses untuk halaman ini.');
        }

        return $next($request);
    }
}