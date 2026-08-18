<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CmsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        // User CMS biasa — cukup login biasa
        if (Auth::check() && Auth::user()->role === 'cms') {
            return $next($request);
        }

        // Admin — harus punya flag cms_verified di session
        if (Auth::check() && Auth::user()->role === 'admin') {
            if (session('cms_verified')) {
                return $next($request);
            }
            // Belum verifikasi — arahkan ke form login CMS
            return redirect()->route('admin.cms-login')
                ->with('info', 'Silakan login CMS terlebih dahulu.');
        }

        // Selain itu — tidak boleh masuk
        abort(403);
    }
}
