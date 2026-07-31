<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: dokters
     * Fungsi: Menyimpan data dokter yang bertugas di rumah sakit.
     * Dikelola oleh: Admin
     * Relasi:
     *   - spesialisasis (N:1)  — setiap dokter memiliki satu spesialisasi utama
     *   - jadwal_dokters (1:N) — setiap dokter memiliki banyak jadwal praktik
     *   - janji_temus   (1:N) — setiap dokter dapat memiliki banyak janji temu
     */
    public function up(): void
    {
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spesialisasi_id')->constrained('spesialisasis')->restrictOnDelete();
            $table->string('nama');
            $table->string('gelar', 100)->nullable();              // e.g. "Sp.JP, FIHA"
            $table->string('slug')->unique();
            $table->string('foto')->nullable();
            $table->string('warna_dari', 20)->nullable();          // untuk kartu UI
            $table->string('warna_ke', 20)->nullable();
            $table->text('bio')->nullable();
            $table->string('pendidikan', 200)->nullable();         // e.g. "FK UI"
            $table->integer('tahun_pengalaman')->nullable();
            $table->string('no_str', 50)->nullable();              // Surat Tanda Registrasi
            $table->string('no_sip', 50)->nullable();              // Surat Izin Praktik
            $table->boolean('tersedia_online')->default(false);    // dokter online
            $table->boolean('is_aktif')->default(true);
            $table->integer('total_ulasan')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokters');
    }
};
