@extends('layouts.admin')
@php $pageTitle = 'Login CMS'; $breadcrumb = 'Admin / Login CMS'; @endphp

@section('content')
<div class="max-w-md mx-auto">

    <div class="card card-body" style="padding:40px 36px">

        {{-- Icon --}}
        <div style="text-align:center;margin-bottom:28px">
            <div style="width:64px;height:64px;background:#dbeafe;border-radius:18px;display:inline-flex;align-items:center;justify-content:center;font-size:26px;margin-bottom:16px">
                <i class="fas fa-pen-nib" style="color:#2563eb"></i>
            </div>
            <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin-bottom:6px">Login CMS</h2>
            <p style="font-size:13px;color:#64748b">Masukkan kredensial akun CMS untuk melanjutkan</p>
        </div>

        {{-- Info admin yang sedang login --}}
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:12px 14px;display:flex;align-items:center;gap:10px;margin-bottom:20px">
            <div style="width:34px;height:34px;background:#16a34a;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;font-weight:700;flex-shrink:0">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>
            <div>
                <p style="font-size:12px;font-weight:700;color:#166534">{{ Auth::user()->nama }}</p>
                <p style="font-size:11px;color:#4ade80">Login sebagai Administrator</p>
            </div>
            <span class="badge badge-green" style="margin-left:auto;font-size:10px">Admin</span>
        </div>

        {{-- Flash messages --}}
        @if(session('info'))
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:#1d4ed8;display:flex;align-items:center;gap:8px">
            <i class="fas fa-circle-info"></i> {{ session('info') }}
        </div>
        @endif

        @if($errors->any())
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:10px 14px;margin-bottom:16px;font-size:13px;color:#dc2626;display:flex;align-items:center;gap:8px">
            <i class="fas fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.cms-login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email CMS <span style="color:#ef4444">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-input" placeholder="email@cms.com"
                       autocomplete="off" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password CMS <span style="color:#ef4444">*</span></label>
                <div style="position:relative">
                    <input type="password" name="password" id="pw-cms"
                           class="form-input" placeholder="••••••••"
                           autocomplete="new-password" required
                           style="padding-right:42px">
                    <button type="button" onclick="togglePw()"
                            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#94a3b8;font-size:14px">
                        <i class="fas fa-eye" id="pw-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:12px;margin-top:4px">
                <i class="fas fa-arrow-right-to-bracket"></i> Masuk ke CMS
            </button>
        </form>

        <div style="text-align:center;margin-top:16px">
            <a href="{{ route('admin.dashboard') }}" style="font-size:12px;color:#94a3b8;text-decoration:none;hover:color:#64748b">
                <i class="fas fa-arrow-left" style="margin-right:4px"></i> Kembali ke Dashboard Admin
            </a>
        </div>
    </div>

    <p style="text-align:center;font-size:11px;color:#94a3b8;margin-top:12px">
        Masuk ke CMS tidak mengubah role atau profil Admin Anda
    </p>
</div>
@endsection

@push('scripts')
<script>
function togglePw() {
    const inp = document.getElementById('pw-cms');
    const eye = document.getElementById('pw-eye');
    if (inp.type === 'password') {
        inp.type = 'text';
        eye.className = 'fas fa-eye-slash';
    } else {
        inp.type = 'password';
        eye.className = 'fas fa-eye';
    }
}
</script>
@endpush
