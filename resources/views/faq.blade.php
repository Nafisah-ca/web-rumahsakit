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
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('faq'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'FAQ'],
]])

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
            <div style="width:48px;height:4px;background:linear-gradient(90deg,#16a34a,#22c55e);border-radius:4px;margin:14px auto 18px"></div>

            {{-- Search Box --}}
            <div style="max-width:420px;margin:0 auto 16px;position:relative">
                <i class="fas fa-search" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:13px;pointer-events:none"></i>
                <input id="faq-search" type="text" placeholder="Cari pertanyaan… (Ctrl+K)"
                       style="width:100%;padding:12px 16px 12px 42px;border-radius:14px;border:1.5px solid #e2e8f0;font-size:13px;font-family:inherit;color:#0f172a;background:#fff;transition:border-color .2s,box-shadow .2s;outline:none">
            </div>

            {{-- Expand All --}}
            <button id="faq-toggle-all"
                    style="display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;border:1.5px solid #bbf7d0;color:#16a34a;font-size:12px;font-weight:700;padding:7px 16px;border-radius:10px;cursor:pointer;font-family:inherit;transition:all .2s"
                    onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                ⊕ Buka Semua
            </button>
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
// ── CONFIG ───────────────────────────────────────────────────────────
const DURATION = 380; // ms animasi buka/tutup

