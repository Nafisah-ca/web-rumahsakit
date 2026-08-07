<?php $pageTitle = 'Data Pasien'; $breadcrumb = 'Admin / Pasien'; ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Pasien</h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <form style="display:flex;gap:8px;align-items:center" method="GET">
                <input type="hidden" name="tab" value="<?php echo e($tab); ?>">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama / No RM / NIK..." class="form-input" style="width:260px">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request('search')): ?><a href="<?php echo e(route('admin.pasien', ['tab' => $tab])); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <?php if($tab === 'aktif'): ?>
            <a href="<?php echo e(route('admin.pasien.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pasien</a>
            <?php endif; ?>
        </div>
    </div>

    
    <div style="display:flex;gap:4px;padding:0 20px;border-bottom:1px solid #e2e8f0;margin-bottom:0">
        <a href="<?php echo e(route('admin.pasien', ['tab' => 'aktif', 'search' => request('search')])); ?>"
           style="padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;border-bottom:2px solid <?php echo e($tab === 'aktif' ? '#16a34a' : 'transparent'); ?>;color:<?php echo e($tab === 'aktif' ? '#16a34a' : '#64748b'); ?>">
            <i class="fas fa-user-check" style="margin-right:6px"></i>Aktif
        </a>
        <a href="<?php echo e(route('admin.pasien', ['tab' => 'nonaktif', 'search' => request('search')])); ?>"
           style="padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;border-bottom:2px solid <?php echo e($tab === 'nonaktif' ? '#dc2626' : 'transparent'); ?>;color:<?php echo e($tab === 'nonaktif' ? '#dc2626' : '#64748b'); ?>">
            <i class="fas fa-user-slash" style="margin-right:6px"></i>Nonaktif
            <?php if($totalNonaktif > 0): ?>
            <span style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:9999px;padding:1px 8px;font-size:11px;margin-left:4px"><?php echo e($totalNonaktif); ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr>
                <th>#</th><th>Pasien</th><th>No. RM</th><th>NIK</th><th>Telepon</th><th>Usia</th>
                <?php if($tab === 'nonaktif'): ?><th>Dinonaktifkan</th><?php endif; ?>
                <th>Aksi</th>
            </tr></thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pasiens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr style="<?php echo e($tab === 'nonaktif' ? 'opacity:0.75;background:#fafafa' : ''); ?>">
                    <td style="color:#94a3b8"><?php echo e($pasiens->firstItem()+$i); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar avatar-sm avatar-sq" style="background:<?php echo e($tab === 'nonaktif' ? '#f1f5f9' : '#dcfce7'); ?>;color:<?php echo e($tab === 'nonaktif' ? '#64748b' : '#166534'); ?>">
                                <?php echo e(strtoupper(substr($p->nama_lengkap,0,1))); ?>

                            </div>
                            <div>
                                <p style="font-weight:600;color:#0f172a;font-size:13px"><?php echo e($p->nama_lengkap); ?></p>
                                <?php if($p->jenis_kelamin): ?><p style="font-size:11px;color:#94a3b8"><?php echo e($p->jenis_kelamin_label); ?></p><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><span class="code-tag"><?php echo e($p->no_rm ?? '-'); ?></span></td>
                    <td style="color:#64748b;font-size:12px"><?php echo e($p->nik ?? '-'); ?></td>
                    <td style="color:#64748b"><?php echo e($p->user?->no_hp ?? '-'); ?></td>
                    <td style="color:#64748b"><?php echo e($p->umur ? $p->umur.' th' : '-'); ?></td>
                    <?php if($tab === 'nonaktif'): ?>
                    <td style="color:#94a3b8;font-size:12px">
                        <?php echo e($p->deleted_tm ? \Carbon\Carbon::parse($p->deleted_tm)->format('d M Y') : '-'); ?>

                    </td>
                    <?php endif; ?>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <?php if($tab === 'aktif'): ?>
                                <a href="<?php echo e(route('admin.pasien.show', $p)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                                <a href="<?php echo e(route('admin.pasien.edit', $p)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="<?php echo e(route('admin.pasien.destroy', $p)); ?>" onsubmit="return confirm('Nonaktifkan pasien ini? Data tidak akan dihapus permanen.')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-user-slash"></i> Nonaktifkan</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('admin.pasien.restore', $p->id)); ?>" onsubmit="return confirm('Pulihkan pasien ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-user-check"></i> Pulihkan</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="<?php echo e($tab === 'nonaktif' ? 8 : 7); ?>">
                        <div class="empty-state">
                            <i class="fas <?php echo e($tab === 'nonaktif' ? 'fa-user-slash' : 'fa-users'); ?>"></i>
                            <p><?php echo e($tab === 'nonaktif' ? 'Tidak ada pasien nonaktif' : 'Tidak ada data pasien'); ?></p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($pasiens->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/pasien/index.blade.php ENDPATH**/ ?>