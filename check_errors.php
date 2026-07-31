<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

$php = 'C:\laragon\bin\php\php-8.3.12-nts-Win32-vs16-x64\php.exe';

// Test semua route dengan method GET
$routes = [
    '/'                   => 'home',
    '/dokter'             => 'dokter',
    '/artikel'            => 'artikel',
    '/promo'              => 'promo',
    '/event'              => 'event',
    '/layanan'            => 'layanan',
    '/kontak'             => 'kontak',
    '/tentang-kami'       => 'tentang',
];

echo "\n=== TEST ROUTES RENDERING (via PHP) ===\n";

foreach ($routes as $url => $name) {
    try {
        $request = Request::create($url, 'GET');
        $response = $app->handle($request);
        $status = $response->getStatusCode();
        $icon = $status === 200 ? '✅' : '⚠️';
        echo "  {$icon} GET {$url} → HTTP {$status}\n";
    } catch (\Throwable $e) {
        echo "  ❌ GET {$url} → ERROR: " . $e->getMessage() . "\n";
        echo "     File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}

echo "\n=== TEST AUTH ROUTES (simulasi login admin) ===\n";
$authRoutes = [
    '/admin/dashboard',
    '/cms/dashboard',
];

// Buat fake user admin
$admin = \App\Models\User::where('role','admin')->first();
$cms   = \App\Models\User::where('role','cms')->first();

foreach ($authRoutes as $url) {
    try {
        $request = Request::create($url, 'GET');
        $request->setLaravelSession($app->make('session.store'));
        
        $user = str_contains($url, 'admin') ? $admin : $cms;
        if ($user) {
            Auth::setUser($user);
            $request->setUserResolver(fn() => $user);
        }
        
        $response = $app->handle($request);
        $status = $response->getStatusCode();
        $icon = ($status === 200 || $status === 302) ? '✅' : '⚠️';
        echo "  {$icon} GET {$url} → HTTP {$status}\n";
    } catch (\Throwable $e) {
        echo "  ❌ GET {$url} → ERROR: " . $e->getMessage() . "\n";
        echo "     File: " . basename($e->getFile()) . ":" . $e->getLine() . "\n";
    }
}

echo "\nDone.\n";
