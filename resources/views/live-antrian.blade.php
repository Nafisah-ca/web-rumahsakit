@extends('layouts.app')
@php $title = 'Live Antrian'; @endphp

@if($tanggalStr === now()->toDateString())
<meta http-equiv="refresh" content="{{ $interval }}">
@endif

@push('styles')
<style>
/* ── PAGE ── */
.la-page { background:#f5f6fa; min-height:100vh; padding:0 0 60px; }
.la-wrap { max-width:1280px; margin:0 auto; padding:0 16px; }

/* ── STICKY TOPBAR ── */
.la-topbar {
    background:#fff;
    border-bottom:1px solid #e8ecef;
    padding:13px 0;
    position:sticky; top:0; z-index:100;
    box-shadow:0 1px 8px rgba(0,0,0,.06);
}
.la-topbar-inner {
    max-width:1280px; margin:0 auto; padding:0 16px;
    display:flex; align-items:center; gap:14px; flex-wrap:wrap;
}

/* Countdown */
.la-countdown { display:flex; flex-direction:column; line-height:1.2; }
.la-countdown-label { font-size:9px; font-weight:800; color:#9ca3af; text-transform:uppercase; letter-spacing:.07em; }
#la-timer {
    font-size:24px; font-weight:900; color:#00b04f;
    font-variant-numeric:tabular-nums; letter-spacing:-1px;
    transition:color .3s;
}

/* Search */
.la-search-wrap { flex:1; max-width:360px; position:relative; }
.la-search-wrap i { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:12px; pointer-events:none; }
#la-search {
    width:100%; padding:9px 12px 9px 34px;
    border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:13px; font-family:inherit; color:#111; outline:none;
    background:#f9fafb; transition:border-color .15s, box-shadow .15s;
}
#la-search:focus { border-color:#00b04f; box-shadow:0 0 0 3px rgba(0,176,79,.1); background:#fff; }

