@extends('layouts.app')

@push('styles')
<style>
/* ── TYPOGRAPHY PAIRING ─────────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@600;700&display=swap');

/* ── PAGE BASE ──────────────────────────────────────────── */
.portal-bg {
    background: #f7f9f7;
    min-height: 100vh;
}

/* ── PROFIL CARD ────────────────────────────────────────── */
.profil-card {
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e4ede7;
    box-shadow: 0 2px 16px rgba(0,82,31,.06);
    padding: 0;
    overflow: hidden;
    position: relative;
}
.profil-card-stripe {
    height: 6px;
    background: linear-gradient(90deg, #00521f 0%, #00b04f 60%, #7ee8a2 100%);
}
.profil-card-body {
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
}
.profil-avatar {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: #00521f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 900;
    color: #fff;
    flex-shrink: 0;
    letter-spacing: -1px;
    font-family: 'Lora', serif;
    box-shadow: 0 4px 12px rgba(0,82,31,.25);
}
.profil-nama {
    font-size: 16px;
    font-weight: 800;
    color: #111;
    line-height: 1.2;
    font-family: 'Lora', serif;
}
.profil-rm {
    font-size: 11px;
    color: #6b7280;
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.profil-rm .rm-code {
    font-family: 'Courier New', monospace;
    font-weight: 700;
    color: #00521f;
    background: #e8f5ec;
    padding: 1px 7px;
    border-radius: 5px;
    font-size: 11px;
    letter-spacing: .03em;
}
.profil-rm .rm-jk {
    background: #f3f4f6;
    color: #374151;
    padding: 1px 8px;
    border-radius: 5px;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .05em;
}
.edit-profil-btn {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 700;
    color: #00521f;
    background: #e8f5ec;
    border: 1.5px solid #b2d8bf;
    border-radius: 10px;
    padding: 7px 14px;
    text-decoration: none;
    transition: background .15s, border-color .15s, transform .12s;
    white-space: nowrap;
    flex-shrink: 0;
}
.edit-profil-btn:hover {
    background: #d0edda;
    border-color: #00b04f;
    transform: translateY(-1px);
}

/* ── BOOKING CARD ───────────────────────────────────────── */
.booking-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid #e8eee9;
    box-shadow: 0 1px 8px rgba(0,0,0,.05);
    overflow: hidden;
    transition: box-shadow .2s ease, transform .2s ease;
    margin-bottom: 14px;
}
.booking-card:hover {
    box-shadow: 0 6px 24px rgba(0,82,31,.1);
    transform: translateY(-2px);
}
.booking-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 18px 11px;
    border-bottom: 1px solid #f0f4f1;
    background: #fcfffe;
    gap: 10px;
    flex-wrap: wrap;
}
.booking-kode {
    font-family: 'Courier New', monospace;
    font-size: 11px;
    font-weight: 700;
    color: #4b5563;
    background: #f3f4f6;
    padding: 3px 10px;
    border-radius: 7px;
    letter-spacing: .03em;
}
.booking-card-body {
    padding: 16px 18px 14px;
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    gap: 12px 16px;
    align-items: start;
}
@media (max-width: 520px) {
    .booking-card-body { grid-template-columns: 1fr 1fr; }
    .booking-card-antrian { grid-column: span 2; display: flex; align-items: center; gap: 10px; }
}
.booking-field-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #9ca3af;
    margin-bottom: 3px;
}
.booking-field-val {
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    line-height: 1.3;
}
.booking-field-sub {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 1px;
}
.antrian-num {
    font-size: 36px;
    font-weight: 900;
    color: #00521f;
    line-height: 1;
    font-family: 'Lora', serif;
    letter-spacing: -1px;
}
.booking-keluhan {
    margin: 0 18px 14px;
    padding: 10px 14px;
    background: #f9fafb;
    border-left: 3px solid #d1fae5;
    border-radius: 0 10px 10px 0;
    font-size: 12px;
    color: #374151;
    line-height: 1.5;
}
.booking-keluhan .klh-label {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .07em;
    color: #6b7280;
    margin-bottom: 2px;
}

