@extends('layouts.admin')
@php $pageTitle = 'Transaksi'; $breadcrumb = 'Admin / Transaksi'; @endphp
@section('content')

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
    @foreach([
        ['Total Transaksi',  $stats['total'],           '#2563eb', 'fa-receipt'],
        ['Lunas',            $stats['lunas'],            '#16a34a', 'fa-circle-check'],
        ['Belum Bayar',      $stats['belum_bayar'],      '#f59e0b', 'fa-clock'],
        ['Total Pendapatan', 'Rp'.number_format($stats['total_pendapatan'],0,',','.'), '#7c3aed', 'fa-coins'],
    ] as [$lbl,$val,$clr,$ico])
    <div class="card card-body" style="display:flex;align-items:center;gap:14px">
        <div style="width:44px;height:44px;border-radius:12px;background:{{ $clr }}15;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="fas {{ $ico }}" style="color:{{ $clr }};font-size:18px"></i>
        </div>
        <div>
            <p style="font-size:11px;color:#94a3b8;font-weight:600">{{ $lbl }}</p>
            <p style="font-size:18px;font-weight:800;color:#0f172a">{{ $val }}</p>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h3>Daftar Transaksi</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
            <form style="display:flex;gap:8px;flex-wrap:wrap" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode / Nama pasien..." class="form-input" style="width:180px">
                <select name="status_pembayaran" class="form-input" style="width:160px">
                    <option value="">Semua Pembayaran</option>
                    <option value="belum_bayar"         {{ request('status_pembayaran')=='belum_bayar'?'selected':'' }}>Belum Bayar</option>
                    <option value="menunggu_verifikasi" {{ request('status_pembayaran')=='menunggu_verifikasi'?'selected':'' }}>Menunggu Verifikasi</option>
                    <option value="lunas"               {{ request('status_pembayaran')=='lunas'?'selected':'' }}>Lunas</option>
                    <option value="gagal"               {{ request('status_pembayaran')=='gagal'?'selected':'' }}>Gagal</option>
                </select>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-filter"></i></button>
                @if(request()->hasAny(['search','status_pembayaran','tanggal']))
                <a href="{{ route('admin.transaksi') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>
                @endif
            </form>
            <a href="{{ route('admin.transaksi.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Buat Transaksi</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Kode</th><th>Pasien</th><th>Dokter</th><th>Total Biaya</th><th>Metode</th><th>Status Bayar</th><th>Status Transaksi</th><th>Tanggal</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                @php
                $spBadge = ['belum_bayar'=>'badge-amber','menunggu_verifikasi'=>'badge-blue','lunas'=>'badge-green','gagal'=>'badge-red'][$t->status_pembayaran] ?? 'badge-slate';
                $spLabel = ['belum_bayar'=>'Belum Bayar','menunggu_verifikasi'=>'Menunggu','lunas'=>'Lunas','gagal'=>'Gagal'][$t->status_pembayaran] ?? $t->status_pembayaran;
                $stBadge = ['menunggu'=>'badge-amber','diproses'=>'badge-blue','selesai'=>'badge-green','dibatalkan'=>'badge-red'][$t->status_transaksi] ?? 'badge-slate';
                $stLabel = ['menunggu'=>'Menunggu','diproses'=>'Diproses','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'][$t->status_transaksi] ?? $t->status_transaksi;
                @endphp
                <tr>
                    <td><span class="code-tag">{{ $t->kode_transaksi }}</span></td>
                    <td>
                        <p style="font-weight:600;font-size:13px">{{ $t->pasien?->user?->nama ?? '-' }}</p>
                        <p style="font-size:11px;color:#94a3b8">{{ $t->pasien?->no_rekam_medis }}</p>
                    </td>
                    <td style="font-size:12px;color:#64748b">{{ $t->janjiTemu?->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</td>
                    <td style="font-weight:700;color:#0f172a">Rp{{ number_format($t->total_biaya,0,',','.') }}</td>
                    <td><span class="badge badge-slate">{{ ucfirst($t->metode_pembayaran) }}</span></td>
                    <td><span class="badge {{ $spBadge }}">{{ $spLabel }}</span></td>
                    <td><span class="badge {{ $stBadge }}">{{ $stLabel }}</span></td>
                    <td style="font-size:12px;color:#94a3b8;white-space:nowrap">{{ $t->tanggal_transaksi?->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('admin.transaksi.show', $t) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                            <form method="POST" action="{{ route('admin.transaksi.destroy', $t) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9"><div class="empty-state"><i class="fas fa-receipt"></i><p>Belum ada transaksi</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $transaksis->links() }}</div>
</div>
@endsection
