@extends('layouts.cms')
@php $pageTitle = 'Layanan'; $breadcrumb = 'CMS / Layanan'; @endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Layanan</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..." class="form-input" style="width:180px">
                <select name="kategori_id" class="form-input" style="width:160px">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id')==$k->id?'selected':'' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                <select name="status" class="form-input" style="width:120px">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','kategori_id','status']))
                <a href="{{ route('cms.layanan') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
            <a href="{{ route('cms.layanan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Layanan</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Kategori</th>
                    <th>Icon</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($layanans as $l)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            @if($l->gambar)
                            <img src="{{ Storage::url($l->gambar) }}" style="width:42px;height:42px;border-radius:10px;object-fit:cover;flex-shrink:0;border:1px solid #e2e8f0">
                            @else
                            <div style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#dcfce7;color:#16a34a;flex-shrink:0">
                                <i class="fas {{ $l->icon ?? 'fa-stethoscope' }}"></i>
                            </div>
                            @endif
                            <div>
                                <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $l->nama_layanan }}</p>
                                @if($l->deskripsi)
                                <p style="font-size:11px;color:#94a3b8;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $l->deskripsi }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($l->kategori)
                        <span style="display:inline-flex;align-items:center;gap:5px;background:#f0fdf4;color:#166534;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:600">
                            <i class="fas {{ $l->kategori->icon ?? 'fa-hospital' }}" style="font-size:10px"></i>
                            {{ $l->kategori->nama_kategori }}
                        </span>
                        @else
                        <span style="color:#94a3b8;font-size:12px">—</span>
                        @endif
                    </td>
                    <td><span class="code-tag"><i class="fas {{ $l->icon ?? 'fa-stethoscope' }} mr-1"></i>{{ $l->icon ?? 'fa-stethoscope' }}</span></td>
                    <td><span class="badge {{ $l->status==='aktif'?'badge-green':'badge-slate' }}">{{ $l->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.layanan.edit',$l) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('cms.layanan.destroy',$l) }}" onsubmit="return confirm('Hapus layanan ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-stethoscope"></i><p>Belum ada layanan yang ditemukan</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $layanans->links() }}</div>
</div>
@endsection
