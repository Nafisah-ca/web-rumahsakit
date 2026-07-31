<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Galeri extends Model
{
    protected $table = 'galeri';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'kategori_galeri_id', 'judul', 'gambar', 'deskripsi', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Relasi ───────────────────────────────────────

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriGaleri::class, 'kategori_galeri_id');
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif')->orderBy('id');
    }

    public function scopeFoto(Builder $query): Builder
    {
        return $query->aktif();
    }

    // Backward compatibility
    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif';
    }

    public function getFileAttribute(): ?string
    {
        return $this->gambar;
    }
}
