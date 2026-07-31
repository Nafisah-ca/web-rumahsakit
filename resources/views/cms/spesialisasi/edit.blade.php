@extends('layouts.cms')
@php $pageTitle = 'Edit Spesialisasi'; $breadcrumb = 'CMS / Spesialisasi / Edit'; @endphp
@section('content')
<div style="max-width:480px">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Edit: <span style="color:#16a34a">{{ $spesialisasi->nama_spesialis }}</span></p>
        <form method="POST" action="{{ route('cms.spesialisasi.update', $spesialisasi) }}">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif
            <div class="form-group">
                <label class="form-label">Nama Spesialisasi <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_spesialis" value="{{ old('nama_spesialis', $spesialisasi->nama_spesialis) }}" class="form-input" required maxlength="100">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-input">{{ old('deskripsi', $spesialisasi->deskripsi) }}</textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('cms.spesialisasi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
