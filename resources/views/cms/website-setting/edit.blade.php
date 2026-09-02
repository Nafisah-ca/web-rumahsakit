@extends('layouts.cms')
@php $pageTitle = 'Pengaturan Website'; $breadcrumb = 'CMS / Pengaturan Website'; @endphp

@section('content')
<form method="POST" action="{{ route('cms.website-setting.update') }}" enctype="multipart/form-data">
@csrf @method('PUT')

@if($errors->any())
<div class="form-error" style="margin-bottom:16px">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

{{-- ── NAVIGASI ANCHOR ─────────────────────────────────────────── --}}
<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:24px">
    @foreach([
        ['#identitas-rs','fa-hospital','Identitas RS'],
        ['#kontak-lokasi','fa-phone','Kontak & Lokasi'],
        ['#logo-tampilan','fa-image','Logo & Tampilan'],
        ['#statistik','fa-chart-bar','Statistik'],
        ['#sosial-media','fa-share-nodes','Sosial Media'],
        ['#footer-section','fa-align-left','Footer'],
    ] as [$href,$ico,$label])
    <a href="{{ $href }}"
       style="display:inline-flex;align-items:center;gap:6px;padding:7px 14px;
              background:#f1f5f9;border-radius:20px;font-size:12px;font-weight:600;
              color:#475569;text-decoration:none;border:1px solid #e2e8f0;
              transition:all .15s"
       onmouseover="this.style.background='#2563eb';this.style.color='#fff';this.style.borderColor='#2563eb'"
       onmouseout="this.style.background='#f1f5f9';this.style.color='#475569';this.style.borderColor='#e2e8f0'">
        <i class="fas {{ $ico }}" style="font-size:11px"></i> {{ $label }}
    </a>
    @endforeach
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">

