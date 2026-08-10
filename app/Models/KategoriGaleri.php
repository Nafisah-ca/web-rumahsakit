<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriGaleri extends Model
{
    use SoftDeletes;

    protected $table = 'kategori_galeri';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_kategori', 'deskripsi', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function galeris(): HasMany
    {
        return $this->hasMany(Galeri::class);
    }

    public function galeriAktif(): HasMany
    {
        return $this->galeris()->where('status', 'aktif');
    }

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo { return $this->belongsTo(User::class, 'deleted_by'); }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }
}
