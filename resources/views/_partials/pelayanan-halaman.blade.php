{{--
    Layout halaman Pelayanan (informasional, premium redesign)
    Props: $kategoriList, $aktifKategori, $layanans, $aktifLayanan, $dokterTerkait, $banner
--}}
@php
    $judulHalaman = $aktifKategori?->nama_kategori ?? ($banner?->judul ?? 'Pelayanan Kami');
    $labelKartu   = $aktifKategori?->nama_kategori ?? 'Pelayanan';
    $judulKartu   = $aktifLayanan?->nama_layanan
        ?? $aktifKategori?->nama_kategori
        ?? ($banner?->judul ?? 'Pelayanan Kami');
    $isiKartu = $aktifLayanan?->deskripsi
        ?? $aktifKategori?->deskripsi
        ?? $banner?->subjudul
        ?? 'Rumah sakit kami menyediakan berbagai layanan kesehatan berkualitas untuk masyarakat. Pilih kategori di menu samping untuk melihat penjelasan lengkap setiap layanan kami.';
    $katIcon  = $aktifLayanan?->icon ?? $aktifKategori?->icon ?? 'fa-clinic-medical';
    $dokterUrl = null;
    if ($dokterTerkait) {
        $dokterUrl = $dokterTerkait->spesialis_id
            ? route('dokter.by-spesialis', $dokterTerkait->spesialis_id).'#dokter-'.$dokterTerkait->id
            : route('dokter').'#dokter-'.$dokterTerkait->id;
    }
    // Mode: semua pelayanan (tidak ada kategori aktif)
    $modeAll = !$aktifKategori;
@endphp

{{-- ── Hero Banner ───────────────────────────────────── --}}
@php
    $heroStyle = $banner->gambar_url
        ? 'background-image: linear-gradient(135deg, ' . ($banner->warna_awal ?? '#00521f') . 'ee, ' . ($banner->warna_akhir ?? '#00b04f') . 'cc), url(\'' . $banner->gambar_url . '\'); background-size: cover; background-position: center;'
        : 'background: linear-gradient(135deg, ' . ($banner->warna_awal ?? '#00521f') . ' 0%, ' . ($banner->warna_akhir ?? '#00b04f') . ' 100%);';
@endphp
<div class="ply-page-hero" style="{{ $heroStyle }}">
    <div class="ply-page-hero-inner">
        @if($banner->label_atas)
        <span style="display:inline-block;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);color:#fff;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:4px 12px;border-radius:50px;margin-bottom:10px;">
            {{ $banner->label_atas }}
        </span>
        @endif
        <h1>{{ $banner->judul ?? $judulHalaman }}</h1>
        @if($banner->subjudul)
        <p style="color:rgba(255,255,255,.85);font-size:14px;margin-top:6px;max-width:560px;line-height:1.6;">{{ $banner->subjudul }}</p>
        @endif
        <nav class="ply-page-hero-breadcrumb" style="margin-top:12px;" aria-label="Breadcrumb">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> Beranda</a>
            <span class="sep">›</span>
            @if($aktifKategori)
                <a href="{{ route('layanan') }}">Pelayanan</a>
                <span class="sep">›</span>
                <span style="color:#fff;font-weight:700;">{{ $aktifKategori->nama_kategori }}</span>
            @elseif($aktifLayanan)
                <a href="{{ route('layanan') }}">Pelayanan</a>
                <span class="sep">›</span>
                <span style="color:#fff;font-weight:700;">{{ $aktifLayanan->nama_layanan }}</span>
            @else
                <span style="color:#fff;font-weight:700;">Semua Pelayanan</span>
            @endif
        </nav>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODE: SEMUA PELAYANAN — grid kartu kategori
