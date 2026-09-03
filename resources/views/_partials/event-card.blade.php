<a href="{{ route('event.detail', $ev) }}"
   class="event-card group flex flex-col h-full bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm {{ $lewat ? 'opacity-70 hover:opacity-100' : '' }}"
   data-index="{{ $index ?? 0 }}">

    {{-- Gambar --}}
    <div class="relative flex-shrink-0 overflow-hidden" style="height:180px; background: linear-gradient(135deg,#4c1d95,#7c3aed)">
        @if($ev->gambar)
        <img src="{{ Storage::url($ev->gambar) }}" alt="{{ $ev->judul }}"
             class="event-img absolute inset-0 w-full h-full object-cover {{ $lewat ? 'grayscale' : '' }}">
        @elseif($ev->thumbnail)
        <img src="{{ Storage::url($ev->thumbnail) }}" alt="{{ $ev->judul }}"
             class="event-img absolute inset-0 w-full h-full object-cover {{ $lewat ? 'grayscale' : '' }}">
        @else
        <div class="absolute inset-0 flex items-center justify-center">
            <i class="fas fa-calendar-star text-5xl text-white opacity-20"></i>
        </div>
        @endif

        {{-- Efek Shine / Kilap saat hover --}}
        <div class="event-shine"></div>

        {{-- Badge status --}}
        <div class="absolute top-3 left-3 z-10">
            @if($lewat)
            <span class="bg-gray-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-sm">SELESAI</span>
            @else
            <span class="bg-purple-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full shadow-sm">EVENT</span>
            @endif
        </div>

        {{-- Tanggal --}}
        <div class="absolute top-3 right-3 z-10 flex items-center gap-1.5 bg-white text-gray-800 text-xs font-black px-2.5 py-1.5 rounded-lg shadow-md">
            <i class="fas fa-calendar-alt text-purple-600 text-[11px]"></i>
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
        <div class="mt-auto">
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
            @if($lewat)
            <span class="text-xs text-gray-400">Sudah berlangsung</span>
            @else
            <span class="text-xs font-bold text-green-600">
                <i class="fas fa-clock mr-1"></i>{{ $ev->tanggal_event->diffForHumans() }}
            </span>
            @endif
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700">
                Lihat Detail <i class="fas fa-arrow-right text-[10px] event-arrow"></i>
            </span>
        </div>
        </div>
    </div>
</a>
