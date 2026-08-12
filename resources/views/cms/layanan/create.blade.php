@extends('layouts.cms')
@php $pageTitle = 'Tambah Layanan'; $breadcrumb = 'CMS / Layanan / Tambah'; @endphp
@section('content')
<form method="POST" action="{{ route('cms.layanan.store') }}" enctype="multipart/form-data">
@csrf
@if($errors->any())
<div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif
<div style="display:grid;grid-template-columns:1fr 300px;gap:24px">

    {{-- KIRI: konten utama --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card card-body">
            <div class="form-group">
                <label class="form-label">Nama Layanan <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" class="form-input" required maxlength="255"
                    style="font-size:16px;font-weight:600" placeholder="Nama layanan...">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" class="form-input"
                    placeholder="Penjelasan singkat yang tampil di kartu dan halaman list...">{{ old('deskripsi') }}</textarea>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">Maks ~200 karakter, tampil di kartu layanan.</p>
            </div>
        </div>

        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px">Konten Lengkap <span style="font-size:11px;color:#94a3b8;font-weight:400">(opsional – tampil di halaman detail)</span></p>
            <div class="form-group">
                <textarea name="konten" id="layanan-editor" class="form-input" rows="10">{{ old('konten') }}</textarea>
            </div>
        </div>
    </div>

    {{-- KANAN: sidebar --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:16px">Pengaturan</p>

            <div class="form-group">
                <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                <select name="status" class="form-input">
                    <option value="aktif"    {{ old('status','aktif')=='aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status')=='nonaktif'         ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select name="kategori_layanan_id" class="form-input">
                    <option value="">— Tanpa Kategori —</option>
                    @foreach($kategoris as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_layanan_id') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                    @endforeach
                </select>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">
                    <a href="{{ route('cms.kategori-layanan') }}" style="color:#16a34a">+ Tambah kategori baru</a>
                </p>
            </div>

            <div class="form-group">
                <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(Font Awesome)</span></label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="text" name="icon" id="icon-input" value="{{ old('icon','fa-stethoscope') }}" class="form-input" placeholder="fa-stethoscope">
                    <div id="icon-preview" style="width:36px;height:36px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i id="icon-display" class="fas fa-stethoscope" style="color:#16a34a"></i>
                    </div>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px">
                    @foreach(['fa-stethoscope','fa-heartbeat','fa-baby','fa-brain','fa-bone','fa-eye','fa-tooth','fa-lungs','fa-spa','fa-dna','fa-microscope','fa-hospital','fa-ambulance','fa-pills','fa-syringe','fa-x-ray'] as $ic)
                    <button type="button" onclick="setIcon('{{ $ic }}')"
                        style="width:32px;height:32px;border-radius:6px;background:#f1f5f9;border:1px solid #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:13px;color:#64748b;transition:all .15s"
                        onmouseover="this.style.background='#dcfce7';this.style.color='#16a34a'"
                        onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b'"
                        title="{{ $ic }}">
                        <i class="fas {{ $ic }}"></i>
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Urutan Tampil</label>
                <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-input" min="0" max="999">
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">Angka kecil tampil lebih dulu. Default: 0.</p>
            </div>
        </div>

        <div class="card card-body">
            <p style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:12px">Gambar <span style="font-size:11px;color:#94a3b8;font-weight:400">(opsional, max 2MB)</span></p>
            <div id="gambar-drop" style="border:2px dashed #e2e8f0;border-radius:12px;padding:16px;text-align:center;cursor:pointer;transition:all .15s;margin-bottom:8px"
                 onclick="document.getElementById('gambar-input').click()"
                 ondragover="event.preventDefault();this.style.borderColor='#16a34a'"
                 ondragleave="this.style.borderColor='#e2e8f0'"
                 ondrop="handleDrop(event)">
                <img id="gambar-preview" style="display:none;width:100%;max-height:140px;object-fit:cover;border-radius:8px;margin-bottom:8px">
                <i class="fas fa-image" style="font-size:24px;color:#cbd5e1;display:block;margin-bottom:6px" id="gambar-placeholder-icon"></i>
                <p style="font-size:11px;color:#94a3b8" id="gambar-placeholder-text">Klik atau drag gambar ke sini</p>
            </div>
            <input type="file" name="gambar" class="form-input" accept="image/*" id="gambar-input" style="display:none">
        </div>

        <div style="display:flex;gap:8px">
            <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center"><i class="fas fa-save"></i> Simpan</button>
            <a href="{{ route('cms.layanan') }}" class="btn btn-secondary">Batal</a>
        </div>
    </div>
</div>
</form>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#layanan-editor',
    license_key: 'gpl',
    height: 400,
    menubar: false,
    base_url: 'https://cdn.jsdelivr.net/npm/tinymce@6.8.3',
    suffix: '.min',
    plugins: ['advlist','autolink','lists','link','charmap','preview','anchor','searchreplace','visualblocks','code','table','help','wordcount'],
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | outdent indent | link table | removeformat | code | help',
    content_style: 'body { font-family: "Plus Jakarta Sans", sans-serif; font-size:14px; line-height:1.8; color:#334155; padding:16px; }',
    branding: false,
    promotion: false,
    setup: function(editor) { editor.on('change input', function() { editor.save(); }); }
});

// Icon picker
function setIcon(ic) {
    document.getElementById('icon-input').value = ic;
    document.getElementById('icon-display').className = 'fas ' + ic;
}
document.getElementById('icon-input').addEventListener('input', function() {
    document.getElementById('icon-display').className = 'fas ' + this.value;
});

// Gambar preview
document.getElementById('gambar-input').addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;
    const p = document.getElementById('gambar-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
    document.getElementById('gambar-placeholder-icon').style.display = 'none';
    document.getElementById('gambar-placeholder-text').style.display = 'none';
});
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('gambar-drop').style.borderColor = '#e2e8f0';
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
