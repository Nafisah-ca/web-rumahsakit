<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: kontaks
     * Fungsi: Menyimpan pesan masuk dari pengunjung/pasien melalui form Kontak.
     * Dikelola oleh: Admin (baca & balas pesan)
     * Relasi:
     *   - users (N:1, nullable) — jika pengirim sudah login sebagai pasien
     */
    public function up(): void
    {
        Schema::create('kontaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama', 100);
            $table->string('email', 150);
            $table->string('telepon', 20)->nullable();
            $table->string('subjek', 200)->nullable();
            $table->text('pesan');
            $table->enum('status', ['belum_dibaca', 'sudah_dibaca', 'dibalas'])->default('belum_dibaca');
            $table->text('balasan')->nullable();
            $table->timestamp('dibalas_pada')->nullable();
            $table->foreignId('dibalas_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontaks');
    }
};
