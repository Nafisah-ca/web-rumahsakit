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

/* State when card is scrolled into view */
.event-card.is-revealed {
    opacity: 1;
    transform: translateY(0) scale(1);
}

/* Hover: sedikit membesar (scale 1.025) & naik sedikit (translateY -8px) dengan shadow halus */
.event-card.is-revealed:hover {
    transform: translateY(-8px) scale(1.025);
    box-shadow: 0 20px 30px -8px rgba(124, 58, 237, 0.15), 0 10px 15px -4px rgba(0, 0, 0, 0.06);
    border-color: rgba(168, 85, 247, 0.45);
}

/* Zoom halus gambar saat card di-hover */
.event-card .event-img {
    transition: transform 0.65s cubic-bezier(0.16, 1, 0.3, 1);
}
.event-card:hover .event-img {
    transform: scale(1.08);
}

/* Animasi geser panah saat hover */
.event-card .event-arrow {
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.event-card:hover .event-arrow {
    transform: translateX(5px);
}

/* Sub-overlay shimmer hover */
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
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-purple-600 rounded-full"></div>
                <h2 class="text-xl font-extrabold text-gray-900">Event Mendatang</h2>
                @if($eventsMendatang->total() > 0)
                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $eventsMendatang->total() }} event
                </span>
                @endif
            </div>

            @if($eventsMendatang->isEmpty())
            <div class="text-center py-16 text-gray-400 bg-white rounded-2xl border border-gray-100">
                <i class="fas fa-calendar-days text-5xl opacity-20 block mb-4"></i>
                <p class="font-semibold">Belum ada event mendatang</p>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventsMendatang as $ev)
                @include('_partials.event-card', ['ev' => $ev, 'lewat' => false, 'index' => $loop->index])
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">{{ $eventsMendatang->appends(request()->except('page_m'))->links() }}</div>
            @endif
        </div>

        {{-- ===== EVENT SUDAH LEWAT ===== --}}
        @if($eventsLewat->isNotEmpty())
        <div class="mt-12 pt-10 border-t border-gray-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-gray-400 rounded-full"></div>
                <h2 class="text-xl font-extrabold text-gray-500">Event Sebelumnya</h2>
                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $eventsLewat->total() }} event
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventsLewat as $ev)
                @include('_partials.event-card', ['ev' => $ev, 'lewat' => true, 'index' => $loop->index])
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">{{ $eventsLewat->appends(request()->except('page_l'))->links() }}</div>
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const eventCards = document.querySelectorAll('.event-card');
    if (!eventCards.length) return;

    // Gunakan IntersectionObserver untuk animasi saat card muncul saat scroll
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -40px 0px',
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

        eventCards.forEach(card => observer.observe(card));
    } else {
        // Fallback untuk browser tanpa IntersectionObserver
        eventCards.forEach(card => card.classList.add('is-revealed'));
    }
});
</script>
@endpush
