@extends('layouts.admin')
@php $pageTitle = 'Detail Pasien'; $breadcrumb = 'Admin / Pasien / ' . ($pasien->user?->nama ?? 'Pasien'); @endphp
@section('content')
<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start">
    {{-- Kartu Profil --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body" style="text-align:center">
            <div class="avatar avatar-lg" style="background:#dcfce7;color:#166534;margin:0 auto 14px;font-size:24px">
                {{ strtoupper(substr($pasien->user?->nama ?? '?', 0, 1)) }}
            </div>
            <p style="font-size:17px;font-weight:800;color:#0f172a">{{ $pasien->user?->nama ?? '-' }}</p>
            <p style="font-size:12px;color:#16a34a;font-weight:700;margin-top:4px">{{ $pasien->no_rekam_medis ?? '-' }}</p>

            <div style="margin-top:20px;display:flex;flex-direction:column;gap:10px;text-align:left">
                @foreach([
                    ['fas fa-id-card',     'NIK',          $pasien->nik ?? '-'],
                    ['fas fa-venus-mars',  'Jenis Kelamin',$pasien->jenis_kelamin_label],
                    ['fas fa-birthday-cake','Umur',        $pasien->umur ? $pasien->umur.' tahun' : '-'],
                    ['fas fa-phone',       'Telepon',      $pasien->user?->no_hp ?? '-'],
                    ['fas fa-tint',        'Gol. Darah',   $pasien->golongan_darah ?? '-'],
                    ['fas fa-envelope',    'Email',        $pasien->user?->email ?? '-'],
                    ['fas fa-map-marker-alt','Alamat',     Str::limit($pasien->alamat ?? '-', 60)],
                ] as [$ico,$lbl,$val])
                <div style="display:flex;align-items:flex-start;gap:10px">
                    <i class="{{ $ico }}" style="width:14px;text-align:center;color:#94a3b8;font-size:12px;flex-shrink:0;margin-top:3px"></i>
                    <div>
                        <p style="font-size:10px;color:#94a3b8;font-weight:600">{{ $lbl }}</p>
                        <p style="font-size:12px;color:#334155;font-weight:600">{{ $val }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top:20px;display:flex;gap:8px">
                <a href="{{ route('admin.pasien.edit', $pasien) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <a href="{{ route('admin.pasien') }}" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center">Kembali</a>
            </div>
        </div>

        @if($pasien->penjamin)
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:12px"><i class="fas fa-shield-halved" style="color:#2563eb;margin-right:6px"></i>Penjamin</p>
            <p style="font-size:12px;color:#334155;font-weight:600">{{ $pasien->penjamin->nama_penjamin }}</p>
            <p style="font-size:11px;color:#94a3b8">{{ $pasien->penjamin->tipePenjamin?->nama_tipe }}</p>
            @if($pasien->nomor_penjamin)
            <p style="font-size:12px;color:#334155;font-family:monospace;margin-top:4px">{{ $pasien->nomor_penjamin }}</p>
            @endif
        </div>
        @endif
    </div>

    {{-- Riwayat Booking --}}
    <div class="card">
        <div class="card-header">
            <h3>Riwayat Booking</h3>
            <span class="badge badge-blue">{{ $pasien->janjiTemus->count() }} total</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Dokter</th><th>Tanggal Booking</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($pasien->janjiTemus as $jt)
                    @php
                    $bc = ['pending'=>'badge-amber','approved'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'][$jt->status] ?? 'badge-slate';
                    $bl = ['pending'=>'Menunggu','approved'=>'Dikonfirmasi','completed'=>'Selesai','cancelled'=>'Dibatalkan'][$jt->status] ?? $jt->status;
                    @endphp
                    <tr>
                        <td><span class="code-tag">{{ $jt->kode_booking }}</span></td>
                        <td style="font-weight:600;font-size:13px">{{ $jt->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</td>
                        <td style="color:#64748b;font-size:12px">{{ $jt->tanggal_booking?->format('d M Y') }}</td>
                        <td><span class="badge {{ $bc }}">{{ $bl }}</span></td>
                        <td><a href="{{ route('admin.booking.show',$jt) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>Belum ada riwayat booking</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
