@extends('layouts.cms')
@php $pageTitle = 'Penghargaan & Akreditasi'; $breadcrumb = 'CMS / Penghargaan'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Penghargaan & Akreditasi</h3>
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
                <i class="fas fa-plus"></i> Tambah Penghargaan
            </a>
        </div>
    </div>

    <div style="padding:12px 20px;background:#fffbeb;border-bottom:1px solid #fde68a;font-size:12px;color:#92400e">
        <i class="fas fa-info-circle" style="margin-right:6px;color:#d97706"></i>
        <strong>Cara kerja:</strong> Data yang ditambahkan di sini akan otomatis tampil di section <em>"Penghargaan Kami"</em> pada halaman Tentang Kami.
        Gunakan logo resmi (PNG transparan) dan atur urutan tampil sesuai keinginan.
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:50px">Urutan</th>
                    <th style="width:80px">Logo</th>
                    <th>Nama &amp; Deskripsi</th>
                    <th style="width:70px">Tahun</th>
                    <th style="width:90px">Status</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($akreditasis as $a)
                <tr>
                    {{-- Urutan --}}
                    <td style="text-align:center;color:#94a3b8;font-weight:700">{{ $a->urutan }}</td>

                    {{-- Logo --}}
                    <td>
                        @if($a->logo)
                        <img src="{{ $a->logo_url }}"
                             alt="{{ $a->nama }}"
                             style="height:44px;width:auto;max-width:80px;object-fit:contain;
                                    border-radius:6px;background:#f8fafc;padding:5px;border:1px solid #e2e8f0">
                        @else
                        <div style="width:44px;height:44px;background:#f1f5f9;border-radius:6px;
                                    display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-certificate" style="color:#cbd5e1;font-size:18px"></i>
                        </div>
                        @endif
                    </td>

                    {{-- Nama & Deskripsi --}}
                    <td>
                        <p style="font-weight:700;font-size:13px;color:#0f172a;margin-bottom:2px">{{ $a->nama }}</p>
                        @if($a->deskripsi)
                        <p style="font-size:11px;color:#94a3b8;margin:0">{{ $a->deskripsi }}</p>
                        @endif
                    </td>

                    {{-- Tahun --}}
                    <td style="font-weight:600;color:#64748b;font-size:13px">{{ $a->tahun ?? '—' }}</td>

                    {{-- Status --}}
                    <td>
                        @if($a->status === 'aktif')
                        <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;
                                     background:#f0fdf4;color:#16a34a;border:1px solid #86efac">Aktif</span>
                        @else
                        <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:999px;
                                     background:#f1f5f9;color:#64748b;border:1px solid #cbd5e1">Nonaktif</span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('cms.akreditasi.edit', $a) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('cms.akreditasi.destroy', $a) }}"
                                  onsubmit="return confirm('Hapus penghargaan &quot;{{ $a->nama }}&quot;?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="fas fa-award"></i>
                            <p>Belum ada data penghargaan</p>
                            <a href="{{ route('cms.akreditasi.create') }}" class="btn btn-primary" style="margin-top:8px">
                                <i class="fas fa-plus"></i> Tambah Pertama
                            </a>
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
