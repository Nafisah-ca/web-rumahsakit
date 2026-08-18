<?php

namespace Database\Seeders;

use App\Models\KategoriArtikel;
use App\Models\Spesialisasi;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * KategoriArtikelSeeder
 *
 * Membuat/update 6 kategori artikel yang masing-masing
 * terhubung ke spesialisasi dokter → agar tombol "Buat Janji Temu"
 * di artikel mengarah langsung ke dokter spesialis yang tepat.
 */
class KategoriArtikelSeeder extends Seeder
{
    public function run(): void
    {
        $cms = User::where('role', 'cms')->first()
            ?? User::where('role', 'admin')->first();
        $cmsId = $cms?->id ?? 1;

        // Mapping: nama kategori → nama spesialisasi
        $kategoris = [
            [
                'nama_kategori' => 'Kesehatan Jantung',
                'spesialis'     => 'Jantung & Pembuluh',
                'deskripsi'     => 'Artikel seputar penyakit jantung, pembuluh darah, dan cara menjaga kesehatan kardiovaskular.',
            ],
            [
                'nama_kategori' => 'Kebidanan & Kandungan',
                'spesialis'     => 'Kebidanan',
                'deskripsi'     => 'Informasi seputar kehamilan, persalinan, nifas, dan kesehatan reproduksi wanita.',
            ],
            [
                'nama_kategori' => 'Saraf & Neurologi',
                'spesialis'     => 'Syaraf',
                'deskripsi'     => 'Artikel tentang gangguan saraf, stroke, migrain, vertigo, dan penyakit neurologis lainnya.',
            ],
            [
                'nama_kategori' => 'Kesehatan Anak',
                'spesialis'     => 'Anak',
                'deskripsi'     => 'Tips tumbuh kembang anak, imunisasi, nutrisi bayi, dan penyakit umum pada anak.',
            ],
            [
                'nama_kategori' => 'Penyakit Dalam',
                'spesialis'     => 'Penyakit Dalam',
                'deskripsi'     => 'Informasi tentang diabetes, hipertensi, asam urat, kolesterol, dan penyakit dalam lainnya.',
            ],
            [
                'nama_kategori' => 'Tips Kesehatan Umum',
                'spesialis'     => null, // tidak terkait spesialisasi khusus
                'deskripsi'     => 'Tips hidup sehat, pola makan seimbang, olahraga, dan pencegahan penyakit.',
            ],
        ];

        foreach ($kategoris as $k) {
            $spesialisId = null;
            if ($k['spesialis']) {
                $sp = Spesialisasi::where('nama_spesialis', $k['spesialis'])->first();
                $spesialisId = $sp?->id;
            }

            KategoriArtikel::updateOrCreate(
                ['nama_kategori' => $k['nama_kategori']],
                [
                    'deskripsi'    => $k['deskripsi'],
                    'spesialis_id' => $spesialisId,
                    'status'       => 'aktif',
                    'created_by'   => $cmsId,
                ]
            );
        }

        $this->command->info('✅ KategoriArtikelSeeder: 6 kategori artikel dengan relasi spesialisasi berhasil disemai.');
    }
}
