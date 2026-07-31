<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: notifikasis
     * Fungsi: Menyimpan notifikasi in-app untuk setiap user (admin, cms, pasien).
     *         Menggunakan pola polymorphic ringan lewat kolom notifiable_type & notifiable_id
     *         sehingga notifikasi bisa merujuk ke janji_temu, kontak, dll.
     * Dikelola oleh: Admin (kirim), Pasien (terima & lihat)
     * Relasi:
     *   - users (N:1 — penerima)
     */
    public function up(): void
    {
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('penerima notifikasi')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 200);
            $table->text('pesan');
            $table->enum('tipe', [
                'janji_temu',   // konfirmasi / perubahan jadwal
                'pengumuman',   // info umum dari admin/CMS
                'promo',        // notifikasi promo baru
                'sistem',       // pesan teknis dari sistem
            ])->default('sistem');
            $table->string('notifiable_type')->nullable();          // e.g. "App\Models\JanjiTemu"
            $table->unsignedBigInteger('notifiable_id')->nullable();// FK polymorphic
            $table->string('url_aksi')->nullable();                 // link tombol aksi
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'sudah_dibaca']);
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
