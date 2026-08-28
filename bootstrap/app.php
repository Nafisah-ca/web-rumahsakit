<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TrackPageVisit;
use App\Http\Middleware\UpdateLastActivity;
use App\Http\Middleware\CmsVerified;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Track semua kunjungan halaman publik
        $middleware->web(append: [
            TrackPageVisit::class,
        ]);

        $middleware->alias([
            'role'          => RoleMiddleware::class,
            'last.activity' => UpdateLastActivity::class,
            'cms.verified'  => CmsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
