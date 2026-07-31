@extends('layouts.cms')
@php $pageTitle = 'Edit Informasi'; $breadcrumb = 'CMS / Informasi / Edit'; @endphp
@section('content')
<div class="max-w-3xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.informasi.update',$informasi) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul',$informasi->judul) }}" class="form-input" required maxlength="200">
                </div>
                <div class="form-group">
                    <label class="form-label">Thumbnail Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    @if($informasi->thumbnail)
                    <div style="margin-bottom:8px"><img src="{{ Storage::url($informasi->thumbnail) }}" style="height:60px;border-radius:6px"></div>
                    @endif
                    <input type="file" name="thumbnail" class="form-input" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">Gambar Utama Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    @if($informasi->gambar)
                    <div style="margin-bottom:8px"><img src="{{ Storage::url($informasi->gambar) }}" style="height:60px;border-radius:6px"></div>
                    @endif
                    <input type="file" name="gambar" class="form-input" accept="image/*">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Isi Konten <span style="color:#ef4444">*</span></label>
                    <textarea name="isi" rows="12" class="form-input" required>{{ old('isi',$informasi->isi) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="draft"   {{ old('status',$informasi->status)=='draft'?'selected':'' }}>Draft</option>
                        <option value="publish" {{ old('status',$informasi->status)=='publish'?'selected':'' }}>Publish</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('cms.informasi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
