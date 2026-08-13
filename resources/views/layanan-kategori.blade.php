@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner, 'pageTitle' => $aktifKategori->nama_kategori, 'breadcrumbs' => [
    ['label' => 'Beranda',    'url' => route('home')],
    ['label' => 'Pelayanan',  'url' => route('layanan')],
    ['label' => $aktifKategori->nama_kategori],
]])

{{-- ═══ TAB FILTER KATEGORI ═══ --}}
<section class="bg-white sticky top-0 z-40 border-b border-gray-100 shadow-sm">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex gap-0 overflow-x-auto" style="-ms-overflow-style:none;scrollbar-width:none">
            {{-- Tab Semua --}}
            <a href="{{ route('layanan') }}"
               class="flex-shrink-0 flex items-center gap-2 px-5 py-4 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-200 transition-all whitespace-nowrap">
                <i class="fas fa-th-list text-xs"></i> Semua
            </a>
            @foreach($kategoriList as $kat)
            <a href="{{ route('layanan.by-kategori', $kat->id) }}"
               class="flex-shrink-0 flex items-center gap-2 px-5 py-4 text-sm font-bold border-b-2 transition-all whitespace-nowrap
                      {{ $kat->id == $aktifKategori->id
                         ? 'border-green-600 text-green-700'
                         : 'border-transparent text-gray-500 hover:text-gray-800 hover:border-gray-200' }}">
                <i class="fas {{ $kat->icon ?? 'fa-hospital' }} text-xs"></i>
                {{ $kat->nama_kategori }}
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ DAFTAR LAYANAN ═══ --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-gray-900 font-extrabold text-2xl">{{ $aktifKategori->nama_kategori }}</h2>
                <p class="text-gray-500 text-sm mt-1">{{ $layanans->count() }} layanan tersedia</p>
            </div>
            <a href="{{ route('portal.booking.create') }}"
               class="hidden sm:flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-colors">
                <i class="fas fa-calendar-plus"></i> Buat Janji Temu
            </a>
        </div>

        @if($layanans->isEmpty())
        <div class="text-center py-20">
            <i class="fas fa-{{ $aktifKategori->icon ?? 'stethoscope' }} text-6xl text-gray-200 block mb-4"></i>
            <p class="text-gray-400 font-semibold text-lg">Belum ada layanan di kategori ini.</p>
            <a href="{{ route('layanan') }}" class="mt-4 inline-flex items-center gap-2 text-green-600 font-bold text-sm hover:underline">
                <i class="fas fa-arrow-left"></i> Kembali ke semua pelayanan
            </a>
        </div>
        @else
        <div class="layanan-grid">
            @foreach($layanans as $l)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group overflow-hidden">
                <div class="h-1.5 bg-green-500 rounded-t-2xl"></div>

                @if($l->gambar)
                <div class="relative overflow-hidden">
                    <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}"
                         class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                    <span class="absolute bottom-3 left-3 bg-white/90 text-green-700 text-[10px] font-black px-2.5 py-1 rounded-full border border-green-100">
                        <i class="fas {{ $aktifKategori->icon ?? 'fa-hospital' }} mr-1"></i>{{ $aktifKategori->nama_kategori }}
                    </span>
                </div>
                @endif

                <div class="p-5">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0
                                    group-hover:bg-green-600 transition-colors duration-200">
                            <i class="fas {{ $l->icon ?? 'fa-stethoscope' }} text-green-600
                                      group-hover:text-white text-base transition-colors duration-200"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-base leading-snug">{{ $l->nama_layanan }}</h3>
                            <span class="text-xs text-green-600 font-bold">{{ $aktifKategori->nama_kategori }}</span>
                        </div>
                    </div>

                    @if($l->deskripsi)
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ Str::limit($l->deskripsi, 110) }}</p>
                    @endif

                    <a href="{{ route('portal.booking.create') }}"
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl text-sm transition-colors">
                        <i class="fas fa-calendar-plus mr-1.5"></i> Buat Janji Temu
                    </a>
                </div>
            </div>
            @endforeach

            {{-- Padding card kosong agar grid tetap rapi saat item < 3 --}}
            @if($layanans->count() % 3 === 1)
            <div class="hidden lg:block"></div>
            <div class="hidden lg:block"></div>
            @elseif($layanans->count() % 3 === 2)
            <div class="hidden lg:block"></div>
            @endif
        </div>
        @endif

        {{-- Navigasi antar kategori --}}
        @if($kategoriList->count() > 1)
        <div class="mt-14 pt-10 border-t border-gray-100">
            <p class="text-gray-400 text-xs font-black uppercase tracking-widest mb-4">Kategori Lainnya</p>
            <div class="flex flex-wrap gap-3">
                @foreach($kategoriList->where('id', '!=', $aktifKategori->id) as $kat)
                <a href="{{ route('layanan.by-kategori', $kat->id) }}"
                   class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gray-50 hover:bg-green-50 border border-gray-200 hover:border-green-200 text-gray-700 hover:text-green-700 font-semibold text-sm transition-all">
                    <i class="fas {{ $kat->icon ?? 'fa-hospital' }} text-green-500 text-xs"></i>
                    {{ $kat->nama_kategori }}
                    @php $cnt = $kat->layanansAktif ? $kat->layanansAktif->count() : \App\Models\Layanan::aktif()->where('kategori_layanan_id',$kat->id)->count(); @endphp
                    @if($cnt > 0)
                    <span class="bg-green-100 text-green-700 text-[10px] font-black px-1.5 py-0.5 rounded-full">{{ $cnt }}</span>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ═══ CTA ═══ --}}
<section class="py-12 bg-green-50 border-t border-green-100">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-5">
            <div>
                <h3 class="text-gray-900 font-extrabold text-xl mb-1">Butuh Layanan {{ $aktifKategori->nama_kategori }}?</h3>
                <p class="text-gray-500 text-sm">Daftar sekarang dan dapatkan pelayanan terbaik dari dokter kami.</p>
            </div>
            <div class="flex gap-3 flex-shrink-0">
                <a href="{{ route('portal.booking.create') }}"
                   class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-xl font-extrabold text-sm transition-all shadow-lg shadow-green-100">
                    <i class="fas fa-calendar-check"></i> Daftar Poliklinik
                </a>
                <a href="{{ route('kontak') }}"
                   class="flex items-center gap-2 border-2 border-green-600 text-green-700 hover:bg-green-50 px-6 py-3 rounded-xl font-extrabold text-sm transition-all">
                    <i class="fas fa-phone"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* Grid layanan — 3 kolom desktop, 2 tablet, 1 mobile */
.layanan-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.25rem;
}
@media (max-width: 1023px) {
    .layanan-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 639px) {
    .layanan-grid { grid-template-columns: repeat(1, minmax(0, 1fr)); }
}
</style>
@endpush
