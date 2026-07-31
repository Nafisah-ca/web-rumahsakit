@extends('layouts.admin')
@php $pageTitle = 'Penjamin'; $breadcrumb = 'Admin / Penjamin'; @endphp
@section('content')

<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start">

    {{-- List Penjamin --}}
    <div class="card">
        <div class="card-header">
            <h3>Daftar Penjamin</h3>
            <form style="display:flex;gap:8px" method="GET">
                <select name="tipe_id" class="form-input" style="width:160px">
                    <option value="">Semua Tipe</option>
                    @foreach($tipes as $t)
                    <option value="{{ $t->id }}" {{ request('tipe_id')==$t->id?'selected':'' }}>{{ $t->nama_tipe }}</option>
                    @endforeach
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penjamin..." class="form-input" style="width:160px">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['tipe_id','search']))<a href="{{ route('admin.penjamin') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Nama Penjamin</th><th>Tipe</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($penjamins as $i => $p)
                    <tr>
                        <td style="color:#94a3b8">{{ $penjamins->firstItem()+$i }}</td>
                        <td style="font-weight:600;font-size:13px">{{ $p->nama_penjamin }}</td>
                        <td>
                            @php $tipeClr=['Umum'=>'badge-green','BPJS Kesehatan'=>'badge-blue','Asuransi Swasta'=>'badge-purple'][$p->tipePenjamin?->nama_tipe]??'badge-slate'; @endphp
                            <span class="badge {{ $tipeClr }}">{{ $p->tipePenjamin?->nama_tipe ?? '-' }}</span>
                        </td>
                        <td><span class="badge {{ $p->status==='aktif'?'badge-green':'badge-slate' }}">{{ $p->status==='aktif'?'Aktif':'Nonaktif' }}</span></td>
                        <td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap">
                                <button class="btn btn-sm btn-secondary" onclick="editPenjamin({{ $p->id }},'{{ $p->nama_penjamin }}',{{ $p->tipe_penjamin_id }},'{{ $p->status }}')">
                                    <i class="fas fa-pen"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('admin.penjamin.destroy', $p) }}" onsubmit="return confirm('Hapus penjamin ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state"><i class="fas fa-shield-halved"></i><p>Belum ada penjamin</p></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-footer">{{ $penjamins->links() }}</div>
    </div>

    {{-- Form Tambah/Edit --}}
    <div style="display:flex;flex-direction:column;gap:16px">
        {{-- Tambah Penjamin --}}
        <div class="card card-body">
            <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px">Tambah Penjamin Baru</p>
            @if(session('success'))<div class="alert alert-success" style="margin-bottom:10px"><i class="fas fa-check-circle"></i><span>{{ session('success') }}</span></div>@endif
            @if(session('error'))<div class="alert alert-error" style="margin-bottom:10px"><i class="fas fa-exclamation-circle"></i><span>{{ session('error') }}</span></div>@endif
            <form method="POST" action="{{ route('admin.penjamin.store') }}" id="add-form">
                @csrf
                <div class="form-group">
                    <label class="form-label">Tipe Penjamin <span style="color:#ef4444">*</span></label>
                    <select name="tipe_penjamin_id" class="form-input" required>
                        <option value="">— Pilih Tipe —</option>
                        @foreach($tipes as $t)
                        <option value="{{ $t->id }}">{{ $t->nama_tipe }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Penjamin <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama_penjamin" class="form-input" required placeholder="cth: BPJS Kesehatan, Prudential...">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    <i class="fas fa-plus"></i> Tambah Penjamin
                </button>
            </form>
        </div>

        {{-- Link ke Tipe Penjamin --}}
        <a href="{{ route('admin.tipe-penjamin') }}" class="card card-body" style="display:flex;align-items:center;gap:12px;text-decoration:none;border:2px solid #f1f5f9;transition:all .2s" onmouseover="this.style.borderColor='#16a34a'" onmouseout="this.style.borderColor='#f1f5f9'">
            <div style="width:40px;height:40px;background:#dcfce7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <i class="fas fa-layer-group" style="color:#16a34a"></i>
            </div>
            <div>
                <p style="font-weight:700;font-size:13px;color:#0f172a">Kelola Tipe Penjamin</p>
                <p style="font-size:11px;color:#94a3b8">Umum, BPJS, Asuransi Swasta, dll</p>
            </div>
            <i class="fas fa-chevron-right" style="color:#94a3b8;margin-left:auto"></i>
        </a>
    </div>
</div>

{{-- Modal Edit (hidden form) --}}
<div id="edit-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;padding:24px;width:400px;max-width:90vw">
        <p style="font-size:16px;font-weight:700;color:#0f172a;margin-bottom:16px">Edit Penjamin</p>
        <form method="POST" id="edit-form">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Tipe Penjamin</label>
                <select name="tipe_penjamin_id" id="edit-tipe" class="form-input" required>
                    @foreach($tipes as $t)
                    <option value="{{ $t->id }}">{{ $t->nama_tipe }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Nama Penjamin</label>
                <input type="text" name="nama_penjamin" id="edit-nama" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" id="edit-status" class="form-input">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">Simpan</button>
                <button type="button" onclick="closeEdit()" class="btn btn-secondary" style="flex:1;justify-content:center">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
function editPenjamin(id, nama, tipeId, status) {
    document.getElementById('edit-form').action = `/admin/penjamin/${id}`;
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-tipe').value = tipeId;
    document.getElementById('edit-status').value = status;
    document.getElementById('edit-modal').style.display = 'flex';
}
function closeEdit() { document.getElementById('edit-modal').style.display = 'none'; }
document.getElementById('edit-modal').addEventListener('click', function(e) { if(e.target===this) closeEdit(); });
</script>
@endpush
