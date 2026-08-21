@extends('layouts.app')

@push('styles')
<style>
/* ── BASE STATE: elemen sebelum animasi ────────────────────── */
.ao-fade-up {
    opacity: 0;
    transform: translateY(28px);
    transition: opacity 0.55s ease, transform 0.55s ease;
}
.ao-fade-left {
    opacity: 0;
    transform: translateX(-28px);
    transition: opacity 0.55s ease, transform 0.55s ease;
}
.ao-fade-right {
    opacity: 0;
    transform: translateX(28px);
    transition: opacity 0.55s ease, transform 0.55s ease;
}
.ao-scale {
    opacity: 0;
    transform: scale(0.92);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
/* ── VISIBLE STATE ─────────────────────────────────────────── */
.ao-fade-up.ao-in,
.ao-fade-left.ao-in,
.ao-fade-right.ao-in,
.ao-scale.ao-in {
    opacity: 1;
    transform: none;
}
/* ── Stagger delays ────────────────────────────────────────── */
.ao-delay-1 { transition-delay: 0.08s; }
.ao-delay-2 { transition-delay: 0.18s; }
.ao-delay-3 { transition-delay: 0.28s; }
.ao-delay-4 { transition-delay: 0.38s; }
.ao-delay-5 { transition-delay: 0.48s; }
.ao-delay-6 { transition-delay: 0.58s; }
.ao-delay-7 { transition-delay: 0.68s; }
.ao-delay-8 { transition-delay: 0.78s; }

/* ── Penghargaan card hover lift ───────────────────────────── */
.award-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}
.award-card:hover {
    transform: translateY(-5px) scale(1.03);
    box-shadow: 0 10px 30px rgba(0,0,0,0.10);
    border-color: #86efac;
}

/* ── Mobile fixes: Tentang Kami ────────────────────────────── */
@media (max-width: 767px) {
    /* Profil: stat grid 2 kolom penuh */
    .grid.grid-cols-2.gap-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    /* Kotak hijau RS: ukuran wajar */
    [style*="min-height: 380px"] { min-height: 240px !important; }
    /* Sambutan direktur: stack vertikal */
    [style*="grid-template-columns:220px 1fr"] {
        display: flex !important;
        flex-direction: column !important;
    }
    /* Visi/Misi/Nilai: gap kecil */
    .grid.grid-cols-1.md\:grid-cols-3 { gap: 0.75rem; }
    /* Padding card */
    .rounded-2xl.p-7 { padding: 1.25rem !important; }
    /* Sejarah: tidak ada padding kiri berlebih di mobile */
    .relative.pl-8.border-l-4 { padding-left: 1.5rem; }
    /* Penghargaan: 2 kolom */
    .grid.grid-cols-2.sm\:grid-cols-3.md\:grid-cols-4 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.5rem;
    }
    .award-card { padding: 0.75rem !important; }
}
</style>
@endpush

@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('tentang'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Tentang Kami'],
]])

{{-- Profil Singkat --}}
<section class="py-16 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="ao-fade-left">
                <span class="section-label">Siapa Kami</span>

                {{-- Teks 1: Judul Utama (field: nama_rumahsakit) --}}
                <h2 class="section-title mb-2">{{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }}</h2>

                {{-- Teks 2: Tagline / Motto (field: motto) --}}
                @if($setting->motto ?? null)
                <p class="text-green-600 font-bold text-base mb-5" style="font-family: Georgia, serif; font-style: italic; letter-spacing: 0.02em;">
                    &ldquo;{{ $setting->motto }}&rdquo;
                </p>
                @endif

                {{-- Teks 3: Deskripsi / Paragraf (field: tentang_kami) --}}
                @if($setting->tentang_kami ?? null)
                <div class="text-gray-600 leading-relaxed mb-6 text-sm">
                    {!! nl2br(e($setting->tentang_kami)) !!}
                </div>
                @else
                <p class="text-gray-600 leading-relaxed mb-6 text-sm">
                    Kami berkomitmen memberikan pelayanan kesehatan yang profesional, terjangkau, dan berorientasi pada pasien.
                </p>
                @endif

                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['5','Spesialisasi','fa-stethoscope'],
                        ['50','Mitra Asuransi','fa-handshake'],
                        ['1993','Tahun Berdiri','fa-calendar'],
                    ] as [$val,$lbl,$ico])
                    <div class="ao-scale ao-delay-{{ $loop->index + 1 }} flex items-center gap-3 bg-green-50 rounded-xl p-4 border border-green-100">
                        <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $ico }} text-white text-sm"></i>
                        </div>
                        <div>
                            <div class="font-extrabold text-gray-900 text-lg leading-tight">{{ $val }}</div>
                            <div class="text-gray-500 text-xs">{{ $lbl }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="ao-fade-right">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="background: linear-gradient(135deg, #00521f, #00b04f); min-height: 380px;">
                    <div class="absolute inset-0 flex items-center justify-center opacity-10">
                        <i class="fas fa-hospital text-white" style="font-size: 18rem;"></i>
                    </div>
                    <div class="relative p-10 flex flex-col items-center justify-center h-full text-center" style="min-height: 380px;">
                        <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-5 border-2 border-white/30">
                            <i class="fas fa-hospital-alt text-white text-3xl"></i>
                        </div>
                        {{-- Kotak hijau: Teks 1 & Teks 2 juga dinamis --}}
                        <h3 class="text-white font-extrabold text-2xl mb-2">{{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }}</h3>
                        <p class="text-green-200 text-sm font-semibold tracking-wider uppercase mb-6">
                            {{ $setting->motto ?? 'Melayani dengan Kasih Sayang' }}
                        </p>
                        <div class="grid grid-cols-2 gap-3 w-full max-w-xs">
                            @foreach(['KARS Paripurna','ISO 9001:2015','SNARS Ed.1','BPJS Kesehatan'] as $a)
                            <div class="bg-white/15 backdrop-blur-sm rounded-xl px-3 py-2 text-white text-xs font-bold border border-white/20">
                                <i class="fas fa-certificate text-yellow-300 mr-1"></i>{{ $a }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Sambutan Direktur --}}
