@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner, 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Pelayanan'],
]])

{{-- ═══════════════ IGD BANNER ═══════════════ --}}
<div class="bg-red-600">
    <div class="max-w-screen-xl mx-auto px-4 py-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="relative w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <span class="absolute inset-0 rounded-xl bg-white/20 animate-ping"></span>
                    <i class="fas fa-ambulance text-white text-xl relative z-10"></i>
                </div>
                <div>
                    <p class="text-white font-extrabold text-base">IGD 24 JAM – Siap Melayani</p>
                    <p class="text-red-100 text-xs">Respons cepat setiap kondisi darurat, 365 hari setahun</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="tel:118" class="flex items-center gap-2 bg-white text-red-700 px-5 py-2 rounded-xl font-bold text-sm hover:bg-red-50 transition-all">
                    <i class="fas fa-phone-alt"></i> 118
                </a>
                <a href="{{ route('portal.booking.create') }}" class="flex items-center gap-2 border-2 border-white text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-white/10 transition-all">
                    <i class="fas fa-calendar-check"></i> Buat Janji
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════ QUICK STATS ═══════════════ --}}
<section class="py-8 bg-white border-b border-gray-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['fa-stethoscope','text-green-600','bg-green-50', $totalLayanan . '+', 'Jenis Layanan'],
                ['fa-user-md',    'text-blue-600', 'bg-blue-50',  '50+',             'Dokter Spesialis'],
                ['fa-clock',      'text-amber-600','bg-amber-50', '24 Jam',          'IGD Siap Melayani'],
                ['fa-shield-alt', 'text-purple-600','bg-purple-50','30+',            'Mitra Asuransi'],
            ] as [$ico,$tc,$bc,$val,$lbl])
            <div class="flex items-center gap-3 p-4 rounded-2xl {{ $bc }}">
                <div class="w-12 h-12 rounded-xl {{ $bc }} flex items-center justify-center flex-shrink-0 border border-white shadow-sm">
                    <i class="fas {{ $ico }} {{ $tc }} text-lg"></i>
                </div>
                <div>
                    <p class="font-extrabold text-gray-900 text-lg leading-none">{{ $val }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $lbl }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════ LAYANAN PER KATEGORI ═══════════════ --}}
@if($kategoriList->isNotEmpty())

{{-- Tab Navigasi Kategori --}}
<section class="bg-white sticky top-0 z-40 border-b border-gray-100 shadow-sm">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex gap-0 overflow-x-auto scrollbar-none" id="kategori-tabs">
            @foreach($kategoriList as $i => $kat)
            <button
                onclick="scrollToKategori('kat-{{ $kat->id }}')"
                class="kategori-tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap
                       {{ $i===0 ? 'border-green-600 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-200' }}"
                data-target="kat-{{ $kat->id }}">
                <i class="fas {{ $kat->icon ?? 'fa-hospital' }} text-xs"></i>
                {{ $kat->nama_kategori }}
                <span class="bg-green-100 text-green-700 text-[10px] font-black px-1.5 py-0.5 rounded-full">{{ $kat->layanansAktif->count() }}</span>
            </button>
            @endforeach
            @if($layananTanpaKategori->isNotEmpty())
            <button
                onclick="scrollToKategori('kat-lainnya')"
                class="kategori-tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-200 transition-all whitespace-nowrap"
                data-target="kat-lainnya">
                <i class="fas fa-ellipsis text-xs"></i> Lainnya
                <span class="bg-gray-100 text-gray-600 text-[10px] font-black px-1.5 py-0.5 rounded-full">{{ $layananTanpaKategori->count() }}</span>
            </button>
            @endif
        </div>
    </div>
</section>

