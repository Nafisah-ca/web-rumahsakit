@extends('layouts.cms')
@php $pageTitle = 'Ganti Password'; $breadcrumb = 'CMS / Ganti Password'; @endphp

@section('content')
<div style="max-width:480px">
    <div class="card card-body">
        <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">
            <i class="fas fa-lock" style="color:#2563eb;margin-right:8px"></i>Ganti Password
        </h3>

        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.setting.password.update') }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Password Lama <span style="color:#ef4444">*</span></label>
                <input type="password" name="password_lama" class="form-input" required placeholder="Masukkan password lama">
                @error('password_lama')
                <p style="color:#dc2626;font-size:12px;margin-top:4px">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:#ef4444">*</span></label>
                <input type="password" name="password_baru" class="form-input" required placeholder="Minimal 8 karakter">
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru <span style="color:#ef4444">*</span></label>
                <input type="password" name="password_baru_confirmation" class="form-input" required placeholder="Ulangi password baru">
            </div>
            <div style="display:flex;gap:10px;margin-top:4px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
                <a href="{{ route('cms.profile') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
