@extends('layouts.admin')
@php $pageTitle = 'Manajemen Dokter'; $breadcrumb = 'Admin / Dokter'; @endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Dokter</h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="form-input" style="width:180px">
                <select name="spesialis_id" class="form-input" style="width:180px">
                    <option value="">Semua Spesialisasi</option>
                    @foreach($spesialisasis as $sp)
                    <option value="{{ $sp->id }}" {{ request('spesialis_id')==$sp->id?'selected':'' }}>{{ $sp->nama_spesialis }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','spesialis_id']))<a href="{{ route('admin.dokter') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('admin.dokter.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Dokter</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Dokter</th><th>Spesialisasi</th><th>Email</th><th>No. HP</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($dokters as $i => $d)
                <tr>
                    <td style="color:#94a3b8">{{ $dokters->firstItem()+$i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($d->foto)
                            <img src="{{ Storage::url($d->foto) }}" style="width:36px;height:36px;border-radius:10px;object-fit:cover;flex-shrink:0">
                            @else
                            <div style="width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;background:#374151">
                                {{ strtoupper(substr($d->nama_dokter,0,1)) }}
                            </div>
                            @endif
                            <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $d->nama_dokter }}</p>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#64748b">{{ $d->spesialisasi?->nama_spesialis ?? '-' }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $d->email }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $d->no_hp }}</td>
                    <td><span class="badge {{ $d->status==='aktif' ? 'badge-green' : 'badge-slate' }}">{{ $d->status==='aktif' ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('admin.dokter.edit',$d) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('admin.dokter.destroy',$d) }}" onsubmit="return confirm('Hapus dokter ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-user-doctor"></i><p>Tidak ada dokter</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $dokters->links() }}</div>
</div>
@endsection
