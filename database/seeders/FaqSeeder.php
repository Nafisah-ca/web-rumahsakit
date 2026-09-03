<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Seed 2 FAQ asli dari database.
     *
     * Pencegahan duplikasi: updateOrInsert berdasarkan pertanyaan
     * (pertanyaan bersifat unik per FAQ).
     */
    public function run(): void
    {
        $faqs = [
            [
                'pertanyaan' => 'Apa saja layanan yang tersedia di rumah sakit?',
                'jawaban'    => 'Banyak',
                'urutan'     => 0,
                'status'     => 'aktif',
                'created_by' => 2,
                'updated_by' => null,
                'created_tm' => '2026-08-13 08:35:39',
                'updated_tm' => '2026-08-13 08:35:39',
            ],
            [
                'pertanyaan' => 'Apakah rumah sakit menerima pasien BPJS?',
                'jawaban'    => 'iya disini menerima pasien BPJS',
                'urutan'     => 1,
                'status'     => 'aktif',
                'created_by' => 2,
                'updated_by' => null,
                'created_tm' => '2026-08-13 08:41:00',
                'updated_tm' => '2026-08-13 08:41:00',
            ],
        ];

        foreach ($faqs as $data) {
            DB::table('faq')->updateOrInsert(
                ['pertanyaan' => $data['pertanyaan']],
                $data
            );
        }

        $this->command->info('✅ FaqSeeder: 2 FAQ berhasil di-seed.');
    }
}
