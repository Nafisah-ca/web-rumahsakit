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
        'page_key', 'gambar', 'status', 'updated_by',
    ];

    public const HALAMAN_LIST = [
        'layanan'  => 'Pelayanan',
        'dokter'   => 'Dokter',
        'artikel'  => 'Artikel',
        'event'    => 'Event & Kegiatan',
        'tentang'  => 'Tentang Kami',
        'kontak'   => 'Hubungi Kami',
        'mcu'      => 'Medical Check-Up',
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public static function getForPage(string $key): ?static
    {
        return static::where('page_key', $key)->where('status', 'aktif')->first();
    }
}
