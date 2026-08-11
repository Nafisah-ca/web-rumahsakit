<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?php echo e($metaDesc ?? ($setting_global->motto ?? 'RS Sari Sehat - Melayani dengan Kasih Sayang')); ?>">
<title><?php echo e($title ?? $setting_global->nama_rumahsakit ?? 'RS Sari Sehat'); ?> | <?php echo e($setting_global->motto ?? 'Melayani dengan Kasih Sayang'); ?></title>
<?php if($setting_global->favicon ?? null): ?>
<link rel="icon" type="image/x-icon" href="<?php echo e(Storage::url($setting_global->favicon)); ?>">
<?php endif; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css','resources/js/app.js']); ?>
<?php else: ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans','sans-serif']}}}}</script>
    <link rel="stylesheet" href="<?php echo e(asset('css/fallback.css')); ?>">
<?php endif; ?>
<?php echo $__env->yieldPushContent('styles'); ?>
<style>
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
</style>
</head>
<body class="font-sans antialiased bg-white text-gray-800">


<div class="topbar hidden md:block">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-between h-10">
            <div class="flex items-center gap-5 text-xs text-gray-600">
                <a href="<?php echo e(route('kontak')); ?>" class="flex items-center gap-1.5 hover:text-green-600 transition-colors font-semibold">
                    <i class="fas fa-map-marker-alt text-green-600"></i> Lokasi
                </a>
                <a href="<?php echo e(route('live.antrian')); ?>" class="flex items-center gap-2 hover:text-green-600 transition-colors font-semibold">
                    <span class="live-badge">Live</span> Live Antrian
                </a>
                <span class="flex items-center gap-1.5 text-gray-500" id="jam-sholat">
                    <i class="fas fa-mosque text-green-600 text-xs"></i>
                    <span id="waktu-sholat">Loading...</span>
                </span>
            </div>
            <div class="flex items-center gap-4">
                <a href="https://www.instagram.com/rssarisehat/" target="_blank" rel="noopener" aria-label="Instagram"
                   class="text-gray-500 hover:text-pink-500 transition-colors text-sm"><i class="fab fa-instagram"></i></a>
                <a href="https://www.facebook.com/rssarisehat/" target="_blank" rel="noopener" aria-label="Facebook"
                   class="text-gray-500 hover:text-blue-600 transition-colors text-sm"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.youtube.com/@RSSariSehat" target="_blank" rel="noopener" aria-label="YouTube"
                   class="text-gray-500 hover:text-red-500 transition-colors text-sm"><i class="fab fa-youtube"></i></a>
                <a href="https://www.tiktok.com/@rssarisehat" target="_blank" rel="noopener" aria-label="TikTok"
                   class="text-gray-500 hover:text-gray-900 transition-colors text-sm"><i class="fab fa-tiktok"></i></a>
                <a href="https://open.spotify.com" target="_blank" rel="noopener" aria-label="Spotify"
                   class="text-gray-500 hover:text-green-500 transition-colors text-sm"><i class="fab fa-spotify"></i></a>
                <div class="w-px h-4 bg-gray-200"></div>
                <?php if(auth()->guard()->check()): ?>
                
                <div class="relative" id="user-dropdown-wrap">
                    <button id="user-dropdown-btn"
                        class="flex items-center gap-1.5 text-xs font-semibold text-gray-700 hover:text-green-600 transition-colors focus:outline-none"
                        aria-expanded="false" aria-haspopup="true">
                        <div class="w-6 h-6 rounded-full bg-green-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-[10px]"></i>
                        </div>
                        <span><?php echo e(Str::limit(Auth::user()->nama, 14)); ?></span>
                        <i class="fas fa-chevron-down text-[9px] opacity-70 transition-transform duration-200" id="user-chevron"></i>
                    </button>

                    
                    <div id="user-dropdown-menu"
                         class="hidden absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-[9999]"
                         style="min-width:210px">
                        
                        <div class="px-4 py-3 border-b border-gray-50">
                            <p class="text-sm font-extrabold text-gray-900 truncate"><?php echo e(Auth::user()->nama); ?></p>
                            <p class="text-xs text-gray-400 truncate"><?php echo e(Auth::user()->email); ?></p>
                        </div>
                        <?php if(Auth::user()->isPasien()): ?>
                        <a href="<?php echo e(route('portal.profil')); ?>?tab=profil"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-user-edit text-green-500 w-4 text-center flex-shrink-0"></i> Profil Saya
                        </a>
                        <a href="<?php echo e(route('portal.profil')); ?>?tab=riwayat"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-calendar-check text-green-500 w-4 text-center flex-shrink-0"></i> Riwayat Poliklinik
                        </a>
                        <a href="<?php echo e(route('portal.profil')); ?>?tab=penjamin"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-shield-halved text-green-500 w-4 text-center flex-shrink-0"></i> Penjamin & Asuransi
                        </a>
                        <?php else: ?>
                        <a href="<?php echo e(route('dashboard')); ?>"
                           class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors">
                            <i class="fas fa-gauge-high text-green-500 w-4 text-center flex-shrink-0"></i> Dashboard
                        </a>
                        <?php endif; ?>
                        <div class="border-t border-gray-50 mt-1 pt-1">
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 transition-colors text-left">
                                    <i class="fas fa-sign-out-alt w-4 text-center flex-shrink-0"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="flex items-center gap-1.5 text-xs font-semibold text-gray-600 hover:text-green-600 transition-colors">
                    <i class="fas fa-user text-xs"></i> Masuk
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<nav id="navbar-main" class="navbar-main">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-3 flex-shrink-0 group min-w-0">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-md group-hover:scale-105 transition-transform flex-shrink-0 overflow-hidden">
                    <?php if($setting_global->logo ?? null): ?>
                        <img src="<?php echo e(Storage::url($setting_global->logo)); ?>" alt="<?php echo e($setting_global->nama_rumahsakit ?? 'Logo RS'); ?>" class="w-full h-full object-contain p-0.5">
                    <?php else: ?>
                        <i class="fas fa-hospital-alt text-green-600 text-lg"></i>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <div class="text-white font-extrabold text-lg leading-tight tracking-tight"><?php echo e($setting_global->nama_rumahsakit ?? 'RS Sari Sehat'); ?></div>
                    <div class="text-green-100 text-[10px] font-semibold tracking-widest uppercase"><?php echo e($setting_global->motto ?? 'Melayani dengan Kasih Sayang'); ?></div>
                </div>
            </a>

            
            <div class="hidden lg:flex items-center gap-1">
                <a href="<?php echo e(route('home')); ?>" class="nav-item <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Beranda</a>

                <div class="nav-dropdown">
                    <button class="nav-item flex items-center gap-1">
                        Pelayanan <i class="fas fa-chevron-down text-[10px] opacity-80"></i>
                    </button>
                    <div class="nav-dropdown-menu">
                        <?php $__currentLoopData = [
                            ['fa-stethoscope','Pelayanan Utama','layanan'],
                            ['fa-star','Pelayanan Khusus','layanan'],
                            ['fa-heartbeat','Pusat Layanan Ibu & Anak','layanan'],
                            ['fa-spa','Pain Clinic & Wellness','layanan'],
                            ['fa-clipboard-check','Medical Check-Up','mcu'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ico,$lbl,$rt]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route($rt)); ?>" class="nav-dropdown-item">
                            <i class="fas <?php echo e($ico); ?> text-green-500 w-4 text-center"></i> <?php echo e($lbl); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="nav-dropdown">
                    <button class="nav-item flex items-center gap-1">
                        Dokter <i class="fas fa-chevron-down text-[10px] opacity-80"></i>
                    </button>
                    <div class="nav-dropdown-menu">
                        <?php $__currentLoopData = [
                            ['fa-calendar-check','Daftar Poliklinik','dokter'],
                            ['fa-laptop-medical','Layanan Online','dokter.online'],
                            ['fa-user-md','Profil Dokter','dokter'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ico,$lbl,$rt]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route($rt)); ?>" class="nav-dropdown-item">
                            <i class="fas <?php echo e($ico); ?> text-green-500 w-4 text-center"></i> <?php echo e($lbl); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <a href="<?php echo e(route('promo')); ?>" class="nav-item <?php echo e(request()->routeIs('promo*') ? 'active' : ''); ?>">Promo</a>

                <div class="nav-dropdown">
                    <button class="nav-item flex items-center gap-1">
                        Informasi <i class="fas fa-chevron-down text-[10px] opacity-80"></i>
                    </button>
                    <div class="nav-dropdown-menu">
                        <?php $__currentLoopData = [
                            ['fa-newspaper','Artikel','artikel'],
                            ['fa-calendar-alt','Jadwal Kegiatan','event'],
                            ['fa-info-circle','Tentang Kami','tentang'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ico,$lbl,$rt]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route($rt)); ?>" class="nav-dropdown-item">
                            <i class="fas <?php echo e($ico); ?> text-green-500 w-4 text-center"></i> <?php echo e($lbl); ?>

                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <a href="<?php echo e(route('kontak')); ?>" class="nav-item <?php echo e(request()->routeIs('kontak') ? 'active' : ''); ?>">Hubungi Kami</a>
            </div>

            
            <div class="hidden lg:flex items-center gap-3">
                <a href="<?php echo e(route('dokter')); ?>" class="flex items-center gap-2 bg-white text-green-700 px-4 py-2 rounded-lg font-bold text-sm hover:bg-green-50 transition-all shadow-sm">
                    <i class="fas fa-calendar-check text-green-600"></i> Daftar Poliklinik
                </a>
            </div>

            
            <button id="hamburger-btn" class="lg:hidden text-white p-2 rounded-lg hover:bg-white/15 transition-colors" aria-label="Menu">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</nav>


