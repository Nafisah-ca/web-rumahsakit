@php
    $hariSekarang = now()->locale('id')->isoFormat('dddd');
    $jadwals      = $d->jadwalAktif;
    $available    = $jadwals->isNotEmpty() && $jadwals->contains('hari', $hariSekarang);

    // Overlay hijau sesuai tipe dokter
    $overlayColor = match($d->tipe_dokter ?? 'spesialis') {
        'umum'    => 'rgba(15, 50, 140, 0.70)',
        'lainnya' => 'rgba(70, 15, 130, 0.70)',
        default   => 'rgba(8, 70, 30, 0.70)',
    };

    $tipeBadge = match($d->tipe_dokter ?? 'spesialis') {
        'umum'    => ['label' => 'Dokter Umum',    'bg' => '#dbeafe', 'color' => '#1d4ed8'],
        'lainnya' => ['label' => 'Dokter Lainnya', 'bg' => '#ede9fe', 'color' => '#6d28d9'],
        default   => ['label' => 'Spesialis',      'bg' => '#dcfce7', 'color' => '#15803d'],
    };

    $btnColor = match($d->tipe_dokter ?? 'spesialis') {
        'umum'    => '#1d4ed8',
        'lainnya' => '#6d28d9',
        default   => '#16a34a',
    };

    // URL foto profil dokter
    $fotoUrl = null;
    if ($d->foto) {
        $fotoUrl = str_starts_with($d->foto, 'images/')
            ? asset($d->foto)
            : Storage::url($d->foto);
    }

    // URL banner: dari DB jika ada, fallback ke bg-hospital.svg
    $bannerUrl = $d->foto_banner
        ? Storage::url($d->foto_banner)
        : asset('images/bg-hospital.svg');
@endphp

