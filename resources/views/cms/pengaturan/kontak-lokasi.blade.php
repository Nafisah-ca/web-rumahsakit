@extends('layouts.cms')
@php $pageTitle = 'Kontak & Lokasi'; $breadcrumb = 'CMS / Pengaturan / Kontak & Lokasi'; @endphp

@section('content')
<div style="max-width:720px">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error" style="margin-bottom:16px">
            <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="{{ route('cms.kontak-lokasi.update') }}">
        @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                       value="{{ old('email', $setting->email) }}"
                       class="form-input">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon"
                           value="{{ old('telepon', $setting->telepon) }}"
                           class="form-input" maxlength="20">
                </div>
                <div class="form-group">
                    <label class="form-label">
                        <i class="fab fa-whatsapp" style="color:#25d366"></i> WhatsApp
                    </label>
                    <input type="text" name="whatsapp"
                           value="{{ old('whatsapp', $setting->whatsapp) }}"
                           class="form-input" maxlength="20"
                           placeholder="08123456789">
                    <p class="form-hint">Untuk tombol Chat WA di halaman Hubungi Kami.</p>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" rows="2" class="form-input">{{ old('alamat', $setting->alamat) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Jam Operasional</label>
                <input type="text" name="jam_operasional"
                       value="{{ old('jam_operasional', $setting->jam_operasional) }}"
                       class="form-input"
                       placeholder="Senin – Jumat: 08:00 – 20:00">
            </div>

            <div class="form-group" style="margin-bottom:24px">
                <label class="form-label">Google Maps Embed URL</label>
                <input type="text" name="google_maps"
                       value="{{ old('google_maps', $setting->google_maps) }}"
                       class="form-input"
                       placeholder="https://maps.google.com/maps?...">
                <p class="form-hint">Salin URL embed dari Google Maps (bukan URL biasa).</p>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection
