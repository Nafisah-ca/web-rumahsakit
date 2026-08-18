<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ $metaDesc ?? ($setting_global->motto ?? 'RS Sari Sehat - Melayani dengan Kasih Sayang') }}">
<title>{{ $title ?? $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }} | {{ $setting_global->motto ?? 'Melayani dengan Kasih Sayang' }}</title>
@if($setting_global->favicon ?? null)
<link rel="icon" type="image/x-icon" href="{{ Storage::url($setting_global->favicon) }}">
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css','resources/js/app.js'])
@else
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans','sans-serif']}}}}</script>
    <link rel="stylesheet" href="{{ asset('css/fallback.css') }}">
@endif
@stack('styles')
<style>
/* ===== TOPBAR Z-INDEX FIX ===== */
.topbar { position: relative; z-index: 200; }
#user-dropdown-wrap { position: relative; }

/* ===== BOTTOM NAV MOBILE ===== */
#bottom-nav {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background: #fff;
    border-top: 1px solid #e5e7eb;
    box-shadow: 0 -4px 20px rgba(0,0,0,.08);
    padding-bottom: env(safe-area-inset-bottom);
}
#bottom-nav .bn-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    height: 60px;
}
#bottom-nav .bn-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    color: #94a3b8;
    text-decoration: none;
    font-family: inherit;
    transition: color .15s;
    padding: 4px 2px;
}
#bottom-nav .bn-item i {
    font-size: 20px;
    line-height: 1;
}
#bottom-nav .bn-item span {
    font-size: 10px;
    font-weight: 700;
    line-height: 1;
    white-space: nowrap;
}
#bottom-nav .bn-item.active { color: #16a34a; }
#bottom-nav .bn-item:hover  { color: #16a34a; }

/* Tampilkan bottom nav & spacer hanya di mobile */
@media (max-width: 1023px) {
    #bottom-nav { display: block; }
    #bottom-nav-spacer { display: block; }
}

/* Geser tombol float agar tidak tertutup bottom nav di mobile */
@media (max-width: 1023px) {
    #btn-back-to-top { bottom: 76px !important; }
    #btn-whatsapp    { bottom: 136px !important; }
}

/* Sub-dropdown dokter */
.nav-dropdown-sub { position: relative; }
</style>
</head>
<body class="font-sans antialiased bg-white text-gray-800">

