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
                    <option value="aktif" {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
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
                <tr><th>Judul</th><th>Tanggal</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($events as $ev)
                <tr>
                    <td style="font-weight:600;font-size:13px;color:#0f172a">{{ $ev->judul }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $ev->tanggal_event?->format('d M Y') ?? '-' }}</td>
                    <td style="font-size:12px;color:#94a3b8;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $ev->lokasi ?? '-' }}</td>
                    <td><span class="badge {{ $ev->status==='aktif'?'badge-green':'badge-amber' }}">{{ $ev->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.event.edit',$ev) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('cms.event.destroy',$ev) }}" onsubmit="return confirm('Hapus event ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-calendar-days"></i><p>Belum ada event</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $events->links() }}</div>
</div>
@endsection