@if($setting->sambutan_direktur ?? null)
<section class="py-16 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10 ao-fade-up">
            <span class="section-label">Dari Pimpinan Kami</span>
            <h2 class="section-title">Sambutan <span>Direktur</span></h2>
        </div>
        <div class="max-w-4xl mx-auto ao-fade-up ao-delay-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Pakai grid 4 kolom: foto 1 kolom, teks 3 kolom agar lebih lega --}}
                <div style="display:grid;grid-template-columns:220px 1fr" class="max-md:block">
                    {{-- Foto / Ikon Direktur --}}
                    <div class="flex flex-col items-center justify-center p-8 bg-gradient-to-br from-green-600 to-green-700 text-center" style="min-width:0">
                        <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-4 border-2 border-white/30">
                            <i class="fas fa-user-tie text-white text-3xl"></i>
                        </div>
                        <p class="text-white font-extrabold text-base leading-tight">Direktur</p>
                        <p class="text-green-200 text-xs mt-1 uppercase tracking-wider font-semibold">{{ $setting->nama_rumahsakit ?? 'Rumah Sakit' }}</p>
                    </div>
                    {{-- Isi Sambutan --}}
                    <div class="p-8 md:p-10" style="min-width:0">
                        <i class="fas fa-quote-left text-green-200 text-3xl mb-4 block"></i>
                        {{-- max-height + overflow-y:auto agar tidak makan tempat terlalu panjang --}}
                        <div class="text-gray-600 leading-relaxed text-sm"
                             style="max-height:260px;overflow-y:auto;padding-right:8px;word-break:break-word;white-space:pre-line">{{ $setting->sambutan_direktur }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Visi Misi --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10 ao-fade-up">
            <span class="section-label">Arah & Tujuan</span>
            <h2 class="section-title">Visi, Misi & <span>Nilai</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-7 shadow-sm border-t-4 border-green-500 ao-fade-up ao-delay-1">
                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center mb-5">
                    <i class="fas fa-eye text-green-600 text-2xl"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg mb-4">VISI</h3>
                <p class="text-gray-600 leading-relaxed text-sm italic">
                    @if($setting->visi ?? null)
                        &ldquo;{{ $setting->visi }}&rdquo;
                    @else
                        "Menjadi rumah sakit terpercaya yang dikenal karena pelayanan kasih sayang, kualitas medis unggul, dan kepercayaan masyarakat."
                    @endif
                </p>
            </div>
            <div class="bg-white rounded-2xl p-7 shadow-sm border-t-4 border-blue-500 ao-fade-up ao-delay-2">
                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center mb-5">
                    <i class="fas fa-bullseye text-blue-600 text-2xl"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg mb-4">MISI</h3>
                @if($setting->misi ?? null)
                <div class="text-sm text-gray-600 leading-relaxed">
                    {!! nl2br(e($setting->misi)) !!}
                </div>
                @else
                <ul class="space-y-2.5 text-sm text-gray-600">
                    @foreach([
                        'Memberikan pelayanan medis berkualitas tinggi yang berpusat pada pasien',
                        'Mengembangkan SDM kesehatan yang kompeten dan berkarakter',
                        'Menerapkan teknologi medis terkini di setiap layanan',
                        'Memperluas jangkauan layanan untuk masyarakat dan sekitarnya',
                        'Menjaga kepercayaan pasien sebagai amanah tertinggi',
                    ] as $m)
                    <li class="flex items-start gap-2">
                        <i class="fas fa-check-circle text-blue-500 mt-0.5 flex-shrink-0"></i>
                        <span>{{ $m }}</span>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
            <div class="bg-white rounded-2xl p-7 shadow-sm border-t-4 border-purple-500 ao-fade-up ao-delay-3">
                <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center mb-5">
                    <i class="fas fa-heart text-purple-600 text-2xl"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-lg mb-4">NILAI INTI</h3>
                <div class="space-y-3">
                    @foreach([
                        ['K','asih Sayang','Melayani dengan tulus dari hati'],
                        ['A','manah','Bertanggung jawab kepada pasien dan Tuhan'],
                        ['S','antun','Bersikap hormat kepada semua pasien'],
                        ['I','novatif','Selalu berkembang mengikuti kemajuan medis'],
                        ['H','andal','Profesional, terlatih, dan dapat diandalkan'],
                    ] as [$l,$rest,$d])
                    <div class="flex gap-3 items-start">
                        <div class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center font-black text-sm flex-shrink-0">{{ $l }}</div>
                        <div>
                            <span class="font-bold text-gray-900 text-sm">{{ $l.$rest }}</span>
                            <p class="text-gray-500 text-xs">{{ $d }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Sejarah --}}
