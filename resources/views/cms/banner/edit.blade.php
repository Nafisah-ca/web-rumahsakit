@extends('layouts.cms')
@php $pageTitle = 'Edit Banner'; $breadcrumb = 'CMS / Banner / Edit'; @endphp
@section('content')
<div class="max-w-2xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.banner.update',$banner) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul Banner <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul',$banner->judul) }}" class="form-input" required maxlength="255">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi / Sub Judul</label>
                    <textarea name="deskripsi" rows="2" class="form-input">{{ old('deskripsi',$banner->deskripsi) }}</textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Gambar Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    <div id="preview-wrap" style="margin-bottom:8px">
                        @if($banner->gambar)
                        <img id="gambar-preview" src="{{ Storage::url($banner->gambar) }}" style="width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0">
                        @else
                        <img id="gambar-preview" style="display:none;width:100%;max-height:160px;object-fit:cover;border-radius:8px;border:2px solid #e2e8f0">
                        @endif
                    </div>
                    <input type="file" name="gambar" accept="image/*" class="form-input" id="gambar-input">
                    <p style="font-size:11px;color:#94a3b8;margin-top:4px">Rekomendasi: 1920×600px, max 3MB. Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"    {{ old('status',$banner->status)=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status',$banner->status)=='nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('cms.banner') }}" class="btn btn-secondary">Batal</a>
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
    p.src = URL.createObjectURL(f);
    p.style.display = 'block';
});
</script>
@endpush
