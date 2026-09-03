@extends('layouts.app')

@push('styles')
<style>
/* ── EVENT CARD ANIMATIONS & HOVER ── */
.event-card {
    opacity: 0;
    transform: translateY(30px) scale(0.97);
    transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                border-color 0.35s ease;
    will-change: opacity, transform;
    backface-visibility: hidden;
}

.event-card.is-revealed {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.event-card.is-revealed:hover {
    transform: translateY(-8px) scale(1.025);
    box-shadow: 0 20px 30px -8px rgba(124, 58, 237, 0.15), 0 10px 15px -4px rgba(0, 0, 0, 0.06);
    border-color: rgba(168, 85, 247, 0.45);
}

.event-card .event-img {
    transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
}
.event-card:hover .event-img {
    transform: scale(1.08);
}

.event-card .event-arrow {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.event-card:hover .event-arrow {
    transform: translateX(5px);
}

.event-card .event-shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.2) 50%, transparent 60%);
    background-size: 200% 100%;
    background-position-x: 180%;
    transition: background-position-x 0.75s ease;
    pointer-events: none;
}
.event-card:hover .event-shine {
    background-position-x: -20%;
}

/* ── Card hidden (lebih dari 6) ── */
.ev-hidden {
    display: none !important;
}

/* ── Tombol Lihat Semua ── */
#ev-btn-lihat-semua {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 20px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    color: #7c3aed;
    background: #fff;
    border: 1.5px solid #7c3aed;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all .2s cubic-bezier(.22,1,.36,1);
    box-shadow: 0 1px 4px rgba(124,58,237,.1);
}
#ev-btn-lihat-semua:hover {
    background: #7c3aed;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(124,58,237,.3);
}
#ev-btn-lihat-semua.ev-hidden-btn { display: none; }

/* ── Tombol Lebih Sedikit ── */
#ev-btn-lebih-sedikit {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 9px 20px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    color: #64748b;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    cursor: pointer;
    white-space: nowrap;
    flex-shrink: 0;
    transition: all .2s cubic-bezier(.22,1,.36,1);
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
#ev-btn-lebih-sedikit:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #334155;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
#ev-btn-lebih-sedikit.ev-show { display: inline-flex; }

/* ── Pesan semua sudah tampil ── */
#ev-all-shown {
    display: none;
    font-size: 12px;
    color: #94a3b8;
    padding: 8px 14px;
    background: #f8fafc;
    border-radius: 99px;
    border: 1px solid #e2e8f0;
    white-space: nowrap;
    flex-shrink: 0;
    align-items: center;
    gap: 5px;
}
#ev-all-shown.ev-show { display: inline-flex; }

/* ── Event Sebelumnya: hidden, tombol, pesan ── */
.evl-hidden { display: none !important; }

#evl-btn-lihat-semua {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 20px; border-radius: 12px; font-size: 13px; font-weight: 700;
    color: #64748b; background: #fff; border: 1.5px solid #94a3b8;
    cursor: pointer; white-space: nowrap; flex-shrink: 0;
    transition: all .2s cubic-bezier(.22,1,.36,1);
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
#evl-btn-lihat-semua:hover {
    background: #64748b; color: #fff;
    transform: translateY(-2px); box-shadow: 0 6px 18px rgba(100,116,139,.3);
}
#evl-btn-lihat-semua.evl-hidden-btn { display: none; }

#evl-btn-lebih-sedikit {
    display: none; align-items: center; gap: 8px;
    padding: 9px 20px; border-radius: 12px; font-size: 13px; font-weight: 700;
    color: #64748b; background: #fff; border: 1.5px solid #e2e8f0;
    cursor: pointer; white-space: nowrap; flex-shrink: 0;
    transition: all .2s cubic-bezier(.22,1,.36,1);
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
#evl-btn-lebih-sedikit:hover {
    background: #f8fafc; border-color: #cbd5e1; color: #334155;
    transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1);
}
#evl-btn-lebih-sedikit.evl-show { display: inline-flex; }

