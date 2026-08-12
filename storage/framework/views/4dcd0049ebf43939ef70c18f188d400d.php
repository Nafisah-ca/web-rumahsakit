
<?php $__env->startSection('content'); ?>

<div class="py-16" style="background:linear-gradient(135deg,#1e3a5f,#0284c7)">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-blue-300 text-xs font-black uppercase tracking-widest block mb-2">Tips & Edukasi</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Artikel Kesehatan</h1>
        <p class="text-blue-100 text-sm max-w-xl mx-auto">Informasi kesehatan terkini dari tim medis RS Sari Sehat</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-blue-200">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Artikel</span>
        </nav>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="artikel-layout">
            <div>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px">
                    <?php $__empty_1 = true; $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('artikel.detail',$art->slug)); ?>" class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all block group">
                        <div style="height:160px;overflow:hidden;background:linear-gradient(135deg,#00521f,#00b04f)">
                            <?php if($art->gambar): ?>
                            <img src="<?php echo e(Storage::url($art->gambar)); ?>" alt="<?php echo e($art->judul); ?>" style="width:100%;height:100%;object-fit:cover">
                            <?php elseif($art->thumbnail): ?>
                            <img src="<?php echo e(Storage::url($art->thumbnail)); ?>" alt="<?php echo e($art->judul); ?>" style="width:100%;height:100%;object-fit:cover">
                            <?php else: ?>
                            <div style="height:100%;display:flex;align-items:center;justify-content:center">
                                <i class="fas fa-newspaper text-white opacity-30" style="font-size:40px"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div style="padding:16px">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px">
                                <?php if($art->kategori): ?>
                                <span style="font-size:10px;font-weight:700;color:#fff;background:#2563eb;padding:2px 8px;border-radius:20px"><?php echo e($art->kategori->nama_kategori); ?></span>
                                <?php endif; ?>
                                <span style="font-size:11px;color:#94a3b8"><?php echo e($art->created_tm?->format('d M Y')); ?></span>
                            </div>
                            <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden" class="group-hover:text-blue-600 transition-colors"><?php echo e($art->judul); ?></h3>
                            <p style="font-size:12px;color:#64748b;line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?php echo e(Str::limit(strip_tags($art->isi), 100)); ?></p>
                            <div style="margin-top:10px;font-size:12px;font-weight:700;color:#2563eb">Baca Selengkapnya →</div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div style="grid-column:1/-1;text-align:center;padding:60px;color:#94a3b8">
                        <i class="fas fa-newspaper" style="font-size:40px;opacity:.2;display:block;margin-bottom:10px"></i>
                        <p>Belum ada artikel</p>
                    </div>
                    <?php endif; ?>
                </div>
                <div style="margin-top:32px"><?php echo e($articles->links()); ?></div>
            </div>

            <div class="artikel-sidebar">
                <div style="background:#fff;border-radius:16px;border:1px solid #f1f5f9;padding:20px">
                    <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:14px">Kategori Artikel</p>
                    <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('artikel')); ?>?kategori_id=<?php echo e($k->id); ?>" style="display:flex;align-items:center;justify-content:space-between;padding:8px 10px;border-radius:8px;text-decoration:none;transition:background .15s;margin-bottom:2px" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:8px;height:8px;border-radius:50%;background:#2563eb"></div>
                            <span style="font-size:13px;color:#334155;font-weight:500"><?php echo e($k->nama_kategori); ?></span>
                        </div>
                        <span style="font-size:11px;color:#94a3b8;font-weight:600"><?php echo e($k->artikels_count); ?></span>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('styles'); ?>
<style>
.artikel-layout {
    display: grid;
    grid-template-columns: 1fr 260px;
    gap: 32px;
    align-items: start;
}
.artikel-sidebar {
    position: sticky;
    top: 24px;
}
@media (max-width: 768px) {
    .artikel-layout {
        grid-template-columns: 1fr;
    }
    .artikel-sidebar {
        position: static;
    }
}
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/artikel.blade.php ENDPATH**/ ?>