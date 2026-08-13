<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageBannerSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama dulu
        DB::table('page_banners')->delete();

        $banners = [
            ['page_key' => 'dokter',           'gambar' => 'page-banner/zFTI3jiFtwUFdzccE4X5rJjEvtnDdcfWDm056OFl.jpg'],
            ['page_key' => 'artikel',          'gambar' => 'page-banner/BV6Y9dHyWwQJ3TImlMPotooBi8IseQQD9yumLfxa.jpg'],
            ['page_key' => 'event',            'gambar' => 'page-banner/dKL23bXLI3QXjWa4Nes6aw0wFyXphFbdmDuxhMpD.jpg'],
            ['page_key' => 'kontak',           'gambar' => 'page-banner/dWr1X3MDlzw3rlwwjg7PYKKkZEge6aYUK2KROLoq.jpg'],
            ['page_key' => 'mcu',              'gambar' => 'page-banner/dsBqlr3PXWTmf9ktIqwh9vpz88BKln3S2aiJh8sI.jpg'],
            ['page_key' => 'layanan-kategori', 'gambar' => 'page-banner/qKgazRyOrPLr1mYStfjOeYgicO2EgKfDTw1NqIfx.jpg'],
            // Belum ada gambar — bisa diisi nanti lewat CMS
            ['page_key' => 'pelayanan',        'gambar' => null],
            ['page_key' => 'tentang',          'gambar' => null],
            ['page_key' => 'promo',            'gambar' => null],
            ['page_key' => 'informasi',        'gambar' => null],
            ['page_key' => 'ulasan',           'gambar' => null],
            ['page_key' => 'fasilitas',        'gambar' => null],
            ['page_key' => 'live-antrian',     'gambar' => null],
            ['page_key' => 'kebijakan-privasi','gambar' => null],
            ['page_key' => 'syarat-ketentuan', 'gambar' => null],
        ];

        $now = now();
        foreach ($banners as $b) {
            DB::table('page_banners')->insert([
                'page_key'   => $b['page_key'],
                'gambar'     => $b['gambar'],
                'status'     => 'aktif',
                'created_tm' => $now,
                'updated_tm' => $now,
                'updated_by' => null,
            ]);
        }
    }
}
