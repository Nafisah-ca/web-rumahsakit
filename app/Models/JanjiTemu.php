<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JanjiTemu extends Model
{
    use SoftDeletes;

    protected $table = 'janji_temu';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'pasien_id', 'jadwal_dokter_id',
        'tanggal_booking', 'keluhan', 'nomor_antrian', 'status',
        'kode_booking',
        'alasan_pembatalan', 'tanggal_pembatalan', 'dibatalkan_oleh',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_booking'    => 'date',
        'nomor_antrian'      => 'integer',
        'tanggal_pembatalan' => 'datetime',
    ];

    /** Status-to-label + warna badge untuk UI */
    public const STATUS_CONFIG = [
        'pending'   => ['label' => 'Menunggu',     'color' => 'yellow', 'old' => 'menunggu'],
        'approved'  => ['label' => 'Dikonfirmasi', 'color' => 'blue',   'old' => 'dikonfirmasi'],
        'completed' => ['label' => 'Selesai',      'color' => 'green',  'old' => 'selesai'],
        'cancelled' => ['label' => 'Dibatalkan',   'color' => 'red',    'old' => 'dibatalkan'],
    ];

    /** Mapping old status to new status */
    public const STATUS_OLD_TO_NEW = [
        'menunggu'     => 'pending',
        'dikonfirmasi' => 'approved',
        'hadir'        => 'approved',
        'selesai'      => 'completed',
        'dibatalkan'   => 'cancelled',
        'tidak_hadir'  => 'cancelled',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class)->withTrashed();
    }

    public function jadwalDokter(): BelongsTo
    {
        return $this->belongsTo(JadwalDokter::class);
    }

    // Backward compatibility: akses dokter langsung
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'jadwal_dokter_id')
                    ->join('jadwal_dokter', 'dokter.id', '=', 'jadwal_dokter.dokter_id')
                    ->select('dokter.*');
    }

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class, 'id', 'janji_temu_id');
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('tanggal_booking', today());
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->whereIn('status', ['pending', 'approved']);
    }

    public function scopeBulan(Builder $query, int $bulan, int $tahun): Builder
    {
        return $query->whereMonth('tanggal_booking', $bulan)
                     ->whereYear('tanggal_booking', $tahun);
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

    // Backward compatibility: tanggal_kunjungan → tanggal_booking
    public function getTanggalKunjunganAttribute()
    {
        return $this->tanggal_booking;
    }

    // Backward compatibility: kode_booking — pakai nilai DB jika sudah ada, fallback generate dari tanggal + nomor urut hari itu
    public function getKodeBookingAttribute(): string
    {
        // Jika sudah tersimpan di DB, gunakan langsung
        if (!empty($this->attributes['kode_booking'])) {
            return $this->attributes['kode_booking'];
        }

        // Fallback: hitung nomor urut booking di tanggal yang sama
        $tanggal = $this->tanggal_booking?->format('Ymd') ?? now()->format('Ymd');
        $urut = static::withTrashed()
            ->whereDate('tanggal_booking', $this->tanggal_booking ?? now()->toDateString())
            ->where('id', '<=', $this->id)
            ->count();

        return 'RS-' . $tanggal . '-' . str_pad($urut, 5, '0', STR_PAD_LEFT);
    }
}
