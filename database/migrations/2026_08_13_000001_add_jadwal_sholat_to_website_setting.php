<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('website_setting', 'jadwal_sholat')) {
                // Format JSON: {"subuh":"04:30","dzuhur":"12:00","ashar":"15:20","maghrib":"17:52","isya":"19:06"}
                $table->text('jadwal_sholat')->nullable()->after('jam_operasional');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            $table->dropColumn('jadwal_sholat');
        });
    }
};
