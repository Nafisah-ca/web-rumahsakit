@extends('layouts.app')

@push('styles')
<style>
/* ── SECTION DIVIDER ──────────────────────────────────────── */
.section-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 2px;
    background: #f3f4f6;
    position: relative;
    margin: 0;
}
.section-divider::before {
    content: '';
    width: 48px;
    height: 3px;
    background: linear-gradient(90deg, #00521f, #00b04f);
    border-radius: 999px;
    position: absolute;
    top: -1px;
}

/* ── Semua section pakai bg putih bersih ─────────────────── */
.section-home { background: #fff; }
/* ── CURSOR GLOW ─────────────────────────────────────── */
#cursor-glow {
    position: fixed;
    width: 320px;
    height: 320px;
    border-radius: 50%;
    pointer-events: none;
    z-index: 0;
    background: radial-gradient(circle, rgba(0,176,79,.07) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    transition: opacity .4s;
}

/* ── HERO TEKS ANIMASI ───────────────────────────────── */
.hero-word {
    display: inline-block;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity .5s ease, transform .5s ease;
}
.hero-word.in { opacity: 1; transform: translateY(0); }

/* ── COUNTER ANIMATED ────────────────────────────────── */
.stat-number { transition: color .3s; }

/* ── CARD TILT ───────────────────────────────────────── */
.tilt-card {
    transition: transform .2s ease, box-shadow .2s ease;
    transform-style: preserve-3d;
    will-change: transform;
}

/* ── SPESIALISASI PILL WAVE ──────────────────────────── */
.spesialis-pill {
    animation: pill-in .4s both;
}
@keyframes pill-in {
    from { opacity:0; transform:scale(.85) translateY(8px); }
    to   { opacity:1; transform:scale(1) translateY(0); }
}

/* ── SECTION LABEL TYPEWRITER ────────────────────────── */
.label-typed::after {
    content: '|';
    animation: blink-cur .7s step-end infinite;
    margin-left: 1px;
    font-weight: 300;
    opacity: .6;
}
@keyframes blink-cur { 0%,100%{opacity:.6;} 50%{opacity:0;} }

/* ── SCROLL PROGRESS BAR ─────────────────────────────── */
#scroll-progress {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    background: linear-gradient(90deg, #00b04f, #00d46a);
    z-index: 9999;
    width: 0%;
    transition: width .1s linear;
    border-radius: 0 2px 2px 0;
}

/* ── QUICK MENU RIPPLE ───────────────────────────────── */
.quick-menu-item { position: relative; overflow: hidden; }
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(0,176,79,.18);
    transform: scale(0);
    animation: ripple-anim .55s linear;
    pointer-events: none;
}
@keyframes ripple-anim {
    to { transform: scale(3.5); opacity: 0; }
}

/* ── ULASAN CARD ENTER ───────────────────────────────── */
.ulasan-card-home {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity .5s ease, transform .5s ease, box-shadow .2s ease;
}
.ulasan-card-home.visible {
    opacity: 1;
    transform: translateY(0);
}
.ulasan-card-home:hover {
    box-shadow: 0 12px 32px rgba(0,176,79,.1);
    transform: translateY(-4px) !important;
}

/* ── ULASAN REDESIGN ─────────────────────────────────── */
/* Summary wrapper */
.ulasan-summary-wrap {
    display: flex;
    align-items: stretch;
    gap: 16px;
    background: linear-gradient(135deg,#00521f,#00b04f);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 32px;
    flex-wrap: wrap;
}
/* Score block */
.ulasan-score-block {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 100px;
    flex-shrink: 0;
}
.ulasan-score-num {
    font-size: 52px;
    font-weight: 900;
    color: #fff;
    line-height: 1;
    letter-spacing: -3px;
}
.ulasan-score-stars {
    display: flex;
    gap: 3px;
    margin: 6px 0 4px;
}
.ulasan-score-stars i { font-size: 13px; }
.ulasan-score-label { font-size: 11px; color: rgba(255,255,255,.7); font-weight: 600; }
/* Bars */
.ulasan-bars { flex: 1; min-width: 160px; display: flex; flex-direction: column; justify-content: center; gap: 6px; }
.ulasan-bar-row { display: flex; align-items: center; gap: 7px; }
.ulasan-bar-num { font-size: 11px; font-weight: 700; color: rgba(255,255,255,.8); width: 12px; text-align: right; flex-shrink: 0; }
.ulasan-bar-track { flex: 1; height: 7px; background: rgba(255,255,255,.2); border-radius: 999px; overflow: hidden; }
.ulasan-bar-fill { height: 100%; border-radius: 999px; transition: width 1s cubic-bezier(.4,0,.2,1); }
.ulasan-bar-count { font-size: 11px; color: rgba(255,255,255,.65); width: 20px; flex-shrink: 0; }
/* CTA button */
.ulasan-cta-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: rgba(255,255,255,.15);
    border: 1.5px solid rgba(255,255,255,.3);
    color: #fff;
    border-radius: 14px;
    padding: 14px 20px;
    text-decoration: none;
    transition: background .15s;
    flex-shrink: 0;
    text-align: center;
}
.ulasan-cta-btn:hover { background: rgba(255,255,255,.25); }

