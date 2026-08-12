<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingEvent extends Model
{
    protected $table = 'booking_event';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';

    protected $fillable = [
        'event_id', 'pasien_id', 'kode_booking',
        'status', 'catatan',
        'created_by', 'updated_by',
    ];

    public const STATUS_CONFIG = [
        'pending'   => ['label' => 'Menunggu',     'color' => 'amber'],
        'confirmed' => ['label' => 'Dikonfirmasi', 'color' => 'green'],
        'cancelled' => ['label' => 'Dibatalkan',   'color' => 'red'],
    ];

    // ─── Relasi ───────────────────────────────────────

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    // ─── Accessors ────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_CONFIG[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_CONFIG[$this->status]['color'] ?? 'gray';
    }

    // ─── Helpers ──────────────────────────────────────

    public static function generateKode(): string
    {
        do {
            $kode = 'EVT-' . strtoupper(\Illuminate\Support\Str::random(8));
        } while (static::where('kode_booking', $kode)->exists());

        return $kode;
    }
}
