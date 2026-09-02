@extends('layouts.cms')
@php $pageTitle = 'Logo & Tampilan'; $breadcrumb = 'CMS / Pengaturan / Logo & Tampilan'; @endphp

@section('content')
<div style="max-width:520px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.logo-tampilan.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Logo Utama</label>
                @if($setting->logo)
                <div style="margin-bottom:10px;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;display:inline-block">
                    <img src="{{ Storage::url($setting->logo) }}" style="height:52px;border-radius:4px">
                </div><br>
                @endif
                <input type="file" name="logo" accept="image/*" class="form-input">
                <p class="form-hint">PNG transparan direkomendasikan. Kosongkan jika tidak ingin mengubah.</p>
            </div>

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">
                    Favicon
                    <span style="font-size:11px;color:#94a3b8">(32×32 px, .ico atau .png)</span>
                </label>
                @if($setting->favicon)
                <div style="margin-bottom:10px;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;display:inline-block">
                    <img src="{{ Storage::url($setting->favicon) }}" style="height:32px">
                </div><br>
                @endif
                <input type="file" name="favicon" accept="image/*" class="form-input">
                <p class="form-hint">Tampil di tab browser. Kosongkan jika tidak ingin mengubah.</p>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
