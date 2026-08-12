<?php $pageTitle = 'Artikel'; $breadcrumb = 'CMS / Artikel'; ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Artikel</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul..." class="form-input" style="width:180px">
                <select name="status" class="form-input" style="width:130px">
                    <option value="">Semua Status</option>
                    <option value="draft" <?php echo e(request('status')=='draft'?'selected':''); ?>>Draft</option>
                    <option value="publish" <?php echo e(request('status')=='publish'?'selected':''); ?>>Publish</option>
                </select>
                <select name="kategori_id" class="form-input" style="width:160px">
                    <option value="">Semua Kategori</option>
                    <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k->id); ?>" <?php echo e(request('kategori_id')==$k->id?'selected':''); ?>><?php echo e($k->nama_kategori); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request()->hasAny(['search','status','kategori_id'])): ?><a href="<?php echo e(route('cms.artikel')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <a href="<?php echo e(route('cms.artikel.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tulis Artikel</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Artikel</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Tanggal</th><th>Diperbarui Oleh</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $artikels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="max-width:280px">
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:38px;height:38px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;background:#dbeafe;color:#2563eb;font-size:16px">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <p style="font-weight:600;font-size:13px;color:#0f172a;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical"><?php echo e($art->judul); ?></p>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#64748b"><?php echo e($art->kategori?->nama_kategori??'-'); ?></td>
                    <td style="font-size:12px;color:#64748b"><?php echo e($art->penulis?->nama??'-'); ?></td>
                    <td><span class="badge <?php echo e($art->status==='publish'?'badge-green':'badge-amber'); ?>"><?php echo e($art->status==='publish'?'Publish':'Draft'); ?></span></td>
                    <td style="font-size:12px;color:#94a3b8"><?php echo e($art->created_tm->format('d M Y')); ?></td>
                    <td style="font-size:12px;color:#64748b"><?php echo e($art->updatedBy?->nama ?? '-'); ?></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="<?php echo e(route('cms.artikel.edit',$art)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="<?php echo e(route('cms.artikel.destroy',$art)); ?>" onsubmit="return confirm('Hapus artikel ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-newspaper"></i><p>Belum ada artikel</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($artikels->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/cms/artikel/index.blade.php ENDPATH**/ ?>