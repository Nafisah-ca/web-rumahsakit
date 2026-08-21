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

            {{-- Card: Jenis Layanan — dropdown kategori untuk scroll navigation --}}
            <div class="relative" id="layanan-dropdown-wrap">
                <button type="button" id="btn-layanan-dropdown"
                    aria-expanded="false"
                    aria-haspopup="true"
                    class="layanan-stat-card flex items-center gap-3 p-4 rounded-2xl bg-green-50 text-left w-full group transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-400">
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0 border border-white shadow-sm transition-colors duration-200 group-hover:bg-green-600">
                        <i class="fas fa-stethoscope text-green-600 text-lg transition-colors duration-200 group-hover:text-white"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-extrabold text-gray-900 text-lg leading-none">{{ $totalLayanan }}+</p>
                        <p class="text-gray-500 text-xs mt-1">Jenis Layanan</p>
                    </div>
                    <i id="icon-layanan-dropdown"
                       class="fas fa-chevron-down text-green-500 text-xs flex-shrink-0 transition-transform duration-300"></i>
                </button>

                {{-- Dropdown panel berisi daftar kategori --}}
                <div id="layanan-dropdown-menu"
                     role="menu"
                     style="display:none;position:absolute;top:calc(100% + 6px);left:0;z-index:9999;
                            background:#fff;border-radius:14px;
                            box-shadow:0 8px 32px rgba(0,0,0,0.13);
                            border:1px solid #e2e8f0;min-width:220px;max-width:280px;
                            padding:8px 0;
                            animation:dropdown-in 0.18s ease both">
                    <div style="padding:8px 16px 6px;border-bottom:1px solid #f1f5f9;margin-bottom:4px">
                        <p style="font-size:10px;font-weight:800;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em">
                            Kategori Layanan
                        </p>
                    </div>
                    @forelse($kategoriList as $kat)
                    <button type="button"
                            onclick="scrollToKategori('kat-{{ $kat->id }}'); closeLayananDropdown();"
                            role="menuitem"
                            style="display:flex;align-items:center;gap:10px;width:100%;padding:9px 16px;
                                   text-align:left;background:none;border:none;cursor:pointer;
                                   font-size:13px;font-weight:600;color:#1e293b;
                                   transition:background .12s ease,color .12s ease"
                            onmouseover="this.style.background='#f0fdf4';this.style.color='#15803d'"
                            onmouseout="this.style.background='none';this.style.color='#1e293b'">
                        <span style="width:28px;height:28px;border-radius:8px;background:#f0fdf4;
                                     display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="fas {{ $kat->icon ?? 'fa-hospital' }}" style="color:#16a34a;font-size:12px"></i>
                        </span>
                        <span style="flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $kat->nama_kategori }}</span>
                        <span style="font-size:10px;font-weight:700;background:#f0fdf4;color:#16a34a;
                                     padding:2px 7px;border-radius:99px;flex-shrink:0">
                            {{ $kat->layanansAktif->count() }}
                        </span>
                    </button>
                    @empty
                    <div style="padding:12px 16px;font-size:12px;color:#94a3b8;text-align:center">
                        Belum ada kategori
                    </div>
                    @endforelse
                    @if($layananTanpaKategori->isNotEmpty())
                    <button type="button"
                            onclick="scrollToKategori('kat-lainnya'); closeLayananDropdown();"
                            role="menuitem"
                            style="display:flex;align-items:center;gap:10px;width:100%;padding:9px 16px;
                                   text-align:left;background:none;border:none;cursor:pointer;
                                   font-size:13px;font-weight:600;color:#1e293b;
                                   transition:background .12s ease,color .12s ease"
                            onmouseover="this.style.background='#f8fafc';this.style.color='#475569'"
                            onmouseout="this.style.background='none';this.style.color='#1e293b'">
                        <span style="width:28px;height:28px;border-radius:8px;background:#f8fafc;
                                     display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="fas fa-ellipsis" style="color:#64748b;font-size:12px"></i>
                        </span>
                        <span style="flex:1">Lainnya</span>
                        <span style="font-size:10px;font-weight:700;background:#f1f5f9;color:#64748b;
                                     padding:2px 7px;border-radius:99px;flex-shrink:0">
                            {{ $layananTanpaKategori->count() }}
                        </span>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Card: Dokter Spesialis — link langsung ke halaman Dokter --}}
            <a href="{{ route('dokter') }}"
               class="flex items-center gap-3 p-4 rounded-2xl bg-blue-50 group transition-all duration-200 hover:bg-blue-600 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-400">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0 border border-white shadow-sm transition-colors duration-200 group-hover:bg-white/20">
                    <i class="fas fa-user-md text-blue-600 text-lg transition-colors duration-200 group-hover:text-white"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-extrabold text-gray-900 text-lg leading-none group-hover:text-white transition-colors duration-200">{{ $dokterSpesialis->count() > 0 ? $dokterSpesialis->count().'+' : '50+' }}</p>
                    <p class="text-gray-500 text-xs mt-1 group-hover:text-blue-100 transition-colors duration-200">Dokter Spesialis</p>
                </div>
                <i class="fas fa-arrow-right text-blue-400 text-xs flex-shrink-0 group-hover:text-white transition-colors duration-200"></i>
            </a>

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

