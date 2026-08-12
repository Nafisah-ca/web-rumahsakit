<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisit
{
    // Prefix yang tidak dicatat (admin, cms, api, auth, portal)
    private const SKIP_PREFIXES = [
        'admin', 'cms', 'portal', 'api',
        'sign-in', 'sign-out', 'daftar',
        '_ignition', '_debugbar', 'livewire',
        'favicon', 'build', 'storage',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Hanya catat GET requests dengan response sukses (2xx/3xx)
        if (
            $request->isMethod('GET') &&
            $response->getStatusCode() < 400 &&
            !$request->ajax() &&
            !$request->wantsJson() &&
            $this->shouldTrack($request)
        ) {
            try {
                PageVisit::create([
                    'ip_address' => $request->ip(),
                    'page_title' => null,               // JS bisa update, atau extract dari response
                    'page_url'   => $request->fullUrl(),
                    'user_agent' => substr($request->userAgent() ?? '', 0, 500),
                    'referer'    => substr($request->headers->get('referer') ?? '', 0, 1000),
                    'user_id'    => auth()->id(),
                    'visited_at' => now(),
                ]);
            } catch (\Throwable) {
                // Jangan gagalkan request hanya karena tracking error
            }
        }

        return $response;
    }

    private function shouldTrack(Request $request): bool
    {
        $path = ltrim($request->path(), '/');

        foreach (self::SKIP_PREFIXES as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/')) {
                return false;
            }
        }

        return true;
    }
}
