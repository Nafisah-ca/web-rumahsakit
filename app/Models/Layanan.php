<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $table = 'layanan';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_layanan', 'deskripsi', 'gambar', 'icon', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Relasi ───────────────────────────────────────

    // Note: Relasi ke janji_temu dihapus karena layanan_id tidak ada di tabel janji_temu baru

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif')->orderBy('id');
    }

    // Backward compatibility
    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif';
    }

    public function getNamaAttribute(): string
    {
        return $this->nama_layanan;
    }
}
