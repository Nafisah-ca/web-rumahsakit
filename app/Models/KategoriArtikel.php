<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriArtikel extends Model
{
    protected $table = 'kategori_artikel';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_kategori', 'deskripsi', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Relasi ───────────────────────────────────────

    public function artikels(): HasMany
    {
        return $this->hasMany(Artikel::class, 'kategori_artikel_id');
    }

    public function artikelPublished(): HasMany
    {
        return $this->artikels()->where('status', 'publish');
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    // Backward compatibility
    public function getNamaAttribute(): string
    {
        return $this->nama_kategori;
    }
}
