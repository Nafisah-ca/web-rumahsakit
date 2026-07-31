@extends('layouts.admin')
@php $pageTitle = 'Manajemen User'; $breadcrumb = 'Admin / User'; @endphp
@section('content')
<div class="card">
    <div class="card-header">
        <h3>Daftar User</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            <form style="display:flex;gap:8px" method="GET">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / email..." class="form-input" style="width:200px">
                <select name="role" class="form-input" style="width:130px">
                    <option value="">Semua Role</option>
                    <option value="admin"  {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="cms"    {{ request('role')=='cms'?'selected':'' }}>CMS</option>
                    <option value="pasien" {{ request('role')=='pasien'?'selected':'' }}>Pasien</option>
                </select>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                @if(request()->hasAny(['search','role']))<a href="{{ route('admin.users') }}" class="btn btn-secondary"><i class="fas fa-xmark"></i></a>@endif
            </form>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah User</a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th>Bergabung</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($users as $i => $u)
                @php
                $roleClr = $u->role==='admin' ? 'badge-red' : ($u->role==='cms' ? 'badge-blue' : 'badge-green');
                $roleAv  = $u->role==='admin' ? '#ef4444' : ($u->role==='cms' ? '#2563eb' : '#16a34a');
                @endphp
                <tr>
                    <td style="color:#94a3b8">{{ $users->firstItem()+$i }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div class="avatar avatar-sm" style="background:{{ $roleAv }};color:#fff">
                                {{ strtoupper(substr($u->nama ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-weight:600;font-size:13px;color:#0f172a">{{ $u->nama }}</p>
                                <p style="font-size:11px;color:#94a3b8">{{ $u->username }}</p>
                            </div>
                        </div>
                    </td>
                    <td style="color:#64748b;font-size:13px">{{ $u->email }}</td>
                    <td><span class="badge {{ $roleClr }}">{{ $u->role_label }}</span></td>
                    <td><span class="badge {{ $u->status==='aktif' ? 'badge-green' : 'badge-slate' }}">{{ $u->status==='aktif' ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td style="color:#94a3b8;font-size:12px">{{ $u->created_tm?->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('admin.users.edit',$u) }}" class="btn btn-sm btn-secondary"><i class="fas fa-pen"></i> Edit</a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy',$u) }}" onsubmit="return confirm('Hapus user ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-users"></i><p>Tidak ada user</p></div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-footer">{{ $users->links() }}</div>
</div>
@endsection
