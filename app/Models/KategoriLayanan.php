<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

class KategoriLayanan extends Model
{
    use SoftDeletes;

    protected $table = 'kategori_layanan';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama_kategori', 'icon', 'deskripsi', 'urutan', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function layanans(): HasMany
    {
        return $this->hasMany(Layanan::class, 'kategori_layanan_id');
    }

    public function layanansAktif(): HasMany
    {
        return $this->layanans()->where('status', 'aktif')->orderBy('id');
    }

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo { return $this->belongsTo(User::class, 'deleted_by'); }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        $q = $query->where('status', 'aktif');

        // Gunakan urutan hanya jika kolom sudah ada di DB
        if (Schema::hasColumn('kategori_layanan', 'urutan')) {
            $q->orderBy('urutan');
        }

        return $q->orderBy('nama_kategori');
    }
}
