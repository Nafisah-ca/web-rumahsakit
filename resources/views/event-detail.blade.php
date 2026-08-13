@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('event'), 'pageTitle' => $event->judul, 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Event',   'url' => route('event')],
    ['label' => Str::limit($event->judul, 40)],
]])

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
                        @php
                            $sudahBooking = false;
                            $kuotaPenuh   = $event->kuota_penuh;
                            $eventLewat   = $event->tanggal_event?->isPast();
                            if (auth()->check() && auth()->user()->pasien) {
                                $sudahBooking = \App\Models\BookingEvent::where('event_id', $event->id)
                                    ->where('pasien_id', auth()->user()->pasien->id)
                                    ->whereIn('status', ['pending','confirmed'])
                                    ->exists();
                            }
                        @endphp

                        @if($eventLewat)
                            <div class="w-full text-center bg-gray-100 text-gray-400 font-bold py-3 rounded-xl text-sm">
                                <i class="fas fa-calendar-xmark mr-2"></i>Event Sudah Berlangsung
                            </div>
                        @elseif($sudahBooking)
                            <div class="w-full text-center bg-green-50 text-green-700 border border-green-200 font-bold py-3 rounded-xl text-sm">
                                <i class="fas fa-circle-check mr-2"></i>Anda Sudah Terdaftar
                            </div>
                            <a href="{{ route('portal.booking-event.riwayat') }}"
                               class="block w-full text-center border-2 border-purple-600 text-purple-700 hover:bg-purple-50 font-bold py-2.5 rounded-xl text-sm transition-colors mt-2">
                                <i class="fas fa-list mr-2"></i>Lihat Pendaftaran Saya
                            </a>
                        @elseif($kuotaPenuh)
                            <div class="w-full text-center bg-red-50 text-red-500 border border-red-200 font-bold py-3 rounded-xl text-sm">
                                <i class="fas fa-users-slash mr-2"></i>Kuota Penuh
                            </div>
                        @elseif(auth()->check())
                            <a href="{{ route('portal.booking-event.create', $event) }}"
                               class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-extrabold py-3 rounded-xl text-sm transition-colors">
                                <i class="fas fa-ticket mr-2"></i>Daftar ke Event Ini
                            </a>
                        @else
                            <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                               class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-extrabold py-3 rounded-xl text-sm transition-colors">
                                <i class="fas fa-ticket mr-2"></i>Daftar ke Event Ini
                            </a>
                            <p class="text-center text-xs text-gray-400 mt-2">Masuk terlebih dahulu untuk mendaftar</p>
                        @endif

                        @if($event->kuota && !$eventLewat)
                        <div class="mt-3 text-center text-xs text-gray-400">
                            @php $aktif = $event->pesertaAktif()->count(); @endphp
                            <span>{{ $aktif }} / {{ $event->kuota }} peserta terdaftar</span>
                            <div class="mt-1.5 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $kuotaPenuh ? 'bg-red-500' : 'bg-purple-500' }}"
                                     style="width:{{ min(100, round($aktif / $event->kuota * 100)) }}%"></div>
                            </div>
                        </div>
                        @endif

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
