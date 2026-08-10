@extends('layouts.admin')
@php $pageTitle = 'Ganti Password'; $breadcrumb = 'Admin / Setting / Ganti Password'; @endphp

@section('content')
<div style="max-width:520px">

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-lock" style="color:#16a34a;margin-right:8px"></i>Ganti Password</h3>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div class="form-error" style="margin-bottom:20px">
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.setting.password.update') }}">
                @csrf @method('PUT')

                {{-- Password Lama --}}
                <div class="form-group" x-data="{ show: false }">
                    <label class="form-label">Password Lama <span style="color:#ef4444">*</span></label>
                    <div style="position:relative">
                        <input :type="show ? 'text' : 'password'" name="password_lama"
                               class="form-input @error('password_lama') border-red-400 @enderror"
                               placeholder="Masukkan password lama" required
                               style="padding-right:42px">
                        <button type="button" @click="show = !show"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    @error('password_lama')
                    <p class="form-hint" style="color:#dc2626"><i class="fas fa-circle-exclamation" style="margin-right:4px"></i>{{ $message }}</p>
                    @enderror
                </div>

                <hr class="divider">

                {{-- Password Baru --}}
                <div class="form-group" x-data="{ show: false }">
                    <label class="form-label">Password Baru <span style="color:#ef4444">*</span></label>
                    <div style="position:relative">
                        <input :type="show ? 'text' : 'password'" name="password_baru"
                               class="form-input @error('password_baru') border-red-400 @enderror"
                               placeholder="Minimal 8 karakter" required
                               style="padding-right:42px">
                        <button type="button" @click="show = !show"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                    @error('password_baru')
                    <p class="form-hint" style="color:#dc2626"><i class="fas fa-circle-exclamation" style="margin-right:4px"></i>{{ $message }}</p>
                    @enderror
                    <p class="form-hint">Minimal 8 karakter.</p>
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div class="form-group" x-data="{ show: false }">
                    <label class="form-label">Konfirmasi Password Baru <span style="color:#ef4444">*</span></label>
                    <div style="position:relative">
                        <input :type="show ? 'text' : 'password'" name="password_baru_confirmation"
                               class="form-input"
                               placeholder="Ulangi password baru" required
                               style="padding-right:42px">
                        <button type="button" @click="show = !show"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;padding:0">
                            <i :class="show ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div style="display:flex;gap:10px;align-items:center;margin-top:24px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-floppy-disk"></i> Simpan Password
                    </button>
                    <a href="{{ route('admin.profile') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Info box --}}
    <div style="margin-top:16px;padding:14px 18px;background:#fffbeb;border:1px solid #fde68a;border-radius:12px">
        <p style="font-size:12px;color:#92400e;font-weight:600"><i class="fas fa-triangle-exclamation" style="margin-right:6px"></i>Tips keamanan password:</p>
        <ul style="margin-top:8px;padding-left:16px;font-size:12px;color:#78350f;line-height:1.8">
            <li>Gunakan minimal 8 karakter</li>
            <li>Kombinasikan huruf besar, huruf kecil, angka, dan simbol</li>
            <li>Jangan gunakan informasi pribadi (nama, tanggal lahir)</li>
            <li>Ganti password secara berkala</li>
        </ul>
    </div>

</div>
@endsection
