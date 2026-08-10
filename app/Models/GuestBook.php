<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuestBook extends Model
{
    use SoftDeletes;

    protected $table = 'guest_book';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama', 'email', 'no_hp', 'pesan', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    // ─── Relasi ───────────────────────────────────────

    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function deletedBy(): BelongsTo { return $this->belongsTo(User::class, 'deleted_by'); }

    // ─── Scopes ───────────────────────────────────────

    public function scopeBaru(Builder $query): Builder
    {
        return $query->where('status', 'baru')->orderByDesc('created_tm');
    }

    public function scopeDibaca(Builder $query): Builder
    {
        return $query->where('status', 'dibaca');
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'selesai');
    }

    // ─── Accessors ────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru'    => 'Baru',
            'dibaca'  => 'Dibaca',
            'selesai' => 'Selesai',
            default   => $this->status,
        };
    }
}
