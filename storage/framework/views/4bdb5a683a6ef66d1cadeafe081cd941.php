<?php $pageTitle = 'Edit Pasien'; $breadcrumb = 'Admin / Pasien / Edit'; ?>
<?php $__env->startSection('content'); ?>
<div style="max-width:720px">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">
            Edit Pasien: <span style="color:#16a34a"><?php echo e($pasien->user?->nama ?? '-'); ?></span>
        </p>
        <form method="POST" action="<?php echo e(route('admin.pasien.update', $pasien)); ?>">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            <?php if($errors->any()): ?>
            <div class="form-error" style="margin-bottom:16px"><ul><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>
            <?php endif; ?>

            <p style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin-bottom:12px">Data Medis</p>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NIK (16 digit) <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nik" value="<?php echo e(old('nik',$pasien->nik)); ?>" class="form-input" maxlength="16" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span style="color:#ef4444">*</span></label>
                    <select name="jenis_kelamin" class="form-input" required>
                        <option value="L" <?php echo e(old('jenis_kelamin',$pasien->jenis_kelamin)=='L'?'selected':''); ?>>Laki-laki</option>
                        <option value="P" <?php echo e(old('jenis_kelamin',$pasien->jenis_kelamin)=='P'?'selected':''); ?>>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir <span style="color:#ef4444">*</span></label>
                    <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir',$pasien->tempat_lahir)); ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir',$pasien->tanggal_lahir?->format('Y-m-d'))); ?>" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Golongan Darah</label>
                    <select name="golongan_darah" class="form-input">
                        <option value="">— Pilih —</option>
                        <?php $__currentLoopData = ['A','B','AB','O']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($gb); ?>" <?php echo e(old('golongan_darah',$pasien->golongan_darah)==$gb?'selected':''); ?>><?php echo e($gb); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-input">
                        <option value="">— Pilih —</option>
                        <?php $__currentLoopData = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($ag); ?>" <?php echo e(old('agama',$pasien->agama)==$ag?'selected':''); ?>><?php echo e($ag); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="<?php echo e(old('pekerjaan',$pasien->pekerjaan)); ?>" class="form-input" placeholder="cth: PNS, Wiraswasta, dll">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap <span style="color:#ef4444">*</span></label>
                <textarea name="alamat" rows="2" class="form-input" required><?php echo e(old('alamat',$pasien->alamat)); ?></textarea>
            </div>

            <p style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:16px 0 12px">Penjamin / Asuransi</p>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Penjamin</label>
                    <select name="penjamin_id" class="form-input">
                        <option value="">— Umum / Bayar Sendiri —</option>
                        <?php $__currentLoopData = $penjamins->groupBy('tipePenjamin.nama_tipe'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipe => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <optgroup label="<?php echo e($tipe); ?>">
                            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php echo e(old('penjamin_id',$pasien->penjamin_id)==$p->id?'selected':''); ?>>
                                <?php echo e($p->nama_penjamin); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </optgroup>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Kartu Penjamin</label>
                    <input type="text" name="nomor_penjamin" value="<?php echo e(old('nomor_penjamin',$pasien->nomor_penjamin)); ?>" class="form-input" placeholder="No. BPJS / No. Asuransi">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="<?php echo e(route('admin.pasien.show',$pasien)); ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/pasien/edit.blade.php ENDPATH**/ ?>