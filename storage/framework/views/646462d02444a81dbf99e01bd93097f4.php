<?php $pageTitle = 'Manajemen User'; $breadcrumb = 'Admin / User'; ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar User</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Nama / email..." class="form-input" style="width:200px">
                <select name="role" class="form-input" style="width:130px">
                    <option value="">Semua Role</option>
                    <option value="admin"  <?php echo e(request('role')=='admin'?'selected':''); ?>>Admin</option>
                    <option value="cms"    <?php echo e(request('role')=='cms'?'selected':''); ?>>CMS</option>
                    <option value="pasien" <?php echo e(request('role')=='pasien'?'selected':''); ?>>Pasien</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request()->hasAny(['search','role'])): ?><a href="<?php echo e(route('admin.users')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Bergabung</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                $roleClr = $u->role==='admin' ? 'badge-red' : ($u->role==='cms' ? 'badge-blue' : 'badge-green');
                $roleAv  = $u->role==='admin' ? '#ef4444' : ($u->role==='cms' ? '#2563eb' : '#16a34a');
                ?>
                <tr>
                    <td style="color:#94a3b8"><?php echo e($users->firstItem()+$i); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar avatar-sm" style="background:<?php echo e($roleAv); ?>;color:#fff">
                                <?php echo e(strtoupper(substr($u->nama ?? '?', 0, 1))); ?>

                            </div>
                            <div>
                                <p style="font-weight:600;font-size:13px;color:#0f172a"><?php echo e($u->nama); ?></p>
                                <p style="font-size:11px;color:#94a3b8"><?php echo e($u->username); ?></p>
                            </div>
                        </div>
                    </td>
                    <td style="color:#64748b;font-size:13px"><?php echo e($u->email); ?></td>
                    <td><span class="badge <?php echo e($roleClr); ?>"><?php echo e($u->role_label); ?></span></td>
                    <td><span class="badge <?php echo e($u->status==='aktif' ? 'badge-green' : 'badge-slate'); ?>"><?php echo e($u->status==='aktif' ? 'Aktif' : 'Nonaktif'); ?></span></td>
                    <td style="color:#94a3b8;font-size:12px"><?php echo e($u->created_tm?->format('d M Y')); ?></td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="<?php echo e(route('admin.users.edit',$u)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <?php if($u->id !== auth()->id()): ?>
                            <form method="POST" action="<?php echo e(route('admin.users.destroy',$u)); ?>" onsubmit="return confirm('Hapus user ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-users"></i><p>Tidak ada user</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($users->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/admin/users/index.blade.php ENDPATH**/ ?>