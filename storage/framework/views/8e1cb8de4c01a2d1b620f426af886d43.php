<?php $pageTitle = 'Manajemen Dokter'; $breadcrumb = 'Admin / Dokter'; ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Dokter</h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama..." class="form-input" style="width:180px">
                <select name="spesialis_id" class="form-input" style="width:180px">
                    <option value="">Semua Spesialisasi</option>
                    <?php $__currentLoopData = $spesialisasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sp->id); ?>" <?php echo e(request('spesialis_id')==$sp->id?'selected':''); ?>><?php echo e($sp->nama_spesialis); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request()->hasAny(['search','spesialis_id'])): ?><a href="<?php echo e(route('admin.dokter')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <a href="<?php echo e(route('admin.dokter.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Dokter</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Dokter</th><th>Spesialisasi</th><th>Email</th><th>No. HP</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $dokters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="color:#94a3b8"><?php echo e($dokters->firstItem()+$i); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <?php if($d->foto): ?>
                            <img src="<?php echo e(Storage::url($d->foto)); ?>" style="width:36px;height:36px;border-radius:10px;object-fit:cover;flex-shrink:0">
                            <?php else: ?>
                            <div style="width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;background:#374151">
                                <?php echo e(strtoupper(substr($d->nama_dokter,0,1))); ?>

                            </div>
                            <?php endif; ?>
                            <p style="font-weight:600;font-size:13px;color:#0f172a"><?php echo e($d->nama_dokter); ?></p>
                        </div>
                    </td>
                    <td style="font-size:12px;color:#64748b"><?php echo e($d->spesialisasi?->nama_spesialis ?? '-'); ?></td>
                    <td style="font-size:12px;color:#64748b"><?php echo e($d->email); ?></td>
                    <td style="font-size:12px;color:#64748b"><?php echo e($d->no_hp); ?></td>
                    <td><span class="badge <?php echo e($d->status==='aktif' ? 'badge-green' : 'badge-slate'); ?>"><?php echo e($d->status==='aktif' ? 'Aktif' : 'Nonaktif'); ?></span></td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="<?php echo e(route('admin.dokter.edit',$d)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="<?php echo e(route('admin.dokter.destroy',$d)); ?>" onsubmit="return confirm('Hapus dokter ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-user-doctor"></i><p>Tidak ada dokter</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($dokters->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/dokter/index.blade.php ENDPATH**/ ?>