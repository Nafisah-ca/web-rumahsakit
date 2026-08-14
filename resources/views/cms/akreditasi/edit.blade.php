@extends('layouts.cms')
@php $pageTitle = 'Edit Akreditasi'; $breadcrumb = 'CMS / Akreditasi / Edit'; @endphp

@section('content')
<div style="max-width:540px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.akreditasi.update', $akreditasi) }}"
              enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Akreditasi <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $akreditasi->nama) }}"
                       class="form-input" required maxlength="100">
            </div>

            <div class="form-group">
                <label class="form-label">Logo</label>
                @if($akreditasi->logo)
                <div style="margin-bottom:10px;display:flex;align-items:center;gap:12px">
                    <img src="{{ Storage::url($akreditasi->logo) }}"
                         alt="{{ $akreditasi->nama }}"
                         style="height:56px;width:auto;object-fit:contain;border:1px solid #e2e8f0;border-radius:8px;padding:6px;background:#f8fafc">
                    <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#ef4444;cursor:pointer">
                        <input type="checkbox" name="hapus_logo" value="1"> Hapus logo ini
                    </label>
                </div>
                @endif
                <input type="file" name="logo" accept="image/*" class="form-input" id="logo-input">
                <img id="logo-preview" style="display:none;height:60px;width:auto;object-fit:contain;margin-top:8px;border:1px solid #e2e8f0;border-radius:8px;padding:6px;background:#f8fafc">
                <p class="form-hint">Kosongkan jika tidak ingin mengubah logo.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', $akreditasi->urutan) }}"
                       class="form-input" min="0" style="width:120px">
                <p class="form-hint">Angka lebih kecil tampil lebih dulu.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="aktif"    {{ old('status',$akreditasi->status)==='aktif'    ? 'selected':'' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status',$akreditasi->status)==='nonaktif' ? 'selected':'' }}>Nonaktif</option>
                </select>
            </div>

            <div style="display:flex;gap:8px;margin-top:24px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                    <i class="fas fa-save"></i> Simpan Perubahan
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
