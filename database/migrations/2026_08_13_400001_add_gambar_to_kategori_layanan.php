<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kategori_layanan') && !Schema::hasColumn('kategori_layanan', 'gambar')) {
            Schema::table('kategori_layanan', function (Blueprint $table) {
                $table->string('gambar')->nullable()->after('deskripsi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('kategori_layanan', 'gambar')) {
            Schema::table('kategori_layanan', function (Blueprint $table) {
                $table->dropColumn('gambar');
            });
        }
    }
};
