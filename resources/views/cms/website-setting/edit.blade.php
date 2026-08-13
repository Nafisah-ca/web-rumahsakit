@extends('layouts.cms')
@php $pageTitle = 'Website Setting'; $breadcrumb = 'CMS / Website Setting'; @endphp
@section('content')
<form method="POST" action="{{ route('cms.website-setting.update') }}" enctype="multipart/form-data">
@csrf @method('PUT')
@if(session('success'))<div class="alert alert-success" style="margin-bottom:20px"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
@if($errors->any())<div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
{{-- Kolom Kiri --}}
<div style="display:flex;flex-direction:column;gap:20px">
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9"><i class="fas fa-hospital" style="color:#2563eb;margin-right:6px"></i>Identitas RS</p>
        <div class="form-group">
            <label class="form-label">Nama RS <span style="color:#ef4444">*</span></label>
            <input type="text" name="nama_rumahsakit" value="{{ old('nama_rumahsakit',$setting->nama_rumahsakit) }}" class="form-input" required maxlength="150">
            <p style="font-size:11px;color:#94a3b8;margin-top:4px">Teks 1 — Judul utama di halaman Tentang Kami</p>
        </div>
        <div class="form-group">
            <label class="form-label">Motto / Tagline</label>
            <input type="text" name="motto" value="{{ old('motto',$setting->motto) }}" class="form-input" maxlength="255">
            <p style="font-size:11px;color:#94a3b8;margin-top:4px">Teks 2 — Tagline berwarna italic di bawah judul (cth: Melayani dengan Kasih Sayang)</p>
        </div>
        <div class="form-group">
            <label class="form-label">Tentang Kami</label>
            <textarea name="tentang_kami" rows="4" class="form-input">{{ old('tentang_kami',$setting->tentang_kami) }}</textarea>
            <p style="font-size:11px;color:#94a3b8;margin-top:4px">Teks 3 — Paragraf deskripsi di bawah tagline</p>
        </div>
        <div class="form-group">
            <label class="form-label">Visi</label>
            <textarea name="visi" rows="2" class="form-input">{{ old('visi',$setting->visi) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Misi</label>
            <textarea name="misi" rows="3" class="form-input">{{ old('misi',$setting->misi) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Sejarah</label>
            <textarea name="sejarah" rows="3" class="form-input">{{ old('sejarah',$setting->sejarah) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Sambutan Direktur</label>
            <textarea name="sambutan_direktur" rows="3" class="form-input">{{ old('sambutan_direktur',$setting->sambutan_direktur) }}</textarea>
        </div>
    </div>

    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9"><i class="fas fa-image" style="color:#2563eb;margin-right:6px"></i>Logo & Favicon</p>
        <div class="form-group">
            <label class="form-label">Logo Utama</label>
            @if($setting->logo)<div style="margin-bottom:8px"><img src="{{ Storage::url($setting->logo) }}" style="height:48px;border-radius:4px"></div>@endif
            <input type="file" name="logo" accept="image/*" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Favicon <span style="font-size:11px;color:#94a3b8">(32×32px)</span></label>
            @if($setting->favicon)<div style="margin-bottom:8px"><img src="{{ Storage::url($setting->favicon) }}" style="height:32px"></div>@endif
            <input type="file" name="favicon" accept="image/*" class="form-input">
        </div>
    </div>
</div>

{{-- Kolom Kanan --}}
<div style="display:flex;flex-direction:column;gap:20px">
    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9"><i class="fas fa-phone" style="color:#2563eb;margin-right:6px"></i>Kontak & Alamat</p>
        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email',$setting->email) }}" class="form-input">
        </div>
        <div class="form-group">
            <label class="form-label">Telepon</label>
            <input type="text" name="telepon" value="{{ old('telepon',$setting->telepon) }}" class="form-input" maxlength="20">
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fab fa-whatsapp" style="color:#25d366;width:16px"></i> Nomor WhatsApp</label>
            <input type="text" name="whatsapp" value="{{ old('whatsapp',$setting->whatsapp) }}" class="form-input" maxlength="20" placeholder="cth: 08123456789 atau +6281234567890">
            <p style="font-size:11px;color:#94a3b8;margin-top:4px">Digunakan untuk tombol Chat WA di halaman Hubungi Kami</p>
        </div>
        <div class="form-group">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="2" class="form-input">{{ old('alamat',$setting->alamat) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Google Maps Embed URL</label>
            <input type="text" name="google_maps" value="{{ old('google_maps',$setting->google_maps) }}" class="form-input" placeholder="https://maps.google.com/maps?...">
        </div>
        <div class="form-group">
            <label class="form-label">Jam Operasional</label>
            <input type="text" name="jam_operasional" value="{{ old('jam_operasional',$setting->jam_operasional) }}" class="form-input" placeholder="Senin - Jumat: 08:00 - 20:00">
        </div>
    </div>

    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9">
            <i class="fas fa-mosque" style="color:#2563eb;margin-right:6px"></i>Jadwal Sholat
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:14px">Ditampilkan berganti-ganti di topbar website. Format: HH:MM (24 jam)</p>
        @php
            $sholat = json_decode($setting->jadwal_sholat ?? '{}', true) ?? [];
        @endphp
        <div class="form-row">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-sun" style="color:#f59e0b;width:14px"></i> Subuh</label>
                <input type="time" name="sholat_subuh" value="{{ old('sholat_subuh', $sholat['subuh'] ?? '04:30') }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-sun" style="color:#f97316;width:14px"></i> Dzuhur</label>
                <input type="time" name="sholat_dzuhur" value="{{ old('sholat_dzuhur', $sholat['dzuhur'] ?? '12:00') }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-cloud-sun" style="color:#eab308;width:14px"></i> Ashar</label>
                <input type="time" name="sholat_ashar" value="{{ old('sholat_ashar', $sholat['ashar'] ?? '15:20') }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-sunset" style="color:#ef4444;width:14px"></i> Maghrib</label>
                <input type="time" name="sholat_maghrib" value="{{ old('sholat_maghrib', $sholat['maghrib'] ?? '17:52') }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-moon" style="color:#6366f1;width:14px"></i> Isya</label>
                <input type="time" name="sholat_isya" value="{{ old('sholat_isya', $sholat['isya'] ?? '19:06') }}" class="form-input">
            </div>
        </div>
    </div>

    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9"><i class="fas fa-share-nodes" style="color:#2563eb;margin-right:6px"></i>Media Sosial</p>
        <div class="form-group">
            <label class="form-label"><i class="fab fa-instagram" style="color:#e1306c;width:16px"></i> Instagram</label>
            <input type="text" name="instagram" value="{{ old('instagram',$setting->instagram) }}" class="form-input" placeholder="https://instagram.com/...">
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fab fa-facebook" style="color:#1877f2;width:16px"></i> Facebook</label>
            <input type="text" name="facebook" value="{{ old('facebook',$setting->facebook) }}" class="form-input" placeholder="https://facebook.com/...">
        </div>
        <div class="form-group">
            <label class="form-label"><i class="fab fa-youtube" style="color:#ff0000;width:16px"></i> YouTube</label>
            <input type="text" name="youtube" value="{{ old('youtube',$setting->youtube) }}" class="form-input" placeholder="https://youtube.com/...">
        </div>
    </div>

    <div class="card card-body">
        <p style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9"><i class="fas fa-pen" style="color:#2563eb;margin-right:6px"></i>Footer</p>
        <div class="form-group">
            <label class="form-label">Footer Text</label>
            <textarea name="footer" rows="2" class="form-input">{{ old('footer',$setting->footer) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Copyright</label>
            <input type="text" name="copyright" value="{{ old('copyright',$setting->copyright) }}" class="form-input" placeholder="© {{ date('Y') }} RS Sari Sehat">
        </div>
    </div>
</div>
</div>

<div style="margin-top:24px">
    <button type="submit" class="btn btn-primary" style="padding:12px 32px">
        <i class="fas fa-save"></i> Simpan Semua Perubahan
    </button>
</div>
</form>
@endsection
