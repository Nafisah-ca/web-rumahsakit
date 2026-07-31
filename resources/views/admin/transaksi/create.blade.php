@extends('layouts.admin')
@php $pageTitle = 'Buat Transaksi'; $breadcrumb = 'Admin / Transaksi / Buat'; @endphp
@section('content')

@if($errors->any())
<div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<form method="POST" action="{{ route('admin.transaksi.store') }}" id="trx-form">
@csrf
<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

{{-- Kiri: Info Transaksi --}}
<div style="display:flex;flex-direction:column;gap:20px">

    {{-- Pilih Janji Temu --}}
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px"><i class="fas fa-calendar-check" style="color:#2563eb;margin-right:6px"></i>Pilih Janji Temu</p>
        <div class="form-group">
            <label class="form-label">Janji Temu <span style="color:#ef4444">*</span></label>
            <select name="janji_temu_id" class="form-input" required id="janji-select"
                    onchange="window.location='{{ route('admin.transaksi.create') }}?janji_temu_id='+this.value">
                <option value="">— Pilih Janji Temu —</option>
                @foreach($janjiTemuList as $j)
                <option value="{{ $j->id }}" {{ (request('janji_temu_id')==$j->id || (isset($selectedJanji) && $selectedJanji->id==$j->id)) ? 'selected' : '' }}>
                    #{{ $j->id }} | {{ $j->pasien?->user?->nama }} | {{ $j->jadwalDokter?->dokter?->nama_dokter }} | {{ $j->tanggal_booking?->format('d M Y') }} | {{ ucfirst($j->status) }}
                </option>
                @endforeach
            </select>
        </div>

        @if($selectedJanji)
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:14px;margin-top:8px">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;font-size:12px">
                <div><span style="color:#94a3b8;font-weight:600">Pasien</span><br><span style="color:#0f172a;font-weight:700">{{ $selectedJanji->pasien?->user?->nama }}</span></div>
                <div><span style="color:#94a3b8;font-weight:600">No. RM</span><br><span style="color:#0f172a;font-weight:700">{{ $selectedJanji->pasien?->no_rekam_medis }}</span></div>
                <div><span style="color:#94a3b8;font-weight:600">Dokter</span><br><span style="color:#0f172a;font-weight:700">{{ $selectedJanji->jadwalDokter?->dokter?->nama_dokter }}</span></div>
                <div><span style="color:#94a3b8;font-weight:600">Tanggal</span><br><span style="color:#0f172a;font-weight:700">{{ $selectedJanji->tanggal_booking?->format('d M Y') }}</span></div>
            </div>
        </div>
        @endif
    </div>

    {{-- Rincian Biaya --}}
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px"><i class="fas fa-list" style="color:#2563eb;margin-right:6px"></i>Rincian Biaya</p>
        <table id="biaya-table" style="width:100%;border-collapse:collapse;font-size:13px">
            <thead>
                <tr style="border-bottom:1px solid #f1f5f9">
                    <th style="padding:8px 4px;text-align:left;color:#94a3b8;font-weight:600;font-size:11px">NAMA BIAYA</th>
                    <th style="padding:8px 4px;text-align:center;width:70px;color:#94a3b8;font-weight:600;font-size:11px">QTY</th>
                    <th style="padding:8px 4px;text-align:right;width:140px;color:#94a3b8;font-weight:600;font-size:11px">HARGA (Rp)</th>
                    <th style="padding:8px 4px;text-align:right;width:120px;color:#94a3b8;font-weight:600;font-size:11px">SUBTOTAL</th>
                    <th style="width:36px"></th>
                </tr>
            </thead>
            <tbody id="biaya-body">
                <tr class="biaya-row">
                    <td style="padding:6px 4px"><input type="text" name="nama_biaya[]" class="form-input" placeholder="cth: Biaya Konsultasi" required style="font-size:12px"></td>
                    <td style="padding:6px 4px"><input type="number" name="qty[]" value="1" min="1" class="form-input qty-input" style="text-align:center;font-size:12px" required></td>
                    <td style="padding:6px 4px"><input type="number" name="harga[]" value="0" min="0" class="form-input harga-input" style="text-align:right;font-size:12px" required></td>
                    <td style="padding:6px 4px;text-align:right;font-weight:600;color:#0f172a" class="subtotal-cell">Rp0</td>
                    <td style="padding:6px 4px;text-align:center"><button type="button" onclick="removeRow(this)" style="background:none;border:none;color:#ef4444;cursor:pointer;font-size:14px"><i class="fas fa-xmark"></i></button></td>
                </tr>
            </tbody>
        </table>
        <button type="button" onclick="addRow()" class="btn btn-secondary btn-sm" style="margin-top:10px;width:100%;justify-content:center">
            <i class="fas fa-plus"></i> Tambah Item
        </button>
        <div style="display:flex;justify-content:flex-end;margin-top:12px;padding-top:12px;border-top:2px solid #f1f5f9">
            <div style="text-align:right">
                <p style="font-size:11px;color:#94a3b8;font-weight:600">TOTAL BIAYA</p>
                <p style="font-size:22px;font-weight:800;color:#16a34a" id="grand-total">Rp0</p>
            </div>
        </div>
    </div>
