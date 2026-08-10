<?php $pageTitle = 'Profile Saya'; $breadcrumb = 'Admin / Profile'; ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:600px">

    
    <div class="card" style="margin-bottom:20px">
        <div class="card-body" style="display:flex;align-items:center;gap:20px">
            <div style="width:64px;height:64px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <span style="font-size:26px;font-weight:800;color:#16a34a"><?php echo e(strtoupper(substr($user->nama, 0, 1))); ?></span>
            </div>
            <div>
                <p style="font-size:18px;font-weight:800;color:#0f172a"><?php echo e($user->nama); ?></p>
                <p style="font-size:13px;color:#64748b;margin-top:3px"><?php echo e($user->email); ?></p>
                <span class="badge badge-red" style="margin-top:6px;font-size:11px"><?php echo e($user->role_label); ?></span>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-pen" style="color:#16a34a;margin-right:8px"></i>Edit Profile</h3>
        </div>
        <div class="card-body">
            <?php if($errors->any()): ?>
            <div class="form-error" style="margin-bottom:16px">
                <ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('admin.profile.update')); ?>">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama" value="<?php echo e(old('nama', $user->nama)); ?>"
                           class="form-input <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Nama lengkap admin" required>
                    <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-hint" style="color:#dc2626"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>"
                           class="form-input <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="email@example.com" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="form-hint" style="color:#dc2626"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label class="form-label">No. Handphone</label>
                    <input type="text" name="no_hp" value="<?php echo e(old('no_hp', $user->no_hp)); ?>"
                           class="form-input" placeholder="08xxxxxxxxxx">
                </div>

                <div style="display:flex;gap:10px;align-items:center;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    
    <div style="margin-top:16px;padding:14px 18px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;display:flex;align-items:center;justify-content:space-between">
        <div>
            <p style="font-size:13px;font-weight:600;color:#334155"><i class="fas fa-lock" style="color:#64748b;margin-right:8px"></i>Password</p>
            <p style="font-size:12px;color:#94a3b8;margin-top:2px">Ubah password akun Anda secara berkala</p>
        </div>
        <a href="<?php echo e(route('admin.setting.password')); ?>" class="btn btn-secondary btn-sm">
            Ganti Password <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/setting/profile.blade.php ENDPATH**/ ?>