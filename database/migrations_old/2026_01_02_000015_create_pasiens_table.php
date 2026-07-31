<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: pasiens
     * Fungsi: Menyimpan data medis/demografis pasien yang terpisah dari tabel users.
     *         Normalisasi: users berisi kredensial login, pasiens berisi data medis.
     *         Satu user (role=user) memiliki satu profil pasien.
     * Dikelola oleh: Admin (CRUD), Pasien (lihat & update profil sendiri)
     * Relasi:
     *   - users (1:1)
     */
    public function up(): void
    {
        Schema::create('pasiens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('no_rm', 20)->unique()->nullable();     // Nomor Rekam Medis
            $table->string('nik', 16)->unique()->nullable();       // NIK KTP
            $table->string('nama_lengkap');
            $table->string('nama_panggilan', 50)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('telepon_darurat', 20)->nullable();
            $table->string('nama_kontak_darurat', 100)->nullable();
            $table->string('hubungan_kontak_darurat', 50)->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->string('agama', 50)->nullable();
            $table->enum('status_pernikahan', ['belum_menikah', 'menikah', 'cerai'])->nullable();
            $table->string('no_bpjs', 30)->nullable();
            $table->string('no_asuransi', 50)->nullable();
            $table->string('nama_asuransi', 100)->nullable();
            $table->text('riwayat_alergi')->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasiens');
    }
};
