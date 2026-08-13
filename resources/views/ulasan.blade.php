@extends('layouts.app')
@section('content')

@push('styles')
<style>
/* Animasi bounce saat hover/klik button filter */
.filter-btn {
    transition: transform .15s cubic-bezier(.34,1.56,.64,1), box-shadow .15s ease, background .15s ease, border-color .15s ease, color .15s ease;
}
.filter-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,.1);
}
.filter-btn:active {
    transform: translateY(-1px) scale(.97);
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
}

/* Hover card ulasan */
.ulasan-card {
    transition: transform .2s ease, box-shadow .2s ease;
}
.ulasan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,.09);
}
</style>
@endpush

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('ulasan'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Ulasan Pasien'],
]])

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Summary + Filter --}}
        <div class="flex flex-col md:flex-row gap-6 mb-10">

            {{-- Rating summary --}}
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-6 md:w-80 flex-shrink-0">
                <div class="text-center flex-shrink-0">
                    <div class="text-5xl font-black text-green-600">{{ number_format($avgRating ?? 0, 1) }}</div>
                    <div class="flex justify-center gap-0.5 my-1.5">
                        @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-sm {{ $i <= round($avgRating ?? 0) ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                    <div class="text-xs text-gray-500">{{ $totalUlasan }} ulasan</div>
                </div>
                <div class="flex-1">
                    @for($star = 5; $star >= 1; $star--)
                    @php $count = $ratingCounts[$star] ?? 0; $pct = $totalUlasan > 0 ? ($count/$totalUlasan)*100 : 0; @endphp
                    <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-xs text-gray-500 w-3">{{ $star }}</span>
                        <i class="fas fa-star text-yellow-400 text-xs"></i>
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                            <div class="bg-yellow-400 h-1.5 rounded-full" style="width:{{ $pct }}%"></div>
                        </div>
                        <span class="text-xs text-gray-400 w-5">{{ $count }}</span>
                    </div>
                    @endfor
                </div>
            </div>

            {{-- Filter + CTA --}}
            <div class="flex-1 flex flex-col justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Filter Berdasarkan Rating</p>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('ulasan.public') }}"
                           class="filter-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold border
                                  {{ !$rating ? 'bg-green-600 text-white border-green-600 shadow-md' : 'bg-white text-gray-600 border-gray-200' }}">
                            Semua
                        </a>
                        @foreach([5,4,3,2,1] as $r)
                        <a href="{{ route('ulasan.public', ['rating' => $r]) }}"
                           class="filter-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold border
                                  {{ $rating == $r ? 'bg-yellow-400 text-yellow-900 border-yellow-400 shadow-md' : 'bg-white text-gray-600 border-gray-200' }}">
                            <i class="fas fa-star text-xs text-yellow-500"></i>
                            {{ $r }} Bintang
                            <span class="text-[10px] opacity-60">({{ $ratingCounts[$r] ?? 0 }})</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <a href="{{ route('kontak') }}#ulasan-form"
                       class="filter-btn inline-flex items-center gap-2 bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm border border-green-600 shadow-md">
                        <i class="fas fa-star"></i> Tulis Ulasan Anda
                    </a>
                </div>
            </div>
        </div>

        {{-- Grid ulasan --}}
        <div id="ulasan-grid">
            @if($ulasans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                @foreach($ulasans as $u)
                <div class="ulasan-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex flex-col">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <span class="text-green-700 font-black text-base">{{ strtoupper(substr($u->nama, 0, 1)) }}</span>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900 text-sm">{{ $u->nama }}</p>
                                <p class="text-gray-400 text-xs">{{ $u->created_tm?->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex gap-0.5 flex-shrink-0">
                            @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= $u->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @if($u->judul)
                    <p class="font-bold text-gray-800 text-sm mb-1.5">{{ $u->judul }}</p>
                    @endif
                    <p class="text-gray-600 text-xs leading-relaxed flex-1">{{ $u->isi }}</p>
                </div>
                @endforeach
            </div>
            <div class="flex justify-center">
                {{ $ulasans->links() }}
            </div>
            @else
            <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
                <i class="fas fa-star text-5xl text-gray-200 block mb-4"></i>
                <p class="text-gray-500 font-semibold">
                    Belum ada ulasan
                    @if($rating) dengan rating {{ $rating }} bintang @endif
                </p>
                <a href="{{ route('kontak') }}#ulasan-form"
                   class="filter-btn mt-4 inline-flex items-center gap-2 text-green-600 font-bold text-sm border border-transparent">
                    Jadilah yang pertama menulis ulasan <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            @endif
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Scroll ke grid setelah filter redirect (pakai sessionStorage)
    document.querySelectorAll('.filter-btn[href*="ulasan"]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (this.href && this.href.includes('/ulasan')) {
                sessionStorage.setItem('scrollToGrid', '1');
            }
        });
    });

    if (sessionStorage.getItem('scrollToGrid')) {
        sessionStorage.removeItem('scrollToGrid');
        setTimeout(function () {
            const grid = document.getElementById('ulasan-grid');
            if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 150);
    }
});
</script>
@endpush