{{-- ===== TOPBAR ===== --}}
<div class="topbar hidden md:block">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-between h-10">
            <div class="flex items-center gap-5 text-xs text-gray-600">
                <a href="{{ route('kontak') }}" class="flex items-center gap-1.5 hover:text-green-600 transition-colors font-semibold">
                    <i class="fas fa-map-marker-alt text-green-600"></i> Lokasi
                </a>
                <a href="{{ route('live.antrian') }}" class="flex items-center gap-2 hover:text-green-600 transition-colors font-semibold">
                    <span class="live-badge">Live</span> Live Antrian
                </a>
                <span class="flex items-center gap-1.5 text-gray-500" id="jam-sholat">
                    <i class="fas fa-mosque text-green-600 text-xs"></i>
                    <span id="waktu-sholat">...</span>
                </span>
            </div>
            <div class="flex items-center gap-4">
                <div class="w-px h-4 bg-gray-200"></div>
                @auth
                {{-- USER DROPDOWN TOPBAR --}}
                <div class="relative" id="user-dropdown-wrap">
                    <button id="user-dropdown-btn"
                        class="flex items-center gap-1.5 text-xs font-semibold text-gray-700 hover:text-green-600 transition-colors focus:outline-none"
                        aria-expanded="false" aria-haspopup="true">
                        {{-- Avatar: foto jika ada, icon jika belum --}}
                        @if(Auth::user()->foto)
                            <img src="{{ Storage::url(Auth::user()->foto) }}"
                                 alt="{{ Auth::user()->nama }}"
                                 class="w-6 h-6 rounded-full object-cover flex-shrink-0 border border-white/30">
                        @else
                            <div class="w-6 h-6 rounded-full bg-green-600 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user text-white text-[10px]"></i>
                            </div>
                        @endif
                        <span>{{ Str::limit(Auth::user()->nama, 14) }}</span>
                        <i class="fas fa-chevron-down text-[9px] opacity-70 transition-transform duration-200" id="user-chevron"></i>
                    </button>

                    {{-- DROPDOWN PANEL --}}
                    <div id="user-dropdown-menu"
                         class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-[9999]"
                         style="min-width:210px">
                        {{-- User info --}}
                        <div class="px-4 py-3 border-b border-gray-50">
                            <p class="text-sm font-extrabold text-gray-900 truncate">{{ Auth::user()->nama }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        @if(Auth::user()->isPasien())
                        <a href="{{ route('portal.profil') }}?tab=profil"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-user-edit text-green-500 w-4 text-center flex-shrink-0"></i> Profil Saya
                        </a>
                        <a href="{{ route('portal.profil') }}?tab=riwayat"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-calendar-check text-green-500 w-4 text-center flex-shrink-0"></i> Riwayat Poliklinik
                        </a>
                        <a href="{{ route('portal.profil') }}?tab=penjamin"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-shield-halved text-green-500 w-4 text-center flex-shrink-0"></i> Penjamin & Asuransi
                        </a>
                        @else
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-gauge-high text-green-500 w-4 text-center flex-shrink-0"></i> Dashboard
                        </a>
                        @endif
                        <div class="border-t border-gray-50 mt-1 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors text-left">
                                    <i class="fas fa-sign-out-alt w-4 text-center flex-shrink-0"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-green-600 transition-colors">
                    <i class="fas fa-user text-xs"></i> Masuk
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- ===== NAVBAR UTAMA ===== --}}
<nav id="navbar-main" class="navbar-main">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            {{-- LOGO --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 flex-shrink-0 group min-w-0">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-md group-hover:scale-105 transition-transform flex-shrink-0 overflow-hidden">
                    @if($setting_global->logo ?? null)
                        <img src="{{ Storage::url($setting_global->logo) }}" alt="{{ $setting_global->nama_rumahsakit ?? 'Logo RS' }}" class="w-full h-full object-contain p-0.5">
                    @else
                        <i class="fas fa-hospital-alt text-green-600 text-lg"></i>
                    @endif
                </div>
                <div class="min-w-0">
                    <div class="text-white font-extrabold text-lg leading-tight tracking-tight">{{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}</div>
                    <div class="text-green-100 text-[10px] font-semibold tracking-widest uppercase">{{ $setting_global->motto ?? 'Melayani dengan Kasih Sayang' }}</div>
                </div>
            </a>

            {{-- DESKTOP NAV --}}
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>

                <div class="nav-dropdown">
                    <button class="nav-item flex items-center gap-1 {{ request()->routeIs('layanan') || request()->routeIs('igd') ? 'active' : '' }}">
                        Pelayanan <i class="fas fa-chevron-down text-[10px] opacity-80"></i>
                    </button>
                    <div class="nav-dropdown-menu" style="min-width:220px">

                        {{-- Daftar Pelayanan — dengan sub-menu kategori di kanan (mirip Daftar Poliklinik) --}}
                        <div class="nav-dropdown-sub" style="position:relative"
                             onmouseenter="showNavSub('sub-pelayanan-menu')"
                             onmouseleave="hideNavSub('sub-pelayanan-menu')">
                            <a href="{{ route('layanan') }}"
                               class="nav-dropdown-item flex items-center justify-between">
                                <span><i class="fas fa-stethoscope text-green-500 w-4 text-center"></i> Daftar Pelayanan</span>
                                @if(isset($nav_kategori_layanan) && $nav_kategori_layanan->isNotEmpty())
                                <i class="fas fa-chevron-right text-[9px] text-gray-400 ml-2"></i>
                                @endif
                            </a>
                            {{-- Sub-menu kanan: daftar kategori layanan --}}
                            @if(isset($nav_kategori_layanan) && $nav_kategori_layanan->isNotEmpty())
                            <div id="sub-pelayanan-menu"
                                 style="display:none;position:absolute;left:100%;top:0;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.12);border:1px solid #e5e7eb;min-width:200px;z-index:9999;padding:6px 0"
                                 onmouseenter="showNavSub('sub-pelayanan-menu')"
                                 onmouseleave="hideNavSub('sub-pelayanan-menu')">
                                @foreach($nav_kategori_layanan as $navKat)
                                <a href="{{ route('layanan.by-kategori', $navKat->id) }}"
                                   class="nav-dropdown-item">
                                    <i class="fas {{ $navKat->icon ?? 'fa-hospital' }} text-green-500 w-4 text-center"></i>
                                    {{ $navKat->nama_kategori }}
                                </a>
                                @endforeach
                                <div style="border-top:1px solid #f1f5f9;margin:4px 0"></div>
                                <a href="{{ route('layanan') }}" class="nav-dropdown-item">
                                    <i class="fas fa-list text-green-500 w-4 text-center"></i> Semua Pelayanan
                                </a>
                            </div>
                            @endif
                        </div>

                        {{-- Profil Pelayanan --}}
                        <a href="{{ route('layanan') }}" class="nav-dropdown-item">
                            <i class="fas fa-hospital text-green-500 w-4 text-center"></i> Profil Pelayanan
                        </a>

                        {{-- Medical Check-Up --}}
                        <a href="{{ route('mcu') }}" class="nav-dropdown-item">
                            <i class="fas fa-clipboard-check text-green-500 w-4 text-center"></i> Medical Check-Up
                        </a>

                    </div>
                </div>

                <div class="nav-dropdown">
                    <button class="nav-item flex items-center gap-1">
                        Dokter <i class="fas fa-chevron-down text-[10px] opacity-80"></i>
                    </button>
                    <div class="nav-dropdown-menu" style="min-width:220px">
                        {{-- Daftar Poliklinik (dengan sub-menu spesialisasi) --}}
                        <div class="nav-dropdown-sub" style="position:relative">
                            <a href="{{ route('dokter') }}" class="nav-dropdown-item flex items-center justify-between"
                               id="sub-poliklinik-trigger"
                               onmouseenter="document.getElementById('sub-poliklinik-menu').style.display='block'"
                               onmouseleave="setTimeout(()=>{ if(!document.getElementById('sub-poliklinik-menu').matches(':hover')) document.getElementById('sub-poliklinik-menu').style.display='none' },120)">
                                <span><i class="fas fa-calendar-check text-green-500 w-4 text-center"></i> Daftar Poliklinik</span>
                                <i class="fas fa-chevron-right text-[9px] text-gray-400 ml-2"></i>
                            </a>
                            {{-- Sub-menu spesialisasi --}}
                            <div id="sub-poliklinik-menu"
                                 style="display:none;position:absolute;left:100%;top:0;background:#fff;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.12);border:1px solid #e5e7eb;min-width:200px;z-index:9999;padding:6px 0"
                                 onmouseenter="this.style.display='block'"
                                 onmouseleave="this.style.display='none'">
                                @isset($nav_spesialisasi)
                                @foreach($nav_spesialisasi as $sp)
                                <a href="{{ route('dokter.by-spesialis', $sp->id) }}"
                                   class="nav-dropdown-item flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                    <i class="fas fa-stethoscope text-green-500 w-4 text-center text-xs"></i>
                                    {{ $sp->nama_spesialis }}
                                </a>
                                @endforeach
                                @endisset
                                <div style="border-top:1px solid #f1f5f9;margin:4px 0"></div>
                                <a href="{{ route('dokter') }}" class="nav-dropdown-item flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 transition-colors">
                                    <i class="fas fa-list text-green-500 w-4 text-center text-xs"></i> Semua Poliklinik
                                </a>
                            </div>
                        </div>
                        {{-- Profil Dokter --}}
                        <a href="{{ route('dokter') }}" class="nav-dropdown-item">
                            <i class="fas fa-user-md text-green-500 w-4 text-center"></i> Profil Dokter
                        </a>
                        {{-- Layanan Online --}}
                        <a href="{{ route('dokter.online') }}" class="nav-dropdown-item">
                            <i class="fas fa-laptop-medical text-green-500 w-4 text-center"></i> Layanan Online
                        </a>
                    </div>
                </div>

                <a href="{{ route('promo') }}" class="nav-item {{ request()->routeIs('promo*') ? 'active' : '' }}">Promo</a>

                <div class="nav-dropdown">
                    <button class="nav-item flex items-center gap-1">
                        Informasi <i class="fas fa-chevron-down text-[10px] opacity-80"></i>
                    </button>
                    <div class="nav-dropdown-menu">
                        <a href="{{ route('artikel') }}" class="nav-dropdown-item">
                            <i class="fas fa-newspaper text-green-500 w-4 text-center"></i> Artikel
                        </a>
                        <a href="{{ route('event') }}" class="nav-dropdown-item">
                            <i class="fas fa-calendar-alt text-green-500 w-4 text-center"></i> Event
                        </a>
                        <a href="{{ route('tentang') }}" class="nav-dropdown-item">
                            <i class="fas fa-info-circle text-green-500 w-4 text-center"></i> Tentang Kami
                        </a>
                    </div>
                </div>

                <a href="{{ route('kontak') }}" class="nav-item {{ request()->routeIs('kontak') ? 'active' : '' }}">Hubungi Kami</a>
            </div>

            {{-- DAFTAR BTN --}}
            <div class="hidden lg:flex items-center gap-3">
                <a href="{{ route('dokter') }}" class="flex items-center gap-2 bg-white text-green-700 px-4 py-2 rounded-lg font-bold text-sm hover:bg-green-50 transition-all shadow-sm">
                    <i class="fas fa-calendar-check text-green-600"></i> Daftar Poliklinik
                </a>
            </div>

            {{-- HAMBURGER --}}
            <button id="hamburger-btn" class="lg:hidden text-white p-2 rounded-lg hover:bg-white/15 transition-colors" aria-label="Menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</nav>

{{-- MOBILE MENU PANEL --}}
<div id="mobile-menu-panel">
    <div id="mobile-overlay"></div>
    <div id="mobile-drawer">
        <div class="bg-green-600 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center overflow-hidden">
                    @if($setting_global->logo ?? null)
                        <img src="{{ Storage::url($setting_global->logo) }}" alt="{{ $setting_global->nama_rumahsakit ?? 'Logo RS' }}" class="w-full h-full object-contain p-0.5">
                    @else
                        <i class="fas fa-hospital-alt text-green-600"></i>
                    @endif
                </div>
                <div>
                    <div class="text-white font-bold text-sm">{{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}</div>
                    <div class="text-green-100 text-[10px]">{{ $setting_global->motto ?? 'Melayani dengan Kasih Sayang' }}</div>
                </div>
            </div>
            <button id="close-drawer" class="text-white p-1"><i class="fas fa-times text-lg"></i></button>
        </div>
        <div class="p-4 space-y-1">
            {{-- Beranda --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas fa-home text-green-500 w-4 text-center"></i> Beranda
            </a>

            {{-- Pelayanan accordion --}}
            <div>
                <button onclick="toggleMobileAcc('acc-pelayanan')"
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                    <span class="flex items-center gap-3">
                        <i class="fas fa-stethoscope text-green-500 w-4 text-center"></i> Pelayanan
                    </span>
                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform" id="acc-pelayanan-icon"></i>
                </button>
                <div id="acc-pelayanan" style="display:none" class="pl-4 space-y-0.5 mt-1">
                    <a href="{{ route('layanan') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-green-50 hover:text-green-700 font-bold transition-colors text-sm">
                        <i class="fas fa-hospital text-green-500 w-4 text-center text-xs"></i> Profil Pelayanan
                    </a>
                    @if(isset($nav_kategori_layanan) && $nav_kategori_layanan->isNotEmpty())
                        @foreach($nav_kategori_layanan as $mKat)
                        <a href="{{ route('layanan.by-kategori', $mKat->id) }}"
                           class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                            <i class="fas {{ $mKat->icon ?? 'fa-hospital' }} text-green-400 w-4 text-center text-xs"></i>
                            {{ $mKat->nama_kategori }}
                        </a>
                        @endforeach
                    @endif
                    <a href="{{ route('mcu') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-gray-600 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                        <i class="fas fa-clipboard-check text-green-400 w-4 text-center text-xs"></i> Medical Check-Up
                    </a>
                </div>
            </div>

            {{-- Dokter --}}
            <a href="{{ route('dokter') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas fa-user-md text-green-500 w-4 text-center"></i> Dokter
            </a>
            <a href="{{ route('promo') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas fa-tags text-green-500 w-4 text-center"></i> Promo
            </a>
            <a href="{{ route('artikel') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas fa-newspaper text-green-500 w-4 text-center"></i> Artikel
            </a>
            <a href="{{ route('event') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas fa-calendar-alt text-green-500 w-4 text-center"></i> Kegiatan
            </a>
            <a href="{{ route('tentang') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas fa-info-circle text-green-500 w-4 text-center"></i> Tentang Kami
            </a>
            <a href="{{ route('kontak') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas fa-phone text-green-500 w-4 text-center"></i> Hubungi Kami
            </a>
            <div class="pt-3">
                <a href="{{ route('dokter') }}" class="block w-full text-center btn-green py-3 rounded-xl font-bold">
                    <i class="fas fa-calendar-check mr-2"></i>Daftar Poliklinik
                </a>
            </div>
            <div class="pt-3 mt-3 border-t border-gray-100">
                @auth
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-100">
                    @if(Auth::user()->foto)
                        <img src="{{ Storage::url(Auth::user()->foto) }}"
                             alt="{{ Auth::user()->nama }}"
                             class="w-9 h-9 rounded-full object-cover flex-shrink-0 border-2 border-green-200">
                    @else
                        <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-xs"></i>
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="text-gray-900 text-sm font-extrabold truncate">{{ Auth::user()->nama }}</p>
                        <p class="text-green-700 text-xs font-semibold">{{ Auth::user()->role_label }}</p>
                    </div>
                </div>
                @if(Auth::user()->isPasien())
                <a href="{{ route('portal.profil') }}?tab=profil" class="mt-2 flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-user-edit text-green-500 w-4 text-center"></i> Profil Saya
                </a>
                <a href="{{ route('portal.profil') }}?tab=riwayat" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-calendar-check text-green-500 w-4 text-center"></i> Riwayat Poliklinik
                </a>
                <a href="{{ route('portal.profil') }}?tab=penjamin" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-shield-halved text-green-500 w-4 text-center"></i> Penjamin & Asuransi
                </a>
                @else
                <a href="{{ route('dashboard') }}" class="mt-2 flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-gauge-high text-green-500 w-4 text-center"></i> Dashboard
                </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-50 text-red-600 border border-red-100 font-bold text-sm">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-green-50 text-green-700 border border-green-100 font-bold text-sm">
                    <i class="fas fa-user"></i> Masuk
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- MAIN --}}
<main>@yield('content')</main>

{{-- Social media section dihapus --}}

{{-- ===== FOOTER ===== --}}
<footer class="footer-main text-white">
    <div class="max-w-screen-xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- Brand --}}
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-green-600 flex items-center justify-center overflow-hidden">
                        @if($setting_global->logo ?? null)
                            <img src="{{ Storage::url($setting_global->logo) }}" alt="{{ $setting_global->nama_rumahsakit ?? 'Logo RS' }}" class="w-full h-full object-contain p-0.5">
                        @else
                            <i class="fas fa-hospital-alt text-white text-lg"></i>
                        @endif
                    </div>
                    <div>
                        <div class="font-extrabold text-base text-white">{{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}</div>
                        <div class="text-green-400 text-[10px] font-semibold uppercase tracking-wider">{{ $setting_global->motto ?? 'Melayani dengan Kasih Sayang' }}</div>
                    </div>
                </div>
                @if($setting_global->footer ?? null)
                <p class="text-gray-400 text-sm leading-relaxed mb-5">{{ $setting_global->footer }}</p>
                @else
                <p class="text-gray-400 text-sm leading-relaxed mb-5">
                    Rumah sakit yang mengutamakan pelayanan kesehatan berkualitas dengan penuh kasih sayang.
                </p>
                @endif
                <div class="space-y-2 text-sm">
                    @if($setting_global->telepon ?? null)
                    <div class="flex items-center gap-3">
                        @php
                            $telBrand = $setting_global->telepon;
                            $waBrand  = preg_replace('/[^0-9]/', '', $telBrand);
                            if (str_starts_with($waBrand, '0')) $waBrand = '62' . substr($waBrand, 1);
                            if (empty($waBrand)) $waBrand = '6289501895170';
                        @endphp
                        <i class="fab fa-whatsapp text-green-400 w-4 text-base"></i>
                        <a href="https://wa.me/{{ $waBrand }}" target="_blank" rel="noopener"
                           class="text-gray-300 hover:text-green-400 transition-colors">
                            {{ $telBrand }}
                        </a>
                    </div>
                    @else
                    <div class="flex items-center gap-3">
                        <i class="fab fa-whatsapp text-green-400 w-4 text-base"></i>
                        <a href="https://wa.me/6289501895170" target="_blank" rel="noopener"
                           class="text-gray-300 hover:text-green-400 transition-colors">
                            +62 895-0189-5170
                        </a>
                    </div>
                    @endif
                    @if($setting_global->email ?? null)
                    <div class="flex items-center gap-3">
                        @php
                            $gmailLink = 'https://mail.google.com/mail/?view=cm&to=' . urlencode($setting_global->email);
                        @endphp
                        <i class="fas fa-envelope text-green-500 w-4"></i>
                        <a href="{{ $gmailLink }}" target="_blank" rel="noopener" class="text-gray-300 hover:text-white transition-colors">{{ $setting_global->email }}</a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Menu --}}
            <div>
                <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Menu</h4>
                <ul class="space-y-2.5">
                    @foreach([['Tentang Kami','tentang'],['Jadwal Dokter','dokter'],['Promo','promo'],['Hubungi Kami','kontak']] as [$lbl,$rt])
                    <li>
                        <a href="{{ route($rt) }}" class="text-gray-400 hover:text-green-400 text-sm transition-colors flex items-center gap-2">
                            <i class="fas fa-chevron-right text-green-600 text-xs"></i> {{ $lbl }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Informasi --}}
            <div>
                <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Informasi</h4>
                <ul class="space-y-2.5">
                    @foreach([['Promo','promo'],['Artikel','artikel'],['Jadwal Kegiatan','event'],['Medical Check-Up','mcu'],['Live Antrian','live.antrian']] as [$lbl,$rt])
                    <li>
                        <a href="{{ route($rt) }}" class="text-gray-400 hover:text-green-400 text-sm transition-colors flex items-center gap-2">
                            <i class="fas fa-chevron-right text-green-600 text-xs"></i> {{ $lbl }}
                        </a>
                    </li>
                    @endforeach
                    <li>
                        <a href="{{ route('faq') }}" class="text-gray-400 hover:text-green-400 text-sm transition-colors flex items-center gap-2">
                            <i class="fas fa-chevron-right text-green-600 text-xs"></i> FAQ
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Kontak</h4>
                <ul class="space-y-2.5">
                    @if($setting_global->alamat ?? null)
                    <li class="text-gray-400 text-sm flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-green-500 text-xs w-3 mt-1 flex-shrink-0"></i>
                        <span>{{ $setting_global->alamat }}</span>
                    </li>
                    @endif
                    @if($setting_global->telepon ?? null)
                    <li class="text-gray-400 text-sm flex items-center gap-2">
                        @php
                            $telKontak = $setting_global->telepon;
                            $waKontak  = preg_replace('/[^0-9]/', '', $telKontak);
                            if (str_starts_with($waKontak, '0')) $waKontak = '62' . substr($waKontak, 1);
                            if (empty($waKontak)) $waKontak = '6289501895170';
                        @endphp
                        <i class="fab fa-whatsapp text-green-400 text-sm w-3"></i>
                        <a href="https://wa.me/{{ $waKontak }}" target="_blank" rel="noopener"
                           class="hover:text-green-400 transition-colors">
                            {{ $telKontak }}
                        </a>
                    </li>
                    @else
                    <li class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="fab fa-whatsapp text-green-400 text-sm w-3"></i>
                        <a href="https://wa.me/6289501895170" target="_blank" rel="noopener"
                           class="hover:text-green-400 transition-colors">+62 895-0189-5170</a>
                    </li>
                    @endif
                    @if($setting_global->email ?? null)
                    <li class="text-gray-400 text-sm flex items-center gap-2">
                        @php
                            $gmailLinkKontak = 'https://mail.google.com/mail/?view=cm&to=' . urlencode($setting_global->email);
                        @endphp
                        <i class="fas fa-envelope text-green-500 text-xs w-3"></i>
                        <a href="{{ $gmailLinkKontak }}" target="_blank" rel="noopener" class="hover:text-green-400 transition-colors">{{ $setting_global->email }}</a>
                    </li>
                    @endif
                    @if($setting_global->jam_operasional ?? null)
                    <li class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="fas fa-clock text-green-500 text-xs w-3"></i>
                        <span>{{ $setting_global->jam_operasional }}</span>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    {{-- Akreditasi --}}
    <div class="border-t border-white/10">
        <div class="max-w-screen-xl mx-auto px-4 py-10">
            <div class="flex flex-wrap justify-center gap-4 items-center">
                <span class="text-gray-500 text-xs font-semibold mr-1">Terakreditasi:</span>
                @forelse($akreditasi_footer as $akr)
                <div class="flex items-center gap-1.5 px-3 py-1 bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 transition-colors">
                    @if($akr->logo)
                    <img src="{{ $akr->logo_url }}"
                         alt="{{ $akr->nama }}"
                         style="height:14px;width:auto;object-fit:contain;filter:brightness(0) invert(1);opacity:.75;flex-shrink:0">
                    @endif
                    <span class="text-xs text-green-400 font-bold whitespace-nowrap">{{ $akr->nama }}</span>
                </div>
                @empty
                @foreach(['KARS Paripurna','ISO 9001:2015','SNARS Edisi 1.1','BPJS Kesehatan','Kemenkes RI'] as $a)
                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-green-400 font-bold">{{ $a }}</span>
                @endforeach
                @endforelse
            </div>
        </div>
    </div>
    <div class="border-t border-white/10">
        <div class="max-w-screen-xl mx-auto px-4 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-gray-500">
                <span>&copy; {{ date('Y') }} {{ $setting_global->copyright ?? ($setting_global->nama_rumahsakit ?? 'RS Sari Sehat') . '. All rights reserved.' }}</span>
                <div class="flex gap-4">
                    <a href="{{ route('kebijakan-privasi') }}" class="hover:text-green-400 transition-colors">Kebijakan Privasi</a>
                    <a href="{{ route('syarat-ketentuan') }}" class="hover:text-green-400 transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </div>
</footer>

{{-- BACK TO TOP --}}
<button id="btn-back-to-top" aria-label="Kembali ke atas"
    class="fixed bottom-6 right-6 w-11 h-11 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-xl opacity-0 pointer-events-none transition-all duration-300 z-40 flex items-center justify-center">
    <i class="fas fa-arrow-up text-sm"></i>
</button>

{{-- WHATSAPP FLOAT --}}
@php
    $floatWa = $setting_global->telepon ?? '';
    $floatWa = preg_replace('/[^0-9]/', '', $floatWa);
    if (str_starts_with($floatWa, '0')) $floatWa = '62' . substr($floatWa, 1);
    if (empty($floatWa)) $floatWa = '6289501895170';
@endphp
<a id="btn-whatsapp" href="https://wa.me/{{ $floatWa }}" target="_blank" rel="noopener" aria-label="WhatsApp"
   class="fixed bottom-20 right-6 w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-xl flex items-center justify-center transition-all hover:scale-110 z-40 group">
    <i class="fab fa-whatsapp text-2xl"></i>
    <span class="absolute right-full mr-3 px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
        Chat WhatsApp
    </span>
</a>

{{-- BOTTOM NAV MOBILE --}}
<nav id="bottom-nav">
    <div class="bn-grid">
        <a href="{{ route('home') }}" class="bn-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
        <a href="{{ route('layanan') }}" class="bn-item {{ request()->routeIs('layanan*') ? 'active' : '' }}">
            <i class="fas fa-stethoscope"></i>
            <span>Pelayanan</span>
        </a>
        <a href="{{ route('dokter') }}" class="bn-item {{ request()->routeIs('dokter*') ? 'active' : '' }}">
            <i class="fas fa-user-doctor"></i>
            <span>Dokter</span>
        </a>
        <a href="{{ route('promo') }}" class="bn-item {{ request()->routeIs('promo*') ? 'active' : '' }}">
            <i class="fas fa-tags"></i>
            <span>Promo</span>
        </a>
        <a href="{{ route('artikel') }}" class="bn-item {{ request()->routeIs('artikel*') || request()->routeIs('event*') ? 'active' : '' }}">
            <i class="fas fa-newspaper"></i>
            <span>Informasi</span>
        </a>
        <a href="{{ route('kontak') }}" class="bn-item {{ request()->routeIs('kontak') ? 'active' : '' }}">
            <i class="fas fa-phone"></i>
            <span>Kontak</span>
        </a>
    </div>
</nav>

{{-- Spacer bawah agar konten tidak tertutup bottom nav --}}
<div id="bottom-nav-spacer" style="display:none;height:60px"></div>

@stack('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Navbar scroll
    const navbar = document.getElementById('navbar-main');
    window.addEventListener('scroll', () => {
        navbar && navbar.classList.toggle('scrolled', window.scrollY > 60);
    });

    // Mobile menu
    const hamburger = document.getElementById('hamburger-btn');
    const panel = document.getElementById('mobile-menu-panel');
    const overlay = document.getElementById('mobile-overlay');
    const closeBtn = document.getElementById('close-drawer');
    function openMenu() { panel && panel.classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeMenu() { panel && panel.classList.remove('open'); document.body.style.overflow = ''; }
    hamburger && hamburger.addEventListener('click', openMenu);
    overlay && overlay.addEventListener('click', closeMenu);
    closeBtn && closeBtn.addEventListener('click', closeMenu);

    // Back to top
    const btt = document.getElementById('btn-back-to-top');
    window.addEventListener('scroll', () => {
        if (!btt) return;
        if (window.scrollY > 400) { btt.classList.remove('opacity-0','pointer-events-none'); }
        else { btt.classList.add('opacity-0','pointer-events-none'); }
    });
    btt && btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Fade-up observer
    const fadeEls = document.querySelectorAll('.fade-up');
    if (fadeEls.length) {
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        fadeEls.forEach(el => obs.observe(el));
    }

    // Counter animation
    function animateCount(el) {
        const target = parseInt(el.dataset.count);
        const dur = 1800;
        const step = target / (dur / 16);
        let curr = 0;
        const t = setInterval(() => {
            curr = Math.min(curr + step, target);
            el.textContent = Math.floor(curr).toLocaleString('id-ID');
            if (curr >= target) clearInterval(t);
        }, 16);
    }
    const statsSection = document.getElementById('stats-section');
    if (statsSection) {
        const so = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.querySelectorAll('[data-count]').forEach(animateCount);
                    so.unobserve(e.target);
                }
            });
        }, { threshold: 0.3 });
        so.observe(statsSection);
    }

    // Hero Slider
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let current = 0, timer;
    function goTo(n) {
        slides[current].classList.remove('active');
        dots[current] && dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current] && dots[current].classList.add('active');
    }
    function startAuto() { timer = setInterval(() => goTo(current + 1), 5000); }
    function stopAuto() { clearInterval(timer); }
    if (slides.length) {
        slides[0].classList.add('active');
        dots[0] && dots[0].classList.add('active');
        startAuto();
        document.querySelector('.slide-arrow.prev') && document.querySelector('.slide-arrow.prev').addEventListener('click', () => { stopAuto(); goTo(current - 1); startAuto(); });
        document.querySelector('.slide-arrow.next') && document.querySelector('.slide-arrow.next').addEventListener('click', () => { stopAuto(); goTo(current + 1); startAuto(); });
        dots.forEach((d, i) => d.addEventListener('click', () => { stopAuto(); goTo(i); startAuto(); }));
    }

    // ── Jadwal Sholat (data dari CMS, berganti-ganti) ─────────────────
    (function () {
        const el = document.getElementById('waktu-sholat');
        if (!el) return;

        @php
            $sholatJson = $setting_global->jadwal_sholat ?? '{}';
            $sholatData = json_decode($sholatJson, true) ?? [];
            $sholatArr  = [
                ['label' => 'Subuh',   'waktu' => $sholatData['subuh']   ?? '04:30'],
                ['label' => 'Dzuhur',  'waktu' => $sholatData['dzuhur']  ?? '12:00'],
                ['label' => 'Ashar',   'waktu' => $sholatData['ashar']   ?? '15:20'],
                ['label' => 'Maghrib', 'waktu' => $sholatData['maghrib'] ?? '17:52'],
                ['label' => 'Isya',    'waktu' => $sholatData['isya']    ?? '19:06'],
            ];
        @endphp

        const jadwal = @json($sholatArr);
        let idx = 0;

        // Tentukan index awal: waktu sholat berikutnya
        const now        = new Date();
        const minutesNow = now.getHours() * 60 + now.getMinutes();
        function toMin(t) { const [h,m] = t.split(':').map(Number); return h*60+m; }

        const nextIdx = jadwal.findIndex(j => toMin(j.waktu) > minutesNow);
        idx = nextIdx >= 0 ? nextIdx : 0;

        function show() {
            const j = jadwal[idx];
            el.innerHTML = `<strong style="color:#15803d">${j.label}</strong>&nbsp;${j.waktu}`;
            idx = (idx + 1) % jadwal.length;
        }

        show();
        setInterval(show, 3000); // ganti tiap 3 detik
    })();

    // User dropdown (topbar)
    const dropBtn  = document.getElementById('user-dropdown-btn');
    const dropMenu = document.getElementById('user-dropdown-menu');
    const chevron  = document.getElementById('user-chevron');
    if (dropBtn && dropMenu) {
        dropBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = !dropMenu.classList.contains('hidden');
            dropMenu.classList.toggle('hidden', isOpen);
            dropBtn.setAttribute('aria-expanded', String(!isOpen));
            chevron && chevron.classList.toggle('rotate-180', !isOpen);
        });
        document.addEventListener('click', function (e) {
            if (!dropMenu.classList.contains('hidden')) {
                if (!dropBtn.contains(e.target) && !dropMenu.contains(e.target)) {
                    dropMenu.classList.add('hidden');
                    dropBtn.setAttribute('aria-expanded', 'false');
                    chevron && chevron.classList.remove('rotate-180');
                }
            }
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !dropMenu.classList.contains('hidden')) {
                dropMenu.classList.add('hidden');
                dropBtn.setAttribute('aria-expanded', 'false');
                chevron && chevron.classList.remove('rotate-180');
                dropBtn.focus();
            }
        });
    }

});

// ── Navbar sub-menu helper (kategori layanan & spesialisasi) ──────────
var _navSubTimers = {};
function showNavSub(id) {
    clearTimeout(_navSubTimers[id]);
    var el = document.getElementById(id);
    if (el) el.style.display = 'block';
}
function hideNavSub(id) {
    _navSubTimers[id] = setTimeout(function() {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    }, 120);
}

// ── Mobile accordion Pelayanan ────────────────────────────────────────
function toggleMobileAcc(id) {
    var el   = document.getElementById(id);
    var icon = document.getElementById(id + '-icon');
    if (!el) return;
    var open = el.style.display === 'block';
    el.style.display   = open ? 'none' : 'block';
    if (icon) icon.style.transform = open ? '' : 'rotate(180deg)';
}
</script>
</body>
</html>
