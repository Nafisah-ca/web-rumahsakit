@extends('layouts.cms')
@php $pageTitle = 'Tambah Informasi'; $breadcrumb = 'CMS / Informasi / Tambah'; @endphp
@section('content')
<div class="max-w-3xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.informasi.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-input" required maxlength="200">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Gambar Utama <span style="font-size:11px;color:#94a3b8">(opsional, max 3MB)</span></label>
                    <input type="file" name="gambar" class="form-input" accept="image/*" id="gambar-input">
                    <div id="gambar-preview" style="margin-top:8px;display:none"><img id="gambar-img" style="max-height:120px;border-radius:8px"></div>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Isi Konten <span style="color:#ef4444">*</span></label>
                    <textarea name="isi" rows="12" class="form-input" required>{{ old('isi') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="draft"   {{ old('status','draft')=='draft'?'selected':'' }}>Draft</option>
                        <option value="publish" {{ old('status')=='publish'?'selected':'' }}>Publish</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('cms.informasi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('gambar-input')?.addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;
    document.getElementById('gambar-img').src = URL.createObjectURL(f);
    document.getElementById('gambar-preview').style.display = 'block';
});
</script>
@endpush
