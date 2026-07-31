# DATABASE MIGRATION PLAN
**From Old Schema → New Schema (Database Baru)**

## 📌 RINGKASAN PERBEDAAN UTAMA

### 1. **Tabel Users**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `name` | `nama` | Rename |
| `phone` | `no_hp` | Rename |
| `avatar` | `foto` | Rename |
| `is_active` (boolean) | `status` (enum 'aktif'/'nonaktif') | Type change |
| `remember_token` | ❌ Tidak ada | Removed |
| `email_verified_at` | ❌ Tidak ada | Removed |
| `created_at`, `updated_at` | `created_tm`, `updated_tm`, `deleted_tm` | Rename + Soft Delete support |
| ❌ Tidak ada | `username` (varchar 50, unique) | Added |
| ❌ Tidak ada | `last_login` (datetime) | Added |
| `role` (admin/cms/user) | `role` (cms/admin/pasien) | Value change |
| ❌ Tidak ada | `created_by`, `updated_by`, `deleted_by` | Added (audit) |

### 2. **Tabel Spesialisasi**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `spesialisasis` | `spesialis` | Rename table |
| `nama` | `nama_spesialis` | Rename |
| `is_aktif` (boolean) | ❌ Tidak ada status | Removed |
| `urutan`, `slug`, `icon_fa` | ❌ Tidak ada | Removed |
| ❌ Tidak ada | `created_tm`, `updated_tm`, `deleted_tm` | Added |
| ❌ Tidak ada | `created_by`, `updated_by`, `deleted_by` | Added (audit) |

### 3. **Tabel Dokter**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `dokters` | `dokter` | Rename table |
| `nama` | `nama_dokter` | Rename |
| `gelar`, `slug`, `warna_dari`, `warna_ke`, `bio`, `pendidikan`, `tahun_pengalaman`, `total_ulasan`, `rating`, `tersedia_online` | ❌ Tidak ada | Removed |
| `no_str` | ❌ Tidak ada | Removed |
| `no_sip` | `sip` | Rename |
| `is_aktif` (boolean) | `status` (enum 'aktif'/'nonaktif') | Type change |
| ❌ Tidak ada | `email` (unique) | Added |
| ❌ Tidak ada | `no_hp` | Added |
| `spesialisasi_id` | `spesialis_id` | Rename |
| `created_at`, `updated_at` | `created_tm`, `updated_tm`, `deleted_tm` | Rename |

### 4. **Tabel Jadwal Dokter**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `jadwal_dokters` | `jadwal_dokter` | Rename table |
| `hari` (int 1-7) | `hari` (enum Senin-Minggu) + `tanggal_praktek` (date) | Type change + Added |
| `is_aktif` (boolean) | `status` (enum) | Type change |
| ❌ Tidak ada | `spesialis_id` | Added |
| ❌ Tidak ada | `penjamin_id` | Added |

### 5. **Tabel Pasien**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `pasiens` | `pasien` | Rename table |
| `nama_lengkap` | ❌ (ambil dari users.nama) | Removed |
| `nama_panggilan`, `tempat_lahir`, `kota`, `provinsi`, `kode_pos`, `telepon`, `telepon_darurat`, `nama_kontak_darurat`, `hubungan_kontak_darurat`, `pekerjaan`, `agama`, `status_pernikahan`, `no_asuransi`, `nama_asuransi`, `riwayat_alergi`, `riwayat_penyakit` | ❌ | Removed |
| `no_rm` | `no_rekam_medis` | Rename |
| `golongan_darah` | `golongan_darah` (enum A/B/AB/O) | Same but type change |
| `jenis_kelamin` | `jenis_kelamin` (enum L/P) | Same |
| ❌ Tidak ada | `tempat_lahir` | Added |
| ❌ Tidak ada | `agama` | Added |
| ❌ Tidak ada | `pekerjaan` | Added |
| ❌ Tidak ada | `penjamin_id` | Added |
| ❌ Tidak ada | `nomor_penjamin` | Added |
| `no_bpjs` | ❌ (sekarang via penjamin) | Removed |

