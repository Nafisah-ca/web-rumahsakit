@extends('layouts.cms')
@php $pageTitle = 'Tulis Artikel'; $breadcrumb = 'CMS / Artikel / Tulis'; @endphp
@section('content')
<form method="POST" action="{{ route('cms.artikel.store') }}" enctype="multipart/form-data">
@csrf
@if($errors->any())
<div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div style="display:grid;grid-template-columns:1fr 300px;gap:24px">
    {{-- Editor --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card card-body">
            <div class="form-group">
                <label class="form-label">Judul Artikel <span style="color:#ef4444">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-input" required maxlength="200"
                    style="font-size:16px;font-weight:600" placeholder="Tulis judul artikel menarik...">
            </div>
            <div class="form-group">
                <label class="form-label">Isi / Konten Artikel <span style="color:#ef4444">*</span></label>
                <textarea name="isi" rows="20" class="form-input" required
                    placeholder="Tulis konten lengkap artikel di sini...">{{ old('isi') }}</textarea>
            </div>
        </div>
    </div>
    {{-- Sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px">Pengaturan</p>
            <div class="form-group">
                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="draft"   {{ old('status','draft')=='draft'?'selected':'' }}>Draft</option>
                    <option value="publish" {{ old('status')=='publish'?'selected':'' }}>Publish</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori <span style="color:#ef4444">*</span></label>
                <select name="kategori_artikel_id" class="form-input" required>
                    <option value="">— Pilih Kategori —</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_artikel_id')==$k->id?'selected':'' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Gambar Utama <span style="font-size:11px;color:#94a3b8">(max 3MB)</span></label>
                <input type="file" name="gambar" accept="image/*" class="form-input" id="gambar-input">
                <img id="gambar-preview" style="display:none;width:100%;max-height:120px;object-fit:cover;border-radius:8px;margin-top:8px">
            </div>
            <div class="form-group">
                <label class="form-label">Thumbnail <span style="font-size:11px;color:#94a3b8">(opsional, max 2MB)</span></label>
                <input type="file" name="thumbnail" accept="image/*" class="form-input" id="thumb-input">
                <img id="thumb-preview" style="display:none;width:100%;max-height:80px;object-fit:cover;border-radius:6px;margin-top:8px">
            </div>
        </div>
        <div style="display:flex;gap:8px">
            <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('cms.artikel') }}" class="btn btn-secondary">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script>
function previewImg(inputId, previewId) {
    document.getElementById(inputId).addEventListener('change', function() {
        const f = this.files[0]; if (!f) return;
        const p = document.getElementById(previewId);
        p.src = URL.createObjectURL(f); p.style.display = 'block';
    });
}
previewImg('gambar-input','gambar-preview');
previewImg('thumb-input','thumb-preview');
</script>
@endpush