═══════════════════════════════════════════════════════ --}}
@if($modeAll)
<section style="background:#f8fffe;padding:48px 0 60px;">
    <div class="max-w-screen-xl mx-auto px-4">

        {{-- Intro --}}
        <div style="text-align:center;margin-bottom:40px;">
            <span style="display:inline-block;background:#ecfdf5;border:1px solid #a7f3d0;color:#059669;font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;padding:4px 14px;border-radius:50px;margin-bottom:12px;">
                <i class="fas fa-hospital-alt" style="margin-right:4px;"></i> Layanan Medis
            </span>
            <h2 style="font-size:clamp(22px,3vw,30px);font-weight:900;color:#111827;margin-bottom:10px;">Pelayanan Unggulan Kami</h2>
            <p style="font-size:15px;color:#6b7280;max-width:560px;margin:0 auto;line-height:1.7;">
                Kami menyediakan berbagai layanan kesehatan komprehensif, didukung dokter spesialis berpengalaman dan fasilitas medis modern.
            </p>
        </div>

        {{-- Grid kartu kategori --}}
        @if($kategoriList->isNotEmpty())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;">
            @foreach($kategoriList as $kat)
            @php $icon = $kat->icon ?? 'fa-clinic-medical'; @endphp
            <a href="{{ route('layanan.by-kategori', $kat->id) }}"
               class="ply-kat-card">
                <div class="ply-kat-card-icon">
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div class="ply-kat-card-body">
                    <h3 class="ply-kat-card-title">{{ $kat->nama_kategori }}</h3>
                    @if($kat->deskripsi)
                    <p class="ply-kat-card-desc">{{ Str::limit($kat->deskripsi, 90) }}</p>
                    @endif
                    @if($kat->layanansAktif->isNotEmpty())
                    <div class="ply-kat-card-tags">
                        @foreach($kat->layanansAktif->take(3) as $lItem)
                        <span class="ply-kat-tag">{{ $lItem->nama_layanan }}</span>
                        @endforeach
                        @if($kat->layanansAktif->count() > 3)
                        <span class="ply-kat-tag ply-kat-tag-more">+{{ $kat->layanansAktif->count() - 3 }} lainnya</span>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="ply-kat-card-arrow">
                    <i class="fas fa-arrow-right"></i>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div style="text-align:center;padding:60px 0;color:#9ca3af;">
            <i class="fas fa-hospital" style="font-size:48px;margin-bottom:16px;display:block;"></i>
            <p>Belum ada kategori pelayanan.</p>
        </div>
        @endif

        {{-- MCU Banner --}}
        <div style="margin-top:40px;background:linear-gradient(135deg,#166534,#00b04f);border-radius:20px;padding:32px 36px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div>
                <p style="color:rgba(255,255,255,.75);font-size:12px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:6px;">Kesehatan Preventif</p>
                <h3 style="color:#fff;font-size:22px;font-weight:900;margin-bottom:8px;">Medical Check-Up</h3>
                <p style="color:rgba(255,255,255,.8);font-size:14px;max-width:480px;line-height:1.6;">Deteksi dini penyakit untuk hidup lebih sehat. Tersedia paket MCU untuk individu, eksekutif, pranikah, dan karyawan perusahaan.</p>
            </div>
            <a href="{{ route('mcu') }}" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:#00b04f;font-weight:800;font-size:14px;padding:12px 24px;border-radius:10px;white-space:nowrap;transition:all .2s;">
                <i class="fas fa-heartbeat"></i> Lihat Paket MCU
            </a>
        </div>

    </div>
</section>

{{-- ═══════════════════════════════════════════════════════
     MODE: DETAIL KATEGORI / LAYANAN — sidebar + konten
═══════════════════════════════════════════════════════ --}}
@else
<section style="background:#f8fffe;padding:40px 0 60px;">
    <div class="max-w-screen-xl mx-auto px-4">
        <div style="display:grid;grid-template-columns:280px 1fr;gap:28px;align-items:start;">

            {{-- ── SIDEBAR ──────────────────────────────── --}}
            <aside class="ply-sidebar-wrap">

                {{-- Daftar kategori --}}
                <div class="ply-side-box" style="margin-bottom:16px;">
                    <div class="ply-side-head">
                        <span class="ply-side-icon"><i class="fas fa-hospital-alt"></i></span>
                        <span>Kategori Pelayanan</span>
                    </div>
                    <ul class="ply-side-list">
                        <li>
                            <a href="{{ route('layanan') }}" class="ply-side-link">
                                <i class="fas fa-th-large"></i> Semua Pelayanan
                            </a>
                        </li>
                        @foreach($kategoriList as $kat)
                        <li>
                            <a href="{{ route('layanan.by-kategori', $kat->id) }}"
                               class="ply-side-link {{ ($aktifKategori && $aktifKategori->id == $kat->id) ? 'is-active' : '' }}">
                                <i class="fas {{ $kat->icon ?? 'fa-clinic-medical' }}" style="width:14px;text-align:center;opacity:.7;"></i>
                                {{ $kat->nama_kategori }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Sub-layanan dalam kategori ini --}}
                @if($aktifKategori && $layanans->isNotEmpty())
                <div class="ply-side-box" style="margin-bottom:16px;">
                    <div class="ply-side-head">
                        <span class="ply-side-icon"><i class="fas {{ $katIcon }}"></i></span>
                        <span>{{ $aktifKategori->nama_kategori }}</span>
                    </div>
                    <ul class="ply-side-list">
                        @foreach($layanans as $l)
                        <li>
                            <a href="{{ route('layanan.detail', [$aktifKategori->id, $l->id]) }}"
                               class="ply-side-link {{ ($aktifLayanan && $aktifLayanan->id == $l->id) ? 'is-active' : '' }}">
                                <i class="fas {{ $l->icon ?? $katIcon }}" style="width:14px;text-align:center;opacity:.7;"></i>
                                {{ $l->nama_layanan }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

            </aside>

            {{-- ── KONTEN KANAN ──────────────────────────── --}}
            <div>
                <div class="ply-info-card">
                    <div class="ply-info-card-band"></div>
                    <div class="ply-info-card-body">

                        {{-- Label --}}
                        <p class="ply-info-label">
                            <i class="fas {{ $katIcon }}"></i>
                            {{ strtoupper($labelKartu) }}
                        </p>

                        {{-- Judul --}}
                        <h2 class="ply-info-title">{{ $judulKartu }}</h2>
                        <div class="ply-info-divider"></div>

                        {{-- Gambar --}}
                        @if($aktifLayanan?->gambar)
                        <img src="{{ Storage::url($aktifLayanan->gambar) }}" alt="{{ $aktifLayanan->nama_layanan }}" class="ply-info-img">
                        @elseif($aktifKategori?->gambar)
                        <img src="{{ Storage::url($aktifKategori->gambar) }}" alt="{{ $aktifKategori->nama_kategori }}" class="ply-info-img">
                        @endif

                        {{-- Deskripsi --}}
                        <div class="ply-info-body">
                            {!! nl2br(e($isiKartu)) !!}
                        </div>

                        {{-- Referral dokter --}}
                        @if($dokterTerkait && $dokterUrl)
                        <div class="ply-info-dokter">
                            <span class="ply-info-dokter-icon"><i class="fas fa-user-md"></i></span>
                            <div>
                                Konsultasikan kondisi Anda dengan
                                <a href="{{ $dokterUrl }}">dr. {{ $dokterTerkait->nama_dokter }}</a>.
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Grid sub-layanan (jika di halaman kategori, bukan detail) --}}
                @if($aktifKategori && !$aktifLayanan && $layanans->isNotEmpty())
                <div style="margin-top:24px;">
                    <h3 style="font-size:17px;font-weight:800;color:#111827;margin-bottom:14px;">
                        <i class="fas fa-list-ul" style="color:#00b04f;margin-right:6px;"></i>
                        Layanan dalam {{ $aktifKategori->nama_kategori }}
                    </h3>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
                        @foreach($layanans as $l)
                        <a href="{{ route('layanan.detail', [$aktifKategori->id, $l->id]) }}"
                           class="ply-sub-card">
                            <span class="ply-sub-icon">
                                <i class="fas {{ $l->icon ?? $katIcon }}"></i>
                            </span>
                            <span class="ply-sub-name">{{ $l->nama_layanan }}</span>
                            <i class="fas fa-chevron-right" style="font-size:10px;color:#86efac;margin-left:auto;flex-shrink:0;"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</section>
@endif
