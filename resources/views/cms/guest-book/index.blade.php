@extends('layouts.cms')
@php $pageTitle = 'Guest Book'; $breadcrumb = 'CMS / Guest Book'; @endphp
@section('content')

@if($pesanBaru > 0)
<div class="alert alert-success" style="margin-bottom:20px">
    <i class="fas fa-envelope"></i> Ada <strong>{{ $pesanBaru }} pesan baru</strong> yang belum dibaca.
</div>
@endif

<div class="card">
    <div class="card-header" style="flex-wrap:wrap;gap:12px">
        <h3>Pesan Masuk</h3>
        <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="form-input" style="width:200px">
            <select name="status" class="form-input" style="width:140px">
                <option value="">Semua Status</option>
                <option value="baru"    {{ request('status')=='baru'?'selected':'' }}>Baru</option>
                <option value="dibaca"  {{ request('status')=='dibaca'?'selected':'' }}>Dibaca</option>
                <option value="selesai" {{ request('status')=='selesai'?'selected':'' }}>Selesai</option>
            </select>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
            @if(request()->hasAny(['search','status']))<a href="{{ route('cms.guest-book') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Pengirim</th><th>Pesan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($pesans as $p)
                <tr style="{{ $p->status==='baru' ? 'background:#fffbeb' : '' }}">
                    <td>
                        <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $p->nama }}</p>
                        <p style="font-size:11px;color:#94a3b8">{{ $p->email }}</p>
                        @if($p->no_hp)<p style="font-size:11px;color:#94a3b8">{{ $p->no_hp }}</p>@endif
                    </td>
                    <td style="font-size:13px;color:#334155;max-width:320px">{{ Str::limit($p->pesan, 80) }}</td>
                    <td>
                        @php $bc=['baru'=>'badge-amber','dibaca'=>'badge-blue','selesai'=>'badge-green'][$p->status]??'badge-slate'; @endphp
                        <span class="badge {{ $bc }}">{{ ucfirst($p->status) }}</span>
                    </td>
                    <td style="font-size:12px;color:#94a3b8;white-space:nowrap">{{ $p->created_tm->format('d M Y H:i') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('cms.guest-book.show',$p) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                            <form method="POST" action="{{ route('cms.guest-book.destroy',$p) }}" onsubmit="return confirm('Hapus pesan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-envelope-open-text"></i><p>Belum ada pesan masuk</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $pesans->links() }}</div>
</div>
@endsection