### 6. **Tabel Janji Temu**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `janji_temus` | `janji_temu` | Rename table |
| `kode_booking` | ❌ Tidak ada | Removed |
| `dokter_id` | ❌ (sekarang via jadwal_dokter) | Removed |
| `layanan_id` | ❌ Tidak ada | Removed |
| `tanggal_kunjungan` | `tanggal_booking` | Rename |
| `jam_kunjungan` | ❌ Tidak ada | Removed |
| `keluhan` | `keluhan` | Same |
| `catatan_pasien`, `catatan_admin`, `alasan_batal`, `tipe` | ❌ | Removed |
| `nomor_antrian` | `nomor_antrian` | Same |
| `status` (menunggu/dikonfirmasi/hadir/selesai/dibatalkan/tidak_hadir) | `status` (pending/approved/completed/cancelled) | Value change |

### 7. **TABEL BARU yang TIDAK ADA di OLD SCHEMA**
- ✅ `tipe_penjamin` - Tabel tipe penjamin (BPJS, Asuransi, Umum, dll)
- ✅ `penjamin` - Master data penjamin per tipe
- ✅ `transaksi` - Data transaksi pembayaran
- ✅ `detail_transaksi` - Detail item transaksi
- ✅ `guest_book` - Buku tamu pengunjung website
- ✅ `informasi` - Halaman informasi/konten static
- ✅ `kategori_galeri` - Kategori untuk galeri
- ✅ `kategori_layanan` - Kategori untuk layanan
- ✅ `website_setting` - Pengaturan website (visi, misi, kontak, dll)

### 8. **TABEL LAMA yang TIDAK ADA di NEW SCHEMA**
- ❌ `rumah_sakits` - Informasi RS (sekarang di `website_setting`)
- ❌ `informasi_kontaks` - Info kontak (sekarang di `website_setting`)
- ❌ `notifikasis` - Notifikasi in-app user
- ❌ `kontaks` - Pesan kontak dari user

### 9. **Tabel Artikel**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `artikels` | `artikel` | Rename table |
| `user_id` | ❌ Tidak ada | Removed |
| `ringkasan`, `konten` | `isi` | Merged |
| `gambar_utama` | `gambar` + `thumbnail` | Split |
| `emoji`, `warna_dari`, `warna_ke`, `tags`, `published_at`, `total_dibaca` | ❌ | Removed |
| `status` (draft/published) | `status` (draft/publish) | Same |

### 10. **Tabel Banner**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `banners` | `banner` | Rename table |
| `subjudul`, `warna_dari`, `warna_ke`, `badge_label`, `teks_tombol_1`, `url_tombol_1`, `teks_tombol_2`, `url_tombol_2`, `posisi`, `urutan` | ❌ | Removed |
| `is_aktif` (boolean) | `status` (enum) | Type change |

### 11. **Tabel Galeri**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `galeris` | `galeri` | Rename table |
| `file`, `tipe`, `thumbnail`, `kategori`, `urutan` | ❌ | Removed |
| `is_aktif` (boolean) | `status` (enum) | Type change |
| ❌ Tidak ada | `kategori_galeri_id` | Added |
| ❌ Tidak ada | `gambar` | Added |

### 12. **Tabel Layanan**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `layanans` | `layanan` | Rename table |
| `kode`, `fasilitas`, `jam_operasional`, `tersedia_online`, `urutan`, `slug` | ❌ | Removed |
| `nama` | `nama_layanan` | Rename |
| `icon_fa`, `warna` | `icon` | Merged |
| `is_aktif` (boolean) | `status` (enum) | Type change |
| `deskripsi` | `deskripsi` | Same |

