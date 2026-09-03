@extends('layouts.app')
@section('content')

<style>
/* ===== WIZARD WRAPPER ===== */
.wizard-wrap {
    min-height: 100vh;
    background: #f0fdf4;
    padding: 40px 16px 60px;
}
.wizard-inner {
    max-width: 900px;
    margin: 0 auto;
}

/* ===== HEADING ===== */
.wizard-heading {
    text-align: center;
    margin-bottom: 32px;
}
.wizard-heading h1 {
    font-size: 26px;
    font-weight: 800;
    color: #14532d;
    letter-spacing: -.4px;
}
.wizard-heading p {
    font-size: 14px;
    color: #6b7280;
    margin-top: 6px;
}

/* ===== STEP INDICATOR ===== */
.step-bar {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0;
    margin-bottom: 36px;
}
.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    position: relative;
    z-index: 1;
}
.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 800;
    border: 3px solid #d1d5db;
    background: #fff;
    color: #9ca3af;
    transition: all .3s;
}
.step-item.active .step-circle {
    background: #16a34a;
    border-color: #16a34a;
    color: #fff;
    box-shadow: 0 0 0 5px rgba(22,163,74,.15);
}
.step-item.done .step-circle {
    background: #16a34a;
    border-color: #16a34a;
    color: #fff;
}
.step-label {
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    white-space: nowrap;
    letter-spacing: .03em;
    text-transform: uppercase;
}
.step-item.active .step-label,
.step-item.done .step-label {
    color: #16a34a;
}
.step-line {
    flex: 1;
    height: 3px;
    background: #d1d5db;
    margin: 0 4px;
    margin-bottom: 22px;
    min-width: 60px;
    max-width: 120px;
    border-radius: 2px;
    transition: background .3s;
}
.step-line.done {
    background: #16a34a;
}

/* ===== SLIDE CONTAINER ===== */
.slide-container {
    overflow: hidden;
    position: relative;
}
.slide-panel {
    display: none;
    animation: fadeSlideIn .35s ease;
}
.slide-panel.active {
    display: block;
}
@keyframes fadeSlideIn {
    from { opacity: 0; transform: translateX(32px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes fadeSlideBack {
    from { opacity: 0; transform: translateX(-32px); }
    to   { opacity: 1; transform: translateX(0); }
}
.slide-panel.back-anim {
    animation: fadeSlideBack .3s ease;
}

/* ===== PANEL TITLE ===== */
.panel-title {
    text-align: center;
    margin-bottom: 24px;
}
.panel-title h2 {
    font-size: 20px;
    font-weight: 800;
    color: #14532d;
}
.panel-title p {
    font-size: 13px;
    color: #6b7280;
    margin-top: 4px;
}

/* ===== SEARCH BOX ===== */
.doctor-search {
    position: relative;
    margin-bottom: 20px;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}
.doctor-search input {
    width: 100%;
    padding: 11px 16px 11px 40px;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    font-size: 13px;
    outline: none;
    transition: border-color .2s;
    background: #fff;
}
.doctor-search input:focus {
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22,163,74,.1);
}
.doctor-search i {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 13px;
}

/* ===== SPESIALISASI FILTER ===== */
.spesialis-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    justify-content: center;
    margin-bottom: 24px;
}
.spesialis-btn {
    padding: 6px 14px;
    border-radius: 20px;
    border: 2px solid #e5e7eb;
    background: #fff;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    cursor: pointer;
    transition: all .15s;
    white-space: nowrap;
}
.spesialis-btn:hover {
    border-color: #16a34a;
    color: #16a34a;
}
.spesialis-btn.active {
    background: #16a34a;
    border-color: #16a34a;
    color: #fff;
}

/* ===== DOKTER GRID ===== */
.dokter-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
@media (max-width: 768px) {
    .dokter-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 480px) {
    .dokter-grid { grid-template-columns: 1fr; }
}

/* ===== DOKTER CARD ===== */
.dokter-card {
    background: #fff;
    border-radius: 18px;
    border: 2.5px solid #e5e7eb;
    padding: 20px 16px;
    cursor: pointer;
    transition: all .2s;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.dokter-card:hover {
    border-color: #16a34a;
    box-shadow: 0 6px 24px rgba(22,163,74,.12);
    transform: translateY(-3px);
}
.dokter-card.selected {
    border-color: #16a34a;
    background: #f0fdf4;
    box-shadow: 0 8px 28px rgba(22,163,74,.18);
    transform: translateY(-3px);
}
.dokter-card.selected::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #16a34a, #22c55e);
    border-radius: 18px 18px 0 0;
}
.dokter-card .check-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 24px;
    height: 24px;
    background: #16a34a;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 11px;
}
.dokter-card.selected .check-badge {
    display: flex;
}
.dokter-card-foto {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    margin: 0 auto 12px;
    border: 3px solid #e5e7eb;
    display: block;
}
.dokter-card-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #16a34a, #22c55e);
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    font-weight: 800;
    color: #fff;
    border: 3px solid #e5e7eb;
    flex-shrink: 0;
}
.dokter-card.selected .dokter-card-foto,
.dokter-card.selected .dokter-card-avatar {
    border-color: #16a34a;
}
.dokter-card-nama {
    font-size: 13px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 4px;
    line-height: 1.3;
}
.dokter-card-spesialis {
    font-size: 11px;
    font-weight: 600;
    color: #16a34a;
    background: #dcfce7;
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    margin-bottom: 10px;
}
.dokter-card-jadwal {
    font-size: 11px;
    color: #6b7280;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.dokter-card-jadwal i {
    color: #16a34a;
    font-size: 10px;
}
.dokter-card-pilih {
    margin-top: 14px;
    width: 100%;
    padding: 9px;
    border-radius: 10px;
    border: 2px solid #16a34a;
    background: transparent;
    color: #16a34a;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
    font-family: inherit;
}
.dokter-card:hover .dokter-card-pilih,
.dokter-card.selected .dokter-card-pilih {
    background: #16a34a;
    color: #fff;
}

