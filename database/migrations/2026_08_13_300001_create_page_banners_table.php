<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('page_banners')) {
            Schema::create('page_banners', function (Blueprint $table) {
                $table->id();
                $table->string('page_key', 60)->unique(); // slug unik per halaman
                $table->string('nama_halaman', 100);       // label di CMS
                $table->string('label_atas', 100)->nullable();   // "PENAWARAN TERBAIK"
                $table->string('judul', 200)->nullable();         // "Promo & Penawaran Spesial"
                $table->string('subjudul', 300)->nullable();      // deskripsi singkat
                $table->string('gambar')->nullable();             // background image
                $table->string('warna_awal', 20)->default('#00521f');
                $table->string('warna_akhir', 20)->default('#00b04f');
                $table->enum('status', ['aktif','nonaktif'])->default('aktif');
                $table->timestamp('created_tm')->useCurrent();
                $table->timestamp('updated_tm')->useCurrent()->useCurrentOnUpdate();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            });

            // Seed data default untuk semua halaman
            $pages = [
                ['page_key'=>'pelayanan',       'nama_halaman'=>'Halaman Pelayanan',       'label_atas'=>'Layanan Medis',         'judul'=>'Pelayanan Kami',              'subjudul'=>'Berbagai layanan kesehatan komprehensif didukung dokter spesialis berpengalaman.'],
                ['page_key'=>'dokter',          'nama_halaman'=>'Halaman Dokter',           'label_atas'=>'Tim Medis',             'judul'=>'Dokter & Spesialis',          'subjudul'=>'Didukung dokter spesialis berpengalaman dan berdedikasi tinggi.'],
                ['page_key'=>'promo',           'nama_halaman'=>'Halaman Promo',            'label_atas'=>'Penawaran Terbaik',     'judul'=>'Promo & Penawaran Spesial',   'subjudul'=>'Dapatkan layanan kesehatan terbaik dengan harga terjangkau.'],
                ['page_key'=>'artikel',         'nama_halaman'=>'Halaman Artikel',          'label_atas'=>'Informasi Kesehatan',   'judul'=>'Artikel & Berita',            'subjudul'=>'Tips kesehatan dan informasi medis terkini dari tim dokter kami.'],
                ['page_key'=>'event',           'nama_halaman'=>'Halaman Event',            'label_atas'=>'Kegiatan Rumah Sakit',  'judul'=>'Event & Kegiatan',            'subjudul'=>'Jadwal seminar, pemeriksaan gratis, dan kegiatan kesehatan lainnya.'],
                ['page_key'=>'informasi',       'nama_halaman'=>'Halaman Informasi',        'label_atas'=>'Informasi Terkini',     'judul'=>'Informasi & Pengumuman',      'subjudul'=>'Informasi dan pengumuman terbaru dari RS Sari Sehat.'],
                ['page_key'=>'kontak',          'nama_halaman'=>'Halaman Hubungi Kami',     'label_atas'=>'Hubungi Kami',          'judul'=>'Kontak & Lokasi',             'subjudul'=>'Kami siap membantu Anda. Hubungi kami kapan saja.'],
                ['page_key'=>'tentang',         'nama_halaman'=>'Halaman Tentang Kami',     'label_atas'=>'Profil Rumah Sakit',    'judul'=>'Tentang RS Sari Sehat',       'subjudul'=>'Melayani dengan kasih sayang sejak berdirinya rumah sakit kami.'],
                ['page_key'=>'mcu',             'nama_halaman'=>'Halaman Medical Check-Up', 'label_atas'=>'Kesehatan Preventif',   'judul'=>'Medical Check-Up',            'subjudul'=>'Deteksi dini penyakit untuk hidup lebih sehat dan berkualitas.'],
                ['page_key'=>'ulasan',          'nama_halaman'=>'Halaman Ulasan',           'label_atas'=>'Testimoni Pasien',      'judul'=>'Ulasan & Testimoni',          'subjudul'=>'Cerita nyata dari pasien kami yang telah merasakan pelayanan terbaik.'],
                ['page_key'=>'layanan-kategori','nama_halaman'=>'Halaman Kategori Layanan', 'label_atas'=>'Kategori Layanan',      'judul'=>'Layanan Pilihan',             'subjudul'=>'Pilih layanan yang Anda butuhkan dari kategori yang tersedia.'],
                ['page_key'=>'fasilitas',       'nama_halaman'=>'Halaman Fasilitas',        'label_atas'=>'Sarana & Prasarana',    'judul'=>'Fasilitas Kami',              'subjudul'=>'Peralatan medis modern dan fasilitas berstandar tinggi.'],
                ['page_key'=>'kebijakan-privasi','nama_halaman'=>'Halaman Kebijakan Privasi','label_atas'=>'Legal',                'judul'=>'Kebijakan Privasi',           'subjudul'=>'Informasi mengenai bagaimana kami melindungi data pribadi Anda.'],
                ['page_key'=>'syarat-ketentuan','nama_halaman'=>'Halaman Syarat & Ketentuan','label_atas'=>'Legal',               'judul'=>'Syarat & Ketentuan',          'subjudul'=>'Ketentuan penggunaan layanan dan website RS Sari Sehat.'],
                ['page_key'=>'live-antrian',    'nama_halaman'=>'Halaman Live Antrian',    'label_atas'=>'Pantau Antrian',        'judul'=>'Live Antrian',                'subjudul'=>'Pantau antrian poliklinik secara real-time.'],
            ];

            foreach ($pages as $p) {
                DB::table('page_banners')->insert(array_merge($p, [
                    'warna_awal'  => '#00521f',
                    'warna_akhir' => '#00b04f',
                    'status'      => 'aktif',
                    'created_tm'  => now(),
                    'updated_tm'  => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_banners');
    }
};
