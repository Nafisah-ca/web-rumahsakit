@extends('layouts.app')
@section('content')

{{-- Hero --}}
<div class="py-16" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm text-green-200 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('promo') }}" class="hover:text-white">Promo</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">{{ Str::limit($promo->judul, 40) }}</span>
        </nav>
        <h1 class="text-white font-extrabold text-3xl md:text-4xl leading-tight max-w-2xl">
            {{ $promo->judul }}
        </h1>
        <div class="flex flex-wrap gap-4 mt-4 text-sm text-green-200">
            <span><i class="fas fa-calendar mr-1"></i>Mulai: {{ $promo->tanggal_mulai?->format('d M Y') }}</span>
            <span><i class="fas fa-clock mr-1"></i>Berakhir: {{ $promo->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas' }}</span>
            @if($promo->status === 'aktif')
            <span class="bg-green-500 text-white px-2 py-0.5 rounded-full text-xs font-bold">Aktif</span>
            @endif
        </div>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Konten Utama --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Gambar --}}
                @if($promo->gambar)
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ Storage::url($promo->gambar) }}" alt="{{ $promo->judul }}"
                         class="w-full object-cover max-h-80">
                </div>
                @else
                <div class="rounded-2xl h-52 flex items-center justify-center"
                     style="background: linear-gradient(135deg, #00521f, #00b04f)">
                    <i class="fas fa-tag text-6xl text-white opacity-30"></i>
                </div>
                @endif

                {{-- Deskripsi --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-extrabold text-gray-900 mb-4">Detail Promo</h2>
                    <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                        {!! nl2br(e($promo->deskripsi)) !!}
                    </div>
                </div>

                {{-- Thumbnail --}}
                @if($promo->thumbnail)
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ Storage::url($promo->thumbnail) }}" alt="thumbnail"
                         class="w-full object-cover">
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                {{-- Info Promo --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 mb-4">Info Promo</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-calendar-check text-green-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Periode</p>
                                <p class="text-gray-800 font-semibold">
                                    {{ $promo->tanggal_mulai?->format('d M Y') }}
                                    — {{ $promo->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-hourglass-half text-red-500 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Sisa Waktu</p>
                                <p class="font-semibold {{ $promo->tanggal_selesai && $promo->tanggal_selesai->isPast() ? 'text-red-500' : 'text-green-600' }}">
                                    @if(!$promo->tanggal_selesai)
                                        Tidak terbatas
                                    @elseif($promo->tanggal_selesai->isPast())
                                        Promo telah berakhir
                                    @else
                                        {{ $promo->tanggal_selesai->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-circle-check text-green-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Status</p>
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold
                                    {{ $promo->status==='aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $promo->status==='aktif' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100">
                        <a href="{{ route('portal.booking.create') }}"
                           class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-extrabold py-3 rounded-xl text-sm transition-colors">
                            <i class="fas fa-calendar-check mr-2"></i>Daftar Sekarang
                        </a>
                        <a href="{{ route('kontak') }}"
                           class="block w-full text-center border-2 border-green-600 text-green-700 hover:bg-green-50 font-bold py-2.5 rounded-xl text-sm transition-colors mt-2">
                            <i class="fas fa-phone mr-2"></i>Hubungi Kami
                        </a>
                    </div>
                </div>

                {{-- Promo Lainnya --}}
                @if($related->isNotEmpty())
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 mb-4">Promo Lainnya</h3>
                    <div class="space-y-3">
                        @foreach($related as $r)
                        <a href="{{ route('promo.detail', $r) }}"
                           class="flex gap-3 items-start hover:bg-gray-50 p-2 rounded-xl transition-colors group">
                            <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0"
                                 style="background: linear-gradient(135deg, #00521f, #00b04f)">
                                @if($r->gambar)
                                <img src="{{ Storage::url($r->gambar) }}" class="w-full h-full object-cover">
                                @else
                                <div class="flex items-center justify-center h-full">
                                    <i class="fas fa-tag text-white opacity-50"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 leading-snug group-hover:text-green-600 truncate">
                                    {{ $r->judul }}
                                </p>
                                <p class="text-xs text-red-500 font-semibold mt-0.5">
                                    Berakhir: {{ $r->tanggal_selesai?->format('d M Y') }}
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
