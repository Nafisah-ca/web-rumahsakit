<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendaftaranMcu extends Model
{
    protected $table = 'pendaftaran_mcu';

    protected $fillable = [
        'kode_pendaftaran', 'paket', 'nama_lengkap', 'nik',
        'no_hp', 'email', 'jenis_kelamin', 'tanggal_lahir',
        'alamat', 'tanggal_pilihan', 'sesi', 'catatan',
        'status', 'user_id',
    ];

    protected $casts = [
        'tanggal_lahir'   => 'date',
        'tanggal_pilihan' => 'date',
    ];

    // Paket label
    public static array $pakets = [
        'basic'     => ['label' => 'Basic',     'harga' => 'Rp 450.000',   'color' => 'green',  'icon' => 'fa-leaf'],
        'standard'  => ['label' => 'Standard',  'harga' => 'Rp 850.000',   'color' => 'blue',   'icon' => 'fa-star'],
        'executive' => ['label' => 'Executive', 'harga' => 'Rp 1.750.000', 'color' => 'purple', 'icon' => 'fa-crown'],
        'corporate' => ['label' => 'Corporate', 'harga' => 'Custom',       'color' => 'orange', 'icon' => 'fa-building'],
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getPaketLabelAttribute(): string
    {
        return static::$pakets[$this->paket]['label'] ?? ucfirst($this->paket);
    }

    public function getPaketColorAttribute(): string
    {
        return static::$pakets[$this->paket]['color'] ?? 'gray';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => 'Menunggu',
            'dikonfirmasi' => 'Dikonfirmasi',
            'selesai'      => 'Selesai',
            'dibatalkan'   => 'Dibatalkan',
            default        => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'menunggu'     => 'amber',
            'dikonfirmasi' => 'blue',
            'selesai'      => 'green',
            'dibatalkan'   => 'red',
            default        => 'gray',
        };
    }

    // Generate kode unik: MCU-YYYYMMDD-XXXX
    public static function generateKode(): string
    {
        $prefix = 'MCU-' . now()->format('Ymd') . '-';
        $last   = static::where('kode_pendaftaran', 'like', $prefix . '%')
                        ->orderByDesc('id')->value('kode_pendaftaran');
        $seq    = $last ? ((int) substr($last, -4)) + 1 : 1;
        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
