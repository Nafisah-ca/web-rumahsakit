@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('artikel'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Artikel'],
]])

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="artikel-layout">
            <div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px">
                    @forelse($articles as $art)
                    <a href="{{ route('artikel.detail',$art->slug) }}" class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all block group">
                        <div style="height:160px;overflow:hidden;background:linear-gradient(135deg,#00521f,#00b04f)">
                            @if($art->gambar)
                            <img src="{{ Storage::url($art->gambar) }}" alt="{{ $art->judul }}" style="width:100%;height:100%;object-fit:cover">
                            @elseif($art->thumbnail)
                            <img src="{{ Storage::url($art->thumbnail) }}" alt="{{ $art->judul }}" style="width:100%;height:100%;object-fit:cover">
                            @else
                            <div style="height:100%;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-newspaper text-white opacity-30" style="font-size:40px"></i>
                            </div>
                            @endif
                        </div>
                        <div style="padding:16px">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                                @if($art->kategori)
                                <span style="font-size:10px;font-weight:700;color:#fff;background:#2563eb;padding:2px 8px;border-radius:20px">{{ $art->kategori->nama_kategori }}</span>
                                @endif
                                <span style="font-size:11px;color:#94a3b8">{{ $art->created_tm?->format('d M Y') }}</span>
                            </div>
                            <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden" class="group-hover:text-blue-600 transition-colors">{{ $art->judul }}</h3>
                            <p style="font-size:12px;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ Str::limit(strip_tags($art->isi), 100) }}</p>
                            <div style="margin-top:10px;font-size:12px;font-weight:700;color:#2563eb">Baca Selengkapnya →</div>
                        </div>
                    </a>
                    @empty
                    <div style="grid-column:1/-1;text-align:center;padding:60px;color:#94a3b8">
                        <i class="fas fa-newspaper" style="font-size:40px;opacity:.2;display:block;margin-bottom:10px"></i>
                        <p>Belum ada artikel</p>
                    </div>
                    @endforelse
                </div>
                <div style="margin-top:32px">{{ $articles->links() }}</div>
            </div>

            <div class="artikel-sidebar">
                <div style="background:#fff;border-radius:16px;border:1px solid #f1f5f9;padding:20px">
                    <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:14px">Kategori Artikel</p>
                    @foreach($kategoris as $k)
                    <a href="{{ route('artikel') }}?kategori_id={{ $k->id }}" style="display:flex;align-items:center;justify-content:space-between;padding:8px 10px;border-radius:8px;text-decoration:none;transition:background .15s;margin-bottom:2px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:8px;height:8px;border-radius:50%;background:#2563eb"></div>
                            <span style="font-size:13px;color:#334155;font-weight:500">{{ $k->nama_kategori }}</span>
                        </div>
                        <span style="font-size:11px;color:#94a3b8;font-weight:600">{{ $k->artikels_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
.artikel-layout {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 32px;
    align-items: start;
}
.artikel-sidebar {
    position: sticky;
    top: 24px;
}
@media (max-width: 768px) {
    .artikel-layout {
        grid-template-columns: 1fr;
    }
    .artikel-sidebar {
        position: static;
    }
}
</style>
@endpush
@endsection
