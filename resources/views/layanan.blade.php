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
                <a href="{{ route('portal.booking.create') }}"
                   class="rs-btn-janji flex items-center gap-2 border-2 border-white text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-white/10 transition-all">
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

            {{-- Card: Jenis Layanan (clickable, toggles service list) --}}
            <button type="button" id="btn-toggle-layanan"
                aria-expanded="false"
                aria-controls="panel-layanan"
                class="stat-toggle-card flex items-center gap-3 p-4 rounded-2xl bg-green-50 text-left w-full group transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0 border border-white shadow-sm transition-colors duration-200 group-hover:bg-green-600">
                    <i class="fas fa-stethoscope text-green-600 text-lg transition-colors duration-200 group-hover:text-white"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-gray-900 text-lg leading-none">{{ $totalLayanan }}+</p>
                    <p class="text-gray-500 text-xs mt-1">Jenis Layanan</p>
                </div>
                <i id="icon-toggle-layanan"
                   class="fas fa-chevron-down text-green-500 text-xs flex-shrink-0 transition-transform duration-300"></i>
            </button>

            {{-- Card: Dokter Spesialis (clickable, toggles doctor list) --}}
            <button type="button" id="btn-toggle-dokter"
                aria-expanded="false"
                aria-controls="panel-dokter"
                class="stat-toggle-card flex items-center gap-3 p-4 rounded-2xl bg-blue-50 text-left w-full group transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-white shadow-sm transition-colors duration-200 group-hover:bg-blue-600">
                    <i class="fas fa-user-md text-blue-600 text-lg transition-colors duration-200 group-hover:text-white"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-gray-900 text-lg leading-none">{{ $dokterSpesialis->count() > 0 ? $dokterSpesialis->count().'+' : '50+' }}</p>
                    <p class="text-gray-500 text-xs mt-1">Dokter Spesialis</p>
                </div>
                <i id="icon-toggle-dokter"
                   class="fas fa-chevron-down text-blue-500 text-xs flex-shrink-0 transition-transform duration-300"></i>
            </button>

            {{-- Card: IGD --}}
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-amber-50">
                <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0 border border-white shadow-sm">
                    <i class="fas fa-clock text-amber-600 text-lg"></i>
                </div>
                <div>
                    <p class="font-extrabold text-gray-900 text-lg leading-none">24 Jam</p>
                    <p class="text-gray-500 text-xs mt-1">IGD Siap Melayani</p>
                </div>
            </div>

            {{-- Card: Mitra Asuransi --}}
            <div class="flex items-center gap-3 p-4 rounded-2xl bg-purple-50">
                <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center flex-shrink-0 border border-white shadow-sm">
                    <i class="fas fa-shield-alt text-purple-600 text-lg"></i>
                </div>
                <div>
                    <p class="font-extrabold text-gray-900 text-lg leading-none">30+</p>
                    <p class="text-gray-500 text-xs mt-1">Mitra Asuransi</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ PANEL DOKTER SPESIALIS (tersembunyi secara default) ═══════════════ --}}
