@extends('layouts.cms')
@php $pageTitle = 'Banner Halaman'; $breadcrumb = 'CMS / Konten Website / Banner Halaman'; @endphp
@section('content')

<div class="card">
    <div class="card-header">
        <h3>Banner Hero — Semua Halaman</h3>
        <p style="font-size:12px;color:#94a3b8;font-weight:500">Klik Edit untuk mengubah banner tiap halaman. Berlaku di semua halaman kecuali Beranda.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:1px;background:#f1f5f9">
        @foreach($pages as $key)
        @php
            $hero    = $heros[$key] ?? null;
            $judul   = $hero?->judul   ?? \App\Models\PageHero::forPage($key)->judul;
            $label   = $hero?->label   ?? \App\Models\PageHero::forPage($key)->label;
            $warnaDari = $hero?->warna_dari ?? \App\Models\PageHero::forPage($key)->warna_dari;
            $warnaKe   = $hero?->warna_ke   ?? \App\Models\PageHero::forPage($key)->warna_ke;
            $gambar    = $hero?->gambar ?? null;
            $isCustom  = $hero !== null;
            $status    = $hero?->status ?? 'aktif';
            $pageNama  = \App\Models\PageHero::pageLabel($key);
        @endphp
        <div style="background:#fff;padding:20px">
            {{-- Preview mini banner --}}
            @php
                $previewBg = $gambar
                    ? "background:url('".Storage::url($gambar)."') center/cover no-repeat"
                    : "background:linear-gradient(135deg,{$warnaDari},{$warnaKe})";
                $previewOverlay = $gambar
                    ? "position:absolute;inset:0;background:linear-gradient(135deg,{$warnaDari}a0,{$warnaKe}88)"
                    : "";
            @endphp
            <div style="height:90px;border-radius:12px;overflow:hidden;margin-bottom:14px;position:relative;{{ $previewBg }};display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px">
                @if($gambar)
                <div style="{{ $previewOverlay }}"></div>
                @endif
                <div style="position:relative;z-index:2;text-align:center;padding:0 10px">
                    @if($label)
                    <span style="font-size:9px;font-weight:800;color:rgba(255,255,255,.8);letter-spacing:.1em;text-transform:uppercase;display:block">{{ $label }}</span>
                    @endif
                    <span style="font-size:14px;font-weight:800;color:#fff;text-shadow:0 1px 4px rgba(0,0,0,.3)">{{ Str::limit($judul, 35) }}</span>
                </div>
                @if($gambar)
                <span style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.35);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:4px;z-index:3">
                    <i class="fas fa-image"></i> Gambar
                </span>
                @endif
            </div>

            {{-- Info --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px">
                <div>
                    <p style="font-size:13px;font-weight:700;color:#0f172a">{{ $pageNama }}</p>
                    <p style="font-size:11px;color:#94a3b8;margin-top:1px">
                        @if($isCustom)
                            <span style="color:#16a34a;font-weight:600"><i class="fas fa-check-circle"></i> Dikustomisasi</span>
                        @else
                            <span style="color:#94a3b8"><i class="fas fa-info-circle"></i> Default</span>
                        @endif
                        &nbsp;·&nbsp;
                        <span style="color:{{ $status === 'aktif' ? '#16a34a' : '#ef4444' }};font-weight:600">{{ ucfirst($status) }}</span>
                    </p>
                </div>
                <div style="display:flex;align-items:center;gap:6px">
                    {{-- Warna preview --}}
                    <div style="display:flex;gap:3px">
                        <div style="width:16px;height:16px;border-radius:4px;background:{{ $warnaDari }};border:1px solid rgba(0,0,0,.1)"></div>
                        <div style="width:16px;height:16px;border-radius:4px;background:{{ $warnaKe }};border:1px solid rgba(0,0,0,.1)"></div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div style="display:flex;gap:8px">
                <a href="{{ route('cms.page-hero.edit', $key) }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                    <i class="fas fa-pen"></i> Edit Banner
                </a>
                @if($isCustom)
                <form method="POST" action="{{ route('cms.page-hero.reset', $key) }}"
                      onsubmit="return confirm('Reset ke default? Semua perubahan termasuk gambar akan dihapus.')">
                    @csrf
                    <button class="btn btn-secondary btn-sm" title="Reset ke default">
                        <i class="fas fa-rotate-left"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
