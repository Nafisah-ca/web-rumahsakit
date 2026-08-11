<?php $pageTitle = 'Edit Dokter'; $breadcrumb = 'Admin / Dokter / Edit'; ?>
<?php $__env->startSection('content'); ?>
<div style="max-width:620px">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Edit: <span style="color:#16a34a"><?php echo e($dokter->nama_dokter); ?></span></p>
        <form method="POST" action="<?php echo e(route('admin.dokter.update', $dokter)); ?>" enctype="multipart/form-data">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php if($errors->any()): ?>
            <div class="form-error" style="margin-bottom:16px"><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
            <?php endif; ?>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Dokter <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_dokter" value="<?php echo e(old('nama_dokter',$dokter->nama_dokter)); ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Spesialisasi <span style="color:#ef4444">*</span></label>
                    <select name="spesialis_id" class="form-input" required>
                        <?php $__currentLoopData = $spesialisasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($sp->id); ?>" <?php echo e(old('spesialis_id',$dokter->spesialis_id)==$sp->id?'selected':''); ?>><?php echo e($sp->nama_spesialis); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SIP <span style="color:#ef4444">*</span></label>
                    <input type="text" name="sip" value="<?php echo e(old('sip',$dokter->sip)); ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email',$dokter->email)); ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP <span style="color:#ef4444">*</span></label>
                    <input type="text" name="no_hp" value="<?php echo e(old('no_hp',$dokter->no_hp)); ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    <?php if($dokter->foto): ?>
                    <div style="margin-bottom:8px"><img src="<?php echo e(Storage::url($dokter->foto)); ?>" style="width:56px;height:56px;object-fit:cover;border-radius:10px"></div>
                    <?php endif; ?>
                    <input type="file" name="foto" accept="image/*" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"    <?php echo e(old('status',$dokter->status)==='aktif'?'selected':''); ?>>Aktif</option>
                        <option value="nonaktif" <?php echo e(old('status',$dokter->status)==='nonaktif'?'selected':''); ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.dokter')); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/dokter/edit.blade.php ENDPATH**/ ?>