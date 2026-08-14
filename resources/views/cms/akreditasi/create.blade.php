@extends('layouts.cms')
@php $pageTitle = 'Tambah Akreditasi'; $breadcrumb = 'CMS / Akreditasi / Tambah'; @endphp

@section('content')
<div style="max-width:540px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.akreditasi.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Akreditasi <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-input"
                       placeholder="Contoh: KARS Paripurna" required maxlength="100">
            </div>

            <div class="form-group">
                <label class="form-label">Logo <span style="font-size:11px;color:#94a3b8">(PNG/JPG/SVG, max 2MB)</span></label>
                <input type="file" name="logo" accept="image/*" class="form-input" id="logo-input">
                <img id="logo-preview" style="display:none;height:60px;width:auto;object-fit:contain;margin-top:8px;border:1px solid #e2e8f0;border-radius:8px;padding:6px;background:#f8fafc">
                <p class="form-hint">Logo akan tampil di footer website. Gunakan PNG dengan background transparan.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-input"
                       min="0" style="width:120px">
                <p class="form-hint">Angka lebih kecil tampil lebih dulu.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="aktif"    {{ old('status','aktif')==='aktif'    ? 'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status')==='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                </select>
            </div>

            <div style="display:flex;gap:8px;margin-top:24px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('cms.akreditasi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('logo-input')?.addEventListener('change', function () {
    const f = this.files[0]; if (!f) return;
    const p = document.getElementById('logo-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
});
</script>
@endpush
