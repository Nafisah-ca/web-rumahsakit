@extends('layouts.admin')
@php $pageTitle = 'Dashboard'; $breadcrumb = 'Selamat datang, ' . Auth::user()->nama; @endphp

@section('content')

<div class="stats-grid">
    @php
    $statCards = [
        ['fas fa-bed-pulse',      'Total Pasien',       $stats['total_pasien'],      '#2563eb','#dbeafe'],
        ['fas fa-user-doctor',    'Dokter Aktif',       $stats['total_dokter'],      '#16a34a','#dcfce7'],
        ['fas fa-calendar-check', 'Booking Hari Ini',   $stats['booking_hari_ini'],  '#4f46e5','#e0e7ff'],
        ['fas fa-hourglass-half', 'Menunggu Verifikasi',$stats['booking_menunggu'],  '#d97706','#fef3c7'],
        ['fas fa-stethoscope',    'Spesialisasi Aktif', $stats['total_spesialisasi'], '#7c3aed','#ede9fe'],
        ['fas fa-chart-line',     'Booking Bulan Ini',  $stats['booking_bulan_ini'], '#dc2626','#fee2e2'],
    ];
    @endphp
    @foreach($statCards as [$icon,$label,$val,$color,$bg])
    <div class="stat-card">
        <div class="stat-icon" style="background:{{ $bg }};color:{{ $color }}">
            <i class="{{ $icon }}"></i>
        </div>
        <div class="stat-value">{{ number_format($val) }}</div>
        <div class="stat-label">{{ $label }}</div>
    </div>
    @endforeach
</div>

