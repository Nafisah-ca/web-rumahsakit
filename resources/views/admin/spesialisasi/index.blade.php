@extends('layouts.admin')
@php $pageTitle = 'Spesialisasi'; $breadcrumb = 'Admin / Spesialisasi'; @endphp
@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">
    <div class="card">
        <div class="card-header">
            <h3>Daftar Spesialisasi</h3>
            <form method="GET" style="display:flex;gap:8px">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="form-input" style="width:200px">
                <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                @if(request('search'))<a href="{{ route('admin.spesialisasi') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Nama Spesialisasi</th><th>Deskripsi</th><th>Jumlah Dokter</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($spesialisasis as $i => $sp)
                    <tr>
                        <td style="color:#94a3b8">{{ $spesialisasis->firstItem()+$i }}</td>
                        <td style="font-weight:700;font-size:13px">{{ $sp->nama_spesialis }}</td>
                        <td style="font-size:12px;color:#64748b">{{ Str::limit($sp->deskripsi,50) ?? '-' }}</td>
                        <td style="font-size:13px;font-weight:600">{{ $sp->dokters()->count() }}</td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap">
                                <a href="{{ route('admin.spesialisasi.edit',$sp) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="{{ route('admin.spesialisasi.destroy',$sp) }}" onsubmit="return confirm('Hapus spesialisasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-stethoscope"></i><p>Belum ada spesialisasi</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">{{ $spesialisasis->links() }}</div>
    </div>
    {{-- Form tambah cepat --}}
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px">Tambah Spesialisasi Baru</p>
        <a href="{{ route('admin.spesialisasi.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:12px">
            <i class="fas fa-plus"></i> Tambah Spesialisasi
        </a>
        <p style="font-size:12px;color:#94a3b8">Total: {{ $spesialisasis->total() }} spesialisasi</p>
    </div>
</div>
@endsection
