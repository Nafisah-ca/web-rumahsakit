@extends('layouts.cms')
@php $pageTitle = 'Spesialisasi'; $breadcrumb = 'CMS / Spesialisasi'; @endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Spesialisasi</h3>
        <div style="display:flex;gap:10px">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari spesialisasi..." class="form-input" style="width:200px">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('search'))<a href="{{ route('cms.spesialisasi') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('cms.spesialisasi.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Nama Spesialisasi</th><th>Deskripsi</th><th>Jumlah Dokter</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($spesialisasis as $i => $sp)
                <tr>
                    <td style="color:#94a3b8">{{ $spesialisasis->firstItem()+$i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:32px;height:32px;background:#dbeafe;color:#1d4ed8;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <span style="font-weight:600;font-size:13px;color:#0f172a">{{ $sp->nama_spesialis }}</span>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#64748b;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $sp->deskripsi ?? '-' }}</td>
                    <td style="font-weight:600">{{ $sp->dokters()->count() }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.spesialisasi.edit',$sp) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('cms.spesialisasi.destroy',$sp) }}" onsubmit="return confirm('Hapus spesialisasi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-user-doctor"></i><p>Belum ada spesialisasi</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $spesialisasis->links() }}</div>
</div>
@endsection
