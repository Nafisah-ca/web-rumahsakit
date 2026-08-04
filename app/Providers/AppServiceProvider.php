<?php

namespace App\Providers;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Database baru tidak punya kolom remember_token
        // Sudah dihandle di Model User dengan $rememberTokenName = false

        // Share setting global ke semua view
        View::share('setting_global', WebsiteSetting::getSetting());
    }
}
