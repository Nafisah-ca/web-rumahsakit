@extends('layouts.cms')
@php $pageTitle = 'Akreditasi'; $breadcrumb = 'CMS / Akreditasi'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Akreditasi</h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama..." class="form-input" style="width:200px">
                <select name="status" class="form-input" style="width:140px"
                        onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif"    {{ request('status')==='aktif'    ? 'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status')==='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('search') || request('status'))
                <a href="{{ route('cms.akreditasi') }}" class="btn btn-secondary" title="Reset"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
            <a href="{{ route('cms.akreditasi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Akreditasi
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px">Urutan</th>
                    <th>Logo</th>
                    <th>Nama</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($akreditasis as $a)
                <tr>
                    <td style="text-align:center;color:#94a3b8;font-weight:700">{{ $a->urutan }}</td>
                    <td>
                        @if($a->logo)
                        <img src="{{ Storage::url($a->logo) }}"
                             alt="{{ $a->nama }}"
                             style="height:40px;width:auto;object-fit:contain;border-radius:6px;background:#f8fafc;padding:4px;border:1px solid #e2e8f0">
                        @else
                        <div style="width:40px;height:40px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-certificate" style="color:#cbd5e1;font-size:16px"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $a->nama }}</p>
                    </td>
                    <td>
                        @if($a->status === 'aktif')
                        <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;background:#f0fdf4;color:#16a34a;border:1px solid #86efac">Aktif</span>
                        @else
                        <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.akreditasi.edit', $a) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('cms.akreditasi.destroy', $a) }}"
                                  onsubmit="return confirm('Hapus akreditasi {{ $a->nama }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="fas fa-certificate"></i>
                            <p>Belum ada data akreditasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $akreditasis->links() }}</div>
</div>
@endsection
