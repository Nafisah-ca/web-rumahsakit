<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('website_setting', 'jumlah_spesialisasi')) {
                $table->unsignedSmallInteger('jumlah_spesialisasi')->default(5)->after('jadwal_sholat');
            }
            if (!Schema::hasColumn('website_setting', 'jumlah_mitra_asuransi')) {
                $table->unsignedSmallInteger('jumlah_mitra_asuransi')->default(50)->after('jumlah_spesialisasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            $table->dropColumn(['jumlah_spesialisasi', 'jumlah_mitra_asuransi']);
        });
    }
};
