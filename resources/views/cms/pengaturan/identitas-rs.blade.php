@extends('layouts.cms')
@php $pageTitle = 'Identitas Rumah Sakit'; $breadcrumb = 'CMS / Pengaturan / Identitas RS'; @endphp

@section('content')
<div style="max-width:720px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.identitas-rs.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Rumah Sakit <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_rumahsakit"
                       value="{{ old('nama_rumahsakit', $setting->nama_rumahsakit) }}"
                       class="form-input" required maxlength="150">
                <p class="form-hint">Tampil di navbar, halaman Tentang Kami, footer, dan tab browser.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Motto / Tagline</label>
                <input type="text" name="motto"
                       value="{{ old('motto', $setting->motto) }}"
                       class="form-input" maxlength="255"
                       placeholder="Contoh: Melayani dengan Kasih Sayang">
                <p class="form-hint">Tampil di bawah nama RS di navbar dan halaman Tentang Kami.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Tentang Kami</label>
                <textarea name="tentang_kami" rows="4" class="form-input">{{ old('tentang_kami', $setting->tentang_kami) }}</textarea>
                <p class="form-hint">Deskripsi singkat di homepage dan halaman Tentang Kami.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Visi</label>
                <textarea name="visi" rows="2" class="form-input">{{ old('visi', $setting->visi) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Misi</label>
                <textarea name="misi" rows="3" class="form-input">{{ old('misi', $setting->misi) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Sejarah</label>
                <textarea name="sejarah" rows="3" class="form-input">{{ old('sejarah', $setting->sejarah) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Sambutan Direktur</label>
                <textarea name="sambutan_direktur" rows="5" class="form-input">{{ old('sambutan_direktur', $setting->sambutan_direktur) }}</textarea>
                <p class="form-hint">Tampil di section "Sambutan Direktur" pada halaman Tentang Kami.</p>
            </div>

            {{-- ── INFORMASI DIREKTUR ──────────────────────────── --}}
            <div style="border-top:1px solid #f1f5f9;margin:8px 0 20px;padding-top:20px">
                <p style="font-size:12px;font-weight:700;color:#475569;margin-bottom:4px;
                           text-transform:uppercase;letter-spacing:.06em">
                    <i class="fas fa-user-tie" style="color:#2563eb;margin-right:6px"></i>Informasi Direktur
                </p>
                <p style="font-size:11px;color:#94a3b8;margin-bottom:16px">Tampil di sebelah kiri teks sambutan pada halaman Tentang Kami.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Direktur</label>
                <input type="text" name="nama_direktur"
                       value="{{ old('nama_direktur', $setting->nama_direktur ?? '') }}"
                       class="form-input" maxlength="150"
                       placeholder="Contoh: dr. Budi Santoso, Sp.OG">
                <p class="form-hint">Nama dan gelar akan tampil di bawah foto direktur.</p>
            </div>

            <div class="form-group" style="margin-bottom:28px">
                <label class="form-label">
                    Foto Direktur
                    <span style="font-size:11px;color:#94a3b8">(JPG/PNG/WEBP, maks 2MB)</span>
                </label>

                @if($setting->foto_direktur ?? null)
                <div style="margin-bottom:14px;display:flex;align-items:center;gap:16px;
                            padding:14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px">
                    <img src="{{ Storage::url($setting->foto_direktur) }}"
                         alt="Foto Direktur"
                         style="width:80px;height:80px;object-fit:cover;object-position:top;
                                border-radius:50%;border:3px solid #e2e8f0;flex-shrink:0">
                    <div>
                        <p style="font-size:12px;font-weight:600;color:#334155;margin-bottom:6px">Foto saat ini</p>
                        <label style="display:inline-flex;align-items:center;gap:6px;
                                      font-size:12px;color:#ef4444;cursor:pointer">
                            <input type="checkbox" name="hapus_foto_direktur" value="1">
                            Hapus foto ini
                        </label>
                    </div>
                </div>
                @endif

                <input type="file" name="foto_direktur" accept="image/*" class="form-input" id="foto-dir-input">
                <div id="foto-dir-preview-wrap" style="display:none;margin-top:10px">
                    <img id="foto-dir-preview"
                         style="width:80px;height:80px;object-fit:cover;object-position:top;
                                border-radius:50%;border:3px solid #e2e8f0">
                </div>
                <p class="form-hint">Foto potret wajah rasio 1:1. Kosongkan jika tidak ingin mengubah.</p>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('foto-dir-input')?.addEventListener('change', function () {
    const f = this.files[0];
    if (!f) return;
    const preview = document.getElementById('foto-dir-preview');
    const wrap    = document.getElementById('foto-dir-preview-wrap');
    preview.src = URL.createObjectURL(f);
    wrap.style.display = 'block';
});
</script>
@endpush
