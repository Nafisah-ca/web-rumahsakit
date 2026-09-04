@extends('layouts.app')

@push('styles')
<style>
/* ===== ANIMASI HALAMAN DOKTER ===== */

/* Keyframes */
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(32px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInLeft {
    from { opacity: 0; transform: translateX(-28px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.88); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes slideInRight {
    from { opacity: 0; transform: translateX(28px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes pulseGlow {
    0%, 100% { box-shadow: 0 0 0 0 rgba(22,163,74,0.35); }
    50%       { box-shadow: 0 0 0 8px rgba(22,163,74,0); }
}
@keyframes shimmer {
    0%   { background-position: -400px 0; }
    100% { background-position: 400px 0; }
}
@keyframes countUp {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes rotateIn {
    from { opacity: 0; transform: rotate(-8deg) scale(0.9); }
    to   { opacity: 1; transform: rotate(0) scale(1); }
}
@keyframes float {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-6px); }
}

/* Filter bar animasi */
.filter-bar-wrap {
    animation: fadeInDown .5s ease both;
}
.filter-dr-btn {
    transition: all .22s cubic-bezier(.4,0,.2,1);
    animation: scaleIn .4s ease both;
}
.filter-dr-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(22,163,74,.18);
}
.filter-dr-btn.active-filter {
    animation: pulseGlow 2s infinite;
}

/* Info banner spesialisasi */
.info-spesialis-bar {
    animation: fadeInDown .4s ease both;
}

/* Section header (Dokter Spesialis / Dokter Umum) */
.section-header {
    animation: fadeInLeft .5s ease both;
}

/* Card dokter — staggered animation */
.dokter-card-wrap {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity .5s ease, transform .5s cubic-bezier(.22,1,.36,1),
                box-shadow .25s ease;
}
.dokter-card-wrap.visible {
    opacity: 1;
    transform: translateY(0);
}
.dokter-card-wrap:hover {
    transform: translateY(-6px) scale(1.015);
    box-shadow: 0 16px 40px rgba(0,0,0,.13) !important;
    z-index: 2;
}

/* Shimmer skeleton loader */
.card-skeleton {
    border-radius: 18px;
    overflow: hidden;
    background: #f1f5f9;
    height: 380px;
}
.card-skeleton::after {
    content: '';
    display: block;
    height: 100%;
    background: linear-gradient(90deg,
        #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
    background-size: 800px 100%;
    animation: shimmer 1.4s infinite;
}

/* Empty state */
.empty-state-wrap {
    animation: scaleIn .6s ease both;
}

/* Badge count animasi */
.badge-count-animated {
    animation: countUp .5s ease both;
}

/* Foto dokter hover zoom */
.dokter-card-wrap .foto-circle {
    transition: transform .35s cubic-bezier(.22,1,.36,1);
}
.dokter-card-wrap:hover .foto-circle {
    transform: scale(1.07);
}

/* Tombol Buat Janji ripple */
.btn-janji {
    position: relative;
    overflow: hidden;
}
.btn-janji::after {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0);
    transition: background .2s;
}
.btn-janji:hover::after {
    background: rgba(255,255,255,0.12);
}

/* Floating stethoscope decorative */
.deco-float {
    animation: float 4s ease-in-out infinite;
}

/* Page title animasi */
.page-mode-badge {
    animation: slideInRight .5s ease both;
}

/* Search/filter highlight */
.filter-dr-btn:focus {
    outline: 2px solid #16a34a;
    outline-offset: 2px;
}
</style>
@endpush

@section('content')

{{-- Hero --}}
@include('_partials.page-hero', [
    'banner'      => $banner ?? \App\Models\PageBanner::getForPage('dokter'),
    'pageTitle'   => isset($online) && $online
                        ? 'Layanan Online'
                        : (isset($modeDaftar) && $modeDaftar
                            ? 'Daftar Poliklinik — ' . ($activeSpesialisNama ?? 'Semua')
                            : 'Profil Dokter'),
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Dokter'],
    ],
])

{{-- ===== MODE BADGE ===== --}}
<div class="bg-white border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4 py-2 flex items-center gap-3">
        @if(isset($online) && $online)
            <span class="page-mode-badge inline-flex items-center gap-1.5 bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">
                <i class="fas fa-laptop-medical text-blue-500"></i> Layanan Online
            </span>
        @elseif(isset($modeDaftar) && $modeDaftar)
            <span class="page-mode-badge inline-flex items-center gap-1.5 bg-green-50 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-100">
                <i class="fas fa-calendar-check text-green-500"></i> Daftar Poliklinik
            </span>
        @else
            <span class="page-mode-badge inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs font-bold px-3 py-1 rounded-full border border-purple-100">
                <i class="fas fa-user-md text-purple-500"></i> Profil Dokter
            </span>
        @endif
        <span class="text-xs text-gray-400">Pilih spesialisasi untuk filter dokter</span>
    </div>
</div>

{{-- ===== FILTER SPESIALISASI ===== --}}
<div class="bg-white border-b border-gray-100 sticky top-16 z-40 shadow-sm filter-bar-wrap">
    <div class="max-w-screen-xl mx-auto px-4 py-3">
        <div class="flex flex-wrap gap-2" id="filter-container">
            <button onclick="filterDokter('semua', this)"
                data-target="semua"
                class="filter-dr-btn px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       {{ ($activeSpesialisSlug ?? null) == null ? 'bg-green-600 border-green-600 text-white active-filter' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600' }}"
                style="animation-delay: 0s">
                <i class="fas fa-th-large mr-1 opacity-70"></i> Semua
            </button>
            @foreach($spesialisasis as $idx => $sp)
            <button onclick="filterDokter('{{ $sp->id }}', this)"
                data-target="{{ $sp->id }}"
                class="filter-dr-btn px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       {{ ($activeSpesialisSlug ?? null) == $sp->id ? 'bg-green-600 border-green-600 text-white active-filter' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600' }}"
                style="animation-delay: {{ ($idx + 1) * 0.05 }}s">
                {{ $sp->nama_spesialis }}
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- Info mode filter aktif --}}
@if(isset($modeDaftar) && $modeDaftar && $activeSpesialisNama)
<div class="bg-green-50 border-b border-green-100 info-spesialis-bar">
    <div class="max-w-screen-xl mx-auto px-4 py-2.5 flex items-center gap-2">
        <span class="w-5 h-5 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check text-white" style="font-size:9px"></i>
        </span>
        <p class="text-xs text-green-700 font-semibold">
            Menampilkan dokter spesialis <strong>{{ $activeSpesialisNama }}</strong>
        </p>
        <a href="{{ route('dokter') }}" class="ml-auto text-xs text-green-600 hover:text-green-800 font-bold flex items-center gap-1">
            <i class="fas fa-times text-[10px]"></i> Reset
        </a>
    </div>
</div>
@endif

{{-- ===== MAIN SECTION ===== --}}
<section class="py-12 bg-gray-50" id="section-dokter">
    <div class="max-w-screen-xl mx-auto px-4">

        @if($dokterList->isEmpty())
        {{-- Empty state --}}
        <div class="empty-state-wrap text-center py-24">
            <div class="deco-float inline-block mb-6">
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto">
                    <i class="fas fa-user-md text-green-400 text-4xl"></i>
                </div>
            </div>
            <h3 class="text-xl font-extrabold text-gray-700 mb-2">Belum Ada Dokter</h3>
            <p class="text-gray-400 text-sm mb-6">Tidak ada dokter untuk kategori ini saat ini.</p>
            <a href="{{ route('dokter') }}"
               class="inline-flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-green-700 transition-all hover:-translate-y-0.5">
                <i class="fas fa-arrow-left text-xs"></i> Lihat Semua Dokter
            </a>
        </div>

        @else

        @php
            $spesialisList = $dokterList->where('tipe_dokter', 'spesialis');
            $umumList      = $dokterList->whereIn('tipe_dokter', ['umum', 'lainnya']);
            $cardIndex     = 0;
        @endphp

        {{-- ── DOKTER SPESIALIS ── --}}
        @if($spesialisList->isNotEmpty())
        <div class="mb-14" id="grup-spesialis">
            @if($umumList->isNotEmpty())
            <div class="section-header flex items-center gap-3 mb-8">
                <div class="w-1.5 h-8 bg-green-600 rounded-full"></div>
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 leading-tight">Dokter Spesialis</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Dokter dengan keahlian bidang tertentu</p>
                </div>
                <span class="badge-count-animated ml-auto bg-green-100 text-green-700 text-xs font-extrabold px-3 py-1 rounded-full border border-green-200">
                    {{ $spesialisList->count() }} Dokter
                </span>
            </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($spesialisList as $d)
                @php $cardIndex++ @endphp
                <div id="dokter-{{ $d->id }}" class="dokter-card-wrap" data-spesialis="{{ $d->spesialis_id }}" data-index="{{ $cardIndex }}">
                    @include('_partials.dokter-card', ['d' => $d])
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ── DOKTER UMUM ── --}}
        @if($umumList->isNotEmpty())
        <div id="grup-umum">
            <div class="section-header flex items-center gap-3 mb-8">
                <div class="w-1.5 h-8 bg-blue-500 rounded-full"></div>
                <div>
                    <h2 class="text-xl font-extrabold text-gray-900 leading-tight">Dokter Umum</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Dokter layanan kesehatan umum</p>
                </div>
                <span class="badge-count-animated ml-auto bg-blue-100 text-blue-700 text-xs font-extrabold px-3 py-1 rounded-full border border-blue-200">
                    {{ $umumList->count() }} Dokter
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($umumList as $d)
                @php $cardIndex++ @endphp
                <div id="dokter-{{ $d->id }}" class="dokter-card-wrap" data-spesialis="{{ $d->spesialis_id }}" data-index="{{ $cardIndex }}">
                    @include('_partials.dokter-card', ['d' => $d])
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ─── 1. INTERSECTION OBSERVER: Card masuk viewport → animate ───────
    const cards = document.querySelectorAll('.dokter-card-wrap');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const card  = entry.target;
                const index = parseInt(card.dataset.index || 0);
                const delay = (index % 4) * 80; // stagger per baris (max 4 kolom)
                setTimeout(() => {
                    card.classList.add('visible');
                }, delay);
                observer.unobserve(card);
            }
        });
    }, {
        threshold: 0.08,
        rootMargin: '0px 0px -40px 0px'
    });

    cards.forEach(card => observer.observe(card));

    // ─── 2. SECTION HEADER fade in ──────────────────────────────────────
    const sectionObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.animation = 'fadeInLeft .55s ease both';
                sectionObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.section-header').forEach(el => sectionObs.observe(el));

    // ─── 3. FILTER BUTTON: klik dengan ripple effect ────────────────────
    document.querySelectorAll('.filter-dr-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            // Ripple
            const ripple = document.createElement('span');
            const rect   = btn.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height);
            ripple.style.cssText = `
                position:absolute;border-radius:50%;
                width:${size}px;height:${size}px;
                left:${e.clientX - rect.left - size/2}px;
                top:${e.clientY - rect.top - size/2}px;
                background:rgba(255,255,255,0.4);
                transform:scale(0);pointer-events:none;
                animation:rippleAnim .4s ease-out forwards;
            `;
            btn.style.position = 'relative';
            btn.style.overflow = 'hidden';
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 400);
        });
    });

    // ─── 4. SMOOTH SCROLL ke section saat filter diklik ─────────────────
    // (sudah handle via filterDokter function di bawah)

    // ─── 5. HOVER: foto dokter zoom effect (tambahan native JS) ─────────
    cards.forEach(card => {
        const fotoCircle = card.querySelector('.foto-circle-inner');
        if (!fotoCircle) return;
        card.addEventListener('mouseenter', () => {
            fotoCircle.style.transform = 'scale(1.08)';
            fotoCircle.style.transition = 'transform .35s cubic-bezier(.22,1,.36,1)';
        });
        card.addEventListener('mouseleave', () => {
            fotoCircle.style.transform = 'scale(1)';
        });
    });

    // ─── 6. COUNTER ANIMASI untuk badge jumlah dokter ───────────────────
    const badges = document.querySelectorAll('.badge-count-animated');
    const badgeObs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.animation = 'countUp .5s ease both';
                badgeObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.5 });
    badges.forEach(b => badgeObs.observe(b));

    // ─── 7. PAGE LOAD: animasi judul section header ──────────────────────
    const pageModeBadge = document.querySelector('.page-mode-badge');
    if (pageModeBadge) {
        pageModeBadge.style.opacity = '0';
        setTimeout(() => {
            pageModeBadge.style.opacity = '1';
            pageModeBadge.style.animation = 'slideInRight .5s ease both';
        }, 300);
    }
});