<div id="panel-dokter" class="expandable-panel" aria-hidden="true"
     style="overflow:hidden; max-height:0; opacity:0; transition: max-height 0.55s cubic-bezier(0.4,0,0.2,1), opacity 0.4s ease, padding 0.4s ease;">
    <section class="bg-blue-50 border-b border-blue-100">
        <div class="max-w-screen-xl mx-auto px-4 py-10">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="text-blue-600 text-xs font-black uppercase tracking-widest block mb-1">Tim Medis Kami</span>
                    <h2 class="text-gray-900 font-extrabold text-2xl md:text-3xl">Dokter <span class="text-blue-600">Spesialis</span></h2>
                    <p class="text-gray-500 text-sm mt-1">Didukung dokter spesialis berpengalaman dengan dedikasi tinggi.</p>
                </div>
                <a href="{{ route('dokter') }}" class="hidden md:inline-flex items-center gap-2 border-2 border-blue-600 text-blue-700 hover:bg-blue-600 hover:text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all">
                    Lihat Semua <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

            @if($dokterSpesialis->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 dokter-panel-grid">
                @foreach($dokterSpesialis as $d)
                @include('_partials.dokter-card', ['d' => $d])
                @endforeach
            </div>
            <div class="mt-6 text-center md:hidden">
                <a href="{{ route('dokter') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-700 transition-all">
                    Lihat Semua Dokter <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            @else
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-user-md text-5xl opacity-20 block mb-4"></i>
                <p class="font-semibold">Data dokter spesialis belum tersedia.</p>
                <a href="{{ route('dokter') }}" class="mt-4 inline-flex items-center gap-2 text-blue-600 font-bold text-sm hover:underline">
                    Lihat Halaman Dokter <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            @endif
        </div>
    </section>
</div>

{{-- ═══════════════ PANEL LAYANAN (tersembunyi secara default) ═══════════════ --}}
<div id="panel-layanan" class="expandable-panel" aria-hidden="true"
     style="overflow:hidden; max-height:0; opacity:0; transition: max-height 0.55s cubic-bezier(0.4,0,0.2,1), opacity 0.4s ease, padding 0.4s ease;">

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
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 kategori-header">
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
                <div class="layanan-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden flex flex-col">
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

                    <div class="p-5 flex flex-col flex-1">
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
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-1">{{ Str::limit($l->deskripsi, 100) }}</p>
                        @else
                        <div class="flex-1"></div>
                        @endif

                        <a href="{{ route('portal.booking.create') }}"
                           class="rs-btn-janji block w-full text-center font-bold py-2.5 rounded-xl text-sm">
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
            <div class="flex items-center gap-4 mb-10 kategori-header">
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
                <div class="layanan-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden flex flex-col">
                    <div class="h-1 bg-gray-300"></div>
                    @if($l->gambar)
                    <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}" class="w-full h-36 object-cover">
                    @endif
                    <div class="p-5 flex flex-col flex-1">
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
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-1">{{ Str::limit($l->deskripsi, 100) }}</p>
                        @else
                        <div class="flex-1"></div>
                        @endif
                        <a href="{{ route('portal.booking.create') }}"
                           class="rs-btn-janji block w-full text-center font-bold py-2.5 rounded-xl text-sm">
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
                <div class="layanan-card bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden flex flex-col">
                    <div class="h-1 bg-green-500"></div>
                    @if($l->gambar)
                    <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}" class="w-full h-36 object-cover">
                    @endif
                    <div class="p-5 flex flex-col flex-1">
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
                        <p class="text-gray-500 text-sm leading-relaxed mb-4 flex-1">{{ Str::limit($l->deskripsi, 100) }}</p>
                        @else
                        <div class="flex-1"></div>
                        @endif
                        <a href="{{ route('portal.booking.create') }}"
                           class="rs-btn-janji block w-full text-center font-bold py-2.5 rounded-xl text-sm">
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

</div>{{-- /panel-layanan --}}

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
                   class="rs-btn-janji-outline inline-flex items-center gap-2 border border-white/30 text-white hover:bg-white hover:text-green-800 px-5 py-2.5 rounded-xl text-sm font-bold transition-all">
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
                   class="rs-btn-janji flex items-center gap-2 px-7 py-3.5 rounded-xl font-extrabold text-sm">
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
/* ── Scrollbar ─────────────────────────────────────────────── */
.scrollbar-none::-webkit-scrollbar { display: none; }
.scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }

/* ── Active tab ────────────────────────────────────────────── */
.kategori-tab-btn.active-tab {
    border-bottom-color: #16a34a !important;
    color: #15803d !important;
}

/* ── Layanan grid ──────────────────────────────────────────── */
.layanan-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.25rem;
    align-items: stretch;
}
@media (max-width: 1023px) { .layanan-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 639px)  { .layanan-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); } }

/* ── Consistent "Buat Janji Temu" button ───────────────────── */
.rs-btn-janji {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background-color: #16a34a;
    color: #ffffff;
    font-size: 0.8125rem;       /* 13px */
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 12px;
    border: none;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.18s ease, transform 0.15s ease, box-shadow 0.18s ease;
    box-shadow: 0 2px 8px rgba(22,163,74,0.18);
    white-space: nowrap;
}
.rs-btn-janji:hover {
    background-color: #15803d;
    box-shadow: 0 6px 18px rgba(22,163,74,0.30);
    transform: translateY(-2px);
    color: #ffffff;
    text-decoration: none;
}
.rs-btn-janji:active {
    transform: scale(0.96) translateY(0);
    box-shadow: 0 2px 6px rgba(22,163,74,0.18);
}
/* Outline variant for dark backgrounds */
.rs-btn-janji-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background-color: transparent;
    color: #ffffff;
    font-size: 0.8125rem;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 12px;
    border: 1.5px solid rgba(255,255,255,0.4);
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.18s ease, color 0.18s ease, transform 0.15s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}
.rs-btn-janji-outline:hover {
    background-color: #ffffff;
    color: #166534;
    border-color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    text-decoration: none;
}
.rs-btn-janji-outline:active {
    transform: scale(0.96) translateY(0);
}

