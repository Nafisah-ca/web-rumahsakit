<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategori_artikel', function (Blueprint $table) {
            if (!Schema::hasColumn('kategori_artikel', 'spesialis_id')) {
                $table->unsignedBigInteger('spesialis_id')
                      ->nullable()
                      ->after('deskripsi')
                      ->comment('Spesialisasi terkait untuk fitur Buat Janji Temu');
                $table->foreign('spesialis_id')
                      ->references('id')->on('spesialis')
                      ->onDelete('set null')->onUpdate('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kategori_artikel', function (Blueprint $table) {
            $table->dropForeign(['spesialis_id']);
            $table->dropColumn('spesialis_id');
        });
    }
};
