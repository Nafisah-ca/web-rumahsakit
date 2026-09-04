@php
    $todayStr      = now()->toDateString();
    $hariIsoMap    = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
    $hariSekarang  = $hariIsoMap[now()->dayOfWeekIso];
    $menitSekarang = (int) now()->format('H') * 60 + (int) now()->format('i');

    // Semua jadwal aktif dokter (mingguan NULL + spesifik mendatang)
    $jadwals = $d->jadwalAktif;

    // Helper: parse jam_selesai ke menit
    $toMenit = function ($jam) {
        if ($jam instanceof \Carbon\Carbon) return $jam->hour * 60 + $jam->minute;
        $p = explode(':', (string) $jam);
        return (int)($p[0] ?? 0) * 60 + (int)($p[1] ?? 0);
    };

    // 1. Apakah dokter PUNYA jadwal hari ini? (tidak peduli jam sudah lewat atau belum)
    $jadwalHariIni = $jadwals->first(function ($j) use ($todayStr, $hariSekarang) {
        $praktek = $j->tanggal_praktek?->toDateString();
        return ($praktek === null && $j->hari === $hariSekarang)
            || ($praktek === $todayStr);
    });

    // 2. Apakah jadwal hari ini MASIH BUKA (jam belum selesai)?
    $jadwalMasihBuka = $jadwalHariIni
        ? ($menitSekarang < $toMenit($jadwalHariIni->jam_selesai))
        : false;

    // Status untuk visual card
    // - "hari_ini_buka"   : punya jadwal hari ini, jam masih buka → paling menonjol
    // - "hari_ini_selesai": punya jadwal hari ini, jam sudah lewat → agak menonjol
    // - "tidak_hari_ini"  : tidak ada jadwal hari ini → redup, tampilkan jadwal berikutnya
    if ($jadwalHariIni && $jadwalMasihBuka)  $statusCard = 'hari_ini_buka';
    elseif ($jadwalHariIni)                  $statusCard = 'hari_ini_selesai';
    else                                     $statusCard = 'tidak_hari_ini';

    // 3. Jadwal berikutnya (untuk dokter yang tidak ada jadwal hari ini)
    //    Cari jadwal mingguan selain hari ini, atau spesifik setelah hari ini
    $jadwalBerikutnya = ($statusCard === 'tidak_hari_ini')
        ? $jadwals->first(function ($j) use ($todayStr, $hariSekarang) {
            $praktek = $j->tanggal_praktek?->toDateString();
            if ($praktek === null) return $j->hari !== $hariSekarang;
            return $praktek > $todayStr;
        })
        : null;

    // Warna overlay banner
    $overlayColor = match($statusCard) {
        'hari_ini_buka'    => match($d->tipe_dokter ?? 'spesialis') {
                                'umum'    => 'rgba(15,50,140,0.70)',
                                'lainnya' => 'rgba(70,15,130,0.70)',
                                default   => 'rgba(8,70,30,0.70)',
                              },
        'hari_ini_selesai' => 'rgba(30,60,40,0.72)',
        default            => 'rgba(30,35,50,0.75)',
    };

    $tipeBadge = match($d->tipe_dokter ?? 'spesialis') {
        'umum'    => ['label'=>'Dokter Umum',    'bg'=>'#dbeafe','color'=>'#1d4ed8'],
        'lainnya' => ['label'=>'Dokter Lainnya', 'bg'=>'#ede9fe','color'=>'#6d28d9'],
        default   => ['label'=>'Spesialis',      'bg'=>'#dcfce7','color'=>'#15803d'],
    };

    $btnColor = match($d->tipe_dokter ?? 'spesialis') {
        'umum'    => '#1d4ed8',
        'lainnya' => '#6d28d9',
        default   => '#16a34a',
    };

    // URL foto
    $fotoUrl = null;
    if ($d->foto) {
        $fotoUrl = str_starts_with($d->foto, 'images/')
            ? asset($d->foto)
            : asset('storage/' . $d->foto);
    }
    $bannerUrl = $d->foto_banner
        ? asset('storage/' . $d->foto_banner)
        : asset('images/bg-hospital.svg');

    // Style per status
    $cardBorder  = $statusCard === 'hari_ini_buka'    ? '2px solid #16a34a'
                 : ($statusCard === 'hari_ini_selesai' ? '1.5px solid #86efac'
                 : '1px solid #e2e8f0');
    $cardShadow  = $statusCard === 'hari_ini_buka'    ? '0 4px 20px rgba(22,163,74,0.18)'
                 : ($statusCard === 'hari_ini_selesai' ? '0 2px 10px rgba(22,163,74,0.08)'
                 : '0 1px 6px rgba(0,0,0,0.05)');
    $cardOpacity = $statusCard === 'tidak_hari_ini'   ? '0.78' : '1';
    $cardBg      = $statusCard === 'tidak_hari_ini'   ? '#f8fafc' : 'white';
    $fotoGray    = $statusCard === 'hari_ini_buka'    ? '' : ($statusCard === 'hari_ini_selesai' ? 'filter:grayscale(0.25)' : 'filter:grayscale(0.55)');
    $dotOpacity  = $statusCard === 'hari_ini_buka'    ? '0.30' : '0.15';