// ── SMOOTH ACCORDION ─────────────────────────────────────────────────
function toggleFaq(idx) {
    const card = document.getElementById('faq-card-' + idx);
    const body = document.getElementById('faq-body-' + idx);
    const btn  = document.getElementById('faq-btn-'  + idx);
    if (!card) return;

    const isOpen = card.classList.contains('open');

    // Tutup semua dengan animasi slide-up
    document.querySelectorAll('[id^="faq-card-"]').forEach(c => {
        if (c === card) return;
        slideUp(c);
    });

    // Toggle yang diklik
    if (isOpen) {
        slideUp(card);
    } else {
        slideDown(card);
        // Scroll ke item yang dibuka (kalau di luar viewport)
        setTimeout(() => {
            const rect = card.getBoundingClientRect();
            if (rect.top < 80 || rect.top > window.innerHeight * 0.75) {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }, 100);
    }
}

function slideDown(card) {
    const body = card.querySelector('[id^="faq-body-"]');
    const btn  = card.querySelector('[id^="faq-btn-"]');

    // Ukur tinggi asli
    body.style.display    = 'block';
    body.style.overflow   = 'hidden';
    body.style.maxHeight  = '0';
    body.style.opacity    = '0';
    body.style.transition = `max-height ${DURATION}ms cubic-bezier(.4,0,.2,1), opacity ${DURATION * .7}ms ease`;

    requestAnimationFrame(() => {
        const h = body.scrollHeight;
        body.style.maxHeight = h + 'px';
        body.style.opacity   = '1';
    });

    card.classList.add('open');
    btn?.setAttribute('aria-expanded', 'true');

    // Bersihkan inline style setelah animasi selesai
    setTimeout(() => {
        body.style.maxHeight  = 'none';
        body.style.overflow   = '';
        body.style.transition = '';
    }, DURATION + 20);
}

function slideUp(card) {
    const body = card.querySelector('[id^="faq-body-"]');
    const btn  = card.querySelector('[id^="faq-btn-"]');
    if (!card.classList.contains('open')) return;

    body.style.overflow   = 'hidden';
    body.style.maxHeight  = body.scrollHeight + 'px';
    body.style.transition = `max-height ${DURATION}ms cubic-bezier(.4,0,.2,1), opacity ${DURATION * .6}ms ease`;

    requestAnimationFrame(() => {
        body.style.maxHeight = '0';
        body.style.opacity   = '0';
    });

    card.classList.remove('open');
    btn?.setAttribute('aria-expanded', 'false');

    setTimeout(() => {
        body.style.display    = 'none';
        body.style.maxHeight  = '';
        body.style.overflow   = '';
        body.style.opacity    = '';
        body.style.transition = '';
    }, DURATION + 20);
}

// ── INIT ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

    // 1. SCROLL REVEAL — card masuk dari bawah bertahap
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const card = entry.target;
            const i    = parseInt(card.dataset.faqIndex || 0);
            setTimeout(() => {
                card.style.opacity   = '1';
                card.style.transform = 'translateY(0)';
            }, i * 80);
            revealObs.unobserve(card);
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('[id^="faq-card-"]').forEach((card, i) => {
        if (card.classList.contains('open')) {
            // Card pertama langsung tampil
            card.style.opacity   = '1';
            card.style.transform = 'translateY(0)';
        } else {
            card.style.cssText += 'opacity:0;transform:translateY(28px);transition:opacity .45s ease,transform .45s ease,box-shadow .2s,border-color .2s;';
        }
        card.dataset.faqIndex = i;
        revealObs.observe(card);
    });

    // 2. HEADING REVEAL — judul seksi animate masuk
    const heading = document.querySelector('[style*="Temukan Jawaban"]')?.closest('div');
    if (heading) {
        heading.style.cssText += 'opacity:0;transform:translateY(-16px);transition:opacity .5s ease,transform .5s ease;';
        const headObs = new IntersectionObserver(entries => {
            if (entries[0].isIntersecting) {
                heading.style.opacity   = '1';
                heading.style.transform = 'translateY(0)';
                headObs.disconnect();
            }
        });
        headObs.observe(heading);
    }

    // 3. SEARCH / FILTER FAQ ──────────────────────────────────────────
    const searchBox = document.getElementById('faq-search');
    if (searchBox) {
        searchBox.addEventListener('input', () => {
            const q = searchBox.value.trim().toLowerCase();
            let anyVisible = false;

            document.querySelectorAll('[id^="faq-card-"]').forEach(card => {
                const text = card.textContent.toLowerCase();
                const show = !q || text.includes(q);
                card.style.display = show ? '' : 'none';
                if (show) anyVisible = true;

                // Highlight teks yang cocok
                if (q && show) {
                    const qEl = card.querySelector('.faq-q');
                    if (qEl) {
                        const orig = qEl.dataset.orig || qEl.textContent;
                        qEl.dataset.orig = orig;
                        const re = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        qEl.innerHTML = orig.replace(re, '<mark style="background:#fef9c3;border-radius:3px;padding:0 2px">$1</mark>');
                    }
                } else {
                    const qEl = card.querySelector('.faq-q');
                    if (qEl && qEl.dataset.orig) {
                        qEl.textContent = qEl.dataset.orig;
                        delete qEl.dataset.orig;
                    }
                }
            });

            // Tampilkan pesan jika tidak ada hasil
            let noResult = document.getElementById('faq-no-result');
            if (!anyVisible && q) {
                if (!noResult) {
                    noResult = document.createElement('div');
                    noResult.id = 'faq-no-result';
                    noResult.style.cssText = 'text-align:center;padding:40px 20px;color:#94a3b8;font-size:14px;';
                    noResult.innerHTML = '<i class="fas fa-search" style="font-size:28px;display:block;margin-bottom:10px;opacity:.35"></i>Tidak ada FAQ yang cocok dengan "<strong style="color:#475569">' + searchBox.value + '</strong>"';
                    searchBox.closest('div').after(noResult);
                } else {
                    noResult.style.display = '';
                    noResult.querySelector('strong').textContent = searchBox.value;
                }
            } else if (noResult) {
                noResult.style.display = 'none';
            }
        });

        // Shortcut Ctrl+K / Cmd+K fokus ke search
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                searchBox.focus();
                searchBox.select();
            }
        });
    }

    // 4. KEYBOARD NAVIGATION (Up/Down arrow di accordion) ─────────────
    document.querySelectorAll('[id^="faq-btn-"]').forEach((btn, i, all) => {
        btn.addEventListener('keydown', e => {
            if (e.key === 'ArrowDown') { e.preventDefault(); all[i + 1]?.focus(); }
            if (e.key === 'ArrowUp')   { e.preventDefault(); all[i - 1]?.focus(); }
            if (e.key === 'Home')      { e.preventDefault(); all[0]?.focus(); }
            if (e.key === 'End')       { e.preventDefault(); all[all.length - 1]?.focus(); }
        });
    });

    // 5. CTA CARD LIFT ON HOVER ───────────────────────────────────────
    const ctaCard = document.querySelector('[style*="text-align:center;margin-top:48px"]');
    if (ctaCard) {
        ctaCard.style.transition = 'transform .3s ease, box-shadow .3s ease';
        ctaCard.addEventListener('mouseenter', () => {
            ctaCard.style.transform  = 'translateY(-4px)';
            ctaCard.style.boxShadow  = '0 12px 36px rgba(22,163,74,.14)';
        });
        ctaCard.addEventListener('mouseleave', () => {
            ctaCard.style.transform  = '';
            ctaCard.style.boxShadow  = '';
        });
    }

    // 6. ICON BOUNCE saat FAQ dibuka ──────────────────────────────────
    document.querySelectorAll('.faq-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const icon = btn.querySelector('.faq-icon-wrap i');
            if (!icon) return;
            icon.style.transform  = 'scale(1.4) rotate(-10deg)';
            icon.style.transition = 'transform .2s cubic-bezier(.34,1.56,.64,1)';
            setTimeout(() => {
                icon.style.transform = 'scale(1) rotate(0deg)';
            }, 220);
        });
    });

    // 7. RIPPLE EFFECT pada tombol FAQ ────────────────────────────────
    document.querySelectorAll('.faq-btn').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect   = this.getBoundingClientRect();
            const size   = Math.max(rect.width, rect.height);
            ripple.style.cssText = `
                position:absolute;pointer-events:none;
                width:${size}px;height:${size}px;
                background:rgba(22,163,74,.08);border-radius:50%;
                transform:scale(0);animation:faq-ripple .5s linear;
                left:${e.clientX - rect.left - size / 2}px;
                top:${e.clientY - rect.top - size / 2}px;
            `;
            this.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // 8. NOMOR FAQ BADGE ──────────────────────────────────────────────
    document.querySelectorAll('[id^="faq-card-"]').forEach((card, i) => {
        const iconWrap = card.querySelector('.faq-icon-wrap');
        if (iconWrap) {
            const badge = document.createElement('span');
            badge.style.cssText = `
                position:absolute;top:-6px;right:-6px;
                width:18px;height:18px;background:#0f172a;color:#fff;
                border-radius:50%;font-size:9px;font-weight:800;
                display:flex;align-items:center;justify-content:center;
                border:2px solid #fff;
            `;
            badge.textContent = i + 1;
            iconWrap.style.position = 'relative';
            iconWrap.appendChild(badge);
        }
    });

    // 9. EXPAND ALL / COLLAPSE ALL ────────────────────────────────────
    const toggleAllBtn = document.getElementById('faq-toggle-all');
    if (toggleAllBtn) {
        let allOpen = false;
        toggleAllBtn.addEventListener('click', () => {
            allOpen = !allOpen;
            document.querySelectorAll('[id^="faq-card-"]').forEach((card, i) => {
                if (allOpen) slideDown(card);
                else slideUp(card);
            });
            toggleAllBtn.textContent = allOpen ? '⊖ Tutup Semua' : '⊕ Buka Semua';
        });
    }

});
</script>
<style>
@keyframes faq-ripple { to { transform: scale(4); opacity: 0; } }

/* Smooth focus ring */
.faq-btn:focus-visible {
    outline: 2px solid #16a34a;
    outline-offset: 2px;
    border-radius: 12px;
}

/* Search box */
#faq-search:focus {
    outline: none;
    border-color: #16a34a;
    box-shadow: 0 0 0 3px rgba(22,163,74,.15);
}

/* Hover efek lebih halus */
.faq-card:not(.open):hover {
    border-color: #bbf7d0;
    box-shadow: 0 4px 20px rgba(22,163,74,.09);
    transform: translateX(3px);
}
</style>
@endpush

@endsection
