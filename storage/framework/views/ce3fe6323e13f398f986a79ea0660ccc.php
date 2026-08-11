<?php $pageTitle = 'Layanan'; $breadcrumb = 'CMS / Layanan'; ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Layanan</h3>
        <div style="display:flex;gap:10px">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari layanan..." class="form-input" style="width:200px">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request('search')): ?><a href="<?php echo e(route('cms.layanan')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <a href="<?php echo e(route('cms.layanan.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Layanan</th><th>Icon</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $layanans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <?php if($l->gambar): ?>
                            <img src="<?php echo e(Storage::url($l->gambar)); ?>" style="width:40px;height:40px;border-radius:10px;object-fit:cover;flex-shrink:0;border:1px solid #e2e8f0">
                            <?php else: ?>
                            <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#dcfce7;color:#16a34a;flex-shrink:0">
                                <i class="fas <?php echo e($l->icon ?? 'fa-stethoscope'); ?>"></i>
                            </div>
                            <?php endif; ?>
                            <div>
                                <p style="font-weight:600;font-size:13px;color:#0f172a"><?php echo e($l->nama_layanan); ?></p>
                                <?php if($l->deskripsi): ?><p style="font-size:11px;color:#94a3b8;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo e($l->deskripsi); ?></p><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><span class="code-tag"><i class="fas <?php echo e($l->icon ?? 'fa-stethoscope'); ?> mr-1"></i><?php echo e($l->icon ?? 'fa-stethoscope'); ?></span></td>
                    <td><span class="badge <?php echo e($l->status==='aktif'?'badge-green':'badge-slate'); ?>"><?php echo e($l->status==='aktif'?'Aktif':'Nonaktif'); ?></span></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="<?php echo e(route('cms.layanan.edit',$l)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="<?php echo e(route('cms.layanan.destroy',$l)); ?>" onsubmit="return confirm('Hapus layanan ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="4"><div class="empty-state"><i class="fas fa-stethoscope"></i><p>Belum ada layanan</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($layanans->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/cms/layanan/index.blade.php ENDPATH**/ ?>