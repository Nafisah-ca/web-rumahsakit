<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change gambar column to LONGTEXT so base64 images can be stored directly in database
        if (Schema::hasTable('page_banners')) {
            DB::statement("ALTER TABLE page_banners MODIFY gambar LONGTEXT NULL;");

            // Seed missing 'faq' page banner if it doesn't exist
            $exists = DB::table('page_banners')->where('page_key', 'faq')->exists();
            if (!$exists) {
                DB::table('page_banners')->insert([
                    'page_key'     => 'faq',
                    'nama_halaman' => 'Halaman FAQ',
                    'label_atas'   => 'Pusat Bantuan',
                    'judul'        => 'Pertanyaan yang Sering Ditanyakan (FAQ)',
                    'subjudul'     => 'Temukan jawaban atas pertanyaan seputar layanan, pendaftaran, dan fasilitas kami.',
                    'warna_awal'   => '#00521f',
                    'warna_akhir'  => '#00b04f',
                    'status'       => 'aktif',
                    'created_tm'   => now(),
                    'updated_tm'   => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('page_banners')) {
            DB::statement("ALTER TABLE page_banners MODIFY gambar VARCHAR(255) NULL;");
        }
    }
};
