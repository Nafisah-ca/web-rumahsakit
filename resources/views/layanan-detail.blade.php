@extends('layouts.app')
@php $title = $layanan->nama_layanan; $metaDesc = Str::limit(strip_tags($layanan->deskripsi ?? ''), 150); @endphp
@section('content')

@php
    $heroBreadcrumbs = [['Beranda','home'], ['Pelayanan','layanan']];
    if ($layanan->kategori) {
        $heroBreadcrumbs[] = [$layanan->kategori->nama_kategori, null];
    }
    $heroBreadcrumbs[] = [Str::limit($layanan->nama_layanan, 30), null];
@endphp

<x-page-hero
    page="layanan"
    :override-judul="$layanan->nama_layanan"
    :override-label="$layanan->kategori?->nama_kategori ?? 'Layanan Medis'"
    :override-deskripsi="$layanan->deskripsi ? Str::limit($layanan->deskripsi, 180) : null"
    :breadcrumbs="$heroBreadcrumbs"
/>

{{-- Konten Utama --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KIRI: konten --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Gambar utama --}}
                @if($layanan->gambar)
                <div class="rounded-2xl overflow-hidden shadow-md">
                    <img src="{{ Storage::url($layanan->gambar) }}" alt="{{ $layanan->nama_layanan }}"
                         class="w-full object-cover max-h-[400px]">
                </div>
                @endif

                {{-- Deskripsi singkat --}}
                @if($layanan->deskripsi)
                <div class="bg-green-50 border border-green-100 rounded-2xl p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-green-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $layanan->icon ?? 'fa-stethoscope' }} text-white text-sm"></i>
                        </div>
                        <h2 class="font-extrabold text-gray-900 text-lg">Tentang {{ $layanan->nama_layanan }}</h2>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $layanan->deskripsi }}</p>
                </div>
                @endif

                {{-- Konten lengkap (rich text) --}}
                @if($layanan->konten)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="prose prose-green max-w-none text-gray-700 text-sm leading-relaxed layanan-content">
                        {!! $layanan->konten !!}
                    </div>
                </div>
                @else
                {{-- Placeholder content jika konten belum diisi --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="font-extrabold text-gray-900 text-lg mb-4">Yang Kami Tawarkan</h3>
                    <ul class="space-y-3">
                        @foreach([
                            'Penanganan oleh dokter spesialis berpengalaman',
                            'Peralatan medis modern dan berteknologi tinggi',
                            'Prosedur sesuai standar akreditasi KARS',
                            'Pelayanan ramah dan profesional',
                            'Tersedia layanan rawat inap dan rawat jalan',
                        ] as $item)
                        <li class="flex items-start gap-3 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Layanan Terkait --}}
                @if($related->isNotEmpty())
                <div>
                    <h3 class="font-extrabold text-gray-900 text-lg mb-5">Layanan Terkait</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @foreach($related as $r)
                        <a href="{{ route('layanan.detail', $r) }}"
                           class="bg-white rounded-2xl border border-gray-100 hover:border-green-200 hover:shadow-md p-4 transition-all group">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 transition-colors">
                                    <i class="fas {{ $r->icon ?? 'fa-stethoscope' }} text-green-600 group-hover:text-white transition-colors text-sm"></i>
                                </div>
                                <h4 class="font-bold text-gray-900 text-sm leading-tight">{{ $r->nama_layanan }}</h4>
                            </div>
                            @if($r->deskripsi)
                            <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">{{ Str::limit($r->deskripsi, 80) }}</p>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- KANAN: Sidebar --}}
            <div class="space-y-5">

                {{-- CTA Booking --}}
                <div class="bg-green-600 rounded-2xl p-6 text-white">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center mb-4">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                    <h3 class="font-extrabold text-lg mb-2">Buat Janji Temu</h3>
                    <p class="text-green-100 text-sm mb-5 leading-relaxed">
                        Daftarkan diri Anda sekarang dan dapatkan pelayanan terbaik dari dokter spesialis kami.
                    </p>
                    <a href="{{ route('portal.booking.create') }}"
                       class="block w-full text-center bg-white text-green-700 py-3 rounded-xl font-extrabold text-sm hover:bg-green-50 transition-all">
                        <i class="fas fa-calendar-plus mr-2"></i>Daftar Sekarang
                    </a>
                </div>

                {{-- Kontak Cepat --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h4 class="font-extrabold text-gray-900 text-sm mb-4">Hubungi Kami</h4>
                    <div class="space-y-3">
                        @if($setting_global->telepon ?? null)
                        <a href="tel:{{ preg_replace('/[^0-9]/', '', $setting_global->telepon) }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-green-50 hover:text-green-700 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 transition-colors">
                                <i class="fas fa-phone text-green-600 text-xs group-hover:text-white transition-colors"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-semibold">Telepon</p>
                                <p class="text-sm font-bold text-gray-800">{{ $setting_global->telepon }}</p>
                            </div>
                        </a>
                        @endif
                        @if($setting_global->whatsapp ?? null)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $setting_global->whatsapp) }}" target="_blank"
                           class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-green-50 hover:text-green-700 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 transition-colors">
                                <i class="fab fa-whatsapp text-green-600 group-hover:text-white transition-colors"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-semibold">WhatsApp</p>
                                <p class="text-sm font-bold text-gray-800">{{ $setting_global->whatsapp }}</p>
                            </div>
                        </a>
                        @endif
                        <a href="tel:118"
                           class="flex items-center gap-3 p-3 rounded-xl bg-red-50 hover:bg-red-100 transition-colors group">
                            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-ambulance text-red-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-red-400 font-semibold">IGD Darurat</p>
                                <p class="text-sm font-bold text-red-700">118 – 24 Jam</p>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Jam Operasional --}}
                @if($setting_global->jam_operasional ?? null)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h4 class="font-extrabold text-gray-900 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-clock text-green-500"></i> Jam Operasional
                    </h4>
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $setting_global->jam_operasional }}</p>
                </div>
                @endif

                {{-- Semua Kategori Layanan --}}
                @if($kategoris->isNotEmpty())
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <h4 class="font-extrabold text-gray-900 text-sm mb-4">Kategori Layanan</h4>
                    <div class="space-y-1.5">
                        @foreach($kategoris as $kat)
                        <a href="{{ route('layanan.by-kategori', $kat->id) }}"
                           class="flex items-center justify-between p-2.5 rounded-xl hover:bg-green-50 hover:text-green-700 transition-colors group
                                  {{ ($layanan->kategori_layanan_id == $kat->id) ? 'bg-green-50 text-green-700' : 'text-gray-600' }}">
                            <span class="flex items-center gap-2 text-sm font-semibold">
                                <i class="fas {{ $kat->icon ?? 'fa-stethoscope' }} text-green-500 w-4 text-center text-xs"></i>
                                {{ $kat->nama_kategori }}
                            </span>
                            <span class="text-xs font-bold bg-gray-100 group-hover:bg-green-100 px-2 py-0.5 rounded-full transition-colors">
                                {{ $kat->layanan_aktif_count }}
                            </span>
                        </a>
                        @endforeach
                        <a href="{{ route('layanan') }}"
                           class="flex items-center gap-2 p-2.5 rounded-xl text-gray-500 hover:bg-gray-50 text-sm font-semibold transition-colors mt-1 border-t border-gray-50 pt-3">
                            <i class="fas fa-list text-green-500 w-4 text-center text-xs"></i>
                            Semua Layanan
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>

@endsection
@push('styles')
<style>
.layanan-content h1,.layanan-content h2,.layanan-content h3 { font-weight:800; color:#0f172a; margin-bottom:.5rem; margin-top:1.5rem; }
.layanan-content h2 { font-size:1.125rem; }
.layanan-content h3 { font-size:1rem; }
.layanan-content p  { margin-bottom:1rem; color:#475569; line-height:1.8; }
.layanan-content ul,.layanan-content ol { padding-left:1.25rem; margin-bottom:1rem; }
.layanan-content li { margin-bottom:.4rem; color:#475569; }
.layanan-content ul li { list-style:disc; }
.layanan-content strong { color:#0f172a; }
.layanan-content a { color:#16a34a; text-decoration:underline; }
.line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.line-clamp-3 { display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
</style>
@endpush
