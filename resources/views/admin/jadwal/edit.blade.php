@extends('layouts.admin')
@php $pageTitle = 'Edit Jadwal'; $breadcrumb = 'Admin / Jadwal / Edit'; @endphp
@section('content')
<div style="max-width:560px">
    <div class="card card-body">
        <p style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:20px">Edit Jadwal Praktik</p>
        <form method="POST" action="{{ route('admin.jadwal.update', $jadwalDokter) }}">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

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

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                <div class="form-group">
                    <label class="form-label">Tanggal Praktek <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_praktek" id="input_tanggal" 
                           value="{{ old('tanggal_praktek', $jadwalDokter->tanggal_praktek?->format('Y-m-d')) }}" 
                           class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Hari Praktik <span style="color:#ef4444">*</span></label>
                    <select name="hari" id="select_hari" class="form-input" required>
                        @foreach($hariOptions as $h)
                        <option value="{{ $h }}" {{ old('hari',$jadwalDokter->hari)==$h?'selected':'' }}>{{ $h }}</option>
                        @endforeach
                    </select>
                </div>
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
    const dokterSel   = document.getElementById('select_dokter');
    const spesialisSel = document.getElementById('select_spesialis');
    const tanggalInp  = document.getElementById('input_tanggal');
    const hariSel     = document.getElementById('select_hari');

    const hariMap = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    function syncHariFromDate() {
        if (!tanggalInp.value) return;
        const d = new Date(tanggalInp.value + 'T00:00:00');
        if (!isNaN(d.getTime())) {
            const hariNama = hariMap[d.getDay()];
            if (hariNama) {
                hariSel.value = hariNama;
            }
        }
    }

    if (dokterSel) {
        dokterSel.addEventListener('change', function () {
            const opt = dokterSel.options[dokterSel.selectedIndex];
            const spId = opt?.dataset?.spesialis;
            if (spId && spesialisSel) {
                spesialisSel.value = spId;
            }
        });
    }

    if (tanggalInp) {
        tanggalInp.addEventListener('change', syncHariFromDate);
    }
});
</script>
@endsection
