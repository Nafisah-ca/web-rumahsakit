@extends('layouts.cms')
@php $pageTitle = 'Kategori Layanan'; $breadcrumb = 'CMS / Layanan / Kategori'; @endphp
@section('content')
<div style="display:grid;grid-template-columns:1fr 1.5fr;gap:24px">

    {{-- FORM --}}
    <div>
        @if(isset($editKategori))
        {{-- Form Edit --}}
        <div class="card card-body">
            <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px">
                <i class="fas fa-pen text-green-600 mr-2"></i>Edit Kategori
            </p>
            @if($errors->any())
            <div class="form-error" style="margin-bottom:14px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('cms.kategori-layanan.update', $editKategori) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $editKategori->nama_kategori) }}" class="form-input" required maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(Font Awesome)</span></label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input type="text" name="icon" id="icon-input-edit" value="{{ old('icon', $editKategori->icon ?? 'fa-stethoscope') }}" class="form-input" placeholder="fa-stethoscope">
                        <div style="width:36px;height:36px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i id="icon-display-edit" class="fas {{ old('icon', $editKategori->icon ?? 'fa-stethoscope') }}" style="color:#16a34a"></i>
                        </div>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:5px;margin-top:8px">
                        @foreach(['fa-stethoscope','fa-heartbeat','fa-baby','fa-brain','fa-bone','fa-eye','fa-tooth','fa-lungs','fa-spa','fa-dna','fa-microscope','fa-hospital','fa-ambulance','fa-pills','fa-syringe','fa-x-ray','fa-star','fa-shield-halved'] as $ic)
                        <button type="button" onclick="setIconEdit('{{ $ic }}')"
                            style="width:30px;height:30px;border-radius:6px;background:#f1f5f9;border:1px solid #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;color:#64748b"
                            title="{{ $ic }}"><i class="fas {{ $ic }}"></i></button>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="form-input" placeholder="Opsional...">{{ old('deskripsi', $editKategori->deskripsi) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif"    {{ old('status', $editKategori->status)=='aktif'    ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $editKategori->status)=='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div style="display:flex;gap:8px">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{ route('cms.kategori-layanan') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
        @else
        {{-- Form Tambah --}}
        <div class="card card-body">
            <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px">
                <i class="fas fa-plus text-green-600 mr-2"></i>Tambah Kategori
            </p>
            @if($errors->any())
            <div class="form-error" style="margin-bottom:14px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <form method="POST" action="{{ route('cms.kategori-layanan.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kategori <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori') }}" class="form-input" required maxlength="100" placeholder="cth: Rawat Jalan, Bedah, Radiologi">
                </div>
                <div class="form-group">
                    <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(Font Awesome)</span></label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <input type="text" name="icon" id="icon-input-add" value="{{ old('icon','fa-stethoscope') }}" class="form-input" placeholder="fa-stethoscope">
                        <div style="width:36px;height:36px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i id="icon-display-add" class="fas {{ old('icon','fa-stethoscope') }}" style="color:#16a34a"></i>
                        </div>
                    </div>
                    <div style="display:flex;flex-wrap:wrap;gap:5px;margin-top:8px">
                        @foreach(['fa-stethoscope','fa-heartbeat','fa-baby','fa-brain','fa-bone','fa-eye','fa-tooth','fa-lungs','fa-spa','fa-dna','fa-microscope','fa-hospital','fa-ambulance','fa-pills','fa-syringe','fa-x-ray','fa-star','fa-shield-halved'] as $ic)
                        <button type="button" onclick="setIconAdd('{{ $ic }}')"
                            style="width:30px;height:30px;border-radius:6px;background:#f1f5f9;border:1px solid #e2e8f0;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;color:#64748b"
                            title="{{ $ic }}"><i class="fas {{ $ic }}"></i></button>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="form-input" placeholder="Opsional...">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Simpan</button>
            </form>
        </div>
        @endif
    </div>

    {{-- TABEL --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Kategori Layanan</h3>
            <a href="{{ route('cms.layanan') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Ke Layanan</a>
        </div>
        @if(session('success'))
        <div style="margin:12px 16px 0;padding:10px 14px;background:#dcfce7;border-radius:8px;font-size:13px;color:#15803d;font-weight:600">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
        @endif
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th style="text-align:center">Icon</th>
                        <th style="text-align:center">Layanan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris as $kat)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px">
                                <div style="width:36px;height:36px;border-radius:8px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                    <i class="fas {{ $kat->icon ?? 'fa-stethoscope' }}" style="color:#16a34a"></i>
                                </div>
                                <div>
                                    <p style="font-weight:700;font-size:13px;color:#0f172a">{{ $kat->nama_kategori }}</p>
                                    @if($kat->deskripsi)
                                    <p style="font-size:11px;color:#94a3b8">{{ Str::limit($kat->deskripsi, 60) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="text-align:center"><span class="code-tag">{{ $kat->icon ?? '—' }}</span></td>
                        <td style="text-align:center">
                            <a href="{{ route('cms.layanan', ['kategori_id' => $kat->id]) }}"
                               style="font-weight:700;color:#16a34a;text-decoration:none">
                                {{ $kat->layanans_count ?? 0 }}
                            </a>
                        </td>
                        <td><span class="badge {{ $kat->status==='aktif'?'badge-green':'badge-slate' }}">{{ $kat->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <a href="{{ route('cms.kategori-layanan.edit', $kat) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i></a>
                                <form method="POST" action="{{ route('cms.kategori-layanan.destroy', $kat) }}"
                                    onsubmit="return confirm('Hapus kategori ini? Layanan yang terhubung tidak akan dihapus.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-tags"></i>
                                <p>Belum ada kategori layanan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">{{ $kategoris->links() }}</div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function setIconAdd(ic) {
    document.getElementById('icon-input-add').value = ic;
    document.getElementById('icon-display-add').className = 'fas ' + ic;
}
function setIconEdit(ic) {
    document.getElementById('icon-input-edit').value = ic;
    document.getElementById('icon-display-edit').className = 'fas ' + ic;
}
const addInput  = document.getElementById('icon-input-add');
const editInput = document.getElementById('icon-input-edit');
addInput  && addInput.addEventListener('input',  function() { document.getElementById('icon-display-add').className  = 'fas ' + this.value; });
editInput && editInput.addEventListener('input', function() { document.getElementById('icon-display-edit').className = 'fas ' + this.value; });
</script>
@endpush
