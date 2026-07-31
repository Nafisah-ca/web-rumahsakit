# Desain Database — Website RS Sari Sehat

> **Stack:** Laravel 11 · MySQL 8 · Eloquent ORM  
> **Standar penamaan:** `snake_case` (konvensi Laravel)  
> **Normalisasi:** Minimal 3NF — setiap atribut non-key bergantung penuh pada PK, bukan pada atribut non-key lain.

---

## Daftar Isi

1. [Ringkasan Tabel](#1-ringkasan-tabel)
2. [Detail Setiap Tabel](#2-detail-setiap-tabel)
3. [Entity Relationship Diagram (ERD)](#3-entity-relationship-diagram-erd)
4. [Relasi Antar Tabel](#4-relasi-antar-tabel)
5. [Hak Akses Per Role](#5-hak-akses-per-role)
6. [Struktur Menu Dashboard](#6-struktur-menu-dashboard)
7. [Saran Tabel Tambahan](#7-saran-tabel-tambahan)
8. [Cara Menjalankan](#8-cara-menjalankan)

---

## 1. Ringkasan Tabel

| # | Tabel | Dikelola Oleh | Fungsi Singkat |
|---|-------|---------------|----------------|
| 1 | `users` | Admin | Akun login semua role |
| 2 | `rumah_sakits` | CMS | Profil utama rumah sakit |
| 3 | `cabangs` | CMS | Data cabang/unit RS |
| 4 | `banners` | CMS | Hero slider homepage |
| 5 | `kategori_artikels` | CMS | Kategori artikel (normalisasi) |
| 6 | `artikels` | CMS | Artikel/berita kesehatan |
| 7 | `promos` | CMS | Promo & penawaran |
| 8 | `cabang_promo` | — | Pivot promo ↔ cabang (M:N) |
| 9 | `events` | CMS | Jadwal kegiatan/event |
| 10 | `layanans` | CMS | Layanan medis tersedia |
| 11 | `galeris` | CMS | Foto & video galeri |
| 12 | `faqs` | CMS | Pertanyaan umum |
| 13 | `informasi_kontaks` | CMS | Kontak resmi RS per cabang |
| 14 | `spesialisasis` | Admin | Master spesialisasi dokter |
| 15 | `dokters` | Admin | Data dokter |
| 16 | `jadwal_dokters` | Admin | Jadwal praktik dokter |
| 17 | `pasiens` | Admin/Pasien | Profil medis pasien |
| 18 | `janji_temus` | Admin/Pasien | Appointment booking |
| 19 | `kontaks` | Admin | Pesan masuk dari pengunjung |
| 20 | `notifikasis` | Admin/Sistem | Notifikasi in-app |

**Tabel sistem Laravel (bawaan):**  
`password_reset_tokens` · `sessions` · `cache` · `jobs` · `failed_jobs`

---
