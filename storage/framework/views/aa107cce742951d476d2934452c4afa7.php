<?php $pageTitle = 'Booking & Janji Temu'; $breadcrumb = 'Admin / Booking'; ?>
<?php $__env->startSection('content'); ?>


<div class="card" style="margin-bottom:16px">
    <div class="card-body" style="padding:16px 20px">
        <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end">
            <div style="flex:1;min-width:160px">
                <label class="form-label" style="margin-bottom:4px">Nama Pasien</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama pasien..." class="form-input">
            </div>
            <div style="flex:1;min-width:140px">
                <label class="form-label" style="margin-bottom:4px">Status</label>
                <select name="status" class="form-input">
                    <option value="">Semua Status</option>
                    <option value="pending"   <?php echo e(request('status')=='pending'   ? 'selected':''); ?>>Menunggu</option>
                    <option value="approved"  <?php echo e(request('status')=='approved'  ? 'selected':''); ?>>Dikonfirmasi</option>
                    <option value="completed" <?php echo e(request('status')=='completed' ? 'selected':''); ?>>Selesai</option>
                    <option value="cancelled" <?php echo e(request('status')=='cancelled' ? 'selected':''); ?>>Dibatalkan</option>
                </select>
            </div>
            <div style="flex:1;min-width:160px">
                <label class="form-label" style="margin-bottom:4px">Dokter</label>
                <select name="dokter_id" class="form-input">
                    <option value="">Semua Dokter</option>
                    <?php $__currentLoopData = $dokters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($d->id); ?>" <?php echo e(request('dokter_id')==$d->id ? 'selected':''); ?>><?php echo e($d->nama_dokter); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div style="flex:1;min-width:140px">
                <label class="form-label" style="margin-bottom:4px">Tanggal</label>
                <input type="date" name="tanggal" value="<?php echo e(request('tanggal')); ?>" class="form-input">
            </div>
            <div style="display:flex;gap:8px;padding-top:1px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                <?php if(request()->hasAny(['search','status','dokter_id','tanggal'])): ?>
                <a href="<?php echo e(route('admin.booking')); ?>" class="btn btn-secondary" title="Reset filter"><i class="fas fa-xmark"></i></a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-header">
        <h3>Daftar Booking</h3>
        <span style="font-size:12px;color:#94a3b8"><?php echo e($bookings->total()); ?> data ditemukan</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Pasien</th>
                    <th>Dokter</th>
                    <th>Tanggal Booking</th>
                    <th style="text-align:center">No. Antrian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                $bc = ['pending'=>'badge-amber','approved'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'][$b->status] ?? 'badge-slate';
                $bl = ['pending'=>'Menunggu','approved'=>'Dikonfirmasi','completed'=>'Selesai','cancelled'=>'Dibatalkan'][$b->status] ?? $b->status;
                ?>
                <tr>
                    <td><span class="code-tag"><?php echo e($b->kode_booking); ?></span></td>
                    <td>
                        <p style="font-weight:600;font-size:13px"><?php echo e($b->pasien?->user?->nama ?? $b->pasien?->nama_lengkap ?? '-'); ?></p>
                        <p style="font-size:11px;color:#94a3b8"><?php echo e($b->pasien?->no_rekam_medis ?? '-'); ?></p>
                    </td>
                    <td style="color:#64748b;font-size:12px"><?php echo e($b->jadwalDokter?->dokter?->nama_dokter ?? '-'); ?></td>
                    <td style="color:#64748b;font-size:12px">
                        <?php echo e($b->tanggal_booking?->format('d M Y') ?? '-'); ?>

                        <br><span style="color:#94a3b8"><?php echo e($b->jadwalDokter?->jam_mulai ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : ''); ?></span>
                    </td>
                    <td style="font-weight:700;text-align:center"><?php echo e($b->nomor_antrian ?? '-'); ?></td>
                    <td><span class="badge <?php echo e($bc); ?>"><?php echo e($bl); ?></span></td>
                    <td>
                        <div style="display:flex;gap:6px">
                            <a href="<?php echo e(route('admin.booking.show',$b)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                            <form method="POST" action="<?php echo e(route('admin.booking.destroy',$b)); ?>" onsubmit="return confirm('Hapus booking ini?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-calendar-xmark"></i>
                            <p>Tidak ada data booking</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="table-footer"><?php echo e($bookings->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/booking/index.blade.php ENDPATH**/ ?>