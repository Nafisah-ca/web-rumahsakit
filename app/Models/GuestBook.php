<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class GuestBook extends Model
{
    protected $table = 'guest_book';

    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';
    const DELETED_AT = 'deleted_tm';

    protected $fillable = [
        'nama', 'email', 'no_hp', 'pesan', 'status',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $casts = [];

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
