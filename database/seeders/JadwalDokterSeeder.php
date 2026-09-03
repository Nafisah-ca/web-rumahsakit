<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Database\Seeder;

class JadwalDokterSeeder extends Seeder
{
    /**
     * Jadwal per dokter dibuat berdasarkan spesialisasi.
     * Semua jadwal bersifat RECURRING (tanggal_praktek = null)
     * sehingga tidak perlu diupdate setiap hari.
     * Kuota berlaku per tanggal booking.
     */
    public function run(): void
    {
        // Pola jadwal berdasarkan spesialisasi
        // Format: ['Hari', 'jam_mulai', 'jam_selesai', kuota]
        $polaBySp = [
            'Penyakit Dalam' => [
                ['Senin',  '08:00', '13:00', 20],
                ['Rabu',   '08:00', '13:00', 20],
                ['Jumat',  '08:00', '12:00', 15],
            ],
            'Anak' => [
                ['Selasa', '08:00', '13:00', 20],
                ['Kamis',  '08:00', '13:00', 20],
                ['Sabtu',  '08:00', '12:00', 15],
            ],
            'Kandungan' => [
                ['Senin',  '09:00', '14:00', 15],
                ['Rabu',   '09:00', '14:00', 15],
                ['Jumat',  '09:00', '13:00', 12],
            ],
            'Bedah' => [
                ['Selasa', '07:30', '12:00', 10],
                ['Kamis',  '07:30', '12:00', 10],
                ['Sabtu',  '07:30', '11:00', 8],
            ],
            'Jantung' => [
                ['Senin',  '08:00', '13:00', 15],
                ['Kamis',  '08:00', '13:00', 15],
                ['Sabtu',  '08:00', '12:00', 10],
            ],
            'Saraf' => [
                ['Selasa', '08:00', '13:00', 15],
                ['Jumat',  '08:00', '13:00', 15],
            ],
            'Mata' => [
                ['Senin',  '08:00', '12:00', 20],
                ['Rabu',   '08:00', '12:00', 20],
                ['Jumat',  '08:00', '11:00', 15],
            ],
            'THT' => [
                ['Selasa', '09:00', '13:00', 15],
                ['Kamis',  '09:00', '13:00', 15],
                ['Sabtu',  '09:00', '12:00', 10],
            ],
            'Kulit' => [
                ['Senin',  '10:00', '14:00', 20],
                ['Kamis',  '10:00', '14:00', 20],
            ],
            'Gigi' => [
                ['Senin',  '08:00', '14:00', 15],
                ['Selasa', '08:00', '14:00', 15],
                ['Rabu',   '08:00', '14:00', 15],
                ['Kamis',  '08:00', '14:00', 15],
                ['Jumat',  '08:00', '13:00', 12],
                ['Sabtu',  '08:00', '12:00', 10],
            ],
            'Paru' => [
                ['Selasa', '08:00', '13:00', 15],
                ['Jumat',  '08:00', '13:00', 15],
            ],
            'Ortopedi' => [
                ['Senin',  '07:30', '12:00', 12],
                ['Rabu',   '07:30', '12:00', 12],
                ['Sabtu',  '07:30', '11:00', 8],
            ],
            'Urologi' => [
                ['Selasa', '08:00', '13:00', 12],
                ['Kamis',  '08:00', '13:00', 12],
            ],
            'Umum' => [
                ['Senin',  '07:00', '14:00', 30],
                ['Selasa', '07:00', '14:00', 30],
                ['Rabu',   '07:00', '14:00', 30],
                ['Kamis',  '07:00', '14:00', 30],
                ['Jumat',  '07:00', '13:00', 25],
                ['Sabtu',  '07:00', '12:00', 20],
            ],
        ];

        // Jadwal default jika spesialisasi tidak ada di peta di atas
        $defaultPola = [
            ['Senin',  '09:00', '14:00', 20],
            ['Rabu',   '09:00', '14:00', 20],
            ['Jumat',  '09:00', '13:00', 15],
        ];

        $dokters = Dokter::with('spesialisasi')->where('status', 'aktif')->get();

        if ($dokters->isEmpty()) {
            $this->command->warn('Tidak ada dokter aktif ditemukan. Seeder dilewati.');
            return;
        }

        $total = 0;
        $skip  = 0;

        foreach ($dokters as $dokter) {
            $spNama = $dokter->spesialisasi?->nama_spesialis ?? '';
            $spId   = $dokter->spesialisasi?->id ?? $dokter->spesialis_id;

            // Cari pola berdasarkan nama spesialisasi (case-insensitive partial match)
            $pola = $defaultPola;
            foreach ($polaBySp as $key => $jadwalPola) {
                if (stripos($spNama, $key) !== false) {
                    $pola = $jadwalPola;
                    break;
                }
            }

            foreach ($pola as [$hari, $jamMulai, $jamSelesai, $kuota]) {
                // Cek apakah jadwal recurring hari ini sudah ada untuk dokter
                $exists = JadwalDokter::where('dokter_id', $dokter->id)
                    ->where('hari', $hari)
                    ->whereNull('tanggal_praktek')
                    ->exists();

                if ($exists) {
                    $skip++;
                    continue;
                }

                JadwalDokter::create([
                    'dokter_id'       => $dokter->id,
                    'spesialis_id'    => $spId,
                    'tanggal_praktek' => null,  // recurring mingguan
                    'hari'            => $hari,
                    'jam_mulai'       => $jamMulai,
                    'jam_selesai'     => $jamSelesai,
                    'kuota'           => $kuota,
                    'status'          => 'aktif',
                ]);

                $total++;
            }
        }

        $this->command->info("✅ Jadwal selesai: {$total} jadwal ditambahkan, {$skip} sudah ada (dilewati).");
        $this->command->info("   Total dokter diproses: {$dokters->count()}");
    }
}
