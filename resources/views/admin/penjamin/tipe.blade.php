@extends('layouts.admin')
@php $pageTitle = 'Tipe Penjamin'; $breadcrumb = 'Admin / Penjamin / Tipe'; @endphp
@section('content')

<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

    {{-- List Tipe --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Tipe Penjamin</h3>
            <a href="{{ route('admin.penjamin') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali ke Penjamin</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Nama Tipe</th><th>Jumlah Penjamin</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($tipes as $i => $t)
                    <tr>
                        <td style="color:#94a3b8">{{ $i+1 }}</td>
                        <td style="font-weight:700;font-size:13px">{{ $t->nama_tipe }}</td>
                        <td style="font-size:13px">{{ $t->penjamins_count }}</td>
                        <td><span class="badge {{ $t->status==='aktif'?'badge-green':'badge-slate' }}">{{ $t->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.tipe-penjamin.destroy', $t) }}"
                                  onsubmit="return confirm('Hapus tipe ini? Pastikan tidak ada penjamin yang menggunakan tipe ini.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" {{ $t->penjamins_count > 0 ? 'disabled title=Tidak bisa hapus — masih digunakan' : '' }}>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-layer-group"></i><p>Belum ada tipe penjamin</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Form Tambah --}}
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px">Tambah Tipe Penjamin</p>
        @if(session('success'))<div class="alert alert-success" style="margin-bottom:10px"><i class="fas fa-check-circle"></i><span>{{ session('success') }}</span></div>@endif
        @if(session('error'))<div class="alert alert-error" style="margin-bottom:10px"><i class="fas fa-exclamation-circle"></i><span>{{ session('error') }}</span></div>@endif
        @if($errors->any())<div class="form-error" style="margin-bottom:10px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
        <form method="POST" action="{{ route('admin.tipe-penjamin.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Tipe <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_tipe" value="{{ old('nama_tipe') }}" class="form-input" required
                       placeholder="cth: BPJS Ketenagakerjaan, TNI/Polri...">
                @error('nama_tipe')<p style="color:#ef4444;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <i class="fas fa-plus"></i> Tambah Tipe
            </button>
        </form>

        <div style="margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9">
            <p style="font-size:12px;color:#94a3b8;font-weight:600;margin-bottom:10px">TIPE YANG SUDAH ADA</p>
            @foreach($tipes as $t)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 10px;background:#f8fafc;border-radius:8px;margin-bottom:6px">
                <span style="font-size:13px;font-weight:600;color:#334155">{{ $t->nama_tipe }}</span>
                <span style="font-size:11px;color:#94a3b8">{{ $t->penjamins_count }} penjamin</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
