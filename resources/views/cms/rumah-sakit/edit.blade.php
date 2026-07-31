@extends('layouts.cms')
@php $pageTitle = 'Info Rumah Sakit'; $breadcrumb = 'CMS / Pengaturan'; @endphp

@section('content')
<form method="POST" action="{{ route('cms.rumah-sakit.update') }}" enctype="multipart/form-data">
@csrf @method('PUT')

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px">
    {{-- Kolom 1: Identitas --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card card-body">
            <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                <i class="fas fa-hospital" style="color:#2563eb;margin-right:8px"></i>Identitas Rumah Sakit
            </p>
            <div class="form-group">
                <label class="form-label">Nama RS <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama" value="{{ old('nama',$rs->nama) }}" class="form-input" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Singkatan</label>
                    <input type="text" name="singkatan" value="{{ old('singkatan',$rs->singkatan) }}" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Akreditasi</label>
                    <input type="text" name="akreditasi" value="{{ old('akreditasi',$rs->akreditasi) }}" class="form-input" placeholder="Paripurna">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline',$rs->tagline) }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="deskripsi_singkat" rows="3" class="form-input">{{ old('deskripsi_singkat',$rs->deskripsi_singkat) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Visi</label>
                <textarea name="visi" rows="2" class="form-input">{{ old('visi',$rs->visi) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Misi</label>
                <textarea name="misi" rows="3" class="form-input">{{ old('misi',$rs->misi) }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Nilai Utama <span style="font-size:11px;color:#94a3b8">(satu per baris)</span></label>
                <textarea name="nilai_utama" rows="4" class="form-input" style="font-family:monospace">{{ old('nilai_utama', is_array($rs->nilai_utama) ? implode("\n",$rs->nilai_utama) : $rs->nilai_utama) }}</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tahun Berdiri</label>
                    <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri',$rs->tahun_berdiri) }}" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah Tempat Tidur</label>
                    <input type="number" name="jumlah_tempat_tidur" value="{{ old('jumlah_tempat_tidur',$rs->jumlah_tempat_tidur) }}" class="form-input">
                </div>
            </div>
        </div>

        {{-- Logo --}}
        <div class="card card-body">
            <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                <i class="fas fa-image" style="color:#2563eb;margin-right:8px"></i>Logo
            </p>
            <div class="form-group">
                <label class="form-label">Logo Utama</label>
                @if($rs->logo)
                <img src="{{ Storage::url($rs->logo) }}" style="height:48px;margin-bottom:8px;display:block">
                @endif
                <input type="file" name="logo" accept="image/*" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Logo Putih <span style="font-size:11px;color:#94a3b8">(untuk background gelap)</span></label>
                @if($rs->logo_putih)
                <img src="{{ Storage::url($rs->logo_putih) }}" style="height:48px;margin-bottom:8px;display:block;background:#1e293b;padding:4px;border-radius:6px">
                @endif
                <input type="file" name="logo_putih" accept="image/*" class="form-input">
            </div>
        </div>
    </div>

    {{-- Kolom 2: Kontak & Sosmed --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card card-body">
            <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                <i class="fas fa-phone" style="color:#2563eb;margin-right:8px"></i>Kontak
            </p>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email',$rs->email) }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon',$rs->telepon) }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">WhatsApp <span style="font-size:11px;color:#94a3b8">(nomor saja, contoh: 6281234567890)</span></label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp',$rs->whatsapp) }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Website</label>
                <input type="url" name="website" value="{{ old('website',$rs->website) }}" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Pusat</label>
                <textarea name="alamat" rows="2" class="form-input">{{ old('alamat',$rs->alamat) }}</textarea>
            </div>
        </div>

        <div class="card card-body">
            <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9">
                <i class="fas fa-share-nodes" style="color:#2563eb;margin-right:8px"></i>Media Sosial
            </p>
            @foreach([
                ['instagram','fab fa-instagram','Instagram URL'],
                ['facebook','fab fa-facebook','Facebook URL'],
                ['twitter','fab fa-twitter','Twitter/X URL'],
                ['youtube','fab fa-youtube','YouTube URL'],
                ['tiktok','fab fa-tiktok','TikTok URL'],
            ] as [$field,$icon,$placeholder])
            <div class="form-group">
                <label class="form-label" style="display:flex;align-items:center;gap:6px">
                    <i class="{{ $icon }}" style="width:14px;text-align:center;color:#64748b"></i> {{ ucfirst($field) }}
                </label>
                <input type="text" name="{{ $field }}" value="{{ old($field,$rs->{$field}) }}" class="form-input" placeholder="{{ $placeholder }}">
            </div>
            @endforeach
        </div>
    </div>
</div>

<div style="margin-top:24px">
    <button type="submit" class="btn btn-primary" style="padding:12px 32px;font-size:14px">
        <i class="fas fa-save"></i> Simpan Semua Perubahan
    </button>
</div>
</form>
@endsection