{{-- ══════════════════════════════════════════════════════════
     KOLOM KIRI
══════════════════════════════════════════════════════════ --}}
<div style="display:flex;flex-direction:column;gap:20px">

    {{-- ── 1. IDENTITAS RS ─────────────────────────────────── --}}
    <div class="card card-body" id="identitas-rs">
        <p class="ws-section-title">
            <i class="fas fa-hospital" style="color:#2563eb"></i> Identitas Rumah Sakit
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:14px">
            Data ini tampil di navbar, halaman Tentang Kami, dan bagian bawah website.
        </p>

        <div class="form-group">
            <label class="form-label">Nama Rumah Sakit <span style="color:#ef4444">*</span></label>
            <input type="text" name="nama_rumahsakit"
                   value="{{ old('nama_rumahsakit', $setting->nama_rumahsakit) }}"
                   class="form-input" required maxlength="150">
        </div>
        <div class="form-group">
            <label class="form-label">Motto / Tagline</label>
            <input type="text" name="motto"
                   value="{{ old('motto', $setting->motto) }}"
                   class="form-input" maxlength="255"
                   placeholder="Contoh: Melayani dengan Kasih Sayang">
        </div>
        <div class="form-group">
            <label class="form-label">Tentang Kami</label>
            <textarea name="tentang_kami" rows="4" class="form-input">{{ old('tentang_kami', $setting->tentang_kami) }}</textarea>
            <p class="form-hint">Deskripsi singkat yang muncul di halaman Tentang Kami dan homepage.</p>
        </div>
        <div class="form-group">
            <label class="form-label">Visi</label>
            <textarea name="visi" rows="2" class="form-input">{{ old('visi', $setting->visi) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Misi</label>
            <textarea name="misi" rows="3" class="form-input">{{ old('misi', $setting->misi) }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Sejarah</label>
            <textarea name="sejarah" rows="3" class="form-input">{{ old('sejarah', $setting->sejarah) }}</textarea>
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Sambutan Direktur</label>
            <textarea name="sambutan_direktur" rows="4" class="form-input">{{ old('sambutan_direktur', $setting->sambutan_direktur) }}</textarea>
            <p class="form-hint">Tampil di section "Sambutan Direktur" pada halaman Tentang Kami.</p>
        </div>
    </div>

    {{-- ── 3. LOGO & TAMPILAN ───────────────────────────────── --}}
    <div class="card card-body" id="logo-tampilan">
        <p class="ws-section-title">
            <i class="fas fa-image" style="color:#2563eb"></i> Logo & Tampilan
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:14px">
            Logo muncul di navbar dan footer. Favicon muncul di tab browser.
        </p>

        <div class="form-group">
            <label class="form-label">Logo Utama</label>
            @if($setting->logo)
            <div style="margin-bottom:10px;padding:8px;background:#f8fafc;border:1px solid #e2e8f0;
                        border-radius:8px;display:inline-block">
                <img src="{{ Storage::url($setting->logo) }}" style="height:48px;border-radius:4px">
            </div><br>
            @endif
            <input type="file" name="logo" accept="image/*" class="form-input">
            <p class="form-hint">PNG transparan direkomendasikan. Kosongkan jika tidak ingin mengubah.</p>
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Favicon <span style="font-size:11px;color:#94a3b8">(32×32 px, .ico atau .png)</span></label>
            @if($setting->favicon)
            <div style="margin-bottom:10px;padding:8px;background:#f8fafc;border:1px solid #e2e8f0;
                        border-radius:8px;display:inline-block">
                <img src="{{ Storage::url($setting->favicon) }}" style="height:32px">
            </div><br>
            @endif
            <input type="file" name="favicon" accept="image/*" class="form-input">
            <p class="form-hint">Kosongkan jika tidak ingin mengubah.</p>
        </div>
    </div>

</div>{{-- /kolom kiri --}}

{{-- ══════════════════════════════════════════════════════════
     KOLOM KANAN
══════════════════════════════════════════════════════════ --}}
<div style="display:flex;flex-direction:column;gap:20px">

    {{-- ── 2. KONTAK & LOKASI ──────────────────────────────── --}}
    <div class="card card-body" id="kontak-lokasi">
        <p class="ws-section-title">
            <i class="fas fa-phone" style="color:#2563eb"></i> Kontak & Lokasi
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:14px">
            Tampil di halaman Hubungi Kami, footer, dan halaman Tentang Kami.
        </p>

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
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Google Maps Embed URL</label>
            <input type="text" name="google_maps"
                   value="{{ old('google_maps', $setting->google_maps) }}"
                   class="form-input"
                   placeholder="https://maps.google.com/maps?...">
            <p class="form-hint">Salin URL embed dari Google Maps (bukan URL biasa).</p>
        </div>
    </div>

    {{-- ── 4. STATISTIK ────────────────────────────────────── --}}
    <div class="card card-body" id="statistik">
        <p class="ws-section-title">
            <i class="fas fa-chart-bar" style="color:#2563eb"></i> Statistik Homepage
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:14px">
            Angka ditampilkan di bagian <strong>"Sekilas Tentang"</strong> homepage
            dengan tanda <strong>+</strong> di belakangnya.
        </p>

        <div class="form-row">
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">
                    <i class="fas fa-stethoscope" style="color:#16a34a;width:14px"></i>
                    Jumlah Spesialisasi
                </label>
                <input type="number" name="jumlah_spesialisasi"
                       value="{{ old('jumlah_spesialisasi', $setting->jumlah_spesialisasi ?? 5) }}"
                       class="form-input" min="0" max="9999">
                <p class="form-hint">Tampil sebagai: <strong>{{ $setting->jumlah_spesialisasi ?? 5 }}+</strong> Spesialisasi</p>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">
                    <i class="fas fa-handshake" style="color:#16a34a;width:14px"></i>
                    Jumlah Mitra Asuransi
                </label>
                <input type="number" name="jumlah_mitra_asuransi"
                       value="{{ old('jumlah_mitra_asuransi', $setting->jumlah_mitra_asuransi ?? 50) }}"
                       class="form-input" min="0" max="9999">
                <p class="form-hint">Tampil sebagai: <strong>{{ $setting->jumlah_mitra_asuransi ?? 50 }}+</strong> Mitra Asuransi</p>
            </div>
        </div>
    </div>

    {{-- ── 5. SOSIAL MEDIA ─────────────────────────────────── --}}
    <div class="card card-body" id="sosial-media">
        <p class="ws-section-title">
            <i class="fas fa-share-nodes" style="color:#2563eb"></i> Sosial Media
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:14px">
            Link ini ditampilkan di footer website dan halaman Hubungi Kami.
        </p>

        <div class="form-group">
            <label class="form-label">
                <i class="fab fa-instagram" style="color:#e1306c;width:16px"></i> Instagram
            </label>
            <input type="text" name="instagram"
                   value="{{ old('instagram', $setting->instagram) }}"
                   class="form-input" placeholder="https://instagram.com/...">
        </div>
        <div class="form-group">
            <label class="form-label">
                <i class="fab fa-facebook" style="color:#1877f2;width:16px"></i> Facebook
            </label>
            <input type="text" name="facebook"
                   value="{{ old('facebook', $setting->facebook) }}"
                   class="form-input" placeholder="https://facebook.com/...">
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">
                <i class="fab fa-youtube" style="color:#ff0000;width:16px"></i> YouTube
            </label>
            <input type="text" name="youtube"
                   value="{{ old('youtube', $setting->youtube) }}"
                   class="form-input" placeholder="https://youtube.com/...">
        </div>
    </div>

    {{-- ── 6. FOOTER ───────────────────────────────────────── --}}
    <div class="card card-body" id="footer-section">
        <p class="ws-section-title">
            <i class="fas fa-align-left" style="color:#2563eb"></i> Footer Website
        </p>
        <p style="font-size:11px;color:#94a3b8;margin-bottom:14px">
            Tampil di bagian paling bawah setiap halaman website.
        </p>

        <div class="form-group">
            <label class="form-label">Teks Footer</label>
            <textarea name="footer" rows="2" class="form-input">{{ old('footer', $setting->footer) }}</textarea>
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Copyright</label>
            <input type="text" name="copyright"
                   value="{{ old('copyright', $setting->copyright) }}"
                   class="form-input"
                   placeholder="© {{ date('Y') }} RS Sari Sehat. Semua hak dilindungi.">
        </div>
    </div>

</div>{{-- /kolom kanan --}}
</div>{{-- /grid --}}

{{-- ── TOMBOL SIMPAN ────────────────────────────────────────────── --}}
<div style="margin-top:28px;padding-top:20px;border-top:1px solid #f1f5f9;
            display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <p style="font-size:12px;color:#94a3b8">
        <i class="fas fa-info-circle" style="color:#2563eb"></i>
        Semua perubahan akan langsung tampil di website setelah disimpan.
    </p>
    <button type="submit" class="btn btn-primary" style="padding:11px 36px;font-size:14px">
        <i class="fas fa-save"></i> Simpan Semua Perubahan
    </button>
</div>

</form>
@endsection

@push('styles')
<style>
.ws-section-title {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}
@media (max-width: 768px) {
    form > div[style*="grid-template-columns:1fr 1fr"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endpush
