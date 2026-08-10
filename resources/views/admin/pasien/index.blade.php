@extends('layouts.admin')
@php $pageTitle = 'Data Pasien'; $breadcrumb = 'Admin / Pasien'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Pasien</h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <form style="display:flex;gap:8px;align-items:center;flex-wrap:wrap" method="GET" id="form-filter-pasien">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama / No RM / NIK..." class="form-input" style="width:240px">
                <select name="status" class="form-input" style="width:150px"
                        onchange="document.getElementById('form-filter-pasien').submit()">
                    <option value=""        {{ $status === ''        ? 'selected' : '' }}>Semua Status</option>
                    <option value="aktif"   {{ $status === 'aktif'   ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif"{{ $status === 'nonaktif'? 'selected' : '' }}>Nonaktif</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('search') || request('status'))
                    <a href="{{ route('admin.pasien') }}" class="btn btn-secondary" title="Reset filter">
                        <i class="fas fa-xmark"></i>
                    </a>
                @endif
            </form>
            <a href="{{ route('admin.pasien.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pasien
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Pasien</th>
                    <th>No. RM</th>
                    <th>NIK</th>
                    <th>Telepon</th>
                    <th>Usia</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $i => $p)
                @php $isNonaktif = !is_null($p->deleted_tm); @endphp
                <tr style="{{ $isNonaktif ? 'opacity:0.72;background:#fafafa' : '' }}">
                    <td style="color:#94a3b8">{{ $pasiens->firstItem() + $i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar avatar-sm avatar-sq"
                                 style="background:{{ $isNonaktif ? '#f1f5f9' : '#dcfce7' }};color:{{ $isNonaktif ? '#64748b' : '#166534' }}">
                                {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-weight:600;color:#0f172a;font-size:13px">{{ $p->nama_lengkap }}</p>
                                @if($p->jenis_kelamin)
                                    <p style="font-size:11px;color:#94a3b8">{{ $p->jenis_kelamin_label }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><span class="code-tag">{{ $p->no_rm ?? '-' }}</span></td>
                    <td style="color:#64748b;font-size:12px">{{ $p->nik ?? '-' }}</td>
                    <td style="color:#64748b">{{ $p->user?->no_hp ?? '-' }}</td>
                    <td style="color:#64748b">{{ $p->umur ? $p->umur . ' th' : '-' }}</td>
                    <td>
                        @if($isNonaktif)
                            <span class="badge badge-red">Nonaktif</span>
                        @else
                            <span class="badge badge-green">Aktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            @if($isNonaktif)
                                {{-- Pasien nonaktif: hanya tombol Pulihkan --}}
                                <form method="POST" action="{{ route('admin.pasien.restore', $p->id) }}"
                                      onsubmit="return confirm('Pulihkan pasien ini?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-primary">
                                        <i class="fas fa-user-check"></i> Pulihkan
                                    </button>
                                </form>
                            @else
                                {{-- Pasien aktif: Detail, Edit, Nonaktifkan --}}
                                <a href="{{ route('admin.pasien.show', $p) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="{{ route('admin.pasien.edit', $p) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.pasien.destroy', $p) }}"
                                      onsubmit="return confirm('Nonaktifkan pasien ini? Data tidak akan dihapus permanen.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-user-slash"></i> Nonaktifkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>Tidak ada data pasien ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $pasiens->links() }}</div>
</div>
@endsection
