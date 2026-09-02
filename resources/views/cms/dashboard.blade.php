@extends('layouts.cms')
@php $pageTitle = 'Dashboard CMS'; $breadcrumb = 'Selamat datang, ' . Auth::user()->nama; @endphp

@push('styles')
<style>
/* ── Stats: 4 kolom desktop, 2 mobile ─────────────────── */
.dash-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
/* ── Main grid: konten + sidebar ──────────────────────── */
.dash-main {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
}
.dash-login-log { grid-column: 1 / -1; margin-bottom: 4px; }

/* ── Mobile ≤ 767px: semua stack vertikal ─────────────── */
@media (max-width: 767px) {
    .dash-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    .stat-card  { padding: 14px 12px; }
    .stat-value { font-size: 20px; }
    .stat-label { font-size: 10px; }

    .dash-main {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .dash-login-log { grid-column: 1; }

    /* Login log: sembunyikan IP agar tidak overflow */
    .log-ip    { display: none !important; }
    .log-time  { min-width: 0; }
}
</style>
@endpush

@section('content')

{{-- ═══════════ STAT CARDS ═══════════ --}}
<div class="dash-stats">
    @php
    $cards = [
        ['fas fa-newspaper',    'Total Artikel',   $stats['total_artikel'],                                              '#2563eb','#dbeafe'],
        ['fas fa-circle-check', 'Dipublikasi',     $stats['artikel_publish'],                                            '#16a34a','#dcfce7'],
        ['fas fa-file-alt',     'Draft Artikel',   max(0, $stats['total_artikel'] - $stats['artikel_publish']),          '#64748b','#f1f5f9'],
        ['fas fa-tag',          'Total Promo',     $stats['total_promo'],                                                '#d97706','#fef3c7'],
        ['fas fa-star',         'Promo Aktif',     $stats['promo_aktif'],                                                '#ea580c','#ffedd5'],
        ['fas fa-file-alt',     'Draft Promo',     max(0, $stats['total_promo'] - $stats['promo_aktif']),                '#64748b','#f1f5f9'],
        ['fas fa-calendar-days','Event Mendatang', $stats['event_mendatang'],                                            '#7c3aed','#ede9fe'],
        ['fas fa-panorama',     'Total Banner',    $stats['total_banner'],                                               '#db2777','#fce7f3'],
        ['fas fa-eye',          'Banner Aktif',    $stats['banner_aktif'],                                               '#0891b2','#cffafe'],
        ['fas fa-calendar-days','Total Event',     $stats['total_event'],                                                '#4f46e5','#e0e7ff'],
    ];
    @endphp
    @foreach($cards as [$icon,$label,$val,$color,$bg])
    <div class="stat-card">
        <div class="stat-icon" style="background:{{ $bg }};color:{{ $color }}">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="stat-value">{{ $val }}</div>
        <div class="stat-label">{{ $label }}</div>
    </div>
    @endforeach
</div>

{{-- ═══════════ MAIN GRID ═══════════ --}}
<div class="dash-main">

    {{-- ── Riwayat Login (full width) ── --}}
    @if($loginLogs->isNotEmpty())
    <div class="card dash-login-log">
        <div class="card-header" style="padding:12px 20px">
            <h3 style="font-size:13px;display:flex;align-items:center;gap:8px">
                <span style="width:8px;height:8px;background:#ef4444;border-radius:50%;display:inline-block"></span>
                <i class="fas fa-bell" style="color:#ef4444"></i>
                Riwayat Login Admin &amp; CMS
            </h3>
            <span style="font-size:11px;color:#94a3b8">{{ $loginLogs->count() }} login terakhir</span>
        </div>
        <div style="max-height:220px;overflow-y:auto">
            @foreach($loginLogs as $log)
            @php
                $isAdmin  = $log->user?->role === 'admin';
                $isSelf   = $log->user_id === Auth::id();
                $isRecent = $log->login_at->diffInMinutes(now()) <= 60;
            @endphp
            <div style="display:flex;align-items:center;gap:12px;padding:10px 16px;
                        border-bottom:1px solid #f8fafc;
                        background:{{ $isRecent ? '#fefce8' : 'transparent' }}">
                {{-- Avatar --}}
                <div style="flex-shrink:0;width:32px;height:32px;border-radius:50%;
                            background:{{ $isAdmin ? '#fee2e2' : '#dbeafe' }};
                            display:flex;align-items:center;justify-content:center;
                            font-size:12px;font-weight:700;
                            color:{{ $isAdmin ? '#dc2626' : '#2563eb' }}">
                    {{ strtoupper(substr($log->user?->nama ?? '?', 0, 1)) }}
                </div>
                {{-- Nama + badge --}}
                <div style="flex:1;min-width:0;overflow:hidden">
                    <div style="font-size:13px;font-weight:600;color:#0f172a;
                                white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $log->user?->nama ?? 'Unknown' }}
                        @if($isSelf)<span style="font-size:10px;color:#94a3b8;font-weight:400"> (Anda)</span>@endif
                        <span class="badge {{ $isAdmin ? 'badge-red' : 'badge-blue' }}"
                              style="font-size:10px;margin-left:4px;vertical-align:middle">
                            {{ $isAdmin ? 'Admin' : 'CMS' }}
                        </span>
                    </div>
                    <div class="log-ip" style="font-size:11px;color:#94a3b8;margin-top:1px">
                        IP: {{ $log->ip_address ?? '-' }}
                    </div>
                </div>
                {{-- Waktu --}}
                <div class="log-time" style="text-align:right;flex-shrink:0;white-space:nowrap">
                    <p style="font-size:12px;font-weight:600;color:{{ $isRecent ? '#d97706' : '#64748b' }}">
                        {{ $log->login_at->format('H:i') }}
                    </p>
                    <p style="font-size:10px;color:#94a3b8">
                        {{ $log->login_at->format('d M Y') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Artikel Terbaru (kiri) ── --}}
    <div class="card">
        <div class="card-header">
            <h3>Artikel Terbaru</h3>
            <a href="{{ route('cms.artikel') }}" class="btn btn-sm"
               style="color:#2563eb;background:none;border:1px solid #dbeafe">Lihat Semua</a>
        </div>
        @forelse($recentArtikel as $art)
        <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;
                    border-bottom:1px solid #f8fafc">
            <div style="width:38px;height:38px;border-radius:10px;background:#dbeafe;
                        display:flex;align-items:center;justify-content:center;
                        font-size:16px;flex-shrink:0">📰</div>
            <div style="flex:1;min-width:0;overflow:hidden">
                <p style="font-size:13px;font-weight:600;color:#0f172a;
                           white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                    {{ $art->judul }}
                </p>
                <p style="font-size:11px;color:#94a3b8;margin-top:2px">
                    {{ $art->kategori?->nama_kategori ?? '-' }} &middot; {{ $art->created_tm->diffForHumans() }}
                </p>
            </div>
            <span class="badge {{ $art->status==='publish' ? 'badge-green' : 'badge-amber' }}"
                  style="flex-shrink:0">
                {{ $art->status==='publish' ? 'Publish' : 'Draft' }}
            </span>
        </div>
        @empty
        <div class="empty-state"><i class="fas fa-newspaper"></i><p>Belum ada artikel</p></div>
        @endforelse
    </div>

    {{-- ── Sidebar kanan: Akses Cepat + Status User ── --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Akses Cepat --}}
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:12px">Akses Cepat</p>
            @foreach([
                ['fas fa-pen-nib',       'Tulis Artikel Baru', 'cms.artikel.create', '#2563eb','#dbeafe'],
                ['fas fa-tag',           'Tambah Promo',       'cms.promo.create',   '#d97706','#fef3c7'],
                ['fas fa-calendar-plus', 'Tambah Event',       'cms.event.create',   '#7c3aed','#ede9fe'],
                ['fas fa-panorama',      'Kelola Banner',      'cms.banner',         '#db2777','#fce7f3'],
                ['fas fa-hospital',      'Identitas RS',       'cms.identitas-rs',   '#475569','#f1f5f9'],
            ] as [$ico,$lbl,$rt,$clr,$bg])
            <a href="{{ route($rt) }}"
               style="display:flex;align-items:center;gap:10px;padding:9px 10px;
                      border-radius:10px;text-decoration:none;margin-bottom:2px"
               onmouseover="this.style.background='#f8fafc'"
               onmouseout="this.style.background='transparent'">
                <div style="width:30px;height:30px;background:{{ $bg }};color:{{ $clr }};
                            border-radius:8px;display:flex;align-items:center;
                            justify-content:center;flex-shrink:0;font-size:12px">
                    <i class="{{ $ico }}"></i>
                </div>
                <span style="font-size:13px;font-weight:500;color:#334155">{{ $lbl }}</span>
                <i class="fas fa-chevron-right" style="margin-left:auto;font-size:10px;color:#cbd5e1"></i>
            </a>
            @endforeach
        </div>

        {{-- Status User CMS --}}
        <div class="card">
            <div class="card-header" style="padding:14px 16px">
                <h3 style="font-size:13px">
                    <i class="fas fa-users" style="color:#2563eb;margin-right:6px"></i>Status User CMS
                </h3>
                <span style="font-size:10px;color:#94a3b8">Online = aktif 5 menit</span>
            </div>
            @forelse($pengunjungCms as $u)
            @php
                $isOnline = $u->last_activity && now()->diffInMinutes($u->last_activity) <= 5;
                $isSelf   = $u->id === Auth::id();
            @endphp
            <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;
                        border-bottom:1px solid #f8fafc;
                        background:{{ $isSelf ? '#f0f9ff' : 'transparent' }}">
                <div style="position:relative;flex-shrink:0">
                    @if($u->foto)
                        <img src="{{ Storage::url($u->foto) }}"
                             style="width:34px;height:34px;border-radius:50%;object-fit:cover">
                    @else
                        <div style="width:34px;height:34px;border-radius:50%;
                                    background:{{ $u->role==='admin' ? '#fee2e2' : '#dbeafe' }};
                                    display:flex;align-items:center;justify-content:center;
                                    color:{{ $u->role==='admin' ? '#dc2626' : '#2563eb' }};
                                    font-size:13px;font-weight:700">
                            {{ strtoupper(substr($u->nama, 0, 1)) }}
                        </div>
                    @endif
                    <span style="position:absolute;bottom:0;right:0;width:10px;height:10px;
                                 border-radius:50%;border:2px solid #fff;
                                 background:{{ $isOnline ? '#16a34a' : '#94a3b8' }}"></span>
                </div>
                <div style="flex:1;min-width:0;overflow:hidden">
                    <p style="font-size:12px;font-weight:600;color:#0f172a;
                               white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $u->nama }}{{ $isSelf ? ' (Anda)' : '' }}
                    </p>
                    <p style="font-size:10px;color:#94a3b8;margin-top:1px">
                        @if($isOnline)
                            <span style="color:#16a34a;font-weight:600">Online sekarang</span>
                        @elseif($u->last_activity)
                            Aktif {{ $u->last_activity->diffForHumans() }}
                        @else
                            Belum aktif di CMS
                        @endif
                    </p>
                </div>
                <span class="badge {{ $u->role==='admin' ? 'badge-red' : 'badge-blue' }}"
                      style="font-size:10px;flex-shrink:0">
                    {{ $u->role==='admin' ? 'Admin' : 'CMS' }}
                </span>
            </div>
            @empty
            <div style="padding:20px;text-align:center;color:#94a3b8;font-size:12px">
                Belum ada user CMS
            </div>
            @endforelse
        </div>

    </div>{{-- /sidebar --}}

</div>{{-- /dash-main --}}

@endsection
