{{--
    Partial: Page Hero Banner
    Props:
      $banner      — instance PageBanner
      $pageTitle   — override judul (opsional, default dari $banner->judul)
      $breadcrumbs — array [['label'=>'...','url'=>'...']] (opsional)
--}}
@php
    $heroJudul    = $pageTitle ?? $banner?->judul ?? 'Halaman';
    $heroLabel    = $banner?->label_atas ?? '';
    $heroSubjudul = $banner?->subjudul ?? '';
    $w1           = $banner?->warna_awal  ?? '#00521f';
    $w2           = $banner?->warna_akhir ?? '#00b04f';
    $gambarUrl    = $banner?->gambar_url;

    // Gradient atau gambar background
    $heroBg = $gambarUrl
        ? "background: linear-gradient(135deg, {$w1}cc, {$w2}99), url('{$gambarUrl}') center/cover no-repeat;"
        : "background: linear-gradient(135deg, {$w1}, {$w2});";
@endphp

<div class="py-16 relative overflow-hidden" style="{{ $heroBg }}">
    {{-- Decorative circles --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none opacity-10">
        <div class="absolute -top-10 -right-10 w-72 h-72 rounded-full bg-white"></div>
        <div class="absolute -bottom-16 -left-16 w-96 h-96 rounded-full bg-white"></div>
    </div>

    <div class="relative max-w-screen-xl mx-auto px-4 text-center">
        @if($heroLabel)
        <span class="text-green-200 text-xs font-black uppercase tracking-widest block mb-2">{{ $heroLabel }}</span>
        @endif

        <h1 class="text-white font-extrabold text-4xl mb-3">{{ $heroJudul }}</h1>

        @if($heroSubjudul)
        <p class="text-green-100 text-sm max-w-xl mx-auto">{{ $heroSubjudul }}</p>
        @endif

        {{-- Breadcrumb --}}
        @if(!empty($breadcrumbs))
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            @foreach($breadcrumbs as $i => $crumb)
                @if($i > 0)<i class="fas fa-chevron-right text-xs"></i>@endif
                @if(!empty($crumb['url']) && $i < count($breadcrumbs)-1)
                    <a href="{{ $crumb['url'] }}" class="hover:text-white transition-colors">{{ $crumb['label'] }}</a>
                @else
                    <span class="text-white font-semibold">{{ $crumb['label'] }}</span>
                @endif
            @endforeach
        </nav>
        @endif
    </div>
</div>
