@extends('layouts.cms')
@php $pageTitle = 'Edit Penghargaan'; $breadcrumb = 'CMS / Penghargaan / Edit'; @endphp

@section('content')
<div style="max-width:580px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.akreditasi.update', $akreditasi) }}"
              enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Nama --}}
            <div class="form-group">
                <label class="form-label">Nama Penghargaan / Lembaga <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama', $akreditasi->nama) }}"
                       class="form-input" required maxlength="100">
            </div>

            {{-- Tahun --}}
            <div class="form-group">
                <label class="form-label">Tahun Perolehan</label>
                <input type="number" name="tahun" value="{{ old('tahun', $akreditasi->tahun) }}"
                       class="form-input" placeholder="Contoh: 2024"
                       min="1900" max="{{ date('Y') + 1 }}" style="width:140px">
            </div>

            {{-- Deskripsi --}}
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <input type="text" name="deskripsi" value="{{ old('deskripsi', $akreditasi->deskripsi) }}"
                       class="form-input" maxlength="200">
                <p class="form-hint">Opsional. Maksimal 200 karakter.</p>
            </div>

            {{-- Logo --}}
            <div class="form-group">
                <label class="form-label">Logo Lembaga</label>

                @if($akreditasi->logo)
                <div style="margin-bottom:12px;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;display:inline-flex;align-items:center;gap:14px">
                    <img src="{{ $akreditasi->logo_url }}"
                         alt="{{ $akreditasi->nama }}"
                         style="height:60px;width:auto;max-width:160px;object-fit:contain;">
                    <div>
                        <p style="font-size:12px;font-weight:600;color:#334155;margin-bottom:4px">Logo saat ini</p>
                        <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#ef4444;cursor:pointer">
                            <input type="checkbox" name="hapus_logo" value="1"> Hapus logo ini
                        </label>
                    </div>
                </div>
                @endif

                <input type="file" name="logo" accept="image/*" class="form-input" id="logo-input">
                <div id="logo-preview-wrap" style="display:none;margin-top:10px">
                    <img id="logo-preview"
                         style="height:70px;width:auto;max-width:180px;object-fit:contain;
                                border:1px solid #e2e8f0;border-radius:8px;padding:8px;background:#f8fafc">
                </div>
                <p class="form-hint">Kosongkan jika tidak ingin mengubah logo. Gunakan PNG transparan untuk hasil terbaik.</p>
            </div>

            {{-- Urutan --}}
            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', $akreditasi->urutan) }}"
                       class="form-input" min="0" style="width:120px">
                <p class="form-hint">Angka lebih kecil tampil lebih dulu.</p>
            </div>

            {{-- Status --}}
            <div class="form-group">
                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                <select name="status" class="form-input" required>
                    <option value="aktif"    {{ old('status',$akreditasi->status)==='aktif'    ? 'selected':'' }}>Aktif – tampil di website</option>
                    <option value="nonaktif" {{ old('status',$akreditasi->status)==='nonaktif' ? 'selected':'' }}>Nonaktif – disembunyikan</option>
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
    const f = this.files[0];
    if (!f) return;
    const p    = document.getElementById('logo-preview');
    const wrap = document.getElementById('logo-preview-wrap');
    p.src = URL.createObjectURL(f);
    wrap.style.display = 'block';
});
</script>
@endpush
