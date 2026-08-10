@extends('layouts.cms')
@php $pageTitle = 'Informasi Terkini'; $breadcrumb = 'CMS / Informasi'; @endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Informasi</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="form-input" style="width:200px">
                <select name="status" class="form-input" style="width:130px">
                    <option value="">Semua Status</option>
                    <option value="draft"   {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                    <option value="publish" {{ request('status')=='publish'?'selected':'' }}>Publish</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','status']))<a href="{{ route('cms.informasi') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('cms.informasi.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Informasi</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Judul</th><th>Status</th><th>Tanggal</th><th>Dibuat Oleh</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($informasis as $info)
                <tr>
                    <td style="font-weight:600;font-size:13px;max-width:280px">{{ $info->judul }}</td>
                    <td><span class="badge {{ $info->status==='publish'?'badge-green':'badge-amber' }}">{{ $info->status==='publish'?'Publish':'Draft' }}</span></td>
                    <td style="font-size:12px;color:#94a3b8">{{ $info->created_tm->format('d M Y') }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $info->createdBy?->nama ?? '-' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.informasi.edit',$info) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('cms.informasi.destroy',$info) }}" onsubmit="return confirm('Hapus informasi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-circle-info"></i><p>Belum ada informasi</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $informasis->links() }}</div>
</div>
@endsection
