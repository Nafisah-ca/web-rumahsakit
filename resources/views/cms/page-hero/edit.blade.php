@extends('layouts.cms')
@php
    $pageNama  = \App\Models\PageHero::pageLabel($key);
    $pageTitle = 'Edit Banner – '.$pageNama;
    $breadcrumb= 'CMS / Banner Halaman / '.$pageNama;
@endphp
@section('content')

<form method="POST" action="{{ route('cms.page-hero.update', $key) }}" enctype="multipart/form-data">
@csrf

@if($errors->any())
<div class="form-error" style="margin-bottom:16px">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px">

    {{-- KIRI: form fields --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Preview live --}}
        <div id="hero-preview"
             class="{{ $hero->gambar ? '' : '' }}"
             style="border-radius:16px;overflow:hidden;min-height:180px;position:relative;display:flex;align-items:center;justify-content:center;
                    @if(!$hero->gambar) background:linear-gradient(135deg,{{ $hero->warna_dari ?? '#00521f' }},{{ $hero->warna_ke ?? '#00b04f' }}); @endif">

            {{-- Gambar background layer --}}
            @if($hero->gambar)
            <div id="bg-image" style="position:absolute;inset:0;z-index:0;background:url('{{ Storage::url($hero->gambar) }}') center/cover no-repeat"></div>
            @else
            <div id="bg-image" style="position:absolute;inset:0;z-index:0;display:none"></div>
            @endif

            {{-- Overlay gradient --}}
            <div id="bg-overlay" style="position:absolute;inset:0;z-index:1;
                 background:linear-gradient(135deg,{{ $hero->warna_dari ?? '#00521f' }}{{ $hero->gambar ? 'a0' : '' }},{{ $hero->warna_ke ?? '#00b04f' }}{{ $hero->gambar ? '88' : '' }})"></div>

            {{-- Teks konten --}}
            <div style="position:relative;z-index:2;padding:40px 24px;text-align:center">
                <span id="preview-label" style="display:block;font-size:11px;font-weight:800;color:rgba(255,255,255,.8);letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px">
                    {{ $hero->label ?? '' }}
                </span>
                <h2 id="preview-judul" style="font-size:22px;font-weight:800;color:#fff;margin-bottom:8px;line-height:1.2;text-shadow:0 2px 8px rgba(0,0,0,.3)">
                    {{ $hero->judul }}
                </h2>
                <p id="preview-desk" style="font-size:12px;color:rgba(255,255,255,.85);max-width:480px;text-shadow:0 1px 4px rgba(0,0,0,.2)">
                    {{ $hero->deskripsi ?? '' }}
                </p>
                <div style="margin-top:10px;display:flex;align-items:center;justify-content:center;gap:6px;font-size:11px;color:rgba(255,255,255,.6)">
                    <span>Beranda</span>
                    <i class="fas fa-chevron-right" style="font-size:8px"></i>
                    <span id="preview-breadcrumb" style="font-weight:600;color:rgba(255,255,255,.85)">{{ $pageNama }}</span>
                </div>
            </div>
        </div>

        {{-- Judul & Label --}}
        <div class="card card-body">
            <div class="form-group">
                <label class="form-label">Judul Halaman <span style="color:#ef4444">*</span></label>
                <input type="text" name="judul" id="inp-judul"
                       value="{{ old('judul', $hero->judul) }}"
                       class="form-input" required maxlength="200"
                       style="font-size:16px;font-weight:700"
                       placeholder="Judul besar yang tampil di banner...">
            </div>
            <div class="form-group">
                <label class="form-label">Label Kecil <span style="font-size:11px;color:#94a3b8">(teks kecil di atas judul, opsional)</span></label>
                <input type="text" name="label" id="inp-label"
                       value="{{ old('label', $hero->label) }}"
                       class="form-input" maxlength="100"
                       placeholder="Contoh: Tim Medis Profesional">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi <span style="font-size:11px;color:#94a3b8">(teks kecil bawah judul, opsional)</span></label>
                <textarea name="deskripsi" id="inp-desk" rows="2" class="form-input" maxlength="500"
                    placeholder="Kalimat singkat penjelasan halaman...">{{ old('deskripsi', $hero->deskripsi) }}</textarea>
            </div>
        </div>

        {{-- Warna Gradient --}}
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px">
                <i class="fas fa-palette text-green-500 mr-2"></i>Warna Gradient Background
            </p>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Warna Dari <span style="color:#ef4444">*</span></label>
                    <div style="display:flex;gap:10px;align-items:center">
                        <input type="color" name="warna_dari" id="inp-warna-dari"
                               value="{{ old('warna_dari', $hero->warna_dari ?? '#00521f') }}"
                               class="form-input" style="width:60px;height:42px;padding:2px;cursor:pointer">
                        <input type="text" id="txt-warna-dari"
                               value="{{ old('warna_dari', $hero->warna_dari ?? '#00521f') }}"
                               class="form-input" style="width:110px;font-family:monospace"
                               maxlength="7" placeholder="#00521f">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Warna Ke <span style="color:#ef4444">*</span></label>
                    <div style="display:flex;gap:10px;align-items:center">
                        <input type="color" name="warna_ke" id="inp-warna-ke"
                               value="{{ old('warna_ke', $hero->warna_ke ?? '#00b04f') }}"
                               class="form-input" style="width:60px;height:42px;padding:2px;cursor:pointer">
                        <input type="text" id="txt-warna-ke"
                               value="{{ old('warna_ke', $hero->warna_ke ?? '#00b04f') }}"
                               class="form-input" style="width:110px;font-family:monospace"
                               maxlength="7" placeholder="#00b04f">
                    </div>
                </div>
            </div>

            {{-- Preset warna --}}
            <p style="font-size:11px;font-weight:700;color:#94a3b8;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em">Preset Warna</p>
            <div style="display:flex;flex-wrap:wrap;gap:8px">
                @foreach([
                    ['Hijau (default)', '#00521f','#00b04f'],
                    ['Biru Tua',        '#1e3a5f','#0284c7'],
                    ['Biru Medis',      '#0c4a6e','#0369a1'],
                    ['Ungu',            '#4c1d95','#7c3aed'],
                    ['Merah',           '#7f1d1d','#dc2626'],
                    ['Teal',            '#134e4a','#0d9488'],
                    ['Amber',           '#78350f','#d97706'],
                    ['Slate',           '#1e293b','#475569'],
                ] as [$nama,$dari,$ke])
                <button type="button"
                    onclick="setPreset('{{ $dari }}','{{ $ke }}')"
                    style="display:flex;align-items:center;gap:6px;padding:5px 10px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;cursor:pointer;font-size:12px;font-weight:600;color:#334155;transition:all .15s"
                    onmouseover="this.style.borderColor='#16a34a'"
                    onmouseout="this.style.borderColor='#e2e8f0'">
                    <span style="display:inline-block;width:24px;height:14px;border-radius:4px;background:linear-gradient(90deg,{{ $dari }},{{ $ke }})"></span>
                    {{ $nama }}
                </button>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KANAN: gambar + status + aksi --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        {{-- Status --}}
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:12px">Pengaturan</p>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="aktif"    {{ old('status',$hero->status ?? 'aktif')=='aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status',$hero->status ?? 'aktif')=='nonaktif' ? 'selected' : '' }}>Nonaktif (pakai default)</option>
                </select>
            </div>
        </div>

        {{-- Gambar background --}}
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:4px">
                <i class="fas fa-image text-green-500 mr-1"></i> Gambar Background
            </p>
            <p style="font-size:11px;color:#94a3b8;margin-bottom:12px">Opsional. Akan ditimpa gradient di atasnya. Rekomendasi: landscape, min 1280×400px, max 3MB.</p>

            @if($hero->gambar)
            <div style="margin-bottom:12px;border-radius:10px;overflow:hidden;position:relative">
                <img src="{{ Storage::url($hero->gambar) }}"
                     id="gambar-preview"
                     style="width:100%;height:130px;object-fit:cover;display:block">
                <div style="position:absolute;inset:0;background:linear-gradient({{ $hero->warna_dari }}99,{{ $hero->warna_ke }}99)"></div>
            </div>
            <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:#ef4444;cursor:pointer;margin-bottom:10px">
                <input type="checkbox" name="hapus_gambar" value="1"> Hapus gambar background
            </label>
            @else
            <img id="gambar-preview" style="display:none;width:100%;height:130px;object-fit:cover;border-radius:10px;margin-bottom:10px">
            @endif

            <div style="border:2px dashed #e2e8f0;border-radius:10px;padding:16px;text-align:center;cursor:pointer"
                 onclick="document.getElementById('gambar-input').click()"
                 ondragover="event.preventDefault();this.style.borderColor='#16a34a'"
                 ondragleave="this.style.borderColor='#e2e8f0'"
                 ondrop="handleDrop(event)"
                 id="drop-zone">
                <i class="fas fa-cloud-arrow-up" style="font-size:20px;color:#cbd5e1;display:block;margin-bottom:6px" id="drop-icon"></i>
                <p style="font-size:11px;color:#94a3b8" id="drop-text">Klik atau drag gambar di sini</p>
            </div>
            <input type="file" name="gambar" id="gambar-input" accept="image/*" style="display:none">
        </div>

        {{-- Preview link --}}
        @php
            $routeMap = [
                'layanan'  => 'layanan', 'dokter' => 'dokter', 'promo'  => 'promo',
                'artikel'  => 'artikel', 'event'  => 'event',  'tentang'=> 'tentang',
                'kontak'   => 'kontak',  'mcu'    => 'mcu',    'informasi'=> 'informasi',
            ];
        @endphp
        @if(isset($routeMap[$key]))
        <a href="{{ route($routeMap[$key]) }}" target="_blank"
           style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border-radius:12px;border:1px solid #e2e8f0;font-size:13px;font-weight:600;color:#64748b;text-decoration:none"
           onmouseover="this.style.borderColor='#16a34a';this.style.color='#16a34a'"
           onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#64748b'">
            <i class="fas fa-external-link-alt"></i> Lihat Halaman
        </a>
        @endif

        {{-- Tombol simpan --}}
        <div style="display:flex;gap:8px">
            <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">
                <i class="fas fa-save"></i> Simpan
            </button>
            <a href="{{ route('cms.page-hero') }}" class="btn btn-secondary">Batal</a>
        </div>

        {{-- Reset --}}
        @if(!($isNew ?? true))
        <form method="POST" action="{{ route('cms.page-hero.reset', $key) }}"
              onsubmit="return confirm('Reset ke default? Semua perubahan dan gambar akan dihapus.')">
            @csrf
            <button type="submit" class="btn btn-secondary" style="width:100%;justify-content:center;color:#ef4444">
                <i class="fas fa-rotate-left"></i> Reset ke Default
            </button>
        </form>
        @endif
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
// ── Live preview
const inpJudul = document.getElementById('inp-judul');
const inpLabel = document.getElementById('inp-label');
const inpDesk  = document.getElementById('inp-desk');

