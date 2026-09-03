<?php

namespace App\Services;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class JadwalSholatService
{
    protected const API_BASE_URL = 'https://api.myquran.com/v2/sholat';

    /** Default fallback jika DB belum diset */
    public const DEFAULT_FALLBACK = [
        'imsak'   => '04:30',
        'subuh'   => '04:40',
        'terbit'  => '05:52',
        'dhuha'   => '06:18',
        'dzuhur'  => '12:00',
        'ashar'   => '15:15',
        'maghrib' => '17:58',
        'isya'    => '19:08',
    ];

    /**
     * Ambil data pengaturan jadwal sholat dari website_setting
     */
    public static function getSettingConfig(): array
    {
        $setting = WebsiteSetting::first();
        $raw = $setting?->jadwal_sholat;

        $decoded = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);

        return [
            'mode'      => $decoded['mode'] ?? 'api', // 'api' atau 'manual'
            'kota_id'   => $decoded['kota_id'] ?? '1301', // Default: Kota Jakarta (1301)
            'kota_nama' => $decoded['kota_nama'] ?? 'KOTA JAKARTA',
            'manual'    => array_merge(self::DEFAULT_FALLBACK, $decoded['manual'] ?? []),
        ];
    }

    /**
     * Ambil jadwal sholat hari ini (atau tanggal tertentu).
     * Sesuai kondisi:
     * a. Default baca via API MyQuran per lokasi dan per hari
     * b. Jika API Off/Gagal, otomatis fallback baca ke Database/Manual
     */
    public static function getJadwal(?string $date = null): array
    {
        $config  = self::getSettingConfig();
        $dateObj = $date ? \Carbon\Carbon::parse($date) : now();
        $dateStr = $dateObj->toDateString();
        $tahun   = $dateObj->format('Y');
        $bulan   = $dateObj->format('m');
        $hari    = $dateObj->format('d');

        // Jika mode diset manual oleh admin
        if ($config['mode'] === 'manual') {
            return self::buildManualResponse($config, $dateStr, 'Pengaturan manual aktif');
        }

        // Mode API: coba ambil dari Cache terlebih dahulu
        $cacheKey = "jadwal_sholat_{$config['kota_id']}_{$dateStr}";
        $cached   = Cache::get($cacheKey);
        if ($cached && is_array($cached)) {
            return $cached;
        }

        // Panggil API Publik MyQuran
        try {
            $apiUrl   = self::API_BASE_URL . "/jadwal/{$config['kota_id']}/{$tahun}/{$bulan}/{$hari}";
            $response = Http::timeout(3)->get($apiUrl);

            if ($response->successful()) {
                $json = $response->json();
                if (!empty($json['status']) && !empty($json['data']['jadwal'])) {
                    $jadwalApi = $json['data']['jadwal'];

                    $result = [
                        'status'            => true,
                        'sumber'            => 'api',
                        'sumber_label'      => 'API MyQuran (Kemenag RI)',
                        'lokasi'            => $json['data']['lokasi'] ?? $config['kota_nama'],
                        'daerah'            => $json['data']['daerah'] ?? '',
                        'tanggal_label'     => $jadwalApi['tanggal'] ?? $dateObj->translatedFormat('l, d/m/Y'),
                        'date'              => $dateStr,
                        'times'             => [
                            'imsak'   => substr($jadwalApi['imsak'] ?? self::DEFAULT_FALLBACK['imsak'], 0, 5),
                            'subuh'   => substr($jadwalApi['subuh'] ?? self::DEFAULT_FALLBACK['subuh'], 0, 5),
                            'terbit'  => substr($jadwalApi['terbit'] ?? self::DEFAULT_FALLBACK['terbit'], 0, 5),
                            'dhuha'   => substr($jadwalApi['dhuha'] ?? self::DEFAULT_FALLBACK['dhuha'], 0, 5),
                            'dzuhur'  => substr($jadwalApi['dzuhur'] ?? self::DEFAULT_FALLBACK['dzuhur'], 0, 5),
                            'ashar'   => substr($jadwalApi['ashar'] ?? self::DEFAULT_FALLBACK['ashar'], 0, 5),
                            'maghrib' => substr($jadwalApi['maghrib'] ?? self::DEFAULT_FALLBACK['maghrib'], 0, 5),
                            'isya'    => substr($jadwalApi['isya'] ?? self::DEFAULT_FALLBACK['isya'], 0, 5),
                        ],
                    ];

                    $result['sholat_berikutnya'] = self::hitungSholatBerikutnya($result['times']);

                    // Cache selama 6 jam
                    Cache::put($cacheKey, $result, now()->addHours(6));

                    return $result;
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Gagal memanggil API Jadwal Sholat: " . $e->getMessage());
        }

        // Fallback otomatis ke database jika API offline/gagal
        return self::buildManualResponse($config, $dateStr, 'API offline, beralih ke data database');
    }

    /**
     * Helper struktur respon fallback manual
     */
    protected static function buildManualResponse(array $config, string $dateStr, string $keterangan): array
    {
        $dateObj = \Carbon\Carbon::parse($dateStr);
        $manualTimes = $config['manual'] ?? self::DEFAULT_FALLBACK;

        $times = [
            'imsak'   => substr($manualTimes['imsak'] ?? self::DEFAULT_FALLBACK['imsak'], 0, 5),
            'subuh'   => substr($manualTimes['subuh'] ?? self::DEFAULT_FALLBACK['subuh'], 0, 5),
            'terbit'  => substr($manualTimes['terbit'] ?? self::DEFAULT_FALLBACK['terbit'], 0, 5),
            'dhuha'   => substr($manualTimes['dhuha'] ?? self::DEFAULT_FALLBACK['dhuha'], 0, 5),
            'dzuhur'  => substr($manualTimes['dzuhur'] ?? self::DEFAULT_FALLBACK['dzuhur'], 0, 5),
            'ashar'   => substr($manualTimes['ashar'] ?? self::DEFAULT_FALLBACK['ashar'], 0, 5),
            'maghrib' => substr($manualTimes['maghrib'] ?? self::DEFAULT_FALLBACK['maghrib'], 0, 5),
            'isya'    => substr($manualTimes['isya'] ?? self::DEFAULT_FALLBACK['isya'], 0, 5),
        ];

        return [
            'status'            => true,
            'sumber'            => 'database',
            'sumber_label'      => 'Database / Manual (' . $keterangan . ')',
            'lokasi'            => $config['kota_nama'] ?? 'KOTA JAKARTA',
            'daerah'            => 'Indonesia',
            'tanggal_label'     => $dateObj->translatedFormat('l, d/m/Y'),
            'date'              => $dateStr,
            'times'             => $times,
            'sholat_berikutnya' => self::hitungSholatBerikutnya($times),
        ];
    }

    /**
     * Hitung waktu sholat berikutnya berdasarkan jam saat ini
     */
    public static function hitungSholatBerikutnya(array $times): array
    {
        $now = now();
        $nowMinutes = ((int)$now->format('H')) * 60 + (int)$now->format('i');

        $mainPrayers = [
            'Subuh'   => $times['subuh'] ?? '04:40',
            'Dzuhur'  => $times['dzuhur'] ?? '12:00',
            'Ashar'   => $times['ashar'] ?? '15:15',
            'Maghrib' => $times['maghrib'] ?? '17:58',
            'Isya'    => $times['isya'] ?? '19:08',
        ];

        foreach ($mainPrayers as $name => $timeStr) {
            $parts = explode(':', $timeStr);
            $prayerMinutes = ((int)($parts[0] ?? 0)) * 60 + (int)($parts[1] ?? 0);

            if ($prayerMinutes > $nowMinutes) {
                $selisih = $prayerMinutes - $nowMinutes;
                $jam = floor($selisih / 60);
                $menit = $selisih % 60;
                $countdown = $jam > 0 ? "{$jam}j {$menit}m lagi" : "{$menit} menit lagi";

                return [
                    'nama'      => $name,
                    'jam'       => $timeStr,
                    'countdown' => $countdown,
                    'is_besok'  => false,
                ];
            }
        }

        // Jika semua sholat hari ini sudah lewat, maka berikutnya adalah Subuh besok
        return [
            'nama'      => 'Subuh',
            'jam'       => $times['subuh'] ?? '04:40',
            'countdown' => 'Besok subuh',
            'is_besok'  => true,
        ];
    }

    /**
     * Cari kota berdasarkan nama/kata kunci dari MyQuran API
     */
    public static function cariKota(string $keyword): array
    {
        $keyword = trim($keyword);
        if (strlen($keyword) < 2) {
            return [];
        }

        try {
            $response = Http::timeout(3)->get(self::API_BASE_URL . "/kota/cari/{$keyword}");
            if ($response->successful()) {
                $json = $response->json();
                if (!empty($json['status']) && !empty($json['data'])) {
                    return $json['data'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning("Gagal mencari kota di API MyQuran: " . $e->getMessage());
        }

        return [];
    }

    /**
     * Cek status koneksi API MyQuran
     */
    public static function checkApiHealth(): bool
    {
        try {
            $response = Http::timeout(2.5)->get(self::API_BASE_URL . "/kota/cari/jakarta");
            return $response->successful() && !empty($response->json('status'));
        } catch (\Throwable) {
            return false;
        }
    }
}
