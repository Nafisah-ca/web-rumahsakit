<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_hero', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 50)->unique(); // 'dokter','layanan','promo', dst
            $table->string('label', 100)->nullable();          // teks kecil atas (misal "Tim Medis Profesional")
            $table->string('judul', 200);                      // judul besar
            $table->text('deskripsi')->nullable();             // teks deskripsi
            $table->string('gambar')->nullable();              // background image (opsional)
            $table->string('warna_dari', 20)->default('#00521f'); // gradient from
            $table->string('warna_ke', 20)->default('#00b04f');   // gradient to
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('created_tm')->useCurrent();
            $table->timestamp('updated_tm')->useCurrent()->useCurrentOnUpdate();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_hero');
    }
};
