<?php $pageTitle = 'Profile Saya'; $breadcrumb = 'Admin / Profile'; ?>

<?php $__env->startSection('content'); ?>
<div style="max-width:600px">

    
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

            <form method="POST" action="<?php echo e(route('admin.profile.update')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

                
                <div class="form-group"
                     style="display:flex;align-items:center;gap:20px;padding:16px;background:#f8fafc;border-radius:14px;margin-bottom:20px">
                    <div id="foto-preview-wrap" style="flex-shrink:0">
                        <?php if($user->foto): ?>
                            <img id="foto-preview-img"
                                 src="<?php echo e(Storage::url($user->foto)); ?>"
                                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0">
                        <?php else: ?>
                            <div id="foto-preview-initial"
                                 style="width:80px;height:80px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;border:3px solid #e2e8f0">
                                <span style="font-size:28px;font-weight:800;color:#16a34a"><?php echo e(strtoupper(substr($user->nama, 0, 1))); ?></span>
                            </div>
                            <img id="foto-preview-img" src=""
                                 style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;display:none">
                        <?php endif; ?>
                    </div>
                    <div style="flex:1">
                        <p style="font-size:13px;font-weight:600;color:#334155;margin-bottom:4px">Foto Profil</p>
                        <p style="font-size:11px;color:#94a3b8;margin-bottom:10px">JPG, PNG maksimal 2MB</p>
                        <label style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:9px;cursor:pointer;font-size:12px;font-weight:600;color:#475569;transition:all .15s"
                               onmouseover="this.style.borderColor='#16a34a';this.style.color='#16a34a'"
                               onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                            <i class="fas fa-upload"></i> Pilih Foto
                            <input type="file" name="foto" id="foto-input" accept="image/*" style="display:none">
                        </label>
                    </div>
                </div>

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

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('foto-input')?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    const img  = document.getElementById('foto-preview-img');
    const init = document.getElementById('foto-preview-initial');
    img.src = url;
    img.style.display = 'block';
    if (init) init.style.display = 'none';
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/admin/setting/profile.blade.php ENDPATH**/ ?>