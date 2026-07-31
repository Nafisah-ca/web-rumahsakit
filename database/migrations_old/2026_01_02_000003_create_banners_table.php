<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: banners
     * Fungsi: Menyimpan data hero slider/banner homepage dan halaman lainnya.
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('subjudul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();                   // path gambar
            $table->string('warna_dari', 20)->nullable();           // gradient from hex
            $table->string('warna_ke', 20)->nullable();             // gradient to hex
            $table->string('badge_label', 100)->nullable();         // label kecil di atas judul
            $table->string('teks_tombol_1', 100)->nullable();
            $table->string('url_tombol_1')->nullable();
            $table->string('teks_tombol_2', 100)->nullable();
            $table->string('url_tombol_2')->nullable();
            $table->enum('posisi', ['homepage', 'layanan', 'promo', 'dokter'])->default('homepage');
            $table->boolean('is_aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
