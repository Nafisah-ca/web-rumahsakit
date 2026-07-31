<?php
/**
 * One-time promo seeder — run via browser, then DELETE this file.
 * URL: http://127.0.0.1:8000/seed_promos.php  (or via Laragon: http://rumahsakit.test/seed_promos.php)
 */

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Promo;
use App\Models\User;

$cms = User::where('role', 'cms')->first() ?? User::where('role', 'admin')->first();
$cmsId = $cms?->id;

$promosData = [
    [
        'judul'           => 'Paket Medical Check-Up Hemat',
        'deskripsi'       => 'Dapatkan paket pemeriksaan kesehatan lengkap meliputi cek darah, urine, rontgen thorax, dan konsultasi dokter umum dengan harga spesial. Cocok untuk karyawan dan keluarga yang ingin memastikan kondisi kesehatan secara menyeluruh.',
        'tanggal_mulai'   => '2026-07-01',
        'tanggal_selesai' => '2026-09-30',
        'status'          => 'aktif',
        'created_by'      => $cmsId,
    ],
    [
        'judul'           => 'Diskon 20% Konsultasi Spesialis Jantung',
        'deskripsi'       => 'Kami memberikan diskon 20% untuk biaya konsultasi dokter spesialis jantung dan pembuluh darah sepanjang bulan ini. Jaga kesehatan jantung Anda bersama dokter berpengalaman kami.',
        'tanggal_mulai'   => '2026-07-15',
        'tanggal_selesai' => '2026-08-31',
        'status'          => 'aktif',
        'created_by'      => $cmsId,
    ],
    [
        'judul'           => 'Promo Imunisasi Anak Lengkap',
        'deskripsi'       => 'Program imunisasi lengkap untuk anak usia 0–5 tahun dengan harga terjangkau. Termasuk vaksin wajib dan vaksin tambahan yang direkomendasikan oleh dokter spesialis anak RS Sari Sehat.',
        'tanggal_mulai'   => '2026-07-01',
        'tanggal_selesai' => '2026-10-31',
        'status'          => 'aktif',
        'created_by'      => $cmsId,
    ],
    [
        'judul'           => 'Free Ongkir Ambulans Dalam Kota',
        'deskripsi'       => 'Gratis biaya transportasi ambulans untuk pasien yang dirujuk ke RS Sari Sehat dari dalam kota Depok dan sekitarnya. Berlaku untuk kasus gawat darurat selama periode promosi berlangsung.',
        'tanggal_mulai'   => '2026-08-01',
        'tanggal_selesai' => '2026-08-31',
        'status'          => 'aktif',
        'created_by'      => $cmsId,
    ],
    [
        'judul'           => 'Paket Persalinan Normal Spesial',
        'deskripsi'       => 'Nikmati paket persalinan normal dengan fasilitas kamar kelas 1, pendampingan bidan profesional, dan perawatan bayi baru lahir. Daftarkan kehamilan Anda sekarang dan dapatkan harga promo terbaik.',
        'tanggal_mulai'   => '2026-07-01',
        'tanggal_selesai' => '2026-12-31',
        'status'          => 'aktif',
        'created_by'      => $cmsId,
    ],
    [
        'judul'           => 'Diskon Laboratorium Gula Darah & Kolesterol',
        'deskripsi'       => 'Cek gula darah puasa, HbA1c, dan profil lipid (kolesterol lengkap) dengan harga diskon spesial. Tersedia setiap hari Senin–Jumat tanpa perlu janji temu. Hasil keluar dalam 2 jam.',
        'tanggal_mulai'   => '2026-07-10',
        'tanggal_selesai' => '2026-09-10',
        'status'          => 'aktif',
        'created_by'      => $cmsId,
    ],
];

$inserted = 0;
$skipped  = 0;

foreach ($promosData as $p) {
    $exists = Promo::where('judul', $p['judul'])->exists();
    if ($exists) {
        $skipped++;
        continue;
    }
    Promo::create($p);
    $inserted++;
}

$total = Promo::count();

echo "<pre style='font-family:monospace; padding:20px; background:#f0fdf4; border:1px solid #86efac; border-radius:8px;'>";
echo "✅ Seeder selesai!\n";
echo "   Inserted : {$inserted}\n";
echo "   Skipped  : {$skipped} (sudah ada)\n";
echo "   Total promo di DB: {$total}\n\n";
echo "⚠️  HAPUS file ini setelah dijalankan: public/seed_promos.php\n";
echo "</pre>";
