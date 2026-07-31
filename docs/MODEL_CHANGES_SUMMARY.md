# SUMMARY PERUBAHAN MODEL

## ✅ MODEL YANG DIUPDATE (11 Models)

### 1. **User.php**
- ✅ Timestamps: `created_at/updated_at` → `created_tm/updated_tm/deleted_tm`
- ✅ Fillable: `name` → `nama`, `phone` → `no_hp`, `avatar` → `foto`
- ✅ Tambah: `username`, `last_login`, audit fields
- ✅ Role: `'user'` → `'pasien'` (backward compatible)
- ✅ Status: `is_active` boolean → `status` enum (backward compatible accessor)
- ✅ Hapus relasi: artikels, kontaks, notifikasis

### 2. **Spesialisasi.php**
- ✅ Table: `spesialisasis` → `spesialis`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Fillable: `nama` → `nama_spesialis`
- ✅ Hapus: `slug`, `icon_fa`, `is_aktif`, `urutan`
- ✅ Tambah relasi: jadwalDokters

### 3. **Dokter.php**
- ✅ Table: `dokters` → `dokter`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Fillable: update ke kolom baru (nama_dokter, sip, email, no_hp)
- ✅ Hapus: gelar, slug, warna, bio, pendidikan, tahun_pengalaman, no_str, tersedia_online, rating, total_ulasan
- ✅ Status: `is_aktif` → `status` (backward compatible)
- ✅ Foreign key: `spesialisasi_id` → `spesialis_id`
- ✅ Accessor: backward compatible untuk `nama`, `is_aktif`

### 4. **JadwalDokter.php**
- ✅ Table: `jadwal_dokters` → `jadwal_dokter`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hari: integer (1-7) → enum string ('Senin'-'Minggu')
- ✅ Tambah: `spesialis_id`, `penjamin_id`, `tanggal_praktek`
- ✅ Status: `is_aktif` → `status` (backward compatible)
- ✅ Scope: support both integer and string hari

### 5. **Pasien.php**
- ✅ Table: `pasiens` → `pasien`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Fillable: `no_rm` → `no_rekam_medis`
- ✅ Hapus: nama_lengkap, nama_panggilan, kota, provinsi, kode_pos, telepon, telepon_darurat, kontak_darurat, status_pernikahan, no_bpjs, no_asuransi, nama_asuransi, riwayat_alergi, riwayat_penyakit
- ✅ Tambah: `penjamin_id`, `nomor_penjamin`
- ✅ Tambah relasi: penjamin, transaksis
- ✅ Accessor: backward compatible `no_rm`, `nama_lengkap`

### 6. **JanjiTemu.php**
- ✅ Table: `janji_temus` → `janji_temu`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hapus: `kode_booking`, `dokter_id`, `layanan_id`, `jam_kunjungan`, `catatan_pasien`, `catatan_admin`, `alasan_batal`, `tipe`
- ✅ Rename: `tanggal_kunjungan` → `tanggal_booking`
- ✅ Status mapping: menunggu→pending, dikonfirmasi→approved, selesai→completed, dibatalkan→cancelled
- ✅ Tambah relasi: transaksi
- ✅ Accessor: backward compatible `tanggal_kunjungan`, `kode_booking` (auto-generate)

### 7. **Artikel.php**
- ✅ Table: `artikels` → `artikel`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hapus: user_id, ringkasan, emoji, warna, tags, published_at, total_dibaca
- ✅ Merge: `ringkasan` + `konten` → `isi`
- ✅ Split: `gambar_utama` → `gambar` + `thumbnail`
- ✅ Status: `published` → `publish`
- ✅ Relasi penulis: `user_id` → `created_by`
- ✅ Accessor: backward compatible

### 8. **KategoriArtikel.php**
- ✅ Table: `kategori_artikels` → `kategori_artikel`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hapus: slug, warna
- ✅ Rename: `nama` → `nama_kategori`
- ✅ Tambah: `status` enum, audit fields
- ✅ Accessor: backward compatible `nama`

### 9. **Banner.php**
- ✅ Table: `banners` → `banner`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hapus: subjudul, warna, badge_label, tombol, posisi, urutan
- ✅ Status: `is_aktif` → `status` (backward compatible)

### 10. **Galeri.php**
- ✅ Table: `galeris` → `galeri`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Tambah: `kategori_galeri_id`
- ✅ Hapus: tipe, thumbnail, kategori (string), urutan
- ✅ Rename: `file` → `gambar`
- ✅ Status: `is_aktif` → `status` (backward compatible)
- ✅ Tambah relasi: kategori

