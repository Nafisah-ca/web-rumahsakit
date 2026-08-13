@extends('layouts.cms')
@php $pageTitle = 'Kategori Layanan'; $breadcrumb = 'CMS / Kategori Layanan'; @endphp
@section('content')

<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start">

    {{-- ── Daftar Kategori ── --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Kategori Layanan</h3>
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..." class="form-input" style="width:200px">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('search'))<a href="{{ route('cms.kategori-layanan') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Kategori</th>
                        <th>Icon</th>
                        <th>Layanan Aktif</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $k)
                    <tr>
                        <td>
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;background:#f1f5f9;border-radius:8px;font-size:12px;font-weight:700;color:#475569">
                                {{ $k->urutan }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;background:#dcfce7;color:#16a34a;flex-shrink:0">
                                    <i class="fas {{ $k->icon ?? 'fa-hospital' }}"></i>
                                </div>
                                <div>
                                    <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $k->nama_kategori }}</p>
                                    @if($k->deskripsi)
                                    <p style="font-size:11px;color:#94a3b8;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $k->deskripsi }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td><span class="code-tag"><i class="fas {{ $k->icon ?? 'fa-hospital' }} mr-1"></i>{{ $k->icon ?? 'fa-hospital' }}</span></td>
                        <td>
                            <span style="font-weight:700;color:#0f172a">{{ $k->layanans_count }}</span>
                            <span style="font-size:11px;color:#94a3b8"> layanan</span>
                        </td>
                        <td><span class="badge {{ $k->status==='aktif'?'badge-green':'badge-slate' }}">{{ $k->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                {{-- Tombol Edit: buka modal dengan data kategori --}}
                                <button
                                    onclick="openEditModal({{ $k->id }}, '{{ addslashes($k->nama_kategori) }}', '{{ $k->icon ?? 'fa-hospital' }}', '{{ addslashes($k->deskripsi ?? '') }}', {{ $k->urutan }}, '{{ $k->status }}')"
                                    class="btn btn-sm btn-secondary">
                                    <i class="fas fa-pen"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('cms.kategori-layanan.destroy', $k) }}" onsubmit="return confirm('Hapus kategori ini? Layanan terkait akan kehilangan kategorinya.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state"><i class="fas fa-folder-open"></i><p>Belum ada kategori layanan</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">{{ $kategoris->links() }}</div>
    </div>

    {{-- ── Form Tambah Kategori ── --}}
    <div class="card card-body" style="position:sticky;top:24px">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;display:flex;align-items:center;gap:8px">
            <i class="fas fa-folder-plus" style="color:#16a34a"></i> Tambah Kategori Baru
        </p>
        @if($errors->any())
        <div class="form-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.kategori-layanan.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" class="form-input" required maxlength="100" placeholder="cth: Rawat Inap, Bedah, Poliklinik">
            </div>
            <div class="form-group">
                <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(Font Awesome)</span></label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="text" name="icon" id="add-icon-input" value="{{ old('icon','fa-hospital') }}" class="form-input" placeholder="fa-hospital" style="flex:1">
                    <div id="add-icon-preview" style="width:36px;height:36px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;color:#16a34a;flex-shrink:0">
                        <i class="fas fa-hospital" id="add-icon-el"></i>
                    </div>
                </div>
                <p class="form-hint">cth: fa-bed, fa-heart-pulse, fa-scissors, fa-syringe</p>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="urutan" value="{{ old('urutan', 0) }}" class="form-input" min="0" max="999">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi <span style="font-size:11px;color:#94a3b8">(opsional)</span></label>
                <textarea name="deskripsi" rows="2" class="form-input" placeholder="Penjelasan singkat kategori...">{{ old('deskripsi') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <i class="fas fa-save"></i> Simpan Kategori
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit Kategori --}}
<div id="edit-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;padding:28px;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,.2)">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
            <p style="font-size:15px;font-weight:700;color:#0f172a"><i class="fas fa-pen" style="color:#2563eb;margin-right:8px"></i>Edit Kategori Layanan</p>
            <button onclick="closeEditModal()" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:16px"><i class="fas fa-xmark"></i></button>
        </div>
        <form id="edit-form" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_kategori" id="edit-nama" class="form-input" required maxlength="100">
            </div>
            <div class="form-group">
                <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(Font Awesome)</span></label>
                <div style="display:flex;gap:8px;align-items:center">
                    <input type="text" name="icon" id="edit-icon-input" class="form-input" style="flex:1">
                    <div style="width:36px;height:36px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center;color:#16a34a;flex-shrink:0">
                        <i id="edit-icon-el" class="fas fa-hospital"></i>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Urutan Tampil</label>
                    <input type="number" name="urutan" id="edit-urutan" class="form-input" min="0" max="999">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit-status" class="form-input">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi <span style="font-size:11px;color:#94a3b8">(opsional)</span></label>
                <textarea name="deskripsi" id="edit-deskripsi" rows="2" class="form-input"></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:4px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Batal</button>
            </div>
        </form>
    </div>
</div>

@endsection
@push('scripts')
<script>
// Preview icon saat mengetik (form tambah)
document.getElementById('add-icon-input').addEventListener('input', function() {
    const el = document.getElementById('add-icon-el');
    el.className = 'fas ' + (this.value || 'fa-hospital');
});

// Preview icon saat mengetik (form edit)
document.getElementById('edit-icon-input').addEventListener('input', function() {
    const el = document.getElementById('edit-icon-el');
    el.className = 'fas ' + (this.value || 'fa-hospital');
});

function openEditModal(id, nama, icon, deskripsi, urutan, status) {
    const base = '{{ url("cms/kategori-layanan") }}';
    document.getElementById('edit-form').action = base + '/' + id;
    document.getElementById('edit-nama').value      = nama;
    document.getElementById('edit-icon-input').value = icon;
    document.getElementById('edit-icon-el').className = 'fas ' + icon;
    document.getElementById('edit-deskripsi').value  = deskripsi;
    document.getElementById('edit-urutan').value     = urutan;
    document.getElementById('edit-status').value     = status;
    document.getElementById('edit-modal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('edit-modal').style.display = 'none';
}

// Tutup modal kalau klik backdrop
document.getElementById('edit-modal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endpush
