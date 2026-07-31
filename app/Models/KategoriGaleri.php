<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriGaleri extends Model
{
    protected $table = 'kategori_galeri';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_kategori', 'deskripsi', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Relasi ───────────────────────────────────────

    public function galeris(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }

    public function galeriAktif(): HasMany
    {
        return $this->galeris()->where('status', 'aktif');
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }
}
