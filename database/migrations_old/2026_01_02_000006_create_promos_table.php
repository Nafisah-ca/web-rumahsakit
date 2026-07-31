<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: promos
     * Fungsi: Menyimpan data promo/penawaran dari rumah sakit.
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('ringkasan')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('icon_fa', 50)->nullable();
            $table->string('warna_dari', 20)->nullable();
            $table->string('warna_ke', 20)->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_berakhir')->nullable();
            $table->decimal('harga_asli', 12, 2)->nullable();
            $table->decimal('harga_promo', 12, 2)->nullable();
            $table->enum('status', ['draft', 'published', 'expired'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
