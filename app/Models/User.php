<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // Custom timestamp columns sesuai database baru
    const CREATED_AT = 'created_tm';
    const UPDATED_AT = 'updated_tm';

    // Kolom remember_token tidak ada di database baru — nonaktifkan
    protected $rememberTokenName = false;

    protected $fillable = [
        'username', 'email', 'password', 'nama', 'no_hp', 'foto',
        'role', 'status', 'last_login',
        'created_by', 'updated_by', 'deleted_by',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password'   => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    // ─── Relasi ─────────────────────────────────────────────

    public function pasien(): HasOne
    {
        return $this->hasOne(Pasien::class);
    }

    // ─── Role Helpers ───────────────────────────────────────

    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isCms(): bool    { return $this->role === 'cms'; }
    public function isPasien(): bool { return $this->role === 'pasien'; }
    public function isUser(): bool   { return $this->role === 'pasien'; } // backward compat

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin'  => 'Administrator',
            'cms'    => 'Content Manager',
            'pasien' => 'Pasien',
            default  => 'Unknown',
        };
    }

    public function getRoleBadgeColorAttribute(): string
    {
        return match ($this->role) {
            'admin'  => 'red',
            'cms'    => 'blue',
            'pasien' => 'green',
            default  => 'gray',
        };
    }

    // ─── Status Helpers ─────────────────────────────────────

    public function isActive(): bool
    {
        return $this->status === 'aktif';
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'aktif';
    }
}
