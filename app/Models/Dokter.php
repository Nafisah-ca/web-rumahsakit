<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dokter extends Model
{
    protected $table = 'dokter';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'spesialis_id', 'nama_dokter', 'sip', 'email', 'no_hp', 'foto', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Relasi ───────────────────────────────────────

    public function spesialisasi(): BelongsTo
    {
        return $this->belongsTo(Spesialisasi::class, 'spesialis_id');
    }

    // Alias untuk backward compatibility
    public function spesialis(): BelongsTo
    {
        return $this->spesialisasi();
    }

    public function jadwalDokters(): HasMany
    {
        return $this->hasMany(JadwalDokter::class);
    }

    public function jadwalAktif(): HasMany
    {
        return $this->jadwalDokters()->where('status', 'aktif')->orderBy('hari');
    }

    public function janjiTemus(): HasMany
    {
        return $this->hasMany(JanjiTemu::class, 'jadwal_dokter_id')
                    ->join('jadwal_dokter', 'janji_temu.jadwal_dokter_id', '=', 'jadwal_dokter.id')
                    ->where('jadwal_dokter.dokter_id', $this->id);
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    // Backward compatibility
    public function scopeOnline(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function scopeBySpesialisasi(Builder $query, int $spesialisasiId): Builder
    {
        return $query->where('spesialis_id', $spesialisasiId);
    }

    // ─── Accessors ────────────────────────────────────

    /** Nama lengkap - backward compatibility (dulu ada gelar, sekarang tidak) */
    public function getNamaLengkapAttribute(): string
    {
        return $this->nama_dokter;
    }

    // Backward compatibility: property 'nama' → 'nama_dokter'
    public function getNamaAttribute(): string
    {
        return $this->nama_dokter;
    }

    // Backward compatibility: is_aktif boolean
    public function getIsAktifAttribute(): bool
    {
        return $this->status === 'aktif';
    }

    /** Label jadwal singkat untuk ditampilkan di kartu dokter */
    public function getJadwalSingkatAttribute(): string
    {
        $hariMap = [
            'Senin' => 'Sen', 'Selasa' => 'Sel', 'Rabu' => 'Rab',
            'Kamis' => 'Kam', 'Jumat' => 'Jum', 'Sabtu' => 'Sab', 'Minggu' => 'Min'
        ];
        
        $jadwal  = $this->jadwalAktif->map(fn($j) => $hariMap[$j->hari] ?? '?')->unique()->implode(', ');
        $first   = $this->jadwalAktif->first();
        
        if (!$first) return 'Hubungi RS';
        
        $mulai   = substr((string) $first->jam_mulai, 0, 5);
        $selesai = substr((string) $first->jam_selesai, 0, 5);
        
        return $jadwal ? "{$jadwal} {$mulai}–{$selesai}" : 'Hubungi RS';
    }
}
