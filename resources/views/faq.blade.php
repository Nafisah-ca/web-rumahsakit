@extends('layouts.app')
@php $title = 'FAQ'; $metaDesc = 'Pertanyaan yang sering ditanyakan seputar layanan ' . ($setting_global->nama_rumahsakit ?? 'RS Sari Sehat'); @endphp

@push('styles')
<style>
.faq-card {
    background: #fff;
    border-radius: 16px;
    border: 1.5px solid #e8f5e9;
    box-shadow: 0 2px 12px rgba(22,163,74,.06);
    overflow: hidden;
    transition: box-shadow .2s, border-color .2s;
}
.faq-card.open {
    border-color: #16a34a;
    box-shadow: 0 4px 24px rgba(22,163,74,.12);
}
.faq-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    background: none;
    border: none;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    transition: background .15s;
}
.faq-btn:hover { background: #f0fdf4; }
.faq-icon-wrap {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, #15803d, #22c55e);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(22,163,74,.25);
}
.faq-q {
    flex: 1;
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.4;
}
.faq-chevron {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: #f0fdf4;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #16a34a;
    font-size: 12px;
    transition: transform .3s, background .2s;
}
.faq-card.open .faq-chevron {
    transform: rotate(180deg);
    background: #dcfce7;
}
.faq-body {
    display: none;
    border-top: 1.5px solid #f0fdf4;
    padding: 0 24px 22px 24px;
}
.faq-body-inner {
    padding-top: 18px;
    padding-left: 60px;
    font-size: 14px;
    color: #475569;
    line-height: 1.75;
}
.faq-card.open .faq-body { display: block; }

/* Hero decoration dots */
.hero-dot {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
}
</style>
@endpush

@section('content')

{{-- ===== HERO BANNER ===== --}}
<div style="background: linear-gradient(135deg, #00521f 0%, #00893a 60%, #00b04f 100%); position:relative; overflow:hidden;">
    {{-- Decoration --}}
    <span class="hero-dot" style="width:300px;height:300px;top:-100px;right:-80px"></span>
    <span class="hero-dot" style="width:180px;height:180px;bottom:-60px;left:5%"></span>
    <span class="hero-dot" style="width:80px;height:80px;top:30px;left:40%"></span>

    <div class="max-w-screen-xl mx-auto px-4 py-20 text-center" style="position:relative;z-index:1">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4"
             style="background:rgba(255,255,255,.12);backdrop-filter:blur(4px)">
            <i class="fas fa-circle-question text-green-300 text-xs"></i>
            <span class="text-green-200 text-xs font-bold uppercase tracking-widest">Pusat Bantuan</span>
        </div>
        <h1 class="text-white font-extrabold mb-3" style="font-size:clamp(28px,5vw,44px);line-height:1.15">
            Pertanyaan yang Sering<br>
            <span style="color:#86efac">Ditanyakan (FAQ)</span>
        </h1>
        <p class="text-green-100 max-w-xl mx-auto mb-6" style="font-size:14px;line-height:1.7">
            Temukan jawaban atas pertanyaan seputar layanan, pendaftaran, dan fasilitas
            {{ $setting_global->nama_rumahsakit ?? 'RS Sari Sehat' }}.
        </p>
        <nav class="flex items-center justify-center gap-2 text-sm text-green-300">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <i class="fas fa-chevron-right text-xs opacity-60"></i>
            <span class="text-white font-semibold">FAQ</span>
        </nav>
    </div>
</div>

{{-- ===== FAQ SECTION ===== --}}
<section style="background:#f8fafc;padding:64px 0 80px">
    <div class="max-w-3xl mx-auto px-4">

        @if($faqs->isEmpty())
        {{-- Empty state --}}
        <div style="text-align:center;padding:80px 20px">
            <div style="width:80px;height:80px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
                <i class="fas fa-circle-question" style="font-size:32px;color:#16a34a"></i>
            </div>
            <h3 style="color:#1e293b;font-size:18px;font-weight:700;margin-bottom:8px">Belum Ada FAQ</h3>
            <p style="color:#94a3b8;font-size:14px">Pertanyaan umum akan segera ditampilkan di sini.</p>
        </div>

        @else

        {{-- Section heading --}}
        <div style="text-align:center;margin-bottom:40px">
            <p style="font-size:11px;font-weight:800;color:#16a34a;letter-spacing:.12em;text-transform:uppercase;margin-bottom:8px">
                Temukan Jawaban Anda
            </p>
            <h2 style="font-size:26px;font-weight:800;color:#0f172a;line-height:1.3">
                Pertanyaan Umum
            </h2>
            <div style="width:48px;height:4px;background:linear-gradient(90deg,#16a34a,#22c55e);border-radius:4px;margin:14px auto 0"></div>
        </div>

        {{-- Accordion list --}}
        <div style="display:flex;flex-direction:column;gap:12px">
            @foreach($faqs as $item)
            <div class="faq-card {{ $loop->first ? 'open' : '' }}" id="faq-card-{{ $loop->index }}">

                <button class="faq-btn"
                        type="button"
                        onclick="toggleFaq({{ $loop->index }})"
                        aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                        id="faq-btn-{{ $loop->index }}">

                    <div class="faq-icon-wrap">
                        <i class="fas fa-circle-question text-white" style="font-size:18px"></i>
                    </div>

                    <span class="faq-q">{{ $item->pertanyaan }}</span>

                    <div class="faq-chevron" id="faq-chevron-{{ $loop->index }}">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </button>

                <div class="faq-body" id="faq-body-{{ $loop->index }}"
                     style="{{ $loop->first ? 'display:block' : '' }}">
                    <div class="faq-body-inner">
                        {!! nl2br(e($item->jawaban)) !!}
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        <div style="text-align:center;margin-top:48px;padding:32px 24px;background:#fff;border-radius:20px;border:1.5px solid #e8f5e9;box-shadow:0 2px 12px rgba(22,163,74,.06)">
            <div style="width:48px;height:48px;border-radius:14px;background:linear-gradient(135deg,#15803d,#22c55e);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;box-shadow:0 4px 14px rgba(22,163,74,.25)">
                <i class="fas fa-headset text-white" style="font-size:20px"></i>
            </div>
            <h4 style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:6px">
                Tidak menemukan jawaban yang dicari?
            </h4>
            <p style="font-size:13px;color:#64748b;margin-bottom:18px;line-height:1.6">
                Tim kami siap membantu Anda. Hubungi kami melalui form kontak atau WhatsApp.
            </p>
            <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
                <a href="{{ route('kontak') }}"
                   style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#15803d,#16a34a);color:#fff;font-weight:700;font-size:13px;padding:11px 22px;border-radius:12px;text-decoration:none;box-shadow:0 4px 14px rgba(22,163,74,.3);transition:opacity .15s"
                   onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                    <i class="fas fa-envelope"></i> Hubungi Kami
                </a>
                @php
                    $waNum = preg_replace('/[^0-9]/', '', $setting_global->telepon ?? '');
                    if (str_starts_with($waNum, '0')) $waNum = '62' . substr($waNum, 1);
                    if (empty($waNum)) $waNum = '6289501895170';
                @endphp
                <a href="https://wa.me/{{ $waNum }}" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:8px;background:#25d366;color:#fff;font-weight:700;font-size:13px;padding:11px 22px;border-radius:12px;text-decoration:none;box-shadow:0 4px 14px rgba(37,211,102,.3);transition:opacity .15s"
                   onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
            </div>
        </div>

        @endif
    </div>
</section>

@push('scripts')
<script>
function toggleFaq(idx) {
    const card    = document.getElementById('faq-card-'    + idx);
    const body    = document.getElementById('faq-body-'    + idx);
    const btn     = document.getElementById('faq-btn-'     + idx);
    if (!card) return;

    const isOpen = card.classList.contains('open');

    // Close all
    document.querySelectorAll('[id^="faq-card-"]').forEach(function(c) { c.classList.remove('open'); });
    document.querySelectorAll('[id^="faq-body-"]').forEach(function(b) { b.style.display = 'none'; });
    document.querySelectorAll('[id^="faq-btn-"]').forEach(function(b)  { b.setAttribute('aria-expanded', 'false'); });

    // Open clicked one if it was closed
    if (!isOpen) {
        card.classList.add('open');
        body.style.display = 'block';
        btn.setAttribute('aria-expanded', 'true');
    }
}
</script>
@endpush

@endsection
