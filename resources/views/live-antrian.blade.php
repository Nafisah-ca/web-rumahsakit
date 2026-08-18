@extends('layouts.app')
@section('content')

{{-- Auto refresh halaman setiap N detik --}}
<meta http-equiv="refresh" content="{{ $interval }}">

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => \App\Models\PageBanner::getForPage('live-antrian'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Live Antrian'],
]])

<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Header --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-gray-900 text-lg flex items-center gap-2">
                        <i class="fas fa-list-ol text-green-600"></i> Live Antrian Hari Ini
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ now()->translatedFormat('l, d F Y') }}
                        &bull; Halaman diperbarui otomatis setiap {{ $interval }} detik
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                    <span class="text-xs font-semibold text-green-600">Live</span>
                </div>
            </div>
        </div>

        {{-- Grid Poli --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($poliData as $poli)
            @php
                $w    = $poli['warna'] ?? 'blue';
                $buka = $poli['status'] === 'Buka';
            @endphp
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 icon-bg-{{ $w }}">
                            <i class="fas {{ $poli['icon'] }} text-sm icon-clr-{{ $w }}"></i>
                        </div>
                        <h3 class="font-bold text-gray-900 text-sm">{{ $poli['nama'] }}</h3>
                    </div>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $buka ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $poli['status'] }}
                    </span>
                </div>

                @if($buka)
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <div class="text-2xl font-black text-green-700">{{ $poli['total_antrian'] }}</div>
                        <div class="text-xs text-gray-500 font-medium">Total Antrian</div>
                    </div>
                    <div class="bg-green-50 rounded-xl p-3 text-center">
                        <div class="text-2xl font-black text-green-600">{{ $poli['nomor_dipanggil'] }}</div>
                        <div class="text-xs text-gray-500 font-medium">Nomor Dipanggil</div>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 bg-yellow-50 rounded-lg px-3 py-2 border border-yellow-100">
                    <i class="fas fa-clock text-yellow-500"></i>
                    <span>Estimasi tunggu: <strong class="text-yellow-700">{{ $poli['estimasi'] }}</strong></span>
                </div>
                @else
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <i class="fas fa-door-closed text-gray-300 text-2xl mb-2 block"></i>
                    <p class="text-gray-400 text-xs">Poliklinik sedang tutup</p>
                </div>
                @endif
            </div>
            @empty
            <div class="col-span-3 text-center py-16 text-gray-400">
                <i class="fas fa-hospital text-5xl mb-4 block"></i>
                <p class="text-lg font-semibold">Belum ada poli terdaftar</p>
                <p class="text-sm">Data poli diambil dari daftar spesialisasi yang dikelola admin.</p>
            </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 bg-white rounded-xl px-5 py-3 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 italic">
                <i class="fas fa-info-circle text-yellow-500 mr-1"></i>
                {{ $pesanTunggu }}
            </p>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1.5 text-xs text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                    Data diperbarui secara berkala
                </span>
                <a href="{{ route('live.antrian') }}" class="flex items-center gap-1.5 text-green-600 text-xs font-bold hover:text-green-800 transition-colors">
                    <i class="fas fa-sync-alt"></i> Refresh
                </a>
            </div>
        </div>

    </div>
</section>

@endsection
@push('styles')
<style>
.icon-bg-blue   { background:#dbeafe } .icon-clr-blue   { color:#1d4ed8 }
.icon-bg-green  { background:#dcfce7 } .icon-clr-green  { color:#15803d }
.icon-bg-red    { background:#fee2e2 } .icon-clr-red    { color:#b91c1c }
.icon-bg-indigo { background:#e0e7ff } .icon-clr-indigo { color:#4338ca }
.icon-bg-purple { background:#f3e8ff } .icon-clr-purple { color:#7e22ce }
.icon-bg-orange { background:#ffedd5 } .icon-clr-orange { color:#c2410c }
.icon-bg-pink   { background:#fce7f3 } .icon-clr-pink   { color:#be185d }
.icon-bg-teal   { background:#ccfbf1 } .icon-clr-teal   { color:#0f766e }
.icon-bg-yellow { background:#fef9c3 } .icon-clr-yellow { color:#a16207 }
.icon-bg-gray   { background:#f1f5f9 } .icon-clr-gray   { color:#475569 }
</style>
@endpush
