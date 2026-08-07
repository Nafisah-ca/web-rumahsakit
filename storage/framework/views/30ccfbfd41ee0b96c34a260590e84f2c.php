<?php $pageTitle = 'Dashboard CMS'; $breadcrumb = 'Selamat datang, ' . Auth::user()->nama; ?>

<?php $__env->startSection('content'); ?>

<div class="stats-grid">
    <?php
    $cards = [
        ['fas fa-newspaper',   'Total Artikel',    $stats['total_artikel'],   '#2563eb', '#dbeafe'],
        ['fas fa-circle-check','Dipublikasi',       $stats['artikel_publish'], '#16a34a', '#dcfce7'],
        ['fas fa-file-alt',    'Draft Artikel',    max(0, ($stats['total_artikel'] - $stats['artikel_publish'])), '#64748b', '#f1f5f9'],

        ['fas fa-tag',         'Total Promo',       $stats['total_promo'],     '#d97706', '#fef3c7'],
        ['fas fa-star',        'Promo Aktif',       $stats['promo_aktif'],     '#ea580c', '#ffedd5'],
        ['fas fa-file-alt',    'Draft Promo',     max(0, ($stats['total_promo'] - $stats['promo_aktif'])), '#64748b', '#f1f5f9'],


        ['fas fa-calendar-days','Event Mendatang',  $stats['event_mendatang'], '#7c3aed', '#ede9fe'],
        ['fas fa-panorama',    'Total Banner',      $stats['total_banner'],    '#db2777', '#fce7f3'],
        ['fas fa-eye',         'Banner Aktif',      $stats['banner_aktif'],    '#0891b2', '#cffafe'],
        ['fas fa-calendar-days','Total Event',      $stats['total_event'],     '#4f46e5', '#e0e7ff'],
    ];
    ?>
    <?php $__currentLoopData = $cards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$label,$val,$color,$bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="stat-card">
        <div class="stat-icon" style="background:<?php echo e($bg); ?>; color:<?php echo e($color); ?>">
            <i class="<?php echo e($icon); ?>"></i>
        </div>
        <div class="stat-value"><?php echo e($val); ?></div>
        <div class="stat-label"><?php echo e($label); ?></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div style="display:grid;grid-template-columns:1fr 300px;gap:24px">
    <div class="card">
        <div class="card-header">
            <h3>Artikel Terbaru</h3>
            <a href="<?php echo e(route('cms.artikel')); ?>" class="btn btn-sm" style="color:#2563eb;background:none;border:1px solid #dbeafe;">Lihat Semua</a>
        </div>
        <?php $__empty_1 = true; $__currentLoopData = $recentArtikel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div style="display:flex;align-items:center;gap:14px;padding:14px 20px;border-bottom:1px solid #f8fafc;">
            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;background:#dbeafe">
                📰
            </div>
            <div style="flex:1;min-width:0">
                <p style="font-size:13px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?php echo e($art->judul); ?></p>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px"><?php echo e($art->kategori?->nama_kategori ?? '-'); ?> &middot; <?php echo e($art->created_tm->diffForHumans()); ?></p>
            </div>
            <span class="badge <?php echo e($art->status==='publish'?'badge-green':'badge-amber'); ?>">
                <?php echo e($art->status==='publish' ? 'Publish' : 'Draft'); ?>

            </span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="empty-state"><i class="fas fa-newspaper"></i><p>Belum ada artikel</p></div>
        <?php endif; ?>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:12px">Akses Cepat</p>
            <?php $__currentLoopData = [
                ['fas fa-pen-nib',       'Tulis Artikel Baru',   'cms.artikel.create',   '#2563eb','#dbeafe'],
                ['fas fa-tag',           'Tambah Promo',         'cms.promo.create',     '#d97706','#fef3c7'],
                ['fas fa-calendar-plus', 'Tambah Event',         'cms.event.create',     '#7c3aed','#ede9fe'],
                ['fas fa-panorama',      'Kelola Banner',        'cms.banner',           '#db2777','#fce7f3'],
                ['fas fa-images',        'Upload Galeri',        'cms.galeri',           '#16a34a','#dcfce7'],
                ['fas fa-sliders',       'Pengaturan Website',   'cms.website-setting',  '#475569','#f1f5f9'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ico,$lbl,$rt,$clr,$bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($rt)); ?>" style="display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:10px;text-decoration:none;transition:background .15s;margin-bottom:2px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='none'">
                <div style="width:30px;height:30px;background:<?php echo e($bg); ?>;color:<?php echo e($clr); ?>;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:12px">
                    <i class="<?php echo e($ico); ?>"></i>
                </div>
                <span style="font-size:13px;font-weight:500;color:#334155"><?php echo e($lbl); ?></span>
                <i class="fas fa-chevron-right" style="margin-left:auto;font-size:10px;color:#cbd5e1"></i>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div style="background:linear-gradient(135deg,#1e40af,#1d4ed8);border-radius:16px;padding:20px;color:#fff">
            <i class="fas fa-lightbulb" style="font-size:20px;color:#93c5fd;display:block;margin-bottom:10px"></i>
            <p style="font-size:13px;font-weight:700;margin-bottom:6px">Tips Konten</p>
            <p style="font-size:12px;color:#bfdbfe;line-height:1.6">Artikel yang rutin dipublikasikan meningkatkan keterlibatan pengunjung website.</p>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/cms/dashboard.blade.php ENDPATH**/ ?>