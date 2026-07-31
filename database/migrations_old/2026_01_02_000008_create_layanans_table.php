<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: layanans
     * Fungsi: Menyimpan daftar layanan/fasilitas medis yang tersedia.
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('kode', 30)->nullable()->unique();      // e.g. "igd", "rawat-jalan"
            $table->string('icon_fa', 50)->nullable();
            $table->string('warna', 20)->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('fasilitas')->nullable();                  // JSON: array sub-fasilitas
            $table->string('jam_operasional', 100)->nullable();    // e.g. "24 Jam / 7 Hari"
            $table->boolean('tersedia_online')->default(false);
            $table->boolean('is_aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanans');
    }
};
