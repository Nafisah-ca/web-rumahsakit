@extends('layouts.admin')
@php $pageTitle = 'Akses CMS'; $breadcrumb = 'Admin / Akses CMS'; @endphp

@section('content')
<div class="max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="card card-body text-center mb-6" style="padding:40px 32px">
        <div style="width:72px;height:72px;background:#dbeafe;border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:28px">
            <i class="fas fa-pen-nib" style="color:#2563eb"></i>
        </div>
        <h1 style="font-size:22px;font-weight:800;color:#0f172a;margin-bottom:8px">Portal CMS</h1>
        <p style="font-size:14px;color:#64748b;max-width:380px;margin:0 auto">
            Anda akan mengakses sistem manajemen konten (CMS). Di sini Anda dapat mengelola artikel, event, banner, dan konten website lainnya.
        </p>
    </div>

    {{-- Info Admin --}}
    <div class="card card-body mb-6" style="display:flex;align-items:center;gap:16px;padding:20px 24px">
        <div style="width:48px;height:48px;border-radius:50%;background:#16a34a;display:flex;align-items:center;justify-content:center;color:#fff;font-size:18px;font-weight:800;flex-shrink:0">
            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
        </div>
        <div>
            <p style="font-size:15px;font-weight:700;color:#0f172a">{{ Auth::user()->nama }}</p>
            <p style="font-size:12px;color:#64748b">{{ Auth::user()->email }}</p>
        </div>
        <span class="badge badge-red" style="margin-left:auto">Administrator</span>
    </div>

    {{-- Akses CMS --}}
    <div class="card card-body mb-4" style="padding:28px 32px">
        <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px">Yang bisa Anda kelola di CMS:</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:24px">
            @foreach([
                ['fas fa-newspaper',     'Artikel & Berita',   '#dbeafe','#2563eb'],
                ['fas fa-calendar-days', 'Event & Kegiatan',   '#ede9fe','#7c3aed'],
                ['fas fa-tag',           'Promo',              '#fef3c7','#d97706'],
                ['fas fa-panorama',      'Banner Website',     '#fce7f3','#db2777'],
                ['fas fa-image',         'Banner Halaman',     '#dcfce7','#16a34a'],
                ['fas fa-sliders',       'Pengaturan Website', '#f1f5f9','#475569'],
            ] as [$ico, $lbl, $bg, $clr])
            <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:{{ $bg }};border-radius:10px">
                <i class="{{ $ico }}" style="color:{{ $clr }};width:16px;text-align:center"></i>
                <span style="font-size:13px;font-weight:500;color:#334155">{{ $lbl }}</span>
            </div>
            @endforeach
        </div>

        <div style="display:flex;gap:10px">
            <a href="{{ route('cms.dashboard') }}"
               class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                <i class="fas fa-arrow-right-to-bracket"></i> Masuk ke CMS
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary" style="padding:12px 20px">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <p style="text-align:center;font-size:11px;color:#94a3b8">
        Anda tetap login sebagai <strong>Administrator</strong>. Role tidak berubah saat mengakses CMS.
    </p>

</div>
@endsection
