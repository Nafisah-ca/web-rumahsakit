@extends('layouts.admin')
@php $pageTitle = 'Profile Saya'; $breadcrumb = 'Admin / Profile'; @endphp

@section('content')
<div style="max-width:600px">

    {{-- Header card --}}
    <div class="card" style="margin-bottom:20px">
        <div class="card-body" style="display:flex;align-items:center;gap:20px">
            <div style="width:64px;height:64px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <span style="font-size:26px;font-weight:800;color:#16a34a">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
            </div>
            <div>
                <p style="font-size:18px;font-weight:800;color:#0f172a">{{ $user->nama }}</p>
                <p style="font-size:13px;color:#64748b;margin-top:3px">{{ $user->email }}</p>
                <span class="badge badge-red" style="margin-top:6px;font-size:11px">{{ $user->role_label }}</span>
            </div>
        </div>
    </div>

    {{-- Form edit --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-pen" style="color:#16a34a;margin-right:8px"></i>Edit Profile</h3>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px">
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                           class="form-input @error('nama') border-red-400 @enderror"
                           placeholder="Nama lengkap admin" required>
                    @error('nama')<p class="form-hint" style="color:#dc2626">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-input @error('email') border-red-400 @enderror"
                           placeholder="email@example.com" required>
                    @error('email')<p class="form-hint" style="color:#dc2626">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">No. Handphone</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}"
                           class="form-input" placeholder="08xxxxxxxxxx">
                </div>

                <div style="display:flex;gap:10px;align-items:center;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Quick link ganti password --}}
    <div style="margin-top:16px;padding:14px 18px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;display:flex;align-items:center;justify-content:space-between">
        <div>
            <p style="font-size:13px;font-weight:600;color:#334155"><i class="fas fa-lock" style="color:#64748b;margin-right:8px"></i>Password</p>
            <p style="font-size:12px;color:#94a3b8;margin-top:2px">Ubah password akun Anda secara berkala</p>
        </div>
        <a href="{{ route('admin.setting.password') }}" class="btn btn-secondary btn-sm">
            Ganti Password <i class="fas fa-arrow-right"></i>
        </a>
    </div>

</div>
@endsection
