<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            if (!Schema::hasColumn('artikel', 'dokter_id')) {
                $table->unsignedBigInteger('dokter_id')
                      ->nullable()
                      ->after('kategori_artikel_id')
                      ->comment('Dokter terkait artikel untuk fitur Buat Janji Temu');
                $table->foreign('dokter_id')
                      ->references('id')
                      ->on('dokter')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropForeign(['dokter_id']);
            $table->dropColumn('dokter_id');
        });
    }
};
