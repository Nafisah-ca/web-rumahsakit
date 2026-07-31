<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'transaksi_id', 'nama_biaya', 'qty', 'harga', 'subtotal', 'keterangan',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [
        'qty'      => 'integer',
        'harga'    => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class);
    }
}
