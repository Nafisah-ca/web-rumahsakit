<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use SoftDeletes;
    protected $table = 'transaksi';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'janji_temu_id', 'pasien_id', 'penjamin_id', 'kode_transaksi',
        'total_biaya', 'metode_pembayaran', 'status_pembayaran', 'status_transaksi',
        'tanggal_transaksi', 'keterangan',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_biaya'       => 'decimal:2',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function janjiTemu(): BelongsTo
    {
        return $this->belongsTo(JanjiTemu::class);
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function penjamin(): BelongsTo
    {
        return $this->belongsTo(Penjamin::class);
    }

    public function detailTransaksis(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // ─── Scopes ───────────────────────────────────────

    public function scopeLunas(Builder $query): Builder
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeBelumBayar(Builder $query): Builder
    {
        return $query->whereIn('status_pembayaran', ['belum_bayar', 'menunggu_verifikasi']);
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status_transaksi', 'selesai');
    }

    // ─── Accessors ────────────────────────────────────

    public function getStatusPembayaranLabelAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'belum_bayar'           => 'Belum Bayar',
            'menunggu_verifikasi'   => 'Menunggu Verifikasi',
            'lunas'                 => 'Lunas',
            'gagal'                 => 'Gagal',
            default                 => $this->status_pembayaran,
        };
    }

    public function getStatusTransaksiLabelAttribute(): string
    {
        return match ($this->status_transaksi) {
            'menunggu'    => 'Menunggu',
            'diproses'    => 'Diproses',
            'selesai'     => 'Selesai',
            'dibatalkan'  => 'Dibatalkan',
            default       => $this->status_transaksi,
        };
    }
}