@endphp

{{-- ── CARD ─────────────────────────────────────────────────────── --}}
<div style="background:{{ $cardBg }};border-radius:18px;overflow:hidden;
            border:{{ $cardBorder }};box-shadow:{{ $cardShadow }};
            opacity:{{ $cardOpacity }};
            transition:transform .2s,box-shadow .2s,opacity .2s"
     onmouseover="this.style.transform='translateY(-4px)';this.style.opacity='1';
                  this.style.boxShadow='{{ $statusCard==='hari_ini_buka' ? '0 12px 32px rgba(22,163,74,0.22)' : '0 8px 22px rgba(0,0,0,0.12)' }}'"
     onmouseout="this.style.transform='';this.style.boxShadow='{{ $cardShadow }}';this.style.opacity='{{ $cardOpacity }}'">

    {{-- ── BANNER ───────────────────────────────────────────────── --}}
    <div style="position:relative;height:200px;overflow:hidden;border-radius:18px 18px 0 0">

        <img src="{{ $bannerUrl }}" alt=""
             style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;
                    filter:blur(1.5px) saturate({{ $statusCard==='hari_ini_buka' ? '0.75' : '0.20' }});
                    transform:scale(1.06)"
             onerror="this.style.display='none'">

        <div style="position:absolute;inset:0;background:{{ $overlayColor }}"></div>

        {{-- Dekorasi titik --}}
        <div style="position:absolute;top:12px;left:12px;display:grid;grid-template-columns:repeat(4,6px);gap:5px;opacity:{{ $dotOpacity }}">
            @for($i=0;$i<12;$i++)<div style="width:4px;height:4px;border-radius:50%;background:white"></div>@endfor
        </div>
        <div style="position:absolute;bottom:14px;right:12px;display:grid;grid-template-columns:repeat(4,6px);gap:5px;opacity:{{ $dotOpacity }}">
            @for($i=0;$i<12;$i++)<div style="width:4px;height:4px;border-radius:50%;background:white"></div>@endfor
        </div>
        <div style="position:absolute;top:-22px;left:-22px;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,0.10)"></div>
        <div style="position:absolute;bottom:-25px;right:-15px;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,0.08)"></div>
        <div style="position:absolute;top:-8px;right:-8px;width:55px;height:55px;border-radius:50%;background:rgba(255,255,255,0.09)"></div>

        {{-- Badge status --}}
        <div style="position:absolute;top:10px;right:10px;z-index:20">
            @if($jadwals->isEmpty())
                <span style="background:rgba(100,116,139,0.88);color:white;font-size:10px;
                             font-weight:700;padding:3px 10px;border-radius:99px">
                    Belum Ada Jadwal
                </span>
            @elseif($statusCard === 'hari_ini_buka')
                <span style="background:rgba(22,163,74,0.92);color:white;font-size:10px;
                             font-weight:700;padding:3px 10px;border-radius:99px;
                             display:inline-flex;align-items:center;gap:4px">
                    <span style="width:5px;height:5px;background:white;border-radius:50%;
                                 display:inline-block;animation:pulse 1.5s infinite"></span>
                    Sedang Praktik
                </span>
            @elseif($statusCard === 'hari_ini_selesai')
                <span style="background:rgba(22,101,52,0.85);color:white;font-size:10px;
                             font-weight:700;padding:3px 10px;border-radius:99px">
                    <i class="fas fa-calendar-check" style="font-size:9px;margin-right:3px"></i>
                    Praktik Hari Ini
                </span>
            @else
                <span style="background:rgba(71,85,105,0.88);color:white;font-size:10px;
                             font-weight:700;padding:3px 10px;border-radius:99px">
                    <i class="fas fa-clock" style="font-size:9px;margin-right:3px;opacity:.8"></i>
                    Tidak Praktik Hari Ini
                </span>
            @endif
        </div>

        {{-- Foto dokter di tengah --}}
        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;z-index:10">
            <div style="width:136px;height:136px;border-radius:50%;
                        border:2.5px solid rgba(255,255,255,{{ $statusCard==='hari_ini_buka' ? '0.50' : '0.25' }});
                        display:flex;align-items:center;justify-content:center">
                <div style="width:120px;height:120px;border-radius:50%;overflow:hidden;
                            background:#d1fae5;
                            border:3px solid rgba(255,255,255,{{ $statusCard==='hari_ini_buka' ? '0.85' : '0.40' }});
                            box-shadow:0 6px 24px rgba(0,0,0,0.28);{{ $fotoGray }}">
                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="{{ $d->nama_dokter }}"
                             style="width:120px;height:120px;object-fit:cover;object-position:center top;display:block"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div style="display:none;width:120px;height:120px;
                                    background:linear-gradient(135deg,{{ $statusCard==='hari_ini_buka' ? '#004d1a,#16a34a' : '#334155,#64748b' }});
                                    align-items:center;justify-content:center">
                            <i class="fas fa-user-md" style="color:white;font-size:38px"></i>
                        </div>
                    @else
                        <div style="width:120px;height:120px;
                                    background:linear-gradient(135deg,{{ $statusCard==='hari_ini_buka' ? '#004d1a,#16a34a' : '#334155,#64748b' }});
                                    display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-user-md" style="color:white;font-size:38px"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div style="position:absolute;bottom:0;left:0;right:0;height:28px;
                    background:linear-gradient(to bottom,transparent,rgba(255,255,255,0.08))"></div>
    </div>

    {{-- ── BODY ─────────────────────────────────────────────────── --}}
    <div style="padding:16px">

        {{-- Nama --}}
        <h3 style="font-size:13.5px;font-weight:800;
                   color:{{ $statusCard==='tidak_hari_ini' ? '#64748b' : '#0f172a' }};
                   text-align:center;margin-bottom:5px;line-height:1.3">
            {{ $d->nama_dokter }}
        </h3>

        {{-- Badge tipe + spesialisasi --}}
        <div style="display:flex;flex-wrap:wrap;gap:4px;justify-content:center;margin-bottom:12px">
            <span style="background:{{ $statusCard!=='tidak_hari_ini' ? $tipeBadge['bg'] : '#f1f5f9' }};
                         color:{{ $statusCard!=='tidak_hari_ini' ? $tipeBadge['color'] : '#94a3b8' }};
                         font-size:10px;font-weight:700;padding:2px 10px;border-radius:99px">
                {{ $tipeBadge['label'] }}
            </span>
            @if($d->spesialisasi)
            <span style="background:#f1f5f9;
                         color:{{ $statusCard!=='tidak_hari_ini' ? '#475569' : '#94a3b8' }};
                         font-size:10px;font-weight:600;padding:2px 10px;border-radius:99px">
                {{ $d->spesialisasi->nama_spesialis }}
            </span>
            @endif
        </div>

        {{-- Info jadwal --}}
        <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:14px">
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:11px">
                <i class="fas fa-calendar-alt"
                   style="color:{{ $statusCard!=='tidak_hari_ini' ? '#16a34a' : '#94a3b8' }};
                          margin-top:2px;width:12px;flex-shrink:0"></i>
                <span style="line-height:1.6;color:#64748b">
                    @if($jadwals->isEmpty())
                        <span style="color:#94a3b8;font-style:italic">Belum ada jadwal</span>

                    @elseif($statusCard === 'hari_ini_buka')
                        <strong style="color:#15803d">Hari Ini — {{ $jadwalHariIni->hari }}</strong><br>
                        <span style="color:#16a34a;font-weight:700">
                            {{ substr($jadwalHariIni->jam_mulai,0,5) }} – {{ substr($jadwalHariIni->jam_selesai,0,5) }} WIB
                        </span>

                    @elseif($statusCard === 'hari_ini_selesai')
                        <strong style="color:#166534">Hari Ini — {{ $jadwalHariIni->hari }}</strong><br>
                        <span style="color:#64748b">
                            {{ substr($jadwalHariIni->jam_mulai,0,5) }} – {{ substr($jadwalHariIni->jam_selesai,0,5) }} WIB
                        </span>
                        <span style="color:#94a3b8;font-size:10px"> (selesai)</span>

                    @else
                        <span style="color:#94a3b8;font-size:10px;font-style:italic">
                            Tidak praktik hari ini
                        </span>
                        @if($jadwalBerikutnya)
                        <br>
                        <span style="color:#64748b;font-size:10px">
                            Berikutnya:
                            <strong style="color:#475569">{{ $jadwalBerikutnya->hari }}</strong>
                            @if($jadwalBerikutnya->tanggal_praktek)
                                ({{ $jadwalBerikutnya->tanggal_praktek->format('d M') }})
                            @endif
                            &nbsp;{{ substr($jadwalBerikutnya->jam_mulai,0,5) }}–{{ substr($jadwalBerikutnya->jam_selesai,0,5) }} WIB
                        </span>
                        @endif
                    @endif
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:11px;
                        color:{{ $statusCard!=='tidak_hari_ini' ? '#64748b' : '#94a3b8' }}">
                <i class="fas fa-id-card"
                   style="color:{{ $statusCard!=='tidak_hari_ini' ? '#16a34a' : '#94a3b8' }};
                          width:12px;flex-shrink:0"></i>
                <span>SIP: {{ $d->sip }}</span>
            </div>
        </div>

        {{-- Tombol Buat Janji — selalu aktif, selalu berwarna --}}
        <a href="{{ route('portal.booking.create', ['dokter_id' => $d->id]) }}"
           style="display:block;width:100%;text-align:center;padding:10px 0;
                  border-radius:12px;font-size:12px;font-weight:700;color:white;
                  text-decoration:none;background:{{ $btnColor }};
                  box-shadow:0 2px 10px rgba(22,163,74,0.20);transition:opacity .15s"
           onmouseover="this.style.opacity='0.85'"
           onmouseout="this.style.opacity='1'">
            <i class="fas fa-calendar-check" style="margin-right:5px"></i>Buat Janji
        </a>
    </div>
</div>
