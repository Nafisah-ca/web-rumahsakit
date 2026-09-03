<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtikelSeeder extends Seeder
{
    /**
     * Seed 10 artikel asli dari database produksi.
     *
     * Pencegahan duplikasi: updateOrCreate berdasarkan slug
     * (slug bersifat unik per artikel, tidak akan berubah).
     *
     * Catatan:
     *  - created_by = 2 (user CMS aktif)
     *  - kategori_artikel_id merujuk ke ID yang dibuat KategoriArtikelSeeder
     *  - gambar merujuk path relatif di storage/app/public/
     */
    public function run(): void
    {
        // Pastikan timestamps diisi manual
        $now = now();

        $artikels = [
            [
                'slug'                => 'mengenal-diabetes-melitus-gejala-penyebab-dan-cara-mencegahnya-jEB7',
                'judul'               => 'Mengenal Diabetes Melitus: Gejala, Penyebab, dan Cara Mencegahnya',
                'kategori_artikel_id' => $this->kategoriId('Penyakit Dalam'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/4pWRrPOngLztnMW8gFmf2wKs5xqzsMVhPRVyUXcQ.png',
                'isi'                 => 'Diabetes melitus adalah penyakit yang ditandai dengan tingginya kadar gula darah. Gejala yang sering muncul antara lain mudah haus, sering buang air kecil, cepat lelah, dan luka yang sulit sembuh. Menjaga pola makan sehat, rutin berolahraga, serta melakukan pemeriksaan kesehatan secara berkala dapat membantu mencegah diabetes. Jika mengalami gejala tersebut, segera konsultasikan dengan dokter.',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-07-24 15:27:06',
                'updated_tm'          => '2026-07-24 15:27:06',
            ],
            [
                'slug'                => 'pentingnya-memantau-tumbuh-kembang-anak-sejak-dini-RIia',
                'judul'               => 'Pentingnya Memantau Tumbuh Kembang Anak Sejak Dini',
                'kategori_artikel_id' => $this->kategoriId('Anak & Tumbuh Kembang'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/3o1n83S8d4s8l3hKUY9I3ItW1nVsuRAJ9XgRE61h.png',
                'isi'                 => 'Memantau tumbuh kembang anak secara rutin membantu memastikan pertumbuhan fisik dan perkembangan sesuai usianya. Orang tua disarankan memberikan makanan bergizi, melengkapi imunisasi, serta melakukan kontrol rutin ke dokter anak. Deteksi dini dapat membantu penanganan apabila ditemukan gangguan perkembangan.',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-07-24 15:35:09',
                'updated_tm'          => '2026-07-24 15:35:09',
            ],
            [
                'slug'                => '7-kebiasaan-sehat-yang-dapat-menjaga-daya-tahan-tubuh-suts',
                'judul'               => '7 Kebiasaan Sehat yang Dapat Menjaga Daya Tahan Tubuh',
                'kategori_artikel_id' => $this->kategoriId('Tips Kesehatan'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/2X9aaIUHIk0GvDftBCH1yNSX5OVpctA7JUzf9N0A.png',
                'isi'                 => 'Menjaga daya tahan tubuh dapat dimulai dengan mengonsumsi makanan bergizi, minum air putih yang cukup, tidur yang cukup, dan rutin berolahraga. Hindari merokok serta kelola stres dengan baik agar tubuh tetap sehat dan terhindar dari berbagai penyakit.',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-07-24 15:36:52',
                'updated_tm'          => '2026-07-24 15:36:52',
            ],
            [
                'slug'                => 'pemeriksaan-kehamilan-rutin-untuk-menjaga-kesehatan-ibu-dan-janin-b56p',
                'judul'               => 'Pemeriksaan Kehamilan Rutin untuk Menjaga Kesehatan Ibu dan Janin',
                'kategori_artikel_id' => $this->kategoriId('Kebidanan & Kandungan'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/TKUUiWDNo5XIEMrjUchVESZZWfNOIzk5iTLrprMF.png',
                'isi'                 => 'Pemeriksaan kehamilan secara rutin membantu memantau kondisi ibu dan perkembangan janin. Selain pemeriksaan, ibu hamil dianjurkan mengonsumsi makanan bergizi, vitamin sesuai anjuran dokter, dan menjaga pola hidup sehat agar kehamilan tetap berjalan dengan baik.',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-07-24 15:39:30',
                'updated_tm'          => '2026-07-24 15:39:30',
            ],
            [
                'slug'                => 'mengenal-vertigo-penyebab-gejala-dan-cara-mengatasinya-gEX6',
                'judul'               => 'Mengenal Vertigo: Penyebab, Gejala, dan Cara Mengatasinya',
                'kategori_artikel_id' => $this->kategoriId('Saraf & Vertigo'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/ycSAEBrLeEOYhuKKsjMLDaC11lZI7kMI0a4g33iW.png',
                'isi'                 => 'Vertigo adalah kondisi yang menyebabkan sensasi berputar dan kehilangan keseimbangan. Keluhan ini dapat disertai mual atau muntah. Penanganan vertigo bergantung pada penyebabnya, sehingga pemeriksaan oleh dokter sangat dianjurkan untuk mendapatkan terapi yang tepat.',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-07-24 15:41:21',
                'updated_tm'          => '2026-07-24 15:41:21',
            ],
            [
                'slug'                => 'cara-menjaga-kesehatan-jantung-sejak-usia-muda-TXuk',
                'judul'               => 'Cara Menjaga Kesehatan Jantung Sejak Usia Muda',
                'kategori_artikel_id' => $this->kategoriId('Jantung'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/uWU6wNbylTw9oQ8XPOPlMx8MswYoISHMPO5wyHX6.png',
                'isi'                 => '<p>Menjaga kesehatan jantung dapat dilakukan dengan rutin berolahraga, mengonsumsi makanan sehat, menjaga berat badan ideal, serta menghindari rokok. Pemeriksaan kesehatan secara berkala juga penting untuk mendeteksi risiko penyakit jantung sejak dini.</p>',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-07-24 15:44:57',
                'updated_tm'          => '2026-07-24 15:44:57',
            ],
            [
                'slug'                => 'ngoding-adalah-hal-yang-sangat-seru-Ln2C',
                'judul'               => 'ngoding adalah hal yang sangat seru',
                'kategori_artikel_id' => $this->kategoriId('Jantung'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/qAmImnE0UJ97Hi654Ox4Pfd82MIDkBaIQqaQ12em.png',
                'isi'                 => '<p>ngodnng mantep banget euy</p>',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-09-03 13:10:27',
                'updated_tm'          => '2026-09-03 13:10:27',
            ],
            [
                'slug'                => 'ngoding-24fx',
                'judul'               => 'ngoding',
                'kategori_artikel_id' => $this->kategoriId('Penyakit Dalam'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/J5iL1pIlBJpXlhJn8j0EQ4Yh2Ublujez6yiaSCzX.png',
                'isi'                 => '<p>ngoding mnetppp</p>',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-09-03 13:11:20',
                'updated_tm'          => '2026-09-03 13:11:20',
            ],
            [
                'slug'                => 'ngoding-malem-malem-sampe-tipesjl-RZQW',
                'judul'               => 'ngoding malem malem sampe tipesjl',
                'kategori_artikel_id' => $this->kategoriId('Saraf & Neurologi'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/lh75UJICf2B6bkg8BMLJVmazh68UP4RaAtNA5Bui.png',
                'isi'                 => '<p>bhsubdjdaBJKBSUMANSQJHWUW</p>',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-09-03 13:12:34',
                'updated_tm'          => '2026-09-03 13:12:34',
            ],
            [
                'slug'                => 'kimpul-Pg07',
                'judul'               => 'kimpul',
                'kategori_artikel_id' => $this->kategoriId('Saraf & Neurologi'),
                'dokter_id'           => null,
                'gambar'              => 'artikel/AZzWaS1d7wQTTrsGyAtvoFqk2veP05r19omduPoq.png',
                'isi'                 => '<p>adasfsterdsvxc</p>',
                'status'              => 'publish',
                'created_by'          => 2,
                'updated_by'          => null,
                'created_tm'          => '2026-09-03 13:13:45',
                'updated_tm'          => '2026-09-03 13:13:45',
            ],
        ];

        foreach ($artikels as $data) {
            // Cegah duplikasi: gunakan slug sebagai kunci unik
            DB::table('artikel')->updateOrInsert(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('✅ ArtikelSeeder: 10 artikel berhasil di-seed.');
    }

    /**
     * Ambil ID kategori artikel berdasarkan nama.
     * Mencoba beberapa alias nama jika nama utama tidak ditemukan.
     * Fallback ke ID 1 jika benar-benar tidak ada.
     */
    private function kategoriId(string $nama): int
    {
        // Alias: nama di seeder lama → nama di KategoriArtikelSeeder baru
        $alias = [
            'Jantung'              => 'Kesehatan Jantung',
            'Anak & Tumbuh Kembang'=> 'Kesehatan Anak',
            'Tips Kesehatan'       => 'Tips Kesehatan Umum',
            'Saraf & Vertigo'      => 'Saraf & Neurologi',
        ];

        // Coba nama asli dulu
        $row = DB::table('kategori_artikel')
            ->where('nama_kategori', $nama)
            ->whereNull('deleted_tm')
            ->first(['id']);

        if ($row) return $row->id;

        // Coba alias
        if (isset($alias[$nama])) {
            $row = DB::table('kategori_artikel')
                ->where('nama_kategori', $alias[$nama])
                ->whereNull('deleted_tm')
                ->first(['id']);

            if ($row) return $row->id;
        }

        // Fallback ke kategori pertama yang ada
        $fallback = DB::table('kategori_artikel')
            ->whereNull('deleted_tm')
            ->orderBy('id')
            ->first(['id']);

        return $fallback ? $fallback->id : 1;
    }
}
