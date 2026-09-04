<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Layanan extends Model
{
    use SoftDeletes;

    protected $table = 'layanan';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'kategori_layanan_id', 'dokter_id', 'nama_layanan', 'deskripsi', 'gambar', 'icon', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

    // ─── Relasi ───────────────────────────────────────

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriLayanan::class, 'kategori_layanan_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    /** Dokter yang ditautkan ke layanan ini, atau dokter aktif yang spesialisasinya cocok dengan nama layanan. */
    public function resolveDokter(): ?Dokter
    {
        if ($this->relationLoaded('dokter') && $this->dokter) {
            return $this->dokter->status === 'aktif' ? $this->dokter : null;
        }

        if ($this->dokter_id && \Illuminate\Support\Facades\Schema::hasColumn('layanan', 'dokter_id')) {
            $dokter = $this->dokter ?: Dokter::find($this->dokter_id);
            return ($dokter && $dokter->status === 'aktif') ? $dokter : null;
        }

        $nama = trim((string) $this->nama_layanan);
        if ($nama === '') {
            return null;
        }

        $spesialis = Spesialisasi::query()
            ->where(function ($q) use ($nama) {
                $q->where('nama_spesialis', $nama)
                  ->orWhere('nama_spesialis', 'like', '%'.$nama.'%')
                  ->orWhereRaw('? LIKE CONCAT("%", nama_spesialis, "%")', [$nama]);
            })
            ->first();

        if (!$spesialis) {
            return null;
        }

        return Dokter::aktif()
            ->where('spesialis_id', $spesialis->id)
            ->orderBy('nama_dokter')
            ->first();
    }

    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo { return $this->belongsTo(User::class, 'deleted_by'); }

    // ─── Scopes ───────────────────────────────────────

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'aktif')->orderBy('id');
    }

    // Backward compatibility
    public function getIsAktifAttribute(): bool { return $this->status === 'aktif'; }
    public function getNamaAttribute(): string   { return $this->nama_layanan; }
}
