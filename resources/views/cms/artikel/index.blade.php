@extends('layouts.cms')
@php $pageTitle = 'Artikel'; $breadcrumb = 'CMS / Artikel'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Artikel</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul..." class="form-input" style="width:180px">
                <select name="status" class="form-input" style="width:130px">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status')=='draft'?'selected':'' }}>Draft</option>
                    <option value="publish" {{ request('status')=='publish'?'selected':'' }}>Publish</option>
                </select>
                <select name="kategori_id" class="form-input" style="width:160px">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id')==$k->id?'selected':'' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','status','kategori_id']))<a href="{{ route('cms.artikel') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('cms.artikel.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tulis Artikel</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Artikel</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Tanggal</th><th>Diperbarui Oleh</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($artikels as $art)
                <tr>
                    <td style="max-width:280px">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:#dbeafe;color:#2563eb;font-size:16px">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <p style="font-weight:600;font-size:13px;color:#0f172a;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical">{{ $art->judul }}</p>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#64748b">{{ $art->kategori?->nama_kategori??'-' }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $art->penulis?->nama??'-' }}</td>
                    <td><span class="badge {{ $art->status==='publish'?'badge-green':'badge-amber' }}">{{ $art->status==='publish'?'Publish':'Draft' }}</span></td>
                    <td style="font-size:12px;color:#94a3b8">{{ $art->created_tm->format('d M Y') }}</td>
                    <td style="font-size:12px;color:#64748b">{{ $art->updatedBy?->nama ?? '-' }}</td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.artikel.edit',$art) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('cms.artikel.destroy',$art) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-newspaper"></i><p>Belum ada artikel</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $artikels->links() }}</div>
</div>
@endsection
