{{-- Partial: _partials/ulasan-card-v2.blade.php
     Props: $u (Ulasan), $idx (int, index untuk warna)
     Pakai di dalam .ulasan-grid --}}
@php
    $palettes = [
        ['#dcfce7','#166534','#86efac'],
        ['#dbeafe','#1d4ed8','#93c5fd'],
        ['#fce7f3','#be185d','#f9a8d4'],
        ['#fef9c3','#854d0e','#fde047'],
        ['#f3e8ff','#7e22ce','#d8b4fe'],
        ['#ffedd5','#c2410c','#fdba74'],
        ['#ccfbf1','#0f766e','#5eead4'],
        ['#e0e7ff','#3730a3','#a5b4fc'],
    ];
    [$abg,$atxt,$aring] = $palettes[$idx % count($palettes)];
    $rbadge = match(true){
        $u->rating>=5 => ['#f0fdf4','#16a34a','#86efac'],
        $u->rating>=4 => ['#f0fdf4','#15803d','#bbf7d0'],
        $u->rating>=3 => ['#fefce8','#a16207','#fde047'],
        $u->rating>=2 => ['#fff7ed','#c2410c','#fdba74'],
        default       => ['#fef2f2','#dc2626','#fca5a5'],
    };
@endphp
<div class="ulasan-card-v2" style="--delay:{{ $idx * 80 }}ms">
    <span class="ulasan-quote">&ldquo;</span>
    <div class="ulasan-card-head">
        <div class="ulasan-avatar" style="background:{{ $abg }};border-color:{{ $aring }};color:{{ $atxt }}">
            {{ strtoupper(substr($u->nama,0,1)) }}
        </div>
        <div class="ulasan-meta">
            <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap">
                <p class="ulasan-nama">{{ $u->nama }}</p>
                <span class="ulasan-rbadge" style="background:{{ $rbadge[0] }};color:{{ $rbadge[1] }};border-color:{{ $rbadge[2] }}">
                    <i class="fas fa-star" style="font-size:9px"></i> {{ $u->rating }}.0
                </span>
            </div>
            <div class="ulasan-stars-row">
                @for($i=1;$i<=5;$i++)
                <i class="fas fa-star" style="font-size:10px;color:{{ $i<=$u->rating?'#f59e0b':'#e5e7eb' }}"></i>
                @endfor
                <span class="ulasan-date">{{ $u->created_tm?->format('d M Y') }}</span>
            </div>
        </div>
    </div>
    @if($u->judul)<p class="ulasan-judul">{{ $u->judul }}</p>@endif
    <p class="ulasan-isi">{{ $u->isi }}</p>
</div>
