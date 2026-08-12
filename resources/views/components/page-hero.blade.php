{{--
  Komponen Hero Banner dinamis dari CMS.

  Usage:
    <x-page-hero page="dokter" :breadcrumbs="[['Beranda','home'],['Dokter',null]]" />

  Override:
    <x-page-hero page="layanan" :override-judul="$activeKategoriNama ?? null" />
--}}
@props([
    'page',
    'breadcrumbs'       => [],
    'overrideJudul'     => null,
    'overrideLabel'     => null,
    'overrideDeskripsi' => null,
    'py'                => 'py-20',
])
@php
    $hero        = \App\Models\PageHero::forPage($page);
    $judulTampil = $overrideJudul     ?? $hero->judul;
    $labelTampil = $overrideLabel     ?? $hero->label;
    $deskTampil  = $overrideDeskripsi ?? $hero->deskripsi;
    $hasImage    = !empty($hero->gambar);
    $imgUrl      = $hasImage ? Storage::url($hero->gambar) : null;
    $warnaDari   = $hero->warna_dari ?? '#00521f';
    $warnaKe     = $hero->warna_ke   ?? '#00b04f';

    // Warna accent teks
    $h = strtolower($warnaDari);
    if (str_contains($h,'1e3a') || str_contains($h,'0284') || str_contains($h,'0c4a') || str_contains($h,'0369')) {
        $textAcc = 'text-blue-200';
    } elseif (str_contains($h,'4c1d') || str_contains($h,'7c3a')) {
        $textAcc = 'text-purple-200';
    } else {
        $textAcc = 'text-green-200';
    }
@endphp

<div class="{{ $py }} relative overflow-hidden"
     style="@if(!$hasImage)background: linear-gradient(135deg, {{ $warnaDari }}, {{ $warnaKe }});@endif">

    {{-- Layer 1: gambar background (hanya jika ada) --}}
    @if($hasImage)
    <div class="absolute inset-0 z-0"
         style="background: url('{{ $imgUrl }}') center/cover no-repeat;"></div>

    {{-- Layer 2: overlay gradient tipis agar teks tetap terbaca --}}
    <div class="absolute inset-0 z-10"
         style="background: linear-gradient(135deg, {{ $warnaDari }}a0, {{ $warnaKe }}88);"></div>
    @endif

    {{-- Layer 3: konten teks --}}
    <div class="relative z-20 max-w-screen-xl mx-auto px-4 text-center">

        @if($labelTampil)
        <span class="{{ $textAcc }} text-xs font-black uppercase tracking-widest block mb-2 drop-shadow">
            {{ $labelTampil }}
        </span>
        @endif

        <h1 class="text-white font-extrabold text-4xl mb-3 drop-shadow-lg">
            {{ $judulTampil }}
        </h1>

        @if($deskTampil)
        <p class="text-white/90 text-sm max-w-xl mx-auto drop-shadow">
            {{ $deskTampil }}
        </p>
        @endif

        {{-- Breadcrumb --}}
        @if(!empty($breadcrumbs))
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-white/80">
            @foreach(array_values(array_filter($breadcrumbs)) as $i => $crumb)
                @if($i > 0)<i class="fas fa-chevron-right text-xs opacity-60"></i>@endif
                @if(!empty($crumb[1]))
                <a href="{{ route($crumb[1]) }}" class="hover:text-white transition-colors">{{ $crumb[0] }}</a>
                @else
                <span class="text-white font-semibold">{{ $crumb[0] }}</span>
                @endif
            @endforeach
        </nav>
        @endif

        {{-- Slot tambahan (opsional) --}}
        @if(isset($slot) && $slot->isNotEmpty())
        <div class="mt-6">{{ $slot }}</div>
        @endif
    </div>
</div>