<div id="mobile-menu-panel">
    <div id="mobile-overlay"></div>
    <div id="mobile-drawer">
        <div class="bg-green-600 p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center overflow-hidden">
                    <?php if($setting_global->logo ?? null): ?>
                        <img src="<?php echo e(Storage::url($setting_global->logo)); ?>" alt="<?php echo e($setting_global->nama_rumahsakit ?? 'Logo RS'); ?>" class="w-full h-full object-contain p-0.5">
                    <?php else: ?>
                        <i class="fas fa-hospital-alt text-green-600"></i>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="text-white font-bold text-sm"><?php echo e($setting_global->nama_rumahsakit ?? 'RS Sari Sehat'); ?></div>
                    <div class="text-green-100 text-[10px]"><?php echo e($setting_global->motto ?? 'Melayani dengan Kasih Sayang'); ?></div>
                </div>
            </div>
            <button id="close-drawer" class="text-white p-1"><i class="fas fa-times text-lg"></i></button>
        </div>
        <div class="p-4 space-y-1">
            <?php $__currentLoopData = [
                ['fa-home','Beranda','home'],
                ['fa-stethoscope','Pelayanan','layanan'],
                ['fa-user-md','Dokter','dokter'],
                ['fa-tags','Promo','promo'],
                ['fa-newspaper','Artikel','artikel'],
                ['fa-calendar-alt','Kegiatan','event'],
                ['fa-info-circle','Tentang Kami','tentang'],
                ['fa-phone','Hubungi Kami','kontak'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ico,$lbl,$rt]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($rt)); ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm">
                <i class="fas <?php echo e($ico); ?> text-green-500 w-4 text-center"></i> <?php echo e($lbl); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="pt-3">
                <a href="<?php echo e(route('dokter')); ?>" class="block w-full text-center btn-green py-3 rounded-xl font-bold">
                    <i class="fas fa-calendar-check mr-2"></i>Daftar Poliklinik
                </a>
            </div>
            <div class="pt-3 mt-3 border-t border-gray-100">
                <?php if(auth()->guard()->check()): ?>
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-100">
                    <div class="w-9 h-9 rounded-full bg-green-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white text-xs"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-gray-900 text-sm font-extrabold truncate"><?php echo e(Auth::user()->nama); ?></p>
                        <p class="text-green-700 text-xs font-semibold"><?php echo e(Auth::user()->role_label); ?></p>
                    </div>
                </div>
                <?php if(Auth::user()->isPasien()): ?>
                <a href="<?php echo e(route('portal.profil')); ?>?tab=profil" class="mt-2 flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-user-edit text-green-500 w-4 text-center"></i> Profil Saya
                </a>
                <a href="<?php echo e(route('portal.profil')); ?>?tab=riwayat" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-calendar-check text-green-500 w-4 text-center"></i> Riwayat Poliklinik
                </a>
                <a href="<?php echo e(route('portal.profil')); ?>?tab=penjamin" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-shield-halved text-green-500 w-4 text-center"></i> Penjamin & Asuransi
                </a>
                <?php else: ?>
                <a href="<?php echo e(route('dashboard')); ?>" class="mt-2 flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-green-700 font-semibold transition-colors text-sm border border-gray-100">
                    <i class="fas fa-gauge-high text-green-500 w-4 text-center"></i> Dashboard
                </a>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-2">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-red-50 text-red-600 border border-red-100 font-bold text-sm">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </button>
                </form>
                <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-green-50 text-green-700 border border-green-100 font-bold text-sm">
                    <i class="fas fa-user"></i> Masuk
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<main><?php echo $__env->yieldContent('content'); ?></main>


