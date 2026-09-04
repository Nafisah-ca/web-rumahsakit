<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('layanan') && !Schema::hasColumn('layanan', 'dokter_id')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->unsignedBigInteger('dokter_id')->nullable()->after('kategori_layanan_id');
                $table->foreign('dokter_id')->references('id')->on('dokter')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('layanan') && Schema::hasColumn('layanan', 'dokter_id')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->dropForeign(['dokter_id']);
                $table->dropColumn('dokter_id');
            });
        }
    }
};
