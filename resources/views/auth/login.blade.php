@extends('layouts.app')

@push('styles')
<style>
    /* ── Auth Page Background ── */
    .auth-bg-wrapper {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
    }

    /* Override body bg khusus halaman auth */
    body { background: transparent !important; }

    .auth-bg-image {
        position: absolute;
        inset: -10%;
        width: 120%;
        height: 120%;
        background-image: url('{{ asset('images/auth-bg.jpg') }}');
        background-size: cover;
        background-position: center;
        opacity: 0.18;
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

    /* Gradient overlay di atas gambar */
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

    /* Dekoratif: partikel lingkaran */
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

    /* Ikon medis mengambang di latar */
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

    /* ── Auth content ── */
    .auth-page-content {
        position: relative;
        z-index: 10;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4rem 1rem;
    }

    /* Glassmorphism card */
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

    /* Label & input enhancements */
    .auth-card input,
    .auth-card select,
    .auth-card textarea {
        background: rgba(255, 255, 255, 0.95);
    }

    /* Badge hijau RS di atas card */
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

    {{-- Lingkaran dekoratif --}}
    <div class="auth-circle auth-circle-1"></div>
    <div class="auth-circle auth-circle-2"></div>
    <div class="auth-circle auth-circle-3"></div>
    <div class="auth-circle auth-circle-4"></div>

    {{-- Ikon medis mengambang --}}
    <i class="fas fa-heartbeat auth-icon-float"></i>
    <i class="fas fa-stethoscope auth-icon-float"></i>
    <i class="fas fa-hospital auth-icon-float"></i>
    <i class="fas fa-pills auth-icon-float"></i>
    <i class="fas fa-user-md auth-icon-float"></i>
</div>

{{-- ── Konten utama ── --}}
<div class="auth-page-content">
    <div class="w-full max-w-md">

        {{-- Header di atas card --}}
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="auth-header-logo">
                <div class="auth-logo-icon">
                    <i class="fas fa-hospital-alt text-white text-xl"></i>
                </div>
                <div class="text-left">
                    <div class="font-extrabold text-lg auth-title-text">RS Sari Sehat</div>
                    <div class="text-xs font-semibold auth-subtitle-text">Melayani dengan Kasih Sayang sepenuh hati</div>
                </div>
            </a>
            <h1 class="text-2xl font-extrabold text-white" style="text-shadow:0 2px 6px rgba(0,0,0,0.3);">
                Masuk ke Portal Pasien
            </h1>
            <p class="text-green-100 text-sm mt-1" style="text-shadow:0 1px 3px rgba(0,0,0,0.2);">
                Akses riwayat kesehatan dan jadwal Anda
            </p>
        </div>

        {{-- Card form --}}
        <div class="auth-card">

            {{-- Error --}}
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-red-600 text-sm font-medium">{{ $errors->first() }}</p>
            </div>
            @endif

            {{-- Session messages --}}
            @if(session('status') || session('success'))
            <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-xl flex items-start gap-2">
                <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-green-700 text-sm font-medium">{{ session('status') ?? session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl flex items-start gap-2">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                <p class="text-red-600 text-sm font-medium">{{ session('error') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="email@contoh.com"
                        class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
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

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 active:bg-green-800 text-white py-3.5 rounded-xl font-extrabold text-sm transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
            </form>

            <div class="mt-5 pt-5 border-t border-gray-100 text-center">
                <p class="text-gray-500 text-sm">Belum punya akun?
                    <a href="{{ route('register') }}" class="text-green-600 font-bold hover:text-green-800">Daftar Sekarang</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-green-100 mt-4" style="text-shadow:0 1px 2px rgba(0,0,0,0.2);">
            Dengan masuk, Anda menyetujui <a href="#" class="underline text-white">Syarat & Ketentuan</a> kami.
        </p>
    </div>
</div>

@endsection

@push('scripts')
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
@endpush
