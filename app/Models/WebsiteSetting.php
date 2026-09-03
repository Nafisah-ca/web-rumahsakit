<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebsiteSetting extends Model
{
    use SoftDeletes;

    protected $table = 'website_setting';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_rumahsakit', 'logo', 'favicon', 'tentang_kami', 'visi', 'misi', 'sejarah',
        'motto', 'sambutan_direktur', 'nama_direktur', 'foto_direktur',
        'alamat', 'telepon', 'email', 'google_maps',
        'facebook', 'instagram', 'youtube', 'jam_operasional', 'jadwal_sholat',
        'estimasi_antrian',
        'jumlah_spesialisasi', 'jumlah_mitra_asuransi',
        'footer', 'copyright', 'whatsapp', 'privacy_policy', 'syarat_ketentuan',
        'created_by', 'updated_by', 'deleted_by',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo { return $this->belongsTo(User::class, 'deleted_by'); }

    // ─── Helper ───────────────────────────────────────

    public static function getSetting(): static
    {
        return static::first() ?? new static([
            'nama_rumahsakit' => 'RS Sari Sehat',
            'alamat'  => '-',
            'telepon' => '-',
            'email'   => '-',
        ]);
    }

    public function getJadwalSholatConfigAttribute(): array
    {
        return \App\Services\JadwalSholatService::getSettingConfig();
    }
}
