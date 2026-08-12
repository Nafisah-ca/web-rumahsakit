@extends('layouts.app')
@php
    $title = ($activeKategoriNama ?? null) ? $activeKategoriNama.' – Pelayanan' : 'Pelayanan';
    $heroBreadcrumbs = [['Beranda','home']];
    if ($activeKategoriNama ?? null) {
        $heroBreadcrumbs[] = ['Pelayanan','layanan'];
        $heroBreadcrumbs[] = [$activeKategoriNama, null];
    } else {
        $heroBreadcrumbs[] = ['Semua Pelayanan', null];
    }
@endphp
@section('content')

<x-page-hero
    page="layanan"
    :override-judul="$activeKategoriNama ?? null"
    :override-label="($activeKategoriNama ?? null) ? 'Kategori Layanan' : null"
    :breadcrumbs="$heroBreadcrumbs"
/>

{{-- IGD Banner --}}
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
                <a href="tel:{{ preg_replace('/[^0-9]/', '', $setting_global->telepon ?? '02150943838') }}"
                   class="flex items-center gap-2 bg-white text-red-700 px-5 py-2 rounded-xl font-bold text-sm hover:bg-red-50 transition-all">
                    <i class="fas fa-phone-alt"></i>
                    {{ $setting_global->telepon ?? '(021) 5094-3838' }}
                </a>
                <a href="tel:118" class="flex items-center gap-2 border-2 border-white text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-white/10 transition-all">
                    <i class="fas fa-bell"></i> 118
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Filter Kategori (sticky) --}}
@if($kategoris->isNotEmpty())
<div class="bg-white border-b border-gray-100 sticky top-16 z-40 shadow-sm">
    <div class="max-w-screen-xl mx-auto px-4 py-3">
        <div class="flex flex-wrap gap-2 items-center">
            <button onclick="window.location='{{ route('layanan') }}'"
                class="px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       {{ ($activeKategoriId ?? null) == null ? 'bg-green-600 border-green-600 text-white' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600' }}">
                Semua
            </button>
            @foreach($kategoris as $kat)
            <button onclick="window.location='{{ route('layanan.by-kategori', $kat->id) }}'"
                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       {{ ($activeKategoriId ?? null) == $kat->id ? 'bg-green-600 border-green-600 text-white' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600' }}">
                <i class="fas {{ $kat->icon ?? 'fa-stethoscope' }}"></i>
                {{ $kat->nama_kategori }}
                <span class="ml-0.5 opacity-70">({{ $kat->layanan_aktif_count }})</span>
            </button>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- Daftar Layanan --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="section-label">Departemen &amp; Spesialisasi</span>
            <h2 class="section-title">Layanan <span>{{ $activeKategoriNama ?? 'Unggulan' }}</span></h2>
            <p class="text-gray-500 text-sm mt-2 max-w-lg mx-auto">
                @if($layananList->isNotEmpty())
                    Tersedia <strong>{{ $layananList->count() }} layanan</strong> dengan dokter ahli dan peralatan medis terkini.
                @else
                    Belum ada layanan yang tersedia untuk kategori ini.
                @endif
            </p>
        </div>

        @if($layananList->isEmpty())
        <div class="text-center py-20">
            <div class="w-20 h-20 rounded-2xl bg-gray-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-stethoscope text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-400 font-semibold text-base mb-2">Belum ada layanan yang tersedia.</p>
            <p class="text-gray-400 text-sm">Coba pilih kategori lain atau kembali ke semua pelayanan.</p>
            <a href="{{ route('layanan') }}" class="inline-flex items-center gap-2 mt-5 btn-green px-5 py-2.5 rounded-xl text-sm">
                <i class="fas fa-arrow-left"></i> Semua Pelayanan
            </a>
        </div>
        @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($layananList as $l)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all group fade-up">
                {{-- Header card dengan gambar atau gradien --}}
                @if($l->gambar)
                <div class="h-44 overflow-hidden relative">
                    <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    @if($l->kategori)
                    <span class="absolute top-3 left-3 bg-green-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-full">
                        {{ $l->kategori->nama_kategori }}
                    </span>
                    @endif
                </div>
                @else
                <div class="h-36 flex items-center justify-center relative"
                     style="background: linear-gradient(135deg, #00521f, #00b04f)">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-white text-3xl"></i>
                    </div>
                    @if($l->kategori)
                    <span class="absolute top-3 left-3 bg-white/20 text-white text-[10px] font-bold px-2.5 py-1 rounded-full backdrop-blur-sm">
                        {{ $l->kategori->nama_kategori }}
                    </span>
                    @endif
                </div>
                @endif

                <div class="p-5">
                    {{-- Icon + Nama (untuk kartu tanpa gambar, icon sudah di atas) --}}
                    @if($l->gambar)
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-50 group-hover:scale-110 transition-transform">
                            <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-green-600"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="font-extrabold text-gray-900 text-base leading-tight">{{ $l->nama_layanan }}</h3>
                            <span class="text-xs text-gray-400 font-semibold">{{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}</span>
                        </div>
                    </div>
                    @else
                    <h3 class="font-extrabold text-gray-900 text-base mb-1 leading-tight">{{ $l->nama_layanan }}</h3>
                    <span class="text-xs text-gray-400 font-semibold block mb-3">{{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}</span>
                    @endif

                    @if($l->deskripsi)
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3">{{ Str::limit($l->deskripsi, 110) }}</p>
                    @endif

                    <div class="flex gap-2 mt-auto pt-1">
                        <a href="{{ route('layanan.detail', $l) }}"
                           class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-xl text-xs font-bold transition-colors">
                            <i class="fas fa-info-circle mr-1"></i>Selengkapnya
                        </a>
                        <a href="{{ route('portal.booking.create') }}"
                           class="flex-1 text-center border-2 border-green-600 text-green-700 hover:bg-green-50 py-2.5 rounded-xl text-xs font-bold transition-colors">
                            <i class="fas fa-calendar-check mr-1"></i>Buat Janji
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Mengapa Kami --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="section-label">Keunggulan Kami</span>
            <h2 class="section-title">Mengapa Memilih <span>Kami?</span></h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @foreach([
                ['fa-user-md',       'Dokter Spesialis',     'Tim dokter berpengalaman dan bersertifikasi nasional & internasional.', '#16a34a'],
                ['fa-microscope',    'Teknologi Modern',     'Peralatan diagnostik canggih untuk hasil akurat dan cepat.',             '#0ea5e9'],
                ['fa-clock',         'Layanan 24 Jam',       'IGD dan beberapa layanan tersedia sepanjang hari tanpa henti.',          '#f59e0b'],
                ['fa-shield-halved', 'BPJS & Asuransi',      'Melayani pasien BPJS Kesehatan dan berbagai asuransi swasta.',          '#8b5cf6'],
            ] as [$icon, $title, $desc, $color])
            <div class="card-base p-6 text-center group fade-up">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform"
                     style="background:{{ $color }}18">
                    <i class="fas {{ $icon }} text-xl" style="color:{{ $color }}"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-sm mb-1.5">{{ $title }}</h3>
                <p class="text-gray-500 text-xs leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-14" style="background: linear-gradient(135deg, #00521f, #00b04f)">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <h2 class="text-white font-extrabold text-3xl mb-3">Butuh Bantuan Medis?</h2>
        <p class="text-green-100 text-sm max-w-lg mx-auto mb-8">
            Tim kami siap membantu Anda. Buat janji temu sekarang atau hubungi IGD untuk kondisi darurat.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('portal.booking.create') }}"
               class="flex items-center gap-2 bg-white text-green-700 px-7 py-3 rounded-xl font-extrabold text-sm hover:bg-green-50 transition-all shadow-lg">
                <i class="fas fa-calendar-check text-green-600"></i> Buat Janji Temu
            </a>
            <a href="{{ route('dokter') }}"
               class="flex items-center gap-2 border-2 border-white text-white px-7 py-3 rounded-xl font-extrabold text-sm hover:bg-white/10 transition-all">
                <i class="fas fa-user-md"></i> Lihat Dokter
            </a>
        </div>
    </div>
</section>

@endsection
