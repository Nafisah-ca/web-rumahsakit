@extends('layouts.app')
@section('content')

{{-- ===== HERO ===== --}}
<div class="py-16" style="background: linear-gradient(135deg, #4c1d95, #7c3aed);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-purple-300 text-xs font-black uppercase tracking-widest block mb-2">Jadwal Kegiatan</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Event & Kegiatan</h1>
        <p class="text-purple-100 text-sm max-w-xl mx-auto">Ikuti event kesehatan dan edukasi dari RS Sari Sehat</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-purple-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Event</span>
        </nav>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- ===== BANNER EVENT (slider) ===== --}}
        @if($bannerEvents->isNotEmpty())
        <div class="mb-12 relative overflow-hidden rounded-2xl shadow-lg" style="height:340px" id="event-banner">
            @foreach($bannerEvents as $i => $be)
            <div class="event-banner-slide absolute inset-0 transition-opacity duration-700 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}">
                <img src="{{ Storage::url($be->gambar) }}" alt="{{ $be->judul }}"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0" style="background:linear-gradient(to right, rgba(0,0,0,.7) 0%, rgba(0,0,0,.2) 60%, transparent 100%)"></div>
                <div class="absolute inset-0 flex flex-col justify-center px-10 max-w-lg">
                    <span class="text-purple-300 text-xs font-black uppercase tracking-widest mb-2">
                        {{ $be->tanggal_event?->format('d M Y') }}
                        @if($be->lokasi) &nbsp;·&nbsp; {{ $be->lokasi }} @endif
                    </span>
                    <h2 class="text-white font-extrabold text-2xl md:text-3xl leading-tight mb-4">{{ $be->judul }}</h2>
                    <a href="{{ route('event.detail', $be) }}"
                       class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 py-2.5 rounded-xl text-sm transition-colors self-start">
                        <i class="fas fa-ticket"></i> Lihat Detail
                    </a>
                </div>
            </div>
            @endforeach

            {{-- Dots --}}
            @if($bannerEvents->count() > 1)
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2" id="event-banner-dots">
                @foreach($bannerEvents as $i => $be)
                <button class="event-banner-dot w-2 h-2 rounded-full transition-all {{ $i === 0 ? 'bg-white scale-125' : 'bg-white/40' }}"
                        data-idx="{{ $i }}"></button>
                @endforeach
            </div>
            {{-- Arrows --}}
            <button id="banner-prev" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            <button id="banner-next" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 bg-black/30 hover:bg-black/50 text-white rounded-full flex items-center justify-center transition-colors">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>
            @endif
        </div>
        @endif

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
                @include('_partials.event-card', ['ev' => $ev, 'lewat' => false])
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">{{ $eventsMendatang->appends(request()->except('mendatang'))->links() }}</div>
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
                @include('_partials.event-card', ['ev' => $ev, 'lewat' => true])
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">{{ $eventsLewat->appends(request()->except('lewat'))->links() }}</div>
        </div>
        @endif

    </div>
</section>

@push('scripts')
<script>
(function () {
    const slides = document.querySelectorAll('.event-banner-slide');
    const dots   = document.querySelectorAll('.event-banner-dot');
    if (!slides.length) return;

    let current = 0, timer;

    function goTo(n) {
        slides[current].classList.remove('opacity-100');
        slides[current].classList.add('opacity-0');
        dots[current] && dots[current].classList.replace('bg-white', 'bg-white/40');
        dots[current] && dots[current].classList.remove('scale-125');

        current = (n + slides.length) % slides.length;

        slides[current].classList.remove('opacity-0');
        slides[current].classList.add('opacity-100');
        dots[current] && dots[current].classList.replace('bg-white/40', 'bg-white');
        dots[current] && dots[current].classList.add('scale-125');
    }

    function startAuto() { timer = setInterval(() => goTo(current + 1), 5000); }
    function stopAuto()  { clearInterval(timer); }

    if (slides.length > 1) {
        startAuto();
        document.getElementById('banner-prev')?.addEventListener('click', () => { stopAuto(); goTo(current - 1); startAuto(); });
        document.getElementById('banner-next')?.addEventListener('click', () => { stopAuto(); goTo(current + 1); startAuto(); });
        dots.forEach((d, i) => d.addEventListener('click', () => { stopAuto(); goTo(i); startAuto(); }));
    }
})();
</script>
@endpush
@endsection
