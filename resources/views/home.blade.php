@extends('layouts.app')
@section('content')

{{-- ===== HERO SLIDER ===== --}}
<div class="hero-slider">

    @php
    $gradients = [
        'linear-gradient(90deg, rgba(0,82,31,0.92) 0%, rgba(0,176,79,0.82) 100%)',
        'linear-gradient(90deg, rgba(124,45,18,0.92) 0%, rgba(194,65,12,0.82) 100%)',
        'linear-gradient(90deg, rgba(30,58,95,0.92) 0%, rgba(15,76,129,0.82) 100%)',
        'linear-gradient(90deg, rgba(76,29,149,0.92) 0%, rgba(124,58,237,0.82) 100%)',
        'linear-gradient(90deg, rgba(5,95,70,0.92) 0%, rgba(16,185,129,0.82) 100%)',
    ];
    @endphp

    @forelse($banners as $i => $banner)
    <div class="slide" @if($banner->gambar) style="background-image: url('{{ Storage::url($banner->gambar) }}'); background-size: cover; background-position: center;" @endif>
        <div style="{{ $gradients[$i % count($gradients)] }}; height:100%;"></div>
        <div class="slide-content">
            <div class="max-w-screen-xl mx-auto px-6 w-full">
                <div class="max-w-xl">
                    <span class="inline-block bg-white/20 border border-white/30 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-widest backdrop-blur-sm">
                        {{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}
                    </span>
                    <h1 class="text-white font-black text-4xl md:text-5xl leading-tight mb-4">
                        {!! nl2br(e($banner->judul)) !!}
                    </h1>
                    @if($banner->deskripsi)
                    <p class="text-green-100 text-base mb-7 leading-relaxed max-w-md">
                        {{ $banner->deskripsi }}
                    </p>
                    @endif
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dokter') }}" class="flex items-center gap-2 bg-white text-green-700 px-6 py-3 rounded-xl font-bold text-sm shadow-xl hover:bg-green-50 transition-all hover:-translate-y-0.5">
                            <i class="fas fa-calendar-check"></i> Daftar Poliklinik
                        </a>
                        <a href="{{ route('layanan') }}" class="flex items-center gap-2 border-2 border-white/60 hover:border-white text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all hover:-translate-y-0.5 backdrop-blur-sm">
                            <i class="fas fa-info-circle"></i> Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    {{-- Fallback jika belum ada banner di database --}}
    <div class="slide">
        <div style="background: linear-gradient(90deg, rgba(0,82,31,0.95) 0%, rgba(0,176,79,0.85) 100%); height:100%;"></div>
        <div class="slide-content">
            <div class="max-w-screen-xl mx-auto px-6 w-full">
                <div class="max-w-xl">
                    <span class="inline-block bg-white/20 border border-white/30 text-white text-xs font-bold px-3 py-1 rounded-full mb-4 uppercase tracking-widest backdrop-blur-sm">Selamat Datang</span>
                    <h1 class="text-white font-black text-4xl md:text-5xl leading-tight mb-4">
                        Melayani dengan<br><span class="text-yellow-300">Kasih Sayang</span>
                    </h1>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dokter') }}" class="flex items-center gap-2 bg-white text-green-700 px-6 py-3 rounded-xl font-bold text-sm shadow-xl hover:bg-green-50 transition-all">
                            <i class="fas fa-calendar-check"></i> Daftar Poliklinik
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforelse

    {{-- Arrows --}}
    <button class="slide-arrow prev" aria-label="Previous"><i class="fas fa-chevron-left text-sm"></i></button>
    <button class="slide-arrow next" aria-label="Next"><i class="fas fa-chevron-right text-sm"></i></button>
    {{-- Dots --}}
    <div class="slide-dots">
        @foreach($banners as $b)<span class="dot"></span>@endforeach
        @if($banners->isEmpty())<span class="dot"></span>@endif
    </div>
</div>

{{-- ===== QUICK MENU ===== --}}
<div class="quick-menu">
    <div class="quick-menu-grid">
        <a href="{{ route('dokter') }}" class="quick-menu-item">
            <i class="fas fa-calendar-check"></i>
            <span>Daftar Poliklinik</span>
        </a>
        <a href="{{ route('dokter.online') }}" class="quick-menu-item">
            <i class="fas fa-laptop-medical"></i>
            <span>Layanan Online</span>
        </a>
        <a href="{{ route('promo') }}" class="quick-menu-item">
            <i class="fas fa-tags"></i>
            <span>Paket Promo</span>
        </a>
        <a href="{{ route('mcu') }}" class="quick-menu-item">
            <i class="fas fa-clipboard-check"></i>
            <span>Medical Checkup</span>
        </a>
        <a href="{{ route('layanan') }}" class="quick-menu-item">
            <i class="fas fa-spa"></i>
            <span>Pelayanan Khusus</span>
        </a>
        <a href="{{ route('home') }}" class="quick-menu-item">
            <i class="fas fa-ellipsis-h"></i>
            <span>Lainnya</span>
        </a>
    </div>
</div>

{{-- ===== SPESIALISASI ===== --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Pelayanan Spesialis</span>
                <h2 class="section-title">Spesialisasi <span>Kami</span></h2>
                <p class="text-gray-500 text-sm mt-2 max-w-xl">Rumah Sakit Sari Sehat senantiasa berupaya memberikan mutu pelayanan berkualitas dengan didukung para dokter ahli dan fasilitas modern.</p>
            </div>
            <a href="{{ route('dokter') }}" class="btn-outline-green hidden md:inline-flex flex-shrink-0 ml-8">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="flex flex-wrap gap-3 fade-up">
            @forelse($spesialisasi as $sp)
            <a href="{{ route('dokter.by-spesialis', ['spSlug' => $sp->id]) }}" class="spesialis-pill">
                <i class="fas {{ $sp->icon ?? 'fa-stethoscope' }}"></i> {{ $sp->nama_spesialis }}
            </a>
            @empty
            <div class="text-gray-500 text-sm">Belum ada data spesialisasi</div>
            @endforelse
        </div>
        <div class="mt-6 md:hidden fade-up">
            <a href="{{ route('dokter') }}" class="btn-outline-green w-full justify-center">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

{{-- ===== TENTANG / STATS ===== --}}
<section class="py-0 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="rounded-2xl overflow-hidden shadow-xl fade-up" style="background: linear-gradient(135deg, #00521f 0%, #00b04f 100%);">
            <div class="grid grid-cols-1 lg:grid-cols-2">
                {{-- Teks --}}
                <div class="p-8 md:p-12">
                    <span class="section-label text-green-300">Sekilas Tentang</span>
                    <h2 class="text-white font-extrabold text-2xl md:text-3xl leading-tight mb-4">
                        {{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }}
                    </h2>
                    <p class="text-green-100 leading-relaxed text-sm mb-6">
                        @if($setting->tentang_kami ?? null)
                            {{ $setting->tentang_kami }}
                        @else
                            Rumah Sakit Sari Sehat berada di Depok dengan motto <strong class="text-white">"{{ $setting->motto ?? 'Melayani dengan Kasih Sayang' }}"</strong>, dan senantiasa memberikan mutu pelayanan yang profesional kepada seluruh pasien kami.
                        @endif
                    </p>
                    <a href="{{ route('tentang') }}" class="inline-flex items-center gap-2 bg-white text-green-700 px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-green-50 transition-all shadow-md">
                        Tentang Kami <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
                {{-- Stats --}}
                <div id="stats-section" class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6 border-t lg:border-t-0 lg:border-l border-white/20 py-4 sm:py-0">
                    @foreach([
                        ['5','Spesialisasi','fa-stethoscope'],
                        ['50','Mitra Asuransi','fa-handshake'],
                    ] as [$val,$lbl,$ico])
                    <div class="stat-box w-full sm:w-1/2 flex flex-col items-center justify-center">
                        <i class="fas {{ $ico }} text-green-300 text-xl mb-2 block"></i>
                        <div class="stat-number">{{ $val }}</div>
                        <div class="stat-label">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROMO ===== --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Penawaran Terbaik</span>
                <h2 class="section-title">Promo</h2>
            </div>
            <a href="{{ route('promo') }}" class="btn-outline-green hidden md:inline-flex">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 fade-up">
            @forelse($promos as $promo)
            <a href="{{ route('promo.detail', $promo) }}" class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="relative flex-shrink-0" style="height:160px; background: linear-gradient(135deg, #00521f, #00b04f)">
                    @if($promo->gambar)
                    <img src="{{ Storage::url($promo->gambar) }}" alt="{{ $promo->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-tag text-4xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-green-600 text-white text-[10px] font-black px-2 py-1 rounded-full">PROMO</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-10" style="background:linear-gradient(to top,rgba(0,0,0,.3),transparent)">
                        <span class="absolute bottom-2 left-3 text-white text-[10px] font-bold">Selengkapnya</span>
                    </div>
                </div>
                <div class="flex flex-col flex-1 p-4">
                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1 group-hover:text-green-600 transition-colors line-clamp-2">
                        {{ $promo->judul }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 flex-1">
                        {{ Str::limit(strip_tags($promo->deskripsi ?? ''), 80) }}
                    </p>
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center gap-1 text-xs text-red-500 font-semibold">
                            <i class="fas fa-clock"></i>
                            Berakhir: {{ $promo->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas' }}
                        </div>
                        <span class="text-xs font-bold text-green-700">Detail →</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-10 text-gray-400">
                <i class="fas fa-tag text-4xl opacity-20 block mb-3"></i>
                <p>Belum ada promo tersedia</p>
            </div>
            @endforelse
        </div>
        <div class="mt-5 md:hidden text-center fade-up">
            <a href="{{ route('promo') }}" class="btn-outline-green">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

{{-- ===== EVENT ===== --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Jadwal Kegiatan</span>
                <h2 class="section-title">Event</h2>
            </div>
            <a href="{{ route('event') }}" class="btn-outline-green hidden md:inline-flex">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 fade-up">
            @forelse($events as $ev)
            <a href="{{ route('event.detail', $ev) }}" class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="relative flex-shrink-0" style="height:160px; background: linear-gradient(135deg,#4c1d95,#7c3aed)">
                    @if($ev->gambar)
                    <img src="{{ Storage::url($ev->gambar) }}" alt="{{ $ev->judul }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-calendar-star text-4xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-purple-600 text-white text-[10px] font-black px-2 py-1 rounded-full">EVENT</span>
                    </div>
                    <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs font-bold px-2.5 py-1 rounded-lg">
                        {{ $ev->tanggal_event?->format('d M Y') }}
                    </div>
                </div>
                <div class="flex flex-col flex-1 p-4">
                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1 group-hover:text-purple-600 transition-colors line-clamp-2">
                        {{ $ev->judul }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 flex-1">
                        {{ Str::limit(strip_tags($ev->deskripsi ?? ''), 80) }}
                    </p>
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-100">
                        @if($ev->lokasi)
                        <span class="text-xs text-gray-400 truncate max-w-[60%]"><i class="fas fa-location-dot mr-1 text-purple-400"></i>{{ $ev->lokasi }}</span>
                        @else
                        <span class="text-xs text-gray-400">{{ $ev->tanggal_event?->format('d M Y') }}</span>
                        @endif
                        <span class="text-xs font-bold text-purple-700 flex-shrink-0">Detail →</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-10 text-gray-400">
                <i class="fas fa-calendar-days text-4xl opacity-20 block mb-3"></i>
                <p class="text-sm">Belum ada event mendatang</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ===== INFORMASI / ARTIKEL ===== --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8 fade-up">
            <div>
                <span class="section-label">Kesehatan Terkini</span>
                <h2 class="section-title">Informasi <span>Terkini</span></h2>
            </div>
            <a href="{{ route('informasi') }}" class="btn-outline-green hidden md:inline-flex">
                Lihat Semua <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 fade-up">
            @forelse($informasis as $info)
            <a href="{{ route('informasi.detail', $info) }}" class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
                <div class="relative flex-shrink-0" style="height:160px; background:linear-gradient(135deg,#1e3a5f,#0284c7)">
                    @if($info->gambar)
                    <img src="{{ Storage::url($info->gambar) }}" alt="{{ $info->judul }}" class="absolute inset-0 w-full h-full object-cover">
                    @elseif($info->thumbnail)
                    <img src="{{ Storage::url($info->thumbnail) }}" alt="{{ $info->judul }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-newspaper text-4xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-blue-600 text-white text-[10px] font-black px-2 py-0.5 rounded-full">INFO</span>
                    </div>
                </div>
                <div class="flex flex-col flex-1 p-4">
                    <span class="text-gray-400 text-[10px] mb-1.5">{{ $info->created_tm?->format('d M Y') }}</span>
                    <h3 class="font-bold text-gray-900 text-sm leading-snug mb-1 group-hover:text-green-600 transition-colors line-clamp-2 flex-1">
                        {{ $info->judul }}
                    </h3>
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">
                        {{ Str::limit(strip_tags($info->isi ?? ''), 80) }}
                    </p>
                    <div class="mt-2 text-xs font-bold text-green-700">Baca Selengkapnya →</div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-10 text-gray-400">
                <i class="fas fa-newspaper text-4xl opacity-20 block mb-3"></i>
                <p class="text-sm">Belum ada artikel terkini</p>
            </div>
            @endforelse
        </div>
        <div class="mt-6 md:hidden text-center fade-up">
            <a href="{{ route('informasi') }}" class="btn-outline-green">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>

@endsection
