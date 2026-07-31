<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Artikel extends Model
{
    protected $table = 'artikel';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'kategori_artikel_id', 'judul', 'slug', 'thumbnail', 'gambar', 'isi', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Relasi ───────────────────────────────────────

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriArtikel::class, 'kategori_artikel_id');
    }

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'publish')->orderByDesc('created_tm');
    }

    public function scopeTerbaru(Builder $query, int $limit = 6): Builder
    {
        return $query->published()->limit($limit);
    }

    // ─── Accessors ────────────────────────────────────

    public function getTanggalFormatAttribute(): string
    {
        return $this->created_tm ? $this->created_tm->translatedFormat('d M Y') : '-';
    }

    // Backward compatibility
    public function getKontenAttribute(): string
    {
        return $this->isi ?? '';
    }

    public function getGambarUtamaAttribute(): ?string
    {
        return $this->gambar;
    }
}