// ─── FILTER FUNCTION: navigate ke URL spesialis ──────────────────────────
function filterDokter(spesialisId, btn) {
    // Visual feedback langsung
    document.querySelectorAll('.filter-dr-btn').forEach(b => {
        b.classList.remove('bg-green-600', 'border-green-600', 'text-white', 'active-filter');
        b.classList.add('border-gray-200', 'text-gray-500');
    });
    btn.classList.remove('border-gray-200', 'text-gray-500');
    btn.classList.add('bg-green-600', 'border-green-600', 'text-white', 'active-filter');

    // Fade out cards
    document.querySelectorAll('.dokter-card-wrap').forEach((card, i) => {
        card.style.transition = `opacity .2s ease ${i * 20}ms, transform .2s ease ${i * 20}ms`;
        card.style.opacity = '0';
        card.style.transform = 'translateY(12px)';
    });

    // Navigate setelah animasi selesai
    setTimeout(() => {
        if (spesialisId === 'semua') {
            window.location = '{{ route("dokter") }}';
        } else {
            window.location = `{{ url("/dokter/") }}/${spesialisId}`;
        }
    }, 220);
}

// Ripple keyframe inject
const style = document.createElement('style');
style.textContent = `
    @keyframes rippleAnim {
        to { transform: scale(2.5); opacity: 0; }
    }
`;
document.head.appendChild(style);
</script>
@endpush
