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
        'nama_spesialis', 'deskripsi',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

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