/* ── Stat toggle card active state ────────────────────────── */
.stat-toggle-card.is-open {
    background-color: #dcfce7;
    box-shadow: 0 0 0 2px #16a34a33;
}
.stat-toggle-card.is-open .fa-chevron-down {
    transform: rotate(180deg);
}
/* Blue variant */
button#btn-toggle-dokter.is-open {
    background-color: #dbeafe;
    box-shadow: 0 0 0 2px #2563eb33;
}

/* ── Layanan card stagger animation ───────────────────────── */
.layanan-card {
    opacity: 0;
    transform: translateY(16px);
    transition: opacity 0.4s ease, transform 0.4s ease, box-shadow 0.2s ease;
}
.layanan-card.card-visible {
    opacity: 1;
    transform: translateY(0);
}

/* ── Kategori header appear ───────────────────────────────── */
.kategori-header {
    opacity: 0;
    transform: translateX(-10px);
    transition: opacity 0.4s ease, transform 0.4s ease;
}
.kategori-header.header-visible {
    opacity: 1;
    transform: translateX(0);
}

/* ── Dokter panel card stagger ────────────────────────────── */
.dokter-panel-grid > * {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.4s ease, transform 0.4s ease;
}
.dokter-panel-grid > *.card-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>
@endpush

@push('scripts')
<script>
/* ════════════════════════════════════════════════════════════
   HELPER: expand / collapse a panel with smooth animation
   ════════════════════════════════════════════════════════════ */
function expandPanel(panel) {
    panel.style.maxHeight = panel.scrollHeight + 'px';
    panel.style.opacity   = '1';
    panel.setAttribute('aria-hidden', 'false');
}
function collapsePanel(panel) {
    panel.style.maxHeight = '0';
    panel.style.opacity   = '0';
    panel.setAttribute('aria-hidden', 'true');
}

/* ════════════════════════════════════════════════════════════
   TOGGLE — Jenis Layanan card
   ════════════════════════════════════════════════════════════ */
(function () {
    var btn   = document.getElementById('btn-toggle-layanan');
    var panel = document.getElementById('panel-layanan');
    var icon  = document.getElementById('icon-toggle-layanan');
    if (!btn || !panel) return;

    btn.addEventListener('click', function () {
        var isOpen = btn.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
            // Collapse
            btn.setAttribute('aria-expanded', 'false');
            btn.classList.remove('is-open');
            icon.style.transform = 'rotate(0deg)';
            collapsePanel(panel);
        } else {
            // Expand
            btn.setAttribute('aria-expanded', 'true');
            btn.classList.add('is-open');
            icon.style.transform = 'rotate(180deg)';
            expandPanel(panel);

            // Stagger animate cards after panel opens
            setTimeout(function () {
                animateLayananCards();
                animateKategoriHeaders();
            }, 120);

            // Scroll so the panel is in view (with a small offset)
            setTimeout(function () {
                var top = panel.getBoundingClientRect().top + window.pageYOffset - 80;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }, 60);
        }
    });

    /* Re-calculate max-height on window resize when panel is open */
    window.addEventListener('resize', function () {
        if (btn.getAttribute('aria-expanded') === 'true') {
            panel.style.maxHeight = panel.scrollHeight + 'px';
        }
    }, { passive: true });
})();

/* ════════════════════════════════════════════════════════════
   TOGGLE — Dokter Spesialis card
   ════════════════════════════════════════════════════════════ */
