@extends('layouts.cms')
@php $pageTitle = 'Tambah Layanan'; $breadcrumb = 'CMS / Layanan / Tambah'; @endphp
@section('content')
<div class="max-w-2xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.layanan.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Layanan <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" class="form-input" required maxlength="255" placeholder="cth: Rawat Inap Umum">
                </div>

                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Kategori Layanan</label>
                    <select name="kategori_layanan_id" class="form-input">
                        <option value="">— Tanpa Kategori —</option>
                        @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ old('kategori_layanan_id')==$k->id?'selected':'' }}>
                            {{ $k->nama_kategori }}
                        </option>
                        @endforeach
                    </select>
                    <p class="form-hint">Kelompokkan layanan agar lebih mudah ditemukan pengunjung. <a href="{{ route('cms.kategori-layanan') }}" target="_blank" style="color:#2563eb">Kelola kategori →</a></p>
                </div>

                <div class="form-group">
                    <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(Font Awesome class)</span></label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input type="text" name="icon" id="icon-input" value="{{ old('icon','fa-stethoscope') }}" class="form-input" placeholder="fa-stethoscope" style="flex:1">
                        <div style="width:38px;height:38px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;color:#16a34a;flex-shrink:0">
                            <i class="fas fa-stethoscope" id="icon-preview"></i>
                        </div>
                    </div>
                    <p class="form-hint">cth: fa-stethoscope, fa-heartbeat, fa-bed, fa-syringe, fa-microscope</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Gambar <span style="font-size:11px;color:#94a3b8">(opsional, max 2MB)</span></label>
                    <img id="gambar-preview" style="display:none;max-height:120px;border-radius:8px;margin-bottom:8px;border:2px solid #e2e8f0">
                    <input type="file" name="gambar" class="form-input" accept="image/*" id="gambar-input">
                    <p class="form-hint">Format JPG/PNG/WebP, max 2MB</p>
                </div>

                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-input" placeholder="Penjelasan singkat layanan ini...">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"    {{ old('status','aktif')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status')=='nonaktif'?'selected':'' }}>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Layanan</button>
                <a href="{{ route('cms.layanan') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('icon-input').addEventListener('input', function() {
    document.getElementById('icon-preview').className = 'fas ' + (this.value || 'fa-stethoscope');
});
document.getElementById('gambar-input').addEventListener('change', function() {
    const f = this.files[0]; if (!f) return;
    const p = document.getElementById('gambar-preview');
    p.src = URL.createObjectURL(f); p.style.display = 'block';
});
</script>
@endpush
