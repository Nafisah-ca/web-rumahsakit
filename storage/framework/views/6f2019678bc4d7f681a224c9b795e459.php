<?php $pageTitle = 'Ganti Password'; $breadcrumb = 'Admin / Setting / Ganti Password'; ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:520px">

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-lock" style="color:#16a34a;margin-right:8px"></i>Ganti Password</h3>
        </div>
        <div class="card-body">

            <?php if($errors->any()): ?>
            <div class="form-error" style="margin-bottom:20px">
                <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('admin.setting.password.update')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                
                <div class="form-group" x-data="{ show: false }">
                    <label class="form-label">Password Lama <span style="color:#ef4444">*</span></label>
                    <div style="position:relative">
                        <input :type="show ? 'text' : 'password'" name="password_lama"
                               class="form-input <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Masukkan password lama" required
                               style="padding-right:42px">
                        <button type="button" @click="show = !show"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password_lama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="form-hint" style="color:#dc2626"><i class="fas fa-circle-exclamation" style="margin-right:4px"></i><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <hr class="divider">

                
                <div class="form-group" x-data="{ show: false }">
                    <label class="form-label">Password Baru <span style="color:#ef4444">*</span></label>
                    <div style="position:relative">
                        <input :type="show ? 'text' : 'password'" name="password_baru"
                               class="form-input <?php $__errorArgs = ['password_baru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="Minimal 8 karakter" required
                               style="padding-right:42px">
                        <button type="button" @click="show = !show"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password_baru'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="form-hint" style="color:#dc2626"><i class="fas fa-circle-exclamation" style="margin-right:4px"></i><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <p class="form-hint">Minimal 8 karakter.</p>
                </div>

                
                <div class="form-group" x-data="{ show: false }">
                    <label class="form-label">Konfirmasi Password Baru <span style="color:#ef4444">*</span></label>
                    <div style="position:relative">
                        <input :type="show ? 'text' : 'password'" name="password_baru_confirmation"
                               class="form-input"
                               placeholder="Ulangi password baru" required
                               style="padding-right:42px">
                        <button type="button" @click="show = !show"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div style="display:flex;gap:10px;align-items:center;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-floppy-disk"></i> Simpan Password
                    </button>
                    <a href="<?php echo e(route('admin.profile')); ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    
    <div style="margin-top:16px;padding:14px 18px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px">
        <p style="font-size:12px;color:#92400e;font-weight:600"><i class="fas fa-triangle-exclamation" style="margin-right:6px"></i>Tips keamanan password:</p>
        <ul style="margin-top:8px;padding-left:16px;font-size:12px;color:#78350f;line-height:1.8">
            <li>Gunakan minimal 8 karakter</li>
            <li>Kombinasikan huruf besar, huruf kecil, angka, dan simbol</li>
            <li>Jangan gunakan informasi pribadi (nama, tanggal lahir)</li>
            <li>Ganti password secara berkala</li>
        </ul>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/setting/password.blade.php ENDPATH**/ ?>