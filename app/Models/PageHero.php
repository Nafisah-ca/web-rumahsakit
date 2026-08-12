<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageHero extends Model
{
    protected $table = 'page_hero';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';

    protected $fillable = [
        'page_key', 'label', 'judul', 'deskripsi',
        'gambar', 'warna_dari', 'warna_ke', 'status', 'updated_by',
    ];

    /**
     * Ambil hero untuk halaman tertentu.
     * Jika tidak ada / nonaktif → return default berdasarkan key.
     */
    public static function forPage(string $key): static
    {
        $hero = static::where('page_key', $key)->where('status', 'aktif')->first();
        return $hero ?? static::makeDefault($key);
    }

    private static function makeDefault(string $key): static
    {
        $defaults = [
            'layanan'   => ['label'=>'Layanan Medis',         'judul'=>'Semua Pelayanan',          'deskripsi'=>'Berbagai layanan kesehatan komprehensif didukung dokter spesialis berpengalaman.', 'warna_dari'=>'#00521f','warna_ke'=>'#00b04f'],
            'dokter'    => ['label'=>'Tim Medis Profesional',  'judul'=>'Jadwal Dokter',            'deskripsi'=>'Temukan dokter spesialis terbaik kami dan buat janji temu dengan mudah.',          'warna_dari'=>'#00521f','warna_ke'=>'#00b04f'],
            'promo'     => ['label'=>'Penawaran Terbaik',      'judul'=>'Promo & Penawaran Spesial','deskripsi'=>'Dapatkan layanan kesehatan terbaik dengan harga terjangkau.',                       'warna_dari'=>'#00521f','warna_ke'=>'#00b04f'],
            'artikel'   => ['label'=>'Tips & Edukasi',         'judul'=>'Artikel Kesehatan',        'deskripsi'=>'Informasi kesehatan terkini dari tim medis RS Sari Sehat.',                         'warna_dari'=>'#1e3a5f','warna_ke'=>'#0284c7'],
            'event'     => ['label'=>'Jadwal Kegiatan',        'judul'=>'Event & Kegiatan',         'deskripsi'=>'Ikuti event kesehatan dan edukasi dari RS Sari Sehat.',                             'warna_dari'=>'#4c1d95','warna_ke'=>'#7c3aed'],
            'tentang'   => ['label'=>'Profil Rumah Sakit',     'judul'=>'Tentang Kami',             'deskripsi'=>'Mengenal lebih dekat RS Sari Sehat, visi, misi, dan komitmen kami.',                'warna_dari'=>'#00521f','warna_ke'=>'#00b04f'],
            'kontak'    => ['label'=>'Hubungi Kami',           'judul'=>'Kontak',                   'deskripsi'=>'Kami siap membantu Anda. Hubungi kami atau buat janji temu.',                       'warna_dari'=>'#00521f','warna_ke'=>'#00b04f'],
            'mcu'       => ['label'=>'Cek Kesehatan Anda',     'judul'=>'Medical Check-Up',         'deskripsi'=>'Paket pemeriksaan kesehatan menyeluruh untuk deteksi dini penyakit.',               'warna_dari'=>'#0c4a6e','warna_ke'=>'#0369a1'],
            'informasi' => ['label'=>'Berita & Informasi',     'judul'=>'Informasi Terkini',        'deskripsi'=>'Informasi terbaru seputar layanan dan kegiatan rumah sakit.',                       'warna_dari'=>'#00521f','warna_ke'=>'#00b04f'],
        ];

        $d = $defaults[$key] ?? ['label'=>'', 'judul'=>'Halaman', 'deskripsi'=>'', 'warna_dari'=>'#00521f', 'warna_ke'=>'#00b04f'];

        $hero = new static();
        $hero->page_key   = $key;
        $hero->label      = $d['label'];
        $hero->judul      = $d['judul'];
        $hero->deskripsi  = $d['deskripsi'];
        $hero->warna_dari = $d['warna_dari'];
        $hero->warna_ke   = $d['warna_ke'];
        $hero->status     = 'aktif';
        return $hero;
    }

    /** Label tampil untuk UI CMS */
    public static function pageLabel(string $key): string
    {
        return [
            'layanan'   => 'Halaman Pelayanan',
            'dokter'    => 'Halaman Dokter',
            'promo'     => 'Halaman Promo',
            'artikel'   => 'Halaman Artikel',
            'event'     => 'Halaman Event & Kegiatan',
            'tentang'   => 'Halaman Tentang Kami',
            'kontak'    => 'Halaman Hubungi Kami',
            'mcu'       => 'Halaman Medical Check-Up',
            'informasi' => 'Halaman Informasi Terkini',
        ][$key] ?? ucfirst($key);
    }

    public static function allKeys(): array
    {
        return ['layanan','dokter','promo','artikel','event','tentang','kontak','mcu','informasi'];
    }
}
