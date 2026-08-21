@extends('layouts.cms')
@php $pageTitle = 'Tambah Penghargaan'; $breadcrumb = 'CMS / Penghargaan / Tambah'; @endphp

@section('content')
<div style="max-width:580px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.akreditasi.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label">Nama Penghargaan / Lembaga <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" class="form-input"
                       placeholder="Contoh: KARS Paripurna" required maxlength="100">
            </div>

            {{-- Tahun --}}
            <div class="form-group">
                <label class="form-label">Tahun Perolehan</label>
                <input type="number" name="tahun" value="{{ old('tahun') }}" class="form-input"
                       placeholder="Contoh: 2024" min="1900" max="{{ date('Y') + 1 }}" style="width:140px">
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" class="form-input"
                       placeholder="Contoh: Akreditasi rumah sakit tingkat paripurna" maxlength="200">
                <p class="form-hint">Opsional. Maksimal 200 karakter.</p>
            </div>

            {{-- Logo --}}
            <div class="form-group">
                <label class="form-label">Logo Lembaga <span style="font-size:11px;color:#94a3b8">(PNG/JPG/SVG/WEBP, max 2MB)</span></label>
                <input type="file" name="logo" accept="image/*" class="form-input" id="logo-input">
                <div id="logo-preview-wrap" style="display:none;margin-top:10px">
                    <img id="logo-preview"
                         style="height:70px;width:auto;max-width:180px;object-fit:contain;
                                border:1px solid #e2e8f0;border-radius:8px;padding:8px;background:#f8fafc">
                </div>
                <p class="form-hint">
                    Gunakan logo resmi dari lembaga/instansi (misalnya logo BPJS, KARS, ISO, dll.)
                    dalam format PNG dengan background transparan agar hasil terbaik.
                </p>
            </div>

            {{-- Urutan --}}
            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-input"
                       min="0" style="width:120px">
                <p class="form-hint">Angka lebih kecil tampil lebih dulu.</p>
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="aktif"    {{ old('status','aktif')==='aktif'    ? 'selected':'' }}>Aktif – tampil di website</option>
                    <option value="nonaktif" {{ old('status')==='nonaktif' ? 'selected':'' }}>Nonaktif – disembunyikan</option>
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
    const f = this.files[0];
    if (!f) return;
    const p    = document.getElementById('logo-preview');
    const wrap = document.getElementById('logo-preview-wrap');
    p.src = URL.createObjectURL(f);
    wrap.style.display = 'block';
});
</script>
@endpush
