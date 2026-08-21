<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('akreditasi', function (Blueprint $table) {
            if (!Schema::hasColumn('akreditasi', 'tahun')) {
                $table->string('tahun', 4)->nullable()->after('nama')
                      ->comment('Tahun perolehan, mis. 2024');
            }
            if (!Schema::hasColumn('akreditasi', 'deskripsi')) {
                $table->string('deskripsi', 200)->nullable()->after('tahun')
                      ->comment('Deskripsi singkat penghargaan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('akreditasi', function (Blueprint $table) {
            $table->dropColumn(['tahun', 'deskripsi']);
        });
    }
};
