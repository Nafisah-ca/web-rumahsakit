@extends('layouts.cms')
@php $pageTitle = 'Tambah Event'; $breadcrumb = 'CMS / Event / Tambah'; @endphp
@section('content')
<div class="max-w-3xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.event.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul Event <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-input" required maxlength="200">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Gambar Utama <span style="font-size:11px;color:#94a3b8">(max 3MB)</span></label>
                    <input type="file" name="gambar" class="form-input" accept="image/*" id="gambar-input">
                    <div id="gambar-preview" style="margin-top:8px;display:none"><img id="gambar-img" style="max-height:120px;border-radius:8px"></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Event <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_event" value="{{ old('tanggal_event') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu Event <span style="color:#ef4444">*</span></label>
                    <input type="time" name="waktu_event" value="{{ old('waktu_event','08:00') }}" class="form-input" required>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi') }}" class="form-input" maxlength="255" placeholder="Nama tempat / Aula / Online...">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi <span style="color:#ef4444">*</span></label>
                    <textarea name="deskripsi" rows="6" class="form-input" required>{{ old('deskripsi') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="nonaktif" {{ old('status','nonaktif')=='nonaktif'?'selected':'' }}>Nonaktif (Draft)</option>
                        <option value="aktif"    {{ old('status')=='aktif'?'selected':'' }}>Aktif (Tampil)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Kuota Peserta <span style="font-size:11px;color:#94a3b8">(kosongkan = tidak terbatas)</span></label>
                    <input type="number" name="kuota" value="{{ old('kuota') }}" class="form-input" min="1" placeholder="Contoh: 50">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Event</button>
                <a href="{{ route('cms.event') }}" class="btn btn-secondary">Batal</a>
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
