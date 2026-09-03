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
     * Dapat menerima $kotaId dan $kotaNama secara spesifik (misal dari hasil deteksi lokasi user).
     * Sesuai kondisi:
     * a. Default baca via API MyQuran per lokasi dan per hari
     * b. Jika API Off/Gagal, otomatis fallback baca ke Database/Manual
     */
    public static function getJadwal(?string $date = null, ?string $kotaId = null, ?string $kotaNama = null): array
    {
        $config  = self::getSettingConfig();
        $dateObj = $date ? \Carbon\Carbon::parse($date) : now();
        $dateStr = $dateObj->toDateString();
        $tahun   = $dateObj->format('Y');
        $bulan   = $dateObj->format('m');
        $hari    = $dateObj->format('d');

        $targetKotaId   = $kotaId ?? $config['kota_id'];
        $targetKotaNama = $kotaNama ?? ($kotaId ? null : $config['kota_nama']);

        // Jika mode diset manual oleh admin dan tidak ada custom kota yang diminta
        if ($config['mode'] === 'manual' && empty($kotaId)) {
            return self::buildManualResponse($config, $dateStr, 'Pengaturan manual aktif');
        }

        // Mode API: coba ambil dari Cache terlebih dahulu
        $cacheKey = "jadwal_sholat_{$targetKotaId}_{$dateStr}";
        $cached   = Cache::get($cacheKey);
        if ($cached && is_array($cached)) {
            return $cached;
        }

        // Panggil API Publik MyQuran
        try {
            $apiUrl   = self::API_BASE_URL . "/jadwal/{$targetKotaId}/{$tahun}/{$bulan}/{$hari}";
            $response = Http::timeout(3)->get($apiUrl);

            if ($response->successful()) {
                $json = $response->json();
                if (!empty($json['status']) && !empty($json['data']['jadwal'])) {
                    $jadwalApi = $json['data']['jadwal'];
                    $lokasiNama = $json['data']['lokasi'] ?? ($targetKotaNama ?? $config['kota_nama']);

                    $result = [
                        'status'            => true,
                        'sumber'            => 'api',
                        'sumber_label'      => 'API MyQuran (Kemenag RI)',
                        'kota_id'           => $targetKotaId,
                        'lokasi'            => $lokasiNama,
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
            Log::warning("Gagal memanggil API Jadwal Sholat (kotaId: {$targetKotaId}): " . $e->getMessage());
        }

        // Fallback otomatis ke database jika API offline/gagal
        $fallback = self::buildManualResponse($config, $dateStr, 'API offline, beralih ke data database');
        if ($targetKotaNama) {
            $fallback['lokasi'] = $targetKotaNama;
        }
        return $fallback;
    }

    /**
     * Ambil jadwal sholat berdasarkan nama lokasi / kota user secara dinamis
     */
    public static function getJadwalByLocation(?string $cityName = null, ?string $kotaId = null, ?string $date = null): array
    {
        if (!empty($kotaId)) {
            return self::getJadwal($date, $kotaId);
        }

        if (!empty($cityName)) {
            $resolved = self::resolveKotaByName($cityName);
            if ($resolved && !empty($resolved['id'])) {
                return self::getJadwal($date, $resolved['id'], $resolved['lokasi']);
            }
        }

        return self::getJadwal($date);
    }

    /**
     * Resolusi nama kota/kabupaten hasil Geolocation ke ID kota MyQuran
     */
    public static function resolveKotaByName(string $query): ?array
    {
        $query = trim($query);
        if (empty($query)) {
            return null;
        }

        $cacheKey = 'sholat_resolve_kota_' . md5(strtolower($query));
        return Cache::remember($cacheKey, now()->addDays(7), function () use ($query) {
            // Bersihkan prefix umum administratif
            $cleaned = trim(preg_replace('/^(kota administrasi|kota|kabupaten|kab\.|daerah khusus ibukota|dki)\s+/i', '', $query));
            
            // Cari dari API
            $results = self::cariKota($cleaned);
            if (empty($results) && str_contains($cleaned, ' ')) {
                $firstWord = explode(' ', $cleaned)[0];
                $results = self::cariKota($firstWord);
            }

            if (empty($results)) {
                return null;
            }

            $normalize = fn(string $s) => preg_replace('/[^A-Z0-9]/', '', strtoupper($s));
            $qNorm  = $normalize($query);
            $cNorm  = $normalize($cleaned);
            $isKota = (bool)preg_match('/^(kota|dki|daerah)/i', $query);
            $isKab  = (bool)preg_match('/^(kabupaten|kab\.)/i', $query);

            // 1. Exact match normalisasi query (misal: "KOTAPADANGPANJANG" === "KOTAPADANGPANJANG")
            foreach ($results as $item) {
                $lokNorm = $normalize($item['lokasi'] ?? '');
                if ($lokNorm === $qNorm) {
                    return $item;
                }
            }

            // 2. Jika user query 'Kota ...', prioritaskan "KOTA..."
            if ($isKota) {
                foreach ($results as $item) {
                    $lokNorm = $normalize($item['lokasi'] ?? '');
                    if ($lokNorm === "KOTA{$cNorm}" || str_starts_with($lokNorm, "KOTA{$cNorm}")) {
                        return $item;
                    }
                }
            }

            // 3. Jika user query 'Kabupaten ...', prioritaskan "KAB..."
            if ($isKab) {
                foreach ($results as $item) {
                    $lokNorm = $normalize($item['lokasi'] ?? '');
                    if ($lokNorm === "KAB{$cNorm}" || str_starts_with($lokNorm, "KAB{$cNorm}")) {
                        return $item;
                    }
                }
            }

            // 4. Exact match dengan nama cleaned
            foreach ($results as $item) {
                $lokNorm = $normalize($item['lokasi'] ?? '');
                if ($lokNorm === "KOTA{$cNorm}" || $lokNorm === "KAB{$cNorm}" || $lokNorm === $cNorm) {
                    return $item;
                }
            }

            // 5. Fallback ke item pertama yang ditemukan
            return $results[0] ?? null;
        });
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
            'kota_id'           => $config['kota_id'] ?? '1301',
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
