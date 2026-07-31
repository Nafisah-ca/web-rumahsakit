<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: artikels
     * Fungsi: Menyimpan artikel/berita/informasi kesehatan.
     * Dikelola oleh: CMS
     * Relasi: kategori_artikels (N:1), users (N:1 penulis)
     */
    public function up(): void
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_artikel_id')->constrained('kategori_artikels')->restrictOnDelete();
            $table->foreignId('user_id')->comment('penulis/author')->constrained('users')->restrictOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('konten');
            $table->string('gambar_utama')->nullable();
            $table->string('emoji', 10)->nullable();               // emoji placeholder
            $table->string('warna_dari', 20)->nullable();
            $table->string('warna_ke', 20)->nullable();
            $table->string('tags')->nullable();                    // comma-separated tags
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->integer('total_dibaca')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
