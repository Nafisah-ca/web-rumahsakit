@extends('layouts.app')

@push('styles')
<style>
/* ============================================================
   ARTIKEL PAGE — PREMIUM ANIMATIONS
   ============================================================ */

/* ── Keyframes ─────────────────────────────────────────────── */
@keyframes fadeUp {
    from { opacity:0; transform:translateY(36px) scale(.97); }
    to   { opacity:1; transform:translateY(0)    scale(1); }
}
@keyframes fadeLeft {
    from { opacity:0; transform:translateX(-28px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes fadeRight {
    from { opacity:0; transform:translateX(28px); }
    to   { opacity:1; transform:translateX(0); }
}
@keyframes fadeDown {
    from { opacity:0; transform:translateY(-20px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes popIn {
    0%  { opacity:0; transform:scale(.72) translateY(12px); }
    70% { transform:scale(1.05) translateY(-2px); }
    100%{ opacity:1; transform:scale(1) translateY(0); }
}
@keyframes shimmer {
    0%   { background-position:-600px 0; }
    100% { background-position: 600px 0; }
}
@keyframes floatY {
    0%,100% { transform:translateY(0); }
    50%      { transform:translateY(-8px); }
}
@keyframes pulseBar {
    0%,100% { opacity:1; }
    50%      { opacity:.6; }
}
@keyframes rippleOut {
    to { transform:scale(3); opacity:0; }
}
@keyframes gradLine {
    from { width:0; opacity:0; }
    to   { width:100%; opacity:1; }
}
@keyframes countUp {
    from { opacity:0; transform:translateY(8px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes imageReveal {
    from { clip-path:inset(0 100% 0 0); }
    to   { clip-path:inset(0 0% 0 0); }
}
@keyframes spinSlow {
    to { transform:rotate(360deg); }
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
    transition:
        opacity .55s cubic-bezier(.22,1,.36,1),
        transform .55s cubic-bezier(.22,1,.36,1),
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
    opacity:1;
    transform:translateY(0) scale(1);
}
.art-card:hover {
    transform:translateY(-7px) scale(1.016) !important;
    box-shadow:0 18px 44px rgba(37,99,235,.14) !important;
    z-index:3;
}
.art-card:hover .art-img {
    transform:scale(1.08);
}
.art-card:hover .art-read-more {
    gap:8px;
    color:#1d4ed8;
}
.art-card:hover .art-read-arrow {
    transform:translateX(5px);
}

/* Image zoom */
.art-img-wrap {
    height:170px;
    overflow:hidden;
    position:relative;
}
.art-img {
    width:100%;
    height:100%;
    object-fit:cover;
    transition:transform .5s cubic-bezier(.22,1,.36,1);
}

/* Category badge */
.art-cat-badge {
    display:inline-block;
    font-size:10px;
    font-weight:700;
    color:#fff;
    background:linear-gradient(135deg,#2563eb,#3b82f6);
    padding:2px 10px;
    border-radius:99px;
    animation:popIn .4s cubic-bezier(.34,1.56,.64,1) both;
}

/* Read more arrow */
.art-read-more {
    display:flex;
    align-items:center;
    gap:4px;
    font-size:12px;
    font-weight:700;
    color:#2563eb;
    transition:gap .2s ease, color .2s ease;
    margin-top:10px;
}
.art-read-arrow {
    transition:transform .2s cubic-bezier(.22,1,.36,1);
    display:inline-block;
}

/* Hover overlay shimmer on card */
.art-card::before {
    content:'';
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,transparent 60%,rgba(37,99,235,.04));
    opacity:0;
    transition:opacity .3s;
    z-index:1;
    pointer-events:none;
}
.art-card:hover::before { opacity:1; }

/* ── Sidebar ────────────────────────────────────────────────── */
.artikel-sidebar {
    position:sticky;
    top:88px;
    animation:fadeRight .6s cubic-bezier(.22,1,.36,1) .3s both;
}
.sidebar-card {
    background:#fff;
    border-radius:16px;
    border:1px solid #f1f5f9;
    padding:20px;
    box-shadow:0 2px 10px rgba(0,0,0,.05);
}
.sidebar-title {
    font-size:13px;
    font-weight:800;
    color:#0f172a;
    margin-bottom:14px;
    display:flex;
    align-items:center;
    gap:8px;
}
.sidebar-title::after {
    content:'';
    flex:1;
    height:2px;
    background:linear-gradient(90deg,#2563eb,transparent);
    border-radius:99px;
    animation:gradLine .6s ease .5s both;
}

.cat-item {
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:8px 10px;
    border-radius:10px;
    text-decoration:none;
    transition:all .2s ease;
    margin-bottom:2px;
    opacity:0;
    transform:translateX(12px);
    animation:fadeRight .4s ease both;
}
.cat-item:hover {
    background:#eff6ff;
    transform:translateX(4px);
    padding-left:14px;
}
.cat-item:hover .cat-dot {
    transform:scale(1.4);
    box-shadow:0 0 0 3px rgba(37,99,235,.2);
}
.cat-dot {
    width:8px; height:8px;
    border-radius:50%;
    background:#2563eb;
    transition:transform .2s, box-shadow .2s;
    flex-shrink:0;
}
.cat-count {
    font-size:11px;
    color:#94a3b8;
    font-weight:700;
    background:#f1f5f9;
    padding:1px 7px;
    border-radius:99px;
    transition:background .2s, color .2s;
}
.cat-item:hover .cat-count {
    background:#dbeafe;
    color:#1d4ed8;
}

/* ── Empty state ────────────────────────────────────────────── */
.empty-float { animation:floatY 3s ease-in-out infinite; }

/* ── Pagination ─────────────────────────────────────────────── */
.pagination-wrap {
    animation:fadeUp .5s ease .3s both;
}

/* ── Layout ─────────────────────────────────────────────────── */
.artikel-layout {
    display:grid;
    grid-template-columns:1fr 270px;
    gap:32px;
    align-items:start;
}
@media(max-width:1023px) {
    .artikel-layout { grid-template-columns:1fr; }
    .artikel-sidebar { position:static; animation:fadeUp .5s ease .2s both; }
}
</style>
@endpush

@section('content')

{{-- Scroll progress --}}
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

            {{-- ── ARTIKEL GRID ─────────────────────────────── --}}
            <div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px" id="art-grid">
                    @forelse($articles as $idx => $art)
                    <a href="{{ route('artikel.detail', $art->slug) }}"
                       class="art-card"
                       data-index="{{ $idx + 1 }}"
                       style="transition-delay:{{ ($idx % 3) * 80 }}ms">

                        {{-- Gambar --}}
                        <div class="art-img-wrap">
                            @if($art->gambar)
                                <img src="{{ Storage::url($art->gambar) }}"
                                     alt="{{ $art->judul }}"
                                     class="art-img"
                                     loading="lazy">
                            @elseif($art->thumbnail)
                                <img src="{{ Storage::url($art->thumbnail) }}"
                                     alt="{{ $art->judul }}"
                                     class="art-img"
                                     loading="lazy">
                            @else
                                <div style="width:100%;height:100%;background:linear-gradient(135deg,#1e40af,#2563eb);display:flex;align-items:center;justify-content:center">
                                    <i class="fas fa-newspaper text-white opacity-30" style="font-size:40px"></i>
                                </div>
                            @endif

                            {{-- Overlay gradient bottom --}}
                            <div style="position:absolute;bottom:0;left:0;right:0;height:50px;
                                        background:linear-gradient(to top,rgba(0,0,0,.18),transparent);
                                        pointer-events:none"></div>
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

                            <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;
                                       line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;
                                       -webkit-box-orient:vertical;overflow:hidden;
                                       transition:color .2s ease">
                                {{ $art->judul }}
                            </h3>

                            <p style="font-size:12px;color:#64748b;line-height:1.5;
                                      display:-webkit-box;-webkit-line-clamp:2;
                                      -webkit-box-orient:vertical;overflow:hidden">
                                {{ Str::limit(strip_tags($art->isi), 100) }}
                            </p>

                            <div class="art-read-more">
                                Baca Selengkapnya
                                <i class="fas fa-arrow-right text-[10px] art-read-arrow"></i>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div style="grid-column:1/-1" class="empty-float">
                        <div style="text-align:center;padding:60px 20px;color:#94a3b8">
                            <div style="width:80px;height:80px;background:#f1f5f9;border-radius:50%;
                                        display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                <i class="fas fa-newspaper" style="font-size:32px;opacity:.3"></i>
                            </div>
                            <p style="font-size:15px;font-weight:700;margin-bottom:6px">Belum ada artikel</p>
                            <p style="font-size:13px">Nantikan artikel kesehatan terbaru dari kami.</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="pagination-wrap" style="margin-top:32px">
                    {{ $articles->links() }}
                </div>
            </div>

            {{-- ── SIDEBAR ──────────────────────────────────── --}}
            <aside class="artikel-sidebar">
                <div class="sidebar-card">
                    <p class="sidebar-title">
                        <i class="fas fa-tags text-blue-500"></i> Kategori
                    </p>
                    @foreach($kategoris as $ki => $k)
                    <a href="{{ route('artikel') }}?kategori_id={{ $k->id }}"
                       class="cat-item"
                       style="animation-delay:{{ ($ki * 60) + 300 }}ms">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="cat-dot"></div>
                            <span style="font-size:13px;color:#334155;font-weight:500">{{ $k->nama_kategori }}</span>
                        </div>
                        <span class="cat-count">{{ $k->artikels_count }}</span>
                    </a>
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

    // ── 1. Scroll progress bar ───────────────────────────────────────
    const bar = document.getElementById('art-progress');
    window.addEventListener('scroll', () => {
        const pct = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight) * 100;
        if (bar) bar.style.width = pct + '%';
    }, { passive: true });

    // ── 2. IntersectionObserver: article cards ───────────────────────
    const cards = document.querySelectorAll('.art-card');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('in');
            obs.unobserve(entry.target);
        });
    }, { threshold: 0.07, rootMargin: '0px 0px -30px 0px' });

    cards.forEach(c => obs.observe(c));

    // ── 3. Card ripple on click ──────────────────────────────────────
    cards.forEach(card => {
        card.addEventListener('click', function (e) {
            const rect   = card.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height) * 2;
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position:absolute;border-radius:50%;pointer-events:none;
                width:${size}px;height:${size}px;
                left:${e.clientX - rect.left - size/2}px;
                top:${e.clientY - rect.top - size/2}px;
                background:rgba(37,99,235,.12);
                transform:scale(0);z-index:10;
                animation:rippleOut .5s ease-out forwards;
            `;
            card.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        });
    });

    // ── 4. Inject ripple keyframe ─────────────────────────────────────
    const s = document.createElement('style');
    s.textContent = '@keyframes rippleOut { to { transform:scale(2.5); opacity:0; } }';
    document.head.appendChild(s);

    // ── 5. Sidebar category hover line indicator ─────────────────────
    document.querySelectorAll('.cat-item').forEach(item => {
        item.addEventListener('mouseenter', function () {
            this.style.background = '#eff6ff';
        });
        item.addEventListener('mouseleave', function () {
            this.style.background = '';
        });
    });

    // ── 6. Lazy image fade in ─────────────────────────────────────────
    const imgs = document.querySelectorAll('.art-img');
    imgs.forEach(img => {
        img.style.opacity = '0';
        img.style.transition = 'opacity .4s ease';
        if (img.complete) {
            img.style.opacity = '1';
        } else {
            img.addEventListener('load', () => { img.style.opacity = '1'; });
        }
    });

})();
</script>
@endpush
