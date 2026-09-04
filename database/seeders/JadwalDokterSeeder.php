<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Database\Seeder;

class JadwalDokterSeeder extends Seeder
{
    /**
     * Seed jadwal mingguan berulang berdasarkan data aktual.
     * Dokter dicari berdasarkan nama_dokter (bukan ID) agar aman
     * di environment berbeda yang ID-nya bisa berbeda.
     *
     * Pencegahan duplikat: skip jika dokter + hari sudah ada.
     */
    public function run(): void
    {
        // Format: ['nama_dokter', 'hari', 'jam_mulai', 'jam_selesai', kuota]
        $jadwals = [
            // dr. Bambang Wiranto Sp.S
            ['dr. Bambang Wiranto Sp.S', 'Senin',  '09:00', '14:00', 20],
            ['dr. Bambang Wiranto Sp.S', 'Rabu',   '09:00', '14:00', 20],
            ['dr. Bambang Wiranto Sp.S', 'Jumat',  '09:00', '13:00', 15],

            // dr. Linda Susanti Sp.A
            ['dr. Linda Susanti Sp.A',  'Senin',  '08:00', '13:00', 20],
            ['dr. Linda Susanti Sp.A',  'Kamis',  '08:00', '13:00', 20],
            ['dr. Linda Susanti Sp.A',  'Sabtu',  '08:00', '12:00', 15],

            // dr. Dewi Kartika Sp.PD
            ['dr. Dewi Kartika Sp.PD',  'Selasa', '08:00', '12:00', 15],
            ['dr. Dewi Kartika Sp.PD',  'Rabu',   '08:00', '13:00', 20],
            ['dr. Dewi Kartika Sp.PD',  'Jumat',  '08:00', '13:00', 20],

            // dr. Reza Pratama Sp.JP
            ['dr. Reza Pratama Sp.JP',  'Senin',  '09:00', '14:00', 20],
            ['dr. Reza Pratama Sp.JP',  'Kamis',  '09:00', '14:00', 20],
            ['dr. Reza Pratama Sp.JP',  'Jumat',  '09:00', '13:00', 15],

            // dr. Anisa Permata Sp.A
            ['dr. Anisa Permata Sp.A',  'Selasa', '08:00', '13:00', 20],
            ['dr. Anisa Permata Sp.A',  'Kamis',  '08:00', '13:00', 20],
            ['dr. Anisa Permata Sp.A',  'Sabtu',  '08:00', '12:00', 15],

            // dr. Hendra Wijaya Sp.PD
            ['dr. Hendra Wijaya Sp.PD', 'Senin',  '08:00', '13:00', 15],
            ['dr. Hendra Wijaya Sp.PD', 'Kamis',  '08:00', '13:00', 15],
            ['dr. Hendra Wijaya Sp.PD', 'Sabtu',  '08:00', '12:00', 10],

            // dr. Farhan Maulana
            ['dr. Farhan Maulana',      'Rabu',   '08:00', '13:00', 20],
            ['dr. Farhan Maulana',      'Jumat',  '08:00', '12:00', 15],
            ['dr. Farhan Maulana',      'Sabtu',  '08:00', '13:00', 20],

            // dr. Nurul Hidayati
            ['dr. Nurul Hidayati',      'Senin',  '08:00', '13:00', 20],
            ['dr. Nurul Hidayati',      'Rabu',   '08:00', '13:00', 20],
            ['dr. Nurul Hidayati',      'Jumat',  '08:00', '12:00', 15],

            // dr. Bagas Setiawan
            ['dr. Bagas Setiawan',      'Selasa', '08:00', '13:00', 20],
            ['dr. Bagas Setiawan',      'Jumat',  '08:00', '12:00', 15],
            ['dr. Bagas Setiawan',      'Sabtu',  '08:00', '13:00', 20],

            // dr. Maya Indah Sari
            ['dr. Maya Indah Sari',     'Selasa', '08:00', '13:00', 20],
            ['dr. Maya Indah Sari',     'Sabtu',  '08:00', '13:00', 20],
            ['dr. Maya Indah Sari',     'Minggu', '08:00', '13:00', 15],

            // dr. Rizky Ananda
            ['dr. Rizky Ananda',        'Senin',  '08:00', '13:00', 20],
            ['dr. Rizky Ananda',        'Rabu',   '08:00', '13:00', 20],
            ['dr. Rizky Ananda',        'Minggu', '08:00', '12:00', 15],

            // dr. Siti Rahayu Sp.OG
            ['dr. Siti Rahayu Sp.OG',   'Selasa', '09:00', '14:00', 20],
            ['dr. Siti Rahayu Sp.OG',   'Rabu',   '09:00', '14:00', 20],
            ['dr. Siti Rahayu Sp.OG',   'Jumat',  '09:00', '13:00', 15],

            // dr. Putri Ayu Lestari
            ['dr. Putri Ayu Lestari',   'Selasa', '08:00', '13:00', 20],
            ['dr. Putri Ayu Lestari',   'Rabu',   '08:00', '13:00', 20],
            ['dr. Putri Ayu Lestari',   'Jumat',  '08:00', '12:00', 15],
        ];

        // Cache dokter by nama agar tidak query N kali
        $dokterMap = Dokter::where('status', 'aktif')
            ->get()
            ->keyBy('nama_dokter');

        $inserted   = 0;
        $skipped    = 0;
        $notFound   = [];

        foreach ($jadwals as [$namaDokter, $hari, $jamMulai, $jamSelesai, $kuota]) {
            $dokter = $dokterMap[$namaDokter] ?? null;

            if (!$dokter) {
                $notFound[] = $namaDokter;
                continue;
            }

            // Cegah duplikat
            $exists = JadwalDokter::where('dokter_id', $dokter->id)
                ->where('hari', $hari)
                ->whereNull('tanggal_praktek')
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            JadwalDokter::create([
                'dokter_id'       => $dokter->id,
                'spesialis_id'    => $dokter->spesialis_id,
                'tanggal_praktek' => null,
                'hari'            => $hari,
                'jam_mulai'       => $jamMulai,
                'jam_selesai'     => $jamSelesai,
                'kuota'           => $kuota,
                'status'          => 'aktif',
            ]);

            $inserted++;
        }

        $this->command->info("✅ JadwalDokterSeeder: {$inserted} jadwal ditambahkan, {$skipped} sudah ada (dilewati).");

        if (!empty($notFound)) {
            $unique = array_unique($notFound);
            $this->command->warn('⚠️  Dokter tidak ditemukan di database: ' . implode(', ', $unique));
        }
    }
}
