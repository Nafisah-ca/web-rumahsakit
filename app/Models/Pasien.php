<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    use SoftDeletes;

    protected $table = 'pasien';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'user_id', 'no_rekam_medis', 'nik', 'jenis_kelamin',
        'tempat_lahir', 'tanggal_lahir', 'alamat', 'golongan_darah',
        'agama', 'pekerjaan', 'penjamin_id', 'nomor_penjamin',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function penjamin(): BelongsTo
    {
        return $this->belongsTo(Penjamin::class);
    }

    public function janjiTemus(): HasMany
    {
        return $this->hasMany(JanjiTemu::class)->orderByDesc('tanggal_booking');
    }

    public function janjiAktif(): HasMany
    {
        return $this->janjiTemus()
                    ->whereIn('status', ['pending', 'approved'])
                    ->where('tanggal_booking', '>=', today());
    }

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    // ─── Accessors ────────────────────────────────────

    public function getUmurAttribute(): ?int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return match ($this->jenis_kelamin) {
            'L' => 'Laki-laki',
            'P' => 'Perempuan',
            default => '-',
        };
    }

    // Backward compatibility: no_rm → no_rekam_medis
    public function getNoRmAttribute(): string
    {
        return $this->no_rekam_medis;
    }

    // Backward compatibility: nama_lengkap dari user
    public function getNamaLengkapAttribute(): string
    {
        return $this->user?->nama ?? '-';
    }

    // ─── Helper Methods ───────────────────────────────

    /**
     * Generate nomor rekam medis unik berikutnya.
     * Menggunakan withTrashed() agar nomor pasien nonaktif tidak pernah dipakai ulang.
     * Format: 8 digit angka, tanpa prefix.
     */
    public static function generateNoRekamMedis(): string
    {
        $last = static::withTrashed()
            ->orderByDesc('id')
            ->value('no_rekam_medis');

        // Ambil bagian angka dari akhir string (handle format lama 'RMYYYYnnnnn' maupun format baru '00000001')
        $lastNumber = (int) preg_replace('/\D/', '', $last ?? '0');

        return str_pad($lastNumber + 1, 8, '0', STR_PAD_LEFT);
    }
}
