<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBanner extends Model
{
    protected $table = 'page_banners';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';

    protected $fillable = [
        'page_key', 'nama_halaman', 'label_atas', 'judul', 'subjudul',
        'gambar', 'warna_awal', 'warna_akhir', 'status', 'updated_by',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Ambil banner berdasarkan page_key.
     * Jika tidak ada / nonaktif, kembalikan objek default.
     */
    public static function getForPage(string $key): static
    {
        $banner = static::where('page_key', $key)->where('status', 'aktif')->first();

        if ($banner) return $banner;

        // Default fallback
        return new static([
            'page_key'    => $key,
            'label_atas'  => 'RS Sari Sehat',
            'judul'       => 'Selamat Datang',
            'subjudul'    => 'Melayani dengan kasih sayang.',
            'warna_awal'  => '#00521f',
            'warna_akhir' => '#00b04f',
        ]);
    }
}
