@extends('layouts.admin')
@php $pageTitle = 'Statistik Pengunjung'; $breadcrumb = 'Admin / Statistik Pengunjung'; @endphp
@section('content')

{{-- Stats Cards --}}
<div class="stats-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px">
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
    <div class="stat-card">
        <div class="stat-icon" style="background:#f3e8ff;color:#7c3aed"><i class="fas fa-map-marker-alt"></i></div>
        <div class="stat-value">{{ number_format($totalWithLocation) }}</div>
        <div class="stat-label">Terdeteksi Lokasi</div>
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

{{-- Peta Lokasi + Top Kota --}}
<div style="display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px">

    {{-- Peta Leaflet --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-map" style="color:#2563eb;margin-right:6px"></i>Peta Lokasi Pengunjung</h3>
            <span style="font-size:12px;color:#64748b">{{ $locationPoints->count() }} titik lokasi terbaru</span>
        </div>
        <div class="card-body" style="padding:0">
            @if($locationPoints->isEmpty())
            <div class="empty-state" style="padding:48px 16px">
                <i class="fas fa-map-marker-alt" style="font-size:2rem;color:#cbd5e1"></i>
                <p style="margin-top:8px;color:#94a3b8">Belum ada data lokasi. Pengunjung perlu mengizinkan akses lokasi di browser.</p>
            </div>
            @else
            <div id="visitor-map" style="height:340px;width:100%;border-radius:0 0 12px 12px"></div>
            @endif
        </div>
    </div>

    {{-- Top Kota --}}
    <div class="card">
        <div class="card-header"><h3><i class="fas fa-city" style="color:#7c3aed;margin-right:6px"></i>Kota Terbanyak (30 Hari)</h3></div>
        <div class="card-body" style="padding:0">
            @forelse($topCities as $i => $city)
            <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid #f8fafc">
                <span style="width:20px;height:20px;background:{{ $i===0?'#7c3aed':($i===1?'#a855f7':'#64748b') }};color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;flex-shrink:0">{{ $i+1 }}</span>
                <div style="flex:1;min-width:0">
                    <p style="font-size:12px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $city->city ?? '—' }}
                    </p>
                </div>
                <span class="badge" style="background:#f3e8ff;color:#7c3aed;flex-shrink:0">{{ $city->total }}</span>
            </div>
            @empty
            <div class="empty-state"><i class="fas fa-city"></i><p>Belum ada data kota</p></div>
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
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari IP / URL / Kota..." class="form-input" style="width:210px">
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
                    <th>Lokasi</th>
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
                    <td style="max-width:240px">
                        <a href="{{ $v->page_url }}" target="_blank"
                           title="{{ $v->page_url }}"
                           style="font-size:12px;color:#2563eb;text-decoration:none;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:230px">
                            {{ parse_url($v->page_url, PHP_URL_PATH) ?: '/' }}
                            @if(parse_url($v->page_url, PHP_URL_QUERY))
                                <span style="color:#94a3b8">?{{ parse_url($v->page_url, PHP_URL_QUERY) }}</span>
                            @endif
                        </a>
                    </td>
                    <td style="min-width:140px">
                        @if($v->latitude && $v->longitude)
                            <div style="display:flex;flex-direction:column;gap:2px">
                                <span style="font-size:12px;font-weight:600;color:#7c3aed">
                                    <i class="fas fa-map-marker-alt" style="font-size:10px"></i>
                                    {{ $v->city ?? 'Tidak diketahui' }}
                                    @if($v->region && $v->region !== $v->city), {{ $v->region }}@endif
                                </span>
                                <a href="https://maps.google.com/?q={{ $v->latitude }},{{ $v->longitude }}"
                                   target="_blank"
                                   style="font-size:10px;color:#64748b;font-family:monospace;text-decoration:none">
                                    {{ number_format($v->latitude, 5) }}, {{ number_format($v->longitude, 5) }}
                                    <i class="fas fa-external-link-alt" style="font-size:9px"></i>
                                </a>
                            </div>
                        @else
                            <span style="font-size:11px;color:#cbd5e1">—</span>
                        @endif
                    </td>
                    <td style="max-width:160px">
                        @if($v->referer)
                        <span style="font-size:11px;color:#64748b;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px" title="{{ $v->referer }}">
                            {{ parse_url($v->referer, PHP_URL_HOST) ?: $v->referer }}
                        </span>
                        @else
                        <span class="text-muted" style="font-size:11px">—</span>
                        @endif
                    </td>
                    <td style="max-width:180px">
                        <span style="font-size:11px;color:#64748b;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:170px" title="{{ $v->user_agent }}">
                            {{ Str::limit($v->user_agent, 40) }}
                        </span>
                    </td>
                    <td style="white-space:nowrap;font-size:12px;color:#475569">
                        {{ $v->visited_at ? $v->visited_at->format('d/m/Y H:i:s') : '-' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-eye-slash"></i><p>Belum ada data kunjungan</p></div></td></tr>
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

{{-- Leaflet CSS + JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// ── Bar chart kunjungan 14 hari ─────────────────────────────────────────
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

// ── Leaflet peta lokasi pengunjung ──────────────────────────────────────
@if($locationPoints->isNotEmpty())
(function () {
    var points = {!! $locationPoints->map(fn($p) => [
        'lat'  => (float) $p->latitude,
        'lng'  => (float) $p->longitude,
        'city' => $p->city ?? 'Tidak diketahui',
        'url'  => parse_url($p->page_url, PHP_URL_PATH) ?: '/',
        'time' => $p->visited_at ? $p->visited_at->format('d/m/Y H:i') : '-',
    ])->values()->toJson() !!};

    // Hitung rata-rata koordinat untuk center peta
    var sumLat = 0, sumLng = 0;
    points.forEach(function(p) { sumLat += p.lat; sumLng += p.lng; });
    var centerLat = sumLat / points.length;
    var centerLng = sumLng / points.length;

    var map = L.map('visitor-map', { scrollWheelZoom: false }).setView([centerLat, centerLng], 5);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 18
    }).addTo(map);

    // Ikon lingkaran kecil untuk setiap titik
    var dotIcon = L.divIcon({
        className: '',
        html: '<div style="width:10px;height:10px;background:#7c3aed;border:2px solid #fff;border-radius:50%;box-shadow:0 1px 4px rgba(0,0,0,.4)"></div>',
        iconSize: [10, 10],
        iconAnchor: [5, 5],
    });

    points.forEach(function(p) {
        L.marker([p.lat, p.lng], { icon: dotIcon })
            .addTo(map)
            .bindPopup(
                '<div style="font-size:12px;line-height:1.6">' +
                '<strong><i class="fas fa-map-marker-alt" style="color:#7c3aed"></i> ' + p.city + '</strong><br>' +
                '<span style="color:#64748b">Halaman: ' + p.url + '</span><br>' +
                '<span style="color:#64748b">Waktu: ' + p.time + '</span>' +
                '</div>',
                { maxWidth: 220 }
            );
    });
})();
@endif
</script>
@endpush
