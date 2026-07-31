<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel: informasi_kontaks
     * Fungsi: Menyimpan informasi kontak resmi rumah sakit yang ditampilkan di halaman Kontak.
     *         Berbeda dari tabel kontaks (yang berisi pesan masuk dari pengunjung).
     * Dikelola oleh: CMS
     */
    public function up(): void
    {
        Schema::create('informasi_kontaks', function (Blueprint $table) {
            $table->id();
            $table->string('label', 100);                          // e.g. "Telepon Utama", "IGD"
            $table->string('nilai');                               // nomor / email / alamat
            $table->enum('tipe', ['telepon', 'email', 'alamat', 'whatsapp', 'fax', 'lainnya'])->default('telepon');
            $table->string('icon_fa', 50)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informasi_kontaks');
    }
};
