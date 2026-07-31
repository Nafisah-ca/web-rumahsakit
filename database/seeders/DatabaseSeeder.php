<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WebsiteSetting;
use App\Models\Banner;
use App\Models\KategoriArtikel;
use App\Models\Layanan;
use App\Models\Spesialisasi;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\TipePenjamin;
use App\Models\Penjamin;
use App\Models\Promo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. USERS
        // ─────────────────────────────────────────────
        $admin = User::updateOrCreate(['email' => 'admin@sarisehat.test'], [
            'username'  => 'admin_rs',
            'nama'      => 'Admin RS Sari Sehat',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'status'    => 'aktif',
        ]);

        $cms = User::updateOrCreate(['email' => 'cms@sarisehat.test'], [
            'username'  => 'cms_rs',
            'nama'      => 'CMS RS Sari Sehat',
            'password'  => Hash::make('password'),
            'role'      => 'cms',
            'status'    => 'aktif',
        ]);

        User::updateOrCreate(['email' => 'pasien@sarisehat.test'], [
            'username'  => 'pasien_rs',
            'nama'      => 'Pasien Demo',
            'password'  => Hash::make('password'),
            'role'      => 'pasien',
            'status'    => 'aktif',
            'no_hp'     => '081234567890',
        ]);

        // ─────────────────────────────────────────────
        // 2. WEBSITE SETTING
        // ─────────────────────────────────────────────
        WebsiteSetting::updateOrCreate(
            ['nama_rumahsakit' => 'RS Sari Sehat'],
            [
                'tentang_kami' => 'Rumah Sakit Sari Sehat berada di Depok dengan motto "Melayani dengan Kasih Sayang".',
                'visi'         => 'Menjadi rumah sakit pilihan utama masyarakat dengan pelayanan berstandar internasional.',
                'misi'         => 'Memberikan pelayanan kesehatan yang berkualitas, profesional, dan terjangkau.',
                'email'        => 'info@sarisehat.id',
                'telepon'      => '(021) 5579-4100',
                'alamat'       => 'Jl. MH Thamrin No. 3, Depok, Jawa Barat',
                'instagram'    => 'https://instagram.com/rssarisehat',
                'facebook'     => 'https://facebook.com/rssarisehat',
                'copyright'    => '© 2025 RS Sari Sehat. All rights reserved.',
                'created_by'   => $admin->id,
            ]
        );

        // ─────────────────────────────────────────────
        // 3. BANNER
        // ─────────────────────────────────────────────
        $bannersData = [
            ['judul' => 'Kesehatan Ibu & Anak Prioritas Kami',    'deskripsi' => 'Layanan terbaik untuk ibu dan buah hati Anda.'],
            ['judul' => 'Solusi Nyeri Kronis Tanpa Operasi',       'deskripsi' => 'Ditangani oleh dokter spesialis berpengalaman.'],
            ['judul' => 'Deteksi Dini Hidup Lebih Sehat',          'deskripsi' => 'Medical Check-Up lengkap dengan harga terjangkau.'],
            ['judul' => 'Pilihan Utama Layanan Kesehatan Lengkap', 'deskripsi' => 'RS Sari Sehat hadir untuk kesehatan Anda.'],
        ];
        foreach ($bannersData as $b) {
            Banner::updateOrCreate(['judul' => $b['judul']], array_merge($b, [
                'gambar'     => 'banner/default.jpg',
                'status'     => 'aktif',
                'created_by' => $admin->id,
            ]));
        }

        // ─────────────────────────────────────────────
        // 4. KATEGORI ARTIKEL
        // ─────────────────────────────────────────────
        $kategoriData = [
            'Penyakit Dalam', 'Anak & Tumbuh Kembang', 'Tips Kesehatan',
            'Kebidanan & Kandungan', 'Saraf & Vertigo', 'Jantung', 'Ortopedi', 'Umum',
        ];
        foreach ($kategoriData as $nama) {
            KategoriArtikel::updateOrCreate(['nama_kategori' => $nama], [
                'status'     => 'aktif',
                'created_by' => $cms->id,
            ]);
        }

        // ─────────────────────────────────────────────
        // 5. LAYANAN
        // ─────────────────────────────────────────────
        $layananData = [
            ['nama_layanan' => 'IGD 24 Jam',               'icon' => 'fa-ambulance',       'deskripsi' => 'Pelayanan darurat 24 jam.'],
            ['nama_layanan' => 'Rawat Jalan / Poliklinik', 'icon' => 'fa-stethoscope',     'deskripsi' => 'Pemeriksaan dan konsultasi dokter spesialis.'],
            ['nama_layanan' => 'Rawat Inap',               'icon' => 'fa-bed',             'deskripsi' => 'Kamar rawat inap yang nyaman.'],
            ['nama_layanan' => 'Laboratorium Klinik',      'icon' => 'fa-flask',           'deskripsi' => 'Pemeriksaan laboratorium lengkap.'],
            ['nama_layanan' => 'Radiologi',                'icon' => 'fa-x-ray',           'deskripsi' => 'Rontgen, CT Scan, dan MRI.'],
            ['nama_layanan' => 'Medical Check-Up',         'icon' => 'fa-clipboard-check', 'deskripsi' => 'Paket pemeriksaan kesehatan menyeluruh.'],
        ];
        foreach ($layananData as $l) {
            Layanan::updateOrCreate(['nama_layanan' => $l['nama_layanan']], array_merge($l, [
                'status'     => 'aktif',
                'created_by' => $admin->id,
            ]));
        }

        // ─────────────────────────────────────────────
        // 6. TIPE PENJAMIN & PENJAMIN
        // ─────────────────────────────────────────────
        $tipeUmum   = TipePenjamin::updateOrCreate(['nama_tipe' => 'Umum'],             ['status' => 'aktif', 'created_by' => $admin->id]);
        $tipeBpjs   = TipePenjamin::updateOrCreate(['nama_tipe' => 'BPJS Kesehatan'],   ['status' => 'aktif', 'created_by' => $admin->id]);
        $tipeAsuransi = TipePenjamin::updateOrCreate(['nama_tipe' => 'Asuransi Swasta'],['status' => 'aktif', 'created_by' => $admin->id]);

        Penjamin::updateOrCreate(['nama_penjamin' => 'Umum / Mandiri'], [
            'tipe_penjamin_id' => $tipeUmum->id,
            'status'           => 'aktif',
            'created_by'       => $admin->id,
        ]);
        Penjamin::updateOrCreate(['nama_penjamin' => 'BPJS Kesehatan'], [
            'tipe_penjamin_id' => $tipeBpjs->id,
            'status'           => 'aktif',
            'created_by'       => $admin->id,
        ]);
        Penjamin::updateOrCreate(['nama_penjamin' => 'Prudential'], [
            'tipe_penjamin_id' => $tipeAsuransi->id,
            'status'           => 'aktif',
            'created_by'       => $admin->id,
        ]);
        Penjamin::updateOrCreate(['nama_penjamin' => 'Allianz'], [
            'tipe_penjamin_id' => $tipeAsuransi->id,
            'status'           => 'aktif',
            'created_by'       => $admin->id,
        ]);

        // ─────────────────────────────────────────────
        // 7. SPESIALISASI
        // ─────────────────────────────────────────────
        $spesialisasiData = [
            ['nama_spesialis' => 'Penyakit Dalam',    'deskripsi' => 'Menangani penyakit internal organ tubuh.'],
            ['nama_spesialis' => 'Anak',              'deskripsi' => 'Kesehatan bayi, anak, dan remaja.'],
            ['nama_spesialis' => 'Jantung & Pembuluh','deskripsi' => 'Penyakit jantung dan pembuluh darah.'],
            ['nama_spesialis' => 'Kebidanan',         'deskripsi' => 'Kehamilan, persalinan, dan kandungan.'],
            ['nama_spesialis' => 'Syaraf',            'deskripsi' => 'Gangguan sistem saraf.'],
        ];
        foreach ($spesialisasiData as $s) {
            Spesialisasi::updateOrCreate(
                ['nama_spesialis' => $s['nama_spesialis']],
                array_merge($s, ['created_by' => $admin->id])
            );
        }

        // ─────────────────────────────────────────────
        // 8. DOKTER & JADWAL
        // ─────────────────────────────────────────────
        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $tanggalAwal = now()->startOfWeek()->toDateString();

        $doktersData = [
            ['nama_dokter' => 'dr. Ahmad Fauzi Sp.JP',    'spesialis' => 'Jantung & Pembuluh', 'sip' => 'SIP-001-2025', 'email' => 'ahmad.fauzi@sarisehat.id',  'no_hp' => '081100000001'],
            ['nama_dokter' => 'dr. Siti Rahayu Sp.OG',    'spesialis' => 'Kebidanan',           'sip' => 'SIP-002-2025', 'email' => 'siti.rahayu@sarisehat.id',  'no_hp' => '081100000002'],
            ['nama_dokter' => 'dr. Bambang Wiranto Sp.BS', 'spesialis' => 'Syaraf',             'sip' => 'SIP-003-2025', 'email' => 'bambang.w@sarisehat.id',    'no_hp' => '081100000003'],
            ['nama_dokter' => 'dr. Linda Susanti Sp.A',   'spesialis' => 'Anak',                'sip' => 'SIP-004-2025', 'email' => 'linda.susanti@sarisehat.id','no_hp' => '081100000004'],
            ['nama_dokter' => 'dr. Dewi Kartika Sp.PD',   'spesialis' => 'Penyakit Dalam',      'sip' => 'SIP-005-2025', 'email' => 'dewi.kartika@sarisehat.id', 'no_hp' => '081100000005'],
        ];

        foreach ($doktersData as $idx => $d) {
            $sp = Spesialisasi::where('nama_spesialis', $d['spesialis'])->first();
            if (!$sp) continue;

            $dokter = Dokter::updateOrCreate(['sip' => $d['sip']], [
                'spesialis_id' => $sp->id,
                'nama_dokter'  => $d['nama_dokter'],
                'email'        => $d['email'],
                'no_hp'        => $d['no_hp'],
                'status'       => 'aktif',
                'created_by'   => $admin->id,
            ]);

            // 3 hari jadwal per dokter
            $hariDokter = array_slice($hariOptions, ($idx * 2) % 6, 3);
            foreach ($hariDokter as $hari) {
                JadwalDokter::updateOrCreate(
                    ['dokter_id' => $dokter->id, 'hari' => $hari],
                    [
                        'spesialis_id'    => $sp->id,
                        'tanggal_praktek' => $tanggalAwal,
                        'jam_mulai'       => '09:00:00',
                        'jam_selesai'     => '14:00:00',
                        'kuota'           => 20,
                        'status'          => 'aktif',
                        'created_by'      => $admin->id,
                    ]
                );
            }
        }

        // ─────────────────────────────────────────────
        // 9. PROMO
        // ─────────────────────────────────────────────
        $promosData = [
            [
                'judul'           => 'Paket Medical Check-Up Hemat',
                'deskripsi'       => 'Dapatkan paket pemeriksaan kesehatan lengkap meliputi cek darah, urine, rontgen thorax, dan konsultasi dokter umum dengan harga spesial. Cocok untuk karyawan dan keluarga yang ingin memastikan kondisi kesehatan secara menyeluruh.',
                'tanggal_mulai'   => '2026-07-01',
                'tanggal_selesai' => '2026-09-30',
            ],
            [
                'judul'           => 'Diskon 20% Konsultasi Spesialis Jantung',
                'deskripsi'       => 'Kami memberikan diskon 20% untuk biaya konsultasi dokter spesialis jantung dan pembuluh darah sepanjang bulan ini. Jaga kesehatan jantung Anda bersama dokter berpengalaman kami.',
                'tanggal_mulai'   => '2026-07-15',
                'tanggal_selesai' => '2026-08-31',
            ],
            [
                'judul'           => 'Promo Imunisasi Anak Lengkap',
                'deskripsi'       => 'Program imunisasi lengkap untuk anak usia 0–5 tahun dengan harga terjangkau. Termasuk vaksin wajib dan vaksin tambahan yang direkomendasikan oleh dokter spesialis anak RS Sari Sehat.',
                'tanggal_mulai'   => '2026-07-01',
                'tanggal_selesai' => '2026-10-31',
            ],
            [
                'judul'           => 'Free Ongkir Ambulans Dalam Kota',
                'deskripsi'       => 'Gratis biaya transportasi ambulans untuk pasien yang dirujuk ke RS Sari Sehat dari dalam kota Depok dan sekitarnya. Berlaku untuk kasus gawat darurat selama periode promosi berlangsung.',
                'tanggal_mulai'   => '2026-08-01',
                'tanggal_selesai' => '2026-08-31',
            ],
            [
                'judul'           => 'Paket Persalinan Normal Spesial',
                'deskripsi'       => 'Nikmati paket persalinan normal dengan fasilitas kamar kelas 1, pendampingan bidan profesional, dan perawatan bayi baru lahir. Daftarkan kehamilan Anda sekarang dan dapatkan harga promo terbaik.',
                'tanggal_mulai'   => '2026-07-01',
                'tanggal_selesai' => '2026-12-31',
            ],
            [
                'judul'           => 'Diskon Laboratorium Gula Darah & Kolesterol',
                'deskripsi'       => 'Cek gula darah puasa, HbA1c, dan profil lipid (kolesterol lengkap) dengan harga diskon spesial. Tersedia setiap hari Senin–Jumat tanpa perlu janji temu. Hasil keluar dalam 2 jam.',
                'tanggal_mulai'   => '2026-07-10',
                'tanggal_selesai' => '2026-09-10',
            ],
        ];

        foreach ($promosData as $p) {
            Promo::updateOrCreate(
                ['judul' => $p['judul']],
                array_merge($p, [
                    'status'     => 'aktif',
                    'created_by' => $cms->id,
                ])
            );
        }

        $this->command->info('✅ Database seeder selesai!');
        $this->command->info('   Admin:  admin@sarisehat.test / password');
        $this->command->info('   CMS:    cms@sarisehat.test / password');
        $this->command->info('   Pasien: pasien@sarisehat.test / password');
    }
}
