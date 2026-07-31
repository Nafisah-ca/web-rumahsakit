@extends('layouts.cms')
@php $pageTitle = 'Banner Homepage'; $breadcrumb = 'CMS / Banner'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Banner</h3>
        <a href="{{ route('cms.banner.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Banner</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Preview</th><th>Judul</th><th>Posisi</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($banners as $b)
                <tr>
                    <td>
                        <div style="width:80px;height:48px;border-radius:8px;overflow:hidden;background:#374151;display:flex;align-items:center;justify-content:center">
                            @if($b->gambar)
                            <img src="{{ Storage::url($b->gambar) }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                            <i class="fas fa-image" style="color:rgba(255,255,255,.4);font-size:16px"></i>
                            @endif
                        </div>
                    </td>
                    <td>
                        <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $b->judul }}</p>
                    </td>
                    <td><span class="badge badge-blue">Homepage</span></td>
                    <td style="font-size:13px;font-weight:600;color:#64748b;font-family:monospace">-</td>
                    <td><span class="badge {{ $b->status==='aktif'?'badge-green':'badge-slate' }}">{{ $b->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.banner.edit',$b) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('cms.banner.destroy',$b) }}" onsubmit="return confirm('Hapus banner ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-panorama"></i><p>Belum ada banner</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $banners->links() }}</div>
</div>
@endsection
