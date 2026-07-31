<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Banner extends Model
{
    protected $table = 'banner';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'judul', 'gambar', 'deskripsi', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif')->orderBy('id');
    }

    public function scopeHomepage(Builder $query): Builder
    {
        return $query->aktif();
    }

    // Backward compatibility
    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif';
    }
}
