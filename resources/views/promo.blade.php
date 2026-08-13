@extends('layouts.app')
@section('content')

{{-- Hero --}}
<div class="py-16" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Penawaran Terbaik</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Promo & Penawaran Spesial</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Dapatkan layanan kesehatan terbaik dengan harga terjangkau</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Promo</span>
        </nav>
    </div>
</div>

{{-- List Promo --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        @if($promos->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-tag text-5xl opacity-20 block mb-4"></i>
            <p class="font-semibold text-lg">Belum ada promo tersedia</p>
        </div>
        @else

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($promos as $p)
            <a href="{{ route('promo.detail', $p) }}"
               class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">

                {{-- Gambar — tinggi tetap --}}
                <div class="relative flex-shrink-0" style="height:180px; background: linear-gradient(135deg,#00521f,#00b04f)">
                    @if($p->gambar)
                    <img src="{{ Storage::url($p->gambar) }}" alt="{{ $p->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @elseif($p->thumbnail)
                    <img src="{{ Storage::url($p->thumbnail) }}" alt="{{ $p->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-tag text-5xl text-white opacity-20"></i>
                    </div>
                    @endif
                    {{-- Badge --}}
                    <div class="absolute top-3 left-3">
                        <span class="bg-green-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full tracking-wide">PROMO</span>
                    </div>
                    {{-- Overlay gradient bawah --}}
                    <div class="absolute bottom-0 left-0 right-0 h-16"
                         style="background: linear-gradient(to top, rgba(0,0,0,0.35), transparent)">
                        <div class="absolute bottom-3 left-4 text-white text-xs font-bold">Selengkapnya →</div>
                    </div>
                </div>

                {{-- Konten — flex-1 agar semua card sama tinggi --}}
                <div class="flex flex-col flex-1 p-5">
                    <h3 class="font-extrabold text-gray-900 text-base leading-snug mb-2 group-hover:text-green-600 transition-colors line-clamp-2">
                        {{ $p->judul }}
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 flex-1">
                        {{ Str::limit(strip_tags($p->deskripsi ?? ''), 120) }}
                    </p>
                    <div class="flex items-center justify-between pt-3 mt-4 border-t border-gray-100">
                        <div class="flex items-center gap-1.5 text-xs text-red-500 font-semibold">
                            <i class="fas fa-clock"></i>
                            Berakhir: {{ $p->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas' }}
                        </div>
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                            Lihat Detail <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10 flex justify-center">
            {{ $promos->links() }}
        </div>

        @endif
    </div>
</section>
@endsection
