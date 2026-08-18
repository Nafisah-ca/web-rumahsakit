@extends('layouts.admin')
@php $pageTitle = 'Tambah Dokter'; $breadcrumb = 'Admin / Dokter / Tambah'; @endphp
@section('content')
<div style="max-width:620px">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Form Tambah Dokter</p>
        <form method="POST" action="{{ route('admin.dokter.store') }}" enctype="multipart/form-data">
            @csrf
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Dokter <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_dokter" value="{{ old('nama_dokter') }}" class="form-input" required placeholder="dr. Nama Lengkap, Sp.XX">
                </div>
                <div class="form-group">
                    <label class="form-label">Spesialisasi <span style="color:#ef4444">*</span></label>
                    <select name="spesialis_id" class="form-input" required>
                        <option value="">— Pilih Spesialisasi —</option>
                        @foreach($spesialisasis as $sp)
                        <option value="{{ $sp->id }}" {{ old('spesialis_id')==$sp->id?'selected':'' }}>{{ $sp->nama_spesialis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Dokter <span style="color:#ef4444">*</span></label>
                    <select name="tipe_dokter" class="form-input" required>
                        <option value="spesialis" {{ old('tipe_dokter','spesialis')=='spesialis'?'selected':'' }}>Dokter Spesialis</option>
                        <option value="umum"      {{ old('tipe_dokter')=='umum'?'selected':'' }}>Dokter Umum</option>
                        <option value="lainnya"   {{ old('tipe_dokter')=='lainnya'?'selected':'' }}>Dokter Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SIP (No. Izin Praktik) <span style="color:#ef4444">*</span></label>
                    <input type="text" name="sip" value="{{ old('sip') }}" class="form-input" required placeholder="SIP-XXX-2025">
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP <span style="color:#ef4444">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-input" required placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Profil Dokter <span style="font-size:11px;color:#94a3b8">(opsional, max 2MB)</span></label>
                    <input type="file" name="foto" accept="image/*" class="form-input" id="foto-input">
                    <img id="foto-preview" style="display:none;width:56px;height:56px;object-fit:cover;border-radius:50%;margin-top:8px;border:2px solid #e2e8f0">
                    <p class="form-hint">Foto profil dokter (tampil di circle card)</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Banner Card <span style="font-size:11px;color:#94a3b8">(opsional, max 3MB)</span></label>
                    <input type="file" name="foto_banner" accept="image/*" class="form-input" id="banner-input">
                    <img id="banner-preview" style="display:none;width:120px;height:60px;object-fit:cover;border-radius:8px;margin-top:8px">
                    <p class="form-hint">Foto background banner card. Jika kosong, pakai gambar default RS.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"    {{ old('status','aktif')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Dokter</button>
                <a href="{{ route('admin.dokter') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('foto-input').addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;
    const p = document.getElementById('foto-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
});
</script>
@endpush