/* Banyak kartu ukuran sama, tidak terlalu tinggi */
.ulasan-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 14px;
    align-items: stretch;
}

/* Kartu individu */
.ulasan-card-v2 {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f0f0f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    padding: 14px 16px;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: translateY(16px);
    transition: opacity .4s ease calc(var(--delay,0ms)),
                transform .4s ease calc(var(--delay,0ms)),
                box-shadow .2s ease,
                border-color .2s ease;
    width: 100%;
    height: 148px;
}
.ulasan-card-v2.card-visible { opacity: 1; transform: translateY(0); }
.ulasan-card-v2:hover {
    box-shadow: 0 10px 30px rgba(0,0,0,.1);
    border-color: #d1fae5;
    transform: translateY(-4px);
}
/* Quote aksen */
.ulasan-quote {
    position: absolute;
    top: 12px;
    right: 16px;
    font-size: 36px;
    line-height: 1;
    color: #f0fdf4;
    font-family: Georgia, serif;
    pointer-events: none;
    user-select: none;
}
/* Avatar */
.ulasan-avatar {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    border: 2px solid;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 17px;
    font-weight: 900;
    letter-spacing: -1px;
}
/* Head */
.ulasan-card-head { display: flex; align-items: flex-start; gap: 11px; margin-bottom: 8px; }
.ulasan-meta { flex: 1; min-width: 0; }
.ulasan-nama { font-size: 13px; font-weight: 800; color: #111; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px; }
.ulasan-rbadge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 999px;
    border: 1px solid;
    white-space: nowrap;
    flex-shrink: 0;
}
.ulasan-stars-row { display: flex; align-items: center; gap: 2px; margin-top: 4px; }
.ulasan-date { font-size: 10px; color: #9ca3af; margin-left: 6px; }
/* Body */
.ulasan-judul { font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 4px; line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ulasan-isi {
    font-size: 12px;
    color: #6b7280;
    line-height: 1.55;
    flex: 1;
    min-height: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* ── STAT NUMBER PULSE ───────────────────────────────── */
@keyframes num-pop {
    0%   { transform: scale(1); }
    40%  { transform: scale(1.12); }
    100% { transform: scale(1); }
}
.stat-pop { animation: num-pop .4s ease; }

/* ── PROMO & CARD SHINE ──────────────────────────────── */
.shine-card { position: relative; overflow: hidden; }
.shine-card::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -75%;
    width: 50%;
    height: 200%;
    background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.18) 50%, transparent 60%);
    transform: skewX(-15deg);
    transition: left .6s ease;
    pointer-events: none;
}
.shine-card:hover::after { left: 125%; }
</style>
@endpush

@section('content')

