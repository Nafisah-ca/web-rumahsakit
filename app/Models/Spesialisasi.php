<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Spesialisasi extends Model
{
    protected $table = 'spesialis';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_spesialis', 'deskripsi', 'icon', 'warna', 'estimasi_menit',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'estimasi_menit' => 'integer',
    ];

    /** Pilihan warna Tailwind yang tersedia untuk live antrian */
    public const WARNA_OPTIONS = [
        'blue'   => 'Biru',
        'green'  => 'Hijau',
        'red'    => 'Merah',
        'indigo' => 'Indigo',
        'purple' => 'Ungu',
        'orange' => 'Oranye',
        'pink'   => 'Pink',
        'teal'   => 'Teal',
        'yellow' => 'Kuning',
        'gray'   => 'Abu-abu',
    ];

    /** Accessor: label estimasi tunggu siap pakai */
    public function getEstimasiLabelAttribute(): string
    {
        $menit = $this->estimasi_menit ?? 15;
        return "±{$menit} menit";
    }

    // ─── Relasi ───────────────────────────────────────

    public function dokters(): HasMany
    {
        return $this->hasMany(Dokter::class, 'spesialis_id');
    }

    public function dokterAktif(): HasMany
    {
        return $this->dokters()->where('status', 'aktif');
    }

    public function jadwalDokters(): HasMany
    {
        return $this->hasMany(JadwalDokter::class, 'spesialis_id');
    }

    // ─── Scopes ───────────────────────────────────────

    // Backward compatibility: aktif() masih bisa dipanggil
    public function scopeAktif(Builder $query): Builder
    {
        return $query->orderBy('nama_spesialis');
    }
}
