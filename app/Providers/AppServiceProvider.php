<?php

namespace App\Providers;

use App\Models\Faq;
use App\Models\WebsiteSetting;
use App\Models\Spesialisasi;
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

        // Share spesialisasi & dokter counts ke layout public (navbar Dokter dinamis)
        View::composer('layouts.app', function ($view) {
            $view->with('nav_spesialisasi', Spesialisasi::orderBy('nama_spesialis')->get());
            $view->with('footer_faqs', Faq::aktif()->get());
        });
    }
}
