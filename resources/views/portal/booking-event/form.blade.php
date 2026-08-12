@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-xl mx-auto">

        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-purple-600">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('event') }}" class="hover:text-purple-600">Event</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('event.detail', $event) }}" class="hover:text-purple-600 truncate max-w-xs">{{ Str::limit($event->judul, 35) }}</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 font-semibold">Daftar</span>
        </nav>

        @if($existing)
        {{-- Sudah terdaftar --}}
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
            <i class="fas fa-circle-check text-4xl text-green-500 mb-3 block"></i>
            <h2 class="text-lg font-extrabold text-gray-900 mb-1">Anda Sudah Terdaftar</h2>
            <p class="text-sm text-gray-500 mb-4">Kode booking Anda: <span class="font-mono font-bold text-purple-700">{{ $existing->kode_booking }}</span></p>
            <a href="{{ route('portal.booking-event.riwayat') }}" class="inline-flex items-center gap-2 bg-purple-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-purple-700 transition-all">
                <i class="fas fa-list"></i> Lihat Pendaftaran Saya
            </a>
        </div>
        @else

        {{-- Flash messages --}}
        @foreach(['success','error','info'] as $type)
        @if(session($type))
        <div class="mb-4 p-4 rounded-2xl text-sm font-semibold
            {{ $type === 'success' ? 'bg-green-50 border border-green-200 text-green-700' :
               ($type === 'error'  ? 'bg-red-50 border border-red-200 text-red-700' :
                                     'bg-blue-50 border border-blue-200 text-blue-700') }}">
            <i class="fas fa-{{ $type === 'success' ? 'check-circle' : ($type === 'error' ? 'circle-xmark' : 'circle-info') }} mr-2"></i>
            {{ session($type) }}
        </div>
        @endif
        @endforeach

        {{-- Event Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            @if($event->gambar)
            <img src="{{ Storage::url($event->gambar) }}" class="w-full h-40 object-cover">
            @else
            <div class="w-full h-28 flex items-center justify-center" style="background:linear-gradient(135deg,#4c1d95,#7c3aed)">
                <i class="fas fa-calendar-star text-5xl text-white opacity-30"></i>
            </div>
            @endif
            <div class="p-5">
                <h2 class="font-extrabold text-gray-900 text-lg leading-snug">{{ $event->judul }}</h2>
                <div class="mt-3 space-y-2 text-sm text-gray-500">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-check text-purple-500 w-4"></i>
                        <span>{{ $event->tanggal_event?->translatedFormat('l, d M Y') }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-clock text-purple-500 w-4"></i>
                        <span>{{ substr($event->waktu_event ?? '', 0, 5) }} WIB</span>
                    </div>
                    @if($event->lokasi)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-location-dot text-purple-500 w-4"></i>
                        <span>{{ $event->lokasi }}</span>
                    </div>
                    @endif
                    @if($event->kuota)
                    <div class="flex items-center gap-2">
                        <i class="fas fa-users text-purple-500 w-4"></i>
                        @php $aktif = $event->pesertaAktif()->count(); @endphp
                        <span>{{ $aktif }} / {{ $event->kuota }} peserta terdaftar</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Pasien Info --}}
        <div class="bg-purple-50 border border-purple-100 rounded-2xl p-4 mb-5 flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-600 rounded-xl flex items-center justify-center text-white font-black flex-shrink-0">
                {{ strtoupper(substr($pasien->user?->nama ?? '?', 0, 1)) }}
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm">{{ $pasien->user?->nama ?? '-' }}</p>
                <p class="text-xs text-gray-400">No. RM: {{ $pasien->no_rekam_medis ?? '-' }}</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-extrabold text-gray-900 mb-4">Konfirmasi Pendaftaran</h3>

            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('portal.booking-event.store', $event) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                    </label>
                    <textarea name="catatan" rows="3" maxlength="500"
                              class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-100 resize-none"
                              placeholder="Pertanyaan, kebutuhan khusus, atau catatan lain...">{{ old('catatan') }}</textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-extrabold py-3 rounded-xl text-sm transition-colors">
                        <i class="fas fa-ticket mr-2"></i>Daftarkan Saya
                    </button>
                    <a href="{{ route('event.detail', $event) }}"
                       class="flex-1 text-center border-2 border-gray-200 text-gray-600 hover:border-gray-300 font-bold py-3 rounded-xl text-sm transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
        @endif

    </div>
</div>
@endsection
