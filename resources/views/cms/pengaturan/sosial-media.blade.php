@extends('layouts.cms')
@php $pageTitle = 'Sosial Media'; $breadcrumb = 'CMS / Pengaturan / Sosial Media'; @endphp

@section('content')
<div style="max-width:520px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <p style="font-size:12px;color:#64748b;margin-bottom:20px">
            Link sosial media ditampilkan di footer website dan halaman Hubungi Kami.
        </p>

        <form method="POST" action="{{ route('cms.sosial-media.update') }}">
        @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">
                    <i class="fab fa-instagram" style="color:#e1306c;width:16px"></i> Instagram
                </label>
                <input type="text" name="instagram"
                       value="{{ old('instagram', $setting->instagram) }}"
                       class="form-input"
                       placeholder="https://instagram.com/namakers">
            </div>

            <div class="form-group">
                <label class="form-label">
                    <i class="fab fa-facebook" style="color:#1877f2;width:16px"></i> Facebook
                </label>
                <input type="text" name="facebook"
                       value="{{ old('facebook', $setting->facebook) }}"
                       class="form-input"
                       placeholder="https://facebook.com/namakers">
            </div>

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">
                    <i class="fab fa-youtube" style="color:#ff0000;width:16px"></i> YouTube
                </label>
                <input type="text" name="youtube"
                       value="{{ old('youtube', $setting->youtube) }}"
                       class="form-input"
                       placeholder="https://youtube.com/@namakers">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
