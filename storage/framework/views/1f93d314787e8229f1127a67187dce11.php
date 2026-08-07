<?php $pageTitle = 'Dashboard'; $breadcrumb = 'Selamat datang, ' . Auth::user()->nama; ?>

<?php $__env->startSection('content'); ?>

<div class="stats-grid">
    <?php
    $statCards = [
        ['fas fa-bed-pulse',      'Total Pasien',       $stats['total_pasien'],      '#2563eb','#dbeafe'],
        ['fas fa-user-doctor',    'Dokter Aktif',       $stats['total_dokter'],      '#16a34a','#dcfce7'],
        ['fas fa-calendar-check', 'Booking Hari Ini',   $stats['booking_hari_ini'],  '#4f46e5','#e0e7ff'],
        ['fas fa-hourglass-half', 'Menunggu Verifikasi',$stats['booking_menunggu'],  '#d97706','#fef3c7'],
        ['fas fa-stethoscope',    'Spesialisasi Aktif', $stats['total_spesialisasi'], '#7c3aed','#ede9fe'],
        ['fas fa-chart-line',     'Booking Bulan Ini',  $stats['booking_bulan_ini'], '#dc2626','#fee2e2'],
    ];
    ?>
    <?php $__currentLoopData = $statCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon,$label,$val,$color,$bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="stat-card">
        <div class="stat-icon" style="background:<?php echo e($bg); ?>;color:<?php echo e($color); ?>">
            <i class="<?php echo e($icon); ?>"></i>
        </div>
        <div class="stat-value"><?php echo e(number_format($val)); ?></div>
        <div class="stat-label"><?php echo e($label); ?></div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;margin-bottom:24px">
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px">Booking 7 Hari Terakhir</p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:16px"><?php echo e(now()->subDays(6)->format('d M')); ?> – <?php echo e(now()->format('d M Y')); ?></p>
        <canvas id="bookingChart" height="120"></canvas>
    </div>
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px">Status Booking</p>
        <?php
        $statusConf = [
            'pending'   => ['Menunggu',     '#f59e0b'],
            'approved'  => ['Dikonfirmasi', '#3b82f6'],
            'completed' => ['Selesai',      '#16a34a'],
            'cancelled' => ['Dibatalkan',   '#ef4444'],
        ];
        $totalSt = array_sum($statusCounts);
        ?>
        <?php $__currentLoopData = $statusConf; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$lbl,$clr]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $cnt=$statusCounts[$key]??0; $pct=$totalSt>0?round($cnt/$totalSt*100):0; ?>
        <div style="margin-bottom:12px">
            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
                <span style="font-weight:600;color:#334155"><?php echo e($lbl); ?></span>
                <span style="color:#94a3b8"><?php echo e($cnt); ?> (<?php echo e($pct); ?>%)</span>
            </div>
            <div style="height:6px;background:#f1f5f9;border-radius:99px;overflow:hidden">
                <div style="height:100%;background:<?php echo e($clr); ?>;border-radius:99px;width:<?php echo e($pct); ?>%;transition:width .3s"></div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <h3>Booking Terbaru</h3>
        <a href="<?php echo e(route('admin.booking')); ?>" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th><th>Pasien</th><th>Dokter</th><th>Tanggal</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                $bconf=[
                    'pending'   =>['Menunggu','badge-amber'],
                    'approved'  =>['Dikonfirmasi','badge-blue'],
                    'completed' =>['Selesai','badge-green'],
                    'cancelled' =>['Dibatalkan','badge-red'],
                ];
                [$blbl,$bcls]=($bconf[$b->status]??['–','badge-slate']);
                ?>
                <tr>
                    <td><span class="code-tag"><?php echo e($b->kode_booking); ?></span></td>
                    <td style="font-weight:600"><?php echo e($b->pasien?->nama_lengkap??'-'); ?></td>
                    <td style="color:#64748b"><?php echo e($b->jadwalDokter?->dokter?->nama_dokter??'-'); ?></td>
                    <td style="color:#64748b"><?php echo e($b->tanggal_booking?->format('d M Y')); ?></td>
                    <td><span class="badge <?php echo e($bcls); ?>"><?php echo e($blbl); ?></span></td>
                        <td><a href="<?php echo e(route('admin.booking.show',$b)); ?>" style="color:#16a34a;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px"><i class="fas fa-eye"></i> Detail</a></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>Belum ada booking</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <h3>Dokter Aktif</h3>
        <a href="<?php echo e(route('admin.dokter')); ?>" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Online</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $doktersAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <?php if($d->foto): ?>
                                    <img src="<?php echo e(Storage::url($d->foto)); ?>" style="width:36px;height:36px;border-radius:10px;object-fit:cover;flex-shrink:0">
                                <?php else: ?>
                                    <div style="width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;background:linear-gradient(135deg,#374151,#1f2937)">
                                        <?php echo e(strtoupper(substr($d->nama_dokter,3,1))); ?>

                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p style="font-weight:700;font-size:13px;color:#0f172a"><?php echo e($d->nama_dokter); ?></p>
                                    <p style="font-size:11px;color:#94a3b8"></p>
                                </div>
                            </div>
                        </td>
                        <td style="color:#64748b"><?php echo e($d->spesialisasi?->nama_spesialis ?? '-'); ?></td>
                        <td><span class="badge badge-slate">Tidak</span></td>
                        <td><span class="badge <?php echo e($d->status === 'aktif' ? 'badge-green' : 'badge-slate'); ?>"><?php echo e($d->status === 'aktif' ? 'Aktif' : 'Nonaktif'); ?></span></td>
                        <td>
                            <a href="<?php echo e(route('admin.dokter.edit',$d)); ?>" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-user-doctor"></i><p>Tidak ada dokter aktif</p></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px">
    <?php $__currentLoopData = [
        ['fas fa-calendar-plus','Tambah Jadwal','admin.jadwal.create','#16a34a','#dcfce7'],
        ['fas fa-user-doctor','Tambah Dokter','admin.dokter.create','#2563eb','#dbeafe'],
        ['fas fa-user-plus','Tambah User','admin.users.create','#7c3aed','#ede9fe'],
        ['fas fa-chart-column','Laporan','admin.laporan','#dc2626','#fee2e2'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$ico,$lbl,$rt,$clr,$bg]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route($rt)); ?>" style="display:flex;align-items:center;gap:12px;padding:16px;background:#fff;border-radius:14px;border:1px solid #f1f5f9;text-decoration:none;transition:box-shadow .15s" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="width:38px;height:38px;background:<?php echo e($bg); ?>;color:<?php echo e($clr); ?>;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="<?php echo e($ico); ?>"></i>
        </div>
        <span style="font-size:13px;font-weight:600;color:#334155"><?php echo e($lbl); ?></span>
    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('bookingChart'), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($chartLabels); ?>,
        datasets: [{
            label: 'Booking',
            data: <?php echo json_encode($chartData); ?>,
            backgroundColor: 'rgba(22,163,74,.15)',
            borderColor: '#16a34a',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>