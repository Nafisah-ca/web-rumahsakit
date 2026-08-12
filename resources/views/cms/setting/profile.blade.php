@extends('layouts.cms')
@php $pageTitle = 'Profile Saya'; $breadcrumb = 'CMS / Profile'; @endphp

@section('content')
<div class="max-w-2xl" style="max-width:640px">

    <div class="card card-body">
        <h3 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">
            <i class="fas fa-user-pen" style="color:#2563eb;margin-right:8px"></i>Edit Profile
        </h3>

        @if($errors->any())
        <div class="form-error mb-4">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.profile.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Foto Profil --}}
            <div class="form-group" style="display:flex;align-items:center;gap:20px;padding:16px;background:#f8fafc;border-radius:14px;margin-bottom:20px">
                <div id="foto-preview-wrap" style="flex-shrink:0">
                    @if($user->foto)
                        <img id="foto-preview-img" src="{{ Storage::url($user->foto) }}"
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0">
                    @else
                        <div id="foto-preview-initial"
                             style="width:80px;height:80px;border-radius:50%;background:#2563eb;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;font-weight:700;border:3px solid #e2e8f0">
                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                        </div>
                        <img id="foto-preview-img" src=""
                             style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #e2e8f0;display:none">
                    @endif
                </div>
                <div style="flex:1">
                    <p style="font-size:13px;font-weight:600;color:#334155;margin-bottom:4px">Foto Profil</p>
                    <p style="font-size:11px;color:#94a3b8;margin-bottom:10px">JPG, PNG maksimal 2MB</p>
                    <label style="display:inline-flex;align-items:center;gap:7px;padding:7px 14px;background:#fff;border:1px solid #e2e8f0;border-radius:9px;cursor:pointer;font-size:12px;font-weight:600;color:#475569;transition:all .15s"
                           onmouseover="this.style.borderColor='#2563eb';this.style.color='#2563eb'"
                           onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#475569'">
                        <i class="fas fa-upload"></i> Pilih Foto
                        <input type="file" name="foto" id="foto-input" accept="image/*" style="display:none">
                    </label>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Lengkap <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" class="form-input" required maxlength="150">
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:#ef4444">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input" required maxlength="150">
                </div>
                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" class="form-input" maxlength="20" placeholder="08xx...">
                </div>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <input type="text" value="{{ $user->username }}" class="form-input" disabled
                           style="background:#f8fafc;color:#94a3b8">
                    <p class="form-hint">Username tidak dapat diubah</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <input type="text" value="Content Manager" class="form-input" disabled
                           style="background:#f8fafc;color:#94a3b8">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:4px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('cms.setting.password') }}" class="btn btn-secondary">
                    <i class="fas fa-lock"></i> Ganti Password
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('foto-input')?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);
    const img = document.getElementById('foto-preview-img');
    const init = document.getElementById('foto-preview-initial');
    img.src = url;
    img.style.display = 'block';
    if (init) init.style.display = 'none';
});
</script>
@endpush