(function () {
    var btn   = document.getElementById('btn-toggle-dokter');
    var panel = document.getElementById('panel-dokter');
    var icon  = document.getElementById('icon-toggle-dokter');
    if (!btn || !panel) return;

    btn.addEventListener('click', function () {
        var isOpen = btn.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
            btn.setAttribute('aria-expanded', 'false');
            btn.classList.remove('is-open');
            icon.style.transform = 'rotate(0deg)';
            collapsePanel(panel);
        } else {
            btn.setAttribute('aria-expanded', 'true');
            btn.classList.add('is-open');
            icon.style.transform = 'rotate(180deg)';
            expandPanel(panel);

            // Stagger animate doctor cards
            setTimeout(function () {
                var cards = panel.querySelectorAll('.dokter-panel-grid > *');
                cards.forEach(function (card, i) {
                    setTimeout(function () {
                        card.classList.add('card-visible');
                    }, i * 60);
                });
            }, 120);

            // Scroll to panel
            setTimeout(function () {
                var top = panel.getBoundingClientRect().top + window.pageYOffset - 80;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }, 60);
        }
    });

    window.addEventListener('resize', function () {
        if (btn.getAttribute('aria-expanded') === 'true') {
            panel.style.maxHeight = panel.scrollHeight + 'px';
        }
    }, { passive: true });
})();

/* ════════════════════════════════════════════════════════════
   STAGGER LAYANAN CARDS (called after panel opens)
   ════════════════════════════════════════════════════════════ */
function animateLayananCards() {
    var cards = document.querySelectorAll('#panel-layanan .layanan-card');
    cards.forEach(function (card, i) {
        setTimeout(function () {
            card.classList.add('card-visible');
        }, i * 45);
    });
}

function animateKategoriHeaders() {
    var headers = document.querySelectorAll('#panel-layanan .kategori-header');
    headers.forEach(function (h, i) {
        setTimeout(function () {
            h.classList.add('header-visible');
        }, i * 80);
    });
}

/* ════════════════════════════════════════════════════════════
   KATEGORI TAB — smooth scroll + stagger on section enter
   ════════════════════════════════════════════════════════════ */
function scrollToKategori(id) {
    var el = document.getElementById(id);
    if (!el) return;
    var offset = 130;
    var top = el.getBoundingClientRect().top + window.pageYOffset - offset;
    window.scrollTo({ top: top, behavior: 'smooth' });

    // Animate cards in the target section when arriving
    setTimeout(function () {
        var cards = el.querySelectorAll('.layanan-card:not(.card-visible)');
        cards.forEach(function (card, i) {
            setTimeout(function () {
                card.classList.add('card-visible');
            }, i * 55);
        });
    }, 350);
}

/* ════════════════════════════════════════════════════════════
   HIGHLIGHT ACTIVE TAB on scroll
   ════════════════════════════════════════════════════════════ */
(function () {
    var sections = document.querySelectorAll('.kategori-section');
    var tabs     = document.querySelectorAll('.kategori-tab-btn');
    if (!sections.length || !tabs.length) return;

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                var id = entry.target.id;
                tabs.forEach(function (t) {
                    var isActive = t.dataset.target === id;
                    t.classList.toggle('active-tab', isActive);
                    t.classList.toggle('border-green-600', isActive);
                    t.classList.toggle('text-green-700', isActive);
                    t.classList.toggle('border-transparent', !isActive);
                    t.classList.toggle('text-gray-500', !isActive);
                });
            }
        });
    }, { rootMargin: '-120px 0px -60% 0px', threshold: 0 });

    sections.forEach(function (s) { observer.observe(s); });
})();

/* ════════════════════════════════════════════════════════════
   "BUAT JANJI TEMU" — lightweight click ripple animation
   ════════════════════════════════════════════════════════════ */
document.addEventListener('click', function (e) {
    var btn = e.target.closest('.rs-btn-janji, .rs-btn-janji-outline');
    if (!btn) return;

    // Ripple effect
    var ripple = document.createElement('span');
    var rect   = btn.getBoundingClientRect();
    var size   = Math.max(rect.width, rect.height);
    ripple.style.cssText = [
        'position:absolute',
        'border-radius:50%',
        'pointer-events:none',
        'background:rgba(255,255,255,0.35)',
        'width:'  + size + 'px',
        'height:' + size + 'px',
        'left:'   + (e.clientX - rect.left - size/2) + 'px',
        'top:'    + (e.clientY - rect.top  - size/2) + 'px',
        'transform:scale(0)',
        'animation:btn-ripple 0.5s ease-out forwards',
    ].join(';');

    // Make sure btn has position relative for absolute child
    var prev = window.getComputedStyle(btn).position;
    if (prev === 'static') btn.style.position = 'relative';
    btn.style.overflow = 'hidden';

    btn.appendChild(ripple);
    ripple.addEventListener('animationend', function () { ripple.remove(); });
}, { passive: true });
</script>

<style>
@keyframes btn-ripple {
    to { transform: scale(2.8); opacity: 0; }
}
</style>
@endpush
