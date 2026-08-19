@extends('layouts.app')

{{-- Auto refresh hanya jika tanggal = hari ini --}}
@if($tanggalStr === now()->toDateString())
<meta http-equiv="refresh" content="{{ $interval }}">
@endif

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@600;700&display=swap');

/* ── BASE ───────────────────────────────────────────── */
.antrian-page { background:#f4f7f4; min-height:100vh; padding:32px 16px 80px; }
.antrian-wrap { max-width:1200px; margin:0 auto; }

/* ── HERO STRIP ─────────────────────────────────────── */
.antrian-hero {
    background:#00521f;
    border-radius:20px;
    padding:28px 28px 24px;
    margin-bottom:20px;
    position:relative;
    overflow:hidden;
}
.antrian-hero::before {
    content:'';
    position:absolute;
    inset:0;
    background:
        radial-gradient(ellipse at 90% -20%, rgba(0,176,79,.5) 0%, transparent 55%),
        radial-gradient(ellipse at -10% 110%, rgba(0,176,79,.3) 0%, transparent 50%);
}
.antrian-hero::after {
    content:'';
    position:absolute;
    inset:0;
    background-image:radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
    background-size:20px 20px;
}
.antrian-hero-inner { position:relative;z-index:2; }
.antrian-hero h1 {
    font-family:'Lora',serif;
    font-size:clamp(20px,3vw,26px);
    font-weight:700;
    color:#fff;
    margin-bottom:4px;
    letter-spacing:-.3px;
}
.antrian-hero p { font-size:13px; color:rgba(255,255,255,.65); }

/* LIVE badge */
.live-chip {
    display:inline-flex;
    align-items:center;
    gap:7px;
    background:rgba(255,255,255,.12);
    border:1px solid rgba(255,255,255,.2);
    border-radius:999px;
    padding:5px 14px;
    font-size:12px;
    font-weight:700;
    color:#fff;
    letter-spacing:.03em;
}
.live-dot {
    width:8px;height:8px;
    background:#4ade80;
    border-radius:50%;
    animation:live-pulse 1.4s ease infinite;
}
@keyframes live-pulse {
    0%,100%{box-shadow:0 0 0 0 rgba(74,222,128,.6)}
    50%{box-shadow:0 0 0 6px rgba(74,222,128,0)}
}

/* ── DATE FILTER BAR ────────────────────────────────── */
.date-bar {
    background:#fff;
    border-radius:16px;
    border:1px solid #e4ede7;
    padding:16px 20px;
    margin-bottom:20px;
    display:flex;
    align-items:center;
    gap:10px;
    flex-wrap:wrap;
    box-shadow:0 1px 6px rgba(0,0,0,.04);
}
.date-chip {
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:7px 16px;
    border-radius:10px;
    font-size:12px;
    font-weight:700;
    text-decoration:none;
    transition:background .15s,color .15s,transform .1s,box-shadow .15s;
    border:1.5px solid transparent;
}
.date-chip:hover { transform:translateY(-1px); box-shadow:0 3px 10px rgba(0,0,0,.08); }
.date-chip-today       { background:#00521f;color:#fff;border-color:#00521f; }
.date-chip-tomorrow    { background:#1d4ed8;color:#fff;border-color:#1d4ed8; }
.date-chip-inactive    { background:#f1f5f1;color:#4b5563;border-color:#e5e7eb; }
.date-chip-inactive:hover { background:#e8f0ea;border-color:#b2d8bf; }

.date-input-wrap { display:flex;gap:8px;align-items:center;margin-left:auto; }
.date-input {
    border:1.5px solid #e5e7eb;
    border-radius:10px;
    padding:7px 12px;
    font-size:12px;
    color:#111;
    outline:none;
    font-family:inherit;
    transition:border-color .15s;
}
.date-input:focus { border-color:#00b04f; box-shadow:0 0 0 3px rgba(0,176,79,.1); }
.date-search-btn {
    background:#00521f;
    color:#fff;
    border:none;
    border-radius:10px;
    padding:8px 16px;
    font-size:12px;
    font-weight:700;
    cursor:pointer;
    font-family:inherit;
    transition:background .15s;
    display:flex;align-items:center;gap:6px;
}
.date-search-btn:hover { background:#003d17; }

/* ── POLI GRID ──────────────────────────────────────── */
.poli-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:14px;
}
@media(max-width:1023px){ .poli-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:599px) { .poli-grid{ grid-template-columns:1fr; } }

/* ── POLI CARD ──────────────────────────────────────── */
.poli-card {
    background:#fff;
    border-radius:18px;
    border:1px solid #e8ede9;
    box-shadow:0 1px 8px rgba(0,0,0,.04);
    overflow:hidden;
    transition:box-shadow .2s,transform .2s;
    display:flex;flex-direction:column;
    opacity:0;
    transform:translateY(16px);
    animation:card-in .4s ease forwards;
}
.poli-card:hover { box-shadow:0 6px 24px rgba(0,82,31,.1); transform:translateY(-3px); }
@keyframes card-in {
    to{ opacity:1; transform:translateY(0); }
}

.poli-card-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:14px 16px 12px;
    border-bottom:1px solid #f3f4f1;
    gap:10px;
}
.poli-icon-wrap {
    width:38px;height:38px;
    border-radius:11px;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;
}
.poli-name {
    font-size:13px;
    font-weight:800;
    color:#111;
    flex:1;
    min-width:0;
    line-height:1.3;
}

/* Status badge */
.poli-status {
    font-size:10px;
    font-weight:800;
    padding:3px 10px;
    border-radius:999px;
    letter-spacing:.04em;
    white-space:nowrap;
    display:inline-flex;align-items:center;gap:5px;
}
.poli-status::before {
    content:'';width:6px;height:6px;border-radius:50%;
}
.poli-status-buka  { background:#f0fdf4;color:#166534;border:1px solid #bbf7d0; }
.poli-status-buka::before  { background:#22c55e;animation:live-pulse 1.4s ease infinite; }
.poli-status-tutup { background:#f1f5f9;color:#64748b;border:1px solid #e2e8f0; }
.poli-status-tutup::before { background:#94a3b8; }

/* ── ANTRIAN NUMBERS ────────────────────────────────── */
.poli-numbers {
    display:grid;grid-template-columns:1fr 1fr;gap:10px;
    padding:14px 16px 12px;
}
.num-box {
    border-radius:12px;
    padding:12px 10px;
    text-align:center;
}
.num-box-total  { background:#f9fafb; }
.num-box-panggil{ background:#f0fdf4; }
.num-box.inactive { background:#f9fafb !important; }
.num-value {
    font-size:32px;
    font-weight:900;
    line-height:1;
    font-family:'Lora',serif;
    letter-spacing:-1px;
    margin-bottom:3px;
}
.num-value-total   { color:#1f2937; }
.num-value-panggil { color:#00521f; }
.num-value-inactive{ color:#94a3b8 !important; }
.num-label {
    font-size:10px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.07em;
    color:#9ca3af;
}

/* ── ESTIMASI ───────────────────────────────────────── */
.poli-estimasi {
    margin:0 16px 14px;
    padding:8px 12px;
    border-radius:10px;
    font-size:11px;
    display:flex;align-items:center;gap:7px;
}
.poli-estimasi-buka  { background:#fffbeb;border:1px solid #fde68a;color:#92400e; }
.poli-estimasi-tutup { background:#f1f5f9;border:1px solid #e2e8f0;color:#94a3b8; }
.poli-estimasi i { flex-shrink:0; }

/* ── KOSONG ─────────────────────────────────────────── */
.poli-kosong {
    margin:0 16px 14px;
    font-size:11px;
    color:#9ca3af;
    text-align:center;
    padding:6px 0;
}

/* ── FOOTER INFO ────────────────────────────────────── */
.antrian-footer {
    display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;
    gap:10px;
    background:#fff;
    border-radius:14px;
    border:1px solid #e4ede7;
    padding:12px 20px;
    margin-top:16px;
    font-size:12px;
    color:#6b7280;
    box-shadow:0 1px 4px rgba(0,0,0,.03);
}
.refresh-btn {
    display:inline-flex;align-items:center;gap:6px;
    color:#00521f;font-weight:700;font-size:12px;
    text-decoration:none;
    transition:color .15s;
}
.refresh-btn:hover { color:#003d17; }
.refresh-btn i { transition:transform .4s; }
.refresh-btn:hover i { transform:rotate(180deg); }

/* ── EMPTY STATE ────────────────────────────────────── */
.empty-antrian {
    grid-column:1/-1;
    text-align:center;
    padding:64px 24px;
    background:#fff;
    border-radius:18px;
    border:1.5px dashed #d1fae5;
}
.empty-antrian i { font-size:40px;color:#bbf7d0;display:block;margin-bottom:14px; }

/* ── SUMMARY STRIP ──────────────────────────────────── */
.summary-strip {
    display:flex;flex-wrap:wrap;gap:10px;
    margin-bottom:20px;
}
.summary-chip {
    display:flex;align-items:center;gap:8px;
    background:#fff;
    border-radius:12px;
    border:1px solid #e4ede7;
    padding:10px 16px;
    font-size:12px;
    box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.summary-chip .s-val {
    font-size:18px;font-weight:900;
    font-family:'Lora',serif;
    letter-spacing:-1px;
    line-height:1;
}

/* ── ICON COLORS ────────────────────────────────────── */
.icon-bg-blue   {background:#dbeafe} .icon-clr-blue   {color:#1d4ed8}
.icon-bg-green  {background:#dcfce7} .icon-clr-green  {color:#15803d}
.icon-bg-red    {background:#fee2e2} .icon-clr-red    {color:#b91c1c}
.icon-bg-indigo {background:#e0e7ff} .icon-clr-indigo {color:#4338ca}
.icon-bg-purple {background:#f3e8ff} .icon-clr-purple {color:#7e22ce}
.icon-bg-orange {background:#ffedd5} .icon-clr-orange {color:#c2410c}
.icon-bg-pink   {background:#fce7f3} .icon-clr-pink   {color:#be185d}
.icon-bg-teal   {background:#ccfbf1} .icon-clr-teal   {color:#0f766e}
.icon-bg-yellow {background:#fef9c3} .icon-clr-yellow {color:#a16207}
.icon-bg-gray   {background:#f1f5f9} .icon-clr-gray   {color:#475569}
</style>
@endpush

@section('content')
<div class="antrian-page">
<div class="antrian-wrap">

    {{-- ── HERO STRIP ────────────────────────────── --}}
    <div class="antrian-hero">
        <div class="antrian-hero-inner">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <h1>
                        <i class="fas fa-list-ol" style="color:#4ade80;margin-right:8px;font-size:20px"></i>
                        Live Antrian Poliklinik
                    </h1>
                    <p>
                        {{ $tanggalObj->translatedFormat('l, d F Y') }}
                        @if($tanggalStr === now()->toDateString())
                        &bull; Diperbarui setiap {{ $interval }} detik
                        @endif
                    </p>
                </div>
                @if($tanggalStr === now()->toDateString())
                <div class="live-chip">
                    <span class="live-dot"></span> LIVE
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── SUMMARY STRIP ──────────────────────────── --}}
    @php
        $totalBuka  = collect($poliData)->where('status','Buka')->count();
        $totalPoli  = count($poliData);
        $totalPasien= collect($poliData)->sum('total_antrian');
    @endphp
    <div class="summary-strip">
        <div class="summary-chip">
            <div style="width:32px;height:32px;background:#dcfce7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-hospital-user" style="color:#15803d;font-size:13px"></i>
            </div>
            <div>
                <div class="s-val" style="color:#00521f">{{ $totalBuka }}</div>
                <div style="color:#9ca3af;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em">Poli Buka</div>
            </div>
        </div>
        <div class="summary-chip">
            <div style="width:32px;height:32px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-layer-group" style="color:#1d4ed8;font-size:13px"></i>
            </div>
            <div>
                <div class="s-val" style="color:#1d4ed8">{{ $totalPoli }}</div>
                <div style="color:#9ca3af;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em">Total Poli</div>
            </div>
        </div>
        <div class="summary-chip">
            <div style="width:32px;height:32px;background:#fef9c3;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-users" style="color:#a16207;font-size:13px"></i>
            </div>
            <div>
                <div class="s-val" style="color:#a16207">{{ $totalPasien }}</div>
                <div style="color:#9ca3af;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.07em">Total Antrian</div>
            </div>
        </div>
    </div>

    {{-- ── DATE FILTER ─────────────────────────────── --}}
    <div class="date-bar">
        @php
            $today    = now()->toDateString();
            $tomorrow = now()->addDay()->toDateString();
        @endphp
        <i class="fas fa-calendar-alt" style="color:#00521f;font-size:14px;flex-shrink:0"></i>
        <a href="{{ route('live.antrian', ['tanggal' => $today]) }}"
           class="date-chip {{ $tanggalStr === $today ? 'date-chip-today' : 'date-chip-inactive' }}">
            <i class="fas fa-calendar-day" style="font-size:11px"></i> Hari Ini
        </a>
        <a href="{{ route('live.antrian', ['tanggal' => $tomorrow]) }}"
           class="date-chip {{ $tanggalStr === $tomorrow ? 'date-chip-tomorrow' : 'date-chip-inactive' }}">
            <i class="fas fa-calendar-plus" style="font-size:11px"></i> Besok
        </a>
        <div class="date-input-wrap">
            <form method="GET" action="{{ route('live.antrian') }}" style="display:flex;gap:8px;align-items:center">
                <input type="date" name="tanggal"
                       value="{{ $tanggalStr }}"
                       min="{{ now()->toDateString() }}"
                       class="date-input">
                <button type="submit" class="date-search-btn">
                    <i class="fas fa-search" style="font-size:11px"></i> Lihat
                </button>
            </form>
        </div>
    </div>

    {{-- ── POLI GRID ────────────────────────────────── --}}
    <div class="poli-grid">
        @forelse($poliData as $idx => $poli)
        @php
            $w    = $poli['warna'] ?? 'blue';
            $buka = $poli['status'] === 'Buka';
        @endphp
        <div class="poli-card" style="animation-delay:{{ $idx * 60 }}ms">

            {{-- Header --}}
            <div class="poli-card-header">
                <div class="poli-icon-wrap icon-bg-{{ $w }}">
                    <i class="fas {{ $poli['icon'] }} text-sm icon-clr-{{ $w }}"></i>
                </div>
                <p class="poli-name">{{ $poli['nama'] }}</p>
                <span class="poli-status {{ $buka ? 'poli-status-buka' : 'poli-status-tutup' }}">
                    {{ $poli['status'] }}
                </span>
            </div>

            {{-- Numbers --}}
            <div class="poli-numbers">
                <div class="num-box num-box-total {{ !$buka ? 'inactive' : '' }}">
                    <div class="num-value num-value-total {{ !$buka ? 'num-value-inactive' : '' }}">
                        {{ $poli['total_antrian'] }}
                    </div>
                    <div class="num-label">Total Antrian</div>
                </div>
                <div class="num-box num-box-panggil {{ !$buka ? 'inactive' : '' }}">
                    <div class="num-value num-value-panggil {{ !$buka ? 'num-value-inactive' : '' }}">
                        {{ $poli['nomor_dipanggil'] }}
                    </div>
                    <div class="num-label">Dipanggil</div>
                </div>
            </div>

            {{-- Estimasi --}}
            <div class="poli-estimasi {{ $buka ? 'poli-estimasi-buka' : 'poli-estimasi-tutup' }}">
                <i class="fas fa-clock" style="font-size:12px"></i>
                <span>Estimasi tunggu: <strong>{{ $poli['estimasi'] }}</strong></span>
            </div>

            @if(!$buka)
            <p class="poli-kosong">
                <i class="fas fa-circle-info" style="margin-right:4px"></i>
                Tidak ada jadwal dokter hari ini
            </p>
            @endif
        </div>

        @empty
        <div class="empty-antrian">
            <i class="fas fa-hospital"></i>
            <p style="font-size:15px;font-weight:800;color:#374151;font-family:'Lora',serif">Belum ada poli terdaftar</p>
            <p style="font-size:13px;color:#9ca3af;margin-top:4px">Data poli diambil dari daftar spesialisasi yang dikelola admin.</p>
        </div>
        @endforelse
    </div>

    {{-- ── FOOTER ──────────────────────────────────── --}}
    <div class="antrian-footer">
        <p style="display:flex;align-items:center;gap:7px">
            <i class="fas fa-circle-info" style="color:#f59e0b"></i>
            {{ $pesanTunggu }}
        </p>
        <div style="display:flex;align-items:center;gap:16px">
            @if($tanggalStr === now()->toDateString())
            <span style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:11px">
                <span class="live-dot" style="width:6px;height:6px"></span>
                Diperbarui otomatis
            </span>
            @endif
            <a href="{{ route('live.antrian', ['tanggal' => $tanggalStr]) }}" class="refresh-btn">
                <i class="fas fa-arrows-rotate"></i> Refresh
            </a>
        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    /**
     * Countdown timer untuk auto-refresh
     * Tampilkan sisa detik di footer jika halaman hari ini
     */
    @if($tanggalStr === now()->toDateString())
    let sisa = {{ $interval }};
    const footer = document.querySelector('.antrian-footer p');
    if (footer) {
        const orig = footer.innerHTML;
        const tick = setInterval(function () {
            sisa--;
            if (sisa <= 0) { clearInterval(tick); return; }
            // Sisipkan countdown kecil di samping teks footer
            const cd = document.getElementById('countdown-chip');
            if (!cd) {
                const span = document.createElement('span');
                span.id = 'countdown-chip';
                span.style.cssText = 'margin-left:8px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;padding:1px 8px;font-size:11px;font-weight:700;color:#166534';
                footer.appendChild(span);
            }
            document.getElementById('countdown-chip').textContent = 'Refresh dalam ' + sisa + 's';
        }, 1000);
    }
    @endif

    /**
     * Highlight nomor dipanggil tertinggi (poli paling ramai)
     * agar mudah dilihat sekilas
     */
    const numPanggils = document.querySelectorAll('.num-value-panggil');
    let max = 0;
    numPanggils.forEach(el => {
        const v = parseInt(el.textContent) || 0;
        if (v > max) max = v;
    });
    if (max > 0) {
        numPanggils.forEach(el => {
            if ((parseInt(el.textContent) || 0) === max) {
                el.style.color = '#dc2626'; // merah — nomor tertinggi
            }
        });
    }
});
</script>
@endpush
