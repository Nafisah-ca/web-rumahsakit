@extends('layouts.cms')
@php $pageTitle = 'Pengaturan Antrian Poli'; $breadcrumb = 'CMS / Antrian Poli'; @endphp
@section('content')
<form method="POST" action="{{ route('cms.antrian-poli.update') }}">
@csrf @method('PUT')

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:20px"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif
@if($errors->any())
<div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

    {{-- Kolom Kiri: Estimasi per Poli --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-list-ol" style="color:#2563eb;margin-right:6px"></i>Estimasi Waktu Tunggu per Poli</h3>
        </div>
        <div style="padding:16px">
            <p style="font-size:12px;color:#64748b;margin-bottom:16px">
                Estimasi dihitung: <strong>sisa antrian × menit per pasien</strong>. Ubah nilai di bawah sesuai kebutuhan, mirip cara mengubah jadwal waktu sholat.
            </p>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Poli</th>
                            <th>Icon</th>
                            <th style="width:160px">Estimasi (menit/pasien)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($spesialisasis as $sp)
                        @php $w = $sp->warna ?? 'blue'; @endphp
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:8px">
                                    <span class="sp-icon" data-warna="{{ $w }}" style="width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0">
                                        <i class="fas {{ $sp->icon ?? 'fa-stethoscope' }}"></i>
                                    </span>
                                    <span style="font-weight:600;font-size:13px">Poli {{ $sp->nama_spesialis }}</span>
                                </div>
                            </td>
                            <td style="font-size:12px;color:#64748b;font-family:monospace">{{ $sp->icon ?? 'fa-stethoscope' }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:6px">
                                    <input type="number"
                                           name="estimasi[{{ $sp->id }}]"
                                           value="{{ old('estimasi.'.$sp->id, $sp->estimasi_menit ?? 15) }}"
                                           class="form-input"
                                           min="1" max="300"
                                           style="width:80px;text-align:center">
                                    <span style="font-size:12px;color:#94a3b8">menit</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3"><div class="empty-state"><i class="fas fa-stethoscope"></i><p>Belum ada spesialisasi</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Setting Global --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9">
                <i class="fas fa-clock" style="color:#2563eb;margin-right:6px"></i>Pengaturan Global Antrian
            </p>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-sync-alt" style="color:#16a34a;width:14px"></i> Interval Auto-Refresh
                </label>
                <div style="display:flex;align-items:center;gap:8px">
                    <input type="number"
                           name="interval_refresh"
                           value="{{ old('interval_refresh', $estimasi['interval_refresh'] ?? 30) }}"
                           class="form-input"
                           min="5" max="300"
                           style="width:90px;text-align:center">
                    <span style="font-size:12px;color:#64748b">detik</span>
                </div>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">
                    Halaman Live Antrian akan memuat ulang data setiap N detik. Min: 5 detik.
                </p>
                @error('interval_refresh')<p style="color:#ef4444;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-comment-dots" style="color:#f59e0b;width:14px"></i> Pesan Tunggu
                </label>
                <textarea name="pesan_tunggu" rows="3" class="form-input" maxlength="200" placeholder="Harap menunggu, nomor Anda akan segera dipanggil.">{{ old('pesan_tunggu', $estimasi['pesan_tunggu'] ?? 'Harap menunggu, nomor Anda akan segera dipanggil.') }}</textarea>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">
                    Pesan yang muncul di footer kartu antrian untuk pasien.
                </p>
            </div>
        </div>

        <div class="card card-body" style="background:#f0fdf4;border:1px solid #bbf7d0">
            <p style="font-size:12px;font-weight:600;color:#15803d;margin-bottom:8px"><i class="fas fa-info-circle"></i> Cara Kerja</p>
            <ul style="font-size:12px;color:#166534;margin:0;padding-left:16px;line-height:1.8">
                <li>Estimasi tunggu = sisa antrian × menit per pasien</li>
                <li>Poli buka jika ada jadwal dokter aktif hari ini</li>
                <li>Data diperbarui otomatis setiap {{ $estimasi['interval_refresh'] ?? 30 }} detik</li>
                <li>Icon & warna poli diatur di menu <strong>Admin → Spesialisasi</strong></li>
            </ul>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px">
            <i class="fas fa-save"></i> Simpan Pengaturan
        </button>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
const warnaMap = {
    blue:   ['#dbeafe','#1d4ed8'], green:  ['#dcfce7','#15803d'],
    red:    ['#fee2e2','#b91c1c'], indigo: ['#e0e7ff','#4338ca'],
    purple: ['#f3e8ff','#7e22ce'], orange: ['#ffedd5','#c2410c'],
    pink:   ['#fce7f3','#be185d'], teal:   ['#ccfbf1','#0f766e'],
    yellow: ['#fef9c3','#a16207'], gray:   ['#f1f5f9','#475569'],
};
document.querySelectorAll('.sp-icon').forEach(function(el) {
    const [bg, clr] = warnaMap[el.dataset.warna] || warnaMap.blue;
    el.style.background = bg;
    el.style.color       = clr;
});
</script>
@endpush
