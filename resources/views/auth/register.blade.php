@extends('layouts.app')

@push('styles')
<style>
    /* ── Auth Page Background ── */
    .auth-bg-wrapper {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none; /* background tidak blokir klik di bawahnya */
    }

    /* Override body bg khusus halaman auth */
    body { background: transparent !important; }

    /* Pastikan navbar & footer tetap di atas background */
    #navbar-main, nav, footer, .topbar {
        position: relative;
        z-index: 100 !important;
    }

    .auth-bg-image {
        position: absolute;
        inset: -10%;
        width: 120%;
        height: 120%;
        /* Fallback gradient jika gambar belum ada */
        background-image:
            url('{{ asset('images/auth-bg.jpg') }}'),
            linear-gradient(135deg, #064e3b 0%, #065f46 40%, #047857 70%, #34d399 100%);
        background-size: cover;
        background-position: center;
        opacity: 0.22;
        animation: medicalBgMotion 20s ease-in-out infinite;
        will-change: transform;
    }

    @keyframes medicalBgMotion {
        0%   { transform: scale(1.05) translate3d(0, 0, 0); }
        25%  { transform: scale(1.10) translate3d(-1.5%, 0.5%, 0); }
        50%  { transform: scale(1.12) translate3d(1.5%, -0.5%, 0); }
        75%  { transform: scale(1.08) translate3d(-0.8%, 0.8%, 0); }
        100% { transform: scale(1.05) translate3d(0, 0, 0); }
    }

    .auth-bg-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            135deg,
            rgba(5, 100, 48, 0.72) 0%,
            rgba(16, 140, 70, 0.55) 35%,
            rgba(220, 252, 231, 0.45) 70%,
            rgba(255, 255, 255, 0.60) 100%
        );
    }

    .auth-circle {
        position: absolute;
        border-radius: 50%;
        opacity: 0.08;
        background: #16a34a;
        animation: floatCircle 18s ease-in-out infinite;
    }
    .auth-circle-1 { width: 420px; height: 420px; top: -120px; left: -100px; animation-delay: 0s; }
    .auth-circle-2 { width: 280px; height: 280px; bottom: -60px; right: -60px; animation-delay: -6s; background: #059669; }
    .auth-circle-3 { width: 180px; height: 180px; top: 40%; left: 5%; animation-delay: -12s; background: #34d399; }
    .auth-circle-4 { width: 120px; height: 120px; top: 15%; right: 8%; animation-delay: -4s; background: #6ee7b7; }

    @keyframes floatCircle {
        0%, 100% { transform: translateY(0) scale(1); }
        50%       { transform: translateY(-18px) scale(1.04); }
    }

    .auth-icon-float {
        position: absolute;
        color: #16a34a;
        opacity: 0.07;
        animation: floatIcon 16s ease-in-out infinite;
        pointer-events: none;
        user-select: none;
    }
    .auth-icon-float:nth-child(1)  { top: 8%;  left: 6%;  font-size: 3.5rem; animation-delay: 0s; }
    .auth-icon-float:nth-child(2)  { top: 20%; right: 7%; font-size: 2.8rem; animation-delay: -5s; }
    .auth-icon-float:nth-child(3)  { bottom: 18%; left: 10%; font-size: 2.4rem; animation-delay: -10s; }
    .auth-icon-float:nth-child(4)  { bottom: 10%; right: 12%; font-size: 3rem; animation-delay: -3s; }
    .auth-icon-float:nth-child(5)  { top: 50%; left: 3%;  font-size: 2rem; animation-delay: -8s; }

    @keyframes floatIcon {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-14px); }
    }

    .auth-page-content {
        position: relative;
        z-index: 10;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
    }

    .auth-card {
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow:
            0 20px 60px rgba(5, 100, 48, 0.18),
            0 4px 16px rgba(0, 0, 0, 0.08);
        border-radius: 1.5rem;
        padding: 2.25rem;
    }

    .auth-card input,
    .auth-card select,
    .auth-card textarea {
        background: rgba(255, 255, 255, 0.95);
    }

    .auth-header-logo {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .auth-logo-icon {
        width: 3.2rem;
        height: 3.2rem;
        border-radius: 0.875rem;
        background: linear-gradient(135deg, #16a34a, #059669);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(22, 163, 74, 0.40);
    }
    .auth-title-text {
        color: #fff;
        text-shadow: 0 1px 3px rgba(0,0,0,0.25);
    }
    .auth-subtitle-text {
        color: rgba(255,255,255,0.85);
        text-shadow: 0 1px 2px rgba(0,0,0,0.18);
    }
</style>
@endpush

@section('content')

{{-- ── Background layer ── --}}
<div class="auth-bg-wrapper" aria-hidden="true">
    <div class="auth-bg-image"></div>
    <div class="auth-bg-overlay"></div>

    <div class="auth-circle auth-circle-1"></div>
    <div class="auth-circle auth-circle-2"></div>
    <div class="auth-circle auth-circle-3"></div>
    <div class="auth-circle auth-circle-4"></div>

    <i class="fas fa-heartbeat auth-icon-float"></i>
    <i class="fas fa-stethoscope auth-icon-float"></i>
    <i class="fas fa-hospital auth-icon-float"></i>
    <i class="fas fa-pills auth-icon-float"></i>
    <i class="fas fa-user-md auth-icon-float"></i>
</div>

{{-- ── Konten utama ── --}}
<div class="auth-page-content">
    <div class="w-full max-w-lg">

        {{-- Header di atas card --}}
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="auth-header-logo">
                <div class="auth-logo-icon">
                    <i class="fas fa-hospital-alt text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <div class="font-extrabold text-lg auth-title-text">RS Sari Sehat</div>
                    <div class="text-xs font-semibold auth-subtitle-text">Daftar sebagai Pasien</div>
                </div>
            </a>
            <h1 class="text-2xl font-extrabold text-white" style="text-shadow:0 2px 6px rgba(0,0,0,0.3);">
                Buat Akun Pasien
            </h1>
            <p class="text-green-100 text-sm mt-1" style="text-shadow:0 1px 3px rgba(0,0,0,0.2);">
                Daftar untuk membuat janji temu secara online
            </p>
        </div>

        {{-- Card form --}}
        <div class="auth-card">

            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl">
                <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="Sesuai KTP">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="email@contoh.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password" id="pw1" required minlength="6"
                                class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                                placeholder="Min. 6 karakter">
                            <button type="button" onclick="togglePw('pw1','eye1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-eye text-sm" id="eye1"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="pw2" required
                                class="w-full px-4 py-3 pr-11 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                                placeholder="Ulangi password">
                            <button type="button" onclick="togglePw('pw2','eye2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-eye text-sm" id="eye2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon') }}"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="08xxxxxxxxxx">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">NIK (No. KTP) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('nik')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="16 digit NIK KTP">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border {{ $errors->has('jenis_kelamin')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                            <option value="">— Pilih —</option>
                            <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('tanggal_lahir')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required maxlength="100"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('tempat_lahir')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                        placeholder="Kota tempat lahir">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" required
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('alamat')?'border-red-400 bg-red-50':'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all resize-none"
                        placeholder="Jalan, Nomor, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat') }}</textarea>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 active:bg-green-800 text-white py-3.5 rounded-xl font-extrabold text-sm transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-green-600 font-bold hover:text-green-800">Masuk</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-green-100 mt-4" style="text-shadow:0 1px 2px rgba(0,0,0,0.2);">
            Dengan mendaftar, Anda menyetujui <a href="#" class="underline text-white">Syarat & Ketentuan</a> kami.
        </p>
    </div>
</div>

@endsection

@push('scripts')
<script>
function togglePw(id, eyeId) {
    const f = document.getElementById(id);
    const e = document.getElementById(eyeId);
    if (f.type === 'password') { f.type = 'text'; e.className = 'fas fa-eye-slash text-sm'; }
    else { f.type = 'password'; e.className = 'fas fa-eye text-sm'; }
}
</script>
@endpush
