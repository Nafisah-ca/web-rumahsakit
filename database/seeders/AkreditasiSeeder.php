<?php

namespace Database\Seeders;

use App\Models\Akreditasi;
use Illuminate\Database\Seeder;

class AkreditasiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama' => 'KARS Paripurna', 'logo' => 'images/akreditasi/kars.png',     'urutan' => 1],
            ['nama' => 'ISO 9001:2015',  'logo' => 'images/akreditasi/iso.png',      'urutan' => 2],
            ['nama' => 'SNARS Edisi 1.1','logo' => 'images/akreditasi/snars.png',    'urutan' => 3],
            ['nama' => 'BPJS Kesehatan', 'logo' => 'images/akreditasi/bpjs.png',     'urutan' => 4],
            ['nama' => 'Kemenkes RI',    'logo' => 'images/akreditasi/kemenkes.png', 'urutan' => 5],
        ];

        foreach ($data as $item) {
            Akreditasi::updateOrCreate(
                ['nama' => $item['nama']],
                array_merge($item, ['status' => 'aktif'])
            );
        }
    }
}
