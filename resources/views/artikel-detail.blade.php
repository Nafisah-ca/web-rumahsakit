@extends('layouts.app')
@section('content')

<div class="py-16" style="background:linear-gradient(135deg,#1e3a5f,#0284c7)">
    <div class="max-w-screen-xl mx-auto px-4">
        <nav class="flex items-center gap-2 text-sm text-blue-200 mb-4">
            <a href="{{ route('home') }}" class="hover:text-white">Beranduy</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('artikel') }}" class="hover:text-white">Artikel</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">{{ Str::limit($artikel->judul, 40) }}</span>
        </nav>
        <h1 class="text-white font-extrabold text-3xl md:text-4xl leading-tight max-w-3xl">{{ $artikel->judul }}</h1>
        <div class="flex flex-wrap gap-4 mt-4 text-sm text-blue-200">
            @if($artikel->kategori)
            <span class="bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-full">{{ $artikel->kategori->nama_kategori }}</span>
            @endif
            <span><i class="fas fa-clock mr-1"></i>{{ $artikel->created_tm?->format('d M Y') }}</span>
            @if($artikel->penulis)
            <span><i class="fas fa-user mr-1"></i>{{ $artikel->penulis->nama }}</span>
            @endif
        </div>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                @if($artikel->gambar)
                <div class="rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ Storage::url($artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full object-cover max-h-80">
                </div>
                @else
                <div class="rounded-2xl h-48 flex items-center justify-center" style="background:linear-gradient(135deg,#1e3a5f,#0284c7)">
                    <i class="fas fa-newspaper text-6xl text-white opacity-20"></i>
                </div>
                @endif

                <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
                    <div class="text-gray-700 leading-relaxed" style="line-height:1.9">
                        {!! nl2br(e($artikel->isi)) !!}
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-extrabold text-gray-900 mb-4">Artikel Terkait</h3>
                    <div class="space-y-3">
                        @forelse($related as $rel)
                        <a href="{{ route('artikel.detail', $rel->slug) }}" class="flex gap-3 items-start hover:bg-gray-50 p-2 rounded-xl transition-colors group">
                            <div class="w-14 h-14 rounded-lg overflow-hidden flex-shrink-0" style="background:linear-gradient(135deg,#1e3a5f,#0284c7)">
                                @if($rel->gambar)
                                <img src="{{ Storage::url($rel->gambar) }}" class="w-full h-full object-cover">
                                @elseif($rel->thumbnail)
                                <img src="{{ Storage::url($rel->thumbnail) }}" class="w-full h-full object-cover">
                                @else
                                <div class="flex items-center justify-center h-full"><i class="fas fa-newspaper text-white opacity-50"></i></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-gray-900 leading-snug group-hover:text-blue-600 line-clamp-2">{{ $rel->judul }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $rel->created_tm?->format('d M Y') }}</p>
                            </div>
                        </a>
                        @empty
                        <p class="text-sm text-gray-400">Tidak ada artikel terkait.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl p-5 text-white">
                    <i class="fas fa-calendar-check text-green-300 text-2xl mb-3 block"></i>
                    <h4 class="font-extrabold mb-2">Konsultasi Dokter</h4>
                    <p class="text-green-200 text-sm mb-4">Buat janji temu dengan dokter spesialis kami.</p>
                    <a href="{{ route('portal.booking.create') }}" class="block w-full bg-white text-green-700 font-bold text-sm py-2.5 rounded-xl text-center hover:bg-green-50 transition-colors">
                        Buat Janji Temu
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
