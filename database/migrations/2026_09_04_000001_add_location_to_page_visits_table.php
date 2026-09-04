<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('referer');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('city', 150)->nullable()->after('longitude');
            $table->string('region', 150)->nullable()->after('city');
            $table->string('country', 100)->nullable()->after('region');
        });
    }

    public function down(): void
    {
        Schema::table('page_visits', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'city', 'region', 'country']);
        });
    }
};
