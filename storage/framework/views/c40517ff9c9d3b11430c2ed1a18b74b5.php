<?php $pageTitle = 'Promo'; $breadcrumb = 'CMS / Promo'; ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Promo</h3>
        <div style="display:flex;gap:10px">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari promo..." class="form-input" style="width:180px">
                <select name="status" class="form-input" style="width:130px">
                    <option value="">Semua Status</option>
                    <option value="aktif"    <?php echo e(request('status')=='aktif'?'selected':''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(request('status')=='nonaktif'?'selected':''); ?>>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request()->hasAny(['search','status'])): ?><a href="<?php echo e(route('cms.promo')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <a href="<?php echo e(route('cms.promo.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Promo</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Promo</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Sisa Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $promos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $promo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $isExpired  = $promo->tanggal_selesai && $promo->tanggal_selesai->isPast();
                    $statusBadge = $promo->status === 'aktif' ? 'badge-green' : 'badge-slate';
                    $statusLabel = $promo->status === 'aktif' ? 'Aktif' : 'Nonaktif';
                ?>
                <tr>
                    
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:40px;height:40px;border-radius:10px;flex-shrink:0;overflow:hidden;background:#16a34a;display:flex;align-items:center;justify-content:center">
                                <?php if($promo->thumbnail): ?>
                                <img src="<?php echo e(Storage::url($promo->thumbnail)); ?>" style="width:100%;height:100%;object-fit:cover">
                                <?php elseif($promo->gambar): ?>
                                <img src="<?php echo e(Storage::url($promo->gambar)); ?>" style="width:100%;height:100%;object-fit:cover">
                                <?php else: ?>
                                <i class="fas fa-tag text-white text-sm"></i>
                                <?php endif; ?>
                            </div>
                            <p style="font-weight:600;font-size:13px;color:#0f172a"><?php echo e($promo->judul); ?></p>
                        </div>
                    </td>

                    
                    <td style="font-size:12px;color:#64748b;white-space:nowrap">
                        <?php echo e($promo->tanggal_mulai?->format('d M Y') ?? '-'); ?>

                    </td>

                    
                    <td style="font-size:12px;white-space:nowrap;color:<?php echo e($isExpired ? '#ef4444' : '#64748b'); ?>;font-weight:<?php echo e($isExpired ? '700' : '400'); ?>">
                        <?php echo e($promo->tanggal_selesai?->format('d M Y') ?? 'Tidak terbatas'); ?>

                        <?php if($isExpired): ?><span style="font-size:10px;background:#fee2e2;color:#ef4444;padding:1px 6px;border-radius:20px;margin-left:4px">Berakhir</span><?php endif; ?>
                    </td>

                    
                    <td style="font-size:12px;color:#64748b;white-space:nowrap">
                        <?php if(!$promo->tanggal_selesai): ?>
                            <span style="color:#94a3b8">Tidak terbatas</span>
                        <?php elseif($isExpired): ?>
                            <span style="color:#ef4444">Sudah berakhir</span>
                        <?php else: ?>
                            <span style="color:#16a34a;font-weight:600"><?php echo e($promo->tanggal_selesai->diffForHumans()); ?></span>
                        <?php endif; ?>
                    </td>

                    
                    <td>
                        <span class="badge <?php echo e($statusBadge); ?>"><?php echo e($statusLabel); ?></span>
                    </td>

                    
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="<?php echo e(route('cms.promo.edit',$promo)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            <form method="POST" action="<?php echo e(route('cms.promo.destroy',$promo)); ?>" onsubmit="return confirm('Hapus promo ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-tag"></i><p>Belum ada promo</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($promos->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/cms/promo/index.blade.php ENDPATH**/ ?>