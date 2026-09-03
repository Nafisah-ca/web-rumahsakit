@extends('layouts.app')

@push('styles')
<style>
/* ============================================================
   ARTIKEL DETAIL — PREMIUM ANIMATIONS
   ============================================================ */

@keyframes fadeUp {
    from { opacity:0; transform:translateY(32px) scale(.97); }
    to   { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes fadeLeft {
    from { opacity:0; transform:translateX(-28px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes fadeRight {
    from { opacity:0; transform:translateX(28px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes popIn {
    0%  { opacity:0; transform:scale(.72); }
    70% { transform:scale(1.06); }
    100%{ opacity:1; transform:scale(1); }
}
@keyframes imageReveal {
    from { opacity:0; transform:scale(1.06); }
    to   { opacity:1; transform:scale(1); }
}
@keyframes pulseGlow {
    0%,100% { box-shadow:0 0 0 0 rgba(22,163,74,.4); }
    50%      { box-shadow:0 0 0 8px rgba(22,163,74,0); }
}
@keyframes shimmer {
    0%   { background-position:-600px 0; }
    100% { background-position:600px 0; }
}
@keyframes floatY {
    0%,100% { transform:translateY(0); }
    50%      { transform:translateY(-6px); }
}
@keyframes readingBar {
    from { width:0; }
}
@keyframes gradientMove {
    0%,100% { background-position:0% 50%; }
    50%      { background-position:100% 50%; }
}
@keyframes spinSlow {
    to { transform:rotate(360deg); }
}
@keyframes slideInLeft {
    from { opacity:0; transform:translateX(-20px); }
    to   { opacity:1; transform:translateX(0); }
}

/* ── Scroll progress ─────────────────────────────────────── */
#read-progress {
    position:fixed;
    top:0; left:0;
    height:3px;
    background:linear-gradient(90deg,#16a34a,#22c55e,#4ade80);
    background-size:200% 100%;
    animation:gradientMove 3s ease infinite;
    z-index:9999;
    width:0;
    transition:width .1s linear;
}

/* Reading time badge */
#read-progress-wrap {
    position:fixed;
    top:12px; right:16px;
    z-index:9998;
    animation:popIn .5s ease .8s both;
}
.read-time-pill {
    background:rgba(255,255,255,.92);
    backdrop-filter:blur(8px);
    border:1px solid #e2e8f0;
    border-radius:99px;
    padding:4px 12px;
    font-size:11px;
    font-weight:700;
    color:#334155;
    display:flex;
    align-items:center;
    gap:5px;
    box-shadow:0 2px 12px rgba(0,0,0,.08);
    transition:opacity .3s;
}
.read-time-pill.hidden-pill { opacity:0; pointer-events:none; }

/* ── Hero image ────────────────────────────────────────────── */
.art-hero-img {
    animation:imageReveal .8s cubic-bezier(.22,1,.36,1) .2s both;
    border-radius:16px;
    overflow:hidden;
}
.art-hero-img img {
    transition:transform .6s cubic-bezier(.22,1,.36,1);
}
.art-hero-img:hover img {
    transform:scale(1.03);
}

/* ── Content card ──────────────────────────────────────────── */
.art-content-card {
    animation:fadeUp .7s cubic-bezier(.22,1,.36,1) .3s both;
    background:#fff;
    border-radius:16px;
    padding:32px;
    box-shadow:0 2px 12px rgba(0,0,0,.06);
    border:1px solid #f1f5f9;
}

/* Prose enhancements */
.art-content-card .prose h2,
.art-content-card .prose h3 {
    position:relative;
    padding-left:14px;
}
.art-content-card .prose h2::before,
.art-content-card .prose h3::before {
    content:'';
    position:absolute;
    left:0; top:50%;
    transform:translateY(-50%);
    width:4px;
    height:70%;
    background:linear-gradient(to bottom,#16a34a,#22c55e);
    border-radius:99px;
}
.art-content-card .prose p {
    transition:color .2s;
}
.art-content-card .prose img {
    border-radius:12px;
    box-shadow:0 4px 20px rgba(0,0,0,.1);
}

/* ── Share section ─────────────────────────────────────────── */
.art-share-card {
    animation:fadeUp .6s cubic-bezier(.22,1,.36,1) .5s both;
    background:#fff;
    border-radius:16px;
    padding:20px;
    box-shadow:0 2px 12px rgba(0,0,0,.06);
    border:1px solid #f1f5f9;
}
.share-btn {
    display:inline-flex;
    align-items:center;
    gap:7px;
    padding:8px 16px;
    border-radius:12px;
    font-size:12px;
    font-weight:700;
    color:#fff;
    text-decoration:none;
    transition:transform .2s, box-shadow .2s, opacity .2s;
    position:relative;
    overflow:hidden;
}
.share-btn:hover {
    transform:translateY(-3px);
    box-shadow:0 8px 20px rgba(0,0,0,.18);
}
.share-btn::after {
    content:'';
    position:absolute;
    inset:0;
    background:rgba(255,255,255,0);
    transition:background .2s;
}
.share-btn:hover::after { background:rgba(255,255,255,.1); }

/* ── Sidebar cards ─────────────────────────────────────────── */
.sidebar-related {
    animation:fadeRight .6s cubic-bezier(.22,1,.36,1) .4s both;
    background:#fff;
    border-radius:16px;
    padding:20px;
    box-shadow:0 2px 12px rgba(0,0,0,.06);
    border:1px solid #f1f5f9;
}
.related-item {
    opacity:0;
    transform:translateX(16px);
    transition:opacity .4s ease, transform .4s ease;
    border-radius:12px;
    transition:all .25s ease;
}
.related-item.in {
    opacity:1;
    transform:translateX(0);
}
.related-item:hover {
    background:#f8fafc;
    transform:translateX(4px);
}
.related-thumb {
    transition:transform .3s ease;
    border-radius:10px;
    overflow:hidden;
}
.related-item:hover .related-thumb img {
    transform:scale(1.08);
}

/* ── Dokter CTA card ───────────────────────────────────────── */
.dokter-cta {
    animation:fadeRight .6s cubic-bezier(.22,1,.36,1) .6s both;
    background:linear-gradient(135deg,#166534,#16a34a,#15803d);
    background-size:200% 200%;
    animation-name:fadeRight, gradientMove;
    animation-duration:.6s, 6s;
    animation-timing-function:cubic-bezier(.22,1,.36,1), ease;
    animation-delay:.6s, 1.2s;
    animation-iteration-count:1, infinite;
    animation-fill-mode:both, none;
    border-radius:16px;
    padding:20px;
    color:#fff;
}
.dokter-foto-ring {
    animation:pulseGlow 2.5s infinite;
    border-radius:50%;
    display:inline-block;
}
.dokter-cta-btn {
    display:block;
    width:100%;
    background:#fff;
    color:#16a34a;
    font-weight:700;
    font-size:13px;
    padding:12px;
    border-radius:12px;
    text-align:center;
    text-decoration:none;
    transition:transform .2s, box-shadow .2s, background .2s;
    position:relative;
    overflow:hidden;
}
.dokter-cta-btn:hover {
    transform:translateY(-2px);
    box-shadow:0 6px 20px rgba(0,0,0,.18);
    background:#f0fdf4;
}
.dokter-cta-btn::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.3),transparent);
    transform:translateX(-100%);
    transition:transform .5s ease;
}
.dokter-cta-btn:hover::before {
    transform:translateX(100%);
}

/* ── Back to top ───────────────────────────────────────────── */
#btn-back-art {
    position:fixed;
    bottom:100px; right:24px;
    width:42px; height:42px;
    background:linear-gradient(135deg,#16a34a,#22c55e);
    color:#fff;
    border-radius:12px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    box-shadow:0 4px 16px rgba(22,163,74,.4);
    border:none;
    z-index:50;
    opacity:0;
    transform:translateY(16px);
    transition:opacity .3s, transform .3s;
}
#btn-back-art.show {
    opacity:1;
    transform:translateY(0);
}
#btn-back-art:hover {
    transform:translateY(-3px) !important;
    box-shadow:0 8px 24px rgba(22,163,74,.5);
}

/* ── Sidebar sticky (desktop only) ─────────────────────────── */
.art-detail-sidebar {
    position: sticky;
    top: 96px;
    align-self: start;
}

@media (max-width: 1023px) {
    .art-detail-sidebar {
        position: static;
    }
}
</style>
@endpush

@section('content')

{{-- Reading progress bar --}}
<div id="read-progress"></div>

{{-- Reading time pill --}}
<div id="read-progress-wrap">
    <div class="read-time-pill" id="read-time-pill">
        <i class="fas fa-book-open text-green-500 text-xs"></i>
        <span id="read-pct">0%</span>
    </div>
</div>

{{-- Back to top --}}
<button id="btn-back-art" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="fas fa-arrow-up text-sm"></i>
</button>

{{-- Hero --}}
@include('_partials.page-hero', [
    'banner'      => $banner ?? \App\Models\PageBanner::getForPage('artikel'),
    'pageTitle'   => $artikel->judul,
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Artikel',  'url' => route('artikel')],
        ['label' => Str::limit($artikel->judul, 40)],
    ],
])

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:items-start">

            {{-- ── KONTEN UTAMA ─────────────────────────────── --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Gambar utama --}}
                @if($artikel->gambar)
                <div class="art-hero-img shadow-sm">
                    <img src="{{ Storage::url($artikel->gambar) }}"
                         alt="{{ $artikel->judul }}"
                         class="w-full object-cover max-h-80">
                </div>
                @else
                <div class="art-hero-img"
                     style="height:180px;background:linear-gradient(135deg,#1e40af,#2563eb);
                            display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-newspaper text-6xl text-white opacity-20"
                       style="animation:floatY 3s ease-in-out infinite"></i>
                </div>
                @endif

                {{-- Isi artikel --}}
                <div class="art-content-card" id="art-content">
                    <div class="prose prose-slate max-w-none" style="line-height:1.9;font-size:15px">
                        {!! $artikel->isi !!}
                    </div>
                </div>

                {{-- Share --}}
                <div class="art-share-card">
                    <p style="font-size:13px;font-weight:800;color:#0f172a;margin-bottom:12px;display:flex;align-items:center;gap:8px">
                        <i class="fas fa-share-nodes text-green-500"></i> Bagikan Artikel
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <a href="https://wa.me/?text={{ urlencode($artikel->judul . ' - ' . request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="share-btn" style="background:#25d366">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="share-btn" style="background:#1877f2">
                            <i class="fab fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($artikel->judul) }}&url={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="share-btn" style="background:#000">
                            <i class="fab fa-x-twitter"></i> X
                        </a>
                        <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($artikel->judul) }}"
                           target="_blank" rel="noopener"
                           class="share-btn" style="background:#229ed9">
                            <i class="fab fa-telegram"></i> Telegram
                        </a>
                        <button id="btn-copy-link" onclick="copyArtikelLink()"
                                class="share-btn" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0">
                            <i class="fas fa-link"></i>
                            <span id="copy-label">Salin Link</span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── SIDEBAR ──────────────────────────────────── --}}
            <div class="space-y-5 art-detail-sidebar">

                {{-- Artikel Terkait --}}
                <div class="sidebar-related">
                    <h3 style="font-size:14px;font-weight:800;color:#0f172a;margin-bottom:16px;
                               display:flex;align-items:center;gap:8px">
                        <i class="fas fa-newspaper text-blue-500"></i> Artikel Terkait
                    </h3>
                    <div class="space-y-2" id="related-list">
                        @forelse($related as $ri => $rel)
                        <a href="{{ route('artikel.detail', $rel->slug) }}"
                           class="related-item flex gap-3 items-start p-2"
                           data-ri="{{ $ri }}">
                            <div class="related-thumb w-14 h-14 rounded-lg overflow-hidden flex-shrink-0"
                                 style="background:linear-gradient(135deg,#1e3a5f,#0284c7)">
                                @if($rel->gambar)
                                <img src="{{ Storage::url($rel->gambar) }}"
                                     class="w-full h-full object-cover" loading="lazy">
                                @elseif($rel->thumbnail)
                                <img src="{{ Storage::url($rel->thumbnail) }}"
                                     class="w-full h-full object-cover" loading="lazy">
                                @else
                                <div class="flex items-center justify-center h-full">
                                    <i class="fas fa-newspaper text-white opacity-50"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p style="font-size:12px;font-weight:700;color:#0f172a;
                                          line-height:1.4;display:-webkit-box;
                                          -webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                                    {{ $rel->judul }}
                                </p>
                                <p style="font-size:10px;color:#94a3b8;margin-top:3px;display:flex;align-items:center;gap:3px">
                                    <i class="fas fa-clock text-[9px]"></i>
                                    {{ $rel->created_tm?->format('d M Y') }}
                                </p>
                            </div>
                        </a>
                        @empty
                        <p style="font-size:13px;color:#94a3b8;text-align:center;padding:16px 0">
                            <i class="fas fa-inbox opacity-30 text-2xl block mb-2"></i>
                            Tidak ada artikel terkait.
                        </p>
                        @endforelse
                    </div>
                </div>

                {{-- Konsultasi Dokter CTA --}}
                <div class="dokter-cta">
                    {{-- Judul --}}
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px">
                        <i class="fas fa-user-md text-green-300 text-xl"></i>
                        <h4 style="font-weight:800;font-size:15px">Konsultasi Dokter</h4>
                    </div>

                    @if(isset($dokterJanji) && $dokterJanji)
                    @php
                        $dok          = $dokterJanji;
                        $spNama       = $dok->spesialisasi?->nama_spesialis
                                        ?? $artikel->kategori?->spesialisasi?->nama_spesialis
                                        ?? null;
                        $spId         = $artikel->kategori?->spesialis_id;
                        $fotoUrl      = null;
                        if ($dok->foto) {
                            $fotoUrl = str_starts_with($dok->foto, 'images/')
                                ? asset($dok->foto)
                                : Storage::url($dok->foto);
                        }
                    @endphp

                    {{-- Sub-judul --}}
                    <p style="font-size:11px;color:rgba(255,255,255,.6);font-weight:600;
                               letter-spacing:.04em;text-transform:uppercase;margin-bottom:10px">
                        Rekomendasi untuk Anda
                    </p>
                    <p style="font-size:11px;color:rgba(255,255,255,.75);margin-bottom:10px;line-height:1.5">
                        Berdasarkan topik artikel ini, kami merekomendasikan dokter berikut:
                    </p>

                    {{-- Card dokter --}}
                    <div style="display:flex;align-items:center;gap:12px;
                                background:rgba(255,255,255,.12);border-radius:14px;
                                padding:12px;margin-bottom:12px">
                        <div class="dokter-foto-ring" style="flex-shrink:0">
                            @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="{{ $dok->nama_dokter }}"
                                 style="width:52px;height:52px;border-radius:50%;object-fit:cover;
                                        border:2px solid rgba(255,255,255,.5);display:block"
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                            <div style="display:none;width:52px;height:52px;border-radius:50%;
                                        background:rgba(255,255,255,.2);align-items:center;justify-content:center">
                                <i class="fas fa-user-md" style="color:white;font-size:20px"></i>
                            </div>
                            @else
                            <div style="width:52px;height:52px;border-radius:50%;
                                        background:rgba(255,255,255,.2);display:flex;
                                        align-items:center;justify-content:center">
                                <i class="fas fa-user-md" style="color:white;font-size:20px"></i>
                            </div>
                            @endif
                        </div>
                        <div style="min-width:0;flex:1">
                            <p style="font-weight:700;font-size:13px;color:#fff;line-height:1.3">
                                {{ $dok->nama_dokter }}
                            </p>
                            <p style="font-size:11px;color:rgba(255,255,255,.75);margin-top:2px">
                                {{ $spNama ?? 'Spesialis' }}
                            </p>
                            @if($dok->jadwalAktif->isNotEmpty())
                            <p style="font-size:10px;color:rgba(255,255,255,.6);margin-top:3px;
                                      display:flex;align-items:center;gap:3px">
                                <i class="fas fa-clock" style="font-size:9px"></i>
                                {{ $dok->jadwalAktif->pluck('hari')->unique()->implode(', ') }}
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Buat Janji Temu --}}
                    <a href="{{ route('portal.booking.create', ['dokter_id' => $dok->id]) }}"
                       class="dokter-cta-btn" style="margin-bottom:10px">
                        <i class="fas fa-calendar-check mr-2 text-green-600"></i> Buat Janji Temu
                    </a>

                    {{-- Catatan rekomendasi --}}
                    <p style="font-size:10px;color:rgba(255,255,255,.55);line-height:1.5;
                               margin-top:10px;margin-bottom:10px;font-style:italic">
                        Dokter di atas merupakan rekomendasi. Anda juga dapat memilih dokter spesialis lainnya.
                    </p>

                    {{-- Tombol Lihat Semua Dokter [Nama Spesialis] --}}
                    @if($spId && $spNama)
                    <a href="{{ route('dokter.by-spesialis', $spId) }}"
                       style="display:flex;align-items:center;justify-content:center;gap:7px;
                              width:100%;padding:8px 14px;border-radius:10px;
                              background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);
                              font-size:11px;font-weight:700;color:#fff;text-decoration:none;
                              transition:background .2s,border-color .2s;text-align:center"
                       onmouseover="this.style.background='rgba(255,255,255,.25)'"
                       onmouseout="this.style.background='rgba(255,255,255,.15)'">
                        <i class="fas fa-users" style="font-size:10px;opacity:.8"></i>
                        Lihat Semua Dokter {{ $spNama }}
                    </a>
                    @else
                    <a href="{{ route('dokter') }}"
                       style="display:flex;align-items:center;justify-content:center;gap:7px;
                              width:100%;padding:8px 14px;border-radius:10px;
                              background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);
                              font-size:11px;font-weight:700;color:#fff;text-decoration:none;
                              transition:background .2s;text-align:center"
                       onmouseover="this.style.background='rgba(255,255,255,.25)'"
                       onmouseout="this.style.background='rgba(255,255,255,.15)'">
                        <i class="fas fa-users" style="font-size:10px;opacity:.8"></i>
                        Lihat Semua Dokter
                    </a>
                    @endif

                    @else
                    {{-- Fallback: tidak ada dokter terkait spesialisasi --}}
                    @php
                        $spNamaFallback = $artikel->kategori?->spesialisasi?->nama_spesialis ?? null;
                        $spIdFallback   = $artikel->kategori?->spesialis_id ?? null;
                    @endphp
                    <p style="font-size:13px;color:rgba(255,255,255,.8);margin-bottom:14px;line-height:1.6">
                        Konsultasikan keluhan Anda dengan dokter spesialis kami.
                    </p>
                    @if($spIdFallback && $spNamaFallback)
                    <a href="{{ route('dokter.by-spesialis', $spIdFallback) }}" class="dokter-cta-btn">
                        <i class="fas fa-users mr-2 text-green-600"></i>
                        Lihat Dokter {{ $spNamaFallback }}
                    </a>
                    @else
                    <a href="{{ route('dokter') }}" class="dokter-cta-btn">
                        <i class="fas fa-user-md mr-2 text-green-600"></i> Lihat Dokter Kami
                    </a>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {

    // ── 1. Reading progress bar + percentage ─────────────────────────
    const bar     = document.getElementById('read-progress');
    const pctEl   = document.getElementById('read-pct');
    const backBtn = document.getElementById('btn-back-art');
    const pill    = document.getElementById('read-time-pill');
    const content = document.getElementById('art-content');

    window.addEventListener('scroll', () => {
        if (!content) return;
        const rect    = content.getBoundingClientRect();
        const total   = content.offsetHeight;
        const scrolled= Math.max(0, -rect.top);
        const pct     = Math.min(100, Math.round(scrolled / total * 100));

        if (bar)   bar.style.width = pct + '%';
        if (pctEl) pctEl.textContent = pct + '%';

        // Back to top
        if (backBtn) {
            backBtn.classList.toggle('show', window.scrollY > 400);
        }

        // Hide pill when at top
        if (pill) {
            pill.classList.toggle('hidden-pill', window.scrollY < 100);
        }
    }, { passive: true });

    // ── 2. Related articles stagger animation ────────────────────────
    const relItems = document.querySelectorAll('.related-item');
    const relObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (!e.isIntersecting) return;
            const idx = parseInt(e.target.dataset.ri || 0);
            setTimeout(() => e.target.classList.add('in'), idx * 100);
            relObs.unobserve(e.target);
        });
    }, { threshold: 0.1 });
    relItems.forEach(el => relObs.observe(el));

    // ── 3. Share buttons ripple ──────────────────────────────────────
    document.querySelectorAll('.share-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const rect   = btn.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height) * 2.5;
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position:absolute;border-radius:50%;pointer-events:none;z-index:10;
                width:${size}px;height:${size}px;
                left:${e.clientX - rect.left - size/2}px;
                top:${e.clientY - rect.top - size/2}px;
                background:rgba(255,255,255,.25);transform:scale(0);
                animation:rippleBtn .5s ease-out forwards;
            `;
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        });
    });

    // ── 4. Prose image lazy reveal ───────────────────────────────────
    const proseImgs = document.querySelectorAll('.prose img');
    proseImgs.forEach(img => {
        img.style.opacity = '0';
        img.style.transition = 'opacity .5s ease, transform .5s ease';
        img.style.transform  = 'translateY(12px)';
        const imgObs = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.style.opacity   = '1';
                    e.target.style.transform = 'translateY(0)';
                    imgObs.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        imgObs.observe(img);
    });

    // ── 5. Prose paragraphs fade in on scroll ────────────────────────
    const paras = document.querySelectorAll('.prose p, .prose h2, .prose h3, .prose ul, .prose ol');
    paras.forEach((p, i) => {
        p.style.opacity   = '0';
        p.style.transform = 'translateY(14px)';
        p.style.transition = `opacity .5s ease ${i * 30}ms, transform .5s ease ${i * 30}ms`;
    });
    const paraObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.opacity   = '1';
                e.target.style.transform = 'translateY(0)';
                paraObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.05 });
    paras.forEach(p => paraObs.observe(p));

    // ── 6. Inject keyframes ──────────────────────────────────────────
    const s = document.createElement('style');
    s.textContent = `
        @keyframes rippleBtn { to { transform:scale(2); opacity:0; } }
        @keyframes floatY {
            0%,100%{transform:translateY(0)}
            50%{transform:translateY(-6px)}
        }
    `;
    document.head.appendChild(s);

})();

// ── Copy link ────────────────────────────────────────────────────────────
function copyArtikelLink() {
    const url = '{{ request()->url() }}';
    const btn = document.getElementById('btn-copy-link');
    const lbl = document.getElementById('copy-label');
    navigator.clipboard.writeText(url).then(() => {
        lbl.textContent      = 'Tersalin! ✓';
        btn.style.background = '#dcfce7';
        btn.style.color      = '#16a34a';
        btn.style.borderColor= '#86efac';
        btn.style.transform  = 'scale(1.05)';
        setTimeout(() => {
            lbl.textContent      = 'Salin Link';
            btn.style.background = '#f1f5f9';
            btn.style.color      = '#475569';
            btn.style.borderColor= '#e2e8f0';
            btn.style.transform  = '';
        }, 2500);
    });
}
</script>
@endpush
