<?php

namespace App\Providers;

use App\Models\WebsiteSetting;
use App\Models\Spesialisasi;
use App\Models\KategoriLayanan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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

        // Share spesialisasi & kategori layanan ke layout public (navbar dinamis)
        View::composer('layouts.app', function ($view) {
            $view->with('nav_spesialisasi', Spesialisasi::orderBy('nama_spesialis')->get());

            // Kategori layanan untuk dropdown Pelayanan — guard jika tabel belum ada
            try {
                if (Schema::hasTable('kategori_layanan')) {
                    $nav_kategori_layanan = KategoriLayanan::with('layanansAktif')
                        ->where('status', 'aktif')
                        ->when(Schema::hasColumn('kategori_layanan', 'urutan'), fn($q) => $q->orderBy('urutan'))
                        ->orderBy('nama_kategori')
                        ->get();
                } else {
                    $nav_kategori_layanan = collect();
                }
            } catch (\Throwable $e) {
                $nav_kategori_layanan = collect();
            }

            $view->with('nav_kategori_layanan', $nav_kategori_layanan);
        });
    }
}
