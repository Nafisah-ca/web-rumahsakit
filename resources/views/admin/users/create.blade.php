@extends('layouts.admin')
@php $pageTitle = 'Tambah User'; $breadcrumb = 'Admin / User / Tambah'; @endphp

@section('content')
<div class="max-w-2xl">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Form Tambah User</p>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @if($errors->any())
            <div class="form-error"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password" class="form-input" required minlength="6" placeholder="Min. 6 karakter">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span style="color:#ef4444">*</span></label>
                    <input type="password" name="password_confirmation" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Role <span style="color:#ef4444">*</span></label>
                    <select name="role" class="form-input" required>
                        <option value="user"  {{ old('role')=='user'?'selected':'' }}>Pasien</option>
                        <option value="cms"   {{ old('role')=='cms'?'selected':'' }}>Content Manager</option>
                        <option value="admin" {{ old('role')=='admin'?'selected':'' }}>Administrator</option>
                    </select>
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:8px;padding-top:24px">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked style="width:16px;height:16px;accent-color:#16a34a">
                    <label for="is_active" style="font-size:13px;font-weight:600;color:#334155;cursor:pointer">Akun Aktif</label>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary">Simpan User</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
