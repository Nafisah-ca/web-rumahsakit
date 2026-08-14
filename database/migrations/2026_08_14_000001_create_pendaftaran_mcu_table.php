<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran_mcu', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pendaftaran', 30)->unique();
            $table->string('paket', 20); // basic, standard, executive, corporate
            $table->string('nama_lengkap', 150);
            $table->string('nik', 20)->nullable();
            $table->string('no_hp', 20);
            $table->string('email', 150)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->text('alamat')->nullable();
            $table->date('tanggal_pilihan'); // tanggal yang diinginkan
            $table->string('sesi', 10)->default('pagi');  // pagi / siang
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['paket', 'status']);
            $table->index('tanggal_pilihan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_mcu');
    }
};