{{-- ===== HERO SLIDER ===== --}}
<div class="hero-slider">

    @php
    $gradients = [
        'linear-gradient(90deg, rgba(0,82,31,0.92) 0%, rgba(0,176,79,0.82) 100%)',
        'linear-gradient(90deg, rgba(124,45,18,0.92) 0%, rgba(194,65,12,0.82) 100%)',
        'linear-gradient(90deg, rgba(30,58,95,0.92) 0%, rgba(15,76,129,0.82) 100%)',
        'linear-gradient(90deg, rgba(76,29,149,0.92) 0%, rgba(124,58,237,0.82) 100%)',
        'linear-gradient(90deg, rgba(5,95,70,0.92) 0%, rgba(16,185,129,0.82) 100%)',
    ];
    @endphp

    @forelse($banners as $i => $banner)
    <div class="slide" @if($banner->gambar) style="background-image: url('{{ Storage::url($banner->gambar) }}'); background-size: cover; background-position: center;" @endif>
        <div style="{{ $gradients[$i % count($gradients)] }}; height:100%;"></div>
        <div class="slide-content">
            <div class="max-w-screen-xl mx-auto px-6 w-full">
                <div class="max-w-xl">
                    <span class="inline-block bg-white/20 border border-white/30 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-widest backdrop-blur-sm">
                        {{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}
                    </span>
                    <h1 class="text-white font-black text-4xl md:text-5xl leading-tight mb-4">
                        {!! nl2br(e($banner->judul)) !!}
                    </h1>
                    @if($banner->deskripsi)
                    <p class="text-green-100 text-base mb-7 leading-relaxed max-w-md">
                        {{ $banner->deskripsi }}
                    </p>
                    @endif
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dokter') }}" class="flex items-center gap-2 bg-white text-green-700 px-6 py-3 rounded-xl font-bold text-sm shadow-xl hover:bg-green-50 transition-all hover:-translate-y-0.5">
                            <i class="fas fa-calendar-check"></i> Daftar Poliklinik
                        </a>
                        <a href="{{ route('layanan') }}" class="flex items-center gap-2 border-2 border-white/60 hover:border-white text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 backdrop-blur-sm">
                            <i class="fas fa-info-circle"></i> Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    {{-- Fallback jika belum ada banner di database --}}
    <div class="slide">
        <div style="background: linear-gradient(90deg, rgba(0,82,31,0.95) 0%, rgba(0,176,79,0.85) 100%); height:100%;"></div>
        <div class="slide-content">
            <div class="max-w-screen-xl mx-auto px-6 w-full">
                <div class="max-w-xl">
                    <span class="inline-block bg-white/20 border border-white/30 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-widest backdrop-blur-sm">Selamat Datang</span>
                    <h1 class="text-white font-black text-4xl md:text-5xl leading-tight mb-4">
                        Melayani dengan<br><span class="text-yellow-300">Kasih Sayang</span>
                    </h1>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dokter') }}" class="flex items-center gap-2 bg-white text-green-700 px-6 py-3 rounded-xl font-bold text-sm shadow-xl hover:bg-green-50 transition-all">
                            <i class="fas fa-calendar-check"></i> Daftar Poliklinik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforelse

    {{-- Arrows --}}
    <button class="slide-arrow prev" aria-label="Previous"><i class="fas fa-chevron-left text-sm"></i></button>
    <button class="slide-arrow next" aria-label="Next"><i class="fas fa-chevron-right text-sm"></i></button>
    {{-- Dots --}}
    <div class="slide-dots">
        @foreach($banners as $b)<span class="dot"></span>@endforeach
        @if($banners->isEmpty())<span class="dot"></span>@endif
    </div>
</div>

{{-- ===== QUICK MENU ===== --}}
<div class="quick-menu">
    <div class="quick-menu-grid">
        <a href="{{ route('dokter') }}" class="quick-menu-item">
            <i class="fas fa-calendar-check"></i>
            <span>Daftar Poliklinik</span>
        </a>
        <a href="{{ route('dokter.online') }}" class="quick-menu-item">
            <i class="fas fa-laptop-medical"></i>
            <span>Layanan Online</span>
        </a>
        <a href="{{ route('promo') }}" class="quick-menu-item">
            <i class="fas fa-tags"></i>
            <span>Paket Promo</span>
        </a>
        <a href="{{ route('mcu') }}" class="quick-menu-item">
            <i class="fas fa-clipboard-check"></i>
            <span>Medical Checkup</span>
        </a>
        <a href="{{ route('layanan') }}" class="quick-menu-item">
            <i class="fas fa-spa"></i>
            <span>Pelayanan Khusus</span>
        </a>
        <a href="{{ route('home') }}" class="quick-menu-item">
            <i class="fas fa-ellipsis-h"></i>
            <span>Lainnya</span>
        </a>
    </div>
</div>

{{-- ===== JADWAL SHOLAT SECTION ===== --}}
@if(!empty($jadwal_sholat_data['times']))
<section class="py-5 bg-gradient-to-r from-emerald-950 via-emerald-900 to-teal-950 text-white relative overflow-hidden border-y border-emerald-800/40 shadow-inner">
    <div class="max-w-screen-xl mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-5">

            {{-- Header info --}}
            <div class="flex items-center gap-3.5 text-center lg:text-left">
                <div class="w-11 h-11 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center flex-shrink-0 text-emerald-300 text-xl shadow-inner">
                    <i class="fas fa-kaaba"></i>
                </div>
                <div>
                    <div class="flex items-center justify-center lg:justify-start gap-2">
                        <h3 class="text-sm font-extrabold text-white tracking-tight">Jadwal Waktu Sholat</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ ($jadwal_sholat_data['sumber'] ?? '') === 'api' ? 'bg-emerald-500/30 text-emerald-200 border border-emerald-400/40' : 'bg-amber-500/30 text-amber-200 border border-amber-400/40' }}">
                            {{ ($jadwal_sholat_data['sumber'] ?? '') === 'api' ? '● Kemenag RI' : '● Database' }}
                        </span>
                    </div>
                    <p class="text-xs text-emerald-100/80 mt-0.5 flex items-center justify-center lg:justify-start gap-1.5">
                        <i class="fas fa-location-dot text-emerald-400 text-[10px]"></i>
                        <span>{{ $jadwal_sholat_data['lokasi'] ?? 'Indonesia' }}</span>
                        <span class="opacity-50">•</span>
                        <span>{{ $jadwal_sholat_data['tanggal_label'] ?? now()->translatedFormat('l, d M Y') }}</span>
                    </p>
                </div>
            </div>

            {{-- Grid Waktu Sholat --}}
            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 w-full lg:w-auto">
                @php
                    $prayers = [
                        'imsak'   => ['label' => 'Imsak',   'icon' => 'fa-moon'],
                        'subuh'   => ['label' => 'Subuh',   'icon' => 'fa-cloud-sun'],
                        'dhuha'   => ['label' => 'Dhuha',   'icon' => 'fa-sun'],
                        'dzuhur'  => ['label' => 'Dzuhur',  'icon' => 'fa-sun'],
                        'ashar'   => ['label' => 'Ashar',   'icon' => 'fa-cloud-sun'],
                        'maghrib' => ['label' => 'Maghrib', 'icon' => 'fa-moon'],
                        'isya'    => ['label' => 'Isya',    'icon' => 'fa-star-and-crescent'],
                    ];
                    $nextPrayer = $jadwal_sholat_data['sholat_berikutnya']['nama'] ?? '';
                @endphp

                @foreach($prayers as $key => $p)
                @php
                    $isNext = strtolower($nextPrayer) === $key;
                    $jam    = $jadwal_sholat_data['times'][$key] ?? '-';
                @endphp
                <div class="relative px-3 py-2 rounded-xl text-center transition-all duration-200 {{ $isNext ? 'bg-emerald-500/30 border border-emerald-300 shadow-md shadow-emerald-950/40 ring-1 ring-emerald-400/50' : 'bg-white/10 hover:bg-white/15 border border-white/10' }}">
                    @if($isNext)
                    <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-amber-400 text-amber-950 text-[8px] font-black px-1.5 py-0.2 rounded-full uppercase tracking-wider shadow">
                        Berikutnya
                    </span>
                    @endif
                    <div class="text-[10px] font-semibold {{ $isNext ? 'text-emerald-200 font-bold' : 'text-emerald-100/70' }} flex items-center justify-center gap-1">
                        <i class="fas {{ $p['icon'] }} text-[8px]"></i> {{ $p['label'] }}
                    </div>
                    <div class="text-xs font-extrabold tracking-tight mt-0.5 font-mono text-white">
                        {{ $jam }}
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
@endif

