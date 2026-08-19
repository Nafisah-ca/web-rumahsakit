@extends('layouts.app')

@push('styles')
<style>
/* ── PROMO CARD ANIMATIONS & HOVER ── */
.promo-card {
    opacity: 0;
    transform: translateY(30px) scale(0.97);
    transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                transform 0.6s cubic-bezier(0.16, 1, 0.3, 1),
                box-shadow 0.35s cubic-bezier(0.16, 1, 0.3, 1),
                border-color 0.35s ease;
    will-change: opacity, transform;
    backface-visibility: hidden;
}

/* State when card is scrolled into view */
.promo-card.is-revealed {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Hover: sedikit membesar (scale 1.025) & naik sedikit (translateY -8px) dengan shadow halus */
.promo-card.is-revealed:hover {
    transform: translateY(-8px) scale(1.025);
    box-shadow: 0 20px 30px -8px rgba(0, 82, 31, 0.15), 0 10px 15px -4px rgba(0, 0, 0, 0.06);
    border-color: rgba(34, 197, 94, 0.45);
}

/* Zoom halus gambar saat card di-hover */
.promo-card .promo-img {
    transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
}
.promo-card:hover .promo-img {
    transform: scale(1.08);
}

/* Animasi geser panah saat hover */
.promo-card .promo-arrow {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.promo-card:hover .promo-arrow {
    transform: translateX(5px);
}

/* Sub-overlay shimmer hover */
.promo-card .promo-shine {
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,0.18) 50%, transparent 60%);
    background-size: 200% 100%;
    background-position-x: 180%;
    transition: background-position-x 0.75s ease;
    pointer-events: none;
}
.promo-card:hover .promo-shine {
    background-position-x: -20%;
}

@media (prefers-reduced-motion: reduce) {
    .promo-card {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
    .promo-card .promo-img {
        transition: none !important;
    }
}
</style>
@endpush

@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner, 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Promo'],
]])

{{-- List Promo --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        @if($promos->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-tag text-5xl opacity-20 block mb-4"></i>
            <p class="font-semibold text-lg">Belum ada promo tersedia</p>
        </div>
        @else

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="promo-grid">
            @foreach($promos as $index => $p)
            <a href="{{ route('promo.detail', $p) }}"
               class="promo-card group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm"
               data-index="{{ $index }}">

                {{-- Gambar — tinggi tetap --}}
                <div class="relative flex-shrink-0 overflow-hidden" style="height:180px; background: linear-gradient(135deg,#00521f,#00b04f)">
                    @if($p->gambar)
                    <img src="{{ Storage::url($p->gambar) }}" alt="{{ $p->judul }}"
                         class="promo-img absolute inset-0 w-full h-full object-cover">
                    @elseif($p->thumbnail)
                    <img src="{{ Storage::url($p->thumbnail) }}" alt="{{ $p->judul }}"
                         class="promo-img absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-tag text-5xl text-white opacity-20"></i>
                    </div>
                    @endif

                    {{-- Efek Shine / Kilap saat hover --}}
                    <div class="promo-shine"></div>

                    {{-- Badge --}}
                    <div class="absolute top-3 left-3 z-10">
                        <span class="bg-green-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full tracking-wide shadow-sm">PROMO</span>
                    </div>

                    {{-- Overlay gradient bawah --}}
                    <div class="absolute bottom-0 left-0 right-0 h-16 pointer-events-none"
                         style="background: linear-gradient(to top, rgba(0,0,0,0.45), transparent)">
                        <div class="absolute bottom-3 left-4 text-white text-xs font-bold flex items-center gap-1.5">
                            Selengkapnya <i class="fas fa-arrow-right text-[10px] promo-arrow"></i>
                        </div>
                    </div>
                </div>

                {{-- Konten — flex-1 agar semua card sama tinggi --}}
                <div class="flex flex-col flex-1 p-5">
                    <h3 class="font-extrabold text-gray-900 text-base leading-snug mb-2 group-hover:text-green-600 transition-colors line-clamp-2">
                        {{ $p->judul }}
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 flex-1">
                        {{ Str::limit(strip_tags($p->deskripsi ?? ''), 120) }}
                    </p>
                    <div class="flex items-center justify-between pt-3 mt-4 border-t border-gray-100">
                        <div class="flex items-center gap-1.5 text-xs text-red-500 font-semibold">
                            <i class="fas fa-clock"></i>
                            Berakhir: {{ $p->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas' }}
                        </div>
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700">
                            Lihat Detail <i class="fas fa-arrow-right text-[10px] promo-arrow"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10 flex justify-center">
            {{ $promos->links() }}
        </div>

        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const promoCards = document.querySelectorAll('.promo-card');
    if (!promoCards.length) return;

    // Gunakan IntersectionObserver untuk animasi saat card muncul saat scroll
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -40px 0px', // trigger sedikit sebelum benar-benar terlihat di bawah
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const card = entry.target;
                    const index = parseInt(card.getAttribute('data-index') || '0', 10);
                    // Staggering delay berurutan dalam grid (0ms, 90ms, 180ms)
                    const staggerDelay = (index % 3) * 90;

                    setTimeout(() => {
                        card.classList.add('is-revealed');
                    }, staggerDelay);

                    obs.unobserve(card);
                }
            });
        }, observerOptions);

        promoCards.forEach(card => observer.observe(card));
    } else {
        // Fallback untuk browser tanpa IntersectionObserver
        promoCards.forEach(card => card.classList.add('is-revealed'));
    }
});
</script>
@endpush
