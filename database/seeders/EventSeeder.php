<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Seed 8 event asli dari database.
     *
     * Pencegahan duplikasi: updateOrInsert berdasarkan judul
     * (judul bersifat unik per event).
     */
    public function run(): void
    {
        $events = [
            [
                'judul'         => 'Seminar Kesehatan Jantung: Kenali Gejala Serangan Jantung Sejak Dini',
                'gambar'        => 'event/7lKQIU8lKtR53MXJBenLnPbjAMzjA4WqUSgQZNTH.png',
                'deskripsi'     => 'RS Sari Sehat mengadakan seminar kesehatan mengenai pencegahan penyakit jantung bersama dokter spesialis jantung. Peserta akan mendapatkan edukasi mengenai faktor risiko, pola hidup sehat, serta sesi tanya jawab langsung dengan dokter. Acara ini terbuka untuk masyarakat umum tanpa dipungut biaya.',
                'lokasi'        => 'Aula RS Sari Sehat Lt. 2',
                'tanggal_event' => '2026-08-15',
                'waktu_event'   => '08:00:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:33:34',
                'updated_tm'    => '2026-07-24 07:56:32',
            ],
            [
                'judul'         => 'Program Vaksinasi Influenza untuk Masyarakat',
                'gambar'        => 'event/mpHerMpm4BKxu2Y7xHqjnzRWXW2Ny7hMJPa0kAVG.png',
                'deskripsi'     => 'RS Sari Sehat mengadakan program vaksinasi influenza untuk membantu meningkatkan daya tahan tubuh dan mencegah penyebaran penyakit musiman. Peserta akan mendapatkan konsultasi singkat sebelum vaksinasi oleh tenaga medis profesional.',
                'lokasi'        => 'Klinik Vaksin RS Sari Sehat',
                'tanggal_event' => '2026-09-26',
                'waktu_event'   => '08:00:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:35:12',
                'updated_tm'    => '2026-07-24 08:04:38',
            ],
            [
                'judul'         => 'Edukasi Gizi Seimbang untuk Tumbuh Kembang Anak',
                'gambar'        => 'event/0rg7WYfEMHujpiA3FenMSzdbccFAnMRzuwZIP59J.png',
                'deskripsi'     => 'Seminar bersama ahli gizi mengenai pentingnya asupan nutrisi yang seimbang untuk mendukung pertumbuhan dan perkembangan anak. Orang tua juga dapat berkonsultasi mengenai pola makan sehat bagi buah hati.',
                'lokasi'        => 'Aula RS Sari Sehat',
                'tanggal_event' => '2026-10-03',
                'waktu_event'   => '09:30:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:36:05',
                'updated_tm'    => '2026-07-24 08:06:36',
            ],
            [
                'judul'         => 'Pemeriksaan dan Edukasi Deteksi Dini Kanker Payudara',
                'gambar'        => 'event/gL8wx1ot6DvEHFpcgzFr4rmkait1P3wHKwULCYQk.png',
                'deskripsi'     => 'Kegiatan skrining kesehatan disertai edukasi mengenai pentingnya deteksi dini kanker payudara melalui pemeriksaan mandiri maupun pemeriksaan medis. Terbuka bagi seluruh wanita usia di atas 20 tahun.',
                'lokasi'        => 'Poli Bedah RS Sari Sehat',
                'tanggal_event' => '2026-10-10',
                'waktu_event'   => '08:30:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:38:08',
                'updated_tm'    => '2026-07-24 08:10:24',
            ],
            [
                'judul'         => 'Pemeriksaan Mata Gratis dan Konsultasi Dokter Spesialis Mata',
                'gambar'        => 'event/XGDafNcuS1iPjkDsDMPI6FhjOi6ZbNNR02QDGk5d.png',
                'deskripsi'     => 'Peserta akan mendapatkan pemeriksaan ketajaman penglihatan, konsultasi dengan dokter spesialis mata, serta edukasi mengenai cara menjaga kesehatan mata dalam aktivitas sehari-hari.',
                'lokasi'        => 'Poli Mata RS Sari Sehat',
                'tanggal_event' => '2026-07-17',
                'waktu_event'   => '09:00:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:38:52',
                'updated_tm'    => '2026-07-24 08:00:18',
            ],
            [
                'judul'         => 'Talkshow Kesehatan Mental dan Manajemen Stres',
                'gambar'        => 'event/2DuuFffXZyhqiNO6LSmUTYAm7T6lqe9q904Z74SY.png',
                'deskripsi'     => 'Menghadirkan psikolog dan dokter spesialis kejiwaan untuk membahas pentingnya menjaga kesehatan mental, mengenali tanda stres berlebihan, serta cara mengelola emosi dengan baik.',
                'lokasi'        => 'Aula RS Sari Sehat',
                'tanggal_event' => '2026-10-24',
                'waktu_event'   => '13:00:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:39:49',
                'updated_tm'    => '2026-07-24 08:12:56',
            ],
            [
                'judul'         => 'Penyuluhan Hipertensi dan Pemeriksaan Tekanan Darah Gratis',
                'gambar'        => 'event/PURtzs36ytWEQtVNItFceSCd6z5Qd1cygeKHz6Jl.png',
                'deskripsi'     => 'Kegiatan edukasi mengenai penyebab hipertensi, pencegahan komplikasi, serta pemeriksaan tekanan darah secara gratis bagi seluruh pengunjung rumah sakit.',
                'lokasi'        => 'Lobby RS Sari Sehat',
                'tanggal_event' => '2026-10-31',
                'waktu_event'   => '08:00:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:40:31',
                'updated_tm'    => '2026-07-24 08:17:29',
            ],
            [
                'judul'         => 'Seminar Pola Hidup Sehat untuk Semua Usia',
                'gambar'        => 'event/1jFkBBAqYU2rGhXRLAv2dj9KtC3rmebO7eOj7jqX.png',
                'deskripsi'     => 'Seminar mengenai pentingnya menjaga pola makan, aktivitas fisik, kualitas tidur, dan pemeriksaan kesehatan secara berkala untuk meningkatkan kualitas hidup',
                'lokasi'        => 'Aula RS Sari Sehat',
                'tanggal_event' => '2026-11-07',
                'waktu_event'   => '09:00:00',
                'kuota'         => null,
                'status'        => 'aktif',
                'created_by'    => 2,
                'updated_by'    => 2,
                'created_tm'    => '2026-07-24 07:42:25',
                'updated_tm'    => '2026-07-24 07:48:45',
            ],
        ];

        foreach ($events as $data) {
            // Cegah duplikasi berdasarkan judul (unik per event)
            DB::table('event')->updateOrInsert(
                ['judul' => $data['judul']],
                $data
            );
        }

        $this->command->info('✅ EventSeeder: 8 event berhasil di-seed.');
    }
}
