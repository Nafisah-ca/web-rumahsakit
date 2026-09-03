@forelse($articles as $idx => $art)
<a href="{{ route('artikel.detail', $art->slug) }}"
   class="art-card {{ $idx >= 6 ? 'art-hidden' : '' }}"
   data-index="{{ $idx + 1 }}">

    {{-- Gambar + bling --}}
    <div class="art-img-wrap">
        {{-- Sparkle bintang --}}
        <span class="sparkle" style="--size:14px;--dur:2s;--delay:0s;top:12px;left:12px"></span>
        <span class="sparkle" style="--size:10px;--dur:2.4s;--delay:.6s;top:10px;right:14px"></span>
        <span class="sparkle" style="--size:12px;--dur:1.9s;--delay:1s;bottom:18px;left:18px"></span>
        <span class="sparkle" style="--size:8px;--dur:2.2s;--delay:1.4s;bottom:14px;right:10px"></span>
        <span class="sparkle" style="--size:9px;--dur:2.5s;--delay:.3s;top:45%;left:20px"></span>
        <span class="sparkle" style="--size:11px;--dur:2.1s;--delay:1.8s;top:28%;right:16px"></span>

        @if($art->gambar)
            <img src="{{ asset('storage/' . $art->gambar) }}"
                 alt="{{ $art->judul }}"
                 class="art-img" loading="lazy">
        @else
            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1e40af,#2563eb);
                        display:flex;align-items:center;justify-content:center">
                <i class="fas fa-newspaper text-white opacity-30" style="font-size:40px"></i>
            </div>
        @endif

        <div style="position:absolute;bottom:0;left:0;right:0;height:50px;
                    background:linear-gradient(to top,rgba(0,0,0,.18),transparent);
                    pointer-events:none"></div>
    </div>

    {{-- Body --}}
    <div style="padding:16px;position:relative;z-index:2">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;flex-wrap:wrap">
            @if($art->kategori)
            <span class="art-cat-badge">{{ $art->kategori->nama_kategori }}</span>
            @endif
            <span style="font-size:10px;color:#94a3b8;display:flex;align-items:center;gap:3px">
                <i class="fas fa-clock" style="font-size:9px"></i>
                {{ $art->created_tm?->format('d M Y') }}
            </span>
        </div>

        <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;
                   line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;
                   -webkit-box-orient:vertical;overflow:hidden">
            {{ $art->judul }}
        </h3>

        <p style="font-size:12px;color:#64748b;line-height:1.5;
                  display:-webkit-box;-webkit-line-clamp:2;
                  -webkit-box-orient:vertical;overflow:hidden">
            {{ Str::limit(strip_tags($art->isi), 100) }}
        </p>

        <div class="art-read-more">
            Baca Selengkapnya
            <i class="fas fa-arrow-right text-[10px] art-read-arrow"></i>
        </div>
    </div>
</a>
@empty
<div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:#94a3b8">
    <div style="width:80px;height:80px;background:#f1f5f9;border-radius:50%;
                display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
        <i class="fas fa-newspaper" style="font-size:32px;opacity:.3"></i>
    </div>
    <p style="font-size:15px;font-weight:700;margin-bottom:6px">Belum ada artikel</p>
    <p style="font-size:13px">Tidak ada artikel untuk kategori ini.</p>
</div>
@endforelse
