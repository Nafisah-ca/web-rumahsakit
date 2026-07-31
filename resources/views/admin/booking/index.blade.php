@extends('layouts.admin')
@php $pageTitle = 'Booking & Janji Temu'; $breadcrumb = 'Admin / Booking'; @endphp
@section('content')

{{-- Filter --}}
<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end">
            <div style="flex:1;min-width:160px">
                <label class="form-label" style="margin-bottom:4px">Nama Pasien</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pasien..." class="form-input">
            </div>
            <div style="flex:1;min-width:140px">
                <label class="form-label" style="margin-bottom:4px">Status</label>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="pending"   {{ request('status')=='pending'   ? 'selected':'' }}>Menunggu</option>
                    <option value="approved"  {{ request('status')=='approved'  ? 'selected':'' }}>Dikonfirmasi</option>
                    <option value="completed" {{ request('status')=='completed' ? 'selected':'' }}>Selesai</option>
                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}>Dibatalkan</option>
                </select>
            </div>
            <div style="flex:1;min-width:160px">
                <label class="form-label" style="margin-bottom:4px">Dokter</label>
                <select name="dokter_id" class="form-input">
                    <option value="">Semua Dokter</option>
                    @foreach($dokters as $d)
                    <option value="{{ $d->id }}" {{ request('dokter_id')==$d->id ? 'selected':'' }}>{{ $d->nama_dokter }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;min-width:140px">
                <label class="form-label" style="margin-bottom:4px">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input">
            </div>
            <div style="display:flex;gap:8px;padding-top:1px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                @if(request()->hasAny(['search','status','dokter_id','tanggal']))
                <a href="{{ route('admin.booking') }}" class="btn btn-secondary" title="Reset filter"><i class="fas fa-xmark"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel --}}
<div class="card">
    <div class="card-header">
        <h3>Daftar Booking</h3>
        <span style="font-size:12px;color:#94a3b8">{{ $bookings->total() }} data ditemukan</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal Booking</th>
                    <th style="text-align:center">No. Antrian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                @php
                $bc = ['pending'=>'badge-amber','approved'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'][$b->status] ?? 'badge-slate';
                $bl = ['pending'=>'Menunggu','approved'=>'Dikonfirmasi','completed'=>'Selesai','cancelled'=>'Dibatalkan'][$b->status] ?? $b->status;
                @endphp
                <tr>
                    <td><span class="code-tag">{{ $b->kode_booking }}</span></td>
                    <td>
                        <p style="font-weight:600;font-size:13px">{{ $b->pasien?->user?->nama ?? $b->pasien?->nama_lengkap ?? '-' }}</p>
                        <p style="font-size:11px;color:#94a3b8">{{ $b->pasien?->no_rekam_medis ?? '-' }}</p>
                    </td>
                    <td style="color:#64748b;font-size:12px">{{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</td>
                    <td style="color:#64748b;font-size:12px">
                        {{ $b->tanggal_booking?->format('d M Y') ?? '-' }}
                        <br><span style="color:#94a3b8">{{ $b->jadwalDokter?->jam_mulai ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : '' }}</span>
                    </td>
                    <td style="font-weight:700;text-align:center">{{ $b->nomor_antrian ?? '-' }}</td>
                    <td><span class="badge {{ $bc }}">{{ $bl }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('admin.booking.show',$b) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                            <form method="POST" action="{{ route('admin.booking.destroy',$b) }}" onsubmit="return confirm('Hapus booking ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar-xmark"></i>
                            <p>Tidak ada data booking</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $bookings->links() }}</div>
</div>

@endsection
