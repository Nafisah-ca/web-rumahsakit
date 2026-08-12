<?php

namespace Database\Seeders;

use App\Models\KategoriLayanan;
use Illuminate\Database\Seeder;

class KategoriLayananSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'IGD 24 Jam',              'icon' => 'fa-ambulance',       'deskripsi' => 'Pelayanan darurat 24 jam.'],
            ['nama_kategori' => 'Rawat Jalan / Poliklinik', 'icon' => 'fa-stethoscope',    'deskripsi' => 'Pemeriksaan dan konsultasi dokter spesialis.'],
            ['nama_kategori' => 'Rawat Inap',               'icon' => 'fa-bed',             'deskripsi' => 'Kamar rawat inap yang nyaman.'],
            ['nama_kategori' => 'Laboratorium Klinik',      'icon' => 'fa-flask',           'deskripsi' => 'Pemeriksaan laboratorium lengkap.'],
            ['nama_kategori' => 'Radiologi',                'icon' => 'fa-x-ray',           'deskripsi' => 'Rontgen, CT Scan, dan MRI.'],
            ['nama_kategori' => 'Medical Check-Up',         'icon' => 'fa-clipboard-check', 'deskripsi' => 'Paket pemeriksaan kesehatan menyeluruh.'],
        ];

        foreach ($data as $item) {
            KategoriLayanan::firstOrCreate(
                ['nama_kategori' => $item['nama_kategori']],
                array_merge($item, ['status' => 'aktif'])
            );
        }
    }
}
