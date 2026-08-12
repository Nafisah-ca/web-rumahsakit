@extends('layouts.cms')
@php $pageTitle = 'Layanan'; $breadcrumb = 'CMS / Layanan'; @endphp
@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-bottom:20px">
    @php
        $totalAktif   = $layanans->total();
        $totalKatAktif = $kategoris->count();
    @endphp
    <div class="card card-body" style="padding:16px">
        <p style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Total Layanan</p>
        <p style="font-size:24px;font-weight:800;color:#0f172a;margin-top:2px">{{ $layanans->total() }}</p>
    </div>
    <div class="card card-body" style="padding:16px">
        <p style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Kategori</p>
        <p style="font-size:24px;font-weight:800;color:#0f172a;margin-top:2px">{{ $totalKatAktif }}</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Layanan</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <select name="kategori_id" class="form-input" style="width:160px" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..." class="form-input" style="width:200px">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('search') || request('kategori_id'))
                <a href="{{ route('cms.layanan') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
            <a href="{{ route('cms.layanan.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
            <a href="{{ route('cms.kategori-layanan') }}" class="btn btn-secondary"><i class="fas fa-tags"></i> Kategori</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Kategori</th>
                    <th>Icon</th>
                    <th>Urutan</th>
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
                            <img src="{{ Storage::url($l->gambar) }}" style="width:44px;height:44px;border-radius:10px;object-fit:cover;flex-shrink:0;border:1px solid #e2e8f0">
                            @else
                            <div style="width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#00521f,#00b04f);flex-shrink:0">
                                <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-white"></i>
                            </div>
                            @endif
                            <div>
                                <p style="font-weight:700;font-size:13px;color:#0f172a">{{ $l->nama_layanan }}</p>
                                @if($l->deskripsi)
                                <p style="font-size:11px;color:#94a3b8;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                                    {{ Str::limit($l->deskripsi, 70) }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($l->kategori)
                        <span style="display:inline-flex;align-items:center;gap:5px;background:#dcfce7;color:#15803d;font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px">
                            <i class="fas {{ $l->kategori->icon ?? 'fa-tag' }}" style="font-size:9px"></i>
                            {{ $l->kategori->nama_kategori }}
                        </span>
                        @else
                        <span style="color:#cbd5e1;font-size:12px">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="code-tag"><i class="fas {{ $l->icon ?? 'fa-stethoscope' }} mr-1"></i>{{ $l->icon ?? '—' }}</span>
                    </td>
                    <td style="text-align:center;font-weight:700;color:#64748b">{{ $l->urutan ?? 0 }}</td>
                    <td><span class="badge {{ $l->status==='aktif'?'badge-green':'badge-slate' }}">{{ $l->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="{{ route('layanan.detail', $l) }}" target="_blank" class="btn btn-sm btn-secondary" title="Lihat di website">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('cms.layanan.edit', $l) }}" class="btn btn-sm btn-secondary">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('cms.layanan.destroy', $l) }}" onsubmit="return confirm('Hapus layanan ini?')">
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
                            <i class="fas fa-stethoscope"></i>
                            <p>Belum ada layanan</p>
                            <a href="{{ route('cms.layanan.create') }}" class="btn btn-primary" style="margin-top:10px">
                                <i class="fas fa-plus"></i> Tambah Layanan
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $layanans->withQueryString()->links() }}</div>
</div>
@endsection
