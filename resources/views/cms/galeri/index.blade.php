@extends('layouts.cms')
@php $pageTitle = 'Galeri Foto'; $breadcrumb = 'CMS / Galeri'; @endphp
@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:24px;align-items:start">

    {{-- Form Upload --}}
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px">Upload Foto Baru</p>
        @if(session('success'))
        <div class="alert alert-success" style="margin:0 0 12px"><i class="fas fa-circle-check"></i><span>{{ session('success') }}</span></div>
        @endif
        @if($errors->any())
        <div class="form-error" style="margin-bottom:12px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.galeri.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Kategori <span style="color:#ef4444">*</span></label>
                <select name="kategori_galeri_id" class="form-input" required>
                    <option value="">— Pilih Kategori —</option>
                    @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ old('kategori_galeri_id')==$k->id?'selected':'' }}>{{ $k->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Judul Foto <span style="color:#ef4444">*</span></label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="2" class="form-input">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">File Gambar <span style="color:#ef4444">*</span></label>
                <input type="file" name="gambar" accept="image/*" class="form-input" required id="galeri-upload">
                <div id="galeri-preview" style="display:none;margin-top:8px">
                    <img id="galeri-img" style="width:100%;height:120px;object-fit:cover;border-radius:10px">
                </div>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">Maks. 3MB. JPG, PNG, WEBP</p>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-input">
                    <option value="aktif">Aktif (Tampil)</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <i class="fas fa-upload"></i> Upload Foto
            </button>
        </form>
    </div>

    {{-- Grid Galeri --}}
    <div class="card card-body">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
            <p style="font-size:15px;font-weight:700;color:#0f172a">Koleksi Foto</p>
            <span style="font-size:12px;color:#94a3b8">{{ $galeris->total() }} foto</span>
        </div>
        @if($galeris->isEmpty())
        <div class="empty-state"><i class="fas fa-images"></i><p>Belum ada foto. Upload menggunakan form di samping.</p></div>
        @else
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
            @foreach($galeris as $g)
            <div style="position:relative;border-radius:12px;overflow:hidden;aspect-ratio:1;background:#f1f5f9">
                <img src="{{ Storage::url($g->gambar) }}" alt="{{ $g->judul }}"
                     style="width:100%;height:100%;object-fit:cover;transition:transform .3s"
                     onmouseover="this.style.transform='scale(1.05)'"
                     onmouseout="this.style.transform='scale(1)'">
                <div style="position:absolute;inset:0;background:rgba(0,0,0,.5);opacity:0;transition:opacity .2s;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;padding:10px"
                     onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                    <p style="color:#fff;font-size:11px;font-weight:600;text-align:center;line-height:1.3">{{ $g->judul }}</p>
                    <p style="color:rgba(255,255,255,.7);font-size:10px">{{ $g->kategori?->nama_kategori }}</p>
                    <form method="POST" action="{{ route('cms.galeri.destroy', $g) }}" onsubmit="return confirm('Hapus foto ini?')">
                        @csrf @method('DELETE')
                        <button style="background:#ef4444;color:#fff;border:none;font-size:11px;font-weight:700;padding:6px 12px;border-radius:8px;cursor:pointer">
                            <i class="fas fa-trash" style="font-size:10px"></i> Hapus
                        </button>
                    </form>
                </div>
                @if($g->status !== 'aktif')
                <div style="position:absolute;top:8px;left:8px;background:rgba(0,0,0,.6);color:#fff;font-size:9px;font-weight:700;padding:2px 6px;border-radius:4px">Tersembunyi</div>
                @endif
            </div>
            @endforeach
        </div>
        <div style="margin-top:16px">{{ $galeris->links() }}</div>
        @endif
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('galeri-upload')?.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('galeri-img').src = e.target.result;
            document.getElementById('galeri-preview').style.display = '';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
