<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'nama_dokter'  => $this->nama_dokter,
            'tipe_dokter'  => $this->tipe_dokter,
            'spesialisasi' => $this->spesialisasi?->nama_spesialis,
            'foto'         => $this->foto ? asset('storage/' . $this->foto) : null,
        ];
    }
}
