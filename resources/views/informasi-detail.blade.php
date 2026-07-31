@extends('layouts.app')
@section('content')

<div class="py-16" style="background: linear-gradient(135deg, #0c4a6e, #0284c7);">
    <div class="max-w-screen-xl mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm text-blue-200 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('informasi') }}" class="hover:text-white">Informasi Terkini</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">{{ Str::limit($informasi->judul, 40) }}</span>
        </nav>
        <h1 class="text-white font-extrabold text-3xl md:text-4xl leading-tight max-w-3xl">
            {{ $informasi->judul }}
        </h1>
        <div class="flex flex-wrap gap-4 mt-4 text-sm text-blue-200">
            <span><i class="fas fa-clock mr-1"></i>{{ $informasi->created_tm?->format('d M Y') }}</span>
            @if($informasi->updated_tm != $informasi->created_tm)
            <span><i class="fas fa-pen mr-1"></i>Diperbarui {{ $informasi->updated_tm?->format('d M Y') }}</span>
            @endif
        </div>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Konten Utama --}}
            <div class="lg:col-span-2 space-y-6">
                @if($informasi->gambar)
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ Storage::url($informasi->gambar) }}" alt="{{ $informasi->judul }}"
                         class="w-full object-cover max-h-80">
                </div>
                @else
                <div class="rounded-2xl h-52 flex items-center justify-center"
                     style="background: linear-gradient(135deg, #0c4a6e, #0284c7)">
                    <i class="fas fa-newspaper text-6xl text-white opacity-30"></i>
                </div>
                @endif

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="text-gray-600 leading-relaxed text-sm" style="line-height:1.9">
                        {!! nl2br(e($informasi->isi)) !!}
                    </div>
                </div>

                @if($informasi->thumbnail)
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ Storage::url($informasi->thumbnail) }}" alt="thumbnail" class="w-full object-cover">
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 mb-4">Informasi</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-calendar text-blue-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Dipublikasikan</p>
                                <p class="text-gray-800 font-semibold">{{ $informasi->created_tm?->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-tag text-blue-600 mt-0.5 w-4 flex-shrink-0"></i>
                            <div>
                                <p class="text-gray-400 text-xs font-semibold">Status</p>
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                    Publish
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 pt-4 border-t border-gray-100 space-y-2">
                        <a href="{{ route('portal.booking.create') }}"
                           class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-extrabold py-3 rounded-xl text-sm transition-colors">
                            <i class="fas fa-calendar-check mr-2"></i>Buat Janji Temu
                        </a>
                        <a href="{{ route('informasi') }}"
                           class="block w-full text-center border-2 border-blue-600 text-blue-700 hover:bg-blue-50 font-bold py-2.5 rounded-xl text-sm transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
                        </a>
                    </div>
                </div>

                @if($related->isNotEmpty())
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 mb-4">Info Lainnya</h3>
                    <div class="space-y-3">
                        @foreach($related as $r)
                        <a href="{{ route('informasi.detail', $r) }}"
                           class="flex gap-3 items-start hover:bg-gray-50 p-2 rounded-xl transition-colors group">
                            <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0"
                                 style="background: linear-gradient(135deg, #0c4a6e, #0284c7)">
                                @if($r->gambar)
                                <img src="{{ Storage::url($r->gambar) }}" class="w-full h-full object-cover">
                                @else
                                <div class="flex items-center justify-center h-full">
                                    <i class="fas fa-newspaper text-white opacity-50"></i>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 leading-snug group-hover:text-blue-600 truncate">
                                    {{ $r->judul }}
                                </p>
                                <p class="text-xs text-gray-400 font-semibold mt-0.5">
                                    {{ $r->created_tm?->format('d M Y') }}
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
