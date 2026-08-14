<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PageBanner extends Model
{
    protected $table = 'page_banners';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';

    protected $fillable = [
        'page_key', 'nama_halaman', 'label_atas', 'judul', 'subjudul',
        'gambar', 'warna_awal', 'warna_akhir', 'status', 'updated_by',
    ];

    protected $appends = ['gambar_url'];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Accessor URL Gambar (Base64 data URL, HTTP URL, atau URL Storage)
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        if (str_starts_with($this->gambar, 'data:image/') ||
            str_starts_with($this->gambar, 'http://') ||
            str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        return Storage::url($this->gambar);
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
            'nama_halaman'=> ucfirst($key),
            'label_atas'  => 'RS Sari Sehat',
            'judul'       => 'Selamat Datang',
            'subjudul'    => 'Melayani dengan kasih sayang.',
            'warna_awal'  => '#00521f',
            'warna_akhir' => '#00b04f',
            'status'      => 'aktif',
        ]);
    }
}