#evl-all-shown {
    display: none; font-size: 12px; color: #94a3b8;
    padding: 8px 14px; background: #f8fafc; border-radius: 99px;
    border: 1px solid #e2e8f0; white-space: nowrap; flex-shrink: 0;
    align-items: center; gap: 5px;
}
#evl-all-shown.evl-show { display: inline-flex; }

@media (prefers-reduced-motion: reduce) {
    .event-card {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
    .event-card .event-img {
        transition: none !important;
    }
}
</style>
@endpush

@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('event'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Event & Kegiatan'],
]])

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- ===== EVENT MENDATANG ===== --}}
        <div class="mb-10">

            {{-- Header: judul kiri + info + tombol kanan --}}
            <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-1 h-6 bg-purple-600 rounded-full flex-shrink-0"></div>
                    <h2 class="text-xl font-extrabold text-gray-900">Event Mendatang</h2>
                    @if($eventsMendatang->count() > 0)
                    <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0">
                        <span id="ev-showing">{{ min(6, $eventsMendatang->count()) }}</span>
                        dari {{ $eventsMendatang->count() }} event
                    </span>
                    @endif
                </div>

                {{-- Tombol kanan — selalu dirender, JS yang kelola visibilitas --}}
                <div id="ev-btn-wrap" style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                    {{-- Pesan semua sudah tampil --}}
                    <div id="ev-all-shown">
                        <i class="fas fa-check-circle" style="color:#4ade80;font-size:12px"></i>
                        Semua <strong id="ev-all-count" style="color:#475569">{{ $eventsMendatang->count() }}</strong> event
                    </div>

                    {{-- Tombol Lebih Sedikit --}}
                    <button id="ev-btn-lebih-sedikit" onclick="evTampilkanLebihSedikit()">
                        <i class="fas fa-chevron-up" style="font-size:10px"></i>
                        Lebih Sedikit
                    </button>

                    {{-- Tombol Lihat Semua --}}
                    <button id="ev-btn-lihat-semua" onclick="evTampilkanSemua()">
                        Lihat Semua <span style="font-size:14px">→</span>
                    </button>
                </div>
            </div>

            @if($eventsMendatang->isEmpty())
            <div class="text-center py-16 text-gray-400 bg-white rounded-2xl border border-gray-100">
                <i class="fas fa-calendar-days text-5xl opacity-20 block mb-4"></i>
                <p class="font-semibold">Belum ada event mendatang</p>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="ev-grid">
                @foreach($eventsMendatang as $ev)
                <div class="flex flex-col {{ $loop->index >= 6 ? 'ev-hidden' : '' }}" data-ev-index="{{ $loop->index }}">
                    @include('_partials.event-card', ['ev' => $ev, 'lewat' => false, 'index' => $loop->index])
                </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- ===== EVENT SUDAH LEWAT ===== --}}
        @if($eventsLewat->isNotEmpty())
        <div class="mt-12 pt-10 border-t border-gray-200">

            {{-- Header: judul kiri + tombol kanan --}}
            <div class="flex items-center justify-between gap-3 mb-6 flex-wrap">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-1 h-6 bg-gray-400 rounded-full flex-shrink-0"></div>
                    <h2 class="text-xl font-extrabold text-gray-500">Event Sebelumnya</h2>
                    <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0">
                        <span id="evl-showing">{{ min(6, $eventsLewat->count()) }}</span>
                        dari {{ $eventsLewat->count() }} event
                    </span>
                </div>

                {{-- Tombol kanan — selalu dirender, JS yang kelola visibilitas --}}
                <div id="evl-btn-wrap" style="display:flex;align-items:center;gap:8px;flex-shrink:0">
                    <div id="evl-all-shown">
                        <i class="fas fa-check-circle" style="color:#4ade80;font-size:12px"></i>
                        Semua <strong id="evl-all-count" style="color:#475569">{{ $eventsLewat->count() }}</strong> event
                    </div>
                    <button id="evl-btn-lebih-sedikit" onclick="evlTampilkanLebihSedikit()">
                        <i class="fas fa-chevron-up" style="font-size:10px"></i>
                        Lebih Sedikit
                    </button>
                    <button id="evl-btn-lihat-semua" onclick="evlTampilkanSemua()">
                        Lihat Semua <span style="font-size:14px">→</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="evl-grid">
                @foreach($eventsLewat as $ev)
                <div class="flex flex-col {{ $loop->index >= 6 ? 'evl-hidden' : '' }}" data-evl-index="{{ $loop->index }}">
                    @include('_partials.event-card', ['ev' => $ev, 'lewat' => true, 'index' => $loop->index])
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── IntersectionObserver: reveal cards saat scroll ──────────────
    function observeCards() {
        if (!('IntersectionObserver' in window)) {
            document.querySelectorAll('.event-card').forEach(c => c.classList.add('is-revealed'));
            return;
        }
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const card  = entry.target;
                const index = parseInt(card.getAttribute('data-index') || '0', 10);
                setTimeout(() => card.classList.add('is-revealed'), (index % 3) * 90);
                obs.unobserve(card);
            });
        }, { rootMargin: '0px 0px -40px 0px', threshold: 0.1 });

        // Observe hanya card yang sedang terlihat (tidak hidden)
        document.querySelectorAll('#ev-grid > div:not(.ev-hidden) .event-card').forEach(c => observer.observe(c));
        // Event lewat — hanya yang tidak hidden
        document.querySelectorAll('#evl-grid > div:not(.evl-hidden) .event-card').forEach(c => observer.observe(c));
    }
    observeCards();

    // Sembunyikan wrapper tombol jika tidak ada card hidden (≤ 6 event)
    const btnWrap = document.getElementById('ev-btn-wrap');
    if (btnWrap) {
        const hasHidden = document.querySelectorAll('#ev-grid > div.ev-hidden').length > 0;
        btnWrap.style.display = hasHidden ? 'flex' : 'none';
    }

    // Sembunyikan wrapper tombol Event Lewat jika tidak ada card hidden
    const btnWrapL = document.getElementById('evl-btn-wrap');
    if (btnWrapL) {
        const hasHiddenL = document.querySelectorAll('#evl-grid > div.evl-hidden').length > 0;
        btnWrapL.style.display = hasHiddenL ? 'flex' : 'none';
    }

    // Expose untuk dipakai fungsi tampilkan semua
    window._evObserveNewCards = function() {
        if (!('IntersectionObserver' in window)) return;
        const obs = new IntersectionObserver((entries, o) => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                const card  = e.target;
                const index = parseInt(card.getAttribute('data-index') || '0', 10);
                setTimeout(() => card.classList.add('is-revealed'), (index % 3) * 90);
                o.unobserve(card);
            });
        }, { rootMargin: '0px 0px -40px 0px', threshold: 0.1 });

        document.querySelectorAll('#ev-grid > div:not(.ev-hidden) .event-card:not(.is-revealed)').forEach(c => obs.observe(c));
    };
});

