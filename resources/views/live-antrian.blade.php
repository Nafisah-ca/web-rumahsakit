@extends('layouts.app')
@section('content')

{{-- Auto refresh hanya jika tanggal = hari ini --}}
@if($tanggalStr === now()->toDateString())
<meta http-equiv="refresh" content="{{ $interval }}">
@endif

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => \App\Models\PageBanner::getForPage('live-antrian'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Live Antrian'],
]])

<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Header + Filter Tanggal --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-extrabold text-gray-900 text-lg flex items-center gap-2">
                        <i class="fas fa-list-ol text-green-600"></i>
                        Live Antrian
                        @if($tanggalStr === now()->toDateString())
                            <span class="text-sm font-normal text-green-600">— Hari Ini</span>
                        @elseif($tanggalStr === now()->addDay()->toDateString())
                            <span class="text-sm font-normal text-blue-600">— Besok</span>
                        @else
                            <span class="text-sm font-normal text-gray-500">— {{ $tanggalObj->translatedFormat('d F Y') }}</span>
                        @endif
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $tanggalObj->translatedFormat('l, d F Y') }}
                        @if($tanggalStr === now()->toDateString())
                            &bull; Diperbarui otomatis setiap {{ $interval }} detik
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    @if($tanggalStr === now()->toDateString())
                    <span class="flex items-center gap-1.5 text-xs font-semibold text-green-600">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                        Live
                    </span>
                    @endif
                </div>
            </div>

            {{-- Filter Tanggal --}}
            <div class="mt-5 pt-5 border-t border-gray-100">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Pilih Tanggal Antrian</p>
                <div class="flex flex-wrap gap-2 items-center">
                    {{-- Shortcut: Hari Ini & Besok --}}
                    @php
                        $today    = now()->toDateString();
                        $tomorrow = now()->addDay()->toDateString();
                    @endphp
                    <a href="{{ route('live.antrian', ['tanggal' => $today]) }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                        {{ $tanggalStr === $today ? 'bg-green-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <i class="fas fa-calendar-day mr-1.5"></i> Hari Ini
                    </a>
                    <a href="{{ route('live.antrian', ['tanggal' => $tomorrow]) }}"
                       class="px-4 py-2 rounded-xl text-sm font-semibold transition-all
                        {{ $tanggalStr === $tomorrow ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                        <i class="fas fa-calendar-plus mr-1.5"></i> Besok
                    </a>

                    {{-- Input tanggal bebas --}}
                    <form method="GET" action="{{ route('live.antrian') }}" class="flex items-center gap-2">
                        <input type="date" name="tanggal"
                               value="{{ $tanggalStr }}"
                               min="{{ now()->toDateString() }}"
                               class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400 focus:border-transparent bg-white">
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-semibold transition-all">
                            <i class="fas fa-search mr-1"></i> Lihat
                        </button>
                    </form>
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

                {{-- Selalu tampilkan data antrian, buka atau tutup --}}
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <div class="text-2xl font-black {{ $buka ? 'text-green-700' : 'text-gray-400' }}">
                            {{ $poli['total_antrian'] }}
                        </div>
                        <div class="text-xs text-gray-500 font-medium">Total Antrian</div>
                    </div>
                    <div class="{{ $buka ? 'bg-green-50' : 'bg-gray-50' }} rounded-xl p-3 text-center">
                        <div class="text-2xl font-black {{ $buka ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $poli['nomor_dipanggil'] }}
                        </div>
                        <div class="text-xs text-gray-500 font-medium">Nomor Dipanggil</div>
                    </div>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 {{ $buka ? 'bg-yellow-50 border-yellow-100' : 'bg-gray-50 border-gray-100' }} rounded-lg px-3 py-2 border">
                    <i class="fas fa-clock {{ $buka ? 'text-yellow-500' : 'text-gray-400' }}"></i>
                    <span>Estimasi tunggu: <strong class="{{ $buka ? 'text-yellow-700' : 'text-gray-400' }}">{{ $poli['estimasi'] }}</strong></span>
                </div>

                @if(!$buka)
                <div class="mt-3 text-center">
                    <p class="text-gray-400 text-xs"><i class="fas fa-info-circle mr-1"></i>Tidak ada jadwal dokter pada hari ini</p>
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

        {{-- Footer Info --}}
        <div class="mt-6 flex flex-wrap items-center justify-between gap-3 bg-white rounded-xl px-5 py-3 border border-gray-100 shadow-sm">
            <p class="text-xs text-gray-500 italic">
                <i class="fas fa-info-circle text-yellow-500 mr-1"></i>
                {{ $pesanTunggu }}
            </p>
            <div class="flex items-center gap-3">
                @if($tanggalStr === now()->toDateString())
                <span class="flex items-center gap-1.5 text-xs text-gray-400">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                    Data diperbarui otomatis
                </span>
                @endif
                <a href="{{ route('live.antrian', ['tanggal' => $tanggalStr]) }}"
                   class="flex items-center gap-1.5 text-green-600 text-xs font-bold hover:text-green-800 transition-colors">
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