### 11. **Layanan.php**
- ✅ Table: `layanans` → `layanan`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hapus: slug, kode, fasilitas, jam_operasional, tersedia_online, urutan
- ✅ Rename: `nama` → `nama_layanan`
- ✅ Merge: `icon_fa` + `warna` → `icon`
- ✅ Status: `is_aktif` → `status` (backward compatible)
- ✅ Hapus relasi: janjiTemus (kolom layanan_id tidak ada di janji_temu baru)

### 12. **Event.php**
- ✅ Table: `events` → `event`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hapus: slug, tipe, link_pendaftaran, is_online
- ✅ Split: `tanggal_mulai`, `tanggal_selesai` → `tanggal_event` + `waktu_event`
- ✅ Status: `published` → `aktif`
- ✅ Accessor: backward compatible

### 13. **Promo.php**
- ✅ Table: `promos` → `promo`
- ✅ Timestamps: standardize ke `_tm`
- ✅ Hapus: slug, ringkasan, icon, warna, harga_asli, harga_promo, is_featured, urutan
- ✅ Rename: `tanggal_berakhir` → `tanggal_selesai`
- ✅ Status: `published` → `aktif`
- ✅ Hapus relasi: cabangs
- ✅ Accessor: backward compatible

---

## ✅ MODEL BARU YANG DIBUAT (8 Models)

### 14. **TipePenjamin.php** ⭐ NEW
- Table: `tipe_penjamin`
- Relasi: hasMany penjamins
- Scope: aktif()

### 15. **Penjamin.php** ⭐ NEW
- Table: `penjamin`
- Relasi: belongsTo tipePenjamin, hasMany pasiens, jadwalDokters, transaksis
- Scope: aktif(), byTipe()

### 16. **Transaksi.php** ⭐ NEW
- Table: `transaksi`
- Relasi: belongsTo janjiTemu, pasien, penjamin; hasMany detailTransaksis
- Scope: lunas(), belumBayar(), selesai()
- Accessor: status labels

### 17. **DetailTransaksi.php** ⭐ NEW
- Table: `detail_transaksi`
- Relasi: belongsTo transaksi
- Cast: decimal untuk harga

### 18. **GuestBook.php** ⭐ NEW
- Table: `guest_book`
- Scope: baru(), dibaca(), selesai()
- Accessor: statusLabel

### 19. **Informasi.php** ⭐ NEW
- Table: `informasi`
- Scope: published(), terbaru()
- Accessor: tanggalFormat

### 20. **WebsiteSetting.php** ⭐ NEW
- Table: `website_setting`
- Method: getSetting() - singleton pattern
- Replace: RumahSakit + InformasiKontak (merged)

### 21. **KategoriGaleri.php** ⭐ NEW
- Table: `kategori_galeri`
- Relasi: hasMany galeris
- Scope: aktif()

---

## ❌ MODEL YANG DIHAPUS (5 Models)

1. **RumahSakit.php** - Replaced by WebsiteSetting
2. **InformasiKontak.php** - Merged into WebsiteSetting
3. **Kontak.php** - Tidak ada di database baru
4. **Notifikasi.php** - Tidak ada di database baru
5. **Faq.php** - Tidak ada di database baru

---

## 📝 CATATAN PENTING

### Backward Compatibility
Semua model dibuat dengan backward compatibility accessor agar frontend tidak perlu diubah:
- `is_aktif` → tetap bisa diakses meski DB pakai `status`
- `nama` → tetap bisa diakses meski DB pakai `nama_dokter`/`nama_layanan`
- `no_rm` → tetap bisa diakses meski DB pakai `no_rekam_medis`
- Role `'user'` → masih support meski DB pakai `'pasien'`
- Dan lain-lain

### Timestamps Standardization
SEMUA model sekarang menggunakan:
```php
const CREATED_AT = 'created_tm';
const UPDATED_AT = 'updated_tm';
const DELETED_AT = 'deleted_tm';
```

### Audit Trail
Hampir semua tabel sekarang punya audit trail:
- `created_by` (bigint unsigned nullable)
- `updated_by` (bigint unsigned nullable)
- `deleted_by` (bigint unsigned nullable)

### Status Fields
Semua `is_aktif` boolean dirubah jadi `status` enum ('aktif', 'nonaktif')

### Foreign Key Changes
- `spesialisasi_id` → `spesialis_id` di tabel dokter & jadwal_dokter
- Banyak relasi baru: penjamin, transaksi, dll

---

## 🎯 TOTAL PERUBAHAN

- ✅ **13 Model Updated**
- ✅ **8 Model Baru Dibuat**
- ✅ **5 Model Lama Dihapus**
- ✅ **Total: 21 Model Aktif**

---

## ⚠️ NEXT STEPS

1. ✅ Task #3: Update Models - **SELESAI**
2. ⏭️ Task #4: Update Controllers
3. ⏭️ Task #5: Update Seeders & Factories
4. ⏭️ Task #6: Testing
