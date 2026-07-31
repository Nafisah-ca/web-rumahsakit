<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: jadwal_dokters
     * Fungsi: Menyimpan jadwal praktik setiap dokter per hari.
     *         Normalisasi dari string "Sen, Rab, Jum 09.00–14.00" menjadi baris per hari.
     * Dikelola oleh: Admin
     * Relasi:
     *   - dokters  (N:1)
     */
    public function up(): void
    {
        Schema::create('jadwal_dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id')->constrained('dokters')->cascadeOnDelete();
            $table->tinyInteger('hari')
                  ->comment('1=Senin, 2=Selasa, 3=Rabu, 4=Kamis, 5=Jumat, 6=Sabtu, 7=Minggu');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota')->default(20);                 // maks pasien per sesi
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            // Satu dokter hanya boleh satu jadwal per hari
            $table->unique(['dokter_id', 'hari'], 'uq_jadwal_dokter_hari');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokters');
    }
};
