@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('mcu'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Medical Check-Up'],
]])

<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-12 fade-up">
            <span class="section-label">Pilih Paket</span>
            <h2 class="section-title">Paket Medical <span>Check-Up</span></h2>
            <p class="text-gray-500 text-sm mt-2 max-w-lg mx-auto">Semua paket mencakup konsultasi dokter, hasil pemeriksaan lengkap, dan rekomendasi kesehatan personal.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                [
                    'nama'=>'Basic','harga'=>'Rp 450.000','color'=>'green','icon'=>'fa-leaf',
                    'items'=>['Pemeriksaan fisik umum','Darah lengkap','Urin lengkap','Gula darah puasa','Kolesterol total','Konsultasi dokter'],
                    'best'=>false,
                ],
                [
                    'nama'=>'Standard','harga'=>'Rp 850.000','color'=>'blue','icon'=>'fa-star',
                    'items'=>['Semua paket Basic','Fungsi hati (SGOT/SGPT)','Fungsi ginjal','Asam urat','Rontgen thorax','EKG / Rekam jantung'],
                    'best'=>false,
                ],
                [
                    'nama'=>'Executive','harga'=>'Rp 1.750.000','color'=>'purple','icon'=>'fa-crown',
                    'items'=>['Semua paket Standard','USG abdomen','Thyroid (TSH, T3, T4)','Tumor marker','Spirometri','Audiometri','Visus mata'],
                    'best'=>true,
                ],
                [
                    'nama'=>'Corporate','harga'=>'Custom','color'=>'orange','icon'=>'fa-building',
                    'items'=>['Disesuaikan kebutuhan','Kunjungan ke perusahaan','Laporan kolektif','Konsultasi HR','Sertifikat kesehatan','Harga volume'],
                    'best'=>false,
                ],
            ] as $p)
            <div class="card-base overflow-hidden relative {{ $p['best'] ? 'ring-2 ring-purple-500' : '' }} fade-up">
                @if($p['best'])
                <div class="absolute top-0 left-0 right-0 text-center bg-purple-600 text-white text-xs font-bold py-1 tracking-wider">
                    ⭐ TERPOPULER
                </div>
                @endif
                <div class="p-6 {{ $p['best'] ? 'pt-10' : '' }}">
                    <div class="w-12 h-12 rounded-xl bg-{{ $p['color'] }}-100 flex items-center justify-center mb-4">
                        <i class="fas {{ $p['icon'] }} text-{{ $p['color'] }}-600 text-xl"></i>
                    </div>
                    <h3 class="font-extrabold text-gray-900 text-xl mb-1">{{ $p['nama'] }}</h3>
                    <div class="text-2xl font-black text-{{ $p['color'] }}-600 mb-5">{{ $p['harga'] }}</div>
                    <ul class="space-y-2 mb-6">
                        @foreach($p['items'] as $item)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-{{ $p['color'] }}-500 mt-0.5 flex-shrink-0 text-xs"></i>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('mcu.daftar', ['paket' => strtolower($p['nama'])]) }}"
                       class="block w-full text-center py-2.5 rounded-xl font-bold text-sm transition-all
                        {{ $p['best'] ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="rounded-2xl p-8 md:p-12 text-center" style="background: linear-gradient(135deg, #1e3a5f, #0f4c81);">
            <i class="fas fa-clipboard-check text-blue-300 text-4xl mb-4 block"></i>
            <h2 class="text-white font-extrabold text-2xl mb-3">Daftar MCU Sekarang</h2>
            <p class="text-blue-100 text-sm mb-6 max-w-lg mx-auto">Hubungi kami atau datang langsung ke RS Sari Sehat. Tersedia setiap hari Senin–Sabtu, pukul 07.00–14.00.</p>
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('mcu.daftar') }}" class="flex items-center gap-2 bg-white text-blue-800 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-50 transition-all shadow-lg">
                    <i class="fas fa-calendar-check"></i> Daftar MCU
                </a>
                @php
                    $mcuTel  = $setting_global->telepon ?? '+62 895-0189-5170';
                    $mcuWa   = preg_replace('/[^0-9]/', '', $mcuTel);
                    if (str_starts_with($mcuWa, '0')) $mcuWa = '62' . substr($mcuWa, 1);
                    if (empty($mcuWa)) $mcuWa = '6289501895170';
                @endphp
                <a href="https://wa.me/{{ $mcuWa }}" target="_blank" rel="noopener"
                   class="flex items-center gap-2 border-2 border-white/60 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:border-white transition-all">
                    <i class="fab fa-whatsapp"></i> +62 895-0189-5170
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── 1. SCROLL REVEAL ─────────────────────────────────────────────
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity    = '1';
                    entry.target.style.transform  = 'translateY(0) scale(1)';
                }, entry.target.dataset.delay || 0);
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.card-base').forEach((el, i) => {
        el.style.cssText += 'opacity:0;transform:translateY(40px) scale(0.97);transition:all 0.55s cubic-bezier(.25,.8,.25,1);';
        el.dataset.delay  = i * 120;
        revealObserver.observe(el);
    });

    // ── 2. HARGA COUNTER ANIMATION ───────────────────────────────────
    document.querySelectorAll('.price-counter').forEach(el => {
        const target = parseInt(el.dataset.target.replace(/\D/g, '')) || 0;
        if (target === 0) return;
        const duration = 1200;
        const step     = 16;
        let   current  = 0;
        const obs = new IntersectionObserver(entries => {
            if (!entries[0].isIntersecting) return;
            obs.disconnect();
            const timer = setInterval(() => {
                current += Math.ceil(target / (duration / step));
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = 'Rp ' + current.toLocaleString('id-ID');
            }, step);
        }, { threshold: 0.5 });
        obs.observe(el);
    });

    // ── 3. CARD 3D TILT ──────────────────────────────────────────────
    document.querySelectorAll('.card-base').forEach(card => {
        card.addEventListener('mousemove', e => {
            const rect = card.getBoundingClientRect();
            const x    = ((e.clientX - rect.left) / rect.width  - 0.5) * 14;
            const y    = ((e.clientY - rect.top)  / rect.height - 0.5) * -14;
            card.style.transform = `perspective(800px) rotateY(${x}deg) rotateX(${y}deg) translateY(-6px) scale(1.025)`;
            card.style.boxShadow = `${-x * 1.5}px ${y * 1.5 + 20}px 50px rgba(0,0,0,.13)`;
            card.style.transition = 'none';
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform  = 'perspective(800px) rotateY(0) rotateX(0) translateY(0) scale(1)';
            card.style.boxShadow  = '';
            card.style.transition = 'all 0.4s cubic-bezier(.25,.8,.25,1)';
        });
    });

    // ── 4. PAKET HIGHLIGHT PULSE ─────────────────────────────────────
    const popular = document.querySelector('.ring-2.ring-purple-500');
    if (popular) {
        let pulse = 0;
        setInterval(() => {
            pulse = !pulse;
            popular.style.boxShadow = pulse
                ? '0 0 0 4px rgba(168,85,247,.25), 0 20px 60px rgba(168,85,247,.2)'
                : '0 0 0 2px rgba(168,85,247,.4)';
        }, 1800);
    }

    // ── 5. DAFTAR BUTTON RIPPLE ──────────────────────────────────────
    document.querySelectorAll('a[href*="daftar"]').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.addEventListener('click', function(e) {
            const ripple  = document.createElement('span');
            const rect    = this.getBoundingClientRect();
            const size    = Math.max(rect.width, rect.height);
            ripple.style.cssText = `
                position:absolute;width:${size}px;height:${size}px;
                background:rgba(255,255,255,.35);border-radius:50%;
                transform:scale(0);animation:ripple .55s linear;
                left:${e.clientX - rect.left - size/2}px;
                top:${e.clientY - rect.top - size/2}px;
                pointer-events:none;
            `;
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // ── 6. CHECKLIST ITEMS STAGGER ───────────────────────────────────
    const checkObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.querySelectorAll('li').forEach((li, i) => {
                li.style.cssText = `opacity:0;transform:translateX(-16px);transition:all .35s ease ${i * 60}ms`;
                requestAnimationFrame(() => {
                    li.style.opacity   = '1';
                    li.style.transform = 'translateX(0)';
                });
            });
            checkObserver.unobserve(entry.target);
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('ul.space-y-2').forEach(ul => {
        checkObserver.observe(ul);
    });

    // ── 7. SECTION FADE IN ───────────────────────────────────────────
    const sectionObs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.style.opacity   = '1';
            entry.target.style.transform = 'translateY(0)';
            sectionObs.unobserve(entry.target);
        });
    }, { threshold: 0.05 });

    document.querySelectorAll('section').forEach(s => {
        s.style.cssText += 'opacity:0;transform:translateY(24px);transition:opacity .6s ease,transform .6s ease;';
        sectionObs.observe(s);
    });

});
</script>
<style>
@keyframes ripple { to { transform: scale(4); opacity: 0; } }
</style>
@endpush