{{-- Section per Kategori --}}
@foreach($kategoriList as $kat)
<section id="kat-{{ $kat->id }}" class="py-14 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }} kategori-section">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Header Kategori --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10">
            <div class="flex items-center gap-4">
                @if($kat->gambar)
                <img src="{{ Storage::url($kat->gambar) }}" alt="{{ $kat->nama_kategori }}" class="w-16 h-16 rounded-2xl object-cover shadow-md flex-shrink-0 border-2 border-white">
                @else
                <div class="w-14 h-14 rounded-2xl bg-green-600 flex items-center justify-center shadow-lg flex-shrink-0">
                    <i class="fas {{ $kat->icon ?? 'fa-hospital' }} text-white text-2xl"></i>
                </div>
                @endif
                <div>
                    <span class="text-green-600 text-xs font-black uppercase tracking-widest block mb-1">Kategori Layanan</span>
                    <h2 class="text-gray-900 font-extrabold text-2xl md:text-3xl leading-tight">{{ $kat->nama_kategori }}</h2>
                    @if($kat->deskripsi)
                    <p class="text-gray-500 text-sm mt-1 max-w-lg">{{ $kat->deskripsi }}</p>
                    @endif
                </div>
            </div>
            <div class="flex-shrink-0">
                <span class="bg-green-50 text-green-700 border border-green-200 text-sm font-bold px-4 py-2 rounded-full">
                    {{ $kat->layanansAktif->count() }} Layanan Tersedia
                </span>
            </div>
        </div>

        {{-- Grid Layanan --}}
        <div class="layanan-grid">
            @foreach($kat->layanansAktif as $l)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden">
                {{-- Accent stripe kategori --}}
                <div class="h-1 bg-green-500"></div>

                @if($l->gambar)
                <div class="relative overflow-hidden">
                    <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}"
                         class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    {{-- Badge kategori on image --}}
                    <span class="absolute bottom-3 left-3 bg-white/90 backdrop-blur text-green-700 text-[10px] font-black px-2.5 py-1 rounded-full border border-green-100">
                        <i class="fas {{ $kat->icon ?? 'fa-hospital' }} mr-1"></i>{{ $kat->nama_kategori }}
                    </span>
                </div>
                @endif

                <div class="p-5">
                    <div class="flex items-start gap-3 mb-3">
                        @if(!$l->gambar)
                        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 transition-colors duration-200">
                            <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-green-600 group-hover:text-white text-base transition-colors duration-200"></i>
                        </div>
                        @else
                        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-green-600 text-base"></i>
                        </div>
                        @endif
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-base leading-snug">{{ $l->nama_layanan }}</h3>
                            <span class="text-xs text-gray-400 font-semibold">RS Sari Sehat</span>
                        </div>
                    </div>

                    @if($l->deskripsi)
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ Str::limit($l->deskripsi, 100) }}</p>
                    @endif

                    <a href="{{ route('portal.booking.create') }}"
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                        <i class="fas fa-calendar-plus mr-1.5"></i> Buat Janji Temu
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endforeach

{{-- Layanan Tanpa Kategori --}}
@if($layananTanpaKategori->isNotEmpty())
<section id="kat-lainnya" class="py-14 {{ $kategoriList->count() % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} kategori-section">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center gap-4 mb-10">
            <div class="w-14 h-14 rounded-2xl bg-gray-500 flex items-center justify-center shadow-lg flex-shrink-0">
                <i class="fas fa-ellipsis text-white text-2xl"></i>
            </div>
            <div>
                <span class="text-gray-400 text-xs font-black uppercase tracking-widest block mb-1">Layanan Lainnya</span>
                <h2 class="text-gray-900 font-extrabold text-2xl md:text-3xl">Layanan Lainnya</h2>
            </div>
        </div>
        <div class="layanan-grid">
            @foreach($layananTanpaKategori as $l)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden">
                <div class="h-1 bg-gray-300"></div>
                @if($l->gambar)
                <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}" class="w-full h-36 object-cover">
                @endif
                <div class="p-5">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-11 h-11 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0 group-hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-gray-600 group-hover:text-white text-base transition-colors duration-200"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-base leading-snug">{{ $l->nama_layanan }}</h3>
                            <span class="text-xs text-gray-400 font-semibold">RS Sari Sehat</span>
                        </div>
                    </div>
                    @if($l->deskripsi)
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ Str::limit($l->deskripsi, 100) }}</p>
                    @endif
                    <a href="{{ route('portal.booking.create') }}"
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                        <i class="fas fa-calendar-plus mr-1.5"></i> Buat Janji Temu
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@else
{{-- ═══════════ FALLBACK: belum ada kategori, tampilkan semua flat ═══════════ --}}
@php $allLayanan = \App\Models\Layanan::aktif()->get(); @endphp
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-green-600 text-xs font-black uppercase tracking-widest block mb-2">Departemen & Spesialisasi</span>
            <h2 class="text-gray-900 font-extrabold text-3xl mb-2">Layanan <span class="text-green-600">Unggulan</span></h2>
            <p class="text-gray-500 text-sm max-w-lg mx-auto">
                Tersedia {{ $totalLayanan }}+ layanan dengan dokter ahli dan peralatan medis terkini.
            </p>
        </div>
        @if($allLayanan->isEmpty())
        <div class="text-center py-16">
            <i class="fas fa-stethoscope text-5xl text-gray-200 block mb-4"></i>
            <p class="text-gray-400 font-semibold">Belum ada layanan yang tersedia.</p>
        </div>
        @else
        <div class="layanan-grid">
            @foreach($allLayanan as $l)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden">
                <div class="h-1 bg-green-500"></div>
                @if($l->gambar)
                <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}" class="w-full h-36 object-cover">
                @endif
                <div class="p-5">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 transition-colors duration-200">
                            <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-green-600 group-hover:text-white text-base transition-colors duration-200"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-base leading-snug">{{ $l->nama_layanan }}</h3>
                            <span class="text-xs text-gray-400 font-semibold">RS Sari Sehat</span>
                        </div>
                    </div>
                    @if($l->deskripsi)
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ Str::limit($l->deskripsi, 100) }}</p>
                    @endif
                    <a href="{{ route('portal.booking.create') }}"
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                        <i class="fas fa-calendar-plus mr-1.5"></i> Buat Janji Temu
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

