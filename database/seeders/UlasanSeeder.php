<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UlasanSeeder extends Seeder
{
    /**
     * Seed 8 ulasan asli dari database.
     *
     * Pencegahan duplikasi: updateOrInsert berdasarkan kombinasi
     * email + judul (identifikasi unik yang cukup untuk ulasan ini).
     */
    public function run(): void
    {
        $ulasans = [
            [
                'nama'       => 'nnaca',
                'email'      => 'nnaca@gmail.com',
                'rating'     => 5,
                'judul'      => 'Pelajayan',
                'isi'        => 'masi sulit di akses',
                'status'     => 'approved',
                'created_by' => null,
                'updated_by' => 2,
                'created_tm' => '2026-08-13 03:30:47',
                'updated_tm' => '2026-08-13 03:33:16',
            ],
            [
                'nama'       => 'dapa',
                'email'      => 'dapa@gmail.com',
                'rating'     => 5,
                'judul'      => 'Keren',
                'isi'        => 'Website nya keren dan bisa memudahkan pasien untuk daftar antrian online...',
                'status'     => 'approved',
                'created_by' => null,
                'updated_by' => 2,
                'created_tm' => '2026-08-13 03:37:06',
                'updated_tm' => '2026-08-13 03:37:34',
            ],
            [
                'nama'       => 'nakita',
                'email'      => 'nakita@gmail.com',
                'rating'     => 4,
                'judul'      => 'Bagus',
                'isi'        => 'Warna website terlalu terang, update agar bisa  mode malam',
                'status'     => 'approved',
                'created_by' => null,
                'updated_by' => 2,
                'created_tm' => '2026-08-13 03:38:25',
                'updated_tm' => '2026-08-13 03:42:00',
            ],
            [
                'nama'       => 'amma',
                'email'      => 'amma@gmail.com',
                'rating'     => 3,
                'judul'      => 'eror',
                'isi'        => 'akses login eror saya tidak bisa login',
                'status'     => 'approved',
                'created_by' => null,
                'updated_by' => 2,
                'created_tm' => '2026-08-13 03:39:22',
                'updated_tm' => '2026-08-13 03:42:07',
            ],
            [
                'nama'       => 'mine',
                'email'      => 'mine@gmail.com',
                'rating'     => 2,
                'judul'      => 'Erorr',
                'isi'        => 'saya tidak bisa buat janji temu',
                'status'     => 'approved',
                'created_by' => null,
                'updated_by' => 2,
                'created_tm' => '2026-08-13 03:39:58',
                'updated_tm' => '2026-08-13 03:42:11',
            ],
            [
                'nama'       => 'afdal',
                'email'      => 'afdal@gmail.com',
                'rating'     => 1,
                'judul'      => 'kurang menarik',
                'isi'        => 'tema masi keterangan',
                'status'     => 'approved',
                'created_by' => null,
                'updated_by' => 2,
                'created_tm' => '2026-08-13 03:41:44',
                'updated_tm' => '2026-08-13 03:42:14',
            ],
            [
                'nama'       => 'jasmin',
                'email'      => 'jasmin@gmail.com',
                'rating'     => 5,
                'judul'      => 'keren benget',
                'isi'        => 'sudah bagus memudahkan pasien, tingkatkan lagi ya',
                'status'     => 'pending',
                'created_by' => null,
                'updated_by' => null,
                'created_tm' => '2026-08-13 03:49:01',
                'updated_tm' => '2026-08-13 03:49:01',
            ],
            [
                'nama'       => 'iah sopiah',
                'email'      => 'sopiah@gmail.com',
                'rating'     => 5,
                'judul'      => 'bagus',
                'isi'        => 'cantik sekali website nya hijau, adiwiyata',
                'status'     => 'approved',
                'created_by' => null,
                'updated_by' => 2,
                'created_tm' => '2026-08-19 13:35:45',
                'updated_tm' => '2026-08-19 13:36:22',
            ],
        ];

        foreach ($ulasans as $data) {
            // Cegah duplikasi berdasarkan kombinasi email + judul
            DB::table('ulasan')->updateOrInsert(
                ['email' => $data['email'], 'judul' => $data['judul']],
                $data
            );
        }

        $this->command->info('✅ UlasanSeeder: 8 ulasan berhasil di-seed.');
    }
}
