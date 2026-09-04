<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LayananDeskripsiSeeder extends Seeder
{
    public function run(): void
    {
        // ── Deskripsi KATEGORI LAYANAN ───────────────────────────────
        $kategoriData = [
            [
                'keywords' => ['bedah'],
                'deskripsi' => 'Layanan bedah komprehensif dengan tim dokter spesialis bedah berpengalaman dan fasilitas kamar operasi modern. Kami menangani berbagai prosedur pembedahan mulai dari bedah umum, bedah digestif, hingga bedah laparoskopi minim invasif untuk pemulihan lebih cepat.',
                'icon' => 'fa-scalpel-alt',
            ],
            [
                'keywords' => ['igd', 'gawat darurat', '24 jam'],
                'deskripsi' => 'Instalasi Gawat Darurat (IGD) beroperasi 24 jam sehari, 7 hari seminggu. Ditangani dokter jaga dan tim perawat terlatih yang siap memberikan penanganan cepat dan tepat untuk setiap kondisi darurat medis, kecelakaan, dan kedaruratan lainnya.',
                'icon' => 'fa-ambulance',
            ],
            [
                'keywords' => ['laboratorium', 'lab klinik', 'lab'],
                'deskripsi' => 'Laboratorium klinik berstandar akreditasi nasional dengan lebih dari 200 jenis pemeriksaan. Meliputi hematologi, kimia darah, imunologi, mikrobiologi, dan urinalisis. Hasil akurat dengan waktu tunggu efisien dan pengiriman hasil secara digital.',
                'icon' => 'fa-flask',
            ],
            [
                'keywords' => ['medical check', 'mcu', 'check-up', 'checkup'],
                'deskripsi' => 'Program Medical Check-Up untuk deteksi dini penyakit dan pemantauan kesehatan secara menyeluruh. Tersedia paket MCU Basic, Standar, Komprehensif, dan Eksekutif, serta paket khusus pranikah dan karyawan perusahaan dengan harga terjangkau.',
                'icon' => 'fa-clipboard-check',
            ],
            [
                'keywords' => ['radiologi', 'radiology'],
                'deskripsi' => 'Departemen Radiologi dengan peralatan pencitraan berteknologi tinggi termasuk X-Ray digital, USG, CT-Scan multi-slice, dan MRI. Seluruh hasil pemeriksaan diekspertisi oleh dokter spesialis radiologi untuk diagnosis yang akurat dan terpercaya.',
                'icon' => 'fa-x-ray',
            ],
            [
                'keywords' => ['rawat inap'],
                'deskripsi' => 'Fasilitas rawat inap dengan berbagai kelas kamar: Kelas III, II, I, VIP, dan VVIP. Setiap kamar dilengkapi bed adjustable, TV, AC, kamar mandi dalam, dan akses WiFi. Perawatan diberikan oleh perawat profesional selama 24 jam penuh.',
                'icon' => 'fa-bed',
            ],
            [
                'keywords' => ['rawat jalan', 'poliklinik'],
                'deskripsi' => 'Poliklinik rawat jalan melayani konsultasi dan pemeriksaan medis oleh dokter umum dan dokter spesialis setiap hari. Tersedia lebih dari 10 poli spesialis termasuk penyakit dalam, bedah, anak, kandungan, jantung, saraf, mata, THT, kulit, dan gigi.',
                'icon' => 'fa-stethoscope',
            ],
        ];

        foreach ($kategoriData as $item) {
            $query = DB::table('kategori_layanan')->whereNull('deleted_tm');
            $orQuery = null;
            foreach ($item['keywords'] as $kw) {
                if ($orQuery === null) {
                    $orQuery = $query->where('nama_kategori', 'like', '%' . $kw . '%');
                } else {
                    $orQuery = $orQuery->orWhere('nama_kategori', 'like', '%' . $kw . '%');
                }
            }
            if ($orQuery) {
                $orQuery->where(function ($q) {
                    $q->whereNull('deskripsi')->orWhere('deskripsi', '');
                })->update(['deskripsi' => $item['deskripsi']]);
            }
        }

        // ── Deskripsi LAYANAN (sub-layanan) ─────────────────────────
        $layananData = [
            [
                'keywords' => ['ruang bedah'],
                'deskripsi' => "Ruang Bedah RS Sari Sehat dilengkapi dengan peralatan operasi berstandar internasional, sistem ventilasi bertekanan positif, laminar air flow, dan monitoring anestesi canggih untuk menjamin keselamatan dan sterilisasi selama operasi berlangsung.\n\nTim dokter bedah kami didukung ahli anestesiologi berpengalaman yang menangani berbagai prosedur bedah elektif maupun darurat dengan tingkat keberhasilan tinggi.",
            ],
            [
                'keywords' => ['bedah berdarah', 'bedah darah'],
                'deskripsi' => "Ruang bedah khusus untuk prosedur yang melibatkan potensi perdarahan signifikan, dilengkapi unit transfusi darah, peralatan cell-saver, dan tim dokter spesialis terlatih dalam manajemen perdarahan intraoperatif.\n\nFasilitas ini memastikan penanganan optimal pada kasus seperti bedah onkologi, trauma mayor, dan prosedur vaskular.",
            ],
            [
                'keywords' => ['igd', 'gawat darurat', '24 jam'],
                'deskripsi' => "IGD RS Sari Sehat beroperasi penuh 24 jam sehari, 7 hari seminggu tanpa libur. Sistem triase terstruktur memastikan pasien dengan kondisi paling kritis mendapat penanganan pertama dengan cepat.\n\nFasilitas IGD kami meliputi:\n• Ruang resusitasi dengan ventilator dan defibrillator\n• Ruang observasi berkapasitas 10 tempat tidur\n• Akses langsung ke laboratorium dan radiologi darurat\n• Ambulans siap 24 jam untuk rujukan dan evakuasi\n\nTim dokter jaga IGD kami bersertifikat ACLS dan ATLS, memastikan penanganan kegawatdaruratan sesuai standar internasional.",
            ],
            [
                'keywords' => ['laboratorium klinik', 'lab klinik'],
                'deskripsi' => "Laboratorium Klinik RS Sari Sehat terakreditasi oleh Komite Akreditasi Laboratorium Kesehatan (KALK) dengan lebih dari 200 jenis pemeriksaan tersedia, meliputi:\n\n• Darah lengkap dan hitung jenis\n• Kimia klinik (gula darah, kolesterol, fungsi hati, fungsi ginjal)\n• Hormon (tiroid, reproduksi, kortisol)\n• Tumor marker (PSA, CEA, CA125, dll)\n• Pemeriksaan infeksi (dengue, malaria, hepatitis, HIV)\n• Analisis urine dan feses\n• Kultur dan sensitivitas bakteri\n\nHasil pemeriksaan dapat diterima dalam 2-4 jam untuk pemeriksaan rutin, dengan sistem pengiriman hasil digital langsung ke email atau WhatsApp pasien.",
            ],
            [
                'keywords' => ['medical check-up', 'medical checkup', 'mcu'],
                'deskripsi' => "Program Medical Check-Up RS Sari Sehat dirancang untuk deteksi dini penyakit dan pemantauan kondisi kesehatan secara menyeluruh.\n\nPaket yang tersedia:\n\n🔹 Paket Basic\nDarah lengkap, urine lengkap, gula darah, kolesterol, dan konsultasi dokter\n\n🔹 Paket Standar\nSemua paket Basic + fungsi hati, fungsi ginjal, asam urat, foto rontgen thorax, dan EKG\n\n🔹 Paket Komprehensif\nSemua paket Standar + USG abdomen, tumor marker, hormon tiroid, dan mata\n\n🔹 Paket Eksekutif\nPemeriksaan paling lengkap termasuk CT-Scan, stress test EKG, dan konsultasi dokter spesialis\n\n🔹 Paket Pranikah\nPemeriksaan kesehatan khusus calon pengantin termasuk pemeriksaan darah lengkap dan infeksi\n\nHasil MCU tersedia dalam bentuk laporan tertulis lengkap dengan interpretasi dokter.",
            ],
            [
                'keywords' => ['radiologi'],
                'deskripsi' => "Unit Radiologi RS Sari Sehat menggunakan peralatan pencitraan berteknologi tinggi untuk membantu diagnosis yang akurat dan tepat:\n\n• X-Ray Digital — foto rontgen konvensional dengan hasil lebih jelas dan dosis radiasi lebih rendah\n• USG (Ultrasonografi) — untuk abdomen, obstetri-ginekologi, jantung, dan jaringan lunak\n• CT-Scan Multi-Slice — pencitraan 3D detail untuk kepala, thorax, abdomen, dan tulang belakang\n• MRI (Magnetic Resonance Imaging) — diagnosis jaringan lunak, otak, dan tulang belakang tanpa radiasi\n\nSeluruh hasil pemeriksaan radiologi dibaca dan diekspertisi oleh dokter spesialis radiologi berpengalaman. Hasil dapat tersedia dalam 1-24 jam tergantung jenis pemeriksaan.",
            ],
            [
                'keywords' => ['rawat inap'],
                'deskripsi' => "Fasilitas Rawat Inap RS Sari Sehat menyediakan lingkungan yang nyaman dan kondusif untuk proses pemulihan pasien.\n\nKelas kamar yang tersedia:\n\n🏥 Kelas III — Kamar bersama kapasitas 4 tempat tidur, fasilitas dasar lengkap\n🏥 Kelas II — Kamar bersama kapasitas 2 tempat tidur, lebih privat\n🏥 Kelas I — Kamar pribadi dengan fasilitas lengkap\n🌟 VIP — Kamar mewah dengan ruang keluarga, sofa, kulkas mini, dan TV LED\n👑 VVIP — Suite premium dengan fasilitas premium dan layanan personal\n\nSetiap kamar dilengkapi:\n• Bed adjustable elektrik\n• Nurse call system 24 jam\n• Kamar mandi dalam\n• AC dengan kontrol individual\n• TV LED\n• Akses WiFi gratis\n• Menu makan yang dapat dikonsultasikan dengan ahli gizi",
            ],
            [
                'keywords' => ['rawat jalan', 'poliklinik'],
                'deskripsi' => "Poliklinik Rawat Jalan RS Sari Sehat menyediakan layanan konsultasi dan pemeriksaan medis yang komprehensif dengan sistem antrian yang terorganisir.\n\nPoliklinik Spesialis yang tersedia:\n• Poli Penyakit Dalam\n• Poli Bedah Umum\n• Poli Anak (Pediatri)\n• Poli Kandungan & Kebidanan (Obgyn)\n• Poli Jantung (Kardiologi)\n• Poli Saraf (Neurologi)\n• Poli Mata (Oftalmologi)\n• Poli THT (Telinga Hidung Tenggorokan)\n• Poli Kulit & Kelamin\n• Poli Gigi & Mulut\n• Poli Ortopedi\n• Poli Dokter Umum\n\nSistem Pendaftaran:\n✅ Pendaftaran online melalui portal pasien\n✅ Pendaftaran via WhatsApp\n✅ Walk-in di loket pendaftaran\n\nJadwal dokter dapat dilihat di halaman Dokter & Spesialis.",
            ],
        ];

        foreach ($layananData as $item) {
            foreach ($item['keywords'] as $kw) {
                DB::table('layanan')
                    ->whereNull('deleted_tm')
                    ->where('nama_layanan', 'like', '%' . $kw . '%')
                    ->where(function ($q) {
                        $q->whereNull('deskripsi')->orWhere('deskripsi', '');
                    })
                    ->update(['deskripsi' => $item['deskripsi']]);
            }
        }
    }
}
