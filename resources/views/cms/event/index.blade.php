@extends('layouts.cms')
@php $pageTitle = 'Event & Kegiatan'; $breadcrumb = 'CMS / Event'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Event</h3>
        <div style="display:flex;gap:10px">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event..." class="form-input" style="width:180px">
                <select name="status" class="form-input" style="width:130px">
                    <option value="">Semua</option>
                    <option value="aktif"    {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','status']))<a href="{{ route('cms.event') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('cms.event.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Event</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Kuota</th>
                    <th>Peserta</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $ev)
                @php $jumlahPeserta = $ev->pesertaAktif()->count(); @endphp
                <tr>
                    <td style="font-weight:600;font-size:13px;color:#0f172a">{{ $ev->judul }}</td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap">{{ $ev->tanggal_event?->format('d M Y') ?? '-' }}</td>
                    <td style="font-size:12px;color:#94a3b8;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $ev->lokasi ?? '-' }}</td>
                    <td style="font-size:12px;color:#64748b;text-align:center">
                        {!! $ev->kuota ?? '<span style="color:#94a3b8">∞</span>' !!}
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('cms.event.peserta', $ev) }}"
                           style="display:inline-flex;align-items:center;gap:5px;background:#ede9fe;color:#6d28d9;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;transition:background .15s"
                           onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                            <i class="fas fa-users" style="font-size:11px"></i>
                            {{ $jumlahPeserta }}
                            @if($ev->kuota)
                                / {{ $ev->kuota }}
                            @endif
                        </a>
                    </td>
                    <td>
                        <span class="badge {{ $ev->status==='aktif'?'badge-green':'badge-amber' }}">
                            {{ $ev->status==='aktif'?'Aktif':'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:nowrap">
                            <a href="{{ route('cms.event.peserta', $ev) }}" class="btn btn-sm"
                               style="background:#ede9fe;color:#6d28d9;border:none" title="Lihat Peserta">
                                <i class="fas fa-users"></i> Peserta
                            </a>
                            <a href="{{ route('cms.event.edit', $ev) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('cms.event.destroy', $ev) }}" onsubmit="return confirm('Hapus event ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar-days"></i>
                            <p>Belum ada event</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $events->links() }}</div>
</div>
@endsection
