<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $table = 'website_setting';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_rumahsakit', 'logo', 'favicon', 'tentang_kami', 'visi', 'misi', 'sejarah',
        'motto', 'sambutan_direktur', 'alamat', 'telepon', 'whatsapp', 'email', 'google_maps',
        'facebook', 'instagram', 'youtube', 'jam_operasional', 'footer', 'copyright',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Helper Methods ───────────────────────────────

    /**
     * Get singleton instance (biasanya cuma ada 1 record)
     */
    public static function getSetting()
    {
        return static::first() ?? new static([
            'nama_rumahsakit' => 'RS Sari Sehat',
            'alamat' => '-',
            'telepon' => '-',
            'email' => '-',
        ]);
    }
}
