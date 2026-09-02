@extends('layouts.cms')
@php $pageTitle = 'Statistik Homepage'; $breadcrumb = 'CMS / Pengaturan / Statistik'; @endphp

@section('content')
<div style="max-width:520px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <p style="font-size:12px;color:#64748b;margin-bottom:20px">
            Angka di bawah ditampilkan di bagian <strong>"Sekilas Tentang"</strong> pada homepage
            dengan tanda <strong>+</strong> di belakangnya.
        </p>

        <form method="POST" action="{{ route('cms.statistik.update') }}">
        @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">
                    <i class="fas fa-stethoscope" style="color:#16a34a;width:14px"></i>
                    Jumlah Spesialisasi
                </label>
                <input type="number" name="jumlah_spesialisasi"
                       value="{{ old('jumlah_spesialisasi', $setting->jumlah_spesialisasi ?? 5) }}"
                       class="form-input" min="0" max="9999" style="width:160px">
                <p class="form-hint">
                    Akan tampil sebagai: <strong>{{ $setting->jumlah_spesialisasi ?? 5 }}+</strong> Spesialisasi
                </p>
            </div>

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">
                    <i class="fas fa-handshake" style="color:#16a34a;width:14px"></i>
                    Jumlah Mitra Asuransi
                </label>
                <input type="number" name="jumlah_mitra_asuransi"
                       value="{{ old('jumlah_mitra_asuransi', $setting->jumlah_mitra_asuransi ?? 50) }}"
                       class="form-input" min="0" max="9999" style="width:160px">
                <p class="form-hint">
                    Akan tampil sebagai: <strong>{{ $setting->jumlah_mitra_asuransi ?? 50 }}+</strong> Mitra Asuransi
                </p>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
