<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Promo extends Model
{
    protected $table = 'promo';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'judul', 'thumbnail', 'gambar', 'deskripsi',
        'tanggal_mulai', 'tanggal_selesai', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
    ];

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
