@extends('layouts.admin')
@php $pageTitle = 'Spesialisasi'; $breadcrumb = 'Admin / Spesialisasi'; @endphp
@section('content')
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">
    <div class="card">
        <div class="card-header">
            <h3>Daftar Spesialisasi</h3>
            <form method="GET" style="display:flex;gap:8px">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="form-input" style="width:200px">
                <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
                @if(request('search'))<a href="{{ route('admin.spesialisasi') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Nama Spesialisasi</th><th>Icon / Warna</th><th>Estimasi Tunggu</th><th>Jumlah Dokter</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($spesialisasis as $i => $sp)
                    @php $w = $sp->warna ?? 'blue'; @endphp
                    <tr>
                        <td style="color:#94a3b8">{{ $spesialisasis->firstItem()+$i }}</td>
                        <td style="font-weight:700;font-size:13px">{{ $sp->nama_spesialis }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px">
                                <span style="width:28px;height:28px;border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;background:var(--icon-bg);color:var(--icon-clr)"
                                      class="sp-icon-badge" data-warna="{{ $w }}">
                                    <i class="fas {{ $sp->icon ?? 'fa-stethoscope' }}"></i>
                                </span>
                                <span style="font-size:12px;color:#64748b">{{ \App\Models\Spesialisasi::WARNA_OPTIONS[$w] ?? $w }}</span>
                            </div>
                        </td>
                        <td style="font-size:12px;color:#64748b">± {{ $sp->estimasi_menit ?? 15 }} menit</td>
                        <td style="font-size:13px;font-weight:600">{{ $sp->dokters()->count() }}</td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap">
                                <a href="{{ route('admin.spesialisasi.edit',$sp) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="{{ route('admin.spesialisasi.destroy',$sp) }}" onsubmit="return confirm('Hapus spesialisasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-stethoscope"></i><p>Belum ada spesialisasi</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">{{ $spesialisasis->links() }}</div>
    </div>
    {{-- Form tambah cepat --}}
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px">Tambah Spesialisasi Baru</p>
        <a href="{{ route('admin.spesialisasi.create') }}" class="btn btn-primary" style="width:100%;justify-content:center;margin-bottom:12px">
            <i class="fas fa-plus"></i> Tambah Spesialisasi
        </a>
        <p style="font-size:12px;color:#94a3b8">Total: {{ $spesialisasis->total() }} spesialisasi</p>
    </div>
</div>
@endsection
@push('scripts')
<script>
// Warnai icon badge sesuai warna poli
const warnaMap = {
    blue:   ['#dbeafe','#1d4ed8'], green:  ['#dcfce7','#15803d'],
    red:    ['#fee2e2','#b91c1c'], indigo: ['#e0e7ff','#4338ca'],
    purple: ['#f3e8ff','#7e22ce'], orange: ['#ffedd5','#c2410c'],
    pink:   ['#fce7f3','#be185d'], teal:   ['#ccfbf1','#0f766e'],
    yellow: ['#fef9c3','#a16207'], gray:   ['#f1f5f9','#475569'],
};
document.querySelectorAll('.sp-icon-badge').forEach(function(el) {
    const w = el.dataset.warna || 'blue';
    const [bg, clr] = warnaMap[w] || warnaMap.blue;
    el.style.background = bg;
    el.style.color       = clr;
});
</script>
@endpush
