@extends('layouts.cms')
@php $pageTitle = 'Promo'; $breadcrumb = 'CMS / Promo'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Promo</h3>
        <div style="display:flex;gap:10px">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari promo..." class="form-input" style="width:180px">
                <select name="status" class="form-input" style="width:150px" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="aktif"       {{ request('status')==='aktif'       ? 'selected':'' }}>Aktif</option>
                    <option value="nonaktif"    {{ request('status')==='nonaktif'    ? 'selected':'' }}>Nonaktif</option>
                    <option value="kedaluwarsa" {{ request('status')==='kedaluwarsa' ? 'selected':'' }}>Kedaluwarsa</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','status']))<a href="{{ route('cms.promo') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('cms.promo.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Promo</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Promo</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Sisa Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($promos as $promo)
                @php
                    $isExpired  = $promo->tanggal_selesai && $promo->tanggal_selesai->isPast();
                    // Badge: expired tampil kuning-oranye biar admin langsung tahu
                    $statusBadge = $isExpired
                        ? 'badge-amber'
                        : ($promo->status === 'aktif' ? 'badge-green' : 'badge-slate');
                    $statusLabel = $isExpired
                        ? 'Kedaluwarsa'
                        : ($promo->status === 'aktif' ? 'Aktif' : 'Nonaktif');
                @endphp
                <tr>
                    {{-- Judul --}}
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:40px;height:40px;border-radius:10px;flex-shrink:0;overflow:hidden;background:#16a34a;display:flex;align-items:center;justify-content:center">
                                @if($promo->gambar)
                                <img src="{{ Storage::url($promo->gambar) }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                <i class="fas fa-tag text-white text-sm"></i>
                                @endif
                            </div>
                            <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $promo->judul }}</p>
                        </div>
                    </td>

                    {{-- Tanggal Mulai --}}
                    <td style="font-size:12px;color:#64748b;white-space:nowrap">
                        {{ $promo->tanggal_mulai?->format('d M Y') ?? '-' }}
                    </td>

                    {{-- Tanggal Selesai --}}
                    <td style="font-size:12px;white-space:nowrap;color:{{ $isExpired ? '#ef4444' : '#64748b' }};font-weight:{{ $isExpired ? '700' : '400' }}">
                        {{ $promo->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas' }}
                        @if($isExpired)<span style="font-size:10px;background:#fee2e2;color:#ef4444;padding:1px 6px;border-radius:20px;margin-left:4px">Berakhir</span>@endif
                    </td>

                    {{-- Sisa Waktu --}}
                    <td style="font-size:12px;color:#64748b;white-space:nowrap">
                        @if(!$promo->tanggal_selesai)
                            <span style="color:#94a3b8">Tidak terbatas</span>
                        @elseif($isExpired)
                            <span style="color:#ef4444">Sudah berakhir</span>
                        @else
                            <span style="color:#16a34a;font-weight:600">{{ $promo->tanggal_selesai->diffForHumans() }}</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td>
                        <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('cms.promo.edit',$promo) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="{{ route('cms.promo.destroy',$promo) }}" onsubmit="return confirm('Hapus promo ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-tag"></i><p>Belum ada promo</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $promos->links() }}</div>
</div>
@endsection
