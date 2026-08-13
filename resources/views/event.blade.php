@extends('layouts.app')
@section('content')

{{-- ===== HERO ===== --}}
<div class="py-16" style="background:linear-gradient(135deg,#4c1d95,#7c3aed)">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-purple-300 text-xs font-black uppercase tracking-widest block mb-2">Jadwal Kegiatan</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Event & Kegiatan</h1>
        <p class="text-purple-100 text-sm max-w-xl mx-auto">Ikuti event kesehatan dan edukasi dari RS Sari Sehat</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-purple-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Event</span>
        </nav>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- ===== EVENT MENDATANG ===== --}}
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-purple-600 rounded-full"></div>
                <h2 class="text-xl font-extrabold text-gray-900">Event Mendatang</h2>
                @if($eventsMendatang->total() > 0)
                <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $eventsMendatang->total() }} event
                </span>
                @endif
            </div>

            @if($eventsMendatang->isEmpty())
            <div class="text-center py-16 text-gray-400 bg-white rounded-2xl border border-gray-100">
                <i class="fas fa-calendar-days text-5xl opacity-20 block mb-4"></i>
                <p class="font-semibold">Belum ada event mendatang</p>
            </div>
            @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventsMendatang as $ev)
                @include('_partials.event-card', ['ev' => $ev, 'lewat' => false])
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">{{ $eventsMendatang->appends(request()->except('page_m'))->links() }}</div>
            @endif
        </div>

        {{-- ===== EVENT SUDAH LEWAT ===== --}}
        @if($eventsLewat->isNotEmpty())
        <div class="mt-12 pt-10 border-t border-gray-200">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-1 h-6 bg-gray-400 rounded-full"></div>
                <h2 class="text-xl font-extrabold text-gray-500">Event Sebelumnya</h2>
                <span class="bg-gray-100 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full">
                    {{ $eventsLewat->total() }} event
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($eventsLewat as $ev)
                @include('_partials.event-card', ['ev' => $ev, 'lewat' => true])
                @endforeach
            </div>
            <div class="mt-8 flex justify-center">{{ $eventsLewat->appends(request()->except('page_l'))->links() }}</div>
        </div>
        @endif

    </div>
</section>

@endsection
