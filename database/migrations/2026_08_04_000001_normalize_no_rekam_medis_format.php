<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Normalisasi format no_rekam_medis lama (RM2026XXXXX atau 2026XXXXX)
     * menjadi 8 digit angka polos tanpa prefix apapun (00000001, 00000002, dst).
     *
     * Jika hasil normalisasi menghasilkan duplikat, gunakan ID pasien
     * sebagai fallback agar tetap unik.
     */
    public function up(): void
    {
        // Nonaktifkan unique check sementara agar tidak error saat update bertahap
        DB::statement('SET unique_checks = 0');

        try {
            // Ambil semua pasien, urutkan berdasarkan id agar konsisten
            $pasiens = DB::table('pasien')
                ->orderBy('id')
                ->get(['id', 'no_rekam_medis']);

            // Kumpulkan semua no_rekam_medis yang SUDAH ADA (sudah bersih)
            // agar tidak bentrok saat assign nilai baru
            $taken = DB::table('pasien')
                ->whereRaw("no_rekam_medis NOT REGEXP '[^0-9]' AND LENGTH(no_rekam_medis) = 8")
                ->pluck('no_rekam_medis', 'id')
                ->toArray();

            foreach ($pasiens as $pasien) {
                $current = $pasien->no_rekam_medis;

                // Sudah format 8 digit polos → skip
                if (preg_match('/^\d{8}$/', $current)) {
                    continue;
                }

                // Strip semua non-angka
                $digits = preg_replace('/\D/', '', $current);

                // Ambil 5 digit terakhir sebagai urutan
                $urutan = (int) substr($digits, -5);

                // Buat kandidat baru
                $candidate = str_pad($urutan, 8, '0', STR_PAD_LEFT);

                // Jika kandidat sudah dipakai pasien lain, gunakan ID sebagai fallback
                if (in_array($candidate, $taken)) {
                    $candidate = str_pad($pasien->id, 8, '0', STR_PAD_LEFT);
                }

                // Jika fallback ID pun masih duplikat, tambah suffix unik
                $finalCandidate = $candidate;
                $suffix = 1;
                while (in_array($finalCandidate, $taken)) {
                    $finalCandidate = str_pad($pasien->id * 1000 + $suffix, 8, '0', STR_PAD_LEFT);
                    $suffix++;
                }

                // Simpan ke daftar taken dan update DB
                $taken[$pasien->id] = $finalCandidate;

                DB::table('pasien')
                    ->where('id', $pasien->id)
                    ->update(['no_rekam_medis' => $finalCandidate]);
            }
        } finally {
            // Aktifkan kembali unique check
            DB::statement('SET unique_checks = 1');
        }
    }

    public function down(): void
    {
        // Tidak bisa dikembalikan otomatis karena format lama sudah overwrite.
    }
};
