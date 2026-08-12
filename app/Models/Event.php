<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    protected $table = 'event';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'judul', 'gambar', 'deskripsi', 'lokasi',
        'tanggal_event', 'waktu_event', 'kuota', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_event' => 'date',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo { return $this->belongsTo(User::class, 'deleted_by'); }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingEvent::class);
    }

    /** Peserta aktif (pending + confirmed) */
    public function pesertaAktif(): HasMany
    {
        return $this->hasMany(BookingEvent::class)->whereIn('status', ['pending', 'confirmed']);
    }

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

    public function getSisaKuotaAttribute(): ?int
    {
        if ($this->kuota === null) return null;
        return max(0, $this->kuota - $this->pesertaAktif()->count());
    }

    public function getKuotaPenuhAttribute(): bool
    {
        if ($this->kuota === null) return false;
        return $this->pesertaAktif()->count() >= $this->kuota;
    }

    // Backward compatibility
    public function getTanggalMulaiAttribute() { return $this->tanggal_event; }
    public function getTanggalSelesaiAttribute() { return $this->tanggal_event; }
}