</div>

{{-- Kanan: Info Pembayaran --}}
<div style="display:flex;flex-direction:column;gap:16px">
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px"><i class="fas fa-credit-card" style="color:#2563eb;margin-right:6px"></i>Pembayaran</p>
        <div class="form-group">
            <label class="form-label">Tanggal Transaksi <span style="color:#ef4444">*</span></label>
            <input type="datetime-local" name="tanggal_transaksi" value="{{ now()->format('Y-m-d\TH:i') }}" class="form-input" required>
        </div>
        <div class="form-group">
            <label class="form-label">Metode Pembayaran <span style="color:#ef4444">*</span></label>
            <select name="metode_pembayaran" class="form-input" required>
                <option value="tunai">💵 Tunai</option>
                <option value="transfer">🏦 Transfer Bank</option>
                <option value="qris">📱 QRIS</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Penjamin / Asuransi</label>
            <select name="penjamin_id" class="form-input">
                <option value="">— Umum / Bayar Sendiri —</option>
                @foreach($penjamins->groupBy('tipePenjamin.nama_tipe') as $tipe => $list)
                <optgroup label="{{ $tipe }}">
                    @foreach($list as $p)
                    <option value="{{ $p->id }}"
                        {{ (isset($selectedJanji) && $selectedJanji->pasien?->penjamin_id == $p->id) ? 'selected' : '' }}>
                        {{ $p->nama_penjamin }}
                    </option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
            @if(isset($selectedJanji) && $selectedJanji->pasien?->penjamin)
            <p style="font-size:11px;color:#16a34a;margin-top:4px">
                <i class="fas fa-info-circle"></i>
                Penjamin pasien: <strong>{{ $selectedJanji->pasien->penjamin->nama_penjamin }}</strong>
                @if($selectedJanji->pasien->nomor_penjamin)
                (No. {{ $selectedJanji->pasien->nomor_penjamin }})
                @endif
            </p>
            @endif
        </div>
        <div class="form-group">
            <label class="form-label">Status Pembayaran <span style="color:#ef4444">*</span></label>
            <select name="status_pembayaran" class="form-input" required>
                <option value="belum_bayar">⏳ Belum Bayar</option>
                <option value="menunggu_verifikasi">🔍 Menunggu Verifikasi</option>
                <option value="lunas">✅ Lunas</option>
                <option value="gagal">❌ Gagal</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Status Transaksi <span style="color:#ef4444">*</span></label>
            <select name="status_transaksi" class="form-input" required>
                <option value="menunggu">⏳ Menunggu</option>
                <option value="diproses">🔄 Diproses</option>
                <option value="selesai">✅ Selesai</option>
                <option value="dibatalkan">❌ Dibatalkan</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" rows="2" class="form-input" placeholder="Opsional..."></textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:14px">
        <i class="fas fa-save"></i> Simpan Transaksi
    </button>
    <a href="{{ route('admin.transaksi') }}" class="btn btn-secondary" style="justify-content:center">Batal</a>
</div>

</div>
</form>
@endsection
@push('scripts')
<script>
function formatRp(n) { return 'Rp' + parseInt(n||0).toLocaleString('id-ID'); }
function calcRow(row) {
    const qty = parseInt(row.querySelector('.qty-input').value)||0;
    const hrg = parseInt(row.querySelector('.harga-input').value)||0;
    const sub = qty * hrg;
    row.querySelector('.subtotal-cell').textContent = formatRp(sub);
    return sub;
}
function calcTotal() {
    let t = 0;
    document.querySelectorAll('.biaya-row').forEach(r => t += calcRow(r));
    document.getElementById('grand-total').textContent = formatRp(t);
}
document.addEventListener('input', e => { if (e.target.classList.contains('qty-input')||e.target.classList.contains('harga-input')) calcTotal(); });
function addRow() {
    const tr = document.querySelector('.biaya-row').cloneNode(true);
    tr.querySelectorAll('input[type=text],input[type=number]').forEach(i => { if(i.type==='number') i.value=i.name.includes('qty')?1:0; else i.value=''; });
    tr.querySelector('.subtotal-cell').textContent = 'Rp0';
    document.getElementById('biaya-body').appendChild(tr);
}
function removeRow(btn) {
    if (document.querySelectorAll('.biaya-row').length > 1) { btn.closest('tr').remove(); calcTotal(); }
}
calcTotal();
</script>
@endpush