<div class="dash-main-grid">
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px">Booking 7 Hari Terakhir</p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:16px">{{ now()->subDays(6)->format('d M') }} – {{ now()->format('d M Y') }}</p>
        <canvas id="bookingChart" height="120"></canvas>
    </div>
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px">Status Booking</p>
        @php
        $statusConf = [
            'pending'   => ['Menunggu',     '#f59e0b'],
            'approved'  => ['Dikonfirmasi', '#3b82f6'],
            'completed' => ['Selesai',      '#16a34a'],
            'cancelled' => ['Dibatalkan',   '#ef4444'],
        ];
        $totalSt = array_sum($statusCounts);
        @endphp
        @foreach($statusConf as $key => [$lbl,$clr])
        @php $cnt=$statusCounts[$key]??0; $pct=$totalSt>0?round($cnt/$totalSt*100):0; @endphp
        <div style="margin-bottom:12px">
            <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
                <span style="font-weight:600;color:#334155">{{ $lbl }}</span>
                <span style="color:#94a3b8">{{ $cnt }} ({{ $pct }}%)</span>
            </div>
            <div style="height:6px;background:#f1f5f9;border-radius:99px;overflow:hidden">
                <div style="height:100%;background:{{ $clr }};border-radius:99px;width:{{ $pct }}%;transition:width .3s"></div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <h3>Booking Terbaru</h3>
        <div style="display:flex;align-items:center;gap:8px">
            {{-- Tab --}}
            <div style="display:flex;background:#f1f5f9;border-radius:8px;padding:3px;gap:2px">
                <button onclick="switchTab('hari-ini')" id="tab-hari-ini"
                    style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:6px;border:none;cursor:pointer;transition:all .15s;background:#16a34a;color:#fff">
                    Hari Ini
                    <span style="background:rgba(255,255,255,.25);border-radius:99px;padding:1px 6px;font-size:11px;margin-left:4px">{{ $bookingHariIni->count() }}</span>
                </button>
                <button onclick="switchTab('terbaru')" id="tab-terbaru"
                    style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:6px;border:none;cursor:pointer;transition:all .15s;background:transparent;color:#64748b">
                    Semua Terbaru
                </button>
            </div>
            <a href="{{ route('admin.booking') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
    </div>

    {{-- Tabel Hari Ini --}}
    <div id="panel-hari-ini" class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th><th>Pasien</th><th>Dokter</th><th>Jam</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookingHariIni as $b)
                @php
                $bconf=['pending'=>['Menunggu','badge-amber'],'approved'=>['Dikonfirmasi','badge-blue'],'completed'=>['Selesai','badge-green'],'cancelled'=>['Dibatalkan','badge-red']];
                [$blbl,$bcls]=($bconf[$b->status]??['–','badge-slate']);
                @endphp
                <tr>
                    <td><span class="code-tag">{{ $b->kode_booking }}</span></td>
                    <td style="font-weight:600">{{ $b->pasien?->user?->nama ?? $b->pasien?->nama_lengkap ?? '-' }}</td>
                    <td style="color:#64748b">{{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</td>
                    <td style="color:#64748b;font-size:12px">{{ $b->jadwalDokter?->jam_mulai ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : '-' }}</td>
                    <td><span class="badge {{ $bcls }}">{{ $blbl }}</span></td>
                    <td><a href="{{ route('admin.booking.show',$b) }}" style="color:#16a34a;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px"><i class="fas fa-eye"></i> Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="6">
                    <div class="empty-state">
                        <i class="fas fa-calendar-day"></i>
                        <p>Tidak ada booking hari ini</p>
                    </div>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Tabel Semua Terbaru --}}
    <div id="panel-terbaru" class="table-wrap" style="display:none">
        <table>
            <thead>
                <tr>
                    <th>Kode</th><th>Pasien</th><th>Dokter</th><th>Tanggal</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $b)
                @php
                $bconf=['pending'=>['Menunggu','badge-amber'],'approved'=>['Dikonfirmasi','badge-blue'],'completed'=>['Selesai','badge-green'],'cancelled'=>['Dibatalkan','badge-red']];
                [$blbl,$bcls]=($bconf[$b->status]??['–','badge-slate']);
                @endphp
                <tr>
                    <td><span class="code-tag">{{ $b->kode_booking }}</span></td>
                    <td style="font-weight:600">{{ $b->pasien?->user?->nama ?? $b->pasien?->nama_lengkap ?? '-' }}</td>
                    <td style="color:#64748b">{{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</td>
                    <td style="color:#64748b">{{ $b->tanggal_booking?->format('d M Y') }}</td>
                    <td><span class="badge {{ $bcls }}">{{ $blbl }}</span></td>
                    <td><a href="{{ route('admin.booking.show',$b) }}" style="color:#16a34a;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px"><i class="fas fa-eye"></i> Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-calendar-xmark"></i><p>Belum ada booking</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-bottom:24px">
    <div class="card-header">
        <h3>Dokter Aktif</h3>
        <a href="{{ route('admin.dokter') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Online</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doktersAktif as $d)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                @if($d->foto)
                                    <img src="{{ Storage::url($d->foto) }}" style="width:36px;height:36px;border-radius:10px;object-fit:cover;flex-shrink:0">
                                @else
                                    <div style="width:36px;height:36px;border-radius:10px;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;background:linear-gradient(135deg,#374151,#1f2937)">
                                        {{ strtoupper(substr($d->nama_dokter,3,1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p style="font-weight:700;font-size:13px;color:#0f172a">{{ $d->nama_dokter }}</p>
                                    <p style="font-size:11px;color:#94a3b8"></p>
                                </div>
                            </div>
                        </td>
                        <td style="color:#64748b">{{ $d->spesialisasi?->nama_spesialis ?? '-' }}</td>
                        <td><span class="badge badge-slate">Tidak</span></td>
                        <td><span class="badge {{ $d->status === 'aktif' ? 'badge-green' : 'badge-slate' }}">{{ $d->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td>
                            <a href="{{ route('admin.dokter.edit',$d) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-user-doctor"></i><p>Tidak ada dokter aktif</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="dash-quick-grid">
    @foreach([
        ['fas fa-calendar-plus','Tambah Jadwal','admin.jadwal.create','#16a34a','#dcfce7'],
        ['fas fa-user-doctor','Tambah Dokter','admin.dokter.create','#2563eb','#dbeafe'],
        ['fas fa-user-plus','Tambah User','admin.users.create','#7c3aed','#ede9fe'],
        ['fas fa-chart-column','Laporan','admin.laporan','#dc2626','#fee2e2'],
    ] as [$ico,$lbl,$rt,$clr,$bg])
    <a href="{{ route($rt) }}" style="display:flex;align-items:center;gap:12px;padding:16px;background:#fff;border-radius:14px;border:1px solid #f1f5f9;text-decoration:none;transition:box-shadow .15s" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.08)'" onmouseout="this.style.boxShadow='none'">
        <div style="width:38px;height:38px;background:{{ $bg }};color:{{ $clr }};border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="{{ $ico }}"></i>
        </div>
        <span style="font-size:13px;font-weight:600;color:#334155">{{ $lbl }}</span>
    </a>
    @endforeach
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
function switchTab(tab) {
    const panels = { 'hari-ini': 'panel-hari-ini', 'terbaru': 'panel-terbaru' };
    const tabs   = { 'hari-ini': 'tab-hari-ini',   'terbaru': 'tab-terbaru'   };

    Object.keys(panels).forEach(key => {
        const isActive = key === tab;
        document.getElementById(panels[key]).style.display = isActive ? '' : 'none';
        const btn = document.getElementById(tabs[key]);
        btn.style.background = isActive ? '#16a34a' : 'transparent';
        btn.style.color      = isActive ? '#fff'    : '#64748b';
    });
}

new Chart(document.getElementById('bookingChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'Booking',
            data: {!! json_encode($chartData) !!},
            backgroundColor: 'rgba(22,163,74,.15)',
            borderColor: '#16a34a',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush
