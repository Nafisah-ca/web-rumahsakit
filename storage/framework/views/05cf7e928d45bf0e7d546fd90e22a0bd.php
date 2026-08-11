<?php $pageTitle = 'Banner Homepage'; $breadcrumb = 'CMS / Banner'; ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Banner</h3>
        <a href="<?php echo e(route('cms.banner.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Banner</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Preview</th><th>Judul</th><th>Posisi</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="width:80px;height:48px;border-radius:8px;overflow:hidden;background:#374151;display:flex;align-items:center;justify-content:center">
                            <?php if($b->gambar): ?>
                            <img src="<?php echo e(Storage::url($b->gambar)); ?>" style="width:100%;height:100%;object-fit:cover">
                            <?php else: ?>
                            <i class="fas fa-image" style="color:rgba(255,255,255,.4);font-size:16px"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <p style="font-weight:600;font-size:13px;color:#0f172a"><?php echo e($b->judul); ?></p>
                    </td>
                    <td><span class="badge badge-blue">Homepage</span></td>
                    <td style="font-size:13px;font-weight:600;color:#64748b;font-family:monospace">-</td>
                    <td><span class="badge <?php echo e($b->status==='aktif'?'badge-green':'badge-slate'); ?>"><?php echo e($b->status==='aktif'?'Aktif':'Nonaktif'); ?></span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="<?php echo e(route('cms.banner.edit',$b)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="<?php echo e(route('cms.banner.destroy',$b)); ?>" onsubmit="return confirm('Hapus banner ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-panorama"></i><p>Belum ada banner</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($banners->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/cms/banner/index.blade.php ENDPATH**/ ?>