<section class="social-section py-12">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <div class="mb-6">
            <span class="text-green-400 text-xs font-black uppercase tracking-widest block mb-2">Follow</span>
            <h2 class="text-white text-2xl font-extrabold">Sosial Media Kami</h2>
        </div>
        <div class="flex flex-wrap justify-center gap-4">
            <?php $__currentLoopData = [
                ['fab fa-instagram','Instagram','@rssarisehat','bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400','https://instagram.com'],
                ['fab fa-facebook-f','Facebook','RS Sari Sehat','bg-blue-600','https://facebook.com'],
                ['fab fa-youtube','YouTube','RS Sari Sehat Group','bg-red-600','https://youtube.com'],
                ['fab fa-tiktok','TikTok','@rssarisehat','bg-gray-900','https://tiktok.com'],
                ['fab fa-spotify','Spotify','RS Sari Sehat Podcast','bg-green-500','https://spotify.com'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$platform,$handle,$bg,$url]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($url); ?>" target="_blank" rel="noopener"
               class="flex items-center gap-3 <?php echo e($bg); ?> text-white px-5 py-3 rounded-xl font-semibold text-sm hover:opacity-90 hover:-translate-y-1 transition-all shadow-lg">
                <i class="<?php echo e($icon); ?> text-lg"></i>
                <div class="text-left">
                    <div class="text-xs opacity-80"><?php echo e($platform); ?></div>
                    <div class="font-bold text-sm"><?php echo e($handle); ?></div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<footer class="footer-main text-white">
    <div class="max-w-screen-xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            
            <div class="lg:col-span-1">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-11 h-11 rounded-xl bg-green-600 flex items-center justify-center overflow-hidden">
                        <?php if($setting_global->logo ?? null): ?>
                            <img src="<?php echo e(Storage::url($setting_global->logo)); ?>" alt="<?php echo e($setting_global->nama_rumahsakit ?? 'Logo RS'); ?>" class="w-full h-full object-contain p-0.5">
                        <?php else: ?>
                            <i class="fas fa-hospital-alt text-white text-lg"></i>
                        <?php endif; ?>
                    </div>
                    <div>
                        <div class="font-extrabold text-base text-white"><?php echo e($setting_global->nama_rumahsakit ?? 'RS Sari Sehat'); ?></div>
                        <div class="text-green-400 text-[10px] font-semibold uppercase tracking-wider"><?php echo e($setting_global->motto ?? 'Melayani dengan Kasih Sayang'); ?></div>
                    </div>
                </div>
                <?php if($setting_global->footer ?? null): ?>
                <p class="text-gray-400 text-sm leading-relaxed mb-5"><?php echo e($setting_global->footer); ?></p>
                <?php else: ?>
                <p class="text-gray-400 text-sm leading-relaxed mb-5">
                    Rumah sakit yang mengutamakan pelayanan kesehatan berkualitas dengan penuh kasih sayang.
                </p>
                <?php endif; ?>
                <div class="space-y-2 text-sm">
                    <?php if($setting_global->telepon ?? null): ?>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-phone text-green-500 w-4"></i>
                        <a href="tel:<?php echo e(preg_replace('/[^0-9]/', '', $setting_global->telepon)); ?>" class="text-gray-300 hover:text-white transition-colors"><?php echo e($setting_global->telepon); ?></a>
                    </div>
                    <?php endif; ?>
                    <?php if($setting_global->email ?? null): ?>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-envelope text-green-500 w-4"></i>
                        <a href="mailto:<?php echo e($setting_global->email); ?>" class="text-gray-300 hover:text-white transition-colors"><?php echo e($setting_global->email); ?></a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div>
                <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Menu</h4>
                <ul class="space-y-2.5">
                    <?php $__currentLoopData = [['Tentang Kami','tentang'],['Jadwal Dokter','dokter'],['Promo','promo'],['Hubungi Kami','kontak']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl,$rt]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route($rt)); ?>" class="text-gray-400 hover:text-green-400 text-sm transition-colors flex items-center gap-2">
                            <i class="fas fa-chevron-right text-green-600 text-xs"></i> <?php echo e($lbl); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            
            <div>
                <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Informasi</h4>
                <ul class="space-y-2.5">
                    <?php $__currentLoopData = [['Promo','promo'],['Artikel','artikel'],['Jadwal Kegiatan','event'],['Medical Check-Up','mcu'],['Live Antrian','live.antrian']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$lbl,$rt]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route($rt)); ?>" class="text-gray-400 hover:text-green-400 text-sm transition-colors flex items-center gap-2">
                            <i class="fas fa-chevron-right text-green-600 text-xs"></i> <?php echo e($lbl); ?>

                        </a>
                    </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>

            
            <div>
                <h4 class="text-white font-bold text-sm mb-4 uppercase tracking-wider">Kontak</h4>
                <ul class="space-y-2.5">
                    <?php if($setting_global->alamat ?? null): ?>
                    <li class="text-gray-400 text-sm flex items-start gap-2">
                        <i class="fas fa-map-marker-alt text-green-500 text-xs w-3 mt-1 flex-shrink-0"></i>
                        <span><?php echo e($setting_global->alamat); ?></span>
                    </li>
                    <?php endif; ?>
                    <?php if($setting_global->telepon ?? null): ?>
                    <li class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="fas fa-phone text-green-500 text-xs w-3"></i>
                        <a href="tel:<?php echo e(preg_replace('/[^0-9]/', '', $setting_global->telepon)); ?>" class="hover:text-green-400 transition-colors"><?php echo e($setting_global->telepon); ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if($setting_global->email ?? null): ?>
                    <li class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="fas fa-envelope text-green-500 text-xs w-3"></i>
                        <a href="mailto:<?php echo e($setting_global->email); ?>" class="hover:text-green-400 transition-colors"><?php echo e($setting_global->email); ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if($setting_global->jam_operasional ?? null): ?>
                    <li class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="fas fa-clock text-green-500 text-xs w-3"></i>
                        <span><?php echo e($setting_global->jam_operasional); ?></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    
    <div class="border-t border-white/10">
        <div class="max-w-screen-xl mx-auto px-4 py-4">
            <div class="flex flex-wrap justify-center gap-3 items-center">
                <span class="text-gray-500 text-xs font-semibold">Terakreditasi:</span>
                <?php $__currentLoopData = ['KARS Paripurna','ISO 9001:2015','SNARS Edisi 1.1','BPJS Kesehatan','Kemenkes RI']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs text-green-400 font-bold"><?php echo e($a); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    
    <div class="border-t border-white/10">
        <div class="max-w-screen-xl mx-auto px-4 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-gray-500">
                <span>&copy; <?php echo e(date('Y')); ?> <?php echo e($setting_global->copyright ?? ($setting_global->nama_rumahsakit ?? 'RS Sari Sehat') . '. All rights reserved.'); ?></span>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-green-400 transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-green-400 transition-colors">Syarat Ketentuan</a>
                    <a href="#" class="hover:text-green-400 transition-colors">Kebijakan Cookie</a>
                </div>
            </div>
        </div>
    </div>
