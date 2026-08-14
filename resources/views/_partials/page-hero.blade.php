{{--
    Partial: Page Hero Banner
    Props:
      $banner      — instance PageBanner (dari getForPage())
      $pageTitle   — override judul (opsional)
      $breadcrumbs — array [['label'=>'...','url'=>'...']] (opsional)
--}}
@php
    $heroJudul = $pageTitle ?? $banner?->judul ?? '';

    $hasBanner = $banner && $banner->gambar;

    if ($hasBanner) {
        $gambarUrl = $banner->gambar_url;

        $bgStyle = "background-image: url('" . $gambarUrl . "');";
    } else {
        $w1 = $banner?->warna_awal ?? '#00521f';
        $w2 = $banner?->warna_akhir ?? '#00b04f';

        $bgStyle = "background: linear-gradient(135deg, {$w1}, {$w2});";
    }
@endphp

<div class="py-16 relative overflow-hidden" style="{{ $bgStyle }}"></div>
    {{--
        Kalau ada gambar: overlay hitam tipis dari kiri (gelap) ke kanan (terang)
        persis seperti slider homepage — gambar tetap keliatan, teks tetap terbaca
    --}}
    @if($hasBanner)
    <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.25) 55%, rgba(0,0,0,0.10) 100%)"></div>
    @endif

    <div class="relative max-w-screen-xl mx-auto px-4 text-center">
        @if(!empty($banner->label_atas))
        <span class="text-xs font-black uppercase tracking-widest block mb-2" style="color:rgba(255,255,255,0.75)">
            {{ $banner->label_atas }}
        </span>
        @endif

        <h1 class="text-white font-extrabold text-4xl mb-3">{{ $heroJudul }}</h1>

        @if(!empty($banner->subjudul))
        <p class="text-sm max-w-xl mx-auto" style="color:rgba(255,255,255,0.8)">{{ $banner->subjudul }}</p>
        @endif

        @if(!empty($breadcrumbs))
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm" style="color:rgba(255,255,255,0.7)">
            @foreach($breadcrumbs as $i => $crumb)
                @if($i > 0)<i class="fas fa-chevron-right text-xs"></i>@endif
                @if(!empty($crumb['url']) && $i < count($breadcrumbs) - 1)
                    <a href="{{ $crumb['url'] }}" class="hover:text-white transition-colors">{{ $crumb['label'] }}</a>
                @else
                    <span class="text-white font-semibold">{{ $crumb['label'] }}</span>
                @endif
            @endforeach
        </nav>
        @endif
    </div>
</div>
