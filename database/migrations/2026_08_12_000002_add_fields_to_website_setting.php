<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('website_setting', 'privacy_policy')) {
                $table->longText('privacy_policy')->nullable()->after('copyright');
            }
            if (!Schema::hasColumn('website_setting', 'syarat_ketentuan')) {
                $table->longText('syarat_ketentuan')->nullable()->after('privacy_policy');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            $table->dropColumn(['privacy_policy', 'syarat_ketentuan']);
        });
    }
};
