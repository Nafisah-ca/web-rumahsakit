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
                <textarea name="isi" id="artikel-editor" class="form-input" required>{{ old('isi') }}</textarea>
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
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#artikel-editor',
    license_key: 'gpl',
    height: 500,
    menubar: true,
    base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6.8.3',
    suffix: '.min',
    plugins: [
        'advlist','autolink','lists','link','charmap','preview','anchor',
        'searchreplace','visualblocks','code','fullscreen',
        'table','help','wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
             'bold italic underline strikethrough | ' +
             'alignleft aligncenter alignright alignjustify | ' +
             'bullist numlist | outdent indent | ' +
             'link table | removeformat | code fullscreen | help',
    content_style: [
        'body {',
        '  font-family: "Plus Jakarta Sans", sans-serif;',
        '  font-size: 14px;',
        '  line-height: 1.8;',
        '  color: #334155;',
        '  padding: 16px;',
        '}'
    ].join(''),
    branding: false,
    promotion: false,
    setup: function (editor) {
        editor.on('change input', function () { editor.save(); });
    }
});

document.getElementById('gambar-input')?.addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;
    const p = document.getElementById('gambar-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
});
</script>
@endpush
