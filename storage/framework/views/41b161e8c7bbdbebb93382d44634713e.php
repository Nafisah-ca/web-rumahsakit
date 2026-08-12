<?php $__env->startSection('content'); ?>


<div class="py-20" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Layanan Medis</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Pelayanan</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Berbagai layanan kesehatan komprehensif didukung dokter spesialis berpengalaman dan peralatan medis modern.</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            <a href="<?php echo e(route('home')); ?>" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Pelayanan</span>
        </nav>
    </div>
</div>


<div class="bg-red-600">
    <div class="max-w-screen-xl mx-auto px-4 py-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="relative w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <span class="absolute inset-0 rounded-xl bg-white/20 animate-ping"></span>
                    <i class="fas fa-ambulance text-white text-xl relative z-10"></i>
                </div>
                <div>
                    <p class="text-white font-extrabold text-base">IGD 24 JAM – Siap Melayani</p>
                    <p class="text-red-100 text-xs">Respons cepat setiap kondisi darurat, 365 hari setahun</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="tel:02150943838" class="flex items-center gap-2 bg-white text-red-700 px-5 py-2 rounded-xl font-bold text-sm hover:bg-red-50 transition-all">
                    <i class="fas fa-phone-alt"></i> (021) 5094-3838
                </a>
                <a href="tel:118" class="flex items-center gap-2 border-2 border-white text-white px-5 py-2 rounded-xl font-bold text-sm hover:bg-white/10 transition-all">
                    <i class="fas fa-bell"></i> 118
                </a>
            </div>
        </div>
    </div>
</div>


<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="section-label">Departemen & Spesialisasi</span>
            <h2 class="section-title">Layanan <span>Unggulan</span></h2>
            <p class="text-gray-500 text-sm mt-2 max-w-lg mx-auto">
                Tersedia lebih dari <?php echo e($layananList->count()); ?> layanan dengan dokter ahli dan peralatan medis terkini.
            </p>
        </div>

        <?php if($layananList->isEmpty()): ?>
        <div class="text-center py-16">
            <i class="fas fa-stethoscope text-5xl text-gray-200 block mb-4"></i>
            <p class="text-gray-400 font-semibold">Belum ada layanan yang tersedia.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php $__currentLoopData = $layananList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-base group fade-up">
                <div class="h-1.5 rounded-t-2xl bg-green-500"></div>
                <div class="p-6">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-green-50 group-hover:scale-110 transition-transform">
                            <i class="fas <?php echo e($l->icon ?? 'fa-stethoscope'); ?> text-lg text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-gray-900 text-base"><?php echo e($l->nama_layanan); ?></h3>
                            <span class="text-xs font-semibold text-gray-400">RS Sari Sehat</span>
                        </div>
                    </div>

                    <?php if($l->deskripsi): ?>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4"><?php echo e(Str::limit($l->deskripsi, 120)); ?></p>
                    <?php endif; ?>

                    <?php if($l->gambar): ?>
                    <img src="<?php echo e(Storage::url($l->gambar)); ?>" alt="<?php echo e($l->nama_layanan); ?>"
                         class="w-full h-32 object-cover rounded-xl mb-4">
                    <?php endif; ?>

                    <a href="<?php echo e(route('portal.booking.create')); ?>"
                       class="block w-full text-center btn-green py-2.5 rounded-xl text-sm">
                        Buat Janji Temu
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</section>


<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10">
            <span class="section-label">Layanan Premium</span>
            <h2 class="section-title">Pelayanan <span>Khusus</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php $__currentLoopData = [
                ['icon'=>'fa-spa',  'title'=>'Pain Clinic & Wellness',   'desc'=>'Terapi nyeri kronis tanpa operasi menggunakan metode Radio Frekuensi, PRP, dan teknik terkini.',  'color'=>'#7c3aed'],
                ['icon'=>'fa-baby', 'title'=>'Pusat Layanan Ibu & Anak', 'desc'=>'Layanan maternal dan pediatri terpadu: NICU, ruang bersalin nyaman, dokter anak subspesialis.',   'color'=>'#be185d'],
                ['icon'=>'fa-dna',  'title'=>'Onkologi Terpadu',         'desc'=>'Penanganan kanker multidisiplin dengan kemoterapi, radioterapi, dan bedah tumor oleh tim ahli.',   'color'=>'#0f4c81'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="card-base p-6 text-center group fade-up">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform"
                     style="background: <?php echo e($pk['color']); ?>20">
                    <i class="fas <?php echo e($pk['icon']); ?> text-2xl" style="color: <?php echo e($pk['color']); ?>"></i>
                </div>
                <h3 class="font-extrabold text-gray-900 text-base mb-2"><?php echo e($pk['title']); ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4"><?php echo e($pk['desc']); ?></p>
                <a href="<?php echo e(route('portal.booking.create')); ?>" class="btn-outline-green text-xs py-2 px-4">
                    Konsultasi <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/layanan.blade.php ENDPATH**/ ?>