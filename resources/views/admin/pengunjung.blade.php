@extends('layouts.admin')
@php $pageTitle = 'Statistik Pengunjung'; $breadcrumb = 'Admin / Statistik Pengunjung'; @endphp
@section('content')

{{-- Stats Cards --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px">
    <div class="stat-card">
        <div class="stat-icon" style="background:#dbeafe;color:#1d4ed8"><i class="fas fa-eye"></i></div>
        <div class="stat-value">{{ number_format($totalToday) }}</div>
        <div class="stat-label">Kunjungan Hari Ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#dcfce7;color:#166534"><i class="fas fa-users"></i></div>
        <div class="stat-value">{{ number_format($uniqueToday) }}</div>
        <div class="stat-label">IP Unik Hari Ini</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fef3c7;color:#92400e"><i class="fas fa-chart-line"></i></div>
        <div class="stat-value">{{ number_format($totalAll) }}</div>
        <div class="stat-label">Total Semua Kunjungan</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px">

    {{-- Grafik 14 Hari --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-chart-bar" style="color:#16a34a;margin-right:6px"></i>Kunjungan 14 Hari Terakhir</h3></div>
        <div class="card-body">
            <canvas id="visitChart" style="height:200px"></canvas>
        </div>
    </div>

    {{-- Top Pages --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-fire" style="color:#ef4444;margin-right:6px"></i>Halaman Terpopuler (30 Hari)</h3></div>
        <div class="card-body" style="padding:0">
            @forelse($topPages as $i => $page)
            <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid #f8fafc">
                <span style="width:20px;height:20px;background:{{ $i===0?'#16a34a':($i===1?'#2563eb':'#64748b') }};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0">{{ $i+1 }}</span>
                <div style="flex:1;min-width:0">
                    <p style="font-size:12px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ parse_url($page->page_url, PHP_URL_PATH) ?: '/' }}
                    </p>
                </div>
                <span class="badge badge-green" style="flex-shrink:0">{{ $page->total }}</span>
            </div>
            @empty
            <div class="empty-state"><i class="fas fa-chart-bar"></i><p>Belum ada data</p></div>
            @endforelse
        </div>
    </div>
</div>

{{-- Tabel Kunjungan --}}
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list" style="color:#16a34a;margin-right:6px"></i>Log Kunjungan</h3>
        <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap">
            <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input" style="width:160px">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari IP / URL..." class="form-input" style="width:200px">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
            @if(request('tanggal') || request('search'))
            <a href="{{ route('admin.pengunjung') }}" class="btn btn-secondary btn-sm"><i class="fas fa-xmark"></i> Reset</a>
            @endif
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>IP Address</th>
                    <th>Halaman</th>
                    <th>Referer</th>
                    <th>User Agent</th>
                    <th>Waktu Kunjungan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($visits as $v)
                <tr>
                    <td class="text-muted" style="font-size:12px">{{ $visits->firstItem() + $loop->index }}</td>
                    <td>
                        <span style="font-family:monospace;font-size:12px;background:#f1f5f9;padding:2px 6px;border-radius:4px">{{ $v->ip_address ?? '-' }}</span>
                    </td>
                    <td style="max-width:280px">
                        <a href="{{ $v->page_url }}" target="_blank"
                           title="{{ $v->page_url }}"
                           style="font-size:12px;color:#2563eb;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:270px">
                            {{ parse_url($v->page_url, PHP_URL_PATH) ?: '/' }}
                            @if(parse_url($v->page_url, PHP_URL_QUERY))
                                <span style="color:#94a3b8">?{{ parse_url($v->page_url, PHP_URL_QUERY) }}</span>
                            @endif
                        </a>
                    </td>
                    <td style="max-width:180px">
                        @if($v->referer)
                        <span style="font-size:11px;color:#64748b;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px" title="{{ $v->referer }}">
                            {{ parse_url($v->referer, PHP_URL_HOST) ?: $v->referer }}
                        </span>
                        @else
                        <span class="text-muted" style="font-size:11px">—</span>
                        @endif
                    </td>
                    <td style="max-width:200px">
                        <span style="font-size:11px;color:#64748b;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:190px" title="{{ $v->user_agent }}">
                            {{ Str::limit($v->user_agent, 40) }}
                        </span>
                    </td>
                    <td style="white-space:nowrap;font-size:12px;color:#475569">
                        {{ $v->visited_at ? $v->visited_at->format('d/m/Y H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="6"><div class="empty-state"><i class="fas fa-eye-slash"></i><p>Belum ada data kunjungan</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($visits->hasPages())
    <div class="table-footer">{{ $visits->links() }}</div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('visitChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Kunjungan',
                data: {!! json_encode($chartData) !!},
                backgroundColor: 'rgba(22,163,74,0.2)',
                borderColor: '#16a34a',
                borderWidth: 2,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1, precision: 0 } }
            }
        }
    });
}
</script>
@endpush
