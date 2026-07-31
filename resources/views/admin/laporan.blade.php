@extends('layouts.admin')
@php $pageTitle = 'Laporan'; $breadcrumb = 'Admin / Laporan'; @endphp

@section('content')

{{-- Filter --}}
<div class="card card-body" style="margin-bottom:24px">
    <form style="display:flex;align-items:flex-end;gap:16px;flex-wrap:wrap">
        <div>
            <label class="form-label">Bulan</label>
            <select name="bulan" class="form-input" style="width:160px">
                @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ $bulan==$m?'selected':'' }}>
                    {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$m] }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="form-label">Tahun</label>
            <select name="tahun" class="form-input" style="width:120px">
                @foreach(range(now()->year, now()->year-4) as $y)
                <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Terapkan</button>
    </form>
</div>

{{-- Grafik Harian --}}
<div class="card card-body" style="margin-bottom:24px">
    <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:4px">Booking Harian</p>
    <p style="font-size:12px;color:#94a3b8;margin-bottom:20px">
        {{ ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan] }} {{ $tahun }}
    </p>
    <canvas id="dailyChart" height="80"></canvas>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">
    {{-- Per Dokter --}}
    <div class="card">
        <div class="card-header"><h3>Top Dokter</h3></div>        <div style="padding:20px">
            @forelse($bookingPerDokter as $item)
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px">
                <div style="width:28px;height:28px;background:#dcfce7;color:#166534;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0">
                    {{ $loop->iteration }}
                </div>
                <div style="flex:1;min-width:0">
                    <p style="font-size:13px;font-weight:600;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                        {{ $item->jadwalDokter?->dokter?->nama_dokter ?? 'Tidak diketahui' }}
                    </p>
                    <div style="height:4px;background:#f1f5f9;border-radius:4px;margin-top:4px;overflow:hidden">
                        <div style="height:100%;background:#16a34a;border-radius:4px;width:{{ $bookingPerDokter->first()?->total > 0 ? round($item->total/$bookingPerDokter->first()->total*100) : 0 }}%"></div>
                    </div>
                </div>
                <span style="font-size:14px;font-weight:800;color:#0f172a;flex-shrink:0">{{ $item->total }}</span>
            </div>
            @empty
            <div class="empty-state" style="padding:30px 0"><i class="fas fa-chart-bar"></i><p>Tidak ada data</p></div>
            @endforelse
        </div>
    </div>

    {{-- Per Status --}}
    <div class="card">
        <div class="card-header"><h3>Distribusi Status</h3></div>
        <div style="padding:20px">
            @php
            $statusMap=['pending'=>['Menunggu','#f59e0b'],'approved'=>['Dikonfirmasi','#3b82f6'],'completed'=>['Selesai','#16a34a'],'cancelled'=>['Dibatalkan','#ef4444']];
            $grandTotal=$bookingPerStatus->sum();
            @endphp
            @foreach($statusMap as $key=>[$lbl,$clr])
            @php $cnt=$bookingPerStatus[$key]??0; $pct=$grandTotal>0?round($cnt/$grandTotal*100):0; @endphp
            <div style="margin-bottom:14px">
                <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px">
                    <span style="font-weight:600;color:#334155">{{ $lbl }}</span>
                    <span style="color:#94a3b8">{{ $cnt }} ({{ $pct }}%)</span>
                </div>
                <div style="height:6px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                    <div style="height:100%;background:{{ $clr }};border-radius:4px;width:{{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
            <div style="text-align:center;padding-top:16px;border-top:1px solid #f1f5f9;margin-top:16px">
                <p style="font-size:28px;font-weight:800;color:#0f172a">{{ $grandTotal }}</p>
                <p style="font-size:12px;color:#94a3b8;margin-top:2px">Total Booking</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($dailyLabels) !!},
        datasets: [{
            label: 'Booking',
            data: {!! json_encode($dailyData) !!},
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22,163,74,.08)',
            fill: true,
            tension: 0.4,
            pointRadius: 3,
            pointBackgroundColor: '#16a34a',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1, font:{size:11} }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false }, ticks: { font:{size:11} } }
        }
    }
});
</script>
@endpush
