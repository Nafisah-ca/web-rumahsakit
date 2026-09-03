@extends('layouts.cms')
@php $pageTitle = 'Edit Jadwal Dokter'; $breadcrumb = 'CMS / Jadwal Dokter / Edit'; @endphp
@section('content')
<div style="max-width:700px">
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-pen" style="color:#2563eb;margin-right:6px"></i>Edit Jadwal — {{ $jadwalDokter->dokter?->nama_dokter }}</h3>
        <a href="{{ route('cms.jadwal') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif
        <form method="POST" action="{{ route('cms.jadwal.update', $jadwalDokter) }}">
            @csrf @method('PUT')
            @include('cms.jadwal._form', ['jadwalDokter' => $jadwalDokter])
            <div style="margin-top:20px;display:flex;gap:10px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('cms.jadwal') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection
