@extends('layouts.cms')
@php $pageTitle = 'Edit Banner — '.$banner->nama_halaman; $breadcrumb = 'CMS / Banner Halaman / Edit'; @endphp
@section('content')

<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

    {{-- ── Form Edit ── --}}
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <form method="POST" action="{{ route('cms.page-banner.update', $banner) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Halaman</label>
                <input type="text" value="{{ $banner->nama_halaman }} ({{ $banner->page_key }})" class="form-input" disabled style="background:#f8fafc;color:#94a3b8">
            </div>

            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Label Kecil (atas judul)</label>
                    <input type="text" name="label_atas" value="{{ old('label_atas', $banner->label_atas) }}" class="form-input" maxlength="100" placeholder="cth: Layanan Medis, Penawaran Terbaik">
                    <p class="form-hint">Teks kecil berwarna terang di atas judul utama.</p>
                </div>

                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul Utama <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul', $banner->judul) }}" class="form-input" required maxlength="200" placeholder="cth: Promo & Penawaran Spesial">
                </div>

                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Sub-Judul / Deskripsi</label>
                    <textarea name="subjudul" rows="2" class="form-input" maxlength="300" placeholder="Deskripsi singkat di bawah judul...">{{ old('subjudul', $banner->subjudul) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Warna Awal Gradient</label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input type="color" name="warna_awal" value="{{ old('warna_awal', $banner->warna_awal ?? '#00521f') }}" class="form-input" style="width:60px;padding:4px;height:40px">
                        <input type="text" id="warna-awal-hex" value="{{ old('warna_awal', $banner->warna_awal ?? '#00521f') }}" class="form-input" maxlength="7" placeholder="#00521f" style="flex:1">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Warna Akhir Gradient</label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input type="color" name="warna_akhir" value="{{ old('warna_akhir', $banner->warna_akhir ?? '#00b04f') }}" class="form-input" style="width:60px;padding:4px;height:40px">
                        <input type="text" id="warna-akhir-hex" value="{{ old('warna_akhir', $banner->warna_akhir ?? '#00b04f') }}" class="form-input" maxlength="7" placeholder="#00b04f" style="flex:1">
                    </div>
                </div>

                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Gambar Background <span style="font-size:11px;color:#94a3b8">(opsional, overlay gradient tetap tampil)</span></label>
                    @if($banner->gambar)
                    <div style="margin-bottom:8px;display:flex;align-items:center;gap:10px">
                        <img src="{{ Storage::url($banner->gambar) }}" style="height:60px;border-radius:8px;object-fit:cover;border:1px solid #e2e8f0">
                        <div>
                            <p style="font-size:12px;color:#475569;font-weight:600">Gambar saat ini</p>
                            <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#dc2626;cursor:pointer;margin-top:4px">
                                <input type="checkbox" name="hapus_gambar" value="1"> Hapus gambar ini
                            </label>
                        </div>
                    </div>
                    @endif
                    <input type="file" name="gambar" class="form-input" accept="image/*" id="gambar-input">
                    <p class="form-hint">Format JPG/PNG/WebP, max 3MB. Gambar akan ditimpa gradient warna di atas.</p>
                    <img id="gambar-preview" style="display:none;max-height:80px;border-radius:8px;margin-top:8px;border:1px solid #e2e8f0">
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"    {{ old('status',$banner->status)=='aktif'   ?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status',$banner->status)=='nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('cms.page-banner') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    {{-- ── Preview ── --}}
    <div style="position:sticky;top:24px">
        <div class="card" style="overflow:hidden">
            <div class="card-header"><h3 style="font-size:13px">Preview Banner</h3></div>
            <div id="banner-preview"
                 style="padding:32px 24px;text-align:center;background:linear-gradient(135deg,{{ $banner->warna_awal ?? '#00521f' }},{{ $banner->warna_akhir ?? '#00b04f' }});transition:background .3s">
                <p id="prev-label" style="color:#a7f3d0;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px">{{ $banner->label_atas }}</p>
                <h2 id="prev-judul" style="color:#fff;font-size:20px;font-weight:800;line-height:1.3;margin-bottom:6px">{{ $banner->judul }}</h2>
                <p id="prev-sub" style="color:#d1fae5;font-size:11px;line-height:1.6">{{ $banner->subjudul }}</p>
                <p style="color:#a7f3d0;font-size:10px;margin-top:10px">Beranda › <strong style="color:#fff">{{ $banner->nama_halaman }}</strong></p>
            </div>
        </div>
        <p style="font-size:11px;color:#94a3b8;margin-top:8px;text-align:center">Preview berubah real-time saat mengetik</p>
    </div>
</div>

@endsection
@push('scripts')
<script>
// Sync color picker ↔ hex input
const colorFields = [
    { picker: document.querySelector('[name="warna_awal"]'),  hex: document.getElementById('warna-awal-hex') },
    { picker: document.querySelector('[name="warna_akhir"]'), hex: document.getElementById('warna-akhir-hex') },
];
colorFields.forEach(({ picker, hex }) => {
    picker.addEventListener('input', () => { hex.value = picker.value; updatePreview(); });
    hex.addEventListener('input', () => {
        if (/^#[0-9a-fA-F]{6}$/.test(hex.value)) { picker.value = hex.value; updatePreview(); }
    });
});

// Live preview
function updatePreview() {
    const w1 = document.querySelector('[name="warna_awal"]').value;
    const w2 = document.querySelector('[name="warna_akhir"]').value;
    document.getElementById('banner-preview').style.background = `linear-gradient(135deg,${w1},${w2})`;
    document.getElementById('prev-label').textContent  = document.querySelector('[name="label_atas"]').value;
    document.getElementById('prev-judul').textContent  = document.querySelector('[name="judul"]').value;
    document.getElementById('prev-sub').textContent    = document.querySelector('[name="subjudul"]').value;
}
['label_atas','judul','subjudul'].forEach(n => {
    document.querySelector(`[name="${n}"]`)?.addEventListener('input', updatePreview);
});

// Gambar preview
document.getElementById('gambar-input')?.addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;
    const p = document.getElementById('gambar-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
});
</script>
@endpush
