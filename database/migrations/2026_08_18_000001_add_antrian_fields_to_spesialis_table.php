<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom untuk tampilan live antrian per poli
        Schema::table('spesialis', function (Blueprint $table) {
            if (!Schema::hasColumn('spesialis', 'icon')) {
                $table->string('icon', 60)->default('fa-stethoscope')->after('deskripsi')
                      ->comment('FontAwesome icon class, cth: fa-heartbeat');
            }
            if (!Schema::hasColumn('spesialis', 'warna')) {
                $table->string('warna', 30)->default('blue')->after('icon')
                      ->comment('Warna Tailwind: blue, green, red, indigo, purple, orange, pink, teal, gray');
            }
            if (!Schema::hasColumn('spesialis', 'estimasi_menit')) {
                $table->unsignedSmallInteger('estimasi_menit')->default(15)->after('warna')
                      ->comment('Estimasi waktu tunggu per pasien dalam menit');
            }
        });
    }

    public function down(): void
    {
        Schema::table('spesialis', function (Blueprint $table) {
            $table->dropColumn(['icon', 'warna', 'estimasi_menit']);
        });
    }
};
