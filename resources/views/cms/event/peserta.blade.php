@extends('layouts.cms')
@php $pageTitle = 'Peserta: ' . Str::limit($event->judul, 40); $breadcrumb = 'CMS / Event / Peserta'; @endphp

@section('content')

{{-- Info Event --}}
<div class="card card-body" style="margin-bottom:20px">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div>
            <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px">Event</p>
            <h2 style="font-size:17px;font-weight:800;color:#0f172a;margin:0 0 8px">{{ $event->judul }}</h2>
            <div style="display:flex;gap:16px;flex-wrap:wrap;font-size:12px;color:#64748b">
                <span><i class="fas fa-calendar text-purple-600 mr-1"></i>{{ $event->tanggal_event?->format('d M Y') }}</span>
                <span><i class="fas fa-clock text-purple-600 mr-1"></i>{{ substr($event->waktu_event ?? '', 0, 5) }} WIB</span>
                @if($event->lokasi)
                <span><i class="fas fa-location-dot text-purple-600 mr-1"></i>{{ $event->lokasi }}</span>
                @endif
            </div>
        </div>
        <div style="display:flex;gap:8px">
            <a href="{{ route('cms.event.edit', $event) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-pen"></i> Edit Event
            </a>
            <a href="{{ route('cms.event') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

{{-- Statistik --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:20px">
    <div class="card card-body" style="text-align:center;padding:16px 12px">
        <div style="font-size:28px;font-weight:800;color:#7c3aed">{{ $total }}</div>
        <div style="font-size:11px;color:#94a3b8;font-weight:600;margin-top:2px">Total Pendaftar</div>
    </div>
    <div class="card card-body" style="text-align:center;padding:16px 12px">
        <div style="font-size:28px;font-weight:800;color:#16a34a">{{ $confirmed }}</div>
        <div style="font-size:11px;color:#94a3b8;font-weight:600;margin-top:2px">Dikonfirmasi</div>
    </div>
    <div class="card card-body" style="text-align:center;padding:16px 12px">
        <div style="font-size:28px;font-weight:800;color:#d97706">{{ $pending }}</div>
        <div style="font-size:11px;color:#94a3b8;font-weight:600;margin-top:2px">Menunggu</div>
    </div>
    @if($event->kuota)
    @php $terpakai = $confirmed + $pending; $sisa = max(0, $event->kuota - $terpakai); @endphp
    <div class="card card-body" style="text-align:center;padding:16px 12px">
        <div style="font-size:28px;font-weight:800;color:{{ $sisa === 0 ? '#dc2626' : '#0891b2' }}">
            {{ $sisa === 0 ? 'PENUH' : $sisa }}
        </div>
        <div style="font-size:11px;color:#94a3b8;font-weight:600;margin-top:2px">
            Sisa Kuota ({{ $event->kuota }} total)
        </div>
    </div>
    @endif
</div>

{{-- Flash --}}
@if(session('success'))
<div style="margin-bottom:16px;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#15803d;font-size:13px;font-weight:600;display:flex;align-items:center;gap:8px">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Tabel Peserta --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-users" style="color:#7c3aed;margin-right:8px"></i>Daftar Peserta</h3>
        <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama / kode booking..." class="form-input" style="width:220px">
            <select name="status" class="form-input" style="width:160px">
                <option value="">Semua Status</option>
                <option value="pending"   {{ request('status')=='pending'   ? 'selected' : '' }}>Menunggu</option>
                <option value="confirmed" {{ request('status')=='confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('cms.event.peserta', $event) }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
            @endif
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:40px">#</th>
                    <th>Kode Booking</th>
                    <th>Nama Peserta</th>
                    <th>No. Rekam Medis</th>
                    <th>Tanggal Daftar</th>
                    <th>Status</th>
                    <th>Catatan</th>
                    <th style="width:180px">Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peserta as $i => $p)
                @php
                    $statusConf = [
                        'pending'   => ['Menunggu',     'badge-amber'],
                        'confirmed' => ['Dikonfirmasi', 'badge-green'],
                        'cancelled' => ['Dibatalkan',   'badge-red'],
                    ];
                    [$statusLabel, $badgeClass] = $statusConf[$p->status] ?? [$p->status, 'badge-slate'];
                @endphp
                <tr>
                    <td style="color:#94a3b8;font-size:12px">{{ $peserta->firstItem() + $i }}</td>
                    <td>
                        <span style="font-family:monospace;font-size:12px;background:#f1f5f9;padding:3px 8px;border-radius:6px;font-weight:600;white-space:nowrap">
                            {{ $p->kode_booking }}
                        </span>
                    </td>
                    <td style="font-weight:600;font-size:13px;white-space:nowrap">
                        {{ $p->pasien?->user?->nama ?? '<span style="color:#94a3b8">-</span>' }}
                    </td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap">
                        {{ $p->pasien?->no_rekam_medis ?? '-' }}
                    </td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap">
                        {{ $p->created_tm?->format('d M Y') }}<br>
                        <span style="color:#94a3b8">{{ $p->created_tm?->format('H:i') }} WIB</span>
                    </td>
                    <td><span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                    <td style="font-size:12px;color:#94a3b8;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                        {{ $p->catatan ?: '-' }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cms.event.peserta.status', [$event, $p]) }}"
                              style="display:flex;gap:6px;align-items:center">
                            @csrf @method('PUT')
                            <select name="status" class="form-input"
                                    style="width:120px;font-size:12px;padding:5px 8px">
                                <option value="pending"   {{ $p->status==='pending'   ? 'selected' : '' }}>Menunggu</option>
                                <option value="confirmed" {{ $p->status==='confirmed' ? 'selected' : '' }}>Konfirmasi</option>
                                <option value="cancelled" {{ $p->status==='cancelled' ? 'selected' : '' }}>Batalkan</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary" title="Simpan perubahan">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Belum ada peserta yang mendaftar ke event ini</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">
        <div style="font-size:12px;color:#94a3b8">
            Menampilkan {{ $peserta->firstItem() ?? 0 }}–{{ $peserta->lastItem() ?? 0 }} dari {{ $peserta->total() }} peserta
        </div>
        {{ $peserta->links() }}
    </div>
</div>

@endsection
