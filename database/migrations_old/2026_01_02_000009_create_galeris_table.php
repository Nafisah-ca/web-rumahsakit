<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: galeris
     * Fungsi: Menyimpan foto/video galeri rumah sakit.
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('file');                                 // path gambar/video
            $table->enum('tipe', ['foto', 'video'])->default('foto');
            $table->string('thumbnail')->nullable();               // untuk video
            $table->string('kategori', 100)->nullable();           // e.g. "Ruang Operasi", "Lobi"
            $table->boolean('is_aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeris');
    }
};
