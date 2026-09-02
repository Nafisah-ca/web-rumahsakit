<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            if (!Schema::hasColumn('website_setting', 'nama_direktur')) {
                $table->string('nama_direktur', 150)->nullable()->after('sambutan_direktur')
                      ->comment('Nama lengkap direktur beserta gelar');
            }
            if (!Schema::hasColumn('website_setting', 'foto_direktur')) {
                $table->string('foto_direktur')->nullable()->after('nama_direktur')
                      ->comment('Path foto direktur di storage');
            }
        });
    }

    public function down(): void
    {
        Schema::table('website_setting', function (Blueprint $table) {
            $table->dropColumn(['nama_direktur', 'foto_direktur']);
        });
    }
};