{{-- ═══════════════ LAYANAN PER KATEGORI ═══════════════ --}}
@if($kategoriList->isNotEmpty())

    @foreach($kategoriList as $kat)
    <section id="kat-{{ $kat->id }}" class="py-14 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }} kategori-section">
        <div class="max-w-screen-xl mx-auto px-4">

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-10 kategori-header">
                <div class="flex items-center gap-4">
                    @if($kat->gambar)
                    <img src="{{ Storage::url($kat->gambar) }}" alt="{{ $kat->nama_kategori }}"
                         class="w-16 h-16 rounded-2xl object-cover shadow-md flex-shrink-0 border-2 border-white">
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

            <div class="layanan-grid">
                @foreach($kat->layanansAktif as $l)
                <div class="layanan-card bg-white rounded-2xl border border-gray-100 shadow-sm group overflow-hidden flex flex-col">
                    <div class="h-1 bg-green-500"></div>
                    @if($l->gambar)
                    <div class="relative overflow-hidden">
                        <img src="{{ Storage::url($l->gambar) }}" alt="{{ $l->nama_layanan }}"
                             class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
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
                <div class="layanan-card bg-white rounded-2xl border border-gray-100 shadow-sm group overflow-hidden flex flex-col">
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
    {{-- Fallback: belum ada kategori, tampilkan semua layanan flat --}}
    @php $allLayanan = \App\Models\Layanan::aktif()->get(); @endphp
    <section class="py-14 bg-white">
        <div class="max-w-screen-xl mx-auto px-4">
            <div class="text-center mb-10">
                <span class="text-green-600 text-xs font-black uppercase tracking-widest block mb-2">Departemen & Spesialisasi</span>
                <h2 class="text-gray-900 font-extrabold text-3xl mb-2">Layanan <span class="text-green-600">Unggulan</span></h2>
                <p class="text-gray-500 text-sm max-w-lg mx-auto">Tersedia {{ $totalLayanan }}+ layanan dengan dokter ahli dan peralatan medis terkini.</p>
            </div>
            @if($allLayanan->isEmpty())
            <div class="text-center py-16">
                <i class="fas fa-stethoscope text-5xl text-gray-200 block mb-4"></i>
                <p class="text-gray-400 font-semibold">Belum ada layanan yang tersedia.</p>
            </div>
            @else
            <div class="layanan-grid">
                @foreach($allLayanan as $l)
                <div class="layanan-card bg-white rounded-2xl border border-gray-100 shadow-sm group overflow-hidden flex flex-col">
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

{{-- ═══════════════ PELAYANAN KHUSUS ═══════════════ --}}
<section class="py-16" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);">
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
                   class="rs-btn-janji-outline inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold">
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
/* ── Scrollbar ─────────────────────────── */
.scrollbar-none::-webkit-scrollbar { display: none; }
.scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }

/* ── Layanan grid ──────────────────────── */
.layanan-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1.25rem;
    align-items: stretch;
}
@media (max-width: 1023px) { .layanan-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 639px)  { .layanan-grid { grid-template-columns: 1fr; } }

/* ── Layanan card ──────────────────────── */
.layanan-card {
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.layanan-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.10);
    transform: translateY(-2px);
}

