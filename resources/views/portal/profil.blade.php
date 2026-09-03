@extends('layouts.app')

@php
    $penjamins = \App\Models\Penjamin::where('status','aktif')->with('tipePenjamin')->get();
    $activeTab = request('tab', 'profil');
@endphp

@push('styles')
<style>
/* ── IMPORT FONT ─────────────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@600;700&display=swap');

/* ── BASE ────────────────────────────────────────────── */
.profil-page { background: #f5f7f5; min-height: 100vh; padding: 32px 16px 80px; }
.profil-wrap { max-width: 720px; margin: 0 auto; }

/* ── HERO CARD ───────────────────────────────────────── */
.hero-card {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 20px;
    background: #00521f;
    box-shadow: 0 8px 32px rgba(0,82,31,.22);
}
.hero-card-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse at 80% -10%, rgba(0,176,79,.45) 0%, transparent 60%),
        radial-gradient(ellipse at -10% 110%, rgba(0,176,79,.3) 0%, transparent 55%);
}
.hero-card-dots {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
    background-size: 22px 22px;
}
.hero-card-body {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 28px 28px 24px;
    flex-wrap: wrap;
}
/* Avatar upload area */
.avatar-upload {
    position: relative;
    width: 80px;
    height: 80px;
    flex-shrink: 0;
    cursor: pointer;
}
.avatar-upload img,
.avatar-upload .avatar-init {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    object-fit: cover;
    border: 3px solid rgba(255,255,255,.35);
    display: block;
}
.avatar-upload .avatar-init {
    background: rgba(255,255,255,.18);
    backdrop-filter: blur(6px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    font-weight: 900;
    color: #fff;
    font-family: 'Lora', serif;
    letter-spacing: -1px;
}
.avatar-overlay {
    position: absolute;
    inset: 0;
    border-radius: 20px;
    background: rgba(0,0,0,.45);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity .2s;
}
.avatar-upload:hover .avatar-overlay { opacity: 1; }
.avatar-overlay i { color: #fff; font-size: 20px; }
.avatar-upload input[type=file] { display: none; }

.hero-info { flex: 1; min-width: 0; }
.hero-name {
    font-size: 20px;
    font-weight: 700;
    color: #fff;
    font-family: 'Lora', serif;
    letter-spacing: -.3px;
    line-height: 1.2;
    margin-bottom: 4px;
}
.hero-email { font-size: 13px; color: rgba(255,255,255,.65); margin-bottom: 6px; }
.hero-rm {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 8px;
    padding: 3px 10px;
    font-size: 11px;
    color: rgba(255,255,255,.9);
    font-family: 'Courier New', monospace;
    font-weight: 700;
    letter-spacing: .05em;
}
.hero-rm i { font-size: 10px; color: #7ee8a2; }

/* Hero tabs — terintegrasi di dalam hero card */
.hero-tabs {
    position: relative;
    z-index: 2;
    display: flex;
    border-top: 1px solid rgba(255,255,255,.12);
}
.hero-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 13px 8px;
    font-size: 12px;
    font-weight: 700;
    color: rgba(255,255,255,.55);
    text-decoration: none;
    transition: color .2s, background .2s;
    border-top: 2px solid transparent;
    letter-spacing: .02em;
}
.hero-tab:hover { color: rgba(255,255,255,.85); background: rgba(255,255,255,.05); }
.hero-tab.active {
    color: #fff;
    border-top-color: #7ee8a2;
    background: rgba(255,255,255,.07);
}
.hero-tab i { font-size: 12px; }

/* ── FLASH MESSAGES ──────────────────────────────────── */
.flash-success {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-left: 4px solid #22c55e;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 600;
    color: #166534;
    margin-bottom: 18px;
    animation: flash-in .35s ease;
}
.flash-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    border-left: 4px solid #ef4444;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 13px;
    color: #991b1b;
    margin-bottom: 18px;
}
@keyframes flash-in {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}

/* ── SECTION CARD ────────────────────────────────────── */
.section-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8ede9;
    box-shadow: 0 1px 8px rgba(0,0,0,.04);
    padding: 22px 22px 20px;
    margin-bottom: 14px;
    transition: box-shadow .2s;
}
.section-card:focus-within {
    box-shadow: 0 0 0 3px rgba(0,176,79,.08), 0 4px 16px rgba(0,0,0,.06);
    border-color: #b2d8bf;
}
.section-card-title {
    font-size: 13px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    letter-spacing: -.1px;
}
.section-card-title i { color: #00521f; font-size: 13px; }

/* ── FORM FIELDS ─────────────────────────────────────── */
.field-wrap { display: flex; flex-direction: column; gap: 5px; }
.field-label {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #6b7280;
}
.field-label span.req { color: #ef4444; margin-left: 2px; }
.field-input {
    width: 100%;
    padding: 11px 14px;
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    font-size: 13px;
    color: #111827;
    background: #fff;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
    font-family: inherit;
}
.field-input:focus {
    border-color: #00b04f;
    box-shadow: 0 0 0 3px rgba(0,176,79,.1);
}
.field-input::placeholder { color: #d1d5db; }
.field-hint { font-size: 11px; color: #9ca3af; }

/* Grid form */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
@media (max-width: 540px) { .form-grid { grid-template-columns: 1fr; } }

/* ── FOTO CARD ───────────────────────────────────────── */
.foto-card-inner {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #f9fbf9;
    border-radius: 14px;
    padding: 16px;
    border: 1.5px dashed #d1fae5;
    cursor: pointer;
    transition: border-color .2s, background .2s;
}
.foto-card-inner:hover { border-color: #00b04f; background: #f0fdf4; }
.foto-thumb {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid #e4ede7;
    background: #e8f5ec;
    display: flex;
    align-items: center;
    justify-content: center;
}
.foto-thumb img { width:100%; height:100%; object-fit:cover; }
.foto-thumb .foto-init { font-size:22px; font-weight:900; color:#00521f; font-family:'Lora',serif; }
.foto-upload-label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1.5px solid #d1d5db;
    border-radius: 9px;
    padding: 7px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #374151;
    cursor: pointer;
    transition: border-color .15s, color .15s;
}
.foto-upload-label:hover { border-color: #00b04f; color: #00521f; }
.foto-file-name { font-size: 11px; color: #9ca3af; margin-top: 4px; }

/* ── SAVE BUTTON ─────────────────────────────────────── */
.btn-save {
    width: 100%;
    background: #00521f;
    color: #fff;
    border: none;
    border-radius: 14px;
    padding: 14px;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: inherit;
    letter-spacing: -.1px;
    box-shadow: 0 4px 16px rgba(0,82,31,.22);
    transition: background .15s, transform .1s, box-shadow .15s;
    position: relative;
    overflow: hidden;
}
.btn-save:hover { background: #003d17; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,82,31,.3); }
.btn-save:active { transform: translateY(0); }
.btn-save .btn-save-ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,.25);
    transform: scale(0);
    animation: ripple-btn .5s linear;
    pointer-events: none;
}
@keyframes ripple-btn { to { transform: scale(3); opacity: 0; } }

/* Loading state tombol */
.btn-save.loading { pointer-events: none; opacity: .75; }
.btn-save .spinner {
    width: 16px; height: 16px;
    border: 2px solid rgba(255,255,255,.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .6s linear infinite;
    display: none;
}
.btn-save.loading .spinner { display: block; }
.btn-save.loading .btn-txt { display: none; }
@keyframes spin { to { transform: rotate(360deg); } }

/* ── PENJAMIN AKTIF BADGE ────────────────────────────── */
.penjamin-active-badge {
    display: flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1px solid #bbf7d0;
    border-radius: 12px;
    padding: 12px 16px;
    margin-bottom: 16px;
}
.penjamin-active-icon {
    width: 32px; height: 32px;
    background: #00521f;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.penjamin-active-icon i { color: #fff; font-size: 13px; }

/* ── TOAST ───────────────────────────────────────────── */
#profil-toast {
    position: fixed;
    bottom: 88px;
    right: 20px;
    z-index: 9999;
    background: #1a2e22;
    color: #fff;
    padding: 12px 18px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 8px 28px rgba(0,0,0,.2);
    transform: translateY(16px);
    opacity: 0;
    transition: opacity .3s ease, transform .3s ease;
    pointer-events: none;
    max-width: 300px;
}
#profil-toast.show { opacity: 1; transform: translateY(0); }
#profil-toast .t-icon {
    width: 26px; height: 26px;
    background: #00b04f;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 12px;
}

/* ── SECTION ENTRANCE ANIMATION ─────────────────────── */
.fade-section {
    opacity: 0;
    transform: translateY(14px);
    transition: opacity .4s ease, transform .4s ease;
}
.fade-section.visible { opacity: 1; transform: translateY(0); }

/* ── FIELD FOCUS GLOW ────────────────────────────────── */
.field-input:focus + .field-hint { color: #00521f; }

/* ── RIWAYAT POLIKLINIK ──────────────────────────────── */
/* Topbar */
.rw-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.rw-btn-new {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #00521f;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    padding: 9px 16px;
    border-radius: 11px;
    text-decoration: none;
    transition: background .15s;
}
.rw-btn-new:hover { background: #003d17; }

/* Section label separator */
.rw-section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #6b7280;
    margin-bottom: 12px;
}
.rw-count-badge {
    font-size: 10px;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 999px;
}
.rw-batal-toggle {
    margin-left: auto;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    padding: 2px 8px;
    border-radius: 6px;
    font-family: inherit;
    transition: color .15s, background .15s;
}
.rw-batal-toggle:hover { color: #374151; background: #f3f4f6; }

/* Booking card aktif */
.rw-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8ede9;
    box-shadow: 0 2px 10px rgba(0,0,0,.05);
    overflow: hidden;
    margin-bottom: 12px;
    transition: box-shadow .2s;
}
.rw-card:hover { box-shadow: 0 4px 20px rgba(0,82,31,.1); }
.rw-card-aktif { border-left: 3px solid #2563eb; }

.rw-card-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px 10px;
    border-bottom: 1px solid #f3f4f6;
    flex-wrap: wrap;
}
.rw-kode {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    font-weight: 700;
    color: #4b5563;
    background: #f3f4f6;
    padding: 3px 9px;
    border-radius: 6px;
}
.rw-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 999px;
    border: 1px solid;
}
.rw-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
.rw-btn-batal {
    font-size: 11px;
    font-weight: 700;
    color: #dc2626;
    background: #fff;
    border: 1.5px solid #fca5a5;
    padding: 5px 11px;
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: background .15s;
}
.rw-btn-batal:hover { background: #fef2f2; }

.rw-card-body {
    padding: 14px 16px;
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 10px 14px;
    align-items: start;
}
.rw-field-label {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #9ca3af;
    margin-bottom: 2px;
}
.rw-field-val {
    font-size: 13px;
    font-weight: 700;
    color: #111;
    line-height: 1.3;
}
.rw-field-sub { font-size: 11px; color: #9ca3af; }
.rw-antrian {
    font-size: 32px;
    font-weight: 900;
    color: #00521f;
    line-height: 1;
    font-family: 'Lora', serif;
}
.rw-keluhan {
    margin: 0 16px 12px;
    padding: 9px 12px;
    background: #f9fafb;
    border-left: 3px solid #d1fae5;
    border-radius: 0 9px 9px 0;
    font-size: 12px;
    color: #374151;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

/* ── GRID ICON SPESIALIS ─────────────────────────────── */
.rw-sp-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 12px;
    margin-bottom: 16px;
}
.rw-sp-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 7px;
    background: none;
    border: none;
    border-radius: 0;
    padding: 8px;
    cursor: pointer;
    font-family: inherit;
    transition: transform .15s;
}
.rw-sp-btn:hover { transform: translateY(-3px); }
.rw-sp-btn.active .rw-sp-icon {
    background: #00521f;
    box-shadow: 0 6px 18px rgba(0,82,31,.35);
}
.rw-sp-btn.active .rw-sp-icon i { color: #fff; }
.rw-sp-btn.active .rw-sp-label { color: #00521f; font-weight: 800; }
.rw-sp-btn.active .rw-sp-count { background: #dcfce7; color: #166534; }

.rw-sp-icon {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .2s, box-shadow .2s;
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
}
.rw-sp-icon i { font-size: 20px; transition: color .2s; }
.rw-sp-label {
    font-size: 11px;
    font-weight: 700;
    color: #374151;
    text-align: center;
    line-height: 1.3;
    transition: color .2s;
}
.rw-sp-count {
    font-size: 10px;
    font-weight: 800;
    background: #dcfce7;
    color: #166534;
    padding: 1px 7px;
    border-radius: 999px;
    transition: background .2s, color .2s;
}

/* Panel riwayat selesai */
.rw-sp-panel {
    background: #fff;
    border: 1.5px solid #d1fae5;
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 16px;
    animation: panel-in .25s ease;
}
@keyframes panel-in {
    from { opacity:0; transform:translateY(-8px); }
    to   { opacity:1; transform:translateY(0); }
}
.rw-sp-panel-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 800;
    color: #00521f;
    margin-bottom: 14px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0fdf4;
}

.rw-history-item {
    background: #f9fafb;
    border-radius: 12px;
    padding: 12px 14px;
    border: 1px solid #f0f0f0;
    transition: box-shadow .15s;
}
.rw-history-item:hover { box-shadow: 0 2px 8px rgba(0,0,0,.06); }

@media (max-width: 480px) {
    .rw-card-body { grid-template-columns: 1fr 1fr; }
    .rw-sp-grid { grid-template-columns: repeat(3, 1fr); }
}
</style>
@endpush

@section('content')
<div class="profil-page">
<div class="profil-wrap">

    {{-- ── HERO CARD ─────────────────────────────────── --}}
    <div class="hero-card">
        <div class="hero-card-bg"></div>
        <div class="hero-card-dots"></div>
        <div class="hero-card-body">

            {{-- Avatar — klik untuk upload foto (hanya di tab profil) --}}
            @if($activeTab === 'profil')
            <label class="avatar-upload" for="foto-input-hero" title="Klik untuk ganti foto">
                @if(Auth::user()->foto)
                    <img id="hero-foto-img" src="{{ Storage::url(Auth::user()->foto) }}" alt="">
                @else
                    <div class="avatar-init" id="hero-init">{{ strtoupper(substr(Auth::user()->nama ?? '?', 0, 1)) }}</div>
                    <img id="hero-foto-img" src="" alt="" style="display:none;width:80px;height:80px;border-radius:20px;object-fit:cover;border:3px solid rgba(255,255,255,.35)">
                @endif
                <div class="avatar-overlay"><i class="fas fa-camera"></i></div>
                <input type="file" id="foto-input-hero" accept="image/*">
            </label>
            @else
            <div style="width:80px;height:80px;border-radius:20px;overflow:hidden;border:3px solid rgba(255,255,255,.35);flex-shrink:0">
                @if(Auth::user()->foto)
                    <img src="{{ Storage::url(Auth::user()->foto) }}" alt="" style="width:100%;height:100%;object-fit:cover">
                @else
                    <div style="width:100%;height:100%;background:rgba(255,255,255,.18);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;color:#fff;font-family:'Lora',serif">
                        {{ strtoupper(substr(Auth::user()->nama ?? '?', 0, 1)) }}
                    </div>
                @endif
            </div>
            @endif

            <div class="hero-info">
                <p class="hero-name" id="hero-name-display">{{ Auth::user()->nama }}</p>
                <p class="hero-email">{{ Auth::user()->email }}</p>
                @if($pasien?->no_rekam_medis)
                <span class="hero-rm"><i class="fas fa-id-card"></i> {{ $pasien->no_rekam_medis }}</span>
                @endif
            </div>
        </div>

        {{-- Tabs --}}
        <div class="hero-tabs">
            <a href="{{ route('portal.profil') }}?tab=profil"
               class="hero-tab {{ $activeTab === 'profil' ? 'active' : '' }}">
                <i class="fas fa-user-pen"></i> Profil Saya
            </a>
            <a href="{{ route('portal.profil') }}?tab=riwayat"
               class="hero-tab {{ $activeTab === 'riwayat' ? 'active' : '' }}">
                <i class="fas fa-calendar-check"></i> Riwayat Poliklinik
            </a>
            <a href="{{ route('portal.profil') }}?tab=penjamin"
               class="hero-tab {{ $activeTab === 'penjamin' ? 'active' : '' }}">
                <i class="fas fa-shield-halved"></i> Penjamin
            </a>
        </div>
    </div>

    {{-- ── FLASH ───────────────────────────────────────── --}}
    @if(session('success'))
    <div class="flash-success" id="flash-success">
        <i class="fas fa-circle-check" style="font-size:16px;flex-shrink:0"></i>
        {{ session('success') }}
    </div>
    <span id="flash-msg" data-msg="{{ session('success') }}" style="display:none"></span>
    @endif
    @if($errors->any())
    <div class="flash-error">
        <ul style="list-style:disc;padding-left:16px;space-y:4px">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════
         TAB: PROFIL
    ══════════════════════════════════════════════════ --}}
    @if($activeTab === 'profil')
    <form method="POST" action="{{ route('portal.profil.update') }}"
          enctype="multipart/form-data"
          id="form-profil">
        @csrf @method('PUT')
        {{-- Input foto hidden — diisi saat klik avatar hero --}}
        <input type="file" name="foto" id="foto-input-form" accept="image/*" style="display:none">

        {{-- ── SECTION: FOTO ───────────────────────────── --}}
        <div class="section-card fade-section" style="transition-delay:.05s">
            <p class="section-card-title"><i class="fas fa-image"></i> Foto Profil</p>
            <label class="foto-card-inner" for="foto-input-secondary">
                <div class="foto-thumb">
                    @if(Auth::user()->foto)
                        <img id="foto-thumb-img" src="{{ Storage::url(Auth::user()->foto) }}" alt="">
                    @else
                        <div class="foto-init" id="foto-thumb-init">{{ strtoupper(substr(Auth::user()->nama ?? '?', 0, 1)) }}</div>
                        <img id="foto-thumb-img" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover">
                    @endif
                </div>
                <div>
                    <p style="font-size:13px;font-weight:700;color:#111;margin-bottom:6px">Ganti foto profil</p>
                    <span class="foto-upload-label">
                        <i class="fas fa-arrow-up-from-bracket" style="font-size:11px"></i> Pilih Foto
                    </span>
                    <p class="foto-file-name" id="foto-file-name">JPG atau PNG, maks. 2MB</p>
                    <input type="file" id="foto-input-secondary" accept="image/*" style="display:none">
                </div>
            </label>
        </div>

        {{-- ── SECTION: IDENTITAS AKUN ─────────────────── --}}
        <div class="section-card fade-section" style="transition-delay:.1s">
            <p class="section-card-title"><i class="fas fa-id-badge"></i> Identitas Akun</p>
            <div class="form-grid">
                <div class="field-wrap" style="grid-column:span 2">
                    <label class="field-label">Nama Lengkap <span class="req">*</span></label>
                    <input class="field-input" type="text" name="nama_lengkap"
                           value="{{ old('nama_lengkap', Auth::user()->nama) }}" required
                           id="input-nama"
                           placeholder="Nama sesuai KTP">
                    <p class="field-hint">Nama ini yang tampil di semua halaman portal.</p>
                </div>
                <div class="field-wrap">
                    <label class="field-label">No. HP / WhatsApp</label>
                    <input class="field-input" type="text" name="telepon"
                           value="{{ old('telepon', Auth::user()->no_hp) }}"
                           placeholder="08xxxxxxxxxx" maxlength="20">
                </div>
                <div class="field-wrap">
                    <label class="field-label">NIK <span class="req">*</span></label>
                    <input class="field-input" type="text" name="nik"
                           value="{{ old('nik', $pasien?->nik) }}"
                           placeholder="16 digit NIK KTP" maxlength="16" required>
                </div>
            </div>
        </div>

        {{-- ── SECTION: DATA MEDIS ──────────────────────── --}}
        <div class="section-card fade-section" style="transition-delay:.15s">
            <p class="section-card-title"><i class="fas fa-notes-medical"></i> Data Medis</p>
            <div class="form-grid">
                <div class="field-wrap">
                    <label class="field-label">Jenis Kelamin <span class="req">*</span></label>
                    <select class="field-input" name="jenis_kelamin" required>
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('jenis_kelamin',$pasien?->jenis_kelamin)=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin',$pasien?->jenis_kelamin)=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="field-wrap">
                    <label class="field-label">Golongan Darah</label>
                    <select class="field-input" name="golongan_darah">
                        <option value="">— Pilih —</option>
                        @foreach(['A','B','AB','O'] as $gb)
                        <option value="{{ $gb }}" {{ old('golongan_darah',$pasien?->golongan_darah)==$gb?'selected':'' }}>{{ $gb }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-wrap">
                    <label class="field-label">Tempat Lahir <span class="req">*</span></label>
                    <input class="field-input" type="text" name="tempat_lahir"
                           value="{{ old('tempat_lahir', $pasien?->tempat_lahir) }}" required>
                </div>
                <div class="field-wrap">
                    <label class="field-label">Tanggal Lahir <span class="req">*</span></label>
                    <input class="field-input" type="date" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $pasien?->tanggal_lahir?->format('Y-m-d')) }}" required>
                </div>
                <div class="field-wrap">
                    <label class="field-label">Agama</label>
                    <select class="field-input" name="agama">
                        <option value="">— Pilih —</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $ag)
                        <option value="{{ $ag }}" {{ old('agama',$pasien?->agama)==$ag?'selected':'' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-wrap">
                    <label class="field-label">Pekerjaan</label>
                    <input class="field-input" type="text" name="pekerjaan"
                           value="{{ old('pekerjaan', $pasien?->pekerjaan) }}"
                           placeholder="Pegawai Swasta, Wiraswasta, dll">
                </div>
                <div class="field-wrap" style="grid-column:span 2">
                    <label class="field-label">Alamat Lengkap <span class="req">*</span></label>
                    <textarea class="field-input" name="alamat" rows="2" required
                              style="resize:none"
                              placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat', $pasien?->alamat) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── SAVE BUTTON ──────────────────────────────── --}}
        <button type="submit" class="btn-save fade-section" style="transition-delay:.2s" id="btn-save-profil">
            <span class="spinner"></span>
            <span class="btn-txt">
                <i class="fas fa-floppy-disk" style="font-size:13px"></i>
                &nbsp;Simpan Profil
            </span>
        </button>
    </form>

    {{-- ══════════════════════════════════════════════════
         TAB: RIWAYAT
    ══════════════════════════════════════════════════ --}}
    @elseif($activeTab === 'riwayat')
    @php
        $allBookings = \App\Models\JanjiTemu::with(['jadwalDokter.dokter.spesialisasi'])
            ->where('pasien_id', $pasien?->id ?? 0)
            ->orderByDesc('tanggal_booking')
            ->get();

        // Pisah: aktif = belum selesai/dibatalkan, selesai = completed
        $bookingsAktif    = $allBookings->whereIn('status', ['pending','approved']);
        $bookingsSelesai  = $allBookings->where('status', 'completed');
        $bookingsBatal    = $allBookings->where('status', 'cancelled');

        // Group SEMUA booking by spesialisasi (bukan hanya selesai)
        $bySpesialis = $allBookings->groupBy(function($b) {
            return $b->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis ?? 'Lainnya';
        });
        // Ambil SEMUA spesialisasi aktif dari database
        $allSpesialisasi = \App\Models\Spesialisasi::orderBy('nama_spesialis')->get();
        // Icon per spesialis (fallback ke stethoscope)
        $spIcon = function(string $nama): string {
            $map = [
                'penyakit dalam'  => 'fa-stethoscope',
                'anak'            => 'fa-baby',
                'kandungan'       => 'fa-person-pregnant',
                'bedah'           => 'fa-scalpel',
                'jantung'         => 'fa-heart-pulse',
                'saraf'           => 'fa-brain',
                'mata'            => 'fa-eye',
                'tht'             => 'fa-ear-deaf',
                'kulit'           => 'fa-hand-dots',
                'gigi'            => 'fa-tooth',
                'paru'            => 'fa-lungs',
                'ortopedi'        => 'fa-bone',
                'urologi'         => 'fa-droplet',
                'gizi'            => 'fa-apple-whole',
                'psikiatri'       => 'fa-brain',
                'rehabilitasi'    => 'fa-person-walking',
                'umum'            => 'fa-user-doctor',
            ];
            $lower = strtolower($nama);
            foreach ($map as $key => $icon) {
                if (str_contains($lower, $key)) return $icon;
            }
            return 'fa-stethoscope';
        };
        $sMap = [
            'pending'   => ['Menunggu',     '#b45309','#fffbeb','#fde68a'],
            'approved'  => ['Dikonfirmasi', '#1d4ed8','#eff6ff','#bfdbfe'],
            'completed' => ['Selesai',      '#166534','#f0fdf4','#bbf7d0'],
            'cancelled' => ['Dibatalkan',   '#991b1b','#fef2f2','#fecaca'],
        ];
    @endphp

    {{-- ── Header + tombol buat janji ── --}}
    <div class="fade-section rw-topbar">
        <p style="font-size:13px;color:#9ca3af">Riwayat semua janji temu kamu</p>
        <a href="{{ route('portal.booking.create') }}" class="rw-btn-new">
            <i class="fas fa-plus" style="font-size:10px"></i> Buat Janji Baru
        </a>
    </div>

    {{-- ══ BAGIAN 1: BOOKING AKTIF (belum selesai) ══ --}}
    @if($bookingsAktif->isNotEmpty())
    <div class="rw-section-label fade-section">
        <i class="fas fa-clock-rotate-left" style="color:#1d4ed8"></i>
        Janji Mendatang
        <span class="rw-count-badge" style="background:#eff6ff;color:#1d4ed8">{{ $bookingsAktif->count() }}</span>
    </div>

    @foreach($bookingsAktif as $bi => $b)
    @php [$sl,$sc,$sbg,$sbd] = $sMap[$b->status] ?? [$b->status,'#4b5563','#f9fafb','#e5e7eb']; @endphp
    <div class="rw-card rw-card-aktif fade-section" style="transition-delay:{{ $bi * 60 }}ms">
        <div class="rw-card-header">
            <span class="rw-kode">{{ $b->kode_booking }}</span>
            <span class="rw-status-badge" style="color:{{ $sc }};background:{{ $sbg }};border-color:{{ $sbd }}">
                <span class="rw-status-dot" style="background:{{ $sc }}"></span>{{ $sl }}
            </span>
            @if(in_array($b->status, ['pending','approved']))
            <form method="POST" action="{{ route('portal.booking.cancel', $b) }}"
                  style="margin-left:auto"
                  onsubmit="return confirm('Batalkan janji temu ini?')">
                @csrf
                <button type="submit" class="rw-btn-batal">
                    <i class="fas fa-xmark" style="font-size:9px"></i> Batalkan
                </button>
            </form>
            @endif
        </div>
        <div class="rw-card-body">
            <div class="rw-field">
                <p class="rw-field-label">Dokter</p>
                <p class="rw-field-val">{{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</p>
                <p class="rw-field-sub">{{ $b->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis ?? '-' }}</p>
            </div>
            <div class="rw-field">
                <p class="rw-field-label">Tanggal</p>
                <p class="rw-field-val">{{ $b->tanggal_booking?->format('d M Y') }}</p>
                <p class="rw-field-sub">{{ $b->jadwalDokter ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : '' }}</p>
            </div>
            <div class="rw-field" style="text-align:center">
                <p class="rw-field-label">Antrian</p>
                <p class="rw-antrian">{{ $b->nomor_antrian ?? '-' }}</p>
            </div>
        </div>
        @if($b->keluhan)
        <div class="rw-keluhan">
            <span class="rw-field-label">Keluhan</span>
            {{ $b->keluhan }}
        </div>
        @endif
    </div>
    @endforeach
    @endif

    {{-- ══ BAGIAN 2: RIWAYAT KUNJUNGAN (semua spesialis) ══ --}}
    @if($allBookings->isNotEmpty())
    <div class="rw-section-label fade-section" style="margin-top:24px">
        <i class="fas fa-circle-check" style="color:#166534"></i>
        Riwayat Kunjungan
        <span class="rw-count-badge" style="background:#f0fdf4;color:#166534">{{ $allBookings->count() }}</span>
    </div>

    {{-- Grid icon spesialis — SEMUA dari database --}}
    <div class="rw-sp-grid fade-section">
        @foreach($allSpesialisasi as $sp)
        @php
            $spNama = $sp->nama_spesialis;
            $count  = $bySpesialis->get($spNama)?->count() ?? 0;
            $ico    = $spIcon($spNama);
        @endphp
        <button type="button"
                class="rw-sp-btn"
                data-sp="{{ Str::slug($spNama) }}"
                data-index="{{ $loop->index }}"
                onclick="toggleSpesialis('{{ Str::slug($spNama) }}', this)">
            <div class="rw-sp-icon">
                <i class="fas {{ $ico }}"></i>
            </div>
            <span class="rw-sp-label">{{ $spNama }}</span>
            <span class="rw-sp-count" style="{{ $count === 0 ? 'background:#f1f5f9;color:#9ca3af' : '' }}">
                {{ $count > 0 ? $count.'x' : '–' }}
            </span>
        </button>
        @endforeach
    </div>

    {{-- Panel per spesialis — semua dari DB, empty state jika belum ada booking --}}
    @foreach($allSpesialisasi as $sp)
    @php
        $spNama = $sp->nama_spesialis;
        $spSlug = Str::slug($spNama);
        $items  = $bySpesialis->get($spNama) ?? collect();
    @endphp
    <div class="rw-sp-panel" id="panel-{{ $spSlug }}" style="display:none">
        <div class="rw-sp-panel-header">
            <i class="fas {{ $spIcon($spNama) }}" style="color:#00521f"></i>
            <span>{{ $spNama }}</span>
            <button type="button" onclick="toggleSpesialis('{{ $spSlug }}')"
                    style="margin-left:auto;background:none;border:none;cursor:pointer;color:#9ca3af;font-size:16px;padding:4px">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        @if($items->isEmpty())
        <div style="padding:20px;text-align:center">
            <i class="fas fa-calendar-plus" style="font-size:26px;color:#d1fae5;display:block;margin-bottom:8px"></i>
            <p style="font-size:13px;color:#9ca3af;margin-bottom:12px">Belum ada kunjungan ke {{ $spNama }}</p>
            <a href="{{ route('portal.booking.create') }}"
               style="display:inline-flex;align-items:center;gap:6px;background:#00521f;color:#fff;padding:7px 16px;border-radius:10px;font-size:12px;font-weight:700;text-decoration:none">
                <i class="fas fa-plus" style="font-size:10px"></i> Buat Janji
            </a>
        </div>
        @else
        <div style="display:flex;flex-direction:column;gap:10px">
            @foreach($items->sortByDesc('tanggal_booking') as $b)
            @php
                $cardStyle = match($b->status) {
                    'completed' => ['#16a34a','#f0fdf4','#bbf7d0','Selesai'],
                    'approved'  => ['#2563eb','#eff6ff','#bfdbfe','Dikonfirmasi'],
                    'pending'   => ['#d97706','#fffbeb','#fde68a','Menunggu'],
                    'cancelled' => ['#dc2626','#fef2f2','#fecaca','Dibatalkan'],
                    default     => ['#64748b','#f8fafc','#e2e8f0',$b->status],
                };
                [$cText,$cBg,$cBorder,$cLabel] = $cardStyle;
            @endphp
            <div style="border-radius:14px;overflow:hidden;border:2px solid {{ $cBorder }};box-shadow:0 2px 10px rgba(0,0,0,.06)">
                {{-- Header berwarna --}}
                <div style="background:{{ $cText }};padding:10px 14px;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span style="font-family:'Courier New',monospace;font-size:11px;font-weight:800;color:#fff;background:rgba(255,255,255,.2);padding:2px 8px;border-radius:6px">
                        {{ $b->kode_booking }}
                    </span>
                    <span style="font-size:11px;font-weight:800;color:#fff;background:rgba(255,255,255,.2);padding:2px 10px;border-radius:999px;display:inline-flex;align-items:center;gap:4px">
                        <span style="width:5px;height:5px;background:#fff;border-radius:50%;display:inline-block"></span>
                        {{ $cLabel }}
                    </span>
                </div>
                {{-- Body --}}
                <div style="background:{{ $cBg }};padding:12px 14px">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        <div>
                            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:{{ $cText }};margin-bottom:2px">Dokter</p>
                            <p style="font-size:13px;font-weight:700;color:#111">{{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</p>
                        </div>
                        <div>
                            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:{{ $cText }};margin-bottom:2px">Tanggal</p>
                            <p style="font-size:13px;font-weight:700;color:#111">{{ $b->tanggal_booking?->format('d M Y') }}</p>
                            <p style="font-size:11px;color:#9ca3af">{{ $b->jadwalDokter ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : '' }}</p>
                        </div>
                        <div>
                            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:{{ $cText }};margin-bottom:2px">No. Antrian</p>
                            <p style="font-size:26px;font-weight:900;color:{{ $cText }};line-height:1;font-family:'Lora',serif">{{ $b->nomor_antrian ?? '-' }}</p>
                        </div>
                        @if($b->keluhan)
                        <div>
                            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:{{ $cText }};margin-bottom:2px">Keluhan</p>
                            <p style="font-size:12px;color:#374151">{{ $b->keluhan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    @endforeach
    @endif

    {{-- ══ DIBATALKAN (collapse kecil) ══ --}}
    @if($bookingsBatal->isNotEmpty())
    <div class="rw-section-label fade-section" style="margin-top:24px">
        <i class="fas fa-circle-xmark" style="color:#991b1b"></i>
        Dibatalkan
        <span class="rw-count-badge" style="background:#fef2f2;color:#991b1b">{{ $bookingsBatal->count() }}</span>
        <button type="button" onclick="toggleBatal()" class="rw-batal-toggle" id="btn-batal-toggle">
            Lihat <i class="fas fa-chevron-down" style="font-size:10px"></i>
        </button>
    </div>
    <div id="panel-batal" style="display:none">
        @foreach($bookingsBatal->sortByDesc('tanggal_booking') as $b)
        <div class="rw-history-item" style="opacity:.65;margin-bottom:8px">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                <span class="rw-kode">{{ $b->kode_booking }}</span>
                <span style="font-size:12px;font-weight:700;color:#991b1b">{{ $b->tanggal_booking?->format('d M Y') }}</span>
                <span style="font-size:12px;color:#9ca3af">— {{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Empty state --}}
    @if($allBookings->isEmpty())
    <div style="background:#fff;border-radius:18px;border:1.5px dashed #d1fae5;padding:56px 24px;text-align:center" class="fade-section">
        <div style="width:64px;height:64px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
            <i class="fas fa-calendar-xmark" style="font-size:24px;color:#bbf7d0"></i>
        </div>
        <p style="font-size:15px;font-weight:800;color:#374151;font-family:'Lora',serif">Belum ada riwayat</p>
        <p style="font-size:13px;color:#9ca3af;margin-top:4px">Yuk buat janji temu pertama kamu.</p>
        <a href="{{ route('portal.booking.create') }}"
           style="display:inline-flex;align-items:center;gap:7px;margin-top:16px;background:#00521f;color:#fff;padding:10px 22px;border-radius:12px;font-size:13px;font-weight:700;text-decoration:none">
            <i class="fas fa-plus" style="font-size:10px"></i> Buat Janji Temu
        </a>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════
         TAB: PENJAMIN
    ══════════════════════════════════════════════════ --}}
    @elseif($activeTab === 'penjamin')
    <form method="POST" action="{{ route('portal.profil.update') }}" class="space-y-4" id="form-penjamin">
        @csrf @method('PUT')
        <input type="hidden" name="nama_lengkap" value="{{ Auth::user()->nama }}">
        <input type="hidden" name="telepon"       value="{{ Auth::user()->no_hp }}">
        <input type="hidden" name="nik"           value="{{ $pasien?->nik ?? '0000000000000000' }}">
        <input type="hidden" name="jenis_kelamin" value="{{ $pasien?->jenis_kelamin ?? 'L' }}">
        <input type="hidden" name="tempat_lahir"  value="{{ $pasien?->tempat_lahir ?? '-' }}">
        <input type="hidden" name="tanggal_lahir" value="{{ $pasien?->tanggal_lahir?->format('Y-m-d') ?? now()->subYears(20)->format('Y-m-d') }}">
        <input type="hidden" name="alamat"        value="{{ $pasien?->alamat ?? '-' }}">

        <div class="section-card fade-section" style="transition-delay:.05s">
            <p class="section-card-title"><i class="fas fa-shield-halved"></i> Penjamin / Asuransi</p>
            <p style="font-size:12px;color:#9ca3af;margin-bottom:16px">
                Isi jika kamu menggunakan BPJS Kesehatan atau asuransi swasta. Data ini dipakai saat pembayaran.
            </p>

            @if($pasien?->penjamin)
            <div class="penjamin-active-badge">
                <div class="penjamin-active-icon"><i class="fas fa-circle-check"></i></div>
                <div>
                    <p style="font-size:12px;font-weight:800;color:#166534">{{ $pasien->penjamin->nama_penjamin }}</p>
                    @if($pasien->nomor_penjamin)
                    <p style="font-size:11px;color:#16a34a;margin-top:1px">No. Kartu: {{ $pasien->nomor_penjamin }}</p>
                    @endif
                </div>
            </div>
            @endif

            <div class="form-grid">
                <div class="field-wrap">
                    <label class="field-label">Penjamin</label>
                    <select class="field-input" name="penjamin_id">
                        <option value="">— Umum / Bayar Sendiri —</option>
                        @foreach($penjamins->groupBy('tipePenjamin.nama_tipe') as $tipe => $list)
                        <optgroup label="{{ $tipe }}">
                            @foreach($list as $p)
                            <option value="{{ $p->id }}" {{ old('penjamin_id', $pasien?->penjamin_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_penjamin }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    <p class="field-hint">BPJS, Prudential, Allianz, dll.</p>
                </div>
                <div class="field-wrap">
                    <label class="field-label">Nomor Kartu Penjamin</label>
                    <input class="field-input" type="text" name="nomor_penjamin"
                           value="{{ old('nomor_penjamin', $pasien?->nomor_penjamin) }}"
                           placeholder="No. BPJS / No. Polis">
                    <p class="field-hint">Nomor di kartu BPJS atau polis asuransi.</p>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save fade-section" style="transition-delay:.1s" id="btn-save-penjamin">
            <span class="spinner"></span>
            <span class="btn-txt"><i class="fas fa-floppy-disk" style="font-size:13px"></i>&nbsp;Simpan Data Penjamin</span>
        </button>
    </form>
    @endif

</div>
</div>

{{-- Toast --}}
<div id="profil-toast">
    <div class="t-icon"><i class="fas fa-check"></i></div>
    <span id="profil-toast-msg"></span>
</div>
@endsection

@push('scripts')
<script>
/**
 * ── 1. TOAST ─────────────────────────────────────────────
 * Tampilkan notifikasi ringan di pojok kanan bawah.
 */
function showToast(msg, dur = 3500) {
    const t = document.getElementById('profil-toast');
    const m = document.getElementById('profil-toast-msg');
    if (!t || !m) return;
    m.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), dur);
}

/**
 * ── 2. SECTION ENTRANCE ANIMATION ────────────────────────
 * Tiap section card masuk dengan fade+slide menggunakan
 * IntersectionObserver — jalan saat elemen masuk viewport.
 */
function initFadeSections() {
    const els = document.querySelectorAll('.fade-section');
    const obs = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.05 });
    els.forEach(el => obs.observe(el));
}

/**
 * ── 3. FOTO PREVIEW ──────────────────────────────────────
 * Saat user pilih foto di hero avatar ATAU di card foto,
 * kedua preview langsung update tanpa reload, dan file
 * dijadikan satu input sebelum form submit.
 */
function initFotoPreview() {
    const heroInput = document.getElementById('foto-input-hero');
    const secondaryInput = document.getElementById('foto-input-secondary');
    const formInput  = document.getElementById('foto-input-form');

    function applyPhoto(file) {
        if (!file) return;
        const url = URL.createObjectURL(file);

        // Hero avatar
        const heroImg  = document.getElementById('hero-foto-img');
        const heroInit = document.getElementById('hero-init');
        if (heroImg)  { heroImg.src = url; heroImg.style.display = 'block'; }
        if (heroInit) heroInit.style.display = 'none';

        // Card thumb
        const thumbImg  = document.getElementById('foto-thumb-img');
        const thumbInit = document.getElementById('foto-thumb-init');
        if (thumbImg)  { thumbImg.src = url; thumbImg.style.display = 'block'; }
        if (thumbInit) thumbInit.style.display = 'none';

        // Nama file
        const fn = document.getElementById('foto-file-name');
        if (fn) fn.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';

        // Sinkron ke formInput (nama="foto") agar ikut terkirim
        if (formInput) {
            const dt = new DataTransfer();
            dt.items.add(file);
            formInput.files = dt.files;
        }
    }

    // Trigger dari hero avatar
    heroInput?.addEventListener('change', function () {
        applyPhoto(this.files[0]);
        // Sync juga ke secondary input agar UI konsisten
        if (secondaryInput) {
            const dt = new DataTransfer();
            if (this.files[0]) dt.items.add(this.files[0]);
            secondaryInput.files = dt.files;
        }
    });

    // Trigger dari card foto
    secondaryInput?.addEventListener('change', function () {
        applyPhoto(this.files[0]);
        // Sync ke hero input
        if (heroInput) {
            const dt = new DataTransfer();
            if (this.files[0]) dt.items.add(this.files[0]);
            heroInput.files = dt.files;
        }
    });
}

/**
 * ── 4. NAMA REAL-TIME UPDATE DI HERO ─────────────────────
 * Saat user mengetik nama baru, tampilkan langsung di hero card
 * tanpa harus submit dulu.
 */
function initNamaLiveUpdate() {
    const inputNama = document.getElementById('input-nama');
    const heroName  = document.getElementById('hero-name-display');
    if (!inputNama || !heroName) return;
    inputNama.addEventListener('input', function () {
        heroName.textContent = this.value || '—';
    });
}

/**
 * ── 5. SAVE BUTTON LOADING STATE ─────────────────────────
 * Saat form di-submit, tombol simpan ganti jadi spinner
 * agar user tahu proses sedang berjalan.
 */
function initSaveLoading() {
    ['form-profil', 'form-penjamin'].forEach(function (formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        form.addEventListener('submit', function () {
            const btn = form.querySelector('.btn-save');
            if (btn) {
                btn.classList.add('loading');
                // Efek ripple pada klik
                const ripple = document.createElement('span');
                ripple.className = 'btn-save-ripple';
                ripple.style.cssText = 'width:200px;height:200px;left:calc(50% - 100px);top:calc(50% - 100px)';
                btn.appendChild(ripple);
                ripple.addEventListener('animationend', () => ripple.remove());
            }
        });
    });
}

/**
 * ── 6. FIELD FOCUS RING ───────────────────────────────────
 * Tambah efek subtle saat field aktif — border label berubah
 * warna mengikuti focus state input.
 */
function initFieldFocus() {
    document.querySelectorAll('.field-input').forEach(function (input) {
        const label = input.closest('.field-wrap')?.querySelector('.field-label');
        input.addEventListener('focus', function () {
            if (label) label.style.color = '#00521f';
        });
        input.addEventListener('blur', function () {
            if (label) label.style.color = '';
        });
    });
}

// ── INIT SEMUA ────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    initFadeSections();
    initFotoPreview();
    initNamaLiveUpdate();
    initSaveLoading();
    initFieldFocus();

    // Tampilkan toast jika ada session success
    const flashMsg = document.getElementById('flash-msg');
    if (flashMsg) {
        const flashEl = document.getElementById('flash-success');
        if (flashEl) flashEl.style.display = 'none';
        showToast(flashMsg.dataset.msg || 'Profil berhasil disimpan!');
    }
});

/**
 * Assign warna berbeda per icon spesialis saat halaman load.
 * Palet: bg + icon color — kontras jelas, tidak semuanya hijau.
 */
(function initSpesialisColors() {
    var palettes = [
        { bg: '#dbeafe', icon: '#1d4ed8' },  // biru
        { bg: '#fce7f3', icon: '#be185d' },  // pink
        { bg: '#fef9c3', icon: '#a16207' },  // kuning
        { bg: '#f3e8ff', icon: '#7e22ce' },  // ungu
        { bg: '#ffedd5', icon: '#c2410c' },  // oranye
        { bg: '#ccfbf1', icon: '#0f766e' },  // teal
        { bg: '#e0e7ff', icon: '#3730a3' },  // indigo
        { bg: '#fee2e2', icon: '#b91c1c' },  // merah
        { bg: '#dcfce7', icon: '#166534' },  // hijau
    ];

    document.querySelectorAll('.rw-sp-btn').forEach(function (btn) {
        var idx   = parseInt(btn.dataset.index || 0);
        var pal   = palettes[idx % palettes.length];
        var icon  = btn.querySelector('.rw-sp-icon');
        var iEl   = btn.querySelector('.rw-sp-icon i');
        var count = btn.querySelector('.rw-sp-count');

        if (icon) { icon.style.background = pal.bg; }
        if (iEl)  { iEl.style.color       = pal.icon; }
        if (count){ count.style.background = pal.bg; count.style.color = pal.icon; }

        // Simpan warna asal untuk restore saat non-aktif
        btn.dataset.bgOrig    = pal.bg;
        btn.dataset.iconOrig  = pal.icon;
    });
})();

/**
 * Toggle panel riwayat per spesialis.
 * Klik icon → buka panel di bawah grid, icon jadi solid hijau.
 * Klik lagi atau klik X → tutup panel.
 */
function toggleSpesialis(slug, btnEl) {
    var panel    = document.getElementById('panel-' + slug);
    var allBtns  = document.querySelectorAll('.rw-sp-btn');
    var allPanels= document.querySelectorAll('.rw-sp-panel');

    if (!panel) return;
    var isOpen = panel.style.display !== 'none';

    // Reset semua
    allPanels.forEach(function (p) { p.style.display = 'none'; });
    allBtns.forEach(function (b) {
        b.classList.remove('active');
        // Kembalikan warna asal
        var icon  = b.querySelector('.rw-sp-icon');
        var iEl   = b.querySelector('.rw-sp-icon i');
        var count = b.querySelector('.rw-sp-count');
        if (icon)  { icon.style.background  = b.dataset.bgOrig   || ''; }
        if (iEl)   { iEl.style.color        = b.dataset.iconOrig || ''; }
        if (count) { count.style.background = b.dataset.bgOrig   || ''; count.style.color = b.dataset.iconOrig || ''; }
        var lbl = b.querySelector('.rw-sp-label');
        if (lbl) { lbl.style.color = ''; lbl.style.fontWeight = ''; }
    });

    if (!isOpen) {
        panel.style.display = 'block';
        if (btnEl) {
            btnEl.classList.add('active');
            // Aktif: icon jadi hijau solid
            var icon  = btnEl.querySelector('.rw-sp-icon');
            var iEl   = btnEl.querySelector('.rw-sp-icon i');
            var count = btnEl.querySelector('.rw-sp-count');
            var lbl   = btnEl.querySelector('.rw-sp-label');
            if (icon)  { icon.style.background  = '#00521f'; icon.style.boxShadow = '0 4px 14px rgba(0,82,31,.35)'; }
            if (iEl)   { iEl.style.color        = '#fff'; }
            if (count) { count.style.background = 'rgba(0,82,31,.15)'; count.style.color = '#00521f'; }
            if (lbl)   { lbl.style.color = '#00521f'; lbl.style.fontWeight = '800'; }
        }
        setTimeout(function () {
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 50);
    }
}

/**
 * Toggle section booking dibatalkan.
 */
function toggleBatal() {
    const panel = document.getElementById('panel-batal');
    const btn   = document.getElementById('btn-batal-toggle');
    if (!panel) return;
    const isOpen = panel.style.display !== 'none';
    panel.style.display = isOpen ? 'none' : 'block';
    if (btn) btn.innerHTML = isOpen
        ? 'Lihat <i class="fas fa-chevron-down" style="font-size:10px"></i>'
        : 'Sembunyikan <i class="fas fa-chevron-up" style="font-size:10px"></i>';
}
</script>
@endpush
