<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokter', function (Blueprint $table) {
            if (!Schema::hasColumn('dokter', 'foto_banner')) {
                $table->string('foto_banner')->nullable()->after('foto')
                      ->comment('Foto background banner card dokter (opsional)');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dokter', function (Blueprint $table) {
            $table->dropColumn('foto_banner');
        });
    }
};