{{-- ===== SPESIALISASI ===== --}}
<div class="section-divider"></div>
<section class="py-14 bg-white"><div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Pelayanan Spesialis</span>
                <h2 class="section-title">Spesialisasi <span>Kami</span></h2>
                <p class="text-gray-500 text-sm mt-2 max-w-xl">Rumah Sakit Sari Sehat senantiasa berupaya memberikan mutu pelayanan berkualitas dengan didukung para dokter ahli dan fasilitas modern.</p>
            </div>
            <a href="{{ route('dokter') }}" class="btn-outline-green hidden md:inline-flex flex-shrink-0 ml-8">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="flex flex-wrap gap-3 fade-up">
            @forelse($spesialisasi as $sp)
            <a href="{{ route('dokter.by-spesialis', ['spSlug' => $sp->id]) }}" class="spesialis-pill">
                <i class="fas {{ $sp->icon ?? 'fa-stethoscope' }}"></i> {{ $sp->nama_spesialis }}
            </a>
            @empty
            <div class="text-gray-500 text-sm">Belum ada data spesialisasi</div>
            @endforelse
        </div>
        <div class="mt-6 md:hidden fade-up">
            <a href="{{ route('dokter') }}" class="btn-outline-green w-full justify-center">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- ===== TENTANG / STATS ===== --}}
