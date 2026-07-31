<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokter';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'dokter_id', 'spesialis_id', 'penjamin_id',
        'tanggal_praktek', 'hari', 'jam_mulai', 'jam_selesai', 'kuota', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_praktek' => 'date',
        'kuota'           => 'integer',
    ];

    /** Map string hari ke label Indonesia - DB baru menggunakan enum string */
    public const HARI_MAP = [
        'Senin'  => 'Senin',
        'Selasa' => 'Selasa',
        'Rabu'   => 'Rabu',
        'Kamis'  => 'Kamis',
        'Jumat'  => 'Jumat',
        'Sabtu'  => 'Sabtu',
        'Minggu' => 'Minggu',
    ];

    /** Map integer ke string hari untuk backward compatibility */
    public const HARI_INT_TO_STRING = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
        4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function spesialisasi(): BelongsTo
    {
        return $this->belongsTo(Spesialisasi::class, 'spesialis_id');
    }

    public function penjamin(): BelongsTo
    {
        return $this->belongsTo(Penjamin::class);
    }

    public function janjiTemus(): HasMany
    {
        return $this->hasMany(JanjiTemu::class);
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeHari(Builder $query, int|string $hari): Builder
    {
        // Support both integer (old) and string (new)
        if (is_int($hari)) {
            $hari = self::HARI_INT_TO_STRING[$hari] ?? $hari;
        }
        return $query->where('hari', $hari);
    }

    // ─── Accessors ────────────────────────────────────

    public function getNamaHariAttribute(): string
    {
        return $this->hari; // Sudah string di DB baru
    }

    public function getJamRangeAttribute(): string
    {
        $mulai   = substr((string) $this->jam_mulai, 0, 5);
        $selesai = substr((string) $this->jam_selesai, 0, 5);
        return "{$mulai}–{$selesai}";
    }

    // Backward compatibility: is_aktif boolean
    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif';
    }
}