/* ── Dropdown animation ────────────────── */
@keyframes dropdown-in {
    from { opacity: 0; transform: translateY(-6px) scale(0.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* ── Dropdown stat card open state ────── */
.layanan-stat-card.is-open {
    background-color: #dcfce7;
    box-shadow: 0 0 0 2px #16a34a33;
}

/* ── Buat Janji Temu button ────────────── */
.rs-btn-janji {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background-color: #16a34a;
    color: #ffffff;
    font-size: 0.8125rem;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 12px;
    border: none;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.18s ease, transform 0.15s ease, box-shadow 0.18s ease;
    box-shadow: 0 2px 8px rgba(22,163,74,0.18);
    white-space: nowrap;
    position: relative;
    overflow: hidden;
}
.rs-btn-janji:hover {
    background-color: #15803d;
    box-shadow: 0 6px 18px rgba(22,163,74,0.30);
    transform: translateY(-2px);
    color: #ffffff;
    text-decoration: none;
}
.rs-btn-janji:active { transform: scale(0.96) translateY(0); }

/* ── Outline variant (dark bg) ─────────── */
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
    transition: background-color 0.18s ease, color 0.18s ease, transform 0.15s ease, border-color 0.18s ease;
    position: relative;
    overflow: hidden;
}
.rs-btn-janji-outline:hover {
    background-color: #ffffff;
    color: #166534;
    border-color: #ffffff;
    transform: translateY(-2px);
    text-decoration: none;
}
.rs-btn-janji-outline:active { transform: scale(0.96) translateY(0); }

@keyframes btn-ripple {
    to { transform: scale(2.8); opacity: 0; }
}
</style>
@endpush

@push('scripts')
<script>
/* ════════════════════════════════════════
   DROPDOWN — "8+ Jenis Layanan"
   ════════════════════════════════════════ */
(function () {
    var btn  = document.getElementById('btn-layanan-dropdown');
    var menu = document.getElementById('layanan-dropdown-menu');
    var icon = document.getElementById('icon-layanan-dropdown');
    if (!btn || !menu) return;

    function openMenu() {
        menu.style.display = 'block';
        btn.setAttribute('aria-expanded', 'true');
        btn.classList.add('is-open');
        icon.style.transform = 'rotate(180deg)';
        repositionMenu();
    }
    function closeMenu() {
        menu.style.display = 'none';
        btn.setAttribute('aria-expanded', 'false');
        btn.classList.remove('is-open');
        icon.style.transform = 'rotate(0deg)';
    }
    function repositionMenu() {
        var r = btn.getBoundingClientRect();
        if (r.left + 240 > window.innerWidth - 8) {
            menu.style.left = 'auto';
            menu.style.right = '0';
        } else {
            menu.style.left = '0';
            menu.style.right = 'auto';
        }
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.style.display === 'block' ? closeMenu() : openMenu();
    });
    document.addEventListener('click', function (e) {
        if (!document.getElementById('layanan-dropdown-wrap').contains(e.target)) closeMenu();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });
    window.addEventListener('resize', function () {
        if (menu.style.display === 'block') repositionMenu();
    }, { passive: true });
})();

/* Global — dipanggil dari onclick inline di blade */
function closeLayananDropdown() {
    var menu = document.getElementById('layanan-dropdown-menu');
    var btn  = document.getElementById('btn-layanan-dropdown');
    var icon = document.getElementById('icon-layanan-dropdown');
    if (menu) menu.style.display = 'none';
    if (btn)  { btn.setAttribute('aria-expanded','false'); btn.classList.remove('is-open'); }
    if (icon) icon.style.transform = 'rotate(0deg)';
}

/* ════════════════════════════════════════
   SCROLL KE KATEGORI
   ════════════════════════════════════════ */
function scrollToKategori(id) {
    var el = document.getElementById(id);
    if (!el) return;
    var top = el.getBoundingClientRect().top + window.pageYOffset - 130;
    window.scrollTo({ top: top, behavior: 'smooth' });
}

/* ════════════════════════════════════════
   RIPPLE pada tombol Buat Janji Temu
   ════════════════════════════════════════ */
document.addEventListener('click', function (e) {
    var btn = e.target.closest('.rs-btn-janji, .rs-btn-janji-outline');
    if (!btn) return;
    var ripple = document.createElement('span');
    var rect   = btn.getBoundingClientRect();
    var size   = Math.max(rect.width, rect.height);
    ripple.style.cssText = [
        'position:absolute','border-radius:50%','pointer-events:none',
        'background:rgba(255,255,255,0.35)',
        'width:'+size+'px','height:'+size+'px',
        'left:'+(e.clientX-rect.left-size/2)+'px',
        'top:'+(e.clientY-rect.top-size/2)+'px',
        'transform:scale(0)','animation:btn-ripple 0.5s ease-out forwards'
    ].join(';');
    btn.appendChild(ripple);
    ripple.addEventListener('animationend', function(){ ripple.remove(); });
}, { passive: true });
</script>
@endpush