// ── TAMPILKAN SEMUA ──────────────────────────────────────────────────
function evTampilkanSemua() {
    const btnSemua   = document.getElementById('ev-btn-lihat-semua');
    const btnSedikit = document.getElementById('ev-btn-lebih-sedikit');
    const allShown   = document.getElementById('ev-all-shown');
    const allCount   = document.getElementById('ev-all-count');
    const showingEl  = document.getElementById('ev-showing');
    const hiddenWrap = document.querySelectorAll('#ev-grid > div.ev-hidden');
    const total      = document.querySelectorAll('#ev-grid > div').length;

    if (!hiddenWrap.length) return;

    // Animasi klik tombol
    if (btnSemua) {
        btnSemua.style.transform = 'scale(.94)';
        setTimeout(() => { btnSemua.style.transform = ''; }, 150);
    }

    setTimeout(() => {
        // Tampilkan semua wrapper yang tersembunyi dengan stagger
        hiddenWrap.forEach((wrap, i) => {
            wrap.classList.remove('ev-hidden');
            const card = wrap.querySelector('.event-card');
            if (card) {
                card.style.opacity   = '0';
                card.style.transform = 'translateY(24px) scale(.97)';
                card.style.transition = `opacity .4s ease ${i * 50}ms, transform .4s cubic-bezier(.22,1,.36,1) ${i * 50}ms`;
                requestAnimationFrame(() => setTimeout(() => {
                    card.style.opacity   = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                    card.classList.add('is-revealed');
                }, 20));
            }
        });

        // Update counter
        if (showingEl) showingEl.textContent = total;
        if (allCount)  allCount.textContent  = total;

        // Toggle tombol
        if (btnSemua) {
            btnSemua.style.transition = 'opacity .2s ease';
            btnSemua.style.opacity    = '0';
            setTimeout(() => { btnSemua.classList.add('ev-hidden-btn'); btnSemua.style.opacity = ''; }, 220);
        }

        setTimeout(() => {
            if (allShown)   allShown.classList.add('ev-show');
            if (btnSedikit) btnSedikit.classList.add('ev-show');
        }, 300);

        // Scroll ke grid event mendatang
        setTimeout(() => {
            const grid = document.getElementById('ev-grid');
            if (!grid) return;
            const top = grid.getBoundingClientRect().top + window.pageYOffset - 96;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }, 200);

    }, 160);
}

