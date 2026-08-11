<?php $pageTitle = 'Informasi Terkini'; $breadcrumb = 'CMS / Informasi'; ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Informasi</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari judul..." class="form-input" style="width:200px">
                <select name="status" class="form-input" style="width:130px">
                    <option value="">Semua Status</option>
                    <option value="draft"   <?php echo e(request('status')=='draft'?'selected':''); ?>>Draft</option>
                    <option value="publish" <?php echo e(request('status')=='publish'?'selected':''); ?>>Publish</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request()->hasAny(['search','status'])): ?><a href="<?php echo e(route('cms.informasi')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <a href="<?php echo e(route('cms.informasi.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Informasi</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Judul</th><th>Status</th><th>Tanggal</th><th>Dibuat Oleh</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $informasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-weight:600;font-size:13px;max-width:280px"><?php echo e($info->judul); ?></td>
                    <td><span class="badge <?php echo e($info->status==='publish'?'badge-green':'badge-amber'); ?>"><?php echo e($info->status==='publish'?'Publish':'Draft'); ?></span></td>
                    <td style="font-size:12px;color:#94a3b8"><?php echo e($info->created_tm->format('d M Y')); ?></td>
                    <td style="font-size:12px;color:#64748b"><?php echo e($info->createdBy?->nama ?? '-'); ?></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="<?php echo e(route('cms.informasi.edit',$info)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="<?php echo e(route('cms.informasi.destroy',$info)); ?>" onsubmit="return confirm('Hapus informasi ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-circle-info"></i><p>Belum ada informasi</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($informasis->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/cms/informasi/index.blade.php ENDPATH**/ ?>