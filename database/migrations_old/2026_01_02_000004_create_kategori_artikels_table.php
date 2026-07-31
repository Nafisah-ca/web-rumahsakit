<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: kategori_artikels
     * Fungsi: Kategori/klasifikasi artikel kesehatan (normalisasi 3NF).
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('kategori_artikels', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 120)->unique();
            $table->string('warna', 20)->nullable();    // untuk badge UI
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_artikels');
    }
};
