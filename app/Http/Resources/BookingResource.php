<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'kode_booking'    => $this->kode_booking,
            'tanggal_booking' => $this->tanggal_booking?->format('Y-m-d'),
            'nomor_antrian'   => $this->nomor_antrian,
            'keluhan'         => $this->keluhan,
            'status'          => $this->status,
            'status_label'    => $this->status_label,
            'dokter'          => [
                'id'           => $this->jadwalDokter?->dokter?->id,
                'nama_dokter'  => $this->jadwalDokter?->dokter?->nama_dokter,
                'spesialisasi' => $this->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis,
            ],
            'jadwal' => [
                'hari'        => $this->jadwalDokter?->hari,
                'jam_mulai'   => $this->jadwalDokter ? substr($this->jadwalDokter->jam_mulai, 0, 5) : null,
                'jam_selesai' => $this->jadwalDokter ? substr($this->jadwalDokter->jam_selesai, 0, 5) : null,
            ],
        ];
    }
}
