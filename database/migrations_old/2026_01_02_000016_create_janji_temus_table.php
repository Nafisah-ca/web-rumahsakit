<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: janji_temus
     * Fungsi: Menyimpan data janji temu/appointment antara pasien dan dokter.
     * Dikelola oleh: Admin (kelola semua), Pasien (buat & lihat milik sendiri)
     * Relasi:
     *   - pasiens        (N:1)
     *   - dokters        (N:1)
     *   - jadwal_dokters (N:1) — slot jadwal yang diambil
     *   - layanans       (N:1) — layanan/poli yang dituju
     */
    public function up(): void
    {
        Schema::create('janji_temus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_booking', 20)->unique();          // e.g. "RS-20260714-001"
            $table->foreignId('pasien_id')->constrained('pasiens')->restrictOnDelete();
            $table->foreignId('dokter_id')->constrained('dokters')->restrictOnDelete();
            $table->foreignId('jadwal_dokter_id')->constrained('jadwal_dokters')->restrictOnDelete();
            $table->foreignId('layanan_id')->nullable()->constrained('layanans')->nullOnDelete();
            $table->date('tanggal_kunjungan');
            $table->time('jam_kunjungan');
            $table->integer('nomor_antrian')->nullable();
            $table->text('keluhan')->nullable();
            $table->text('catatan_pasien')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->enum('status', [
                'menunggu',     // baru dibuat, belum dikonfirmasi
                'dikonfirmasi', // sudah dikonfirmasi admin
                'hadir',        // pasien hadir
                'selesai',      // kunjungan selesai
                'dibatalkan',   // dibatalkan pasien/admin
                'tidak_hadir',  // no-show
            ])->default('menunggu');
            $table->string('alasan_batal')->nullable();
            $table->enum('tipe', ['offline', 'online'])->default('offline');
            $table->timestamps();

            // Index untuk query laporan harian/bulanan
            $table->index(['tanggal_kunjungan', 'status']);
            $table->index(['dokter_id', 'tanggal_kunjungan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('janji_temus');
    }
};
