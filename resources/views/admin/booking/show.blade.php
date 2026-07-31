@extends('layouts.admin')
@php $pageTitle = 'Detail Booking'; $breadcrumb = 'Admin / Booking / #' . $janjiTemu->kode_booking; @endphp
@section('content')
<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start">

    {{-- Info Booking --}}
    <div class="card card-body">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:24px">
            <div>
                <p style="font-size:11px;color:#94a3b8;font-weight:600;margin-bottom:4px">KODE BOOKING</p>
                <p style="font-size:20px;font-weight:800;color:#0f172a;font-family:monospace">{{ $janjiTemu->kode_booking }}</p>
                <p style="font-size:12px;color:#94a3b8;margin-top:4px">Dibuat {{ $janjiTemu->created_tm?->format('d M Y H:i') }}</p>
            </div>
            @php
            $bconf = [
                'pending'   => ['Menunggu',     'badge-amber'],
                'approved'  => ['Dikonfirmasi', 'badge-blue'],
                'completed' => ['Selesai',      'badge-green'],
                'cancelled' => ['Dibatalkan',   'badge-red'],
            ];
            [$blbl, $bcls] = $bconf[$janjiTemu->status] ?? ['–', 'badge-slate'];
            @endphp
            <span class="badge {{ $bcls }}" style="font-size:13px;padding:6px 14px">{{ $blbl }}</span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
            @foreach([
                ['fas fa-user',         'Pasien',           $janjiTemu->pasien?->user?->nama ?? $janjiTemu->pasien?->nama_lengkap ?? '-'],
                ['fas fa-id-card',      'No. Rekam Medis',  $janjiTemu->pasien?->no_rekam_medis ?? '-'],
                ['fas fa-phone',        'Telepon Pasien',   $janjiTemu->pasien?->user?->no_hp ?? '-'],
                ['fas fa-id-badge',     'NIK',              $janjiTemu->pasien?->nik ?? '-'],
                ['fas fa-user-doctor',  'Dokter',           $janjiTemu->jadwalDokter?->dokter?->nama_dokter ?? '-'],
                ['fas fa-stethoscope',  'Spesialisasi',     $janjiTemu->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis ?? '-'],
                ['fas fa-calendar',     'Tanggal Booking',  $janjiTemu->tanggal_booking?->format('d M Y') ?? '-'],
                ['fas fa-clock',        'Jam Praktik',      $janjiTemu->jadwalDokter ? substr($janjiTemu->jadwalDokter->jam_mulai,0,5).' – '.substr($janjiTemu->jadwalDokter->jam_selesai,0,5).' WIB' : '-'],
                ['fas fa-hashtag',      'No. Antrian',      $janjiTemu->nomor_antrian ?? '-'],
                ['fas fa-calendar-day', 'Hari Praktek',     $janjiTemu->jadwalDokter?->hari ?? '-'],
            ] as [$ico, $lbl, $val])
            <div style="display:flex;align-items:flex-start;gap:10px;background:#f8fafc;border-radius:10px;padding:12px">
                <i class="{{ $ico }}" style="color:#94a3b8;width:14px;text-align:center;margin-top:2px;flex-shrink:0"></i>
                <div>
                    <p style="font-size:10px;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:.04em">{{ $lbl }}</p>
                    <p style="font-size:13px;font-weight:600;color:#0f172a;margin-top:2px">{{ $val }}</p>
                </div>
            </div>
            @endforeach
        </div>

        @if($janjiTemu->keluhan)
        <div style="margin-top:16px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px">
            <p style="font-size:11px;font-weight:700;color:#92400e;margin-bottom:6px">KELUHAN PASIEN</p>
            <p style="font-size:13px;color:#78350f;line-height:1.6">{{ $janjiTemu->keluhan }}</p>
        </div>
        @endif
    </div>

    {{-- Sidebar Update Status --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px">Update Status</p>
            <form method="POST" action="{{ route('admin.booking.status', $janjiTemu) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="pending"   {{ $janjiTemu->status=='pending'?'selected':'' }}>Menunggu</option>
                        <option value="approved"  {{ $janjiTemu->status=='approved'?'selected':'' }}>Dikonfirmasi</option>
                        <option value="completed" {{ $janjiTemu->status=='completed'?'selected':'' }}>Selesai</option>
                        <option value="cancelled" {{ $janjiTemu->status=='cancelled'?'selected':'' }}>Dibatalkan</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    <i class="fas fa-save"></i> Simpan Status
                </button>
            </form>
        </div>

        {{-- Tombol Buat / Lihat Transaksi --}}
        @if(in_array($janjiTemu->status, ['approved','completed']))
        @php $sudahAdaTrx = \App\Models\Transaksi::where('janji_temu_id',$janjiTemu->id)->exists(); @endphp
        @if(!$sudahAdaTrx)
        <a href="{{ route('admin.transaksi.create') }}?janji_temu_id={{ $janjiTemu->id }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;background:#7c3aed;color:#fff;font-weight:700;font-size:13px;padding:12px;border-radius:10px;text-decoration:none;transition:background .2s"
           onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
            <i class="fas fa-receipt"></i> Buat Transaksi
        </a>
        @else
        @php $trx = \App\Models\Transaksi::where('janji_temu_id',$janjiTemu->id)->first(); @endphp
        <a href="{{ route('admin.transaksi.show', $trx) }}"
           style="display:flex;align-items:center;justify-content:center;gap:8px;background:#16a34a;color:#fff;font-weight:700;font-size:13px;padding:12px;border-radius:10px;text-decoration:none">
            <i class="fas fa-receipt"></i> Lihat Transaksi
        </a>
        @endif
        @endif

        <a href="{{ route('admin.booking') }}" class="btn btn-secondary" style="justify-content:center">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

        @if($janjiTemu->pasien)
        <a href="{{ route('admin.pasien.show', $janjiTemu->pasien) }}" class="btn btn-secondary" style="justify-content:center">
            <i class="fas fa-user"></i> Lihat Profil Pasien
        </a>
        @endif
    </div>
</div>
@endsection
