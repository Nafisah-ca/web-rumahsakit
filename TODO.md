# TODO

- [x] Cabang tidak dipakai (hapus link/ketergantungan view agar tidak memanggil route `cabang`).
- [x] Hapus data cabang di halaman kontak (peta/maps & daftar cabang) agar hanya 1 lokasi.
- [ ] (Optional) Hapus route/controller/model Cabang dari sisi admin/cms jika memang tidak digunakan lagi.

- [ ] Pastikan tidak ada lagi pemanggilan `route('cabang')` di seluruh blade/php.
- [ ] Clear cache Laravel (route/view/config) jika error masih muncul.

- [x] Rapihkan tampilan data dokter agar tidak muncul field mentah/JSON saat `$dokterList` berisi model (bukan array transform).
- [x] Batasi dokter per spesialis (max 3) agar tidak terlalu banyak.


