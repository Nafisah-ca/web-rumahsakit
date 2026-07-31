<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: rumah_sakits
     * Fungsi: Menyimpan profil/informasi utama rumah sakit (tentang kami, visi, misi, dll.)
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('rumah_sakits', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('singkatan', 50)->nullable();          // e.g. "RS Sari Sehat"
            $table->string('tagline')->nullable();                 // Motto/tagline
            $table->text('deskripsi_singkat')->nullable();
            $table->longText('deskripsi_lengkap')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('nilai_utama')->nullable();               // JSON: array nilai
            $table->string('logo')->nullable();                    // path file
            $table->string('logo_putih')->nullable();              // logo versi putih
            $table->string('favicon')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('website')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('maps_embed')->nullable();              // URL Google Maps embed
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('akreditasi', 100)->nullable();         // e.g. "Paripurna"
            $table->year('tahun_berdiri')->nullable();
            $table->integer('jumlah_tempat_tidur')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rumah_sakits');
    }
};
