@extends('layouts.cms')
@php $pageTitle = 'Footer Website'; $breadcrumb = 'CMS / Pengaturan / Footer'; @endphp

@section('content')
<div style="max-width:520px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <p style="font-size:12px;color:#64748b;margin-bottom:20px">
            Teks ini tampil di bagian paling bawah setiap halaman website.
        </p>

        <form method="POST" action="{{ route('cms.footer.update') }}">
        @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Teks Footer</label>
                <textarea name="footer" rows="3" class="form-input">{{ old('footer', $setting->footer) }}</textarea>
            </div>

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">Copyright</label>
                <input type="text" name="copyright"
                       value="{{ old('copyright', $setting->copyright) }}"
                       class="form-input"
                       placeholder="© {{ date('Y') }} RS Sari Sehat. Semua hak dilindungi.">
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
