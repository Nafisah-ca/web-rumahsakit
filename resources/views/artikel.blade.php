@extends('layouts.app')

@push('styles')
<style>
/* ============================================================
   ARTIKEL PAGE — PREMIUM ANIMATIONS + AJAX FILTER
   ============================================================ */

@keyframes fadeUp {
    from { opacity:0; transform:translateY(36px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes fadeRight {
    from { opacity:0; transform:translateX(28px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes popIn {
    0%  { opacity:0; transform:scale(.72) translateY(12px); }
    70% { transform:scale(1.05) translateY(-2px); }
    100%{ opacity:1; transform:scale(1) translateY(0); }
}
@keyframes pulseBar {
    0%,100% { opacity:1; } 50% { opacity:.6; }
}
@keyframes gradLine {
    from { width:0; opacity:0; } to { width:100%; opacity:1; }
}
@keyframes sparklePop {
    0%,100% { opacity:0; transform:scale(0) rotate(0deg); }
    20%,80% { opacity:1; transform:scale(1) rotate(15deg); }
    50%      { opacity:.85; transform:scale(1.3) rotate(45deg); }
}
@keyframes rippleOut {
    to { transform:scale(2.5); opacity:0; }
}
@keyframes floatY {
    0%,100% { transform:translateY(0); } 50% { transform:translateY(-8px); }
}
@keyframes spin {
    to { transform:rotate(360deg); }
}
@keyframes skeletonShimmer {
    0%   { background-position:-400px 0; }
    100% { background-position: 400px 0; }
}

/* ── Scroll progress ────────────────────────────────────────── */
#art-progress {
    position:fixed; top:0; left:0; height:3px; z-index:9999;
    background:linear-gradient(90deg,#2563eb,#60a5fa,#2563eb);
    background-size:200% 100%;
    animation:pulseBar 2s infinite;
    width:0; transition:width .12s linear;
    border-radius:0 2px 2px 0;
}

/* ── Article card ───────────────────────────────────────────── */
.art-card {
    opacity:0;
    transform:translateY(32px) scale(.97);
    transition: opacity .5s cubic-bezier(.22,1,.36,1),
                transform .5s cubic-bezier(.22,1,.36,1),
                box-shadow .3s ease;
    border-radius:16px;
    overflow:hidden;
    background:#fff;
    border:1px solid #f1f5f9;
    box-shadow:0 2px 10px rgba(0,0,0,.06);
    display:block;
    text-decoration:none;
    position:relative;
}
.art-card.in {
    opacity:1; transform:translateY(0) scale(1);
}
.art-card:hover {
    transform:translateY(-7px) scale(1.016) !important;
    box-shadow:0 18px 44px rgba(37,99,235,.14) !important;
    z-index:3;
}
.art-card:hover .art-img { transform:scale(1.08); }
.art-card:hover .art-read-more { gap:8px; color:#1d4ed8; }
.art-card:hover .art-read-arrow { transform:translateX(5px); }
.art-card::before {
    content:''; position:absolute; inset:0;
    background:linear-gradient(135deg,transparent 60%,rgba(37,99,235,.04));
    opacity:0; transition:opacity .3s; z-index:1; pointer-events:none;
}
.art-card:hover::before { opacity:1; }

/* ── Image bling-bling ──────────────────────────────────────── */
.art-img-wrap {
    height:170px; overflow:hidden; position:relative;
}
.art-img {
    width:100%; height:100%; object-fit:cover;
    transition:transform .5s cubic-bezier(.22,1,.36,1);
}
/* Shimmer sweep hover */
.art-img-wrap::after {
    content:'';
    position:absolute; inset:0; z-index:2; pointer-events:none;
    background:linear-gradient(115deg,transparent 30%,rgba(255,255,255,.5) 50%,transparent 70%);
    background-size:200% 100%;
    background-position:-200% 0;
    transition:background-position .7s ease;
}
.art-card:hover .art-img-wrap::after { background-position:200% 0; }

/* Sparkle bintang 4-titik */
.sparkle {
    position:absolute; z-index:3; pointer-events:none;
    animation:sparklePop var(--dur,2s) ease-in-out var(--delay,0s) infinite;
    opacity:0; animation-play-state:paused;
}
.sparkle::before, .sparkle::after {
    content:''; position:absolute; background:white; border-radius:99px;
}
.sparkle::before {
    width:var(--size,12px); height:2px; top:50%; left:50%;
    transform:translate(-50%,-50%);
    box-shadow:0 0 6px 2px rgba(255,255,255,.95);
}
.sparkle::after {
    width:2px; height:var(--size,12px); top:50%; left:50%;
    transform:translate(-50%,-50%);
    box-shadow:0 0 6px 2px rgba(255,255,255,.95);
}
.art-card:hover .sparkle { animation-play-state:running; }
/* Glow border hover */
.art-card:hover .art-img-wrap {
    box-shadow:0 0 0 2px rgba(255,255,255,.5), 0 0 18px 3px rgba(255,220,50,.2);
}

/* ── Badges ─────────────────────────────────────────────────── */
.art-cat-badge {
    display:inline-block; font-size:10px; font-weight:700; color:#fff;
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    padding:2px 10px; border-radius:99px;
}
.art-read-more {
    display:flex; align-items:center; gap:4px;
    font-size:12px; font-weight:700; color:#2563eb;
    transition:gap .2s, color .2s; margin-top:10px;
}
.art-read-arrow { transition:transform .2s cubic-bezier(.22,1,.36,1); display:inline-block; }

/* ── Skeleton loader ─────────────────────────────────────────── */
.art-skeleton {
    border-radius:16px; overflow:hidden; height:280px;
    background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);
    background-size:400px 100%;
    animation:skeletonShimmer 1.4s infinite;
}

/* ── Sidebar ─────────────────────────────────────────────────── */
.artikel-sidebar {
    position:sticky; top:88px;
    align-self:start;
    animation:fadeRight .6s cubic-bezier(.22,1,.36,1) .3s both;
}
.sidebar-card {
    background:#fff; border-radius:16px; border:1px solid #f1f5f9;
    padding:20px; box-shadow:0 2px 10px rgba(0,0,0,.05);
}
.sidebar-title {
    font-size:13px; font-weight:800; color:#0f172a;
    margin-bottom:14px; display:flex; align-items:center; gap:8px;
}
.sidebar-title::after {
    content:''; flex:1; height:2px;
    background:linear-gradient(90deg,#2563eb,transparent);
    border-radius:99px; animation:gradLine .6s ease .5s both;
}
.cat-item {
    display:flex; align-items:center; justify-content:space-between;
    padding:8px 10px; border-radius:10px; text-decoration:none;
    cursor:pointer; transition:all .2s ease; margin-bottom:2px;
    border:2px solid transparent;
}
.cat-item:hover { background:#eff6ff; transform:translateX(4px); padding-left:14px; }
.cat-item.active {
    background:#eff6ff; border-color:#bfdbfe;
    transform:translateX(4px); padding-left:14px;
}
.cat-item.active .cat-dot { background:#1d4ed8; transform:scale(1.4); }
.cat-item.active .cat-count { background:#dbeafe; color:#1d4ed8; }
.cat-dot {
    width:8px; height:8px; border-radius:50%; background:#2563eb;
    transition:transform .2s, box-shadow .2s, background .2s; flex-shrink:0;
}
.cat-item:hover .cat-dot { transform:scale(1.4); box-shadow:0 0 0 3px rgba(37,99,235,.2); }
.cat-count {
    font-size:11px; color:#94a3b8; font-weight:700;
    background:#f1f5f9; padding:1px 7px; border-radius:99px;
    transition:background .2s, color .2s;
}
.cat-item:hover .cat-count { background:#dbeafe; color:#1d4ed8; }

/* ── Card hidden (lebih dari 6) ──────────────────────────────── */
.art-hidden {
    display:none !important;
}

/* ── Load more button ────────────────────────────────────────── */
#btn-show-all {
    display:inline-flex;
    align-items:center;
    gap:10px;
    padding:14px 32px;
    border-radius:16px;
    font-size:14px;
    font-weight:700;
    background:linear-gradient(135deg,#16a34a 0%,#22c55e 50%,#16a34a 100%);
    background-size:200% 100%;
    color:#fff;
    border:none;
    cursor:pointer;
    transition:transform .25s cubic-bezier(.22,1,.36,1),
               box-shadow .25s ease,
               background-position .4s ease;
    box-shadow:0 6px 20px rgba(22,163,74,.35),
               0 1px 0 rgba(255,255,255,.2) inset;
    letter-spacing:.01em;
    position:relative;
    overflow:hidden;
}
#btn-show-all::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.18),transparent);
    transform:translateX(-100%);
    transition:transform .5s ease;
}
#btn-show-all:hover {
    transform:translateY(-4px) scale(1.02);
    box-shadow:0 12px 32px rgba(22,163,74,.45),
               0 1px 0 rgba(255,255,255,.2) inset;
    background-position:100% 0;
}
#btn-show-all:hover::before {
    transform:translateX(100%);
}
#btn-show-all:active {
    transform:translateY(-1px) scale(.99);
}

.btn-load-more-style {
    display:inline-flex; align-items:center; gap:8px;
    padding:10px 24px; border-radius:14px; font-size:13px; font-weight:700;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff; border:none; cursor:pointer;
    transition:transform .2s, box-shadow .2s;
    box-shadow:0 4px 16px rgba(22,163,74,.3);
    white-space:nowrap;
}
.btn-load-more-style:hover {
    transform:translateY(-3px);
    box-shadow:0 8px 24px rgba(22,163,74,.4);
}
.btn-load-more-style .spinner {
    width:16px; height:16px; border:2px solid rgba(255,255,255,.4);
    border-top-color:#fff; border-radius:50%;
    animation:spin .7s linear infinite; display:none;
}
.btn-load-more-style.loading .spinner { display:block; }
.btn-load-more-style.loading .btn-label { display:none; }

/* ── Layout ─────────────────────────────────────────────────── */
.artikel-layout {
    display:grid; grid-template-columns:1fr 270px; gap:32px; align-items:start;
}
@media(max-width:1023px) {
    .artikel-layout { grid-template-columns:1fr; }
    .artikel-sidebar {
        position:static;
        animation:fadeUp .5s ease .2s both;
        order:-1; /* Naik ke atas artikel di mobile/tablet */
    }
}

/* Header artikel — judul kiri, tombol kanan */
.art-header {
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:20px;
    animation:fadeUp .4s ease both;
}
.art-header-left { min-width:0; }

/* Tombol Lihat Semua — persis seperti referensi */
#btn-lihat-semua {
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:9px 20px;
    border-radius:12px;
    font-size:13px;
    font-weight:700;
    color:#16a34a;
    background:#fff;
    border:1.5px solid #16a34a;
    cursor:pointer;
    white-space:nowrap;
    flex-shrink:0;
    transition:all .2s cubic-bezier(.22,1,.36,1);
    box-shadow:0 1px 4px rgba(22,163,74,.1);
}
#btn-lihat-semua:hover {
    background:#16a34a;
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 6px 18px rgba(22,163,74,.3);
}
#btn-lihat-semua.hidden-btn { display:none; }

/* Tombol Tampilkan Lebih Sedikit */
#btn-lebih-sedikit {
    display:none;
    align-items:center;
    gap:8px;
    padding:9px 20px;
    border-radius:12px;
    font-size:13px;
    font-weight:700;
    color:#64748b;
    background:#fff;
    border:1.5px solid #e2e8f0;
    cursor:pointer;
    white-space:nowrap;
    flex-shrink:0;
    transition:all .2s cubic-bezier(.22,1,.36,1);
    box-shadow:0 1px 4px rgba(0,0,0,.06);
}
#btn-lebih-sedikit:hover {
    background:#f8fafc;
    border-color:#cbd5e1;
    color:#334155;
    transform:translateY(-2px);
    box-shadow:0 4px 12px rgba(0,0,0,.1);
}
#btn-lebih-sedikit.show { display:inline-flex; }

/* Pesan semua sudah tampil */
#art-all-shown {
    display:none;
    font-size:12px;
    color:#94a3b8;
    padding:8px 14px;
    background:#f8fafc;
    border-radius:99px;
    border:1px solid #e2e8f0;
    white-space:nowrap;
    flex-shrink:0;
    align-items:center;
    gap:5px;
}
#art-all-shown.show { display:inline-flex; }
</style>
@endpush

@section('content')

<div id="art-progress"></div>

{{-- Hero --}}
@include('_partials.page-hero', [
    'banner'      => $banner ?? \App\Models\PageBanner::getForPage('artikel'),
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Artikel'],
    ],
])

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="artikel-layout">

            {{-- ── ARTIKEL GRID ────────────────────────────── --}}
            <div>
                {{-- Header: judul kiri + tombol kanan --}}
                <div class="art-header">
                    <div class="art-header-left">
                        <h2 style="font-size:20px;font-weight:800;color:#0f172a;line-height:1.2">Artikel</h2>
                        <p style="font-size:12px;color:#94a3b8;margin-top:3px">
                            <span id="art-showing">6</span> dari <span id="art-total">{{ $articles->count() }}</span> artikel ditampilkan
                        </p>
                    </div>

                    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                        {{-- Pesan semua sudah tampil --}}
                        <div id="art-all-shown">
                            <i class="fas fa-check-circle" style="color:#4ade80;font-size:12px"></i>
                            Semua <strong id="art-all-count" style="color:#475569">{{ $articles->count() }}</strong> artikel
                        </div>

                        {{-- Tombol Tampilkan Lebih Sedikit --}}
                        <button id="btn-lebih-sedikit" onclick="tampilkanLebihSedikit()">
                            <i class="fas fa-chevron-up" style="font-size:10px"></i>
                            Lebih Sedikit
                        </button>

                        {{-- Tombol Lihat Semua --}}
                        @if($articles->count() > 6)
                        <button id="btn-lihat-semua" onclick="tampilkanSemua()">
                            Lihat Semua <span style="font-size:14px">→</span>
                        </button>
                        @endif

                        {{-- Loading AJAX --}}
                        <div id="art-loading" style="display:none;align-items:center;gap:6px;font-size:12px;color:#2563eb;font-weight:600">
                            <div style="width:14px;height:14px;border:2px solid #dbeafe;border-top-color:#2563eb;border-radius:50%;animation:spin .7s linear infinite"></div>
                            Memuat...
                        </div>
                    </div>
                </div>

                {{-- Grid cards --}}
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px"
                     id="art-grid">
                    @foreach($articles as $idx => $art)
                    <a href="{{ route('artikel.detail', $art->slug) }}"
                       class="art-card {{ $idx >= 6 ? 'art-hidden' : '' }}"
                       data-index="{{ $idx }}">
                        {{-- Gambar + bling --}}
                        <div class="art-img-wrap">
                            <span class="sparkle" style="--size:14px;--dur:2s;--delay:0s;top:12px;left:12px"></span>
                            <span class="sparkle" style="--size:10px;--dur:2.4s;--delay:.6s;top:10px;right:14px"></span>
                            <span class="sparkle" style="--size:12px;--dur:1.9s;--delay:1s;bottom:18px;left:18px"></span>
                            <span class="sparkle" style="--size:8px;--dur:2.2s;--delay:1.4s;bottom:14px;right:10px"></span>
                            <span class="sparkle" style="--size:9px;--dur:2.5s;--delay:.3s;top:45%;left:20px"></span>
                            <span class="sparkle" style="--size:11px;--dur:2.1s;--delay:1.8s;top:28%;right:16px"></span>
                            @if($art->gambar)
                                <img src="{{ asset('storage/' . $art->gambar) }}" alt="{{ $art->judul }}" class="art-img" loading="lazy">
                            @else
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,#1e40af,#2563eb);display:flex;align-items:center;justify-content:center">
                                    <i class="fas fa-newspaper text-white opacity-30" style="font-size:40px"></i>
                                </div>
                            @endif
                            <div style="position:absolute;bottom:0;left:0;right:0;height:50px;background:linear-gradient(to top,rgba(0,0,0,.18),transparent);pointer-events:none"></div>
                        </div>
                        {{-- Body --}}
                        <div style="padding:16px;position:relative;z-index:2">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap">
                                @if($art->kategori)
                                <span class="art-cat-badge">{{ $art->kategori->nama_kategori }}</span>
                                @endif
                                <span style="font-size:10px;color:#94a3b8;display:flex;align-items:center;gap:3px">
                                    <i class="fas fa-clock" style="font-size:9px"></i>
                                    {{ $art->created_tm?->format('d M Y') }}
                                </span>
                            </div>
                            <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                {{ $art->judul }}
                            </h3>
                            <p style="font-size:12px;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                {{ Str::limit(strip_tags($art->isi), 100) }}
                            </p>
                            <div class="art-read-more">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right text-[10px] art-read-arrow"></i>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>

                {{-- Tombol Tampilkan Lebih Banyak --}}
                @if($articles->count() > 6)
                <div id="load-more-wrap" style="text-align:center;margin-top:36px;display:none">
                </div>
                @endif
            </div>

            {{-- ── SIDEBAR ──────────────────────────────────── --}}
            <aside class="artikel-sidebar">
                <div class="sidebar-card">
                    <p class="sidebar-title">
                        <i class="fas fa-tags text-blue-500"></i> Kategori
                    </p>

                    {{-- Semua Kategori (default) --}}
                    <div class="cat-item {{ !request('kategori_id') ? 'active' : '' }}"
                         onclick="filterKategori(null, this)"
                         style="animation:fadeRight .4s ease .2s both">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="cat-dot" style="background:#6366f1"></div>
                            <span style="font-size:13px;color:#334155;font-weight:600">Semua Kategori</span>
                        </div>
                        <span class="cat-count">{{ $articles->total() }}</span>
                    </div>

                    <div style="height:1px;background:#f1f5f9;margin:6px 0"></div>

                    @foreach($kategoris as $ki => $k)
                    <div class="cat-item {{ request('kategori_id') == $k->id ? 'active' : '' }}"
                         onclick="filterKategori({{ $k->id }}, this)"
                         style="animation:fadeRight .4s ease {{ ($ki * 60) + 300 }}ms both">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="cat-dot"></div>
                            <span style="font-size:13px;color:#334155;font-weight:500">{{ $k->nama_kategori }}</span>
                        </div>
                        <span class="cat-count">{{ $k->artikels_count }}</span>
                    </div>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    // ── Scroll progress ──────────────────────────────────────────────
    const bar = document.getElementById('art-progress');
    window.addEventListener('scroll', () => {
        const pct = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight) * 100;
        if (bar) bar.style.width = pct + '%';
    }, { passive:true });

    // ── IntersectionObserver: fade-in cards ─────────────────────────
    function observeCards() {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (!e.isIntersecting) return;
                setTimeout(() => e.target.classList.add('in'), i * 60);
                obs.unobserve(e.target);
            });
        }, { threshold:0.06, rootMargin:'0px 0px -20px 0px' });
        document.querySelectorAll('.art-card:not(.art-hidden):not(.in)').forEach(c => obs.observe(c));
    }
    observeCards();

    // ── Click ripple ─────────────────────────────────────────────────
    function addRipple(card) {
        card.addEventListener('click', function(e) {
            const rect   = card.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height) * 2;
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position:absolute;border-radius:50%;pointer-events:none;z-index:10;
                width:${size}px;height:${size}px;
                left:${e.clientX-rect.left-size/2}px;
                top:${e.clientY-rect.top-size/2}px;
                background:rgba(37,99,235,.1);transform:scale(0);
                animation:rippleOut .5s ease-out forwards;
            `;
            card.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        });
    }
    document.querySelectorAll('.art-card').forEach(addRipple);

    // ── Lazy image fade ──────────────────────────────────────────────
    document.querySelectorAll('.art-img').forEach(img => {
        img.style.opacity = '0';
        img.style.transition = 'opacity .4s ease';
        if (img.complete) { img.style.opacity = '1'; }
        else { img.addEventListener('load', () => { img.style.opacity = '1'; }); }
    });

    // ── Update info awal ─────────────────────────────────────────────
    const total   = document.querySelectorAll('.art-card').length;
    const showing = document.querySelectorAll('.art-card:not(.art-hidden)').length;
    const showEl  = document.getElementById('art-showing');
    const totalEl = document.getElementById('art-total');
    if (showEl)  showEl.textContent  = showing;
    if (totalEl) totalEl.textContent = total;

    // Expose
    window._addRipple = addRipple;

    // ── Scroll helper: geser ke atas #art-grid dengan offset navbar ──
    window._scrollKeGrid = function(delay) {
        setTimeout(function() {
            var grid = document.getElementById('art-grid');
            if (!grid) return;
            // Offset: tinggi navbar (~80px) + sedikit ruang napas (16px)
            var offset = 96;
            var rect   = grid.getBoundingClientRect();
            var top    = rect.top + window.pageYOffset - offset;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }, delay || 0);
    };
})();

// ── TAMPILKAN SEMUA ───────────────────────────────────────────────────────
function tampilkanSemua() {
    const btnSemua   = document.getElementById('btn-lihat-semua');
    const btnSedikit = document.getElementById('btn-lebih-sedikit');
    const allShown   = document.getElementById('art-all-shown');
    const hidden     = document.querySelectorAll('.art-card.art-hidden');
    const total      = document.querySelectorAll('.art-card').length;

    if (!hidden.length) return;

    // Animasi klik
    if (btnSemua) {
        btnSemua.style.transform = 'scale(.94)';
        setTimeout(() => { btnSemua.style.transform = ''; }, 150);
    }

    setTimeout(() => {
        // Tampilkan semua card tersembunyi dengan stagger
        hidden.forEach((card, i) => {
            card.classList.remove('art-hidden');
            card.style.opacity    = '0';
            card.style.transform  = 'translateY(24px) scale(.97)';
            card.style.transition = `opacity .4s ease ${i*35}ms, transform .4s cubic-bezier(.22,1,.36,1) ${i*35}ms`;
            requestAnimationFrame(() => setTimeout(() => {
                card.style.opacity   = '1';
                card.style.transform = 'translateY(0) scale(1)';
                card.classList.add('in');
                if (window._addRipple) window._addRipple(card);
            }, 20));
        });

        // Update info
        const showEl = document.getElementById('art-showing');
        const allCnt = document.getElementById('art-all-count');
        if (showEl) showEl.textContent = total;
        if (allCnt) allCnt.textContent = total;

        // Sembunyikan "Lihat Semua"
        if (btnSemua) {
            btnSemua.style.transition = 'opacity .2s ease';
            btnSemua.style.opacity    = '0';
            setTimeout(() => { btnSemua.classList.add('hidden-btn'); btnSemua.style.opacity = ''; }, 220);
        }

        // Tampilkan pesan + tombol "Lebih Sedikit"
        setTimeout(() => {
            if (allShown)   allShown.classList.add('show');
            if (btnSedikit) btnSedikit.classList.add('show');
        }, 300);

        // Scroll otomatis ke area card artikel setelah animasi mulai berjalan
        window._scrollKeGrid(200);
    }, 160);
}

// ── TAMPILKAN LEBIH SEDIKIT ───────────────────────────────────────────────
function tampilkanLebihSedikit() {
    const btnSemua   = document.getElementById('btn-lihat-semua');
    const btnSedikit = document.getElementById('btn-lebih-sedikit');
    const allShown   = document.getElementById('art-all-shown');
    const allCards   = document.querySelectorAll('.art-card');
    const total      = allCards.length;

    if (total <= 6) return;

    // Sembunyikan card ke-7 dan seterusnya dengan animasi
    allCards.forEach((card, i) => {
        if (i >= 6) {
            card.style.transition = `opacity .25s ease ${(total - i) * 20}ms, transform .25s ease ${(total - i) * 20}ms`;
            card.style.opacity    = '0';
            card.style.transform  = 'translateY(16px) scale(.97)';
            setTimeout(() => {
                card.classList.add('art-hidden');
                card.style.opacity   = '';
                card.style.transform = '';
                card.style.transition= '';
            }, 300 + (total - i) * 20);
        }
    });

    // Update info
    const showEl = document.getElementById('art-showing');
    if (showEl) showEl.textContent = Math.min(6, total);

    // Sembunyikan pesan + tombol sedikit
    if (allShown)   allShown.classList.remove('show');
    if (btnSedikit) btnSedikit.classList.remove('show');

    // Tampilkan kembali "Lihat Semua"
    setTimeout(() => {
        if (btnSemua) {
            btnSemua.classList.remove('hidden-btn');
            btnSemua.style.opacity = '0';
            btnSemua.style.transition = 'opacity .25s ease';
            setTimeout(() => { btnSemua.style.opacity = '1'; }, 20);
        }
    }, 350);

    // Scroll ke atas grid
    setTimeout(() => {
        document.querySelector('.art-header')?.scrollIntoView({ behavior:'smooth', block:'start' });
    }, 200);
}

// ── FILTER KATEGORI (AJAX) — OPTIMASI ────────────────────────────────────
function filterKategori(kategoriId, el) {
    const loading    = document.getElementById('art-loading');
    const grid       = document.getElementById('art-grid');
    const btnSemua   = document.getElementById('btn-lihat-semua');
    const btnSedikit = document.getElementById('btn-lebih-sedikit');
    const allShown   = document.getElementById('art-all-shown');

    // Hindari double click
    if (el.dataset.loading === 'true') return;

    // Update active state sidebar
    document.querySelectorAll('.cat-item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');
    el.dataset.loading = 'true';

    // Reset tombol ke state awal
    if (btnSedikit) btnSedikit.classList.remove('show');
    if (allShown)   allShown.classList.remove('show');

    // Tampilkan loading
    if (loading) loading.style.display = 'flex';

    // Fade out grid + skeleton langsung
    grid.style.transition = 'opacity .15s ease';
    grid.style.opacity    = '0';

    const params = new URLSearchParams();
    if (kategoriId) params.set('kategori_id', kategoriId);

    // Fetch data
    fetch(`{{ route('artikel') }}?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        signal: (window._artFetchController = new AbortController()).signal
    })
    .then(r => r.json())
    .then(data => {
        // Inject HTML baru
        grid.innerHTML = data.html;
        grid.style.transition = 'opacity .25s ease';
        grid.style.opacity    = '1';

        if (loading) loading.style.display = 'none';
        el.dataset.loading = 'false';

        // Hitung visible vs hidden
        const allCards    = grid.querySelectorAll('.art-card');
        const hiddenCards = grid.querySelectorAll('.art-hidden');
        const total       = allCards.length;
        const visible     = total - hiddenCards.length;

        // Update counter
        const showEl  = document.getElementById('art-showing');
        const totalEl = document.getElementById('art-total');
        const allCnt  = document.getElementById('art-all-count');
        if (showEl)  showEl.textContent  = visible;
        if (totalEl) totalEl.textContent = total;
        if (allCnt)  allCnt.textContent  = total;

        // Update tombol "Lihat Semua"
        if (btnSemua) {
            if (hiddenCards.length > 0) {
                btnSemua.classList.remove('hidden-btn');
                btnSemua.style.opacity = '1';
            } else {
                btnSemua.classList.add('hidden-btn');
            }
        }

        // Animasi cards yang visible — stagger ringan
        allCards.forEach((c, i) => {
            if (c.classList.contains('art-hidden')) return;
            c.style.opacity   = '0';
            c.style.transform = 'translateY(16px) scale(.97)';
            c.style.transition = `opacity .35s ease ${i * 40}ms, transform .35s ease ${i * 40}ms`;
            requestAnimationFrame(() => {
                setTimeout(() => {
                    c.style.opacity   = '1';
                    c.style.transform = 'translateY(0) scale(1)';
                    c.classList.add('in');
                }, 10);
            });
        });

    })
    .catch(err => {
        if (err.name === 'AbortError') return;
        if (loading) loading.style.display = 'none';
        el.dataset.loading = 'false';
        grid.style.opacity = '1';
        grid.innerHTML = `
            <div style="grid-column:1/-1;text-align:center;padding:40px;color:#ef4444">
                <i class="fas fa-exclamation-circle" style="font-size:24px;margin-bottom:8px;display:block"></i>
                <p style="font-size:13px;font-weight:600">Gagal memuat artikel.</p>
                <button onclick="location.reload()" style="margin-top:10px;padding:6px 16px;border-radius:8px;background:#f1f5f9;border:none;cursor:pointer;font-size:12px;font-weight:600">Muat Ulang</button>
            </div>`;
    });
}
</script>
@endpush
