@extends('layouts.admin')
@php $pageTitle = 'Edit Dokter'; $breadcrumb = 'Admin / Dokter / Edit'; @endphp
@section('content')
<div style="max-width:620px">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Edit: <span style="color:#16a34a">{{ $dokter->nama_dokter }}</span></p>
        <form method="POST" action="{{ route('admin.dokter.update', $dokter) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Dokter <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_dokter" value="{{ old('nama_dokter',$dokter->nama_dokter) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Spesialisasi <span style="color:#ef4444">*</span></label>
                    <select name="spesialis_id" class="form-input" required>
                        @foreach($spesialisasis as $sp)
                        <option value="{{ $sp->id }}" {{ old('spesialis_id',$dokter->spesialis_id)==$sp->id?'selected':'' }}>{{ $sp->nama_spesialis }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tipe Dokter <span style="color:#ef4444">*</span></label>
                    <select name="tipe_dokter" class="form-input" required>
                        <option value="spesialis" {{ old('tipe_dokter',$dokter->tipe_dokter)=='spesialis'?'selected':'' }}>Dokter Spesialis</option>
                        <option value="umum"      {{ old('tipe_dokter',$dokter->tipe_dokter)=='umum'?'selected':'' }}>Dokter Umum</option>
                        <option value="lainnya"   {{ old('tipe_dokter',$dokter->tipe_dokter)=='lainnya'?'selected':'' }}>Dokter Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">SIP <span style="color:#ef4444">*</span></label>
                    <input type="text" name="sip" value="{{ old('sip',$dokter->sip) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email',$dokter->email) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP <span style="color:#ef4444">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp',$dokter->no_hp) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Profil Dokter <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    @if($dokter->foto)
                    <div style="margin-bottom:8px">
                        @php $fotoPreview = str_starts_with($dokter->foto,'images/') ? asset($dokter->foto) : Storage::url($dokter->foto); @endphp
                        <img src="{{ $fotoPreview }}" style="width:56px;height:56px;object-fit:cover;border-radius:50%;border:2px solid #e2e8f0">
                    </div>
                    @endif
                    <input type="file" name="foto" accept="image/*" class="form-input">
                    <p class="form-hint">Foto profil dokter (tampil di circle card)</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Foto Banner Card <span style="font-size:11px;color:#94a3b8">(opsional, max 3MB)</span></label>
                    @if($dokter->foto_banner)
                    <div style="margin-bottom:8px">
                        <img src="{{ Storage::url($dokter->foto_banner) }}" style="width:120px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0">
                        <p style="font-size:10px;color:#94a3b8;margin-top:4px">Banner saat ini</p>
                    </div>
                    @endif
                    <input type="file" name="foto_banner" accept="image/*" class="form-input">
                    <p class="form-hint">Foto background banner di atas card dokter. Jika kosong, pakai gambar default RS.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"    {{ old('status',$dokter->status)==='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status',$dokter->status)==='nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.dokter') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
