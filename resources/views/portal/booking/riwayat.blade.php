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
    border: 1.5px solid #e8eee9;
    box-shadow: 0 1px 8px rgba(0,0,0,.05);
    overflow: hidden;
    transition: box-shadow .2s ease, transform .2s ease;
    margin-bottom: 14px;
}
.booking-card:hover {
    box-shadow: 0 6px 24px rgba(0,82,31,.1);
    transform: translateY(-2px);
}

/* ── STATUS WARNA FULL CARD ─────────────────────────────── */

/* MENUNGGU = KUNING BOLD */
.booking-card.status-pending {
    border: 2.5px solid #f59e0b;
    background: #fff;
    box-shadow: 0 2px 16px rgba(245,158,11,.18);
}
.booking-card.status-pending .booking-card-header {
    background: #f59e0b;
    border-bottom: none;
}
.booking-card.status-pending .booking-kode {
    background: rgba(255,255,255,.25);
    color: #fff;
    border: 1px solid rgba(255,255,255,.4);
    font-weight: 800;
}
.booking-card.status-pending .sbadge {
    background: rgba(255,255,255,.2);
    color: #fff;
    border-color: rgba(255,255,255,.4);
}
.booking-card.status-pending .sbadge::before { background: #fff; }
.booking-card.status-pending .antrian-num {
    color: #f59e0b;
    font-size: 42px;
}
.booking-card.status-pending .booking-field-label { color: #d97706; }

/* DIKONFIRMASI = BIRU BOLD */
.booking-card.status-approved {
    border: 2.5px solid #3b82f6;
    background: #fff;
    box-shadow: 0 2px 16px rgba(59,130,246,.18);
}
.booking-card.status-approved .booking-card-header {
    background: #3b82f6;
    border-bottom: none;
}
.booking-card.status-approved .booking-kode {
    background: rgba(255,255,255,.25);
    color: #fff;
    border: 1px solid rgba(255,255,255,.4);
    font-weight: 800;
}
.booking-card.status-approved .sbadge {
    background: rgba(255,255,255,.2);
    color: #fff;
    border-color: rgba(255,255,255,.4);
}
.booking-card.status-approved .sbadge::before { background: #fff; }
.booking-card.status-approved .antrian-num {
    color: #3b82f6;
    font-size: 42px;
}
.booking-card.status-approved .booking-field-label { color: #2563eb; }

/* SELESAI = HIJAU BOLD */
.booking-card.status-completed {
    border: 2.5px solid #16a34a;
    background: #fff;
    box-shadow: 0 2px 16px rgba(22,163,74,.18);
}
.booking-card.status-completed .booking-card-header {
    background: #16a34a;
    border-bottom: none;
}
.booking-card.status-completed .booking-kode {
    background: rgba(255,255,255,.25);
    color: #fff;
    border: 1px solid rgba(255,255,255,.4);
    font-weight: 800;
}
.booking-card.status-completed .sbadge {
    background: rgba(255,255,255,.2);
    color: #fff;
    border-color: rgba(255,255,255,.4);
}
.booking-card.status-completed .sbadge::before { background: #fff; }
.booking-card.status-completed .antrian-num {
    color: #16a34a;
    font-size: 42px;
}
.booking-card.status-completed .booking-field-label { color: #15803d; }
.booking-card.status-completed .booking-keluhan {
    background: #f0fdf4;
    border-left-color: #16a34a;
}

/* DIBATALKAN = MERAH BOLD */
.booking-card.status-cancelled {
    border: 2.5px solid #dc2626;
    background: #fff;
    box-shadow: 0 2px 16px rgba(220,38,38,.15);
    position: relative;
    overflow: hidden;
}
.booking-card.status-cancelled .booking-card-header {
    background: #dc2626;
    border-bottom: none;
}
.booking-card.status-cancelled .booking-kode {
    background: rgba(255,255,255,.2);
    color: #fff;
    border: 1px solid rgba(255,255,255,.35);
    font-weight: 800;
    text-decoration: line-through;
    opacity: .85;
}
.booking-card.status-cancelled .sbadge {
    background: rgba(255,255,255,.2);
    color: #fff;
    border-color: rgba(255,255,255,.35);
}
.booking-card.status-cancelled .sbadge::before { background: #fff; }
.booking-card.status-cancelled .antrian-num {
    color: #dc2626;
    font-size: 42px;
    text-decoration: line-through;
    opacity: .55;
}
.booking-card.status-cancelled .booking-field-val { color: #9ca3af; }
.booking-card.status-cancelled .booking-field-sub { color: #d1d5db; }
.booking-card.status-cancelled .booking-field-label { color: #d1d5db; }
.booking-card.status-cancelled .booking-keluhan {
    background: #fef2f2;
    border-left-color: #dc2626;
    color: #9ca3af;
}
/* Stripe diagonal merah samar di body card --*/
.booking-card.status-cancelled::before {
    content: '';
    position: absolute;
    inset: 0;
    background: repeating-linear-gradient(
        -45deg,
        transparent,
        transparent 22px,
        rgba(220,38,38,.04) 22px,
        rgba(220,38,38,.04) 23px
    );
    pointer-events: none;
    z-index: 0;
}
.booking-card.status-cancelled > * { position: relative; z-index: 1; }

/* Header teks putih semua status --*/
.booking-card-header .btn-batal {
    background: rgba(255,255,255,.15);
    border-color: rgba(255,255,255,.4);
    color: #fff;
}
.booking-card-header .btn-batal:hover {
    background: rgba(255,255,255,.3);
    border-color: #fff;
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
}.booking-kode {
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
    <div class="booking-card status-{{ $b->status }}">
        {{-- Header --}}
        <div class="booking-card-header">
            <span class="booking-kode">{{ $b->kode_booking }}</span>
            <span class="sbadge {{ $sClass }}">{{ $sLabel }}</span>

            @if(in_array($b->status, ['pending','approved']))
            <button type="button" class="btn-batal"
                    onclick="openBatalModal({{ $b->id }}, '{{ addslashes($b->kode_booking) }}')">
                <i class="fas fa-xmark" style="font-size:10px"></i> Batalkan
            </button>
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

        {{-- Notifikasi pembatalan oleh admin --}}
        @if($b->status === 'cancelled' && $b->dibatalkan_oleh === 'admin' && $b->alasan_pembatalan)
        <div style="margin:0 18px 14px;padding:12px 16px;background:#fef2f2;border:1.5px solid #fca5a5;border-radius:12px;position:relative;z-index:1">
            <div style="display:flex;align-items:flex-start;gap:10px">
                <div style="width:32px;height:32px;background:#dc2626;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="fas fa-circle-xmark" style="color:#fff;font-size:14px"></i>
                </div>
                <div style="flex:1">
                    <p style="font-size:11px;font-weight:800;color:#dc2626;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px">Dibatalkan oleh Admin</p>
                    <p style="font-size:13px;color:#7f1d1d;line-height:1.5;font-weight:500">{{ $b->alasan_pembatalan }}</p>
                    @if($b->tanggal_pembatalan)
                    <p style="font-size:11px;color:#ef4444;margin-top:4px">
                        <i class="fas fa-clock" style="margin-right:3px"></i>
                        {{ $b->tanggal_pembatalan->format('d M Y, H:i') }} WIB
                    </p>
                    @endif
                </div>
            </div>
        </div>
        @elseif($b->status === 'cancelled' && $b->dibatalkan_oleh === 'pasien' && $b->alasan_pembatalan)
        <div style="margin:0 18px 14px;padding:10px 14px;background:#fef9f0;border-left:3px solid #f59e0b;border-radius:0 10px 10px 0;position:relative;z-index:1">
            <p style="font-size:10px;font-weight:800;color:#92400e;text-transform:uppercase;letter-spacing:.07em;margin-bottom:2px">Alasan Pembatalan</p>
            <p style="font-size:12px;color:#78350f;line-height:1.5">{{ $b->alasan_pembatalan }}</p>
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

{{-- ═══ MODAL POPUP BATALKAN BOOKING ═══ --}}
<div id="modal-batal-overlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9990;backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);align-items:center;justify-content:center;padding:16px">
    <div id="modal-batal-box"
         style="background:#fff;border-radius:24px;max-width:460px;width:100%;box-shadow:0 24px 60px rgba(0,0,0,.2);overflow:hidden;transform:scale(.95) translateY(16px);transition:transform .25s cubic-bezier(.34,1.56,.64,1),opacity .2s ease;opacity:0">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#dc2626,#b91c1c);padding:20px 24px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:12px">
                <div style="width:40px;height:40px;background:rgba(255,255,255,.15);border-radius:12px;display:flex;align-items:center;justify-content:center">
                    <i class="fas fa-calendar-xmark" style="color:#fff;font-size:18px"></i>
                </div>
                <div>
                    <p style="color:#fff;font-weight:800;font-size:15px;line-height:1">Batalkan Janji Temu</p>
                    <p id="modal-batal-kode" style="color:rgba(255,255,255,.7);font-size:11px;margin-top:2px;font-family:monospace"></p>
                </div>
            </div>
            <button onclick="closeBatalModal()" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:16px;display:flex;align-items:center;justify-content:center;transition:background .15s"
                    onmouseover="this.style.background='rgba(255,255,255,.3)'" onmouseout="this.style.background='rgba(255,255,255,.15)'">
                ×
            </button>
        </div>

        {{-- Body --}}
        <div style="padding:24px">
            <p style="font-size:13px;color:#374151;line-height:1.6;margin-bottom:18px">
                Pilih atau tulis alasan pembatalan. Informasi ini membantu kami meningkatkan pelayanan.
            </p>

            {{-- Pilihan alasan cepat --}}
            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px" id="alasan-chips">
                @foreach([
                    'Jadwal berubah',
                    'Kondisi sudah membaik',
                    'Ingin ganti dokter',
                    'Ada keperluan mendadak',
                    'Salah pilih jadwal',
                    'Lainnya',
                ] as $chip)
                <button type="button"
                        onclick="selectAlasan('{{ $chip }}')"
                        class="alasan-chip"
                        style="padding:7px 14px;border-radius:99px;border:1.5px solid #e5e7eb;background:#f9fafb;font-size:12px;font-weight:600;color:#374151;cursor:pointer;transition:all .15s;font-family:inherit">
                    {{ $chip }}
                </button>
                @endforeach
            </div>

            {{-- Textarea --}}
            <div style="margin-bottom:20px">
                <label style="display:block;font-size:11px;font-weight:800;color:#6b7280;text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px">
                    Alasan Pembatalan <span style="color:#dc2626">*</span>
                </label>
                <textarea id="modal-alasan-text" rows="3" maxlength="500"
                          placeholder="Tulis alasan pembatalan di sini..."
                          style="width:100%;padding:12px 14px;border:1.5px solid #e5e7eb;border-radius:14px;font-size:13px;font-family:inherit;color:#111827;resize:none;outline:none;transition:border-color .15s,box-shadow .15s;box-sizing:border-box"
                          oninput="updateCharCount(this)"
                          onfocus="this.style.borderColor='#dc2626';this.style.boxShadow='0 0 0 3px rgba(220,38,38,.1)'"
                          onblur="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'"></textarea>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px">
                    <p id="alasan-error" style="font-size:11px;color:#dc2626;display:none">
                        <i class="fas fa-circle-exclamation" style="margin-right:3px"></i>Alasan wajib diisi minimal 3 karakter.
                    </p>
                    <p id="char-count" style="font-size:11px;color:#9ca3af;margin-left:auto">0 / 500</p>
                </div>
            </div>

            {{-- Tombol --}}
            <div style="display:flex;gap:10px">
                <button onclick="closeBatalModal()"
                        style="flex:1;padding:12px;border-radius:14px;border:1.5px solid #e5e7eb;background:#f9fafb;font-size:13px;font-weight:700;color:#374151;cursor:pointer;font-family:inherit;transition:all .15s"
                        onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='#f9fafb'">
                    Kembali
                </button>
                <button onclick="submitBatal()"
                        style="flex:1;padding:12px;border-radius:14px;border:none;background:#dc2626;font-size:13px;font-weight:700;color:#fff;cursor:pointer;font-family:inherit;transition:all .15s;display:flex;align-items:center;justify-content:center;gap:7px"
                        onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
                    <i class="fas fa-xmark"></i> Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden forms untuk submit per booking --}}
@foreach($bookings as $b)
@if(in_array($b->status, ['pending','approved']))
<form id="form-batal-{{ $b->id }}"
      method="POST"
      action="{{ route('portal.booking.cancel', $b) }}"
      style="display:none">
    @csrf
    <input type="hidden" name="alasan_pembatalan" id="alasan-input-{{ $b->id }}">
</form>
@endif
@endforeach

{{-- Toast element --}}
<div id="rs-toast">
    <div class="toast-icon"><i class="fas fa-check"></i></div>
    <span id="rs-toast-msg"></span>
</div>
@endsection

@push('scripts')
<script>
// ── TOAST ─────────────────────────────────────────────────────────────
function showToast(msg, dur = 3500) {
    const toast = document.getElementById('rs-toast');
    const label = document.getElementById('rs-toast-msg');
    if (!toast || !label) return;
    label.textContent = msg;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), dur);
}

// ── MODAL BATALKAN BOOKING ────────────────────────────────────────────
let _batalId = null;

function openBatalModal(id, kode) {
    _batalId = id;
    document.getElementById('modal-batal-kode').textContent = kode;
    document.getElementById('modal-alasan-text').value = '';
    document.getElementById('char-count').textContent = '0 / 500';
    document.getElementById('alasan-error').style.display = 'none';

    // Reset chips
    document.querySelectorAll('.alasan-chip').forEach(c => {
        c.style.background   = '#f9fafb';
        c.style.borderColor  = '#e5e7eb';
        c.style.color        = '#374151';
    });

    const overlay = document.getElementById('modal-batal-overlay');
    const box     = document.getElementById('modal-batal-box');
    overlay.style.display = 'flex';
    requestAnimationFrame(() => {
        box.style.transform = 'scale(1) translateY(0)';
        box.style.opacity   = '1';
    });

    // Fokus ke textarea
    setTimeout(() => document.getElementById('modal-alasan-text').focus(), 250);
}

function closeBatalModal() {
    const overlay = document.getElementById('modal-batal-overlay');
    const box     = document.getElementById('modal-batal-box');
    box.style.transform = 'scale(.95) translateY(16px)';
    box.style.opacity   = '0';
    setTimeout(() => { overlay.style.display = 'none'; _batalId = null; }, 220);
}

function selectAlasan(text) {
    const ta = document.getElementById('modal-alasan-text');
    ta.value = text;
    updateCharCount(ta);

    // Chip active state
    document.querySelectorAll('.alasan-chip').forEach(c => {
        const isActive = c.textContent.trim() === text;
        c.style.background  = isActive ? '#fef2f2' : '#f9fafb';
        c.style.borderColor = isActive ? '#dc2626'  : '#e5e7eb';
        c.style.color       = isActive ? '#dc2626'  : '#374151';
    });
    document.getElementById('alasan-error').style.display = 'none';
}

function updateCharCount(el) {
    document.getElementById('char-count').textContent = el.value.length + ' / 500';
}

function submitBatal() {
    const ta    = document.getElementById('modal-alasan-text');
    const err   = document.getElementById('alasan-error');
    const alasan = ta.value.trim();

    if (alasan.length < 3) {
        err.style.display = '';
        ta.focus();
        ta.style.borderColor = '#dc2626';
        ta.style.boxShadow   = '0 0 0 3px rgba(220,38,38,.15)';
        return;
    }
    err.style.display = 'none';

    const input = document.getElementById('alasan-input-' + _batalId);
    const form  = document.getElementById('form-batal-'   + _batalId);
    if (!input || !form) return;

    input.value = alasan;
    form.submit();
}

// Tutup modal klik di luar box
document.getElementById('modal-batal-overlay')?.addEventListener('click', function(e) {
    if (e.target === this) closeBatalModal();
});

// Tutup modal dengan Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeBatalModal();
});

// ── INIT ─────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('session-success');
    if (el) showToast(el.dataset.msg || 'Berhasil!');

    const cards = document.querySelectorAll('.booking-card');
    cards.forEach(function (card, i) {
        card.style.opacity   = '0';
        card.style.transform = 'translateY(12px)';
        card.style.transition = 'opacity .4s ease, transform .4s ease';
        setTimeout(function () {
            card.style.opacity   = '1';
            card.style.transform = 'translateY(0)';
        }, 80 + i * 70);
    });
});
</script>

<style>
/* Modal overlay */
#modal-batal-overlay { display: none; }
</style>
@endpush
