@extends('layouts.cms')
@php $pageTitle = 'Tambah Layanan'; $breadcrumb = 'CMS / Layanan / Tambah'; @endphp
@section('content')
<div class="max-w-2xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.layanan.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Layanan <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" class="form-input" required maxlength="255">
                </div>
                <div class="form-group">
                    <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(Font Awesome class)</span></label>
                    <input type="text" name="icon" value="{{ old('icon','fa-stethoscope') }}" class="form-input" placeholder="fa-stethoscope">
                    <p style="font-size:11px;color:#94a3b8;margin-top:4px">Contoh: fa-stethoscope, fa-heartbeat, fa-ambulance</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar <span style="font-size:11px;color:#94a3b8">(opsional, max 2MB)</span></label>
                    <img id="gambar-preview" style="display:none;max-height:120px;border-radius:8px;margin-bottom:8px;border:2px solid #e2e8f0">
                    <input type="file" name="gambar" class="form-input" accept="image/*" id="gambar-input">
                    <p style="font-size:11px;color:#94a3b8;margin-top:4px">Rekomendasi: format JPG/PNG, max 2MB</p>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-input" placeholder="Penjelasan singkat layanan...">{{ old('deskripsi') }}</textarea>
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
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('cms.layanan') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('gambar-input').addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;
    const p = document.getElementById('gambar-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
});
</script>
@endpush
