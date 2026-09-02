<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('janji_temu', function (Blueprint $table) {
            if (!Schema::hasColumn('janji_temu', 'alasan_pembatalan')) {
                $table->text('alasan_pembatalan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('janji_temu', 'tanggal_pembatalan')) {
                $table->timestamp('tanggal_pembatalan')->nullable()->after('alasan_pembatalan');
            }
            if (!Schema::hasColumn('janji_temu', 'dibatalkan_oleh')) {
                // 'pasien' atau 'admin'
                $table->string('dibatalkan_oleh', 10)->nullable()->after('tanggal_pembatalan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('janji_temu', function (Blueprint $table) {
            $table->dropColumn(['alasan_pembatalan', 'tanggal_pembatalan', 'dibatalkan_oleh']);
        });
    }
};