/* ── STATUS BADGE ───────────────────────────────────────── */
.sbadge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 800;
    padding: 4px 11px;
    border-radius: 999px;
    letter-spacing: .03em;
    white-space: nowrap;
}
.sbadge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
}
.sbadge-pending  { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.sbadge-pending::before  { background: #f59e0b; }
.sbadge-approved { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
.sbadge-approved::before { background: #3b82f6; }
.sbadge-completed{ background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.sbadge-completed::before{ background: #22c55e; }
.sbadge-cancelled{ background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.sbadge-cancelled::before{ background: #ef4444; }

/* ── BATALKAN BTN ───────────────────────────────────────── */
.btn-batal {
    font-size: 11px;
    font-weight: 700;
    color: #dc2626;
    background: #fff;
    border: 1.5px solid #fca5a5;
    padding: 5px 13px;
    border-radius: 9px;
    cursor: pointer;
    transition: background .15s, border-color .15s;
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    font-family: inherit;
}
.btn-batal:hover { background: #fef2f2; border-color: #ef4444; }

/* ── EMPTY STATE ────────────────────────────────────────── */
.empty-card {
    background: #fff;
    border-radius: 20px;
    border: 1.5px dashed #d1fae5;
    padding: 60px 24px;
    text-align: center;
}
.empty-icon-wrap {
    width: 72px;
    height: 72px;
    background: #f0fdf4;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 16px;
}
.empty-icon-wrap i {
    font-size: 28px;
    color: #bbf7d0;
}
.empty-copy   { font-size: 15px; font-weight: 800; color: #374151; font-family: 'Lora', serif; }
.empty-sub    { font-size: 13px; color: #9ca3af; margin-top: 5px; }

/* ── TOAST ──────────────────────────────────────────────── */
#rs-toast {
    position: fixed;
    bottom: 88px;
    right: 20px;
    z-index: 9999;
    background: #1a2e22;
    color: #fff;
    padding: 13px 18px;
    border-radius: 14px;
    font-size: 13px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 8px 28px rgba(0,0,0,.2);
    transform: translateY(16px);
    opacity: 0;
    transition: opacity .3s ease, transform .3s ease;
    pointer-events: none;
    max-width: 300px;
}
#rs-toast.show {
    opacity: 1;
    transform: translateY(0);
}
#rs-toast .toast-icon {
    width: 28px;
    height: 28px;
    background: #00b04f;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 13px;
}

/* ── PAGE HEADER ────────────────────────────────────────── */
.page-header-wrap {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 22px;
    gap: 12px;
    flex-wrap: wrap;
}
.page-title {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    font-family: 'Lora', serif;
    letter-spacing: -.3px;
}
.page-sub {
    font-size: 13px;
    color: #9ca3af;
    margin-top: 3px;
}
.btn-buat-janji {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #00521f;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    padding: 10px 20px;
    border-radius: 12px;
    text-decoration: none;
    transition: background .15s, transform .12s;
    box-shadow: 0 3px 12px rgba(0,82,31,.25);
    white-space: nowrap;
}
.btn-buat-janji:hover {
    background: #003d17;
    transform: translateY(-1px);
}
</style>
@endpush

@section('content')
<div class="portal-bg py-10 px-4">
<div style="max-width:780px;margin:0 auto">

    {{-- Page header --}}
    <div class="page-header-wrap">
        <div>
            <h1 class="page-title">Janji Temu Saya</h1>
            <p class="page-sub">Pantau status booking dan riwayat kunjungan Anda</p>
        </div>
        <a href="{{ route('portal.booking.create') }}" class="btn-buat-janji">
            <i class="fas fa-plus text-xs"></i> Buat Janji Baru
        </a>
    </div>

    {{-- Toast trigger dari session (dihandle JS) --}}
    @if(session('success'))
    <span id="session-success" data-msg="{{ session('success') }}" style="display:none"></span>
    @endif

    {{-- Profil Pasien --}}
    @if($pasien)
    <div class="profil-card" style="margin-bottom:20px">
        <div class="profil-card-stripe"></div>
        <div class="profil-card-body">
            @php $fotoUser = Auth::user()->foto; @endphp
            @if($fotoUser)
            <img src="{{ Storage::url($fotoUser) }}"
                 alt="{{ Auth::user()->nama }}"
                 style="width:56px;height:56px;border-radius:14px;object-fit:cover;flex-shrink:0;border:2px solid #e4ede7">
            @else
            <div class="profil-avatar">
                {{ strtoupper(substr($pasien->user?->nama ?? $pasien->nama_lengkap ?? '?', 0, 1)) }}
            </div>
            @endif

            <div style="flex:1;min-width:0">
                <p class="profil-nama">{{ $pasien->user?->nama ?? $pasien->nama_lengkap }}</p>
                <div class="profil-rm">
                    <span class="rm-code">{{ $pasien->no_rekam_medis ?? '-' }}</span>
                    <span class="rm-jk">{{ $pasien->jenis_kelamin_label }}</span>
                </div>
            </div>

            <a href="{{ route('portal.profil') }}" class="edit-profil-btn">
                <i class="fas fa-pen-to-square" style="font-size:11px"></i> Edit Profil
            </a>
        </div>
    </div>
    @endif

    {{-- Booking list --}}
    @forelse($bookings as $b)
    @php
        $sMap = [
            'pending'   => ['Menunggu',     'sbadge-pending'],
            'approved'  => ['Dikonfirmasi', 'sbadge-approved'],
            'completed' => ['Selesai',      'sbadge-completed'],
            'cancelled' => ['Dibatalkan',   'sbadge-cancelled'],
        ];
        [$sLabel, $sClass] = $sMap[$b->status] ?? [$b->status, 'sbadge-pending'];
    @endphp
    <div class="booking-card">
        {{-- Header --}}
        <div class="booking-card-header">
            <span class="booking-kode">{{ $b->kode_booking }}</span>
            <span class="sbadge {{ $sClass }}">{{ $sLabel }}</span>

            @if(in_array($b->status, ['pending','approved']))
            <form method="POST" action="{{ route('portal.booking.cancel', $b) }}"
                  class="ms-auto"
                  onsubmit="return confirmBatal(event, this)">
                @csrf
                <button class="btn-batal" type="submit">
                    <i class="fas fa-xmark" style="font-size:10px"></i> Batalkan
                </button>
            </form>
            @endif
        </div>

        {{-- Body --}}
        <div class="booking-card-body">
            {{-- Dokter --}}
            <div>
                <p class="booking-field-label">Dokter</p>
                <p class="booking-field-val">{{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</p>
                <p class="booking-field-sub">{{ $b->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis ?? '-' }}</p>
            </div>

            {{-- Tanggal --}}
            <div>
                <p class="booking-field-label">Tanggal</p>
                <p class="booking-field-val">{{ $b->tanggal_booking?->format('d M Y') }}</p>
                <p class="booking-field-sub">{{ $b->jadwalDokter ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : '' }}</p>
            </div>

            {{-- Nomor Antrian --}}
            <div class="booking-card-antrian" style="text-align:center">
                <p class="booking-field-label" style="text-align:left">No. Antrian</p>
                <p class="antrian-num">{{ $b->nomor_antrian ?? '-' }}</p>
            </div>
        </div>

        {{-- Keluhan --}}
        @if($b->keluhan)
        <div class="booking-keluhan">
            <p class="klh-label">Keluhan</p>
            {{ $b->keluhan }}
        </div>
        @endif
    </div>
    @empty
    <div class="empty-card">
        <div class="empty-icon-wrap">
            <i class="fas fa-calendar-xmark"></i>
        </div>
        <p class="empty-copy">Belum ada janji temu</p>
        <p class="empty-sub">Yuk buat janji temu pertama kamu — mudah dan cepat.</p>
        <a href="{{ route('portal.booking.create') }}"
           style="display:inline-flex;align-items:center;gap:8px;margin-top:18px;background:#00521f;color:#fff;padding:11px 24px;border-radius:12px;font-size:13px;font-weight:700;text-decoration:none;transition:background .15s"
           onmouseover="this.style.background='#003d17'"
           onmouseout="this.style.background='#00521f'">
            <i class="fas fa-plus text-xs"></i> Buat Janji Temu
        </a>
    </div>
    @endforelse

    {{-- Pagination --}}
    @if($bookings->hasPages())
    <div style="margin-top:16px">{{ $bookings->links() }}</div>
    @endif

</div>
</div>

{{-- Toast element --}}
<div id="rs-toast">
    <div class="toast-icon"><i class="fas fa-check"></i></div>
    <span id="rs-toast-msg"></span>
</div>
@endsection

@push('scripts')
<script>
/**
 * Tampilkan toast notification kustom
 * @param {string} msg  - Pesan yang ditampilkan
 * @param {number} dur  - Durasi tampil dalam milidetik (default 3500)
 */
function showToast(msg, dur = 3500) {
    const toast = document.getElementById('rs-toast');
    const label = document.getElementById('rs-toast-msg');
    if (!toast || !label) return;

    label.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), dur);
}

/**
 * Konfirmasi pembatalan booking — ganti browser confirm() dengan dialog
 * yang lebih humanized. Saat ini masih pakai confirm() sederhana agar
 * kompatibel, tapi dengan teks yang lebih hangat.
 */
function confirmBatal(e, form) {
    e.preventDefault();
    // Teks lebih humanized daripada "confirm 'Batalkan janji temu ini?'"
    const ok = window.confirm(
        'Batalkan janji temu ini?\n\nJika kamu perlu menggeser jadwal, bisa buat janji baru setelahnya.'
    );
    if (ok) form.submit();
}

// Tampilkan toast jika ada session success (disuntikkan dari Blade)
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('session-success');
    if (el) showToast(el.dataset.msg || 'Berhasil!');

    // Micro-interaction: booking card entry animation
    const cards = document.querySelectorAll('.booking-card');
    cards.forEach(function (card, i) {
        card.style.opacity = '0';
        card.style.transform = 'translateY(12px)';
        card.style.transition = 'opacity .4s ease, transform .4s ease';
        setTimeout(function () {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 80 + i * 70);
    });
});
</script>
@endpush