inpJudul && inpJudul.addEventListener('input', () => {
    document.getElementById('preview-judul').textContent = inpJudul.value || 'Judul Halaman';
});
inpLabel && inpLabel.addEventListener('input', () => {
    document.getElementById('preview-label').textContent = inpLabel.value;
});
inpDesk && inpDesk.addEventListener('input', () => {
    document.getElementById('preview-desk').textContent = inpDesk.value;
});

// ── Color pickers sync
const colorDari = document.getElementById('inp-warna-dari');
const txtDari   = document.getElementById('txt-warna-dari');
const colorKe   = document.getElementById('inp-warna-ke');
const txtKe     = document.getElementById('txt-warna-ke');
const preview   = document.getElementById('hero-preview');
const overlay   = document.getElementById('bg-overlay');
const bgImage   = document.getElementById('bg-image');

function hasImage() {
    return bgImage && bgImage.style.backgroundImage && bgImage.style.backgroundImage !== 'none' && bgImage.style.display !== 'none';
}

function updateGradient() {
    if (!overlay) return;
    const dari = colorDari.value;
    const ke   = colorKe.value;
    if (hasImage()) {
        // Ada gambar: overlay tipis agar gambar kelihatan
        overlay.style.background = `linear-gradient(135deg,${dari}a0,${ke}88)`;
    } else {
        // Tanpa gambar: gradient penuh
        overlay.style.background = `linear-gradient(135deg,${dari},${ke})`;
        preview.style.background = `linear-gradient(135deg,${dari},${ke})`;
    }
}

