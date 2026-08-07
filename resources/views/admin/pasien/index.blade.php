@extends('layouts.admin')
@php $pageTitle = 'Data Pasien'; $breadcrumb = 'Admin / Pasien'; @endphp

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar Pasien</h3>
        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
            <form style="display:flex;gap:8px;align-items:center" method="GET">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / No RM / NIK..." class="form-input" style="width:260px">
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request('search'))<a href="{{ route('admin.pasien', ['tab' => $tab]) }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            @if($tab === 'aktif')
            <a href="{{ route('admin.pasien.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pasien</a>
            @endif
        </div>
    </div>

    {{-- Tab Filter --}}
    <div style="display:flex;gap:4px;padding:0 20px;border-bottom:1px solid #e2e8f0;margin-bottom:0">
        <a href="{{ route('admin.pasien', ['tab' => 'aktif', 'search' => request('search')]) }}"
           style="padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;border-bottom:2px solid {{ $tab === 'aktif' ? '#16a34a' : 'transparent' }};color:{{ $tab === 'aktif' ? '#16a34a' : '#64748b' }}">
            <i class="fas fa-user-check" style="margin-right:6px"></i>Aktif
        </a>
        <a href="{{ route('admin.pasien', ['tab' => 'nonaktif', 'search' => request('search')]) }}"
           style="padding:10px 18px;font-size:13px;font-weight:600;text-decoration:none;border-bottom:2px solid {{ $tab === 'nonaktif' ? '#dc2626' : 'transparent' }};color:{{ $tab === 'nonaktif' ? '#dc2626' : '#64748b' }}">
            <i class="fas fa-user-slash" style="margin-right:6px"></i>Nonaktif
            @if($totalNonaktif > 0)
            <span style="background:#fef2f2;color:#dc2626;border:1px solid #fecaca;border-radius:9999px;padding:1px 8px;font-size:11px;margin-left:4px">{{ $totalNonaktif }}</span>
            @endif
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead><tr>
                <th>#</th><th>Pasien</th><th>No. RM</th><th>NIK</th><th>Telepon</th><th>Usia</th>
                @if($tab === 'nonaktif')<th>Dinonaktifkan</th>@endif
                <th>Aksi</th>
            </tr></thead>
            <tbody>
                @forelse($pasiens as $i => $p)
                <tr style="{{ $tab === 'nonaktif' ? 'opacity:0.75;background:#fafafa' : '' }}">
                    <td style="color:#94a3b8">{{ $pasiens->firstItem()+$i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar avatar-sm avatar-sq" style="background:{{ $tab === 'nonaktif' ? '#f1f5f9' : '#dcfce7' }};color:{{ $tab === 'nonaktif' ? '#64748b' : '#166534' }}">
                                {{ strtoupper(substr($p->nama_lengkap,0,1)) }}
                            </div>
                            <div>
                                <p style="font-weight:600;color:#0f172a;font-size:13px">{{ $p->nama_lengkap }}</p>
                                @if($p->jenis_kelamin)<p style="font-size:11px;color:#94a3b8">{{ $p->jenis_kelamin_label }}</p>@endif
                            </div>
                        </div>
                    </td>
                    <td><span class="code-tag">{{ $p->no_rm ?? '-' }}</span></td>
                    <td style="color:#64748b;font-size:12px">{{ $p->nik ?? '-' }}</td>
                    <td style="color:#64748b">{{ $p->user?->no_hp ?? '-' }}</td>
                    <td style="color:#64748b">{{ $p->umur ? $p->umur.' th' : '-' }}</td>
                    @if($tab === 'nonaktif')
                    <td style="color:#94a3b8;font-size:12px">
                        {{ $p->deleted_tm ? \Carbon\Carbon::parse($p->deleted_tm)->format('d M Y') : '-' }}
                    </td>
                    @endif
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            @if($tab === 'aktif')
                                <a href="{{ route('admin.pasien.show', $p) }}" class="btn btn-sm btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                                <a href="{{ route('admin.pasien.edit', $p) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                                <form method="POST" action="{{ route('admin.pasien.destroy', $p) }}" onsubmit="return confirm('Nonaktifkan pasien ini? Data tidak akan dihapus permanen.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger"><i class="fas fa-user-slash"></i> Nonaktifkan</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('admin.pasien.restore', $p->id) }}" onsubmit="return confirm('Pulihkan pasien ini?')">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-primary"><i class="fas fa-user-check"></i> Pulihkan</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $tab === 'nonaktif' ? 8 : 7 }}">
                        <div class="empty-state">
                            <i class="fas {{ $tab === 'nonaktif' ? 'fa-user-slash' : 'fa-users' }}"></i>
                            <p>{{ $tab === 'nonaktif' ? 'Tidak ada pasien nonaktif' : 'Tidak ada data pasien' }}</p>
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
