<?php $pageTitle = 'Data Pasien'; $breadcrumb = 'Admin / Pasien'; ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Pasien</h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <form style="display:flex;gap:8px;align-items:center;flex-wrap:wrap" method="GET" id="form-filter-pasien">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Cari nama / No RM / NIK..." class="form-input" style="width:240px">
                <select name="status" class="form-input" style="width:150px"
                        onchange="document.getElementById('form-filter-pasien').submit()">
                    <option value=""        <?php echo e($status === ''        ? 'selected' : ''); ?>>Semua Status</option>
                    <option value="aktif"   <?php echo e($status === 'aktif'   ? 'selected' : ''); ?>>Aktif</option>
                    <option value="nonaktif"<?php echo e($status === 'nonaktif'? 'selected' : ''); ?>>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request('search') || request('status')): ?>
                    <a href="<?php echo e(route('admin.pasien')); ?>" class="btn btn-secondary" title="Reset filter">
                        <i class="fas fa-xmark"></i>
                    </a>
                <?php endif; ?>
            </form>
            <a href="<?php echo e(route('admin.pasien.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pasien
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pasien</th>
                    <th>No. RM</th>
                    <th>NIK</th>
                    <th>Telepon</th>
                    <th>Usia</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $pasiens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $isNonaktif = !is_null($p->deleted_tm); ?>
                <tr style="<?php echo e($isNonaktif ? 'opacity:0.72;background:#fafafa' : ''); ?>">
                    <td style="color:#94a3b8"><?php echo e($pasiens->firstItem() + $i); ?></td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar avatar-sm avatar-sq"
                                 style="background:<?php echo e($isNonaktif ? '#f1f5f9' : '#dcfce7'); ?>;color:<?php echo e($isNonaktif ? '#64748b' : '#166534'); ?>">
                                <?php echo e(strtoupper(substr($p->nama_lengkap, 0, 1))); ?>

                            </div>
                            <div>
                                <p style="font-weight:600;color:#0f172a;font-size:13px"><?php echo e($p->nama_lengkap); ?></p>
                                <?php if($p->jenis_kelamin): ?>
                                    <p style="font-size:11px;color:#94a3b8"><?php echo e($p->jenis_kelamin_label); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><span class="code-tag"><?php echo e($p->no_rm ?? '-'); ?></span></td>
                    <td style="color:#64748b;font-size:12px"><?php echo e($p->nik ?? '-'); ?></td>
                    <td style="color:#64748b"><?php echo e($p->user?->no_hp ?? '-'); ?></td>
                    <td style="color:#64748b"><?php echo e($p->umur ? $p->umur . ' th' : '-'); ?></td>
                    <td>
                        <?php if($isNonaktif): ?>
                            <span class="badge badge-red">Nonaktif</span>
                        <?php else: ?>
                            <span class="badge badge-green">Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <?php if($isNonaktif): ?>
                                
                                <form method="POST" action="<?php echo e(route('admin.pasien.restore', $p->id)); ?>"
                                      onsubmit="return confirm('Pulihkan pasien ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-user-check"></i> Pulihkan
                                    </button>
                                </form>
                            <?php else: ?>
                                
                                <a href="<?php echo e(route('admin.pasien.show', $p)); ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="<?php echo e(route('admin.pasien.edit', $p)); ?>" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.pasien.destroy', $p)); ?>"
                                      onsubmit="return confirm('Nonaktifkan pasien ini? Data tidak akan dihapus permanen.')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-user-slash"></i> Nonaktifkan
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Tidak ada data pasien ditemukan</p>
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