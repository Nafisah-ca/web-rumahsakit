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
        'page_key',
        'gambar',
        'status',
        'updated_by',
    ];

    public const HALAMAN_LIST = [
        'layanan' => 'Pelayanan',
        'dokter'  => 'Dokter',
        'artikel' => 'Artikel',
        'event'   => 'Event & Kegiatan',
        'tentang' => 'Tentang Kami',
        'kontak'  => 'Hubungi Kami',
        'mcu'     => 'Medical Check-Up',
    ];

    protected $appends = [
        'gambar_url',
    ];

    /**
     * Relasi user yang terakhir mengubah banner.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Ambil banner berdasarkan page_key.
     *
     * Hanya mengambil banner yang statusnya aktif.
     * Jika tidak ditemukan, hasilnya null.
     */
    public static function getForPage(string $key): ?static
    {
        return static::where('page_key', $key)
            ->where('status', 'aktif')
            ->first();
    }

    /**
     * Accessor URL gambar.
     *
     * Mendukung:
     * - Base64 Data URL
     * - HTTP URL
     * - HTTPS URL
     * - Path file dari Laravel Storage
     */
    public function getGambarUrlAttribute(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        // Jika gambar sudah berupa Base64 Data URL
        if (str_starts_with($this->gambar, 'data:image/')) {
            return $this->gambar;
        }

        // Jika gambar sudah berupa URL lengkap
        if (
            str_starts_with($this->gambar, 'http://') ||
            str_starts_with($this->gambar, 'https://')
        ) {
            return $this->gambar;
        }

        // Jika gambar berupa path Storage Laravel
        return Storage::url($this->gambar);
    }
}