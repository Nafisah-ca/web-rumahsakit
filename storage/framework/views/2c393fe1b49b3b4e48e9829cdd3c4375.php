<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-gray-50 flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="<?php echo e(route('home')); ?>" class="inline-flex items-center gap-3 mb-5">
                <div class="w-12 h-12 rounded-2xl bg-green-600 flex items-center justify-center shadow-lg">
                    <i class="fas fa-hospital-alt text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <div class="font-extrabold text-gray-900 text-lg">RS Sari Sehat</div>
                    <div class="text-green-600 text-xs font-semibold">Melayani dengan Kasih Sayang sepenuh hati</div>
                </div>
            </a>
            <h1 class="text-2xl font-extrabold text-gray-900">Masuk ke Portal Pasien</h1>
            <p class="text-gray-500 text-sm mt-1">Akses riwayat kesehatan dan jadwal Anda</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            
            <?php if($errors->any()): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-red-600 text-sm font-medium"><?php echo e($errors->first()); ?></p>
            </div>
            <?php endif; ?>

            
            <?php if(session('status') || session('success')): ?>
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl flex items-start gap-2">
                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-green-700 text-sm font-medium"><?php echo e(session('status') ?? session('success')); ?></p>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-red-600 text-sm font-medium"><?php echo e(session('error')); ?></p>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.post')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus
                        placeholder="email@contoh.com"
                        class="w-full px-4 py-3 rounded-xl border <?php echo e($errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200'); ?> text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="pw-field" required
                            placeholder="••••••••"
                            class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                        <button type="button" onclick="togglePw()" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                            <i class="fas fa-eye text-sm" id="pw-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded accent-green-600"> Ingat saya
                    </label>
                </div>
                <button type="submit" class="w-full btn-green py-3.5 rounded-xl font-extrabold justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">Belum punya akun?
                    <a href="<?php echo e(route('register')); ?>" class="text-green-600 font-bold hover:text-green-800">Daftar Sekarang</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">
            Dengan masuk, Anda menyetujui <a href="#" class="text-green-600">Syarat & Ketentuan</a> kami.
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function togglePw() {
    const f = document.getElementById('pw-field');
    const e = document.getElementById('pw-eye');
    if (f.type === 'password') {
        f.type = 'text';
        e.className = 'fas fa-eye-slash text-sm';
    } else {
        f.type = 'password';
        e.className = 'fas fa-eye text-sm';
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\web-rumahsakit\web-rumahsakit\resources\views/auth/login.blade.php ENDPATH**/ ?>