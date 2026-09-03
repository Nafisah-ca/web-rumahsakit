@extends('layouts.cms')
@php $pageTitle = 'Manajemen Dokter'; $breadcrumb = 'CMS / Dokter'; @endphp
@section('content')

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-user-doctor" style="color:#2563eb;margin-right:6px"></i>Daftar Dokter</h3>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
            <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama dokter..." class="form-input" style="width:200px">
                <select name="spesialis_id" class="form-input" style="width:200px">
                    <option value="">Semua Spesialisasi</option>
                    @foreach($spesialisasis as $sp)
                    <option value="{{ $sp->id }}" {{ request('spesialis_id')==$sp->id?'selected':'' }}>
                        {{ $sp->nama_spesialis }}
                    </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','spesialis_id']))
                <a href="{{ route('cms.dokter') }}" class="btn btn-secondary btn-sm"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
            <a href="{{ route('cms.dokter.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Dokter
            </a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Kontak</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dokters as $i => $d)
                <tr>
                    <td class="text-muted">{{ $dokters->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($d->foto)
                            <img src="{{ Storage::url($d->foto) }}"
                                 style="width:40px;height:40px;border-radius:10px;object-fit:cover;flex-shrink:0;object-position:top"
                                 alt="{{ $d->nama_dokter }}">
                            @else
                            <div style="width:40px;height:40px;border-radius:10px;background:#e0e7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <i class="fas fa-user-doctor" style="color:#4338ca;font-size:16px"></i>
                            </div>
                            @endif
                            <div>
                                <p style="font-weight:700;font-size:13px;color:#0f172a">{{ $d->nama_dokter }}</p>
                                @if($d->pendidikan)
                                <p style="font-size:11px;color:#94a3b8">{{ $d->pendidikan }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-blue">{{ $d->spesialisasi?->nama_spesialis ?? '-' }}</span>
                    </td>
                    <td style="font-size:12px;color:#64748b">
                        @if($d->email)<div><i class="fas fa-envelope" style="width:14px;color:#94a3b8"></i> {{ $d->email }}</div>@endif
                        @if($d->no_hp)<div><i class="fas fa-phone" style="width:14px;color:#94a3b8"></i> {{ $d->no_hp }}</div>@endif
                    </td>
                    <td>
                        <span class="badge {{ $d->status==='aktif' ? 'badge-green' : 'badge-slate' }}">
                            {{ $d->status==='aktif' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.dokter.edit', $d) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('cms.dokter.destroy', $d) }}"
                                  onsubmit="return confirm('Hapus dokter {{ $d->nama_dokter }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-user-doctor"></i>
                            <p>Belum ada dokter. <a href="{{ route('cms.dokter.create') }}" style="color:#2563eb">Tambah sekarang</a></p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dokters->hasPages())
    <div class="table-footer">{{ $dokters->links() }}</div>
    @endif
</div>
@endsection
