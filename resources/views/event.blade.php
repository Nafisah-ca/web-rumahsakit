@extends('layouts.app')
@section('content')

<x-page-hero
    page="event"
    :breadcrumbs="[['Beranda','home'],['Event & Kegiatan',null]]"
/>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        @if($events->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-calendar-days text-5xl opacity-20 block mb-4"></i>
            <p class="font-semibold text-lg">Belum ada event mendatang</p>
        </div>
        @else

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($events as $ev)
            <a href="{{ route('event.detail', $ev) }}"
               class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">

                {{-- Gambar — tinggi tetap --}}
                <div class="relative flex-shrink-0" style="height:180px; background: linear-gradient(135deg,#4c1d95,#7c3aed)">
                    @if($ev->gambar)
                    <img src="{{ Storage::url($ev->gambar) }}" alt="{{ $ev->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @elseif($ev->thumbnail)
                    <img src="{{ Storage::url($ev->thumbnail) }}" alt="{{ $ev->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-calendar-star text-5xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-purple-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full">EVENT</span>
                    </div>
                    <div class="absolute bottom-3 right-3 bg-black/50 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-xl">
                        {{ $ev->tanggal_event?->format('d M Y') }}
                    </div>
                </div>

                {{-- Konten --}}
                <div class="flex flex-col flex-1 p-5">
                    <h3 class="font-extrabold text-gray-900 text-base leading-snug mb-2 group-hover:text-purple-600 transition-colors line-clamp-2">
                        {{ $ev->judul }}
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 flex-1">
                        {{ Str::limit(strip_tags($ev->deskripsi ?? ''), 120) }}
                    </p>
                    <div class="mt-4 space-y-1.5 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-days text-purple-500 w-3 flex-shrink-0"></i>
                            {{ $ev->tanggal_event?->format('d M Y') }}
                            @if($ev->waktu_event)
                            &nbsp;·&nbsp; {{ substr($ev->waktu_event, 0, 5) }} WIB
                            @endif
                        </div>
                        @if($ev->lokasi)
                        <div class="flex items-center gap-2">
                            <i class="fas fa-location-dot text-purple-500 w-3 flex-shrink-0"></i>
                            {{ $ev->lokasi }}
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-gray-100">
                        @if($ev->tanggal_event?->isFuture())
                        <span class="text-xs font-bold text-green-600">
                            <i class="fas fa-clock mr-1"></i>{{ $ev->tanggal_event->diffForHumans() }}
                        </span>
                        @else
                        <span class="text-xs text-gray-400">Sudah berlangsung</span>
                        @endif
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-purple-700">
                            Lihat Detail <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-10 flex justify-center">{{ $events->links() }}</div>
        @endif
    </div>
</section>
@endsection