### 13. **Tabel Event**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `events` | `event` | Rename table |
| `slug`, `tipe`, `link_pendaftaran`, `is_online` | ❌ | Removed |
| `deskripsi` | `deskripsi` | Same |
| `tanggal_mulai`, `tanggal_selesai` | `tanggal_event` + `waktu_event` | Changed |
| `status` (draft/published) | `status` (aktif/nonaktif) | Value change |

### 14. **Tabel Promo**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `promos` | `promo` | Rename table |
| `slug`, `ringkasan`, `icon_fa`, `warna_dari`, `warna_ke`, `harga_asli`, `harga_promo`, `is_featured`, `urutan` | ❌ | Removed |
| `tanggal_berakhir` | `tanggal_selesai` | Rename |
| `status` (draft/published) | `status` (aktif/nonaktif) | Value change |

### 15. **Tabel FAQ**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `faqs` | ❌ Tidak ada | Removed |

### 16. **Tabel Kategori Artikel**
| OLD | NEW | PERUBAHAN |
|-----|-----|-----------|
| `kategori_artikels` | `kategori_artikel` | Rename table |
| `slug`, `warna` | ❌ | Removed |
| `nama` | `nama_kategori` | Rename |
| ❌ Tidak ada | `status` (enum) | Added |

---

## 🎯 STRATEGI MIGRASI

### **FASE 1: Backup & Persiapan**
1. ✅ Backup database lama (sudah dilakukan user)
2. ✅ Import database baru (sudah dilakukan user)
3. ✅ Export skema database baru (sudah dilakukan)
4. 🔄 Generate Laravel migrations berdasarkan skema baru
5. 🔄 Hapus migration lama, gunakan migration baru

### **FASE 2: Update Models**
Sesuaikan SEMUA model agar match dengan database baru:
- Nama tabel
- Fillable attributes
- Casts
- Relasi (foreign key changes)
- Accessor/Mutator (sesuaikan nama kolom)
- Scopes (sesuaikan kondisi)

### **FASE 3: Update Controllers**
- Sesuaikan query eloquent dengan nama tabel/kolom baru
- Sesuaikan validasi dengan kolom baru
- Perbaiki relasi yang berubah
- Tambah logic untuk tabel baru (transaksi, penjamin, dll)

### **FASE 4: Update Routes**
- Pastikan route masih sesuai dengan controller methods
- Tidak perlu ubah URL (agar frontend tidak rusak)

### **FASE 5: Update Seeders & Factories**
- Sesuaikan dengan struktur baru
- Tambah seeder untuk tabel baru

### **FASE 6: Testing**
- Test semua endpoint
- Test relasi antar tabel
- Test frontend (tidak boleh ada perubahan UI)

---

## ⚠️ CATATAN PENTING

1. **Frontend TIDAK BOLEH BERUBAH** - Hanya backend yang disesuaikan
2. **Kolom audit trail** (`created_by`, `updated_by`, `deleted_by`) sekarang wajib ada di hampir semua tabel
3. **Soft delete** menggunakan `deleted_tm` (bukan `deleted_at`)
4. **Timestamps** menggunakan `created_tm` dan `updated_tm` (bukan `created_at` / `updated_at`)
5. **Boolean di DB lama** → **ENUM di DB baru** (`is_aktif` → `status` enum)
6. **Nama tabel** dari plural → singular (users → users, dokters → dokter, dll)
7. **Role user** berubah: `'user'` → `'pasien'`
8. **Status janji temu** berubah: `'menunggu', 'dikonfirmasi', 'hadir', 'selesai', 'dibatalkan', 'tidak_hadir'` → `'pending', 'approved', 'completed', 'cancelled'`

---

## 📋 CHECKLIST EKSEKUSI

- [ ] Task 1: Analisis struktur database baru
- [ ] Task 2: Generate migration files baru
- [ ] Task 3: Update 18 Models
- [ ] Task 4: Update 6+ Controllers
- [ ] Task 5: Update Seeders & Factories
- [ ] Task 6: Testing & Verifikasi

