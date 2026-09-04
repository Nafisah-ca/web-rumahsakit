<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Buat entri page_banners untuk setiap kategori layanan yang sudah ada.
 * page_key = 'layanan-{id}' — unik per kategori.
 * Hanya insert jika belum ada.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kategori_layanan') || !Schema::hasTable('page_banners')) {
            return;
        }

        $kategoris = DB::table('kategori_layanan')
            ->whereNull('deleted_tm')
            ->where('status', 'aktif')
            ->get();

        foreach ($kategoris as $kat) {
            $pageKey = 'layanan-' . $kat->id;

            // Skip jika sudah ada
            $exists = DB::table('page_banners')->where('page_key', $pageKey)->exists();
            if ($exists) continue;

            DB::table('page_banners')->insert([
                'page_key'    => $pageKey,
                'nama_halaman'=> 'Pelayanan: ' . $kat->nama_kategori,
                'label_atas'  => 'Layanan Medis',
                'judul'       => $kat->nama_kategori,
                'subjudul'    => $kat->deskripsi
                    ? \Illuminate\Support\Str::limit($kat->deskripsi, 120)
                    : 'Layanan kesehatan ' . $kat->nama_kategori . ' berkualitas tinggi didukung tenaga medis berpengalaman.',
                'gambar'      => null,
                'warna_awal'  => '#00521f',
                'warna_akhir' => '#00b04f',
                'status'      => 'aktif',
                'created_tm'  => now(),
                'updated_tm'  => now(),
            ]);
        }
    }

    public function down(): void
    {
        // Hapus banner yang page_key-nya diawali 'layanan-' dan berupa angka
        DB::table('page_banners')
            ->where('page_key', 'like', 'layanan-%')
            ->whereRaw("SUBSTRING(page_key, 9) REGEXP '^[0-9]+$'")
            ->delete();
    }
};
