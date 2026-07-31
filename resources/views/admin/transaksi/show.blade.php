@extends('layouts.admin')
@php $pageTitle = 'Detail Transaksi'; $breadcrumb = 'Admin / Transaksi / ' . $transaksi->kode_transaksi; @endphp
@section('content')

@if(session('success'))
<div class="alert alert-success" style="margin-bottom:20px"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">

    {{-- Detail Transaksi --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Header --}}
        <div class="card card-body">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px">
                <div>
                    <p style="font-size:11px;color:#94a3b8;font-weight:600;margin-bottom:4px">KODE TRANSAKSI</p>
                    <p style="font-size:22px;font-weight:800;color:#0f172a;font-family:monospace">{{ $transaksi->kode_transaksi }}</p>
                    <p style="font-size:12px;color:#94a3b8;margin-top:4px">{{ $transaksi->tanggal_transaksi?->format('d M Y, H:i') }} WIB</p>
                </div>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    @php
                    $spBadge=['belum_bayar'=>'badge-amber','menunggu_verifikasi'=>'badge-blue','lunas'=>'badge-green','gagal'=>'badge-red'][$transaksi->status_pembayaran]??'badge-slate';
                    $spLabel=['belum_bayar'=>'Belum Bayar','menunggu_verifikasi'=>'Menunggu Verifikasi','lunas'=>'Lunas','gagal'=>'Gagal'][$transaksi->status_pembayaran]??$transaksi->status_pembayaran;
                    $stBadge=['menunggu'=>'badge-amber','diproses'=>'badge-blue','selesai'=>'badge-green','dibatalkan'=>'badge-red'][$transaksi->status_transaksi]??'badge-slate';
                    $stLabel=['menunggu'=>'Menunggu','diproses'=>'Diproses','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'][$transaksi->status_transaksi]??$transaksi->status_transaksi;
                    @endphp
                    <span class="badge {{ $spBadge }}" style="font-size:13px;padding:6px 12px">{{ $spLabel }}</span>
                    <span class="badge {{ $stBadge }}" style="font-size:13px;padding:6px 12px">{{ $stLabel }}</span>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:20px">
                @foreach([
                    ['Pasien',          $transaksi->pasien?->user?->nama ?? '-'],
                    ['No. Rekam Medis', $transaksi->pasien?->no_rekam_medis ?? '-'],
                    ['Dokter',          $transaksi->janjiTemu?->jadwalDokter?->dokter?->nama_dokter ?? '-'],
                    ['Spesialisasi',    $transaksi->janjiTemu?->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis ?? '-'],
                    ['Metode Bayar',    ucfirst($transaksi->metode_pembayaran)],
                    ['Penjamin',        $transaksi->penjamin?->nama_penjamin ?? 'Umum'],
                ] as [$lbl,$val])
                <div style="background:#f8fafc;border-radius:10px;padding:12px">
                    <p style="font-size:10px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.05em">{{ $lbl }}</p>
                    <p style="font-size:13px;font-weight:700;color:#0f172a;margin-top:4px">{{ $val }}</p>
                </div>
                @endforeach
            </div>

            @if($transaksi->keterangan)
            <div style="margin-top:14px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px">
                <p style="font-size:11px;font-weight:700;color:#92400e;margin-bottom:4px">KETERANGAN</p>
                <p style="font-size:13px;color:#78350f">{{ $transaksi->keterangan }}</p>
            </div>
            @endif
        </div>

        {{-- Rincian Biaya --}}
        <div class="card">
            <div class="card-header"><h3>Rincian Biaya</h3></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>#</th><th>Nama Biaya</th><th style="text-align:center">Qty</th><th style="text-align:right">Harga</th><th style="text-align:right">Subtotal</th></tr></thead>
                    <tbody>
                        @forelse($transaksi->detailTransaksis as $i => $d)
                        <tr>
                            <td style="color:#94a3b8">{{ $i+1 }}</td>
                            <td style="font-weight:600">{{ $d->nama_biaya }}</td>
                            <td style="text-align:center">{{ $d->qty }}</td>
                            <td style="text-align:right">Rp{{ number_format($d->harga,0,',','.') }}</td>
                            <td style="text-align:right;font-weight:700">Rp{{ number_format($d->subtotal,0,',','.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:20px">Tidak ada detail biaya</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="border-top:2px solid #f1f5f9">
                            <td colspan="4" style="text-align:right;font-weight:700;font-size:14px;padding:12px 8px">TOTAL</td>
                            <td style="text-align:right;font-weight:800;font-size:18px;color:#16a34a;padding:12px 8px">Rp{{ number_format($transaksi->total_biaya,0,',','.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Sidebar Update Status --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px">Update Status</p>
            <form method="POST" action="{{ route('admin.transaksi.status', $transaksi) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Status Pembayaran</label>
                    <select name="status_pembayaran" class="form-input" required>
                        <option value="belum_bayar"         {{ $transaksi->status_pembayaran=='belum_bayar'?'selected':'' }}>Belum Bayar</option>
                        <option value="menunggu_verifikasi" {{ $transaksi->status_pembayaran=='menunggu_verifikasi'?'selected':'' }}>Menunggu Verifikasi</option>
                        <option value="lunas"               {{ $transaksi->status_pembayaran=='lunas'?'selected':'' }}>✅ Lunas</option>
                        <option value="gagal"               {{ $transaksi->status_pembayaran=='gagal'?'selected':'' }}>❌ Gagal</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status Transaksi</label>
                    <select name="status_transaksi" class="form-input" required>
                        <option value="menunggu"    {{ $transaksi->status_transaksi=='menunggu'?'selected':'' }}>Menunggu</option>
                        <option value="diproses"    {{ $transaksi->status_transaksi=='diproses'?'selected':'' }}>Diproses</option>
                        <option value="selesai"     {{ $transaksi->status_transaksi=='selesai'?'selected':'' }}>✅ Selesai</option>
                        <option value="dibatalkan"  {{ $transaksi->status_transaksi=='dibatalkan'?'selected':'' }}>❌ Dibatalkan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="form-input">{{ $transaksi->keterangan }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    <i class="fas fa-save"></i> Simpan Status
                </button>
            </form>
        </div>

        <div style="display:flex;flex-direction:column;gap:8px">
            <a href="{{ route('admin.transaksi') }}" class="btn btn-secondary" style="justify-content:center">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if($transaksi->pasien)
            <a href="{{ route('admin.pasien.show', $transaksi->pasien) }}" class="btn btn-secondary" style="justify-content:center">
                <i class="fas fa-user"></i> Profil Pasien
            </a>
            @endif
            <form method="POST" action="{{ route('admin.transaksi.destroy', $transaksi) }}" onsubmit="return confirm('Hapus transaksi ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">
                    <i class="fas fa-trash"></i> Hapus Transaksi
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
