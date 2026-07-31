<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: events
     * Fungsi: Menyimpan jadwal kegiatan/event rumah sakit.
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('tipe', 100)->nullable();               // e.g. "Instagram Live", "Kegiatan Masjid"
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('warna', 20)->nullable();               // warna tema event
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('link_pendaftaran')->nullable();
            $table->boolean('is_online')->default(false);
            $table->enum('status', ['draft', 'published', 'selesai'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
