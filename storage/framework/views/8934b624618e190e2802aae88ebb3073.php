<?php $pageTitle = 'Detail Pasien'; $breadcrumb = 'Admin / Pasien / ' . ($pasien->user?->nama ?? 'Pasien'); ?>
<?php $__env->startSection('content'); ?>
<div style="display:grid;grid-template-columns:300px 1fr;gap:24px;align-items:start">
    
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body" style="text-align:center">
            <div class="avatar avatar-lg" style="background:#dcfce7;color:#166534;margin:0 auto 14px;font-size:24px">
                <?php echo e(strtoupper(substr($pasien->user?->nama ?? '?', 0, 1))); ?>

            </div>
            <p style="font-size:17px;font-weight:800;color:#0f172a"><?php echo e($pasien->user?->nama ?? '-'); ?></p>
            <p style="font-size:12px;color:#16a34a;font-weight:700;margin-top:4px"><?php echo e($pasien->no_rekam_medis ?? '-'); ?></p>

            <div style="margin-top:20px;display:flex;flex-direction:column;gap:10px;text-align:left">
                <?php $__currentLoopData = [
                    ['fas fa-id-card',     'NIK',          $pasien->nik ?? '-'],
                    ['fas fa-venus-mars',  'Jenis Kelamin',$pasien->jenis_kelamin_label],
                    ['fas fa-birthday-cake','Umur',        $pasien->umur ? $pasien->umur.' tahun' : '-'],
                    ['fas fa-phone',       'Telepon',      $pasien->user?->no_hp ?? '-'],
                    ['fas fa-tint',        'Gol. Darah',   $pasien->golongan_darah ?? '-'],
                    ['fas fa-envelope',    'Email',        $pasien->user?->email ?? '-'],
                    ['fas fa-map-marker-alt','Alamat',     Str::limit($pasien->alamat ?? '-', 60)],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ico,$lbl,$val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="display:flex;align-items:flex-start;gap:10px">
                    <i class="<?php echo e($ico); ?>" style="width:14px;text-align:center;color:#94a3b8;font-size:12px;flex-shrink:0;margin-top:3px"></i>
                    <div>
                        <p style="font-size:10px;color:#94a3b8;font-weight:600"><?php echo e($lbl); ?></p>
                        <p style="font-size:12px;color:#334155;font-weight:600"><?php echo e($val); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div style="margin-top:20px;display:flex;gap:8px">
                <a href="<?php echo e(route('admin.pasien.edit', $pasien)); ?>" class="btn btn-primary btn-sm" style="flex:1;justify-content:center">
                    <i class="fas fa-pen"></i> Edit
                </a>
                <a href="<?php echo e(route('admin.pasien')); ?>" class="btn btn-secondary btn-sm" style="flex:1;justify-content:center">Kembali</a>
            </div>
        </div>

        <?php if($pasien->penjamin): ?>
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:12px"><i class="fas fa-shield-halved" style="color:#2563eb;margin-right:6px"></i>Penjamin</p>
            <p style="font-size:12px;color:#334155;font-weight:600"><?php echo e($pasien->penjamin->nama_penjamin); ?></p>
            <p style="font-size:11px;color:#94a3b8"><?php echo e($pasien->penjamin->tipePenjamin?->nama_tipe); ?></p>
            <?php if($pasien->nomor_penjamin): ?>
            <p style="font-size:12px;color:#334155;font-family:monospace;margin-top:4px"><?php echo e($pasien->nomor_penjamin); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3>Riwayat Booking</h3>
            <span class="badge badge-blue"><?php echo e($pasien->janjiTemus->count()); ?> total</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kode</th><th>Dokter</th><th>Tanggal Booking</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $pasien->janjiTemus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                    $bc = ['pending'=>'badge-amber','approved'=>'badge-blue','completed'=>'badge-green','cancelled'=>'badge-red'][$jt->status] ?? 'badge-slate';
                    $bl = ['pending'=>'Menunggu','approved'=>'Dikonfirmasi','completed'=>'Selesai','cancelled'=>'Dibatalkan'][$jt->status] ?? $jt->status;
                    ?>
                    <tr>
                        <td><span class="code-tag"><?php echo e($jt->kode_booking); ?></span></td>
                        <td style="font-weight:600;font-size:13px"><?php echo e($jt->jadwalDokter?->dokter?->nama_dokter ?? '-'); ?></td>
                        <td style="color:#64748b;font-size:12px"><?php echo e($jt->tanggal_booking?->format('d M Y')); ?></td>
                        <td><span class="badge <?php echo e($bc); ?>"><?php echo e($bl); ?></span></td>
                        <td><a href="<?php echo e(route('admin.booking.show',$jt)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i></a></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>Belum ada riwayat booking</p></div></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/pasien/show.blade.php ENDPATH**/ ?>