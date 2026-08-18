<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('website_setting', 'estimasi_antrian')) {
                $table->text('estimasi_antrian')->nullable()->after('jadwal_sholat')
                      ->comment('JSON: {interval_refresh, pesan_tunggu}');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            $table->dropColumn('estimasi_antrian');
        });
    }
};
