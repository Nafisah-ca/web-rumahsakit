<?php

namespace App\Providers;

use App\Models\Faq;
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

        // Share akreditasi aktif ke semua view (pakai composer agar query
        // dieksekusi saat render view, bukan saat boot — aman saat migrate)
        View::composer('*', function ($view) {
            if (!$view->offsetExists('akreditasi_footer')) {
                try {
                    $data = \Illuminate\Support\Facades\Schema::hasTable('akreditasi')
                        ? \App\Models\Akreditasi::aktif()->get()
                        : collect();
                } catch (\Exception $e) {
                    $data = collect();
                }
                $view->with('akreditasi_footer', $data);
            }
        });

        // Share spesialisasi, kategori layanan & jadwal sholat ke layout public
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

            // Jadwal Sholat (Otomatis mendeteksi lokasi user/login/IP/browser)
            try {
                $jadwalSholat = \App\Services\JadwalSholatService::getJadwalForCurrentRequest(request());
            } catch (\Throwable $e) {
                $jadwalSholat = [
                    'status' => false,
                    'times' => \App\Services\JadwalSholatService::DEFAULT_FALLBACK,
                    'lokasi' => 'Indonesia',
                    'tanggal_label' => now()->translatedFormat('l, d/m/Y'),
                    'sumber' => 'database',
                    'sumber_label' => 'Fallback Sistem',
                    'sholat_berikutnya' => \App\Services\JadwalSholatService::hitungSholatBerikutnya(\App\Services\JadwalSholatService::DEFAULT_FALLBACK),
                ];
            }
            $view->with('jadwal_sholat_data', $jadwalSholat);
        });
    }
}
