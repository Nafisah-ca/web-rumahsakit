@extends('layouts.app')
@section('content')

<div class="py-16" style="background: linear-gradient(135deg, #0c4a6e, #0284c7);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-blue-300 text-xs font-black uppercase tracking-widest block mb-2">Berita & Info</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Informasi Terkini</h1>
        <p class="text-blue-100 text-sm max-w-xl mx-auto">Informasi seputar kesehatan dan layanan RS Sari Sehat</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-blue-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Informasi Terkini</span>
        </nav>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        @if($informasis->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-newspaper text-5xl opacity-20 block mb-4"></i>
            <p class="font-semibold text-lg">Belum ada informasi terkini</p>
        </div>
        @else

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($informasis as $info)
            <a href="{{ route('informasi.detail', $info) }}"
               class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">

                {{-- Gambar — tinggi tetap --}}
                <div class="relative flex-shrink-0" style="height:180px; background: linear-gradient(135deg,#0c4a6e,#0284c7)">
                    @if($info->gambar)
                    <img src="{{ Storage::url($info->gambar) }}" alt="{{ $info->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @elseif($info->thumbnail)
                    <img src="{{ Storage::url($info->thumbnail) }}" alt="{{ $info->judul }}"
                         class="absolute inset-0 w-full h-full object-cover">
                    @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-newspaper text-5xl text-white opacity-20"></i>
                    </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-blue-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full">INFO</span>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-14"
                         style="background: linear-gradient(to top, rgba(0,0,0,0.3), transparent)">
                    </div>
                </div>

                {{-- Konten --}}
                <div class="flex flex-col flex-1 p-5">
                    <h3 class="font-extrabold text-gray-900 text-base leading-snug mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                        {{ $info->judul }}
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 flex-1">
                        {{ Str::limit(strip_tags($info->isi ?? ''), 120) }}
                    </p>
                    <div class="flex items-center justify-between pt-3 mt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-400 flex items-center gap-1">
                            <i class="fas fa-clock"></i>
                            {{ $info->created_tm?->format('d M Y') }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-blue-700">
                            Selengkapnya <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-10 flex justify-center">{{ $informasis->links() }}</div>
        @endif
    </div>
</section>
@endsection
