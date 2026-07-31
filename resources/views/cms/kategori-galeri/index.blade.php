@extends('layouts.cms')
@php $pageTitle = 'Kategori Galeri'; $breadcrumb = 'CMS / Kategori Galeri'; @endphp
@section('content')
@php if(!isset($errors)) $errors = new \Illuminate\Support\ViewErrorBag; @endphp
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">
    {{-- List --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Kategori Galeri</h3>
            @if(request('search'))<a href="{{ route('cms.kategori-galeri') }}" class="btn btn-secondary btn-sm"><i class="fas fa-xmark"></i> Reset</a>@endif
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Foto</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($kategoris as $k)
                    <tr>
                        <td style="font-weight:600;font-size:13px">{{ $k->nama_kategori }}</td>
                        <td style="font-size:12px;color:#64748b">{{ Str::limit($k->deskripsi,50) ?? '—' }}</td>
                        <td style="font-size:13px;color:#64748b">{{ $k->galeris_count }}</td>
                        <td><span class="badge {{ $k->status==='aktif'?'badge-green':'badge-slate' }}">{{ $k->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('cms.kategori-galeri.destroy',$k) }}" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Belum ada kategori galeri</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">{{ $kategoris->links() }}</div>
    </div>
    {{-- Form Tambah --}}
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px">Tambah Kategori Baru</p>
        @if(session('success'))<div class="alert alert-success" style="margin-bottom:12px"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('cms.kategori-galeri.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" class="form-input" required maxlength="100" placeholder="cth: Kegiatan RS, Fasilitas...">
                @error('nama_kategori')<p style="color:#ef4444;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-input" placeholder="Opsional...">{{ old('deskripsi') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center"><i class="fas fa-plus"></i> Tambah Kategori</button>
        </form>
    </div>
</div>
@endsection
