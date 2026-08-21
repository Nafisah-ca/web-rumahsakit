<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Akreditasi extends Model
{
    use SoftDeletes;

    protected $table = 'akreditasi';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama', 'tahun', 'deskripsi', 'logo', 'urutan', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif')->orderBy('urutan');
    }

    // ─── Accessors ────────────────────────────────────

    public function getLogoUrlAttribute(): ?string
    {
        if (!$this->logo) return null;
        // Logo dari public/images/ pakai asset(), dari storage/ pakai Storage::url()
        if (str_starts_with($this->logo, 'images/')) {
            return asset($this->logo);
        }
        return \Illuminate\Support\Facades\Storage::url($this->logo);
    }
}
