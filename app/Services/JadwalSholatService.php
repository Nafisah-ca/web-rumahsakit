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

    /** Pemetaan Provinsi & Wilayah Indonesia ke Kota Utama/Ibu Kota */
    public const PROVINCE_CITY_MAP = [
        // Kalimantan
        'kalimantan timur'           => 'Samarinda',
        'kaltim'                     => 'Samarinda',
        'east kalimantan'            => 'Samarinda',
        'kalimantan selatan'         => 'Banjarmasin',
        'kalsel'                     => 'Banjarmasin',
        'south kalimantan'           => 'Banjarmasin',
        'kalimantan barat'           => 'Pontianak',
        'kalbar'                     => 'Pontianak',
        'west kalimantan'            => 'Pontianak',
        'kalimantan tengah'          => 'Palangkaraya',
        'kalteng'                    => 'Palangkaraya',
        'central kalimantan'         => 'Palangkaraya',
        'kalimantan utara'           => 'Tarakan',
        'kaltara'                    => 'Tarakan',
        'north kalimantan'           => 'Tarakan',
        'kalimantan'                 => 'Samarinda',
        'borneo'                     => 'Samarinda',

        // Jawa
        'dki jakarta'                => 'Jakarta',
        'jakarta'                    => 'Jakarta',
        'jawa barat'                 => 'Bandung',
        'jabar'                      => 'Bandung',
        'west java'                  => 'Bandung',
        'jawa tengah'                => 'Semarang',
        'jateng'                     => 'Semarang',
        'central java'               => 'Semarang',
        'jawa timur'                 => 'Surabaya',
        'jatim'                      => 'Surabaya',
        'east java'                  => 'Surabaya',
        'di yogyakarta'              => 'Yogyakarta',
        'daerah istimewa yogyakarta' => 'Yogyakarta',
        'jogja'                      => 'Yogyakarta',
        'yogyakarta'                 => 'Yogyakarta',
        'banten'                     => 'Serang',

        // Sumatera
        'sumatera barat'             => 'Padang',
        'sumbar'                     => 'Padang',
        'west sumatra'               => 'Padang',
        'sumatera utara'             => 'Medan',
        'sumut'                      => 'Medan',
        'north sumatra'              => 'Medan',
        'sumatera selatan'           => 'Palembang',
        'sumsel'                     => 'Palembang',
        'south sumatra'              => 'Palembang',
        'aceh'                       => 'Banda Aceh',
        'nanggroe aceh darussalam'   => 'Banda Aceh',
        'riau'                       => 'Pekanbaru',
        'kepulauan riau'             => 'Batam',
        'kepri'                      => 'Batam',
        'jambi'                      => 'Jambi',
        'bengkulu'                   => 'Bengkulu',
        'lampung'                    => 'Bandar Lampung',
        'bangka belitung'            => 'Pangkal Pinang',
        'babel'                      => 'Pangkal Pinang',

        // Bali & Nusa Tenggara
        'bali'                       => 'Denpasar',
        'nusa tenggara barat'        => 'Mataram',
        'ntb'                        => 'Mataram',
        'nusa tenggara timur'        => 'Kupang',
        'ntt'                        => 'Kupang',

        // Sulawesi
        'sulawesi selatan'           => 'Makassar',
        'sulsel'                     => 'Makassar',
        'south sulawesi'             => 'Makassar',
        'sulawesi utara'             => 'Manado',
        'sulut'                      => 'Manado',
        'north sulawesi'             => 'Manado',
        'sulawesi tengah'            => 'Palu',
        'sulteng'                    => 'Palu',
        'sulawesi tenggara'          => 'Kendari',
        'sultra'                     => 'Kendari',
        'gorontalo'                  => 'Gorontalo',
        'sulawesi barat'             => 'Mamuju',
        'sulbar'                     => 'Mamuju',

        // Maluku & Papua
        'maluku'                     => 'Ambon',
        'maluku utara'               => 'Ternate',
        'papua'                      => 'Jayapura',
        'papua barat'                => 'Sorong',
        'papua selatan'              => 'Merauke',
        'papua tengah'               => 'Nabire',
        'papua pegunungan'           => 'Jayawijaya',
        'papua barat daya'           => 'Sorong',
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
     * Ambil jadwal sholat otomatis mendeteksi lokasi user dari HTTP Request
     * (Query Parameter -> Session -> Cookie -> User Profile -> IP Geolocation -> Default Fallback)
     */
    public static function getJadwalForCurrentRequest(?\Illuminate\Http\Request $request = null, ?string $date = null): array
    {
        $request = $request ?? request();

        // 1. Cek query parameter URL (misal: ?city=Samarinda atau ?kota_id=2310)
        $queryCity   = $request?->query('city') ?? $request?->query('kota');
        $queryKotaId = $request?->query('kota_id');
        if ($queryKotaId || $queryCity) {
            return self::getJadwalByLocation($queryCity, $queryKotaId, $date);
        }

        // 2. Cek Session login user
        $sessionCity = session('user_sholat_city');
        if ($sessionCity) {
            $res = self::getJadwalByLocation($sessionCity, null, $date);
            if (!empty($res['status'])) return $res;
        }

        // 3. Cek Cookie browser user
        $cookieCity = $request?->cookie('user_sholat_city');
        if ($cookieCity) {
            $res = self::getJadwalByLocation($cookieCity, null, $date);
            if (!empty($res['status'])) return $res;
        }

        // 4. Jika user sedang login (role: pasien), cek alamat di profilnya
        if (auth()->check()) {
            $alamat = auth()->user()->pasien?->alamat;
            if ($alamat) {
                $cityFromAddr = self::detectCityFromAddress($alamat);
                if ($cityFromAddr) {
                    $res = self::getJadwalByLocation($cityFromAddr, null, $date);
                    if (!empty($res['status'])) return $res;
                }
            }
        }

        // 5. Cek lokasi via IP Geolocation client
        $ip = $request?->ip();
        if ($ip) {
            $ipCity = self::resolveLocationFromIp($ip);
            if ($ipCity) {
                $res = self::getJadwalByLocation($ipCity, null, $date);
                if (!empty($res['status'])) return $res;
            }
        }

        // 6. Default fallback
        return self::getJadwal($date);
    }

    /**
     * Deteksi nama kota/provinsi dari teks alamat pasien
     */
    public static function detectCityFromAddress(?string $address): ?string
    {
        if (empty(trim($address ?? ''))) {
            return null;
        }

        $lower = strtolower($address);

        // Cek nama provinsi / regional
        foreach (self::PROVINCE_CITY_MAP as $prov => $targetCity) {
            if (str_contains($lower, $prov)) {
                return $targetCity;
            }
        }

        // Cek daftar kota populer di Indonesia
        $popularCities = [
            'samarinda', 'balikpapan', 'banjarmasin', 'pontianak', 'palangkaraya', 'tarakan',
            'kutai kartanegara', 'banjarbaru', 'singkawang', 'bontang', 'sampit', 'pangkalan bun',
            'padang', 'bukittinggi', 'payakumbuh', 'pariaman', 'solok', 'surabaya', 'malang',
            'bandung', 'bogor', 'bekasi', 'depok', 'tangerang', 'semarang', 'solo', 'surakarta',
            'yogyakarta', 'sleman', 'bantul', 'medan', 'palembang', 'pekanbaru', 'batam',
            'makassar', 'manado', 'palu', 'kendari', 'denpasar', 'mataram', 'kupang', 'jayapura', 'jakarta'
        ];

        foreach ($popularCities as $city) {
            if (preg_match('/\b' . preg_quote($city, '/') . '\b/i', $lower)) {
                return ucfirst($city);
            }
        }

        return null;
    }

    /**
     * Resolusi nama kota/daerah dari IP Pengunjung
     */
    public static function resolveLocationFromIp(?string $ip): ?string
    {
        if (empty($ip) || in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return null;
        }

        $cacheKey = 'ip_geo_loc_' . md5($ip);
        return Cache::remember($cacheKey, now()->addHours(24), function () use ($ip) {
            try {
                $response = Http::timeout(2.5)->get("http://ip-api.com/json/{$ip}?fields=status,city,regionName,country");
                if ($response->successful()) {
                    $json = $response->json();
                    if (($json['status'] ?? '') === 'success') {
                        $city = $json['city'] ?? null;
                        $region = $json['regionName'] ?? null;
                        return $city ?: $region;
                    }
                }
            } catch (\Throwable $e) {
                Log::debug("IP Geolocation failed for {$ip}: " . $e->getMessage());
            }
            return null;
        });
    }

    /**
     * Resolusi nama kota/kabupaten/provinsi hasil Geolocation ke ID kota MyQuran
     */
    public static function resolveKotaByName(string $query): ?array
    {
        $query = trim($query);
        if (empty($query)) {
            return null;
        }

        $lowerQuery = strtolower($query);

        // Cek apakah query merupakan nama provinsi / alias (misal: "Kalimantan Timur", "Kaltim", "Kalimantan")
        if (isset(self::PROVINCE_CITY_MAP[$lowerQuery])) {
            $query = self::PROVINCE_CITY_MAP[$lowerQuery];
        } else {
            // Cek frasa kata utuh jika mengandung nama provinsi (gunakan word boundary agar tidak bentrok misal 'bali' di 'balikpapan')
            foreach (self::PROVINCE_CITY_MAP as $alias => $mappedCity) {
                if (preg_match('/\b' . preg_quote($alias, '/') . '\b/i', $lowerQuery)) {
                    $query = $mappedCity;
                    break;
                }
            }
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
