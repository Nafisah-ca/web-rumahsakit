@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('artikel'), 'pageTitle' => $artikel->judul, 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Artikel',  'url' => route('artikel')],
    ['label' => Str::limit($artikel->judul, 40)],
]])
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
                    {{-- Render HTML dari TinyMCE --}}
                    <div class="prose prose-slate max-w-none" style="line-height:1.9;font-size:15px">
                        {!! $artikel->isi !!}
                    </div>
                </div>

                {{-- ===== TOMBOL SHARE ===== --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <p class="text-sm font-extrabold text-gray-700 mb-3"><i class="fas fa-share-nodes text-green-500 mr-2"></i>Bagikan Artikel</p>
                    <div class="flex flex-wrap gap-2">
                        {{-- WhatsApp --}}
                        <a href="https://wa.me/?text={{ urlencode($artikel->judul . ' - ' . request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-xs font-bold transition-all hover:-translate-y-0.5 hover:opacity-90"
                           style="background:#25d366">
                            <i class="fab fa-whatsapp text-sm"></i> WhatsApp
                        </a>
                        {{-- Facebook --}}
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-xs font-bold transition-all hover:-translate-y-0.5 hover:opacity-90"
                           style="background:#1877f2">
                            <i class="fab fa-facebook-f text-sm"></i> Facebook
                        </a>
                        {{-- X / Twitter --}}
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($artikel->judul) }}&url={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-xs font-bold transition-all hover:-translate-y-0.5 hover:opacity-90"
                           style="background:#000">
                            <i class="fab fa-x-twitter text-sm"></i> X
                        </a>
                        {{-- Telegram --}}
                        <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($artikel->judul) }}"
                           target="_blank" rel="noopener"
                           class="flex items-center gap-2 px-4 py-2 rounded-xl text-white text-xs font-bold transition-all hover:-translate-y-0.5 hover:opacity-90"
                           style="background:#229ed9">
                            <i class="fab fa-telegram text-sm"></i> Telegram
                        </a>
                        {{-- Copy Link --}}
                        <button id="btn-copy-link"
                                onclick="copyArtikelLink()"
                                class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold transition-all hover:-translate-y-0.5"
                                style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0">
                            <i class="fas fa-link text-sm"></i>
                            <span id="copy-label">Salin Link</span>
                        </button>
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
@push('scripts')
<script>
function copyArtikelLink() {
    const url  = '{{ request()->url() }}';
    const btn  = document.getElementById('btn-copy-link');
    const lbl  = document.getElementById('copy-label');
    navigator.clipboard.writeText(url).then(function () {
        lbl.textContent = 'Tersalin!';
        btn.style.background = '#dcfce7';
        btn.style.color      = '#16a34a';
        btn.style.borderColor= '#86efac';
        setTimeout(function () {
            lbl.textContent  = 'Salin Link';
            btn.style.background = '#f1f5f9';
            btn.style.color      = '#475569';
            btn.style.borderColor= '#e2e8f0';
        }, 2500);
    });
}
</script>
@endpush