<div class="section-divider"></div>
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="rounded-2xl overflow-hidden shadow-xl fade-up" style="background: linear-gradient(135deg, #00521f 0%, #00b04f 100%);">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                {{-- Teks --}}
                <div class="p-8 md:p-12">
                    <span class="section-label text-green-300">Sekilas Tentang</span>
                    <h2 class="text-white font-extrabold text-2xl md:text-3xl leading-tight mb-2">
                        {{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }}
                    </h2>
                    @if($setting->motto ?? null)
                    <p class="text-green-200 text-sm font-semibold italic mb-4" style="font-family: Georgia, serif; letter-spacing: 0.02em;">
                        &ldquo;{{ $setting->motto }}&rdquo;
                    </p>
                    @endif
                    <p class="text-green-100 leading-relaxed text-sm mb-6">
                        @if($setting->tentang_kami ?? null)
                            {{ $setting->tentang_kami }}
                        @else
                            Rumah Sakit Sari Sehat berada di Depok dengan motto <strong class="text-white">"{{ $setting->motto ?? 'Melayani dengan Kasih Sayang' }}"</strong>, dan senantiasa memberikan mutu pelayanan yang profesional kepada seluruh pasien kami.
                        @endif
                    </p>
                    <a href="{{ route('tentang') }}" class="inline-flex items-center gap-2 bg-white text-green-700 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-green-50 transition-all shadow-md">
                        Tentang Kami <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                {{-- Stats --}}
                <div id="stats-section" class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6 border-t lg:border-t-0 lg:border-l border-white/20 py-4 sm:py-0">
                    @foreach([
                        [$setting->jumlah_spesialisasi ?? 5,'Spesialisasi','fa-stethoscope'],
                        [$setting->jumlah_mitra_asuransi ?? 50,'Mitra Asuransi','fa-handshake'],
                    ] as [$val,$lbl,$ico])
                    <div class="stat-box w-full sm:w-1/2 flex flex-col items-center justify-center">
                        <i class="fas {{ $ico }} text-green-300 text-xl mb-2 block"></i>
                        <div class="stat-number">{{ $val }}+</div>
                        <div class="stat-label">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROMO ===== --}}