/* ===== NO RESULT ===== */
.no-result {
    text-align: center;
    padding: 40px 20px;
    color: #9ca3af;
    grid-column: 1 / -1;
}
.no-result i { font-size: 36px; display: block; margin-bottom: 10px; opacity: .4; }

/* ===== SELECTED DOKTER SUMMARY (step 2 header) ===== */
.selected-dokter-summary {
    background: #fff;
    border: 2px solid #bbf7d0;
    border-radius: 16px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
    position: relative;
}
.selected-dokter-summary .sum-foto {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #bbf7d0;
    flex-shrink: 0;
}
.selected-dokter-summary .sum-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: linear-gradient(135deg,#16a34a,#22c55e);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    flex-shrink: 0;
    border: 2px solid #bbf7d0;
}
.selected-dokter-summary .sum-info { flex: 1; min-width: 0; }
.selected-dokter-summary .sum-info p { font-size: 15px; font-weight: 800; color: #14532d; }
.selected-dokter-summary .sum-info span { font-size: 12px; color: #16a34a; font-weight: 600; }
.selected-dokter-summary .sum-change {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 7px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all .15s;
    font-family: inherit;
    white-space: nowrap;
}
.selected-dokter-summary .sum-change:hover { background: #e2e8f0; }

/* ===== JADWAL SECTION ===== */
.jadwal-section { margin-bottom: 24px; }
.jadwal-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.jadwal-section-title i { color: #16a34a; }

/* Jadwal cards grid */
.jadwal-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
@media (max-width: 600px) {
    .jadwal-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 380px) {
    .jadwal-grid { grid-template-columns: 1fr; }
}

.jadwal-card {
    background: #fff;
    border: 2px solid #e5e7eb;
    border-radius: 14px;
    padding: 14px;
    cursor: pointer;
    transition: all .2s;
    text-align: center;
    position: relative;
}
.jadwal-card:hover:not(.disabled) {
    border-color: #16a34a;
    box-shadow: 0 4px 14px rgba(22,163,74,.12);
}
.jadwal-card.selected {
    border-color: #16a34a;
    background: #f0fdf4;
    box-shadow: 0 4px 14px rgba(22,163,74,.15);
}
.jadwal-card.disabled {
    opacity: .55;
    cursor: not-allowed;
    background: #f9fafb;
}
.jadwal-card .jc-hari {
    font-size: 13px;
    font-weight: 800;
    color: #111827;
    margin-bottom: 4px;
}
.jadwal-card .jc-tanggal {
    font-size: 11px;
    color: #6b7280;
    margin-bottom: 8px;
}
.jadwal-card .jc-jam {
    font-size: 12px;
    font-weight: 700;
    color: #374151;
    background: #f3f4f6;
    padding: 4px 10px;
    border-radius: 8px;
    display: inline-block;
    margin-bottom: 8px;
}
.jadwal-card.selected .jc-jam {
    background: #dcfce7;
    color: #16a34a;
}
.jadwal-card .jc-kuota {
    font-size: 11px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 3px 8px;
    border-radius: 20px;
}
.jc-kuota.avail { background: #dcfce7; color: #166534; }
.jc-kuota.penuh { background: #fee2e2; color: #991b1b; }
.jc-kuota.selesai { background: #f1f5f9; color: #6b7280; }

.jadwal-card .jc-check {
    position: absolute;
    top: 8px; right: 8px;
    width: 20px; height: 20px;
    background: #16a34a;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 9px;
}
.jadwal-card.selected .jc-check { display: flex; }

/* ===== KELUHAN AREA ===== */
.keluhan-wrap {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #e5e7eb;
    padding: 20px;
    margin-bottom: 24px;
}
.keluhan-wrap label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
    margin-bottom: 8px;
}
.keluhan-wrap textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    font-size: 13px;
    font-family: inherit;
    resize: vertical;
    outline: none;
    transition: border-color .2s;
    min-height: 100px;
    color: #111827;
}
.keluhan-wrap textarea:focus {
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22,163,74,.1);
}

/* ===== PASIEN INFO BOX ===== */
.pasien-info-box {
    background: #f0fdf4;
    border: 1.5px solid #bbf7d0;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}
.pasien-info-box .pi-foto {
    width: 40px; height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2px solid #bbf7d0;
}
.pasien-info-box .pi-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    background: #16a34a;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800; color: #fff;
    flex-shrink: 0;
}
.pasien-info-box .pi-text { flex: 1; min-width: 0; }
.pasien-info-box .pi-text p { font-size: 13px; font-weight: 800; color: #14532d; }
.pasien-info-box .pi-text span { font-size: 11px; color: #4ade80; }

/* ===== BOTTOM ACTIONS ===== */
.wizard-actions {
    display: flex;
    gap: 12px;
    align-items: center;
}
.btn-wizard-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 22px;
    border-radius: 14px;
    border: 2px solid #e5e7eb;
    background: #fff;
    color: #6b7280;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
    font-family: inherit;
}
.btn-wizard-back:hover { border-color: #16a34a; color: #16a34a; }
.btn-wizard-next {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px;
    border-radius: 14px;
    border: none;
    background: #16a34a;
    color: #fff;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    transition: all .15s;
    font-family: inherit;
    letter-spacing: -.2px;
}
.btn-wizard-next:hover { background: #15803d; }
.btn-wizard-next:disabled {
    background: #d1d5db;
    color: #9ca3af;
    cursor: not-allowed;
}

/* ===== LOADING STATE ===== */
.jadwal-loading {
    text-align: center;
    padding: 40px;
    color: #6b7280;
    font-size: 13px;
}
.jadwal-loading i { font-size: 24px; color: #16a34a; display: block; margin-bottom: 10px; }

/* ===== ALERT ===== */
.alert-warn {
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 24px;
    font-size: 13px;
    color: #92400e;
}
.alert-warn i { color: #f59e0b; margin-top: 1px; flex-shrink: 0; }

.alert-err {
    background: #fef2f2;
    border: 1.5px solid #fecaca;
    border-radius: 14px;
    padding: 14px 18px;
    font-size: 13px;
    color: #991b1b;
    margin-bottom: 20px;
}

/* ===== JADWAL EMPTY ===== */
.jadwal-empty {
    text-align: center;
    padding: 32px 20px;
    background: #eff6ff;
    border: 1.5px dashed #bfdbfe;
    border-radius: 14px;
    color: #1d4ed8;
    font-size: 13px;
}
.jadwal-empty i { font-size: 28px; display: block; margin-bottom: 8px; opacity: .6; }

/* ===== RESPONSIVE MISC ===== */
@media (max-width: 600px) {
    .wizard-heading h1 { font-size: 20px; }
    .step-line { min-width: 32px; }
    .selected-dokter-summary { flex-wrap: wrap; }
    .wizard-actions { flex-direction: column; }
    .btn-wizard-back { width: 100%; justify-content: center; }
}
</style>

<div class="wizard-wrap">
<div class="wizard-inner">

    {{-- Heading --}}
    <div class="wizard-heading">
        <h1><i class="fas fa-calendar-check" style="color:#16a34a;margin-right:8px"></i>Buat Janji Temu</h1>
        <p>Pilih dokter dan jadwal yang sesuai dengan kebutuhan Anda</p>
    </div>

    {{-- Step Bar --}}
    <div class="step-bar" id="step-bar">
        <div class="step-item active" id="step-ind-1">
            <div class="step-circle" id="sc-1">1</div>
            <span class="step-label">Pilih Dokter</span>
        </div>
        <div class="step-line" id="sl-1"></div>
        <div class="step-item" id="step-ind-2">
            <div class="step-circle" id="sc-2">2</div>
            <span class="step-label">Pilih Jadwal</span>
        </div>
        <div class="step-line" id="sl-2"></div>
        <div class="step-item" id="step-ind-3">
            <div class="step-circle" id="sc-3">3</div>
            <span class="step-label">Konfirmasi</span>
        </div>
    </div>

    {{-- Alerts --}}
    @if(!$pasien)
    <div class="alert-warn">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Profil pasien belum lengkap.</strong>
            Lengkapi profil Anda sebelum membuat janji temu.
            <a href="{{ route('portal.profil') }}" style="font-weight:700;color:#b45309;text-decoration:underline;margin-left:4px">Lengkapi Profil →</a>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="alert-err">
        <strong><i class="fas fa-circle-exclamation mr-2"></i>Terdapat kesalahan:</strong>
        <ul style="list-style:disc;list-style-position:inside;margin-top:6px">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-err"><i class="fas fa-circle-exclamation mr-2"></i>{{ session('error') }}</div>
    @endif

    {{-- ============================================================ --}}
    {{-- FORM --}}
    {{-- ============================================================ --}}
    <form method="POST" action="{{ route('portal.booking.store') }}" id="booking-form">
        @csrf
        <input type="hidden" name="dokter_id"         id="f-dokter-id">
        <input type="hidden" name="jadwal_dokter_id"  id="f-jadwal-id">
        <input type="hidden" name="tanggal_kunjungan" id="f-tanggal">

        {{-- ===================================================== --}}
        {{-- SLIDE 1 — PILIH DOKTER --}}
        {{-- ===================================================== --}}
        <div class="slide-panel active" id="panel-1">

            <div class="panel-title">
                <h2>Pilih Dokter</h2>
                <p>Ketuk kartu dokter untuk memilih</p>
            </div>

            {{-- Search --}}
            <div class="doctor-search">
                <i class="fas fa-search"></i>
                <input type="text" id="search-dokter" placeholder="Cari nama dokter atau spesialisasi..." autocomplete="off">
            </div>

            {{-- Spesialisasi filter --}}
            @php
                $spesialisList = $dokters->pluck('spesialisasi')->filter()->unique('id')->sortBy('nama_spesialis');
            @endphp
            @if($spesialisList->count() > 1)
            <div class="spesialis-filter" id="spesialis-filter">
                <button type="button" class="spesialis-btn active" data-spesialis="all">Semua</button>
                @foreach($spesialisList as $sp)
                <button type="button" class="spesialis-btn" data-spesialis="{{ $sp->id }}">{{ $sp->nama_spesialis }}</button>
                @endforeach
            </div>
            @endif

            {{-- Dokter Grid --}}
            <div class="dokter-grid" id="dokter-grid">
                @forelse($dokters as $d)
                @php
                    $isSelected = old('dokter_id', $dokter?->id) == $d->id;
                    $fotoUrl    = $d->foto ? Storage::url($d->foto) : null;
                    $_namaBersih = preg_replace('/^(dr\.|drg\.|prof\.)\s*/i', '', $d->nama_dokter);
                    $inisial    = strtoupper(substr($_namaBersih, 0, 1));
                    $spNama     = $d->spesialisasi?->nama_spesialis ?? 'Dokter Umum';
                    $jadwalInfo = $d->jadwal_singkat ?? 'Hubungi RS';
                @endphp
                <div class="dokter-card {{ $isSelected ? 'selected' : '' }}"
                     data-id="{{ $d->id }}"
                     data-nama="{{ $d->nama_dokter }}"
                     data-spesialis="{{ $d->spesialis_id }}"
                     data-spesialis-nama="{{ $spNama }}"
                     data-foto="{{ $fotoUrl ?? '' }}"
                     data-inisial="{{ $inisial }}"
                     onclick="pilihDokter(this)">

                    <div class="check-badge"><i class="fas fa-check"></i></div>

                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="{{ $d->nama_dokter }}" class="dokter-card-foto">
                    @else
                        <div class="dokter-card-avatar">{{ $inisial }}</div>
                    @endif

                    <div class="dokter-card-nama">{{ $d->nama_dokter }}</div>
                    <div class="dokter-card-spesialis">{{ $spNama }}</div>
                    <div class="dokter-card-jadwal">
                        <i class="fas fa-clock"></i>
                        {{ $jadwalInfo }}
                    </div>
                    <button type="button" class="dokter-card-pilih">
                        {{ $isSelected ? '✓ Terpilih' : 'Pilih Dokter' }}
                    </button>
                </div>
                @empty
                <div class="no-result">
                    <i class="fas fa-user-doctor"></i>
                    <p>Belum ada dokter aktif</p>
                </div>
                @endforelse
            </div>

            {{-- Lanjut --}}
            <div class="wizard-actions" style="margin-top:28px">
                <a href="{{ route('portal.booking.riwayat') }}" class="btn-wizard-back">
                    <i class="fas fa-list"></i> Riwayat
                </a>
                <button type="button" class="btn-wizard-next" id="btn-next-1" disabled onclick="goToStep2()">
                    Lanjut — Pilih Jadwal <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- SLIDE 2 — PILIH JADWAL & KELUHAN --}}
        {{-- ===================================================== --}}
        <div class="slide-panel" id="panel-2">

            <div class="panel-title">
                <h2>Pilih Jadwal & Keluhan</h2>
                <p>Pilih satu slot jadwal yang tersedia</p>
            </div>

            {{-- Selected dokter summary --}}
            <div class="selected-dokter-summary" id="dokter-summary">
                <div class="sum-avatar" id="sum-avatar-wrap">?</div>
                <div class="sum-info">
                    <p id="sum-nama">—</p>
                    <span id="sum-spesialis">—</span>
                </div>
                <button type="button" class="sum-change" onclick="goToStep1()">
                    <i class="fas fa-arrow-left" style="margin-right:4px"></i> Ganti Dokter
                </button>
            </div>

            {{-- Jadwal container --}}
            <div class="jadwal-section">
                <div class="jadwal-section-title">
                    <i class="fas fa-calendar-days"></i> Jadwal Tersedia
                </div>
                <div id="jadwal-container">
                    <div class="jadwal-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                        Memuat jadwal dokter...
                    </div>
                </div>
            </div>

            {{-- Keluhan --}}
            <div class="keluhan-wrap">
                <label for="keluhan">
                    <i class="fas fa-notes-medical" style="color:#16a34a;margin-right:6px"></i>
                    Keluhan Utama <span style="color:#ef4444">*</span>
                </label>
                <textarea name="keluhan" id="keluhan" rows="3"
                    placeholder="Deskripsikan keluhan Anda secara singkat, minimal 3 karakter..."
                    required>{{ old('keluhan') }}</textarea>
                <p style="font-size:11px;color:#9ca3af;margin-top:6px">
                    <i class="fas fa-info-circle" style="margin-right:4px"></i>
                    Wajib diisi minimal 3 karakter.
                </p>
            </div>

            {{-- Info pasien --}}
            @if($pasien)
            <div class="pasien-info-box">
                @if(Auth::user()->foto)
                    <img src="{{ Storage::url(Auth::user()->foto) }}" alt="{{ Auth::user()->nama }}" class="pi-foto">
                @else
                    <div class="pi-avatar">{{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}</div>
                @endif
                <div class="pi-text">
                    <p>{{ $pasien->nama_lengkap }}</p>
                    <span>No. RM: {{ $pasien->no_rm ?? '-' }} &nbsp;·&nbsp; {{ $pasien->jenis_kelamin_label ?? '-' }} &nbsp;·&nbsp; {{ $pasien->user?->no_hp ?? '-' }}</span>
                </div>
                <a href="{{ route('portal.profil') }}" style="font-size:12px;font-weight:700;color:#16a34a;text-decoration:none;white-space:nowrap">Edit Profil</a>
            </div>
            @endif

            {{-- Actions --}}
            <div class="wizard-actions">
                <button type="button" class="btn-wizard-back" onclick="goToStep1()">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="submit" class="btn-wizard-next" id="btn-submit" {{ !$pasien ? 'disabled' : '' }}>
                    <i class="fas fa-calendar-check"></i> Buat Janji Temu
                </button>
            </div>
        </div>

    </form>

</div>
</div>

@endsection

@push('scripts')
<script>
// =====================================================
// STATE
// =====================================================
var selectedDokter = null;   // { id, nama, spesialisasi, foto, inisial }
var selectedJadwal = null;   // { id, tanggal, hari, jam_mulai, jam_selesai }

// Pre-select jika ada query param dokter_id
@if($dokter)
@php
    $preDokterInisial = strtoupper(substr(preg_replace('/^(dr\.|drg\.|prof\.)\s*/i','', $dokter->nama_dokter), 0, 1));
    $preDokterFoto    = $dokter->foto ? Storage::url($dokter->foto) : '';
    $preDokterSp      = $dokter->spesialisasi?->nama_spesialis ?? 'Dokter Umum';
@endphp
selectedDokter = {
    id:          {{ $dokter->id }},
    nama:        @json($dokter->nama_dokter),
    spesialisasi:@json($preDokterSp),
    foto:        @json($preDokterFoto),
    inisial:     @json($preDokterInisial)
};
@endif

// =====================================================
// PILIH DOKTER — klik card
// =====================================================
function pilihDokter(el) {
    // Deselect semua
    document.querySelectorAll('.dokter-card').forEach(function(c) {
        c.classList.remove('selected');
        var btn = c.querySelector('.dokter-card-pilih');
        if (btn) btn.textContent = 'Pilih Dokter';
    });

    // Select ini
    el.classList.add('selected');
    var btn = el.querySelector('.dokter-card-pilih');
    if (btn) btn.textContent = '✓ Terpilih';

    selectedDokter = {
        id:           el.dataset.id,
        nama:         el.dataset.nama,
        spesialisasi: el.getAttribute('data-spesialis-nama'),
        foto:         el.dataset.foto,
        inisial:      el.dataset.inisial
    };

    document.getElementById('f-dokter-id').value = selectedDokter.id;
    document.getElementById('btn-next-1').disabled = false;
}

// =====================================================
// FILTER & SEARCH
// =====================================================
document.getElementById('search-dokter')?.addEventListener('input', filterDokter);

document.querySelectorAll('.spesialis-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.spesialis-btn').forEach(function(b) { b.classList.remove('active'); });
        this.classList.add('active');
        filterDokter();
    });
});

function filterDokter() {
    var q         = (document.getElementById('search-dokter')?.value || '').toLowerCase();
    var activeBtn = document.querySelector('.spesialis-btn.active');
    var spFilter  = activeBtn ? activeBtn.dataset.spesialis : 'all';
    var cards     = document.querySelectorAll('.dokter-card');
    var visible   = 0;

    cards.forEach(function(c) {
        var nama      = c.dataset.nama.toLowerCase();
        var spNama    = c.getAttribute('data-spesialis-nama').toLowerCase();
        var spId      = c.dataset.spesialis;

        var matchQ  = !q || nama.includes(q) || spNama.includes(q);
        var matchSp = spFilter === 'all' || spId === spFilter;

        if (matchQ && matchSp) {
            c.style.display = '';
            visible++;
        } else {
            c.style.display = 'none';
        }
    });

    var noRes = document.getElementById('no-result-msg');
    if (visible === 0) {
        if (!noRes) {
            var div = document.createElement('div');
            div.id = 'no-result-msg';
            div.className = 'no-result';
            div.style.gridColumn = '1/-1';
            div.innerHTML = '<i class="fas fa-search"></i><p>Dokter tidak ditemukan</p>';
            document.getElementById('dokter-grid').appendChild(div);
        }
    } else if (noRes) {
        noRes.remove();
    }
}

// =====================================================
// NAVIGASI STEP
// =====================================================
function goToStep2() {
    if (!selectedDokter) return;

    // Update summary
    var wrapEl = document.getElementById('sum-avatar-wrap');
    if (selectedDokter.foto) {
        wrapEl.outerHTML = '<img src="' + selectedDokter.foto + '" class="sum-foto" id="sum-avatar-wrap" alt="foto">';
    } else {
        wrapEl.className  = 'sum-avatar';
        wrapEl.textContent = selectedDokter.inisial || '?';
    }
    document.getElementById('sum-nama').textContent      = selectedDokter.nama;
    document.getElementById('sum-spesialis').textContent = selectedDokter.spesialisasi;

    // Tampilkan slide 2
    showPanel(2, false);

    // Load jadwal
    loadJadwal(selectedDokter.id);
}

function goToStep1() {
    showPanel(1, true);
}

function showPanel(num, back) {
    document.querySelectorAll('.slide-panel').forEach(function(p) {
        p.classList.remove('active', 'back-anim');
    });
    var target = document.getElementById('panel-' + num);
    if (back) target.classList.add('back-anim');
    target.classList.add('active');

    // Update step indicator
    for (var i = 1; i <= 3; i++) {
        var item = document.getElementById('step-ind-' + i);
        var sc   = document.getElementById('sc-' + i);
        item.classList.remove('active', 'done');
        if (i < num) {
            item.classList.add('done');
            sc.innerHTML = '<i class="fas fa-check" style="font-size:12px"></i>';
        } else if (i === num) {
            item.classList.add('active');
            sc.textContent = i;
        } else {
            sc.textContent = i;
        }
    }
    // Step lines
    document.querySelectorAll('.step-line').forEach(function(ln, idx) {
        if (idx + 1 < num) ln.classList.add('done');
        else                ln.classList.remove('done');
    });

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// =====================================================
// LOAD JADWAL
// =====================================================
function loadJadwal(dokterId) {
    var cont = document.getElementById('jadwal-container');
    cont.innerHTML = '<div class="jadwal-loading"><i class="fas fa-spinner fa-spin"></i>Memuat jadwal...</div>';
    selectedJadwal = null;
    document.getElementById('f-jadwal-id').value = '';
    document.getElementById('f-tanggal').value   = '';
    updateSubmitBtn();

    fetch('{{ route("portal.booking.jadwal") }}?dokter_id=' + dokterId)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data || data.length === 0) {
                cont.innerHTML = '<div class="jadwal-empty"><i class="fas fa-calendar-xmark"></i>Dokter ini belum memiliki jadwal aktif saat ini.<br><span style="font-size:12px;color:#3b82f6">Silakan hubungi rumah sakit untuk informasi lebih lanjut.</span></div>';
                return;
            }
            renderJadwal(data);
        })
        .catch(function() {
            cont.innerHTML = '<p style="color:#ef4444;font-size:13px;text-align:center;padding:20px">Gagal memuat jadwal. Coba refresh halaman.</p>';
        });
}

function renderJadwal(data) {
    var html = '<div class="jadwal-grid" id="jadwal-grid">';
    data.forEach(function(j) {
        var disabled   = j.sudah_selesai || j.sisa_kuota <= 0;
        var disClass   = disabled ? ' disabled' : '';

        var kuotaHtml;
        if (j.sudah_selesai) {
            kuotaHtml = '<span class="jc-kuota selesai"><i class="fas fa-clock"></i> Selesai</span>';
        } else if (j.sisa_kuota <= 0) {
            kuotaHtml = '<span class="jc-kuota penuh"><i class="fas fa-users-slash"></i> Penuh</span>';
        } else {
            kuotaHtml = '<span class="jc-kuota avail"><i class="fas fa-circle-check"></i> Sisa ' + j.sisa_kuota + '</span>';
        }

        var onclick = disabled ? '' : 'onclick="pilihJadwal(' + j.id + ',\'' + j.tanggal + '\',\'' + j.hari + '\',\'' + j.jam_mulai + '\',\'' + j.jam_selesai + '\',this)"';

        html += '<div class="jadwal-card' + disClass + '" data-id="' + j.id + '" ' + onclick + '>';
        html += '<div class="jc-check"><i class="fas fa-check" style="font-size:8px"></i></div>';
        html += '<div class="jc-hari">' + j.hari + '</div>';
        html += '<div class="jc-tanggal">' + j.tanggal_label + '</div>';
        html += '<div class="jc-jam"><i class="fas fa-clock" style="font-size:10px;margin-right:3px"></i>' + j.jam_mulai + ' – ' + j.jam_selesai + '</div>';
        html += '<div>' + kuotaHtml + '</div>';
        html += '</div>';
    });
    html += '</div>';

    // Info jadwal terpilih
    html += '<div id="jadwal-selected-info" style="display:none;margin-top:14px;padding:12px 16px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;font-size:13px;font-weight:700;color:#166534">';
    html += '<i class="fas fa-circle-check" style="margin-right:8px;color:#16a34a"></i><span id="jadwal-sel-text"></span></div>';

    document.getElementById('jadwal-container').innerHTML = html;
}

// =====================================================
// PILIH JADWAL
// =====================================================
function pilihJadwal(id, tanggal, hari, jamMulai, jamSelesai, el) {
    if (el.classList.contains('disabled')) return;

    document.querySelectorAll('.jadwal-card').forEach(function(c) {
        c.classList.remove('selected');
    });
    el.classList.add('selected');

    selectedJadwal = { id: id, tanggal: tanggal, hari: hari, jam_mulai: jamMulai, jam_selesai: jamSelesai };
    document.getElementById('f-jadwal-id').value = id;
    document.getElementById('f-tanggal').value   = tanggal;

    var info = document.getElementById('jadwal-selected-info');
    var txt  = document.getElementById('jadwal-sel-text');
    if (info && txt) {
        txt.textContent  = 'Jadwal dipilih: ' + hari + ', ' + tanggal + ' · ' + jamMulai + '–' + jamSelesai;
        info.style.display = 'block';
    }

    // Setelah pilih jadwal → tandai step 3
    showStep3Indicator();
    updateSubmitBtn();
}

function showStep3Indicator() {
    var item = document.getElementById('step-ind-3');
    var sc   = document.getElementById('sc-3');
    item.classList.remove('done');
    item.classList.add('active');
    sc.textContent = '3';
}

// =====================================================
// UPDATE SUBMIT BUTTON
// =====================================================
function updateSubmitBtn() {
    var btn = document.getElementById('btn-submit');
    if (!btn) return;
    @if(!$pasien)
    btn.disabled = true;
    @else
    btn.disabled = !(selectedJadwal && selectedJadwal.id);
    @endif
}

// =====================================================
// INIT
// =====================================================
document.addEventListener('DOMContentLoaded', function() {
    // Jika ada pre-selected dokter (dari halaman dokter)
    @if($dokter)
    var preCard = document.querySelector('.dokter-card[data-id="{{ $dokter->id }}"]');
    if (preCard) {
        preCard.classList.add('selected');
        var pBtn = preCard.querySelector('.dokter-card-pilih');
        if (pBtn) pBtn.textContent = '✓ Terpilih';
    }
    document.getElementById('f-dokter-id').value  = '{{ $dokter->id }}';
    document.getElementById('btn-next-1').disabled = false;
    // Langsung masuk step 2
    setTimeout(function() { goToStep2(); }, 120);
    @endif

    updateSubmitBtn();

    // Keluhan change check
    document.getElementById('keluhan')?.addEventListener('input', updateSubmitBtn);
});
</script>
@endpush
