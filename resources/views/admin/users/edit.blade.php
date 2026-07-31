@extends('layouts.admin')
@php $pageTitle = 'Edit User'; $breadcrumb = 'Admin / User / Edit'; @endphp

@section('content')
<div class="max-w-2xl">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Edit User: <span style="color:#16a34a">{{ $user->name }}</span></p>
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="form-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="form-input" minlength="6">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Role <span style="color:#ef4444">*</span></label>
                    <select name="role" class="form-input" required>
                        <option value="user"  {{ old('role',$user->role)=='user'?'selected':'' }}>Pasien</option>
                        <option value="cms"   {{ old('role',$user->role)=='cms'?'selected':'' }}>Content Manager</option>
                        <option value="admin" {{ old('role',$user->role)=='admin'?'selected':'' }}>Administrator</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:8px;padding-top:24px">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ $user->is_active?'checked':'' }} style="width:16px;height:16px;accent-color:#16a34a">
                    <label for="is_active" style="font-size:13px;font-weight:600;color:#334155;cursor:pointer">Akun Aktif</label>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