{{-- ═══════════════ PELAYANAN KHUSUS ═══════════════ --}}
<section class="py-16 bg-green-900" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Layanan Premium</span>
            <h2 class="text-white font-extrabold text-3xl">Pelayanan <span class="text-green-300">Khusus</span></h2>
            <p class="text-green-200 text-sm mt-2 max-w-lg mx-auto">Teknologi medis terkini untuk kondisi yang memerlukan penanganan spesialistik mendalam.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['fa-spa',   '#a78bfa','Pain Clinic & Wellness',   'Terapi nyeri kronis tanpa operasi menggunakan metode Radio Frekuensi, PRP, dan teknik non-invasif terkini.'],
                ['fa-baby',  '#f9a8d4','Pusat Layanan Ibu & Anak', 'Layanan maternal dan pediatri terpadu: NICU modern, ruang bersalin nyaman, dokter anak subspesialis.'],
                ['fa-dna',   '#93c5fd','Onkologi Terpadu',          'Penanganan kanker multidisiplin dengan kemoterapi, radioterapi, dan bedah tumor oleh tim ahli onkologi.'],
            ] as [$ico,$col,$title,$desc])
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 text-center group hover:bg-white/15 transition-all">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform"
                     style="background:{{ $col }}25">
                    <i class="fas {{ $ico }} text-2xl" style="color:{{ $col }}"></i>
                </div>
                <h3 class="text-white font-extrabold text-base mb-2">{{ $title }}</h3>
                <p class="text-green-200 text-sm leading-relaxed mb-5">{{ $desc }}</p>
                <a href="{{ route('portal.booking.create') }}"
                   class="inline-flex items-center gap-2 border border-white/30 text-white hover:bg-white hover:text-green-800 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
                    Konsultasi <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════ CTA BOOKING ═══════════════ --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="bg-green-50 border border-green-100 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-gray-900 font-extrabold text-2xl md:text-3xl mb-2">Siap Mulai Perjalanan Sehat Anda?</h3>
                <p class="text-gray-500 text-sm max-w-lg">Daftarkan diri sekarang dan dapatkan layanan medis terbaik dari dokter spesialis kami. Proses mudah, cepat, dan terpercaya.</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 flex-shrink-0">
                <a href="{{ route('portal.booking.create') }}"
                   class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-7 py-3.5 rounded-xl font-extrabold text-sm transition-all shadow-lg shadow-green-200">
                    <i class="fas fa-calendar-check"></i> Daftar Poliklinik
                </a>
                <a href="{{ route('kontak') }}"
                   class="flex items-center gap-2 border-2 border-green-600 text-green-700 hover:bg-green-50 px-7 py-3.5 rounded-xl font-extrabold text-sm transition-all">
                    <i class="fas fa-phone"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
.scrollbar-none::-webkit-scrollbar { display: none; }
.scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
.kategori-tab-btn.active-tab {
    border-bottom-color: #16a34a !important;
    color: #15803d !important;
}
/* Grid layanan — paksa 3 kolom di desktop */
.layanan-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.25rem;
}
@media (max-width: 1023px) { .layanan-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 639px)  { .layanan-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); } }
</style>
@endpush

@push('scripts')
<script>
function scrollToKategori(id) {
    const el = document.getElementById(id);
    if (!el) return;
    // Offset untuk sticky nav (navbar + tab sticky)
    const offset = 130;
    const top = el.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top, behavior: 'smooth' });
}

// Highlight tab aktif saat scroll
(function () {
    const sections = document.querySelectorAll('.kategori-section');
    const tabs     = document.querySelectorAll('.kategori-tab-btn');
    if (!sections.length || !tabs.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.id;
                tabs.forEach(t => {
                    const isActive = t.dataset.target === id;
                    t.classList.toggle('active-tab', isActive);
                    t.classList.toggle('border-green-600', isActive);
                    t.classList.toggle('text-green-700', isActive);
                    t.classList.toggle('border-transparent', !isActive);
                    t.classList.toggle('text-gray-500', !isActive);
                });
            }
        });
    }, { rootMargin: '-120px 0px -60% 0px', threshold: 0 });

    sections.forEach(s => observer.observe(s));
})();
</script>
@endpush
