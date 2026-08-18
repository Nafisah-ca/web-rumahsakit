<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Spesialisasi;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * DokterSeeder — 6 Spesialis + 6 Dokter Umum
 *
 * Foto disimpan sebagai SVG di public/images/dokter/
 * → ikut ter-commit ke GitHub → muncul di semua clone repo teman.
 */
class DokterSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('role', 'admin')->first();
        $adminId = $admin?->id ?? 1;

        // Buat folder
        $dir = public_path('images/dokter');
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        // Generate semua file SVG foto
        $this->buatSemuaFoto();

        $tanggal = now()->startOfWeek()->toDateString();

        // ─────────────────────────────────────────────────────────
        // 6 DOKTER SPESIALIS
        // ─────────────────────────────────────────────────────────
        $spesialis = [
            [
                'nama_dokter' => 'dr. Ahmad Fauzi Sp.JP',
                'spesialis'   => 'Jantung & Pembuluh',
                'sip'         => 'SIP-001-2025',
                'email'       => 'ahmad.fauzi@sarisehat.id',
                'no_hp'       => '081100000001',
                'foto'        => 'images/dokter/sp-1.svg',
                'hari'        => ['Senin', 'Selasa', 'Rabu'],
                'mulai'       => '09:00:00', 'selesai' => '14:00:00',
            ],
            [
                'nama_dokter' => 'dr. Siti Rahayu Sp.OG',
                'spesialis'   => 'Kebidanan',
                'sip'         => 'SIP-002-2025',
                'email'       => 'siti.rahayu@sarisehat.id',
                'no_hp'       => '081100000002',
                'foto'        => 'images/dokter/sp-2.svg',
                'hari'        => ['Rabu', 'Kamis', 'Jumat'],
                'mulai'       => '09:00:00', 'selesai' => '14:00:00',
            ],
            [
                'nama_dokter' => 'dr. Bambang Wiranto Sp.S',
                'spesialis'   => 'Syaraf',
                'sip'         => 'SIP-003-2025',
                'email'       => 'bambang.w@sarisehat.id',
                'no_hp'       => '081100000003',
                'foto'        => 'images/dokter/sp-3.svg',
                'hari'        => ['Senin', 'Kamis', 'Sabtu'],
                'mulai'       => '10:00:00', 'selesai' => '15:00:00',
            ],
            [
                'nama_dokter' => 'dr. Linda Susanti Sp.A',
                'spesialis'   => 'Anak',
                'sip'         => 'SIP-004-2025',
                'email'       => 'linda.susanti@sarisehat.id',
                'no_hp'       => '081100000004',
                'foto'        => 'images/dokter/sp-4.svg',
                'hari'        => ['Senin', 'Selasa', 'Rabu'],
                'mulai'       => '08:00:00', 'selesai' => '13:00:00',
            ],
            [
                'nama_dokter' => 'dr. Dewi Kartika Sp.PD',
                'spesialis'   => 'Penyakit Dalam',
                'sip'         => 'SIP-005-2025',
                'email'       => 'dewi.kartika@sarisehat.id',
                'no_hp'       => '081100000005',
                'foto'        => 'images/dokter/sp-5.svg',
                'hari'        => ['Rabu', 'Kamis', 'Jumat'],
                'mulai'       => '09:00:00', 'selesai' => '14:00:00',
            ],
            [
                'nama_dokter' => 'dr. Reza Pratama Sp.JP',
                'spesialis'   => 'Jantung & Pembuluh',
                'sip'         => 'SIP-006-2025',
                'email'       => 'reza.pratama@sarisehat.id',
                'no_hp'       => '081100000006',
                'foto'        => 'images/dokter/sp-6.svg',
                'hari'        => ['Selasa', 'Kamis', 'Sabtu'],
                'mulai'       => '13:00:00', 'selesai' => '18:00:00',
            ],
        ];

        // ─────────────────────────────────────────────────────────
        // 6 DOKTER UMUM
        // ─────────────────────────────────────────────────────────
        $umum = [
            [
                'nama_dokter' => 'dr. Farhan Maulana',
                'spesialis'   => 'Penyakit Dalam',
                'sip'         => 'SIP-101-2025',
                'email'       => 'farhan.maulana@sarisehat.id',
                'no_hp'       => '081100000101',
                'foto'        => 'images/dokter/um-1.svg',
                'hari'        => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                'mulai'       => '07:00:00', 'selesai' => '14:00:00',
            ],
            [
                'nama_dokter' => 'dr. Nurul Hidayati',
                'spesialis'   => 'Penyakit Dalam',
                'sip'         => 'SIP-102-2025',
                'email'       => 'nurul.hidayati@sarisehat.id',
                'no_hp'       => '081100000102',
                'foto'        => 'images/dokter/um-2.svg',
                'hari'        => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                'mulai'       => '14:00:00', 'selesai' => '21:00:00',
            ],
            [
                'nama_dokter' => 'dr. Bagas Setiawan',
                'spesialis'   => 'Penyakit Dalam',
                'sip'         => 'SIP-103-2025',
                'email'       => 'bagas.setiawan@sarisehat.id',
                'no_hp'       => '081100000103',
                'foto'        => 'images/dokter/um-3.svg',
                'hari'        => ['Senin', 'Rabu', 'Jumat', 'Sabtu'],
                'mulai'       => '07:00:00', 'selesai' => '14:00:00',
            ],
            [
                'nama_dokter' => 'dr. Maya Indah Sari',
                'spesialis'   => 'Penyakit Dalam',
                'sip'         => 'SIP-104-2025',
                'email'       => 'maya.indah@sarisehat.id',
                'no_hp'       => '081100000104',
                'foto'        => 'images/dokter/um-4.svg',
                'hari'        => ['Selasa', 'Kamis', 'Sabtu'],
                'mulai'       => '08:00:00', 'selesai' => '14:00:00',
            ],
            [
                'nama_dokter' => 'dr. Rizky Ananda',
                'spesialis'   => 'Penyakit Dalam',
                'sip'         => 'SIP-105-2025',
                'email'       => 'rizky.ananda@sarisehat.id',
                'no_hp'       => '081100000105',
                'foto'        => 'images/dokter/um-5.svg',
                'hari'        => ['Selasa', 'Rabu', 'Kamis'],
                'mulai'       => '14:00:00', 'selesai' => '21:00:00',
            ],
            [
                'nama_dokter' => 'dr. Putri Ayu Lestari',
                'spesialis'   => 'Penyakit Dalam',
                'sip'         => 'SIP-106-2025',
                'email'       => 'putri.ayu@sarisehat.id',
                'no_hp'       => '081100000106',
                'foto'        => 'images/dokter/um-6.svg',
                'hari'        => ['Senin', 'Kamis', 'Sabtu'],
                'mulai'       => '07:00:00', 'selesai' => '14:00:00',
            ],
        ];

        // ─────────────────────────────────────────────────────────
        // Insert ke database
        // ─────────────────────────────────────────────────────────
        foreach ($spesialis as $d) {
            $this->insertDokter($d, 'spesialis', $tanggal, $adminId);
        }
        foreach ($umum as $d) {
            $this->insertDokter($d, 'umum', $tanggal, $adminId);
        }

        $this->command->info('✅ DokterSeeder: 6 spesialis + 6 umum berhasil disemai.');
    }

    private function insertDokter(array $d, string $tipe, string $tanggal, int $adminId): void
    {
        $sp = Spesialisasi::where('nama_spesialis', $d['spesialis'])->first();
        if (!$sp) return;

        $dokter = Dokter::updateOrCreate(
            ['sip' => $d['sip']],
            [
                'spesialis_id' => $sp->id,
                'nama_dokter'  => $d['nama_dokter'],
                'tipe_dokter'  => $tipe,
                'email'        => $d['email'],
                'no_hp'        => $d['no_hp'],
                'foto'         => $d['foto'],
                'status'       => 'aktif',
                'created_by'   => $adminId,
            ]
        );

        foreach ($d['hari'] as $hari) {
            JadwalDokter::updateOrCreate(
                ['dokter_id' => $dokter->id, 'hari' => $hari],
                [
                    'spesialis_id'    => $sp->id,
                    'tanggal_praktek' => $tanggal,
                    'jam_mulai'       => $d['mulai'],
                    'jam_selesai'     => $d['selesai'],
                    'kuota'           => 20,
                    'status'          => 'aktif',
                    'created_by'      => $adminId,
                ]
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    // GENERATE FOTO SVG
    // Setiap dokter punya SVG unik dengan inisial nama + warna berbeda.
    // File tersimpan di public/images/dokter/ → di-commit ke Git
    // → muncul di semua clone repo tanpa perlu storage link.
    // ─────────────────────────────────────────────────────────────
    private function buatSemuaFoto(): void
    {
        $list = [
            // Spesialis
            'sp-1' => ['bg1' => '#0d5c2f', 'bg2' => '#1a9450', 'inisial' => 'AF', 'gender' => 'L'],
            'sp-2' => ['bg1' => '#7c1a6e', 'bg2' => '#b5299e', 'inisial' => 'SR', 'gender' => 'P'],
            'sp-3' => ['bg1' => '#1a3a7c', 'bg2' => '#2955b5', 'inisial' => 'BW', 'gender' => 'L'],
            'sp-4' => ['bg1' => '#1a6e4e', 'bg2' => '#29a872', 'inisial' => 'LS', 'gender' => 'P'],
            'sp-5' => ['bg1' => '#6e4a1a', 'bg2' => '#a87229', 'inisial' => 'DK', 'gender' => 'P'],
            'sp-6' => ['bg1' => '#1a1a6e', 'bg2' => '#2929a8', 'inisial' => 'RP', 'gender' => 'L'],
            // Umum
            'um-1' => ['bg1' => '#0d6e5c', 'bg2' => '#1aa88a', 'inisial' => 'FM', 'gender' => 'L'],
            'um-2' => ['bg1' => '#6e1a1a', 'bg2' => '#a82929', 'inisial' => 'NH', 'gender' => 'P'],
            'um-3' => ['bg1' => '#3a5c0d', 'bg2' => '#5a9018', 'inisial' => 'BS', 'gender' => 'L'],
            'um-4' => ['bg1' => '#5c0d4a', 'bg2' => '#901872', 'inisial' => 'MI', 'gender' => 'P'],
            'um-5' => ['bg1' => '#0d3a5c', 'bg2' => '#185a90', 'inisial' => 'RA', 'gender' => 'L'],
            'um-6' => ['bg1' => '#5c3a0d', 'bg2' => '#905a18', 'inisial' => 'PA', 'gender' => 'P'],
        ];

        foreach ($list as $nama => $d) {
            $path = public_path("images/dokter/{$nama}.svg");
            // Selalu tulis ulang agar update ikut saat re-seed
            file_put_contents($path, $this->buatSvg($d['bg1'], $d['bg2'], $d['inisial'], $d['gender']));
        }
    }

    /**
     * Buat SVG ilustrasi dokter dengan jas putih + stetoskop.
     * Lebih realistis dari sekadar inisial.
     */
    private function buatSvg(string $bg1, string $bg2, string $inisial, string $gender): string
    {
        // Warna kulit & rambut
        $kulit  = '#f5c5a3';
        $rambut = $gender === 'P' ? '#3d2b1f' : '#2c1810';
        $jasWarna = '#ffffff';

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$bg1}"/>
      <stop offset="100%" stop-color="{$bg2}"/>
    </linearGradient>
    <clipPath id="circle">
      <circle cx="100" cy="100" r="100"/>
    </clipPath>
  </defs>

  <!-- Background bulat -->
  <circle cx="100" cy="100" r="100" fill="url(#bg)"/>

  <!-- Dekorasi background -->
  <circle cx="30" cy="30" r="40" fill="rgba(255,255,255,0.06)"/>
  <circle cx="170" cy="170" r="50" fill="rgba(255,255,255,0.06)"/>
  <circle cx="165" cy="35" r="20" fill="rgba(255,255,255,0.08)"/>

  <!-- Bahu / Badan (jas putih) -->
  <ellipse cx="100" cy="185" rx="58" ry="45" fill="{$jasWarna}" clip-path="url(#circle)"/>
  <!-- Kerah jas -->
  <polygon points="82,155 100,170 118,155 110,140 100,148 90,140" fill="{$jasWarna}" clip-path="url(#circle)"/>
  <!-- Dasi/baju dalam -->
  <polygon points="94,155 100,170 106,155 103,148 100,150 97,148" fill="{$bg1}" clip-path="url(#circle)"/>

  <!-- Leher -->
  <rect x="91" y="120" width="18" height="25" rx="6" fill="{$kulit}" clip-path="url(#circle)"/>

  <!-- Kepala -->
  <ellipse cx="100" cy="100" rx="32" ry="35" fill="{$kulit}"/>

  <!-- Rambut -->
SVG . ($gender === 'P' ? <<<SVG

  <ellipse cx="100" cy="72" rx="33" ry="18" fill="{$rambut}"/>
  <ellipse cx="68" cy="95" rx="8" ry="22" fill="{$rambut}"/>
  <ellipse cx="132" cy="95" rx="8" ry="22" fill="{$rambut}"/>
  <ellipse cx="100" cy="68" rx="20" ry="8" fill="{$rambut}"/>
SVG : <<<SVG

  <ellipse cx="100" cy="72" rx="33" ry="16" fill="{$rambut}"/>
  <rect x="68" y="72" width="64" height="12" rx="4" fill="{$rambut}"/>
SVG) . <<<SVG

  <!-- Mata -->
  <ellipse cx="88" cy="100" rx="4" ry="4.5" fill="#2c1810"/>
  <ellipse cx="112" cy="100" rx="4" ry="4.5" fill="#2c1810"/>
  <circle cx="89.5" cy="98.5" r="1.2" fill="white"/>
  <circle cx="113.5" cy="98.5" r="1.2" fill="white"/>

  <!-- Alis -->
  <path d="M83,93 Q88,90 93,93" stroke="#3d2b1f" stroke-width="2" fill="none" stroke-linecap="round"/>
  <path d="M107,93 Q112,90 117,93" stroke="#3d2b1f" stroke-width="2" fill="none" stroke-linecap="round"/>

  <!-- Hidung -->
  <path d="M98,106 Q100,112 102,106" stroke="#c9956e" stroke-width="1.5" fill="none" stroke-linecap="round"/>

  <!-- Senyum -->
  <path d="M90,115 Q100,122 110,115" stroke="#c9956e" stroke-width="2" fill="none" stroke-linecap="round"/>

  <!-- Stetoskop -->
  <path d="M75,148 Q65,138 68,125 Q71,112 80,115" stroke="#555" stroke-width="3" fill="none" stroke-linecap="round"/>
  <circle cx="80" cy="118" r="5" fill="#333" stroke="#555" stroke-width="1.5"/>
  <path d="M125,148 Q135,138 132,125 Q129,112 120,115" stroke="#555" stroke-width="3" fill="none" stroke-linecap="round"/>

  <!-- Badge nama inisial (pojok kanan bawah) -->
  <circle cx="158" cy="158" r="18" fill="rgba(0,0,0,0.3)"/>
  <text x="158" y="163" text-anchor="middle" font-family="Arial,sans-serif"
        font-size="11" font-weight="bold" fill="white">{$inisial}</text>
</svg>
SVG;
    }
}
