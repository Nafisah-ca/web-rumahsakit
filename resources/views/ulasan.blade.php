@extends('layouts.app')

@push('styles')
<style>
/* ── CSS Ulasan (sama dengan beranda) ─────────────────── */
.ulasan-summary-wrap{display:flex;align-items:stretch;gap:16px;background:linear-gradient(135deg,#00521f,#00b04f);border-radius:20px;padding:24px;margin-bottom:32px;flex-wrap:wrap}
.ulasan-score-block{display:flex;flex-direction:column;align-items:center;justify-content:center;min-width:100px;flex-shrink:0}
.ulasan-score-num{font-size:52px;font-weight:900;color:#fff;line-height:1;letter-spacing:-3px}
.ulasan-score-stars{display:flex;gap:3px;margin:6px 0 4px}
.ulasan-score-stars i{font-size:13px}
.ulasan-score-label{font-size:11px;color:rgba(255,255,255,.7);font-weight:600}
.ulasan-bars{flex:1;min-width:160px;display:flex;flex-direction:column;justify-content:center;gap:6px}
.ulasan-bar-row{display:flex;align-items:center;gap:7px}
.ulasan-bar-num{font-size:11px;font-weight:700;color:rgba(255,255,255,.8);width:12px;text-align:right;flex-shrink:0}
.ulasan-bar-track{flex:1;height:7px;background:rgba(255,255,255,.2);border-radius:999px;overflow:hidden}
.ulasan-bar-fill{height:100%;border-radius:999px;transition:width 1.2s cubic-bezier(.4,0,.2,1)}
.ulasan-bar-count{font-size:11px;color:rgba(255,255,255,.65);width:20px;flex-shrink:0}
.ulasan-cta-btn{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.3);color:#fff;border-radius:14px;padding:14px 20px;text-decoration:none;transition:background .2s,transform .2s;flex-shrink:0;text-align:center}
.ulasan-cta-btn:hover{background:rgba(255,255,255,.28);transform:translateY(-2px)}

/* Banyak kartu ukuran sama, tidak terlalu tinggi */
.ulasan-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:14px;align-items:stretch}

/* Kartu */
.ulasan-card-v2{background:#fff;border-radius:16px;border:1px solid #f0f0f0;box-shadow:0 2px 12px rgba(0,0,0,.05);padding:14px 16px;display:flex;flex-direction:column;position:relative;overflow:hidden;opacity:0;transform:translateY(20px);transition:opacity .45s ease calc(var(--delay,0ms)),transform .45s cubic-bezier(.34,1.2,.64,1) calc(var(--delay,0ms)),box-shadow .2s,border-color .2s;width:100%;height:148px}
.ulasan-card-v2.card-visible{opacity:1;transform:translateY(0)}
.ulasan-card-v2:hover{box-shadow:0 12px 32px rgba(0,82,31,.12);border-color:#d1fae5;transform:translateY(-5px)}
.ulasan-quote{position:absolute;top:10px;right:14px;font-size:40px;line-height:1;color:#f0fdf4;font-family:Georgia,serif;pointer-events:none;user-select:none;transition:color .3s}
.ulasan-card-v2:hover .ulasan-quote{color:#dcfce7}
.ulasan-avatar{width:42px;height:42px;border-radius:12px;border:2px solid;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:17px;font-weight:900;letter-spacing:-1px;transition:transform .2s}
.ulasan-card-v2:hover .ulasan-avatar{transform:scale(1.08)}
.ulasan-card-head{display:flex;align-items:flex-start;gap:11px;margin-bottom:8px}
.ulasan-meta{flex:1;min-width:0}
.ulasan-nama{font-size:13px;font-weight:800;color:#111;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px}
.ulasan-rbadge{display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:800;padding:2px 8px;border-radius:999px;border:1px solid;white-space:nowrap;flex-shrink:0}
.ulasan-stars-row{display:flex;align-items:center;gap:2px;margin-top:4px}
.ulasan-date{font-size:10px;color:#9ca3af;margin-left:6px}
.ulasan-judul{font-size:13px;font-weight:700;color:#1e293b;margin-bottom:4px;line-height:1.4;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.ulasan-isi{font-size:12px;color:#6b7280;line-height:1.55;flex:1;min-height:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}

/* Filter pills */
.u-filter-btn{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:10px;font-size:12px;font-weight:700;text-decoration:none;border:1.5px solid;transition:transform .15s cubic-bezier(.34,1.56,.64,1),box-shadow .15s,opacity .15s}
.u-filter-btn:hover{transform:translateY(-3px);box-shadow:0 5px 14px rgba(0,0,0,.12)}
.u-filter-btn:active{transform:translateY(-1px) scale(.97)}
</style>
@endpush

@section('content')

@include('_partials.page-hero', [
    'banner'      => $banner ?? \App\Models\PageBanner::getForPage('ulasan'),
    'breadcrumbs' => [['label'=>'Beranda','url'=>route('home')],['label'=>'Ulasan Pasien']],
])

<section class="py-12 bg-gray-50">
<div class="max-w-screen-xl mx-auto px-4">

    @php $barColors=[5=>'#22c55e',4=>'#84cc16',3=>'#eab308',2=>'#f97316',1=>'#ef4444']; @endphp

    {{-- Rating Summary --}}
    <div class="ulasan-summary-wrap">
        <div class="ulasan-score-block">
            <div class="ulasan-score-num">{{ number_format($avgRating??0,1) }}</div>
            <div class="ulasan-score-stars">
                @for($i=1;$i<=5;$i++)
                <i class="fas fa-star" style="color:{{ $i<=round($avgRating??0)?'#fbbf24':'rgba(255,255,255,.3)' }}"></i>
                @endfor
            </div>
            <div class="ulasan-score-label">{{ $totalUlasan }} ulasan</div>
        </div>
        <div class="ulasan-bars">
            @for($s=5;$s>=1;$s--)
            @php $cnt=$ratingCounts[$s]??0; $pct=$totalUlasan>0?round(($cnt/$totalUlasan)*100):0; @endphp
            <div class="ulasan-bar-row">
                <span class="ulasan-bar-num">{{ $s }}</span>
                <i class="fas fa-star" style="font-size:10px;color:{{ $barColors[$s] }}"></i>
                <div class="ulasan-bar-track">
                    <div class="ulasan-bar-fill" data-pct="{{ $pct }}" style="background:{{ $barColors[$s] }};width:0%"></div>
                </div>
                <span class="ulasan-bar-count">{{ $cnt }}</span>
            </div>
            @endfor
        </div>
        <a href="{{ route('kontak') }}#ulasan-form" class="ulasan-cta-btn">
            <i class="fas fa-pen-to-square" style="font-size:20px"></i>
            <span style="font-size:12px;font-weight:800">Tulis<br>Ulasan</span>
        </a>
    </div>

    {{-- Filter Pills --}}
    <div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-bottom:24px">
        <span style="font-size:11px;font-weight:800;color:#9ca3af;text-transform:uppercase;letter-spacing:.08em">Filter:</span>
        <a href="{{ route('ulasan.public') }}" class="u-filter-btn"
           style="{{ !($rating??null) ? 'background:#00521f;color:#fff;border-color:#00521f' : 'background:#fff;color:#6b7280;border-color:#e5e7eb' }}">
            Semua
        </a>
        @foreach([5,4,3,2,1] as $r)
        <a href="{{ route('ulasan.public', ['rating'=>$r]) }}" class="u-filter-btn"
           style="{{ ($rating??null)==$r ? 'background:'.$barColors[$r].';color:#fff;border-color:'.$barColors[$r] : 'background:#fff;color:#6b7280;border-color:#e5e7eb' }}">
            <i class="fas fa-star" style="font-size:10px;color:{{ ($rating??null)==$r?'#fff':$barColors[$r] }}"></i>
            {{ $r }}
            <span style="font-size:10px;opacity:.65">({{ $ratingCounts[$r]??0 }})</span>
        </a>
        @endforeach
    </div>

    {{-- Grid Kartu --}}
    <div id="ulasan-grid">
        @if($ulasans->count() > 0)
        <div class="ulasan-grid mb-8">
            @foreach($ulasans as $idx => $u)
            @php
                $palettes=[['#dcfce7','#166534','#86efac'],['#dbeafe','#1d4ed8','#93c5fd'],['#fce7f3','#be185d','#f9a8d4'],['#fef9c3','#854d0e','#fde047'],['#f3e8ff','#7e22ce','#d8b4fe'],['#ffedd5','#c2410c','#fdba74'],['#ccfbf1','#0f766e','#5eead4'],['#e0e7ff','#3730a3','#a5b4fc']];
                [$abg,$atxt,$aring]=$palettes[$idx%count($palettes)];
                $rb=match(true){$u->rating>=5=>['#f0fdf4','#16a34a','#86efac'],$u->rating>=4=>['#f0fdf4','#15803d','#bbf7d0'],$u->rating>=3=>['#fefce8','#a16207','#fde047'],$u->rating>=2=>['#fff7ed','#c2410c','#fdba74'],default=>['#fef2f2','#dc2626','#fca5a5']};
            @endphp
            <div class="ulasan-card-v2" style="--delay:{{ $idx*70 }}ms">
                <span class="ulasan-quote">&ldquo;</span>
                <div class="ulasan-card-head">
                    <div class="ulasan-avatar" style="background:{{ $abg }};border-color:{{ $aring }};color:{{ $atxt }}">{{ strtoupper(substr($u->nama,0,1)) }}</div>
                    <div class="ulasan-meta">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                            <p class="ulasan-nama">{{ $u->nama }}</p>
                            <span class="ulasan-rbadge" style="background:{{ $rb[0] }};color:{{ $rb[1] }};border-color:{{ $rb[2] }}">
                                <i class="fas fa-star" style="font-size:9px"></i> {{ $u->rating }}.0
                            </span>
                        </div>
                        <div class="ulasan-stars-row">
                            @for($i=1;$i<=5;$i++)<i class="fas fa-star" style="font-size:10px;color:{{ $i<=$u->rating?'#f59e0b':'#e5e7eb' }}"></i>@endfor
                            <span class="ulasan-date">{{ $u->created_tm?->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                @if($u->judul)<p class="ulasan-judul">{{ $u->judul }}</p>@endif
                <p class="ulasan-isi">{{ $u->isi }}</p>
            </div>
            @endforeach
        </div>
        <div class="flex justify-center">{{ $ulasans->links() }}</div>

        @else
        {{-- ── Empty state ── --}}
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
            <i class="fas fa-star text-5xl text-gray-200 block mb-4"></i>
            @if($rating ?? null)
            <p class="text-gray-500 font-semibold">Belum ada ulasan dengan rating {{ $rating }} bintang</p>
            @else
            <p class="text-gray-500 font-semibold">Belum ada ulasan</p>
            @endif
            <a href="{{ route('kontak') }}#ulasan-form"
               style="display:inline-flex;align-items:center;gap:6px;margin-top:16px;color:#00521f;font-size:13px;font-weight:700;text-decoration:none">
                Jadilah yang pertama <i class="fas fa-arrow-right" style="font-size:11px"></i>
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

    /* ── 1. Animasi bar rating ── */
    var sw = document.querySelector('.ulasan-summary-wrap');
    if (sw) {
        new IntersectionObserver(function(en) {
            en.forEach(function(e) {
                if (!e.isIntersecting) return;
                e.target.querySelectorAll('.ulasan-bar-fill').forEach(function(b, i) {
                    setTimeout(function(){ b.style.width = b.dataset.pct + '%'; }, 100 + i * 80);
                });
            });
        }, { threshold: 0.3 }).observe(sw);
    }

    /* ── 2. Stagger entrance kartu ── */
    var grid = document.getElementById('ulasan-grid');
    if (grid) {
        new IntersectionObserver(function(en) {
            en.forEach(function(e) {
                if (!e.isIntersecting) return;
                e.target.querySelectorAll('.ulasan-card-v2').forEach(function(c) {
                    c.classList.add('card-visible');
                });
            });
        }, { threshold: 0.05 }).observe(grid);
    }

    /* ── 3. Scroll ke grid setelah filter redirect ── */
    document.querySelectorAll('.u-filter-btn').forEach(function(b) {
        b.addEventListener('click', function() {
            sessionStorage.setItem('scrollUlasan', '1');
        });
    });
    if (sessionStorage.getItem('scrollUlasan')) {
        sessionStorage.removeItem('scrollUlasan');
        setTimeout(function() {
            var g = document.getElementById('ulasan-grid');
            if (g) g.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 200);
    }

    /* ── 4. Ripple effect saat klik filter pill ── */
    document.querySelectorAll('.u-filter-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            var r = document.createElement('span');
            var sz = Math.max(btn.offsetWidth, btn.offsetHeight) * 2;
            var rc = btn.getBoundingClientRect();
            r.style.cssText = 'position:absolute;border-radius:50%;background:rgba(255,255,255,.35);width:'+sz+'px;height:'+sz+'px;left:'+(e.clientX-rc.left-sz/2)+'px;top:'+(e.clientY-rc.top-sz/2)+'px;transform:scale(0);animation:ripple-u .5s linear;pointer-events:none';
            btn.style.position = 'relative';
            btn.style.overflow = 'hidden';
            btn.appendChild(r);
            r.addEventListener('animationend', function() { r.remove(); });
        });
    });
});
</script>
<style>
@keyframes ripple-u { to { transform:scale(1); opacity:0; } }
</style>
@endpush
