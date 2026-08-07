<?php
    // Pastikan $errors selalu tersedia di semua view CMS
    if (!isset($errors)) $errors = new \Illuminate\Support\ViewErrorBag;
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<title><?php echo e($pageTitle ?? 'CMS'); ?> — RS Sari Sehat</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; }

/* ===== LAYOUT ===== */
.app-shell    { display: flex; height: 100vh; overflow: hidden; }
.sidebar      { width: 240px; background: #1e3a5f; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; }
.main-wrap    { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.main-content { flex: 1; overflow-y: auto; padding: 24px; }

/* ===== SIDEBAR ===== */
.sidebar-logo      { padding: 20px; border-bottom: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 12px; text-decoration: none; }
.sidebar-logo-icon { width: 36px; height: 36px; background: rgba(255,255,255,.15); border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sidebar-logo-icon i { color: #fff; font-size: 14px; }
.sidebar-logo-text p { color: #fff; font-size: 13px; font-weight: 700; line-height: 1.3; }
.sidebar-logo-text span { color: #93c5fd; font-size: 10px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }

.sidebar-nav   { flex: 1; padding: 12px; }
.sidebar-group { font-size: 10px; font-weight: 700; color: rgba(147,197,253,.5); letter-spacing: .1em; text-transform: uppercase; padding: 20px 12px 6px; }

.sidebar-link  { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px; color: #bfdbfe; text-decoration: none; font-size: 13px; font-weight: 500; transition: background .15s, color .15s; margin-bottom: 2px; }
.sidebar-link:hover { background: rgba(255,255,255,.1); color: #fff; }
.sidebar-link.active { background: rgba(255,255,255,.18); color: #fff; font-weight: 600; }
.sidebar-link .icon { width: 16px; text-align: center; flex-shrink: 0; font-size: 13px; }

.sidebar-user  { padding: 12px; border-top: 1px solid rgba(255,255,255,.08); display: flex; align-items: center; gap: 10px; }
.sidebar-user-avatar { width: 32px; height: 32px; background: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.sidebar-user-avatar i { color: #fff; font-size: 12px; }
.sidebar-user-info { flex: 1; min-width: 0; }
.sidebar-user-info p { color: #fff; font-size: 12px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sidebar-user-info span { color: #93c5fd; font-size: 10px; }
.sidebar-logout { background: none; border: none; cursor: pointer; color: #93c5fd; padding: 4px; border-radius: 6px; transition: color .15s; }
.sidebar-logout:hover { color: #ef4444; }

/* ===== TOPBAR ===== */
.topbar        { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 14px 24px; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0; }
.topbar-left h1 { font-size: 18px; font-weight: 800; color: #0f172a; }
.topbar-left p  { font-size: 12px; color: #94a3b8; margin-top: 1px; }
.topbar-right   { display: flex; align-items: center; gap: 12px; }
.topbar-user    { display: flex; align-items: center; gap: 8px; }
.topbar-avatar  { width: 34px; height: 34px; background: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.topbar-avatar i { color: #fff; font-size: 13px; }
.topbar-name    { font-size: 13px; font-weight: 600; color: #334155; }

/* ===== ALERTS ===== */
.alert         { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 500; margin: 16px 24px 0; }
.alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
.alert-close   { margin-left: auto; background: none; border: none; cursor: pointer; color: inherit; opacity: .6; padding: 0; }

/* ===== CARDS ===== */
.card          { background: #fff; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.card-header   { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
.card-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
.card-body     { padding: 24px; }

/* ===== BUTTONS ===== */
.btn           { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; border: none; transition: all .15s; line-height: 1; font-family: inherit; }
.btn-primary   { background: #2563eb; color: #fff; }
.btn-primary:hover { background: #1d4ed8; }
.btn-secondary { background: #f1f5f9; color: #475569; }
.btn-secondary:hover { background: #e2e8f0; }
.btn-danger    { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
.btn-danger:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
.btn-sm        { padding: 6px 12px; font-size: 12px; }

/* ===== FORMS ===== */
.form-group    { margin-bottom: 16px; }
.form-label    { display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px; }
.form-input    { width: 100%; padding: 10px 14px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 13px; font-family: inherit; color: #0f172a; background: #fff; transition: border-color .15s, box-shadow .15s; outline: none; }
.form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.form-input[type="color"] { padding: 4px; cursor: pointer; height: 40px; }
.form-input[type="file"]  { padding: 8px 12px; }
select.form-input { cursor: pointer; }
textarea.form-input { resize: vertical; }
.form-hint     { font-size: 11px; color: #94a3b8; margin-top: 4px; }
.form-error    { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px; margin-bottom: 16px; }
.form-error ul { list-style: disc; list-style-position: inside; color: #dc2626; font-size: 13px; }
.form-row      { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-row-3    { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.form-check    { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.form-check input { width: 16px; height: 16px; accent-color: #2563eb; cursor: pointer; }
.form-check span  { font-size: 13px; font-weight: 500; color: #334155; }

/* ===== TABLES ===== */
.table-wrap    { overflow-x: auto; }
table          { width: 100%; border-collapse: collapse; }
thead          { background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
th             { padding: 11px 16px; text-align: left; font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
td             { padding: 12px 16px; font-size: 13px; color: #334155; border-bottom: 1px solid #f8fafc; }
tr:hover td    { background: #fafcff; }
tr:last-child td { border-bottom: none; }
.table-footer  { padding: 12px 20px; border-top: 1px solid #f1f5f9; }

/* ===== BADGES ===== */
.badge         { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; white-space: nowrap; }
.badge-green   { background: #dcfce7; color: #166534; }
.badge-red     { background: #fee2e2; color: #991b1b; }
.badge-blue    { background: #dbeafe; color: #1d4ed8; }
.badge-amber   { background: #fef3c7; color: #92400e; }
.badge-purple  { background: #ede9fe; color: #6d28d9; }
.badge-slate   { background: #f1f5f9; color: #475569; }

/* ===== STATS GRID ===== */
.stats-grid    { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
.stat-card     { background: #fff; border-radius: 14px; padding: 18px; border: 1px solid #f1f5f9; }
.stat-icon     { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; font-size: 14px; }
.stat-value    { font-size: 24px; font-weight: 800; color: #0f172a; }
.stat-label    { font-size: 11px; color: #94a3b8; font-weight: 500; margin-top: 2px; }

/* ===== MISC ===== */
.text-muted    { color: #94a3b8; }
.font-mono     { font-family: 'Courier New', monospace; }
.code-tag      { display: inline-block; background: #f1f5f9; color: #475569; font-family: monospace; font-size: 12px; padding: 2px 7px; border-radius: 6px; }
.empty-state   { text-align: center; padding: 60px 20px; color: #94a3b8; }
.empty-state i { font-size: 40px; opacity: .25; display: block; margin-bottom: 12px; }
.img-thumb     { border-radius: 8px; object-fit: cover; }
.img-preview   { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; margin-bottom: 8px; }
.color-dot     { width: 18px; height: 18px; border-radius: 50%; border: 2px solid #e2e8f0; display: inline-block; vertical-align: middle; }
nav[role="navigation"] { display: flex; align-items: center; justify-content: space-between; gap: 8px; flex-wrap: wrap; }
nav[role="navigation"] span[aria-current="page"] span, nav[role="navigation"] span span { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; font-size: 13px; font-weight: 600; }
nav[role="navigation"] span[aria-current="page"] span { background: #2563eb; color: #fff; }
nav[role="navigation"] a { display: inline-flex; align-items: center; justify-content: center; min-width: 32px; height: 32px; padding: 0 8px; border-radius: 8px; font-size: 13px; font-weight: 500; color: #475569; text-decoration: none; border: 1px solid #e2e8f0; transition: all .15s; }
nav[role="navigation"] a:hover { background: #f1f5f9; }
@media (max-width: 768px) { .stats-grid { grid-template-columns: 1fr 1fr; } .form-row { grid-template-columns: 1fr; } .topbar-name { display: none; } }
</style>
<?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a href="<?php echo e(route('cms.dashboard')); ?>" class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="fas fa-pen-nib"></i></div>
            <div class="sidebar-logo-text">
                <p>RS Sari Sehat</p>
                <span>CMS Panel</span>
            </div>
        </a>

        <nav class="sidebar-nav">
            <div class="sidebar-group">Menu Utama</div>
            <a href="<?php echo e(route('cms.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.dashboard') ? 'active' : ''); ?>">
                <i class="fas fa-gauge-high icon"></i> Dashboard
            </a>

            <div class="sidebar-group">Konten Website</div>
            <a href="<?php echo e(route('cms.website-setting')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.website-setting*') ? 'active' : ''); ?>">
                <i class="fas fa-sliders icon"></i> Website Setting
            </a>
            <a href="<?php echo e(route('cms.banner')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.banner*') ? 'active' : ''); ?>">
                <i class="fas fa-panorama icon"></i> Home Page Banner
            </a>
            <a href="<?php echo e(route('cms.informasi')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.informasi*') ? 'active' : ''); ?>">
                <i class="fas fa-circle-info icon"></i> Informasi Terkini
            </a>
            <a href="<?php echo e(route('cms.promo')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.promo*') ? 'active' : ''); ?>">
                <i class="fas fa-tag icon"></i> Promo
            </a>
            <a href="<?php echo e(route('cms.event')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.event*') ? 'active' : ''); ?>">
                <i class="fas fa-calendar-days icon"></i> Event & Kegiatan
            </a>
            <a href="<?php echo e(route('cms.kategori-artikel')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.kategori-artikel*') ? 'active' : ''); ?>">
                <i class="fas fa-folder-open icon"></i> Kategori Artikel
            </a>
            <a href="<?php echo e(route('cms.artikel')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.artikel*') ? 'active' : ''); ?>">
                <i class="fas fa-newspaper icon"></i> Artikel
            </a>
            <a href="<?php echo e(route('cms.kategori-galeri')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.kategori-galeri*') ? 'active' : ''); ?>">
                <i class="fas fa-folder-open icon"></i> Kategori Galeri
            </a>
            <a href="<?php echo e(route('cms.galeri')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.galeri*') ? 'active' : ''); ?>">
                <i class="fas fa-images icon"></i> Galeri Foto
            </a>

            <div class="sidebar-group">Master Data</div>
            <a href="<?php echo e(route('cms.layanan')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.layanan*') ? 'active' : ''); ?>">
                <i class="fas fa-stethoscope icon"></i> Layanan
            </a>

            <div class="sidebar-group">Interaksi</div>
            <a href="<?php echo e(route('cms.guest-book')); ?>" class="sidebar-link <?php echo e(request()->routeIs('cms.guest-book*') ? 'active' : ''); ?>">
                <?php $pesanBaru = \App\Models\GuestBook::where('status','baru')->count(); ?>
                <i class="fas fa-envelope icon"></i> Guest Book
                <?php if($pesanBaru > 0): ?>
                <span style="margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 6px;border-radius:999px"><?php echo e($pesanBaru); ?></span>
                <?php endif; ?>
            </a>

            <div class="sidebar-group">Lainnya</div>
            <a href="<?php echo e(route('home')); ?>" target="_blank" class="sidebar-link">
                <i class="fas fa-arrow-up-right-from-square icon"></i> Lihat Website
            </a>
        </nav>

        <div class="sidebar-user">
            <div class="sidebar-user-avatar"><i class="fas fa-user"></i></div>
            <div class="sidebar-user-info">
                <p><?php echo e(Auth::user()->nama); ?></p>
                <span>Content Manager</span>
            </div>
            <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button class="sidebar-logout" title="Keluar"><i class="fas fa-right-from-bracket"></i></button>
            </form>
        </div>
    </aside>

    <div class="main-wrap">
        <header class="topbar">
            <div class="topbar-left">
                <h1><?php echo e($pageTitle ?? 'Dashboard'); ?></h1>
                <?php if(isset($breadcrumb)): ?><p><?php echo e($breadcrumb); ?></p><?php endif; ?>
            </div>
            <div class="topbar-right">
                <div class="topbar-user">
                    <div class="topbar-avatar"><i class="fas fa-user"></i></div>
                    <span class="topbar-name"><?php echo e(Str::words(Auth::user()->nama, 2, '')); ?></span>
                </div>
            </div>
        </header>

        <?php if(session('success')): ?>
        <div class="alert alert-success" id="alert-success">
            <i class="fas fa-circle-check"></i>
            <span><?php echo e(session('success')); ?></span>
            <button class="alert-close" onclick="this.parentElement.remove()"><i class="fas fa-xmark"></i></button>
        </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-error">
            <i class="fas fa-circle-exclamation"></i>
            <span><?php echo e(session('error')); ?></span>
        </div>
        <?php endif; ?>

        <main class="main-content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<script>
setTimeout(() => { document.getElementById('alert-success')?.remove(); }, 4500);
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\laragon\www\rumahsakit\resources\views/layouts/cms.blade.php ENDPATH**/ ?>