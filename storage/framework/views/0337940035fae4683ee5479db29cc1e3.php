<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Janji Temu Saya</h1>
                <p class="text-gray-500 text-sm mt-1">Pantau status booking dan riwayat kunjungan Anda</p>
            </div>
            <a href="<?php echo e(route('portal.booking.create')); ?>"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm">
                <i class="fas fa-plus"></i> Buat Janji Baru
            </a>
        </div>

        <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-green-700 text-sm font-semibold">
            <i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>

        
        <?php if($pasien): ?>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6 flex items-center gap-4">
            <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center text-white text-xl font-black flex-shrink-0">
                <?php echo e(strtoupper(substr($pasien->user?->nama ?? $pasien->nama_lengkap ?? '?', 0, 1))); ?>

            </div>
            <div class="flex-1 min-w-0">
                <p class="font-extrabold text-gray-900"><?php echo e($pasien->user?->nama ?? $pasien->nama_lengkap); ?></p>
                <p class="text-gray-500 text-sm">
                    No. RM: <span class="font-semibold text-green-700"><?php echo e($pasien->no_rekam_medis ?? '-'); ?></span>
                    &nbsp;|&nbsp; <?php echo e($pasien->jenis_kelamin_label); ?>

                </p>
            </div>
            <a href="<?php echo e(route('portal.profil')); ?>" class="text-xs font-bold text-gray-400 hover:text-green-600 transition-colors border border-gray-200 hover:border-green-500 px-3 py-2 rounded-lg">
                <i class="fas fa-edit mr-1"></i>Edit Profil
            </a>
        </div>
        <?php endif; ?>

        
        <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <?php
        $statusConf = [
            'pending'   => ['Menunggu',     'bg-amber-100 text-amber-700 border-amber-200'],
            'approved'  => ['Dikonfirmasi', 'bg-blue-100 text-blue-700 border-blue-200'],
            'completed' => ['Selesai',      'bg-green-100 text-green-700 border-green-200'],
            'cancelled' => ['Dibatalkan',   'bg-red-100 text-red-700 border-red-200'],
        ];
        [$statusLabel, $statusClass] = $statusConf[$b->status] ?? [$b->status, 'bg-slate-100 text-slate-600 border-slate-200'];
        ?>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg font-semibold"><?php echo e($b->kode_booking); ?></span>
                        <span class="badge border <?php echo e($statusClass); ?> text-xs font-bold"><?php echo e($statusLabel); ?></span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">Dokter</p>
                            <p class="font-semibold text-gray-800"><?php echo e($b->jadwalDokter?->dokter?->nama_dokter ?? '-'); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($b->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis ?? '-'); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">Tanggal</p>
                            <p class="font-semibold text-gray-800"><?php echo e($b->tanggal_booking?->format('d M Y')); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($b->jadwalDokter ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : ''); ?></p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">No. Antrian</p>
                            <p class="font-black text-green-700 text-xl"><?php echo e($b->nomor_antrian ?? '-'); ?></p>
                        </div>
                    </div>
                    <?php if($b->keluhan): ?>
                    <div class="mt-3 p-2.5 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-400 font-semibold">Keluhan:</p>
                        <p class="text-sm text-gray-700"><?php echo e($b->keluhan); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if(in_array($b->status, ['pending','approved'])): ?>
                <form method="POST" action="<?php echo e(route('portal.booking.cancel', $b)); ?>" onsubmit="return confirm('Batalkan janji temu ini?')">
                    <?php echo csrf_field(); ?>
                    <button class="flex-shrink-0 text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 border border-red-200 px-3 py-2 rounded-xl transition-all">
                        <i class="fas fa-times mr-1"></i>Batalkan
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <i class="fas fa-calendar-times text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">Belum ada janji temu</p>
            <p class="text-gray-400 text-sm mt-1">Buat janji temu pertama Anda sekarang</p>
            <a href="<?php echo e(route('portal.booking.create')); ?>" class="inline-flex items-center gap-2 mt-4 bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-green-700 transition-all">
                <i class="fas fa-plus"></i> Buat Janji Temu
            </a>
        </div>
        <?php endif; ?>

        <div class="mt-4"><?php echo e($bookings->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/portal/booking/riwayat.blade.php ENDPATH**/ ?>