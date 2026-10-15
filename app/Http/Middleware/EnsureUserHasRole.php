<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware "role" (didaftarkan sebagai alias di bootstrap/app.php).
 * Lapis pertama otorisasi: route group /merchant hanya untuk role merchant,
 * /customer hanya untuk role customer; selain itu 403. Lapis kedua
 * (kepemilikan data) dicek per-resource di policy/controller.
 */
class EnsureUserHasRole
{
    /**
     * Pastikan user yang sedang login memiliki salah satu role yang diminta.
     *
     * Pemakaian di route:
     *   ->middleware('role:merchant')            // hanya merchant
     *   ->middleware('role:merchant,customer')   // keduanya boleh
     *
     * Jika tidak sesuai, request dihentikan dengan 403 (halaman error custom).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