<div class="section-divider"></div>
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Penawaran Terbaik</span>
                <h2 class="section-title">Promo</h2>
            </div>
            <a href="{{ route('promo') }}" class="btn-outline-green hidden md:inline-flex">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 fade-up">
            @forelse($promos as $promo)
            <a href="{{ route('promo.detail', $promo) }}" class="shine-card tilt-card group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="relative flex-shrink-0" style="height:160px; background: linear-gradient(135deg, #00521f, #00b04f)">
                    @if($promo->gambar)
                    <img src="{{ Storage::url($promo->gambar) }}" alt="{{ $promo->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-tag text-4xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-green-600 text-white text-[10px] font-black px-2 py-1 rounded-full">PROMO</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-10" style="background:linear-gradient(to top,rgba(0,0,0,.3),transparent)">
                        <span class="absolute bottom-2 left-3 text-white text-[10px] font-bold">Selengkapnya</span>
                    </div>
                </div>
                <div class="flex flex-col flex-1 p-4">
                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1 group-hover:text-green-600 transition-colors line-clamp-2">
                        {{ $promo->judul }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 flex-1">
                        {{ Str::limit(strip_tags($promo->deskripsi ?? ''), 80) }}
                    </p>
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center gap-1 text-xs text-red-500 font-semibold">
                            <i class="fas fa-clock"></i>
                            Berakhir: {{ $promo->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas' }}
                        </div>
                        <span class="text-xs font-bold text-green-700">Detail →</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-10 text-gray-400">
                <i class="fas fa-tag text-4xl opacity-20 block mb-3"></i>
                <p>Belum ada promo tersedia</p>
            </div>
            @endforelse
        </div>
        <div class="mt-5 md:hidden text-center fade-up">
            <a href="{{ route('promo') }}" class="btn-outline-green">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

{{-- ===== EVENT ===== --}}
<div class="section-divider"></div>
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Jadwal Kegiatan</span>
                <h2 class="section-title">Event</h2>
            </div>
            <a href="{{ route('event') }}" class="btn-outline-green hidden md:inline-flex">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 fade-up">
            @forelse($events as $ev)
            <a href="{{ route('event.detail', $ev) }}" class="shine-card tilt-card group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="relative flex-shrink-0" style="height:160px; background: linear-gradient(135deg,#4c1d95,#7c3aed)">
                    @if($ev->gambar)
                    <img src="{{ asset('storage/' . $ev->gambar) }}" alt="{{ $ev->judul }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-calendar-star text-4xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-purple-600 text-white text-[10px] font-black px-2 py-1 rounded-full">EVENT</span>
                    </div>
                    <div class="absolute top-3 right-3 flex items-center gap-1.5 bg-white text-gray-800 text-xs font-black px-2.5 py-1.5 rounded-lg shadow-md">
                        <i class="fas fa-calendar-alt text-green-600 text-[11px]"></i>
                        {{ $ev->tanggal_event?->format('d M Y') }}
                    </div>
                </div>
                <div class="flex flex-col flex-1 p-4">
                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1 group-hover:text-purple-600 transition-colors line-clamp-2">
                        {{ $ev->judul }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 flex-1">
                        {{ Str::limit(strip_tags($ev->deskripsi ?? ''), 80) }}
                    </p>
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                        @if($ev->lokasi)
                        <span class="text-xs text-gray-400 truncate max-w-[60%]"><i class="fas fa-location-dot mr-1 text-purple-400"></i>{{ $ev->lokasi }}</span>
                        @else
                        <span class="text-xs text-gray-400">{{ $ev->tanggal_event?->format('d M Y') }}</span>
                        @endif
                        <span class="text-xs font-bold text-purple-700 flex-shrink-0">Detail →</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-10 text-gray-400">
                <i class="fas fa-calendar-days text-4xl opacity-20 block mb-3"></i>
                <p class="text-sm">Belum ada event mendatang</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== INFORMASI / ARTIKEL ===== --}}
<div class="section-divider"></div>
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Kesehatan Terkini</span>
                <h2 class="section-title">Informasi <span>Terkini</span></h2>
            </div>
            @if($totalInformasi > 6)
            <a href="{{ route('informasi') }}" class="btn-outline-green hidden md:inline-flex">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 fade-up">
            @forelse($informasis as $info)
            <a href="{{ route('informasi.detail', $info) }}" class="shine-card tilt-card group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="relative flex-shrink-0" style="height:160px; background:linear-gradient(135deg,#1e3a5f,#0284c7)">
                    @if($info->gambar)
                    <img src="{{ Storage::url($info->gambar) }}" alt="{{ $info->judul }}" class="absolute inset-0 w-full h-full object-cover">
                    @elseif($info->thumbnail)
                    <img src="{{ Storage::url($info->thumbnail) }}" alt="{{ $info->judul }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-newspaper text-4xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-blue-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full">INFO</span>
                    </div>
                </div>
                <div class="flex flex-col flex-1 p-4">
                    <span class="text-gray-400 text-[10px] mb-1.5">{{ $info->created_tm?->format('d M Y') }}</span>
                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1 group-hover:text-green-600 transition-colors line-clamp-2 flex-1">
                        {{ $info->judul }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">
                        {{ Str::limit(strip_tags($info->isi ?? ''), 80) }}
                    </p>
                    <div class="mt-2 text-xs font-bold text-green-700">Baca Selengkapnya →</div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-10 text-gray-400">
                <i class="fas fa-newspaper text-4xl opacity-20 block mb-3"></i>
                <p class="text-sm">Belum ada artikel terkini</p>
            </div>
            @endforelse
        </div>
        <div class="mt-6 md:hidden text-center fade-up">
            @if($totalInformasi > 6)
            <a href="{{ route('informasi') }}" class="btn-outline-green">Lihat Semua <i class="fas fa-arrow-right"></i></a>
            @endif
        </div>
    </div>
</section>

{{-- ===== ULASAN PASIEN ===== --}}
@if($ulasanHome->count() > 0)
<div class="section-divider"></div>
<section class="py-16 bg-white" id="section-ulasan">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Header --}}
        <div class="flex items-end justify-between mb-10 fade-up">
            <div>
                <span class="section-label">Testimoni</span>
                <h2 class="section-title">Apa Kata <span>Pasien Kami</span></h2>
                <p class="text-gray-500 text-sm mt-2">Pengalaman nyata pasien yang telah mempercayakan kesehatan mereka kepada kami.</p>
            </div>
            <a href="{{ route('ulasan.public') }}" class="btn-outline-green hidden md:inline-flex flex-shrink-0 ml-8">
                Semua Ulasan <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        {{-- Rating summary bar --}}
        @php
            $avgRating   = $ulasanHome->avg('rating');
            $totalUlasan = \App\Models\Ulasan::approved()->count();
            // Warna bar per bintang
            $barColors = [5=>'#22c55e',4=>'#84cc16',3=>'#eab308',2=>'#f97316',1=>'#ef4444'];
        @endphp
        <div class="ulasan-summary-wrap fade-up">
            {{-- Score besar --}}
            <div class="ulasan-score-block">
                <div class="ulasan-score-num">{{ number_format($avgRating, 1) }}</div>
                <div class="ulasan-score-stars">
                    @for($i=1;$i<=5;$i++)
                    <i class="fas fa-star" style="color:{{ $i<=round($avgRating)?'#fbbf24':'rgba(255,255,255,.3)' }}"></i>
                    @endfor
                </div>
                <div class="ulasan-score-label">{{ $totalUlasan }} ulasan</div>
            </div>
            {{-- Bar breakdown --}}
            <div class="ulasan-bars">
                @for($s=5;$s>=1;$s--)
                @php
                    $cnt = \App\Models\Ulasan::approved()->where('rating',$s)->count();
                    $pct = $totalUlasan>0 ? round(($cnt/$totalUlasan)*100) : 0;
                @endphp
                <div class="ulasan-bar-row">
                    <span class="ulasan-bar-num">{{ $s }}</span>
                    <i class="fas fa-star" style="font-size:10px;color:{{ $barColors[$s] }}"></i>
                    <div class="ulasan-bar-track">
                        <div class="ulasan-bar-fill"
                             data-pct="{{ $pct }}"
                             style="background:{{ $barColors[$s] }};width:0%"></div>
                    </div>
                    <span class="ulasan-bar-count">{{ $cnt }}</span>
                </div>
                @endfor
            </div>
            {{-- CTA --}}
            <a href="{{ route('kontak') }}#ulasan-form" class="ulasan-cta-btn">
                <i class="fas fa-pen-to-square" style="font-size:20px"></i>
                <span style="font-size:12px;font-weight:800">Tulis<br>Ulasan</span>
            </a>
        </div>

        {{-- Grid kartu --}}
        <div class="ulasan-grid fade-up" id="ulasan-home-grid">
            @foreach($ulasanHome as $idx => $u)
            @php
                $palettes = [
                    ['#dcfce7','#166534','#86efac'],
                    ['#dbeafe','#1d4ed8','#93c5fd'],
                    ['#fce7f3','#be185d','#f9a8d4'],
                    ['#fef9c3','#854d0e','#fde047'],
                    ['#f3e8ff','#7e22ce','#d8b4fe'],
                    ['#ffedd5','#c2410c','#fdba74'],
                    ['#ccfbf1','#0f766e','#5eead4'],
                    ['#e0e7ff','#3730a3','#a5b4fc'],
                ];
                [$abg,$atxt,$aring] = $palettes[$idx % count($palettes)];
                $rbadge = match(true){
                    $u->rating>=5 => ['#f0fdf4','#16a34a','#86efac'],
                    $u->rating>=4 => ['#f0fdf4','#15803d','#bbf7d0'],
                    $u->rating>=3 => ['#fefce8','#a16207','#fde047'],
                    $u->rating>=2 => ['#fff7ed','#c2410c','#fdba74'],
                    default       => ['#fef2f2','#dc2626','#fca5a5'],
                };
            @endphp
            <div class="ulasan-card-v2" style="--delay:{{ $idx * 80 }}ms">
                {{-- Quote aksen --}}
                <span class="ulasan-quote">&ldquo;</span>
                {{-- Header --}}
                <div class="ulasan-card-head">
                    <div class="ulasan-avatar" style="background:{{ $abg }};border-color:{{ $aring }};color:{{ $atxt }}">
                        {{ strtoupper(substr($u->nama,0,1)) }}
                    </div>
                    <div class="ulasan-meta">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                            <p class="ulasan-nama">{{ $u->nama }}</p>
                            <span class="ulasan-rbadge" style="background:{{ $rbadge[0] }};color:{{ $rbadge[1] }};border-color:{{ $rbadge[2] }}">
                                <i class="fas fa-star" style="font-size:9px"></i> {{ $u->rating }}.0
                            </span>
                        </div>
                        <div class="ulasan-stars-row">
                            @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star" style="font-size:10px;color:{{ $i<=$u->rating?'#f59e0b':'#e5e7eb' }}"></i>
                            @endfor
                            <span class="ulasan-date">{{ $u->created_tm?->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                {{-- Body --}}
                @if($u->judul)<p class="ulasan-judul">{{ $u->judul }}</p>@endif
                <p class="ulasan-isi">{{ $u->isi }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-8 text-center fade-up md:hidden">
            <a href="{{ route('ulasan.public') }}" class="btn-outline-green">
                Semua Ulasan <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── 1. SCROLL PROGRESS BAR ──────────────────────────────────────
    const bar = document.createElement('div');
    bar.id = 'scroll-progress';
    document.body.prepend(bar);
    window.addEventListener('scroll', function () {
        const pct = (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100;
        bar.style.width = Math.min(pct, 100) + '%';
    }, { passive: true });


    // ── 2. CURSOR GLOW (desktop only) ──────────────────────────────
    if (window.innerWidth > 1024) {
        const glow = document.createElement('div');
        glow.id = 'cursor-glow';
        document.body.appendChild(glow);
        let mx = -999, my = -999;
        document.addEventListener('mousemove', function (e) {
            mx = e.clientX; my = e.clientY;
            glow.style.left = mx + 'px';
            glow.style.top  = my  + window.scrollY + 'px';
        }, { passive: true });
    }


    // ── 3. QUICK MENU RIPPLE ────────────────────────────────────────
    document.querySelectorAll('.quick-menu-item').forEach(function (item) {
        item.addEventListener('click', function (e) {
            const r  = document.createElement('span');
            const sz = Math.max(item.offsetWidth, item.offsetHeight);
            const rc = item.getBoundingClientRect();
            r.className = 'ripple';
            r.style.cssText = `width:${sz}px;height:${sz}px;left:${e.clientX - rc.left - sz/2}px;top:${e.clientY - rc.top - sz/2}px`;
            item.appendChild(r);
            r.addEventListener('animationend', () => r.remove());
        });
    });


    // ── 4. HERO TEKS ANIMASI KATA PER KATA ─────────────────────────
    function animateHeroText() {
        const active = document.querySelector('.slide.active h1');
        if (!active) return;
        const raw   = active.textContent.trim();
        const words = raw.split(/\s+/);
        active.innerHTML = words.map(w =>
            `<span class="hero-word">${w}</span>`
        ).join(' ');
        active.querySelectorAll('.hero-word').forEach(function (el, i) {
            setTimeout(() => el.classList.add('in'), 80 + i * 80);
        });
    }
    setTimeout(animateHeroText, 200);

    // Re-run setelah slide berganti
    const origGoTo = window.goTo;
    if (typeof origGoTo === 'function') {
        window.goTo = function (n) {
            origGoTo(n);
            setTimeout(animateHeroText, 300);
        };
    }


    // ── 5. CARD 3D TILT ─────────────────────────────────────────────
    document.querySelectorAll('.tilt-card').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            const r  = card.getBoundingClientRect();
            const cx = (e.clientX - r.left)  / r.width  - 0.5;
            const cy = (e.clientY - r.top)   / r.height - 0.5;
            card.style.transform = `perspective(600px) rotateY(${cx * 6}deg) rotateX(${-cy * 6}deg) translateY(-4px)`;
        });
        card.addEventListener('mouseleave', function () {
            card.style.transform = '';
        });
    });


    // ── 6. ULASAN CARD STAGGERED ENTRANCE ──────────────────────────
    const ulasanCards = document.querySelectorAll('.ulasan-card-v2');
    if (ulasanCards.length) {
        const obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.querySelectorAll('.ulasan-card-v2').forEach(function (c) {
                        c.classList.add('card-visible');
                    });
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        const grid = document.getElementById('ulasan-home-grid');
        if (grid) obs.observe(grid);
    }

    // ── 7. RATING BAR ANIMASI ───────────────────────────────────────
    const barObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll('.ulasan-bar-fill').forEach(function (bar) {
                    setTimeout(function () { bar.style.width = bar.dataset.pct + '%'; }, 150);
                });
                barObs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    const summaryWrap = document.querySelector('.ulasan-summary-wrap');
    if (summaryWrap) barObs.observe(summaryWrap);


    // ── 8. SPESIALISASI PILL STAGGER ────────────────────────────────
    document.querySelectorAll('.spesialis-pill').forEach(function (pill, i) {
        pill.style.animationDelay = (i * 50) + 'ms';
    });


    // ── 9. SECTION LABEL SUBTLE ENTRANCE ────────────────────────────
    const labelObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.style.transition = 'opacity .5s ease, letter-spacing .5s ease';
                entry.target.style.opacity    = '1';
                entry.target.style.letterSpacing = '.12em';
                labelObs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });
    document.querySelectorAll('.section-label').forEach(function (lbl) {
        lbl.style.opacity       = '0';
        lbl.style.letterSpacing = '.04em';
        labelObs.observe(lbl);
    });


    // ── 10. SMOOTH NUMBER COUNT UNTUK STATS ─────────────────────────
    const statObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            entry.target.querySelectorAll('.stat-number').forEach(function (el) {
                const raw    = el.textContent.replace(/[^0-9]/g, '');
                const target = parseInt(raw) || 0;
                const suffix = el.textContent.replace(/[0-9]/g, '').trim();
                if (!target) return;
                let curr = 0;
                const step = Math.ceil(target / 40);
                const timer = setInterval(function () {
                    curr = Math.min(curr + step, target);
                    el.textContent = curr.toLocaleString('id-ID') + suffix;
                    if (curr >= target) {
                        clearInterval(timer);
                        el.classList.add('stat-pop');
                        el.addEventListener('animationend', () => el.classList.remove('stat-pop'), { once: true });
                    }
                }, 30);
            });
            statObs.unobserve(entry.target);
        });
    }, { threshold: 0.4 });
    const statsSection = document.getElementById('stats-section');
    if (statsSection) statObs.observe(statsSection);

});
</script>
@endpush
