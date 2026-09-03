@extends('layouts.admin')
@php $pageTitle = 'Edit Jadwal'; $breadcrumb = 'Admin / Jadwal / Edit'; @endphp
@section('content')
<div style="max-width:560px">
    <div class="card card-body">
        <p style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:4px">Edit Jadwal Praktik</p>
        <p style="font-size:12px;color:#94a3b8;margin-bottom:16px">
            Jadwal <strong>Mingguan Berulang</strong>: pilih hari saja (tanggal kosong) — berlaku setiap minggu otomatis.<br>
            Jadwal <strong>Tanggal Spesifik</strong>: isi tanggal untuk jadwal sekali jalan.
        </p>
        <form method="POST" action="{{ route('admin.jadwal.update', $jadwalDokter) }}">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            {{-- Toggle Mingguan / Spesifik --}}
            @php $isRecurring = !$jadwalDokter->tanggal_praktek; @endphp
            <div style="display:flex;gap:8px;margin-bottom:16px">
                <button type="button" id="btn-mingguan" onclick="setMode('mingguan')"
                    style="flex:1;padding:10px;border-radius:10px;font-size:12px;font-weight:700;border:2px solid;cursor:pointer;transition:all .15s;
                           background:{{ $isRecurring ? '#00521f' : '#fff' }};
                           border-color:{{ $isRecurring ? '#00521f' : '#e5e7eb' }};
                           color:{{ $isRecurring ? '#fff' : '#6b7280' }}">
                    <i class="fas fa-rotate" style="margin-right:6px"></i>Mingguan Berulang
                </button>
                <button type="button" id="btn-spesifik" onclick="setMode('spesifik')"
                    style="flex:1;padding:10px;border-radius:10px;font-size:12px;font-weight:700;border:2px solid;cursor:pointer;transition:all .15s;
                           background:{{ !$isRecurring ? '#00521f' : '#fff' }};
                           border-color:{{ !$isRecurring ? '#00521f' : '#e5e7eb' }};
                           color:{{ !$isRecurring ? '#fff' : '#6b7280' }}">
                    <i class="fas fa-calendar-day" style="margin-right:6px"></i>Tanggal Spesifik
                </button>
            </div>

            <div class="form-group">
                <label class="form-label">Dokter <span style="color:#ef4444">*</span></label>
                <select name="dokter_id" id="select_dokter" class="form-input" required>
                    @foreach($dokters as $d)
                    <option value="{{ $d->id }}"
                            data-spesialis="{{ $d->spesialis_id }}"
                            {{ old('dokter_id',$jadwalDokter->dokter_id)==$d->id?'selected':'' }}>
                        {{ $d->nama_dokter }} — {{ $d->spesialisasi?->nama_spesialis }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Spesialisasi <span style="color:#ef4444">*</span></label>
                <select name="spesialis_id" id="select_spesialis" class="form-input" required>
                    @foreach($spesialisasis as $s)
                    <option value="{{ $s->id }}" {{ old('spesialis_id',$jadwalDokter->spesialis_id)==$s->id?'selected':'' }}>{{ $s->nama_spesialis }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal (hanya tampil saat mode spesifik) --}}
            <div class="form-group" id="row-tanggal" style="display:{{ $isRecurring ? 'none' : 'block' }}">
                <label class="form-label">Tanggal Praktek</label>
                <input type="date" name="tanggal_praktek" id="input_tanggal"
                       value="{{ old('tanggal_praktek', $jadwalDokter->tanggal_praktek?->format('Y-m-d')) }}"
                       class="form-input">
                <p class="form-hint">Kosongkan untuk jadwal mingguan berulang.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Hari Praktik <span style="color:#ef4444">*</span></label>
                <select name="hari" id="select_hari" class="form-input" required>
                    @foreach($hariOptions as $h)
                    <option value="{{ $h }}" {{ old('hari',$jadwalDokter->hari)==$h?'selected':'' }}>{{ $h }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group">
                    <label class="form-label">Jam Mulai <span style="color:#ef4444">*</span></label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', substr($jadwalDokter->jam_mulai??'',0,5)) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jam Selesai <span style="color:#ef4444">*</span></label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', substr($jadwalDokter->jam_selesai??'',0,5)) }}" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Kuota Pasien <span style="color:#ef4444">*</span></label>
                <input type="number" name="kuota" value="{{ old('kuota',$jadwalDokter->kuota) }}" class="form-input" min="1" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="aktif"    {{ old('status',$jadwalDokter->status)=='aktif'?'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status',$jadwalDokter->status)=='nonaktif'?'selected':'' }}>Nonaktif</option>
                </select>
            </div>

            <div style="display:flex;gap:10px;margin-top:12px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dokterSel    = document.getElementById('select_dokter');
    const spesialisSel = document.getElementById('select_spesialis');
    const tanggalInp   = document.getElementById('input_tanggal');
    const hariSel      = document.getElementById('select_hari');
    const hariMap      = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    // Sync hari dari tanggal (mode spesifik)
    function syncHari() {
        if (!tanggalInp?.value) return;
        const d = new Date(tanggalInp.value + 'T00:00:00');
        if (!isNaN(d)) hariSel.value = hariMap[d.getDay()];
    }
    if (tanggalInp) tanggalInp.addEventListener('change', syncHari);

    // Sync spesialisasi dari dokter
    if (dokterSel) {
        dokterSel.addEventListener('change', function () {
            const spId = dokterSel.options[dokterSel.selectedIndex]?.dataset?.spesialis;
            if (spId && spesialisSel) spesialisSel.value = spId;
        });
    }
});

function setMode(mode) {
    const rowTgl  = document.getElementById('row-tanggal');
    const btnMing = document.getElementById('btn-mingguan');
    const btnSpes = document.getElementById('btn-spesifik');
    const aktif   = '#00521f', nonAktif = '#fff', aktifBorder = '#00521f', nonBorder = '#e5e7eb';

    if (mode === 'mingguan') {
        rowTgl.style.display = 'none';
        document.getElementById('input_tanggal').value = '';
        btnMing.style.background = aktif;   btnMing.style.borderColor = aktifBorder; btnMing.style.color = '#fff';
        btnSpes.style.background = nonAktif; btnSpes.style.borderColor = nonBorder;  btnSpes.style.color = '#6b7280';
    } else {
        rowTgl.style.display = 'block';
        btnSpes.style.background = aktif;   btnSpes.style.borderColor = aktifBorder; btnSpes.style.color = '#fff';
        btnMing.style.background = nonAktif; btnMing.style.borderColor = nonBorder;  btnMing.style.color = '#6b7280';
    }
}
</script>
@endsection
