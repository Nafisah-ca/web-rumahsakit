@extends('layouts.cms')
@php $pageTitle = 'Tambah Promo'; $breadcrumb = 'CMS / Promo / Tambah'; @endphp
@section('content')
<div class="max-w-3xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.promo.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul Promo <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-input" required maxlength="200">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Gambar Utama <span style="font-size:11px;color:#94a3b8">(max 3MB)</span></label>
                    <input type="file" name="gambar" class="form-input" accept="image/*" id="gambar-input">
                    <div id="gambar-preview" style="margin-top:8px;display:none"><img id="gambar-img" style="max-height:120px;border-radius:8px"></div>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi <span style="color:#ef4444">*</span></label>
                    <textarea name="deskripsi" rows="6" class="form-input" required>{{ old('deskripsi') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="nonaktif" {{ old('status','nonaktif')=='nonaktif'?'selected':'' }}>Nonaktif (Draft)</option>
                        <option value="aktif"    {{ old('status')=='aktif'?'selected':'' }}>Aktif (Tampil)</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Promo</button>
                <a href="{{ route('cms.promo') }}" class="btn btn-secondary">Batal</a>
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