@if($setting->sejarah ?? null)
<section class="py-16 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10 ao-fade-up">
            <span class="section-label">Perjalanan Kami</span>
            <h2 class="section-title">Sejarah <span>Rumah Sakit</span></h2>
        </div>
        <div class="max-w-4xl mx-auto ao-fade-up ao-delay-2">
            <div class="relative pl-8 border-l-4 border-green-500">
                <div class="absolute -left-3 top-0 w-6 h-6 rounded-full bg-green-500 flex items-center justify-center">
                    <i class="fas fa-landmark text-white text-xs"></i>
                </div>
                <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 shadow-sm">
                    <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($setting->sejarah)) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Penghargaan — data dinamis dari CMS/database --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10 ao-fade-up">
            <span class="section-label">Pengakuan & Sertifikasi</span>
            <h2 class="section-title">Penghargaan <span>Kami</span></h2>
        </div>

        @if($penghargaan->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($penghargaan as $p)
            <div class="award-card ao-scale ao-delay-{{ min($loop->index + 1, 8) }}
                        bg-gray-50 rounded-2xl p-5 text-center border border-gray-100 group flex flex-col items-center">

                {{-- Logo atau ikon fallback --}}
                <div class="w-16 h-16 flex items-center justify-center mx-auto mb-3 rounded-xl overflow-hidden
                            bg-white border border-gray-100 shadow-sm group-hover:shadow-md transition-shadow"
                     style="padding:6px">
                    @if($p->logo)
                    <img src="{{ $p->logo_url }}"
                         alt="{{ $p->nama }}"
                         class="max-h-full max-w-full object-contain"
                         style="max-height:52px;max-width:52px"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div style="display:none" class="w-full h-full items-center justify-center">
                        <i class="fas fa-certificate text-green-400 text-2xl"></i>
                    </div>
                    @else
                    <i class="fas fa-award text-green-500 text-2xl"></i>
                    @endif
                </div>

                <p class="font-bold text-gray-800 text-xs mb-1 leading-snug">{{ $p->nama }}</p>

                @if($p->tahun)
                <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full font-semibold">{{ $p->tahun }}</span>
                @endif

                @if($p->deskripsi)
                <p class="text-[10px] text-gray-400 mt-1 leading-snug line-clamp-2">{{ $p->deskripsi }}</p>
                @endif
            </div>
            @endforeach
        </div>

        @else
        {{-- Fallback jika belum ada data penghargaan di CMS --}}
        <div class="text-center py-10 text-gray-400">
            <i class="fas fa-award text-4xl opacity-20 block mb-3"></i>
            <p class="text-sm">Belum ada data penghargaan. Tambahkan melalui CMS &rarr; Penghargaan.</p>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* ── Collect all animatable elements ──────────────────────────── */
    var animClasses = ['ao-fade-up', 'ao-fade-left', 'ao-fade-right', 'ao-scale'];
    var selector    = animClasses.map(function (c) { return '.' + c; }).join(',');
    var elements    = document.querySelectorAll(selector);

    if (!elements.length) return;

    /* ── Intersection Observer ─────────────────────────────────────── */
    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('ao-in');
                observer.unobserve(entry.target); /* fire only once */
            }
        });
    }, {
        threshold:  0.12,
        rootMargin: '0px 0px -40px 0px'
    });

    elements.forEach(function (el) {
        observer.observe(el);
    });
})();
</script>
@endpush