</footer>


<button id="btn-back-to-top" aria-label="Kembali ke atas"
    class="fixed bottom-6 right-6 w-11 h-11 bg-green-600 hover:bg-green-700 text-white rounded-xl shadow-xl opacity-0 pointer-events-none transition-all duration-300 z-40 flex items-center justify-center">
    <i class="fas fa-arrow-up text-sm"></i>
</button>


<a id="btn-whatsapp" href="https://wa.me/6221509438380" target="_blank" rel="noopener" aria-label="WhatsApp"
   class="fixed bottom-20 right-6 w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full shadow-xl flex items-center justify-center transition-all hover:scale-110 z-40 group">
    <i class="fab fa-whatsapp text-2xl"></i>
    <span class="absolute right-full mr-3 px-3 py-1.5 bg-gray-900 text-white text-xs rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
        Chat WhatsApp
    </span>
</a>


<nav id="bottom-nav">
    <div class="bn-grid">
        <a href="<?php echo e(route('home')); ?>" class="bn-item <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>
        <a href="<?php echo e(route('layanan')); ?>" class="bn-item <?php echo e(request()->routeIs('layanan*') ? 'active' : ''); ?>">
            <i class="fas fa-stethoscope"></i>
            <span>Pelayanan</span>
        </a>
        <a href="<?php echo e(route('dokter')); ?>" class="bn-item <?php echo e(request()->routeIs('dokter*') ? 'active' : ''); ?>">
            <i class="fas fa-user-doctor"></i>
            <span>Dokter</span>
        </a>
        <a href="<?php echo e(route('promo')); ?>" class="bn-item <?php echo e(request()->routeIs('promo*') ? 'active' : ''); ?>">
            <i class="fas fa-tags"></i>
            <span>Promo</span>
        </a>
        <a href="<?php echo e(route('artikel')); ?>" class="bn-item <?php echo e(request()->routeIs('artikel*') || request()->routeIs('event*') ? 'active' : ''); ?>">
            <i class="fas fa-newspaper"></i>
            <span>Informasi</span>
        </a>
        <a href="<?php echo e(route('kontak')); ?>" class="bn-item <?php echo e(request()->routeIs('kontak') ? 'active' : ''); ?>">
            <i class="fas fa-phone"></i>
            <span>Kontak</span>
        </a>
    </div>
