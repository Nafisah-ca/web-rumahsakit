<?php $__env->startSection('content'); ?>

<div class="py-16" style="background: linear-gradient(135deg, #4c1d95, #7c3aed);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-purple-300 text-xs font-black uppercase tracking-widest block mb-2">Jadwal Kegiatan</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Event & Kegiatan</h1>
        <p class="text-purple-100 text-sm max-w-xl mx-auto">Ikuti event kesehatan dan edukasi dari RS Sari Sehat</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-purple-200">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Event</span>
        </nav>
    </div>
</div>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">

        <?php if($events->isEmpty()): ?>
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-calendar-days text-5xl opacity-20 block mb-4"></i>
            <p class="font-semibold text-lg">Belum ada event mendatang</p>
        </div>
        <?php else: ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('event.detail', $ev)); ?>"
               class="group flex flex-col bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">

                
                <div class="relative flex-shrink-0" style="height:180px; background: linear-gradient(135deg,#4c1d95,#7c3aed)">
                    <?php if($ev->gambar): ?>
                    <img src="<?php echo e(Storage::url($ev->gambar)); ?>" alt="<?php echo e($ev->judul); ?>"
                         class="absolute inset-0 w-full h-full object-cover">
                    <?php elseif($ev->thumbnail): ?>
                    <img src="<?php echo e(Storage::url($ev->thumbnail)); ?>" alt="<?php echo e($ev->judul); ?>"
                         class="absolute inset-0 w-full h-full object-cover">
                    <?php else: ?>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <i class="fas fa-calendar-star text-5xl text-white opacity-20"></i>
                    </div>
                    <?php endif; ?>
                    <div class="absolute top-3 left-3">
                        <span class="bg-purple-600 text-white text-[10px] font-black px-2.5 py-1 rounded-full">EVENT</span>
                    </div>
                    <div class="absolute bottom-3 right-3 bg-black/50 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-xl">
                        <?php echo e($ev->tanggal_event?->format('d M Y')); ?>

                    </div>
                </div>

                
                <div class="flex flex-col flex-1 p-5">
                    <h3 class="font-extrabold text-gray-900 text-base leading-snug mb-2 group-hover:text-purple-600 transition-colors line-clamp-2">
                        <?php echo e($ev->judul); ?>

                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed line-clamp-3 flex-1">
                        <?php echo e(Str::limit(strip_tags($ev->deskripsi ?? ''), 120)); ?>

                    </p>
                    <div class="mt-4 space-y-1.5 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-days text-purple-500 w-3 flex-shrink-0"></i>
                            <?php echo e($ev->tanggal_event?->format('d M Y')); ?>

                            <?php if($ev->waktu_event): ?>
                            &nbsp;·&nbsp; <?php echo e(substr($ev->waktu_event, 0, 5)); ?> WIB
                            <?php endif; ?>
                        </div>
                        <?php if($ev->lokasi): ?>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-location-dot text-purple-500 w-3 flex-shrink-0"></i>
                            <?php echo e($ev->lokasi); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="flex items-center justify-between pt-3 mt-3 border-t border-gray-100">
                        <?php if($ev->tanggal_event?->isFuture()): ?>
                        <span class="text-xs font-bold text-green-600">
                            <i class="fas fa-clock mr-1"></i><?php echo e($ev->tanggal_event->diffForHumans()); ?>

                        </span>
                        <?php else: ?>
                        <span class="text-xs text-gray-400">Sudah berlangsung</span>
                        <?php endif; ?>
                        <span class="inline-flex items-center gap-1 text-xs font-bold text-purple-700">
                            Lihat Detail <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-10 flex justify-center"><?php echo e($events->links()); ?></div>
        <?php endif; ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/event.blade.php ENDPATH**/ ?>