// ── TAMPILKAN LEBIH SEDIKIT ──────────────────────────────────────────
function evTampilkanLebihSedikit() {
    const btnSemua   = document.getElementById('ev-btn-lihat-semua');
    const btnSedikit = document.getElementById('ev-btn-lebih-sedikit');
    const allShown   = document.getElementById('ev-all-shown');
    const showingEl  = document.getElementById('ev-showing');
    const allWraps   = document.querySelectorAll('#ev-grid > div');
    const total      = allWraps.length;

    if (total <= 6) return;

    // Sembunyikan wrapper ke-7 dan seterusnya dengan animasi
    allWraps.forEach((wrap, i) => {
        if (i < 6) return;
        const card = wrap.querySelector('.event-card');
        if (card) {
            card.style.transition = `opacity .25s ease ${(total - i) * 20}ms, transform .25s ease ${(total - i) * 20}ms`;
            card.style.opacity    = '0';
            card.style.transform  = 'translateY(16px) scale(.97)';
        }
        setTimeout(() => {
            wrap.classList.add('ev-hidden');
            if (card) {
                card.style.opacity    = '';
                card.style.transform  = '';
                card.style.transition = '';
            }
        }, 300 + (total - i) * 20);
    });

    // Update counter
    if (showingEl) showingEl.textContent = Math.min(6, total);

    // Toggle tombol
    if (allShown)   allShown.classList.remove('ev-show');
    if (btnSedikit) btnSedikit.classList.remove('ev-show');

    setTimeout(() => {
        if (btnSemua) {
            btnSemua.classList.remove('ev-hidden-btn');
            btnSemua.style.opacity    = '0';
            btnSemua.style.transition = 'opacity .25s ease';
            setTimeout(() => { btnSemua.style.opacity = '1'; }, 20);
        }
    }, 350);

    // Scroll ke atas header event mendatang
    setTimeout(() => {
        const grid = document.getElementById('ev-grid');
        if (!grid) return;
        const top = grid.getBoundingClientRect().top + window.pageYOffset - 96;
        window.scrollTo({ top: top, behavior: 'smooth' });
    }, 200);
}

