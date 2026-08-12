@extends('layouts.cms')
@php $pageTitle = 'Edit Artikel'; $breadcrumb = 'CMS / Artikel / Edit'; @endphp
@section('content')
<form method="POST" action="{{ route('cms.artikel.update',$artikel) }}" enctype="multipart/form-data">
@csrf @method('PUT')
@if($errors->any())
<div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div style="display:grid;grid-template-columns:1fr 300px;gap:24px">
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card card-body">
            <div class="form-group">
                <label class="form-label">Judul Artikel <span style="color:#ef4444">*</span></label>
                <input type="text" name="judul" value="{{ old('judul',$artikel->judul) }}" class="form-input" required maxlength="200" style="font-size:16px;font-weight:600">
            </div>
            <div class="form-group">
                <label class="form-label">Isi / Konten Artikel <span style="color:#ef4444">*</span></label>
                <textarea name="isi" id="artikel-editor" class="form-input" required>{{ old('isi',$artikel->isi) }}</textarea>
            </div>
        </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px">Pengaturan</p>
            <div class="form-group">
                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="draft"   {{ old('status',$artikel->status)=='draft'?'selected':'' }}>Draft</option>
                    <option value="publish" {{ old('status',$artikel->status)=='publish'?'selected':'' }}>Publish</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori <span style="color:#ef4444">*</span></label>
                <select name="kategori_artikel_id" class="form-input" required>
                    <option value="">— Pilih Kategori —</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_artikel_id',$artikel->kategori_artikel_id)==$k->id?'selected':'' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Gambar Utama Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                @if($artikel->gambar)
                <div style="margin-bottom:8px"><img src="{{ Storage::url($artikel->gambar) }}" style="width:100%;max-height:100px;object-fit:cover;border-radius:8px"></div>
                @endif
                <input type="file" name="gambar" accept="image/*" class="form-input">
            </div>
            <div style="padding:12px;background:#f8fafc;border-radius:10px;font-size:12px;color:#64748b">
                <p><span style="color:#94a3b8">Dibuat:</span> {{ $artikel->created_tm?->format('d M Y H:i') ?? '-' }}</p>
                @if($artikel->updated_by)
                <p style="margin-top:4px"><span style="color:#94a3b8">Diperbarui oleh:</span>
                    {{ \App\Models\User::find($artikel->updated_by)?->nama ?? 'ID '.$artikel->updated_by }}
                </p>
                @endif
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
</script>
@endpush
