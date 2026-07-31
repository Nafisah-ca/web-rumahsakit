<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: spesialisasis
     * Fungsi: Master data spesialisasi dokter (Kardiologi, Ortopedi, dll.)
     *         Dinormalisasi agar dokter dapat dihubungkan ke spesialisasi tanpa duplikasi string.
     * Dikelola oleh: Admin
     */
    public function up(): void
    {
        Schema::create('spesialisasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 120)->unique();
            $table->string('icon_fa', 50)->nullable();             // FontAwesome icon
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesialisasis');
    }
};
