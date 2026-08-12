@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">

        <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Pendaftaran Event Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Riwayat pendaftaran event yang pernah Anda ikuti</p>
            </div>
            <a href="{{ route('event') }}"
               class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm">
                <i class="fas fa-calendar-star"></i> Lihat Event
            </a>
        </div>

        {{-- Flash messages --}}
        @foreach(['success','error','info'] as $type)
        @if(session($type))
        <div class="mb-5 p-4 rounded-2xl text-sm font-semibold flex items-center gap-3
            {{ $type === 'success' ? 'bg-green-50 border border-green-200 text-green-700' :
               ($type === 'error'  ? 'bg-red-50 border border-red-200 text-red-700' :
                                     'bg-blue-50 border border-blue-200 text-blue-700') }}">
            <i class="fas fa-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'circle-xmark' : 'circle-info') }} flex-shrink-0"></i>
            {{ session($type) }}
        </div>
        @endif
        @endforeach

        {{-- Pasien Info --}}
        @if($pasien)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 flex items-center gap-4">
            <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center text-white text-xl font-black flex-shrink-0">
                {{ strtoupper(substr($pasien->user?->nama ?? '?', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-extrabold text-gray-900">{{ $pasien->user?->nama ?? '-' }}</p>
                <p class="text-gray-500 text-sm">No. RM: <span class="font-semibold text-purple-700">{{ $pasien->no_rekam_medis ?? '-' }}</span></p>
            </div>
            <a href="{{ route('portal.profil') }}" class="text-xs font-bold text-gray-400 hover:text-purple-600 transition-colors border border-gray-200 hover:border-purple-500 px-3 py-2 rounded-lg">
                <i class="fas fa-edit mr-1"></i>Edit Profil
            </a>
        </div>
        @endif

        {{-- Booking List --}}
        @forelse($bookings as $b)
        @php
            $statusConf = [
                'pending'   => ['Menunggu',     'bg-amber-100 text-amber-700 border-amber-200'],
                'confirmed' => ['Dikonfirmasi', 'bg-green-100 text-green-700 border-green-200'],
                'cancelled' => ['Dibatalkan',   'bg-red-100 text-red-700 border-red-200'],
            ];
            [$statusLabel, $statusClass] = $statusConf[$b->status] ?? [$b->status, 'bg-slate-100 text-slate-600 border-slate-200'];
            $eventLewat = $b->event?->tanggal_event?->isPast();
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg font-semibold">{{ $b->kode_booking }}</span>
                        <span class="badge border {{ $statusClass }} text-xs font-bold">{{ $statusLabel }}</span>
                        @if($eventLewat)
                        <span class="text-xs text-gray-400 font-semibold"><i class="fas fa-clock-rotate-left mr-1"></i>Sudah berlangsung</span>
                        @endif
                    </div>
                    <p class="font-extrabold text-gray-900 mb-2 leading-snug">{{ $b->event?->judul ?? '-' }}</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">Tanggal Event</p>
                            <p class="font-semibold text-gray-800">{{ $b->event?->tanggal_event?->format('d M Y') ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ substr($b->event?->waktu_event ?? '', 0, 5) }} WIB</p>
                        </div>
                        @if($b->event?->lokasi)
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">Lokasi</p>
                            <p class="font-semibold text-gray-800">{{ $b->event->lokasi }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">Tanggal Daftar</p>
                            <p class="font-semibold text-gray-800">{{ $b->created_tm?->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $b->created_tm?->format('H:i') }} WIB</p>
                        </div>
                    </div>
                    @if($b->catatan)
                    <div class="mt-3 p-2.5 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-400 font-semibold">Catatan:</p>
                        <p class="text-sm text-gray-700">{{ $b->catatan }}</p>
                    </div>
                    @endif
                </div>
                <div class="flex flex-col gap-2 flex-shrink-0">
                    <a href="{{ route('event.detail', $b->event) }}"
                       class="text-xs font-bold text-purple-600 hover:text-purple-800 border border-purple-200 hover:border-purple-400 px-3 py-2 rounded-xl transition-all text-center">
                        <i class="fas fa-eye mr-1"></i>Detail Event
                    </a>
                    @if(in_array($b->status, ['pending', 'confirmed']) && !$eventLewat)
                    <form method="POST" action="{{ route('portal.booking-event.cancel', $b) }}"
                          onsubmit="return confirm('Batalkan pendaftaran event ini?')">
                        @csrf
                        <button class="w-full text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 border border-red-200 px-3 py-2 rounded-xl transition-all">
                            <i class="fas fa-times mr-1"></i>Batalkan
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <i class="fas fa-calendar-times text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">Belum ada pendaftaran event</p>
            <p class="text-gray-400 text-sm mt-1">Temukan event menarik dan daftar sekarang</p>
            <a href="{{ route('event') }}" class="inline-flex items-center gap-2 mt-4 bg-purple-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-purple-700 transition-all">
                <i class="fas fa-calendar-star"></i> Lihat Event
            </a>
        </div>
        @endforelse

        <div class="mt-4">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
