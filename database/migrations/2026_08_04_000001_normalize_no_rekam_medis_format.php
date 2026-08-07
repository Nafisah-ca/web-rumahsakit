<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalisasi format no_rekam_medis lama (RM2026XXXXX atau 2026XXXXX)
     * menjadi 8 digit angka polos tanpa prefix apapun (00000001, 00000002, dst).
     *
     * Logika: strip semua karakter non-angka, ambil 5 digit terakhir
     * (urutan aslinya), lalu pad jadi 8 digit.
     *
     * Contoh:
     *   RM202600001 → strip → 202600001 → 5 digit terakhir → 00001 → pad → 00000001
     *   202600003   → strip → 202600003 → 5 digit terakhir → 00003 → pad → 00000003
     *   00000005    → sudah bersih, tidak berubah
     */
    public function up(): void
    {
        // Ambil semua pasien yang no_rekam_medisnya belum format 8 digit polos
        // (yaitu yang panjangnya > 8 atau mengandung huruf)
        $pasiens = DB::table('pasien')
            ->whereRaw("no_rekam_medis REGEXP '[^0-9]' OR LENGTH(no_rekam_medis) > 8")
            ->get(['id', 'no_rekam_medis']);

        foreach ($pasiens as $pasien) {
            // Strip semua non-angka
            $digits = preg_replace('/\D/', '', $pasien->no_rekam_medis);

            // Ambil 5 digit terakhir (urutan asli dari format lama RMYYYYnnnnn)
            $urutan = (int) substr($digits, -5);

            // Format ulang jadi 8 digit polos
            $newNo = str_pad($urutan, 8, '0', STR_PAD_LEFT);

            DB::table('pasien')
                ->where('id', $pasien->id)
                ->update(['no_rekam_medis' => $newNo]);
        }
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan otomatis karena format lama sudah overwrite.
        // Jika perlu rollback, restore dari backup database.
    }
};
