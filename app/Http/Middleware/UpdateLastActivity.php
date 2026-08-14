<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Update tiap request di CMS — throttle tiap 1 menit supaya tidak spam query
            $lastActivity = $user->last_activity;
            if (!$lastActivity || now()->diffInSeconds($lastActivity) >= 60) {
                $user->timestamps = false;
                $user->update(['last_activity' => now()]);
                $user->timestamps = true;
            }
        }

        return $next($request);
    }
}
