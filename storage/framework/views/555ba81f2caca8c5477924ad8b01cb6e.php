<?php $pageTitle = 'Event & Kegiatan'; $breadcrumb = 'CMS / Event'; ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3>Daftar Event</h3>
        <div style="display:flex;gap:10px">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari event..." class="form-input" style="width:180px">
                <select name="status" class="form-input" style="width:130px">
                    <option value="">Semua</option>
                    <option value="aktif"    <?php echo e(request('status')=='aktif'?'selected':''); ?>>Aktif</option>
                    <option value="nonaktif" <?php echo e(request('status')=='nonaktif'?'selected':''); ?>>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                <?php if(request()->hasAny(['search','status'])): ?><a href="<?php echo e(route('cms.event')); ?>" class="btn btn-secondary"><i class="fas fa-xmark"></i></a><?php endif; ?>
            </form>
            <a href="<?php echo e(route('cms.event.create')); ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Event</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Kuota</th>
                    <th>Peserta</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $jumlahPeserta = $ev->pesertaAktif()->count(); ?>
                <tr>
                    <td style="font-weight:600;font-size:13px;color:#0f172a"><?php echo e($ev->judul); ?></td>
                    <td style="font-size:12px;color:#64748b;white-space:nowrap"><?php echo e($ev->tanggal_event?->format('d M Y') ?? '-'); ?></td>
                    <td style="font-size:12px;color:#94a3b8;max-width:140px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?php echo e($ev->lokasi ?? '-'); ?></td>
                    <td style="font-size:12px;color:#64748b;text-align:center">
                        <?php echo $ev->kuota ?? '<span style="color:#94a3b8">∞</span>'; ?>

                    </td>
                    <td style="text-align:center">
                        <a href="<?php echo e(route('cms.event.peserta', $ev)); ?>"
                           style="display:inline-flex;align-items:center;gap:5px;background:#ede9fe;color:#6d28d9;padding:4px 10px;border-radius:8px;font-size:12px;font-weight:700;text-decoration:none;transition:background .15s"
                           onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                            <i class="fas fa-users" style="font-size:11px"></i>
                            <?php echo e($jumlahPeserta); ?>

                            <?php if($ev->kuota): ?>
                                / <?php echo e($ev->kuota); ?>

                            <?php endif; ?>
                        </a>
                    </td>
                    <td>
                        <span class="badge <?php echo e($ev->status==='aktif'?'badge-green':'badge-amber'); ?>">
                            <?php echo e($ev->status==='aktif'?'Aktif':'Nonaktif'); ?>

                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:nowrap">
                            <a href="<?php echo e(route('cms.event.peserta', $ev)); ?>" class="btn btn-sm"
                               style="background:#ede9fe;color:#6d28d9;border:none" title="Lihat Peserta">
                                <i class="fas fa-users"></i> Peserta
                            </a>
                            <a href="<?php echo e(route('cms.event.edit', $ev)); ?>" class="btn btn-sm btn-secondary">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                            <form method="POST" action="<?php echo e(route('cms.event.destroy', $ev)); ?>" onsubmit="return confirm('Hapus event ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar-days"></i>
                            <p>Belum ada event</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($events->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.cms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/cms/event/index.blade.php ENDPATH**/ ?>