</nav>


<div id="bottom-nav-spacer" style="display:none;height:60px"></div>

<?php echo $__env->yieldPushContent('scripts'); ?>
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

    // Waktu sholat display (jadwal fixed - tampilkan hanya saat sedang waktu sholat)
    // Jadwal: Subuh 04:42, Dzuhur 11:58, Asar 15:20, Maghrib 17:52, Isya 19:06
    function updateClock() {
        const el = document.getElementById('waktu-sholat');
        if (!el) return;

        const now = new Date();
        const minutesNow = now.getHours() * 60 + now.getMinutes();

        const jadwal = [
            { name: 'Subuh',   at: 4 * 60 + 42 },
            { name: 'Dzuhur',  at: 11 * 60 + 58 },
            { name: 'Asar',     at: 15 * 60 + 20 },
            { name: 'Maghrib',  at: 17 * 60 + 52 },
            { name: 'Isya',     at: 19 * 60 + 6 },
        ];

        // Tampilkan jika sekarang berada di window +/- 5 menit dari jadwal sholat
        const windowMin = 5;
        const match = jadwal.find(j => Math.abs(minutesNow - j.at) <= windowMin);

        const hh = String(now.getHours()).padStart(2,'0');
        const mm = String(now.getMinutes()).padStart(2,'0');

        // Tetap tampilkan jam sekarang, tapi label sholat tampil hanya saat sedang waktu sholat
        el.textContent = match ? `${hh}:${mm} ${match.name}` : `${hh}:${mm}`;
    }
    updateClock(); setInterval(updateClock, 60000);

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
</script>
</body>
</html>
<?php /**PATH D:\laragon\www\rumahsakit\resources\views/layouts/app.blade.php ENDPATH**/ ?>