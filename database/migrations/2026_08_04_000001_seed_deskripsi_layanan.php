<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Isi deskripsi default untuk layanan dan kategori layanan.
 * Hanya mengisi baris yang deskripsinya masih NULL atau kosong.
 */
return new class extends Migration
{
    public function up(): void
    {
        $this->seedKategori();
        $this->seedLayanan();
    }

    private function seedKategori(): void
    {
        $items = [
            ['like' => '%bedah%',            'desc' => 'Layanan bedah komprehensif dengan tim dokter spesialis bedah berpengalaman dan fasilitas kamar operasi modern. Kami menangani berbagai prosedur pembedahan mulai dari bedah umum, bedah digestif, hingga bedah laparoskopi minim invasif untuk pemulihan lebih cepat.'],
            ['like' => '%igd%',              'desc' => 'Instalasi Gawat Darurat (IGD) beroperasi 24 jam sehari, 7 hari seminggu. Ditangani dokter jaga dan tim perawat terlatih yang siap memberikan penanganan cepat dan tepat untuk setiap kondisi darurat medis, kecelakaan, dan kedaruratan lainnya.'],
            ['like' => '%laborator%',        'desc' => 'Laboratorium klinik berstandar akreditasi nasional dengan lebih dari 200 jenis pemeriksaan. Meliputi hematologi, kimia darah, imunologi, mikrobiologi, dan urinalisis. Hasil akurat dengan waktu tunggu efisien dan pengiriman hasil secara digital.'],
            ['like' => '%medical check%',    'desc' => 'Program Medical Check-Up untuk deteksi dini penyakit dan pemantauan kesehatan secara menyeluruh. Tersedia paket MCU Basic, Standar, Komprehensif, dan Eksekutif, serta paket khusus pranikah dan karyawan perusahaan.'],
            ['like' => '%radiologi%',        'desc' => 'Departemen Radiologi dengan peralatan pencitraan berteknologi tinggi termasuk X-Ray digital, USG, CT-Scan multi-slice, dan MRI. Seluruh hasil pemeriksaan diekspertisi oleh dokter spesialis radiologi untuk diagnosis yang akurat.'],
            ['like' => '%rawat inap%',       'desc' => 'Fasilitas rawat inap dengan berbagai kelas kamar: Kelas III, II, I, VIP, dan VVIP. Setiap kamar dilengkapi bed adjustable, TV, AC, kamar mandi dalam, dan akses WiFi. Perawatan oleh perawat profesional 24 jam.'],
            ['like' => '%rawat jalan%',      'desc' => 'Poliklinik rawat jalan melayani konsultasi medis oleh dokter umum dan dokter spesialis setiap hari. Tersedia lebih dari 10 poli spesialis termasuk penyakit dalam, bedah, anak, kandungan, jantung, saraf, mata, THT, kulit, dan gigi.'],
            ['like' => '%poliklinik%',       'desc' => 'Poliklinik rawat jalan melayani konsultasi medis oleh dokter umum dan dokter spesialis setiap hari. Sistem pendaftaran online tersedia untuk kemudahan pasien.'],
        ];

        foreach ($items as $item) {
            DB::table('kategori_layanan')
                ->whereNull('deleted_tm')
                ->where('nama_kategori', 'like', $item['like'])
                ->where(function ($q) { $q->whereNull('deskripsi')->orWhere('deskripsi', ''); })
                ->update(['deskripsi' => $item['desc']]);
        }
    }

    private function seedLayanan(): void
    {
        $items = [
            ['like' => '%ruang bedah%',       'desc' => "Ruang Bedah RS Sari Sehat dilengkapi peralatan operasi berstandar internasional, sistem ventilasi bertekanan positif, laminar air flow, dan monitoring anestesi canggih.\n\nTim dokter bedah didukung ahli anestesiologi berpengalaman yang menangani berbagai prosedur bedah elektif maupun darurat dengan tingkat keberhasilan tinggi."],
            ['like' => '%bedah berdarah%',    'desc' => "Ruang bedah khusus untuk prosedur yang melibatkan potensi perdarahan signifikan. Dilengkapi unit transfusi darah, peralatan cell-saver, dan tim dokter spesialis terlatih dalam manajemen perdarahan intraoperatif.\n\nMemastikan penanganan optimal pada kasus bedah onkologi, trauma mayor, dan prosedur vaskular."],
            ['like' => '%igd%',               'desc' => "IGD RS Sari Sehat beroperasi penuh 24 jam sehari, 7 hari seminggu tanpa hari libur. Sistem triase terstruktur memastikan pasien kritis mendapat penanganan pertama dengan cepat.\n\nFasilitas meliputi:\n• Ruang resusitasi dengan ventilator dan defibrillator\n• Ruang observasi berkapasitas 10 tempat tidur\n• Akses langsung ke laboratorium dan radiologi darurat\n• Ambulans siap 24 jam\n\nTim dokter jaga bersertifikat ACLS dan ATLS."],
            ['like' => '%laboratori%',        'desc' => "Laboratorium Klinik RS Sari Sehat terakreditasi dengan lebih dari 200 jenis pemeriksaan, meliputi:\n\n• Darah lengkap dan hitung jenis\n• Kimia klinik (gula darah, kolesterol, fungsi hati, fungsi ginjal)\n• Hormon (tiroid, reproduksi)\n• Tumor marker (PSA, CEA, CA125)\n• Pemeriksaan infeksi (dengue, hepatitis, HIV)\n• Analisis urine dan feses\n• Kultur bakteri\n\nHasil tersedia dalam 2-4 jam dengan pengiriman digital ke email atau WhatsApp."],
            ['like' => '%medical check%',     'desc' => "Paket Medical Check-Up RS Sari Sehat:\n\n🔹 Paket Basic — Darah lengkap, urine, gula darah, kolesterol, konsultasi dokter\n\n🔹 Paket Standar — Basic + fungsi hati, fungsi ginjal, asam urat, rontgen thorax, EKG\n\n🔹 Paket Komprehensif — Standar + USG abdomen, tumor marker, hormon tiroid\n\n🔹 Paket Eksekutif — Pemeriksaan paling lengkap termasuk CT-Scan dan konsultasi spesialis\n\n🔹 Paket Pranikah — Pemeriksaan khusus calon pengantin\n\nHasil MCU berupa laporan lengkap dengan interpretasi dokter."],
            ['like' => '%radiologi%',         'desc' => "Unit Radiologi RS Sari Sehat menggunakan peralatan pencitraan berteknologi tinggi:\n\n• X-Ray Digital — rontgen dengan kualitas gambar lebih jelas, dosis radiasi lebih rendah\n• USG (Ultrasonografi) — untuk abdomen, obstetri, jantung, dan jaringan lunak\n• CT-Scan Multi-Slice — pencitraan 3D detail untuk kepala, thorax, abdomen\n• MRI — diagnosis jaringan lunak dan otak tanpa radiasi\n\nSemua hasil diekspertisi dokter spesialis radiologi. Hasil tersedia dalam 1-24 jam."],
            ['like' => '%rawat inap%',        'desc' => "Fasilitas Rawat Inap RS Sari Sehat:\n\n🏥 Kelas III — Kamar bersama 4 tempat tidur\n🏥 Kelas II — Kamar bersama 2 tempat tidur\n🏥 Kelas I — Kamar pribadi\n🌟 VIP — Kamar mewah dengan ruang keluarga dan fasilitas premium\n👑 VVIP — Suite premium dengan layanan personal\n\nSemua kamar dilengkapi bed adjustable, nurse call 24 jam, kamar mandi dalam, AC, TV LED, dan WiFi gratis. Menu makan disesuaikan kebutuhan gizi pasien."],
            ['like' => '%rawat jalan%',       'desc' => "Poliklinik Rawat Jalan RS Sari Sehat menyediakan layanan konsultasi medis komprehensif:\n\nPoliklinik Spesialis:\n• Poli Penyakit Dalam\n• Poli Bedah Umum\n• Poli Anak (Pediatri)\n• Poli Kandungan & Kebidanan\n• Poli Jantung (Kardiologi)\n• Poli Saraf (Neurologi)\n• Poli Mata\n• Poli THT\n• Poli Kulit & Kelamin\n• Poli Gigi & Mulut\n• Poli Ortopedi\n• Poli Dokter Umum\n\nPendaftaran online, via WhatsApp, atau walk-in di loket."],
            ['like' => '%poliklinik%',        'desc' => "Poliklinik RS Sari Sehat melayani pasien dengan sistem perjanjian dan walk-in. Tersedia berbagai poli spesialis dengan jadwal dokter yang terstruktur.\n\nSistem pendaftaran:\n✅ Online melalui portal pasien\n✅ Via WhatsApp\n✅ Langsung di loket pendaftaran\n\nJadwal dokter tersedia di halaman Dokter & Spesialis."],
        ];

        foreach ($items as $item) {
            DB::table('layanan')
                ->whereNull('deleted_tm')
                ->where('nama_layanan', 'like', $item['like'])
                ->where(function ($q) { $q->whereNull('deskripsi')->orWhere('deskripsi', ''); })
                ->update(['deskripsi' => $item['desc']]);
        }
    }

    public function down(): void
    {
        // Intentionally empty - cannot safely rollback content seeding
    }
};
