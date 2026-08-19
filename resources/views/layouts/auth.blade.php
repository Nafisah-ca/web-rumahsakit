<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@php $setting_global = \App\Models\WebsiteSetting::getSetting(); @endphp
<title>{{ $title ?? 'Masuk' }} — {{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}</title>
@if($setting_global->favicon ?? null)
<link rel="icon" type="image/x-icon" href="{{ Storage::url($setting_global->favicon) }}">
@endif
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css','resources/js/app.js'])
@else
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Plus Jakarta Sans','sans-serif']}}}}</script>
@endif
@stack('styles')
<style>
*, *::before, *::after { box-sizing: border-box; }
html, body {
    margin: 0; padding: 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
    min-height: 100vh;
    overflow-x: hidden;
}

/* ── Auth Background ── */
.auth-bg-wrapper {
    position: fixed;
    inset: 0;
    z-index: 0;
    overflow: hidden;
    pointer-events: none;
}
.auth-bg-image {
    position: absolute;
    inset: -10%;
    width: 120%;
    height: 120%;
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
    pointer-events: none;
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
.auth-icon-float:nth-child(1) { top: 8%;  left: 6%;  font-size: 3.5rem; animation-delay: 0s; }
.auth-icon-float:nth-child(2) { top: 20%; right: 7%; font-size: 2.8rem; animation-delay: -5s; }
.auth-icon-float:nth-child(3) { bottom: 18%; left: 10%; font-size: 2.4rem; animation-delay: -10s; }
.auth-icon-float:nth-child(4) { bottom: 10%; right: 12%; font-size: 3rem;   animation-delay: -3s; }
.auth-icon-float:nth-child(5) { top: 50%; left: 3%;  font-size: 2rem;   animation-delay: -8s; }
@keyframes floatIcon {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-14px); }
}

/* ── Content ── */
.auth-page-content {
    position: relative;
    z-index: 10;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem 1rem;
}

/* Glassmorphism card */
.auth-card {
    background: rgba(255, 255, 255, 0.92);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    border: 1px solid rgba(255, 255, 255, 0.7);
    box-shadow: 0 20px 60px rgba(5, 100, 48, 0.18), 0 4px 16px rgba(0,0,0,0.08);
    border-radius: 1.5rem;
    padding: 2.25rem;
}
.auth-logo-icon {
    width: 3.2rem; height: 3.2rem;
    border-radius: 0.875rem;
    background: linear-gradient(135deg, #16a34a, #059669);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.4);
}
</style>
</head>
<body>

{{-- Background layer --}}
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

{{-- Content --}}
<div class="auth-page-content">
    @yield('content')
</div>

@stack('scripts')
</body>
</html>
