<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedIfAuthenticated
{
    /**
     * Jika user sudah login tapi belum verifikasi email → redirect ke halaman verifikasi.
     * Jika user belum login (guest) → biarkan lewat (guest checkout).
     * Jika user sudah login DAN sudah verifikasi → biarkan lewat.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! $request->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
