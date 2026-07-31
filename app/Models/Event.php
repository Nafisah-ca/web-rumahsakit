<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Event extends Model
{
    protected $table = 'event';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'judul', 'thumbnail', 'gambar', 'deskripsi', 'lokasi',
        'tanggal_event', 'waktu_event', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_event' => 'date',
    ];

    // ─── Scopes ───────────────────────────────────────

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'aktif')->orderBy('tanggal_event');
    }

    public function scopeMendatang(Builder $query): Builder
    {
        return $query->published()->where('tanggal_event', '>=', now());
    }

    // ─── Accessors ────────────────────────────────────

    public function getTanggalFormatAttribute(): string
    {
        return $this->tanggal_event?->translatedFormat('d M Y') ?? '-';
    }

    // Backward compatibility
    public function getTanggalMulaiAttribute()
    {
        return $this->tanggal_event;
    }

    public function getTanggalSelesaiAttribute()
    {
        return $this->tanggal_event;
    }
}
