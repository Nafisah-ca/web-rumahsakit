<?php $pageTitle = 'Kategori Artikel'; $breadcrumb = 'CMS / Kategori Artikel'; ?>

<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start">

    
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px">Tambah Kategori Baru</p>
        <form method="POST" action="<?php echo e(route('cms.kategori-artikel.store')); ?>">
            <?php echo csrf_field(); ?>
            <?php if(session('success')): ?>
            <div class="alert alert-success" style="margin:0 0 12px"><i class="fas fa-circle-check"></i><span><?php echo e(session('success')); ?></span></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
            <div class="form-error"><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
            <?php endif; ?>
            <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="<?php echo e(old('nama')); ?>" class="form-input" required placeholder="contoh: Kesehatan Jantung">
            </div>
            <div class="form-group">
                <label class="form-label">Warna Badge</label>
                <input type="color" name="warna" value="<?php echo e(old('warna','#3b82f6')); ?>" class="form-input">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <i class="fas fa-plus"></i> Simpan Kategori
            </button>
        </form>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3>Daftar Kategori</h3>
            <span style="font-size:12px;color:#94a3b8"><?php echo e($kategoris->total()); ?> kategori</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Nama Kategori</th><th>Warna</th><th>Jumlah Artikel</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="font-weight:600;color:#0f172a"><?php echo e($k->nama); ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <div style="width:20px;height:20px;border-radius:50%;border:2px solid #e2e8f0;background:<?php echo e($k->warna); ?>"></div>
                                <span class="code-tag"><?php echo e($k->warna); ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-blue"><?php echo e($k->artikels_count); ?> artikel</span>
                        </td>
                        <td>
                            <?php if($k->artikels_count == 0): ?>
                            <form method="POST" action="<?php echo e(route('cms.kategori-artikel.destroy', $k)); ?>" onsubmit="return confirm('Hapus kategori <?php echo e(addslashes($k->nama)); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                            <?php else: ?>
                            <span style="font-size:12px;color:#94a3b8">Tidak bisa dihapus</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Belum ada kategori</p></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="table-footer"><?php echo e($kategoris->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/cms/kategori-artikel/index.blade.php ENDPATH**/ ?>