<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Promo extends Model
{
    use SoftDeletes;

    protected $table = 'promo';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'judul', 'gambar', 'deskripsi',
        'tanggal_mulai', 'tanggal_selesai', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo { return $this->belongsTo(User::class, 'deleted_by'); }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif')
                     ->where(function ($q) {
                         $q->whereNull('tanggal_selesai')
                           ->orWhere('tanggal_selesai', '>=', Carbon::today());
                     })
                     ->orderBy('id');
    }

    /**
     * Promo yang sudah expired: status aktif tapi tanggal_selesai sudah lewat.
     * Ini yang "menghilang" dari portal tanpa disadari admin.
     */
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'aktif')
                     ->whereNotNull('tanggal_selesai')
                     ->where('tanggal_selesai', '<', Carbon::today())
                     ->orderByDesc('tanggal_selesai');
    }

    // ─── Accessors ────────────────────────────────────

    public function getBerakhirFormatAttribute(): string
    {
        return $this->tanggal_selesai
            ? $this->tanggal_selesai->translatedFormat('d M Y')
            : 'Tidak terbatas';
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->tanggal_selesai && $this->tanggal_selesai->isPast();
    }

    // Backward compatibility
    public function getTanggalBerakhirAttribute()
    {
        return $this->tanggal_selesai;
    }
}