colorDari.addEventListener('input', () => { txtDari.value = colorDari.value; updateGradient(); });
txtDari.addEventListener('input',   () => {
    if (/^#[0-9a-fA-F]{6}$/.test(txtDari.value)) { colorDari.value = txtDari.value; updateGradient(); }
});
colorKe.addEventListener('input', () => { txtKe.value = colorKe.value; updateGradient(); });
txtKe.addEventListener('input',   () => {
    if (/^#[0-9a-fA-F]{6}$/.test(txtKe.value)) { colorKe.value = txtKe.value; updateGradient(); }
});

// ── Preset
function setPreset(dari, ke) {
    colorDari.value = dari; txtDari.value = dari;
    colorKe.value   = ke;   txtKe.value   = ke;
    updateGradient();
}

// ── Gambar upload preview
document.getElementById('gambar-input').addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;

    // Update sidebar preview
    const p = document.getElementById('gambar-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
    document.getElementById('drop-icon').style.display = 'none';
    document.getElementById('drop-text').textContent   = f.name;

    // Update hero preview — tampilkan gambar di background
    const reader = new FileReader();
    reader.onload = e => {
        if (bgImage) {
            bgImage.style.backgroundImage = `url('${e.target.result}')`;
            bgImage.style.backgroundSize  = 'cover';
            bgImage.style.backgroundPosition = 'center';
            bgImage.style.display = 'block';
            preview.style.background = ''; // hapus bg solid
        }
        updateGradient(); // overlay jadi tipis sekarang ada gambar
    };
    reader.readAsDataURL(f);
});

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').style.borderColor = '#e2e8f0';
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('gambar-input').files = dt.files;
        document.getElementById('gambar-input').dispatchEvent(new Event('change'));
    }
}
</script>
@endpush
