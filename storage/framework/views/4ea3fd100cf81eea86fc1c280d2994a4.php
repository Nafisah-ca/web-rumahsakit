
<?php $__env->startSection('content'); ?>

<div class="py-20" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Tim Medis Profesional</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">
            <?php echo e(isset($online) && $online ? 'Layanan Online' : 'Jadwal Dokter'); ?>

        </h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Temukan dokter spesialis terbaik kami dan buat janji temu dengan mudah.</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Jadwal Dokter</span>
        </nav>
    </div>
</div>


<div class="bg-white border-b border-gray-100 sticky top-16 z-40 shadow-sm">
    <div class="max-w-screen-xl mx-auto px-4 py-3">
        <div class="flex flex-wrap gap-2">
            <button onclick="window.location='<?php echo e(route('dokter')); ?>'"
                class="filter-dr-btn px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       <?php echo e(($activeSpesialisSlug ?? null) == null ? 'bg-green-600 border-green-600 text-white' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600'); ?>">
                Semua
            </button>
            <?php $__currentLoopData = $spesialisasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button onclick="window.location='<?php echo e(route('dokter.by-spesialis', ['spSlug' => $sp->id])); ?>'"
                class="filter-dr-btn px-3 py-1.5 rounded-full text-xs font-bold transition-all border-2
                       <?php echo e(($activeSpesialisSlug ?? null) == $sp->id ? 'bg-green-600 border-green-600 text-white' : 'border-gray-200 text-gray-500 hover:border-green-500 hover:text-green-600'); ?>">
                <?php echo e($sp->nama_spesialis); ?>

            </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <?php if($dokterList->isEmpty()): ?>
        <div class="text-center py-20">
            <i class="fas fa-user-md text-gray-300 text-5xl mb-4 block"></i>
            <p class="text-gray-500 font-semibold">Belum ada dokter untuk spesialisasi ini.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            <?php $__currentLoopData = $dokterList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $hariSekarang = now()->locale('id')->isoFormat('dddd');
                $jadwals      = $d->jadwalAktif;
                $available    = $jadwals->isNotEmpty() && $jadwals->contains('hari', $hariSekarang);
            ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all group">
                
                <div class="h-44 relative flex items-center justify-center overflow-hidden"
                     style="background: linear-gradient(135deg, #00521f, #00b04f)">
                    <?php if($d->foto): ?>
                    <img src="<?php echo e(Storage::url($d->foto)); ?>" alt="<?php echo e($d->nama_dokter); ?>"
                         class="w-24 h-24 rounded-full object-cover border-4 border-white/40">
                    <?php else: ?>
                    <div class="w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/40">
                        <i class="fas fa-user-md text-white text-4xl"></i>
                    </div>
                    <?php endif; ?>
                    
                    <div class="absolute top-3 right-3">
                        <?php if($jadwals->isEmpty()): ?>
                            <span class="bg-gray-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Hubungi RS</span>
                        <?php elseif($available): ?>
                            <span class="flex items-center gap-1 bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Tersedia Hari Ini
                            </span>
                        <?php else: ?>
                            <span class="bg-yellow-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">Jadwal Terjadwal</span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-extrabold text-gray-900 text-sm mb-0.5 leading-tight"><?php echo e($d->nama_dokter); ?></h3>
                    <span class="inline-block bg-green-100 text-green-700 text-[10px] font-bold px-2 py-0.5 rounded-full mb-3">
                        <?php echo e($d->spesialisasi?->nama_spesialis ?? '-'); ?>

                    </span>
                    <div class="space-y-1.5 mb-4 text-xs text-gray-500">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-calendar-alt text-green-500 mt-0.5 w-3 flex-shrink-0"></i>
                            <span>
                                <?php if($jadwals->isEmpty()): ?>
                                    Hubungi RS
                                <?php else: ?>
                                    <?php
                                        $hariList   = $jadwals->pluck('hari')->unique()->implode(', ');
                                        $jam        = $jadwals->first();
                                        $jamRange   = $jam ? substr($jam->jam_mulai,0,5).' – '.substr($jam->jam_selesai,0,5) : '';
                                    ?>
                                    <?php echo e($hariList); ?><br>
                                    <span class="text-green-600 font-semibold"><?php echo e($jamRange); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-id-card text-green-500 w-3 flex-shrink-0"></i>
                            <span>SIP: <?php echo e($d->sip); ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone text-green-500 w-3 flex-shrink-0"></i>
                            <span><?php echo e($d->no_hp); ?></span>
                        </div>
                    </div>
                    <a href="<?php echo e(route('portal.booking.create', ['dokter_id' => $d->id])); ?>"
                       class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-xl text-xs font-bold transition-colors">
                        <i class="fas fa-calendar-check mr-1"></i>Buat Janji
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/dokter.blade.php ENDPATH**/ ?>