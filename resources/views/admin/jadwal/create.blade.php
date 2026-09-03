@extends('layouts.admin')
@php $pageTitle = 'Tambah Jadwal'; $breadcrumb = 'Admin / Jadwal / Tambah'; @endphp
@section('content')
<div style="max-width:620px">
    <div class="card card-body">
        <p style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:4px">Tambah Jadwal Praktik</p>
        <p style="font-size:12px;color:#94a3b8;margin-bottom:20px">
            Jadwal <strong>Mingguan Berulang</strong>: pilih hari saja (tanggal kosong) — berlaku setiap minggu otomatis.<br>
            Jadwal <strong>Tanggal Spesifik</strong>: isi tanggal untuk jadwal sekali jalan.
        </p>

        <form method="POST" action="{{ route('admin.jadwal.store') }}">
            @csrf
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            {{-- Mode toggle --}}
            <div style="display:flex;gap:8px;margin-bottom:20px" id="mode-toggle">
                <button type="button" id="btn-mingguan"
                    onclick="setMode('mingguan')"
                    style="flex:1;padding:10px;border-radius:10px;font-size:12px;font-weight:700;border:2px solid #00521f;background:#00521f;color:#fff;cursor:pointer;transition:all .15s">
                    <i class="fas fa-rotate" style="margin-right:6px"></i>Mingguan Berulang
                </button>
                <button type="button" id="btn-spesifik"
                    onclick="setMode('spesifik')"
                    style="flex:1;padding:10px;border-radius:10px;font-size:12px;font-weight:700;border:2px solid #e5e7eb;background:#fff;color:#6b7280;cursor:pointer;transition:all .15s">
                    <i class="fas fa-calendar-day" style="margin-right:6px"></i>Tanggal Spesifik
                </button>
            </div>
            <input type="hidden" id="mode_value" name="_mode" value="mingguan">

            <div class="form-group">
                <label class="form-label">Dokter <span style="color:#ef4444">*</span></label>
                <select name="dokter_id" id="select_dokter" class="form-input" required>
                    <option value="">— Pilih Dokter —</option>
                    @foreach($dokters as $d)
                    <option value="{{ $d->id }}"
                            data-spesialis="{{ $d->spesialis_id }}"
                            {{ old('dokter_id')==$d->id?'selected':'' }}>
                        {{ $d->nama_dokter }} — {{ $d->spesialisasi?->nama_spesialis }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Spesialisasi <span style="color:#ef4444">*</span></label>
                <select name="spesialis_id" id="select_spesialis" class="form-input" required>
                    <option value="">— Pilih Spesialisasi —</option>
                    @foreach($spesialisasis as $s)
                    <option value="{{ $s->id }}" {{ old('spesialis_id')==$s->id?'selected':'' }}>{{ $s->nama_spesialis }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal (hanya tampil saat mode spesifik) --}}
            <div class="form-group" id="row-tanggal" style="display:none">
                <label class="form-label">Tanggal Praktek</label>
                <input type="date" name="tanggal_praktek" id="input_tanggal"
                       min="{{ today()->toDateString() }}"
                       value="{{ old('tanggal_praktek') }}"
                       class="form-input">
                <p class="form-hint">Kosongkan untuk jadwal mingguan berulang.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Hari Praktik <span style="color:#ef4444">*</span></label>
                <select name="hari" id="select_hari" class="form-input" required>
                    <option value="">— Pilih Hari —</option>
                    @foreach($hariOptions as $h)
                    <option value="{{ $h }}" {{ old('hari')==$h?'selected':'' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group">
                    <label class="form-label">Jam Mulai <span style="color:#ef4444">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai','09:00') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai <span style="color:#ef4444">*</span></label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai','14:00') }}" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kuota Pasien / Sesi <span style="color:#ef4444">*</span></label>
                <input type="number" name="kuota" value="{{ old('kuota',20) }}" class="form-input" min="1" max="200" required>
                <p class="form-hint">Jumlah maksimal pasien per hari (berlaku per tanggal, bukan total).</p>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="aktif"    {{ old('status','aktif')=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>

            <div style="display:flex;gap:10px;margin-top:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Jadwal</button>
                <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dokterSel   = document.getElementById('select_dokter');
    const spesialisSel = document.getElementById('select_spesialis');
    const tanggalInp  = document.getElementById('input_tanggal');
    const hariSel     = document.getElementById('select_hari');
    const hariMap     = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    // Auto-fill spesialisasi dari dokter
    if (dokterSel) {
        dokterSel.addEventListener('change', function () {
            const opt  = dokterSel.options[dokterSel.selectedIndex];
            const spId = opt?.dataset?.spesialis;
            if (spId && spesialisSel) spesialisSel.value = spId;
        });
    }

    // Sync hari dari tanggal (mode spesifik)
    function syncHari() {
        if (!tanggalInp?.value) return;
        const d = new Date(tanggalInp.value + 'T00:00:00');
        if (!isNaN(d)) hariSel.value = hariMap[d.getDay()];
    }
    if (tanggalInp) tanggalInp.addEventListener('change', syncHari);
});

function setMode(mode) {
    const rowTgl    = document.getElementById('row-tanggal');
    const btnMing   = document.getElementById('btn-mingguan');
    const btnSpes   = document.getElementById('btn-spesifik');
    const modeInput = document.getElementById('mode_value');

    modeInput.value = mode;

    if (mode === 'mingguan') {
        rowTgl.style.display    = 'none';
        document.getElementById('input_tanggal').value = '';
        btnMing.style.background    = '#00521f';
        btnMing.style.borderColor   = '#00521f';
        btnMing.style.color         = '#fff';
        btnSpes.style.background    = '#fff';
        btnSpes.style.borderColor   = '#e5e7eb';
        btnSpes.style.color         = '#6b7280';
    } else {
        rowTgl.style.display    = 'block';
        btnSpes.style.background    = '#00521f';
        btnSpes.style.borderColor   = '#00521f';
        btnSpes.style.color         = '#fff';
        btnMing.style.background    = '#fff';
        btnMing.style.borderColor   = '#e5e7eb';
        btnMing.style.color         = '#6b7280';
    }
}
</script>
@endsection