/* Sort btn */
.la-sort-btn {
    display:inline-flex; align-items:center; gap:6px;
    padding:8px 14px; border:1.5px solid #e5e7eb; border-radius:10px;
    background:#fff; font-size:12px; font-weight:700; color:#374151;
    cursor:pointer; font-family:inherit; white-space:nowrap;
    transition:all .15s;
}
.la-sort-btn:hover { border-color:#00b04f; color:#00521f; background:#f0fdf4; }

/* Date filter */
.la-date-wrap { display:flex; gap:6px; align-items:center; margin-left:auto; }
.la-chip {
    padding:6px 13px; border-radius:8px; font-size:11px; font-weight:700;
    text-decoration:none; border:1.5px solid transparent; transition:all .15s;
    display:inline-flex; align-items:center; gap:4px;
}
.la-chip-today    { background:#00521f; color:#fff; }
.la-chip-tomorrow { background:#1d4ed8; color:#fff; }
.la-chip-off { background:#f1f5f9; color:#6b7280; border-color:#e5e7eb; }
.la-chip-off:hover { background:#e8f5ec; border-color:#00b04f; color:#00521f; }
.la-date-form { display:flex; gap:5px; align-items:center; }
.la-date-input {
    border:1.5px solid #e5e7eb; border-radius:8px;
    padding:6px 10px; font-size:11px; font-family:inherit; color:#111; outline:none;
}
.la-date-input:focus { border-color:#00b04f; }
.la-date-go {
    padding:6px 12px; background:#00521f; color:#fff; border:none;
    border-radius:8px; font-size:11px; font-weight:700; cursor:pointer;
    font-family:inherit; transition:background .15s;
}
.la-date-go:hover { background:#003d17; }

/* ── HEADER ── */
.la-header { padding:18px 0 10px; display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:8px; }
.la-breadcrumb { font-size:11px; color:#9ca3af; margin-bottom:3px; }
.la-breadcrumb a { color:#9ca3af; text-decoration:none; }
.la-breadcrumb a:hover { color:#00521f; }
.la-header h2 { font-size:19px; font-weight:800; color:#111; }
.la-live-badge {
    display:inline-flex; align-items:center; gap:6px;
    background:#f0fdf4; border:1px solid #bbf7d0; border-radius:999px;
    padding:5px 13px; font-size:10px; font-weight:800; color:#166534; letter-spacing:.05em;
}
.la-live-dot { width:7px; height:7px; background:#22c55e; border-radius:50%; animation:lpulse 1.4s ease infinite; }
@keyframes lpulse { 0%,100%{box-shadow:0 0 0 0 rgba(34,197,94,.6)} 50%{box-shadow:0 0 0 5px rgba(34,197,94,0)} }

/* ── SUMMARY STRIP ── */
.la-summary { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; }
.la-sum-chip {
    display:flex; align-items:center; gap:10px;
    background:#fff; border:1px solid #e8ecef; border-radius:10px;
    padding:8px 14px; box-shadow:0 1px 4px rgba(0,0,0,.03);
}
.la-sum-num { font-size:22px; font-weight:900; line-height:1; font-variant-numeric:tabular-nums; }
.la-sum-label { font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.07em; color:#9ca3af; margin-top:2px; }

/* ── SORT DROPDOWN ── */
#sort-dd {
    display:none; position:absolute; top:calc(100% + 6px); left:0;
    background:#fff; border:1.5px solid #e5e7eb; border-radius:12px;
    box-shadow:0 8px 24px rgba(0,0,0,.1); padding:5px; width:220px; z-index:200;
}
.la-sort-wrap { position:relative; }
.sort-opt {
    width:100%; text-align:left; padding:9px 13px; border:none; background:none;
    font-size:13px; font-weight:600; color:#374151; cursor:pointer;
    border-radius:8px; font-family:inherit; transition:background .12s;
}
.sort-opt:hover { background:#f0fdf4; color:#00521f; }

/* ── DOKTER GRID: 3 kolom desktop ── */
.la-grid {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:12px;
}
@media(max-width:1023px){ .la-grid{ grid-template-columns:repeat(2,1fr); } }
@media(max-width:599px) { .la-grid{ grid-template-columns:1fr; } }

/* ── DOKTER CARD ── */
.dk-card {
    background:#fff;
    border:1px solid #e8ecef;
    border-radius:14px;
    box-shadow:0 1px 6px rgba(0,0,0,.04);
    overflow:hidden;
    display:flex; flex-direction:column;
    transition:box-shadow .2s, transform .18s;
    animation:cdin .35s ease both;
}
.dk-card:hover { box-shadow:0 6px 24px rgba(0,82,31,.13); transform:translateY(-3px); }
@keyframes cdin { from{opacity:0;transform:translateY(14px)} to{opacity:1;transform:translateY(0)} }

/* Status bar atas card */
.dk-status-bar {
    display:flex; align-items:center; justify-content:space-between;
    padding:9px 14px 8px;
}
.dk-status {
    display:inline-flex; align-items:center; gap:5px;
    font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:.07em;
}
.dk-status::before { content:''; width:7px; height:7px; border-radius:50%; flex-shrink:0; }
.dk-buka  { color:#166534; }
.dk-buka::before  { background:#22c55e; animation:lpulse 1.4s ease infinite; }
.dk-tutup { color:#9ca3af; }
.dk-tutup::before { background:#d1d5db; }

/* Nomor antrian besar format: dipanggil/total */
.dk-antrian {
    font-size:26px; font-weight:900; letter-spacing:-1px; line-height:1;
    font-variant-numeric:tabular-nums; white-space:nowrap;
}
.dk-ant-now  { color:#00b04f; }
.dk-ant-sep  { color:#cbd5e1; font-weight:400; margin:0 1px; font-size:20px; }
.dk-ant-tot  { color:#d1d5db; }
.dk-ant-dead { color:#e2e8f0; }

/* Body card: foto kiri, info kanan */
.dk-body {
    display:flex; align-items:flex-start; gap:12px;
    padding:10px 14px 12px;
    border-top:1px solid #f3f4f6;
}
.dk-foto {
    width:68px; height:78px;
    border-radius:10px;
    object-fit:cover; object-position:top;
    flex-shrink:0;
    border:1px solid #e8ecef;
    background:#f9fafb;
}
.dk-foto-placeholder {
    width:68px; height:78px;
    border-radius:10px;
    background:#f3f4f6;
    display:flex; align-items:center; justify-content:center;
    flex-shrink:0; border:1px solid #e8ecef;
}
.dk-foto-placeholder i { color:#cbd5e1; font-size:28px; }
.dk-info { flex:1; min-width:0; }
.dk-nama {
    font-size:13px; font-weight:800; color:#111; line-height:1.3;
    margin-bottom:3px;
}
.dk-poli {
    font-size:10px; font-weight:800; color:#00521f;
    text-transform:uppercase; letter-spacing:.05em; margin-bottom:6px;
}
.dk-jam {
    display:inline-flex; align-items:center; gap:4px;
    font-size:10px; color:#6b7280; font-weight:600;
    background:#f9fafb; border:1px solid #e5e7eb;
    border-radius:6px; padding:3px 8px;
}
.dk-jam i { font-size:9px; color:#00b04f; }

/* Estimasi footer card */
.dk-foot {
    display:flex; align-items:center; gap:6px;
    padding:7px 14px 10px;
    font-size:11px; color:#9ca3af;
}
.dk-foot i { font-size:11px; }

/* ── FOOTER STRIP ── */
.la-footer {
    display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;
    background:#fff; border:1px solid #e8ecef; border-radius:12px;
    padding:11px 18px; margin-top:14px; font-size:12px; color:#6b7280;
    box-shadow:0 1px 4px rgba(0,0,0,.03);
}
.la-refresh {
    display:inline-flex; align-items:center; gap:5px;
    color:#00521f; font-weight:700; font-size:12px; text-decoration:none;
}
.la-refresh:hover { color:#003d17; }
.la-refresh i { transition:transform .4s; }
.la-refresh:hover i { transform:rotate(180deg); }

/* Empty / no-result */
.la-empty {
    grid-column:1/-1; text-align:center; padding:60px 20px;
    background:#fff; border-radius:14px; border:1.5px dashed #d1fae5;
}
.la-empty i { font-size:38px; color:#bbf7d0; display:block; margin-bottom:12px; }
</style>
@endpush

@section('content')
<div class="la-page">

{{-- ── STICKY TOP BAR ── --}}
<div class="la-topbar">
    <div class="la-topbar-inner">

        {{-- Countdown / tanggal --}}
        @if($tanggalStr === now()->toDateString())
        <div class="la-countdown">
            <span class="la-countdown-label">Memperbarui Halaman</span>
            <span id="la-timer">{{ str_pad(floor($interval/60),2,'0',STR_PAD_LEFT) }}:{{ str_pad($interval%60,2,'0',STR_PAD_LEFT) }}</span>
        </div>
        @else
        <div style="font-size:13px;font-weight:700;color:#374151">
            <i class="fas fa-calendar-alt" style="color:#00521f;margin-right:5px"></i>
            {{ $tanggalObj->translatedFormat('d F Y') }}
        </div>
        @endif

        {{-- Search --}}
        <div class="la-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="la-search" placeholder="Cari Dokter / Poli" autocomplete="off">
        </div>

        {{-- Sort --}}
        <div class="la-sort-wrap">
            <button class="la-sort-btn" id="la-sort-btn">
                <i class="fas fa-sort-amount-down" style="font-size:11px"></i>
                Urut Berdasarkan
                <i class="fas fa-chevron-down" style="font-size:10px" id="sort-chevron"></i>
            </button>
            <div id="sort-dd">
                <button class="sort-opt" data-sort="default">Default (A–Z Dokter)</button>
                <button class="sort-opt" data-sort="antrian">Antrian Terbanyak</button>
                <button class="sort-opt" data-sort="poli">Urut Berdasarkan Poli</button>
            </div>
        </div>

        {{-- Date filter --}}
        <div class="la-date-wrap">
            @php $today = now()->toDateString(); $tmr = now()->addDay()->toDateString(); @endphp
            <a href="{{ route('live.antrian', ['tanggal'=>$today]) }}"
               class="la-chip {{ $tanggalStr===$today ? 'la-chip-today' : 'la-chip-off' }}">
               Hari Ini
            </a>
            <a href="{{ route('live.antrian', ['tanggal'=>$tmr]) }}"
               class="la-chip {{ $tanggalStr===$tmr ? 'la-chip-tomorrow' : 'la-chip-off' }}">
               Besok
            </a>
            <form method="GET" action="{{ route('live.antrian') }}" class="la-date-form">
                <input type="date" name="tanggal" value="{{ $tanggalStr }}"
                       min="{{ now()->toDateString() }}" class="la-date-input">
                <button type="submit" class="la-date-go">Lihat</button>
            </form>
        </div>
    </div>
</div>

<div class="la-wrap">

    {{-- Header --}}
    <div class="la-header">
        <div>
            <div class="la-breadcrumb">
                <a href="{{ route('home') }}">Beranda</a>
                <span style="margin:0 4px">›</span>
                <span>Live Antrian</span>
                <span style="margin:0 4px">›</span>
                <span>{{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }}</span>
            </div>
            <h2>Live Antrian {{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }}</h2>
        </div>
        @if($tanggalStr === now()->toDateString())
        <div class="la-live-badge">
            <span class="la-live-dot"></span> LIVE
        </div>
        @endif
    </div>

    {{-- Summary --}}
    @php
        $totalDokter  = count($dokterData);
        $totalPasien  = $dokterData->sum('total_antrian');
    @endphp
    <div class="la-summary">
        <div class="la-sum-chip">
            <div>
                <div class="la-sum-num" style="color:#22c55e">{{ $totalDokter }}</div>
                <div class="la-sum-label">Dokter Praktik</div>
            </div>
        </div>
        <div class="la-sum-chip">
            <div>
                <div class="la-sum-num" style="color:#f59e0b">{{ $totalPasien }}</div>
                <div class="la-sum-label">Total Antrian</div>
            </div>
        </div>
        <div class="la-sum-chip">
            <div>
                <div class="la-sum-num" style="color:#6366f1">{{ $tanggalObj->translatedFormat('l') }}</div>
                <div class="la-sum-label">Hari ini</div>
            </div>
        </div>
    </div>

    {{-- Grid Dokter --}}
    <div class="la-grid" id="la-grid">
        @forelse($dokterData as $idx => $d)
        <div class="dk-card"
             style="animation-delay:{{ $idx * 45 }}ms"
             data-nama="{{ strtolower($d['nama_dokter']) }}"
             data-poli="{{ strtolower($d['spesialis']) }}"
             data-antrian="{{ $d['total_antrian'] }}">

            {{-- Status + Nomor Antrian --}}
            <div class="dk-status-bar">
                <span class="dk-status dk-buka">Poli Sudah Dimulai</span>
                <div class="dk-antrian">
                    <span class="dk-ant-now">{{ str_pad($d['nomor_dipanggil'], 3, '0', STR_PAD_LEFT) }}</span><span class="dk-ant-sep">/</span><span class="dk-ant-tot">{{ str_pad($d['total_antrian'], 3, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            {{-- Foto + Info Dokter --}}
            <div class="dk-body">
                @if($d['foto'])
                    <img src="{{ Storage::url($d['foto']) }}"
                         alt="{{ $d['nama_dokter'] }}"
                         class="dk-foto"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="dk-foto-placeholder" style="display:none">
                        <i class="fas fa-user-doctor"></i>
                    </div>
                @else
                    <div class="dk-foto-placeholder">
                        <i class="fas fa-user-doctor"></i>
                    </div>
                @endif

                <div class="dk-info">
                    <div class="dk-nama">{{ $d['nama_dokter'] }}</div>
                    <div class="dk-poli">{{ $d['spesialis'] }}</div>
                    @if($d['jam_range'])
                    <div class="dk-jam">
                        <i class="fas fa-clock"></i>
                        {{ $d['jam_range'] }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- Estimasi --}}
            <div class="dk-foot">
                <i class="fas fa-hourglass-half" style="color:{{ $d['total_antrian'] > 0 ? '#f59e0b' : '#d1d5db' }}"></i>
                <span>Estimasi tunggu: <strong>{{ $d['estimasi'] }}</strong></span>
            </div>

        </div>
        @empty
        <div class="la-empty">
            <i class="fas fa-calendar-xmark"></i>
            <p style="font-size:15px;font-weight:800;color:#374151">Tidak ada dokter praktik</p>
            <p style="font-size:13px;color:#9ca3af;margin-top:4px">
                Pada {{ $tanggalObj->translatedFormat('l, d F Y') }} tidak ada jadwal dokter yang tersedia.
            </p>
        </div>
        @endforelse
    </div>

    {{-- No result --}}
    <div id="la-noresult" style="display:none;text-align:center;padding:48px;background:#fff;border-radius:14px;border:1.5px dashed #e5e7eb;margin-top:12px">
        <i class="fas fa-search" style="font-size:28px;color:#d1d5db;display:block;margin-bottom:10px"></i>
        <p style="font-size:14px;font-weight:700;color:#374151">Tidak ditemukan</p>
        <p style="font-size:12px;color:#9ca3af;margin-top:3px">Coba kata kunci lain</p>
    </div>

    {{-- Footer strip --}}
    <div class="la-footer">
        <p style="display:flex;align-items:center;gap:6px">
            <i class="fas fa-circle-info" style="color:#f59e0b"></i>
            {{ $pesanTunggu }}
        </p>
        <div style="display:flex;align-items:center;gap:14px">
            @if($tanggalStr === now()->toDateString())
            <span style="display:flex;align-items:center;gap:5px;font-size:11px;color:#9ca3af">
                <span class="la-live-dot" style="width:6px;height:6px"></span>
                Auto-refresh aktif
            </span>
            @endif
            <a href="{{ route('live.antrian', ['tanggal' => $tanggalStr]) }}" class="la-refresh">
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

    // ── COUNTDOWN TIMER ───────────────────────────────────────────────
    @if($tanggalStr === now()->toDateString())
    let sisa = {{ $interval }};
    const timerEl = document.getElementById('la-timer');
    if (timerEl) {
        function fmtTime(s) {
            const m = Math.floor(s / 60);
            return String(m).padStart(2,'0') + ':' + String(s % 60).padStart(2,'0');
        }
        setInterval(function () {
            sisa = sisa > 0 ? sisa - 1 : {{ $interval }};
            timerEl.textContent  = fmtTime(sisa);
            timerEl.style.color  = sisa <= 5 ? '#dc2626' : '#00b04f';
        }, 1000);
    }
    @endif

    // ── SEARCH ────────────────────────────────────────────────────────
    document.getElementById('la-search')?.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        let vis = 0;
        document.querySelectorAll('.dk-card').forEach(c => {
            const match = !q
                || (c.dataset.nama || '').includes(q)
                || (c.dataset.poli || '').includes(q);
            c.style.display = match ? '' : 'none';
            if (match) vis++;
        });
        const nr = document.getElementById('la-noresult');
        if (nr) nr.style.display = q && vis === 0 ? '' : 'none';
    });

    // ── SORT ──────────────────────────────────────────────────────────
    const sortBtn = document.getElementById('la-sort-btn');
    const sortDd  = document.getElementById('sort-dd');
    const chevron = document.getElementById('sort-chevron');
    let   open    = false;

    sortBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        open = !open;
        sortDd.style.display  = open ? '' : 'none';
        chevron.style.transform = open ? 'rotate(180deg)' : '';
    });
    document.addEventListener('click', function () {
        open = false;
        if (sortDd) sortDd.style.display = 'none';
        if (chevron) chevron.style.transform = '';
    });

    document.querySelectorAll('.sort-opt').forEach(btn => {
        btn.addEventListener('click', function () {
            const mode  = this.dataset.sort;
            const grid  = document.getElementById('la-grid');
            const cards = Array.from(grid.querySelectorAll('.dk-card'));
            cards.sort((a, b) => {
                if (mode === 'antrian') {
                    return parseInt(b.dataset.antrian || 0) - parseInt(a.dataset.antrian || 0);
                }
                if (mode === 'poli') {
                    const pa = a.dataset.poli || '', pb = b.dataset.poli || '';
                    return pa.localeCompare(pb) || (a.dataset.nama || '').localeCompare(b.dataset.nama || '');
                }
                return (a.dataset.nama || '').localeCompare(b.dataset.nama || '');
            });
            cards.forEach(c => grid.appendChild(c));
            open = false;
            sortDd.style.display  = 'none';
            chevron.style.transform = '';
        });
    });

    // ── HIGHLIGHT NOMOR DIPANGGIL TERTINGGI ───────────────────────────
    const nowEls = document.querySelectorAll('.dk-ant-now');
    let maxNow = 0;
    nowEls.forEach(el => { const v = parseInt(el.textContent) || 0; if (v > maxNow) maxNow = v; });
    if (maxNow > 0) {
        nowEls.forEach(el => {
            if ((parseInt(el.textContent) || 0) === maxNow) {
                el.style.color = '#dc2626';
            }
        });
    }

});
</script>
@endpush