<div style="background:white;border-radius:18px;box-shadow:0 2px 12px rgba(0,0,0,0.08);
            border:1px solid #f1f5f9;overflow:hidden;
            transition:transform .2s,box-shadow .2s"
     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,0.14)'"
     onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,0.08)'">

    {{-- ===== BANNER (foto RS + overlay + foto dokter DI TENGAH) ===== --}}
    <div style="position:relative;height:200px;overflow:hidden;border-radius:18px 18px 0 0">

        {{-- Layer 1: foto background RS --}}
        <img src="{{ $bannerUrl }}" alt=""
             style="position:absolute;inset:0;width:100%;height:100%;
                    object-fit:cover;filter:blur(1.5px) saturate(0.75);transform:scale(1.06)"
             onerror="this.style.display='none'">

        {{-- Layer 2: overlay warna --}}
        <div style="position:absolute;inset:0;background:{{ $overlayColor }}"></div>

        {{-- Dekorasi: titik-titik kiri atas --}}
        <div style="position:absolute;top:12px;left:12px;display:grid;
                    grid-template-columns:repeat(4,6px);gap:5px;opacity:0.30">
            @for($i=0;$i<12;$i++)
            <div style="width:4px;height:4px;border-radius:50%;background:white"></div>
            @endfor
        </div>

        {{-- Dekorasi: titik-titik kanan bawah --}}
        <div style="position:absolute;bottom:14px;right:12px;display:grid;
                    grid-template-columns:repeat(4,6px);gap:5px;opacity:0.25">
            @for($i=0;$i<12;$i++)
            <div style="width:4px;height:4px;border-radius:50%;background:white"></div>
            @endfor
        </div>

        {{-- Dekorasi: lingkaran kiri atas --}}
        <div style="position:absolute;top:-22px;left:-22px;width:90px;height:90px;
                    border-radius:50%;background:rgba(255,255,255,0.10)"></div>

        {{-- Dekorasi: lingkaran kanan bawah --}}
        <div style="position:absolute;bottom:-25px;right:-15px;width:90px;height:90px;
                    border-radius:50%;background:rgba(255,255,255,0.08)"></div>

        {{-- Dekorasi: lingkaran kanan atas kecil --}}
        <div style="position:absolute;top:-8px;right:-8px;width:55px;height:55px;
                    border-radius:50%;background:rgba(255,255,255,0.09)"></div>

        {{-- Badge status --}}
        <div style="position:absolute;top:10px;right:10px;z-index:20">
            @if($jadwals->isEmpty())
                <span style="background:rgba(100,116,139,0.85);color:white;font-size:10px;
                             font-weight:700;padding:3px 10px;border-radius:99px">Hubungi RS</span>
            @elseif($available)
                <span style="background:rgba(22,163,74,0.88);color:white;font-size:10px;
                             font-weight:700;padding:3px 10px;border-radius:99px;
                             display:inline-flex;align-items:center;gap:4px">
                    <span style="width:5px;height:5px;background:white;border-radius:50%;
                                 display:inline-block;animation:pulse 1.5s infinite"></span>
                    Tersedia Hari Ini
                </span>
            @else
                <span style="background:rgba(202,138,4,0.88);color:white;font-size:10px;
                             font-weight:700;padding:3px 10px;border-radius:99px">Jadwal Terjadwal</span>
            @endif
        </div>

        {{-- ===== FOTO DOKTER: posisi TENGAH-TENGAH banner ===== --}}
        <div style="position:absolute;inset:0;display:flex;align-items:center;
                    justify-content:center;z-index:10">
            {{-- Cincin luar putih transparan (besar) --}}
            <div style="width:136px;height:136px;border-radius:50%;
                        border:2.5px solid rgba(255,255,255,0.50);
                        display:flex;align-items:center;justify-content:center">
                {{-- Foto dalam circle --}}
                <div style="width:120px;height:120px;border-radius:50%;overflow:hidden;
                            background:#d1fae5;
                            border:3px solid rgba(255,255,255,0.85);
                            box-shadow:0 6px 24px rgba(0,0,0,0.28)">
                    @if($fotoUrl)
                        <img src="{{ $fotoUrl }}" alt="{{ $d->nama_dokter }}"
                             style="width:120px;height:120px;object-fit:cover;
                                    object-position:center top;display:block"
                             onerror="this.style.display='none';
                                      this.nextElementSibling.style.display='flex'">
                        <div style="display:none;width:120px;height:120px;
                                    background:linear-gradient(135deg,#004d1a,#16a34a);
                                    align-items:center;justify-content:center">
                            <i class="fas fa-user-md" style="color:white;font-size:38px"></i>
                        </div>
                    @else
                        <div style="width:120px;height:120px;
                                    background:linear-gradient(135deg,#004d1a,#16a34a);
                                    display:flex;align-items:center;justify-content:center">
                            <i class="fas fa-user-md" style="color:white;font-size:38px"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Fade putih di bagian bawah banner agar transisi ke card body halus --}}
        <div style="position:absolute;bottom:0;left:0;right:0;height:28px;
                    background:linear-gradient(to bottom,transparent,rgba(255,255,255,0.08))">
        </div>
    </div>

    {{-- ===== BODY CARD (putih) ===== --}}
    <div style="padding:16px 16px 16px 16px">

        {{-- Nama dokter --}}
        <h3 style="font-size:13.5px;font-weight:800;color:#0f172a;text-align:center;
                   margin-bottom:5px;line-height:1.3">
            {{ $d->nama_dokter }}
        </h3>

        {{-- Badge tipe + spesialisasi --}}
        <div style="display:flex;flex-wrap:wrap;gap:4px;justify-content:center;margin-bottom:12px">
            <span style="background:{{ $tipeBadge['bg'] }};color:{{ $tipeBadge['color'] }};
                         font-size:10px;font-weight:700;padding:2px 10px;border-radius:99px">
                {{ $tipeBadge['label'] }}
            </span>
            @if($d->spesialisasi)
            <span style="background:#f1f5f9;color:#475569;font-size:10px;font-weight:600;
                         padding:2px 10px;border-radius:99px">
                {{ $d->spesialisasi->nama_spesialis }}
            </span>
            @endif
        </div>

        {{-- Info jadwal, SIP, HP --}}
        <div style="display:flex;flex-direction:column;gap:5px;margin-bottom:14px">
            <div style="display:flex;align-items:flex-start;gap:8px;font-size:11px;color:#64748b">
                <i class="fas fa-calendar-alt"
                   style="color:#16a34a;margin-top:1px;width:12px;flex-shrink:0"></i>
                <span>
                    @if($jadwals->isEmpty())
                        Hubungi RS
                    @else
                        @php
                            $hariList = $jadwals->pluck('hari')->unique()->implode(', ');
                            $jam      = $jadwals->first();
                        @endphp
                        {{ $hariList }}<br>
                        <span style="color:#16a34a;font-weight:700">
                            {{ $jam ? substr($jam->jam_mulai,0,5).' – '.substr($jam->jam_selesai,0,5) : '' }}
                        </span>
                    @endif
                </span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:11px;color:#64748b">
                <i class="fas fa-id-card"
                   style="color:#16a34a;width:12px;flex-shrink:0"></i>
                <span>SIP: {{ $d->sip }}</span>
            </div>
            <div style="display:flex;align-items:center;gap:8px;font-size:11px;color:#64748b">
                <i class="fas fa-phone"
                   style="color:#16a34a;width:12px;flex-shrink:0"></i>
                <span>{{ $d->no_hp }}</span>
            </div>
        </div>

        {{-- Tombol Buat Janji --}}
        <a href="{{ route('portal.booking.create', ['dokter_id' => $d->id]) }}"
           style="display:block;width:100%;text-align:center;padding:10px 0;
                  border-radius:12px;font-size:12px;font-weight:700;color:white;
                  text-decoration:none;background:{{ $btnColor }};transition:opacity .15s"
           onmouseover="this.style.opacity='0.85'"
           onmouseout="this.style.opacity='1'">
            <i class="fas fa-calendar-check" style="margin-right:5px"></i>Buat Janji
        </a>
    </div>
</div>
