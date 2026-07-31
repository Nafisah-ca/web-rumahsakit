@extends('layouts.app')
@section('content')

<div class="py-16" style="background: linear-gradient(135deg, #4c1d95, #7c3aed);">
    <div class="max-w-screen-xl mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm text-purple-200 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('event') }}" class="hover:text-white">Event</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">{{ Str::limit($event->judul, 40) }}</span>
        </nav>
        <h1 class="text-white font-extrabold text-3xl md:text-4xl leading-tight max-w-3xl">
            {{ $event->judul }}
        </h1>
        <div class="flex flex-wrap gap-4 mt-4 text-sm text-purple-200">
            <span><i class="fas fa-calendar mr-1"></i>{{ $event->tanggal_event?->format('d M Y') }}</span>
            <span><i class="fas fa-clock mr-1"></i>{{ substr($event->waktu_event ?? '', 0, 5) }} WIB</span>
            @if($event->lokasi)
            <span><i class="fas fa-location-dot mr-1"></i>{{ $event->lokasi }}</span>
            @endif
        </div>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Konten Utama --}}
            <div class="lg:col-span-2 space-y-6">
                @if($event->gambar)
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ Storage::url($event->gambar) }}" alt="{{ $event->judul }}"
                         class="w-full object-cover max-h-80">
                </div>
                @else
                <div class="rounded-2xl h-52 flex items-center justify-center"
                     style="background: linear-gradient(135deg, #4c1d95, #7c3aed)">
                    <i class="fas fa-calendar-star text-6xl text-white opacity-30"></i>
                </div>
                @endif

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-extrabold text-gray-900 mb-4">Tentang Event</h2>
                    <div class="text-gray-600 leading-relaxed text-sm">
                        {!! nl2br(e($event->deskripsi)) !!}
                    </div>
                </div>

                @if($event->thumbnail)
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ Storage::url($event->thumbnail) }}" alt="thumbnail" class="w-full object-cover">
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 mb-4">Info Event</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-calendar-check text-purple-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Tanggal</p>
                                <p class="text-gray-800 font-semibold">{{ $event->tanggal_event?->format('l, d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-clock text-purple-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Waktu</p>
                                <p class="text-gray-800 font-semibold">{{ substr($event->waktu_event ?? '', 0, 5) }} WIB</p>
                            </div>
                        </div>
                        @if($event->lokasi)
                        <div class="flex items-start gap-3">
                            <i class="fas fa-location-dot text-purple-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Lokasi</p>
                                <p class="text-gray-800 font-semibold">{{ $event->lokasi }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="flex items-start gap-3">
                            <i class="fas fa-hourglass-half text-purple-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Status</p>
                                <p class="font-semibold {{ $event->tanggal_event?->isFuture() ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $event->tanggal_event?->isFuture() ? $event->tanggal_event->diffForHumans() : 'Sudah berlangsung' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('portal.booking.create') }}"
                           class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-extrabold py-3 rounded-xl text-sm transition-colors">
                            <i class="fas fa-calendar-check mr-2"></i>Buat Janji Temu
                        </a>
                        <a href="{{ route('kontak') }}"
                           class="block w-full text-center border-2 border-purple-600 text-purple-700 hover:bg-purple-50 font-bold py-2.5 rounded-xl text-sm transition-colors mt-2">
                            <i class="fas fa-phone mr-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>

                @if($related->isNotEmpty())
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 mb-4">Event Lainnya</h3>
                    <div class="space-y-3">
                        @foreach($related as $r)
                        <a href="{{ route('event.detail', $r) }}"
                           class="flex gap-3 items-start hover:bg-gray-50 p-2 rounded-xl transition-colors group">
                            <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0"
                                 style="background: linear-gradient(135deg, #4c1d95, #7c3aed)">
                                @if($r->gambar)
                                <img src="{{ Storage::url($r->gambar) }}" class="w-full h-full object-cover">
                                @else
                                <div class="flex items-center justify-center h-full">
                                    <i class="fas fa-calendar-star text-white opacity-50"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 leading-snug group-hover:text-purple-600 truncate">
                                    {{ $r->judul }}
                                </p>
                                <p class="text-xs text-purple-500 font-semibold mt-0.5">
                                    {{ $r->tanggal_event?->format('d M Y') }}
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