// ── EVENT SEBELUMNYA: TAMPILKAN SEMUA ───────────────────────────────
function evlTampilkanSemua() {
    const btnSemua   = document.getElementById('evl-btn-lihat-semua');
    const btnSedikit = document.getElementById('evl-btn-lebih-sedikit');
    const allShown   = document.getElementById('evl-all-shown');
    const allCount   = document.getElementById('evl-all-count');
    const showingEl  = document.getElementById('evl-showing');
    const hiddenWrap = document.querySelectorAll('#evl-grid > div.evl-hidden');
    const total      = document.querySelectorAll('#evl-grid > div').length;

    if (!hiddenWrap.length) return;

    if (btnSemua) {
        btnSemua.style.transform = 'scale(.94)';
        setTimeout(() => { btnSemua.style.transform = ''; }, 150);
    }

    setTimeout(() => {
        hiddenWrap.forEach((wrap, i) => {
            wrap.classList.remove('evl-hidden');
            const card = wrap.querySelector('.event-card');
            if (card) {
                card.style.opacity    = '0';
                card.style.transform  = 'translateY(24px) scale(.97)';
                card.style.transition = `opacity .4s ease ${i * 50}ms, transform .4s cubic-bezier(.22,1,.36,1) ${i * 50}ms`;
                requestAnimationFrame(() => setTimeout(() => {
                    card.style.opacity   = '1';
                    card.style.transform = 'translateY(0) scale(1)';
                    card.classList.add('is-revealed');
                }, 20));
            }
        });

        if (showingEl) showingEl.textContent = total;
        if (allCount)  allCount.textContent  = total;

        if (btnSemua) {
            btnSemua.style.transition = 'opacity .2s ease';
            btnSemua.style.opacity    = '0';
            setTimeout(() => { btnSemua.classList.add('evl-hidden-btn'); btnSemua.style.opacity = ''; }, 220);
        }
        setTimeout(() => {
            if (allShown)   allShown.classList.add('evl-show');
            if (btnSedikit) btnSedikit.classList.add('evl-show');
        }, 300);

        // Scroll ke grid event sebelumnya
        setTimeout(() => {
            const grid = document.getElementById('evl-grid');
            if (!grid) return;
            const top = grid.getBoundingClientRect().top + window.pageYOffset - 96;
            window.scrollTo({ top: top, behavior: 'smooth' });
        }, 200);
    }, 160);
}

// ── EVENT SEBELUMNYA: TAMPILKAN LEBIH SEDIKIT ───────────────────────
function evlTampilkanLebihSedikit() {
    const btnSemua   = document.getElementById('evl-btn-lihat-semua');
    const btnSedikit = document.getElementById('evl-btn-lebih-sedikit');
    const allShown   = document.getElementById('evl-all-shown');
    const showingEl  = document.getElementById('evl-showing');
    const allWraps   = document.querySelectorAll('#evl-grid > div');
    const total      = allWraps.length;

    if (total <= 6) return;

    allWraps.forEach((wrap, i) => {
        if (i < 6) return;
        const card = wrap.querySelector('.event-card');
        if (card) {
            card.style.transition = `opacity .25s ease ${(total - i) * 20}ms, transform .25s ease ${(total - i) * 20}ms`;
            card.style.opacity    = '0';
            card.style.transform  = 'translateY(16px) scale(.97)';
        }
        setTimeout(() => {
            wrap.classList.add('evl-hidden');
            if (card) {
                card.style.opacity    = '';
                card.style.transform  = '';
                card.style.transition = '';
            }
        }, 300 + (total - i) * 20);
    });

    if (showingEl) showingEl.textContent = Math.min(6, total);

    if (allShown)   allShown.classList.remove('evl-show');
    if (btnSedikit) btnSedikit.classList.remove('evl-show');

    setTimeout(() => {
        if (btnSemua) {
            btnSemua.classList.remove('evl-hidden-btn');
            btnSemua.style.opacity    = '0';
            btnSemua.style.transition = 'opacity .25s ease';
            setTimeout(() => { btnSemua.style.opacity = '1'; }, 20);
        }
    }, 350);

    // Scroll ke atas grid event sebelumnya
    setTimeout(() => {
        const grid = document.getElementById('evl-grid');
        if (!grid) return;
        const top = grid.getBoundingClientRect().top + window.pageYOffset - 96;
        window.scrollTo({ top: top, behavior: 'smooth' });
    }, 200);
}
</script>
@endpush
