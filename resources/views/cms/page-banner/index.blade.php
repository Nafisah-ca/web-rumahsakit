@extends('layouts.cms')
@php $pageTitle = 'Banner Halaman'; $breadcrumb = 'CMS / Banner Halaman'; @endphp
@section('content')

<div class="card">
    <div class="card-header">
        <h3>Banner Halaman Publik</h3>
        <p style="font-size:12px;color:#94a3b8;margin-top:2px">Atur teks, warna, dan gambar hero banner untuk setiap halaman.</p>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Halaman</th>
                    <th>Judul</th>
                    <th>Background</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($banners as $b)
                <tr>
                    <td>
                        <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $b->nama_halaman }}</p>
                        <p style="font-size:11px;color:#94a3b8">/{{ $b->page_key }}</p>
                    </td>
                    <td>
                        <p style="font-size:13px;font-weight:600;color:#0f172a">{{ $b->judul }}</p>
                        @if($b->label_atas)
                        <p style="font-size:11px;color:#94a3b8">{{ $b->label_atas }}</p>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:48px;height:28px;border-radius:6px;background:linear-gradient(135deg,{{ $b->warna_awal }},{{ $b->warna_akhir }});border:1px solid #e2e8f0;flex-shrink:0"></div>
                            @if($b->gambar_url)
                            <img src="{{ $b->gambar_url }}" style="width:48px;height:28px;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0">
                            @else
                            <span style="font-size:11px;color:#94a3b8">Warna saja</span>
                            @endif
                        </div>
                    </td>
                    <td><span class="badge {{ $b->status==='aktif'?'badge-green':'badge-slate' }}">{{ $b->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                    <td>
                        <a href="{{ route('cms.page-banner.edit', $b) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-pen"></i> Edit
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-image"></i><p>Belum ada banner halaman</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
