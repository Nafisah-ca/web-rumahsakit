@extends('layouts.cms')
@php $pageTitle = 'Kategori Artikel'; $breadcrumb = 'CMS / Kategori Artikel'; @endphp

@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start">

    {{-- Form Tambah --}}
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px">Tambah Kategori Baru</p>
        <form method="POST" action="{{ route('cms.kategori-artikel.store') }}">
            @csrf
            @if(session('success'))
            <div class="alert alert-success" style="margin:0 0 12px"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div>
            @endif
            @if($errors->any())
            <div class="form-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-input" required placeholder="contoh: Kesehatan Jantung">
            </div>
            <div class="form-group">
                <label class="form-label">Warna Badge</label>
                <input type="color" name="warna" value="{{ old('warna','#3b82f6') }}" class="form-input">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <i class="fas fa-plus"></i> Simpan Kategori
            </button>
        </form>
    </div>

    {{-- Daftar --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Kategori</h3>
            <span style="font-size:12px;color:#94a3b8">{{ $kategoris->total() }} kategori</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Nama Kategori</th><th>Warna</th><th>Jumlah Artikel</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $k)
                    <tr>
                        <td style="font-weight:600;color:#0f172a">{{ $k->nama }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:20px;height:20px;border-radius:50%;border:2px solid #e2e8f0;background:{{ $k->warna }}"></div>
                                <span class="code-tag">{{ $k->warna }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-blue">{{ $k->artikels_count }} artikel</span>
                        </td>
                        <td>
                            @if($k->artikels_count == 0)
                            <form method="POST" action="{{ route('cms.kategori-artikel.destroy', $k) }}" onsubmit="return confirm('Hapus kategori {{ addslashes($k->nama) }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                            @else
                            <span style="font-size:12px;color:#94a3b8">Tidak bisa dihapus</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Belum ada kategori</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">{{ $kategoris->links() }}</div>
    </div>
</div>
@endsection
