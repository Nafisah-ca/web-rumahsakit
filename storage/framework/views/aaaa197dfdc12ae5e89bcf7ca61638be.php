<?php $__env->startSection('content'); ?>

<?php
    $penjamins = \App\Models\Penjamin::where('status','aktif')->with('tipePenjamin')->get();
    $activeTab = request('tab', 'profil');
?>

<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-3xl mx-auto">

        
        <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl p-6 flex items-center gap-5 mb-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-white text-2xl font-black flex-shrink-0">
                <?php echo e(strtoupper(substr(Auth::user()->nama ?? '?', 0, 1))); ?>

            </div>
            <div>
                <p class="text-white font-extrabold text-lg"><?php echo e(Auth::user()->nama); ?></p>
                <p class="text-green-200 text-sm"><?php echo e(Auth::user()->email); ?></p>
                <?php if($pasien?->no_rekam_medis): ?>
                <p class="text-green-100 text-xs font-semibold mt-1">No. RM: <?php echo e($pasien->no_rekam_medis); ?></p>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="flex gap-2 mb-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-1.5">
            <a href="<?php echo e(route('portal.profil')); ?>?tab=profil"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all
                      <?php echo e($activeTab === 'profil' ? 'bg-green-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50'); ?>">
                <i class="fas fa-user text-xs"></i> Profil Saya
            </a>
            <a href="<?php echo e(route('portal.profil')); ?>?tab=riwayat"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all
                      <?php echo e($activeTab === 'riwayat' ? 'bg-green-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50'); ?>">
                <i class="fas fa-calendar-check text-xs"></i> Riwayat Poliklinik
            </a>
            <a href="<?php echo e(route('portal.profil')); ?>?tab=penjamin"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all
                      <?php echo e($activeTab === 'penjamin' ? 'bg-green-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50'); ?>">
                <i class="fas fa-shield-halved text-xs"></i> Penjamin & Asuransi
            </a>
        </div>

        
        <?php if(session('success')): ?>
        <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-green-700 text-sm font-semibold">
            <i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> <?php echo e(session('success')); ?>

        </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
        </div>
        <?php endif; ?>

        
        <?php if($activeTab === 'profil'): ?>
        <form method="POST" action="<?php echo e(route('portal.profil.update')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base">
                    <i class="fas fa-user text-green-600"></i> Data Pribadi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="<?php echo e(old('nama_lengkap', Auth::user()->nama)); ?>" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                        <p class="text-xs text-gray-400 mt-1">Akan mengubah nama akun Anda.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">No. HP / WhatsApp</label>
                        <input type="text" name="telepon" value="<?php echo e(old('telepon', Auth::user()->no_hp)); ?>" maxlength="20"
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">NIK (16 digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="<?php echo e(old('nik', $pasien?->nik)); ?>" maxlength="16" required
                            placeholder="16 digit NIK KTP"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Pilih —</option>
                            <option value="L" <?php echo e(old('jenis_kelamin',$pasien?->jenis_kelamin)=='L'?'selected':''); ?>>Laki-laki</option>
                            <option value="P" <?php echo e(old('jenis_kelamin',$pasien?->jenis_kelamin)=='P'?'selected':''); ?>>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir', $pasien?->tempat_lahir)); ?>" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $pasien?->tanggal_lahir?->format('Y-m-d'))); ?>" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Pilih —</option>
                            <?php $__currentLoopData = ['A','B','AB','O']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($gb); ?>" <?php echo e(old('golongan_darah',$pasien?->golongan_darah)==$gb?'selected':''); ?>><?php echo e($gb); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Agama</label>
                        <select name="agama" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Pilih —</option>
                            <?php $__currentLoopData = ['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ag); ?>" <?php echo e(old('agama',$pasien?->agama)==$ag?'selected':''); ?>><?php echo e($ag); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="<?php echo e(old('pekerjaan', $pasien?->pekerjaan)); ?>"
                            placeholder="Contoh: Pegawai Swasta"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none transition-all resize-none"
                        placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota"><?php echo e(old('alamat', $pasien?->alamat)); ?></textarea>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-extrabold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                <i class="fas fa-save"></i> Simpan Profil
            </button>
        </form>

        
        <?php elseif($activeTab === 'riwayat'): ?>
        <?php
            $bookings = \App\Models\JanjiTemu::with(['jadwalDokter.dokter.spesialisasi'])
                ->where('pasien_id', $pasien?->id ?? 0)
                ->orderByDesc('tanggal_booking')
                ->paginate(10);
        ?>

        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500">Riwayat semua janji temu Anda</p>
            <a href="<?php echo e(route('portal.booking.create')); ?>"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm">
                <i class="fas fa-plus text-xs"></i> Buat Janji Baru
            </a>
        </div>

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
                        <span class="badge border <?php echo e($statusClass); ?> text-xs font-bold px-2.5 py-0.5 rounded-full"><?php echo e($statusLabel); ?></span>
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
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-14 text-center">
            <i class="fas fa-calendar-times text-4xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">Belum ada riwayat janji temu</p>
            <a href="<?php echo e(route('portal.booking.create')); ?>" class="inline-flex items-center gap-2 mt-4 bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-green-700 transition-all">
                <i class="fas fa-plus"></i> Buat Janji Temu
            </a>
        </div>
        <?php endif; ?>
        <?php if($bookings->hasPages()): ?>
        <div class="mt-4"><?php echo e($bookings->appends(['tab' => 'riwayat'])->links()); ?></div>
        <?php endif; ?>

        
        <?php elseif($activeTab === 'penjamin'): ?>
        <form method="POST" action="<?php echo e(route('portal.profil.update')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

            
            <input type="hidden" name="nama_lengkap" value="<?php echo e(Auth::user()->nama); ?>">
            <input type="hidden" name="telepon" value="<?php echo e(Auth::user()->no_hp); ?>">
            <input type="hidden" name="nik" value="<?php echo e($pasien?->nik ?? '0000000000000000'); ?>">
            <input type="hidden" name="jenis_kelamin" value="<?php echo e($pasien?->jenis_kelamin ?? 'L'); ?>">
            <input type="hidden" name="tempat_lahir" value="<?php echo e($pasien?->tempat_lahir ?? '-'); ?>">
            <input type="hidden" name="tanggal_lahir" value="<?php echo e($pasien?->tanggal_lahir?->format('Y-m-d') ?? now()->subYears(20)->format('Y-m-d')); ?>">
            <input type="hidden" name="alamat" value="<?php echo e($pasien?->alamat ?? '-'); ?>">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base">
                    <i class="fas fa-shield-halved text-green-600"></i> Penjamin / Asuransi
                </h3>
                <p class="text-xs text-gray-500">
                    Isi jika Anda menggunakan BPJS Kesehatan atau asuransi. Data ini akan digunakan saat proses pembayaran.
                </p>

                <?php if($pasien?->penjamin): ?>
                <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-xl mb-2">
                    <i class="fas fa-circle-check text-green-600"></i>
                    <div>
                        <p class="text-xs font-bold text-green-800">Penjamin Aktif: <?php echo e($pasien->penjamin->nama_penjamin); ?></p>
                        <?php if($pasien->nomor_penjamin): ?>
                        <p class="text-xs text-green-700">No. Kartu: <?php echo e($pasien->nomor_penjamin); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Penjamin</label>
                        <select name="penjamin_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Umum / Bayar Sendiri —</option>
                            <?php $__currentLoopData = $penjamins->groupBy('tipePenjamin.nama_tipe'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipe => $list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <optgroup label="<?php echo e($tipe); ?>">
                                <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($p->id); ?>"
                                    <?php echo e(old('penjamin_id', $pasien?->penjamin_id) == $p->id ? 'selected' : ''); ?>>
                                    <?php echo e($p->nama_penjamin); ?>

                                </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Pilih BPJS Kesehatan, Prudential, Allianz, dll.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Kartu Penjamin</label>
                        <input type="text" name="nomor_penjamin"
                            value="<?php echo e(old('nomor_penjamin', $pasien?->nomor_penjamin)); ?>"
                            placeholder="Contoh: 0001234567890 (No. BPJS)"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none transition-all">
                        <p class="text-xs text-gray-400 mt-1">Nomor kartu BPJS / nomor polis asuransi.</p>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-extrabold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                <i class="fas fa-save"></i> Simpan Data Penjamin
            </button>
        </form>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\rumahsakit\resources\views/portal/profil.blade.php ENDPATH**/ ?>