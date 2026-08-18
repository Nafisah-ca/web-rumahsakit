@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', [
    'banner'      => $banner ?? \App\Models\PageBanner::getForPage('dokter'),
    'pageTitle'   => isset($online) && $online
                        ? 'Layanan Online'
                        : (isset($modeDaftar) && $modeDaftar
                            ? 'Daftar Poliklinik — ' . ($activeSpesialisNama ?? 'Semua')
                            : 'Profil Dokter'),
    'breadcrumbs' => [
        ['label' => 'Beranda', 'url' => route('home')],
        ['label' => 'Dokter'],
    ],
])

{{-- Filter spesialisasi --}}
<div class="bg-white border-b border-gray-100 sticky top-16 z-40 shadow-sm">
    <div class="max-w-screen-xl mx-auto px-4 py-3">
        <div class="flex flex-wrap gap-2">
            <button onclick="window.location='{{ route('dokter') }}'"
                class="filter-dr-btn px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       {{ ($activeSpesialisSlug ?? null) == null ? 'bg-green-600 border-green-600 text-white' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600' }}">
                Semua
            </button>
            @foreach($spesialisasis as $sp)
            <button onclick="window.location='{{ route('dokter.by-spesialis', ['spSlug' => $sp->id]) }}'"
                class="filter-dr-btn px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       {{ ($activeSpesialisSlug ?? null) == $sp->id ? 'bg-green-600 border-green-600 text-white' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600' }}">
                {{ $sp->nama_spesialis }}
            </button>
            @endforeach
        </div>
    </div>
</div>

{{-- Info mode: Daftar Poliklinik vs Profil Dokter --}}
@if(isset($modeDaftar) && $modeDaftar && $activeSpesialisNama)
<div class="bg-green-50 border-b border-green-100">
    <div class="max-w-screen-xl mx-auto px-4 py-2.5 flex items-center gap-2">
        <i class="fas fa-info-circle text-green-600 text-xs"></i>
        <p class="text-xs text-green-700 font-semibold">
            Menampilkan dokter spesialis <strong>{{ $activeSpesialisNama }}</strong>
        </p>
    </div>
</div>
@endif

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        @if($dokterList->isEmpty())
        <div class="text-center py-20">
            <i class="fas fa-user-md text-gray-300 text-5xl mb-4 block"></i>
            <p class="text-gray-500 font-semibold">Belum ada dokter untuk kategori ini.</p>
        </div>
        @else

        {{-- Grup: Dokter Spesialis --}}
        @php
            $spesialisList = $dokterList->where('tipe_dokter', 'spesialis');
            $umumList      = $dokterList->whereIn('tipe_dokter', ['umum', 'lainnya']);
        @endphp

        @if($spesialisList->isNotEmpty())
        <div class="mb-10">
            @if($umumList->isNotEmpty())
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-green-600 rounded-full"></div>
                <h2 class="text-lg font-extrabold text-gray-900">Dokter Spesialis</h2>
                <span class="text-xs bg-green-100 text-green-700 font-bold px-2 py-0.5 rounded-full">{{ $spesialisList->count() }} dokter</span>
            </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($spesialisList as $d)
                    @include('_partials.dokter-card', ['d' => $d])
                @endforeach
            </div>
        </div>
        @endif

        {{-- Grup: Dokter Umum --}}
        @if($umumList->isNotEmpty())
        <div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-blue-500 rounded-full"></div>
                <h2 class="text-lg font-extrabold text-gray-900">Dokter Umum</h2>
                <span class="text-xs bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full">{{ $umumList->count() }} dokter</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($umumList as $d)
                    @include('_partials.dokter-card', ['d' => $d])
                @endforeach
            </div>
        </div>
        @endif

        @endif
    </div>
</section>
@endsection
