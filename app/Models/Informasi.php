<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Informasi extends Model
{
    protected $table = 'informasi';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'judul', 'slug', 'thumbnail', 'gambar', 'isi', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

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
}
