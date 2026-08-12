@extends('layouts.cms')
@php $pageTitle = 'Syarat & Ketentuan'; $breadcrumb = 'CMS / Legal / Syarat & Ketentuan'; @endphp
@section('content')
<div style="max-width:860px">
    <div class="card">
        <div class="card-header">
            <div>
                <h3><i class="fas fa-file-contract" style="color:#2563eb;margin-right:8px"></i>Syarat & Ketentuan</h3>
                <p style="font-size:12px;color:#94a3b8;margin-top:2px">Konten ditampilkan di halaman publik <code style="background:#f1f5f9;padding:1px 5px;border-radius:4px;font-size:11px">/syarat-ketentuan</code></p>
            </div>
            <a href="{{ route('syarat-ketentuan') }}" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-up-right-from-square"></i> Lihat Halaman
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('cms.syarat-ketentuan.update') }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Isi Syarat & Ketentuan</label>
                    <p class="form-hint" style="margin-bottom:8px">Mendukung HTML. Gunakan tag seperti <code style="background:#f1f5f9;padding:1px 4px;border-radius:3px">&lt;h2&gt;</code> <code style="background:#f1f5f9;padding:1px 4px;border-radius:3px">&lt;p&gt;</code> <code style="background:#f1f5f9;padding:1px 4px;border-radius:3px">&lt;ul&gt;&lt;li&gt;</code> <code style="background:#f1f5f9;padding:1px 4px;border-radius:3px">&lt;strong&gt;</code>. Kosongkan untuk menampilkan konten default bawaan.</p>
                    <textarea name="syarat_ketentuan" rows="28" class="form-input" style="font-family:monospace;font-size:12px"
                        placeholder="<h2>1. Penerimaan Syarat</h2>&#10;<p>Isi syarat dan ketentuan di sini...</p>">{{ old('syarat_ketentuan', $setting->syarat_ketentuan) }}</textarea>
                </div>

                <div style="display:flex;gap:12px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Syarat & Ketentuan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($setting->syarat_ketentuan)
    <div class="card" style="margin-top:20px">
        <div class="card-header">
            <h3><i class="fas fa-eye" style="color:#64748b;margin-right:8px"></i>Preview</h3>
        </div>
        <div class="card-body" style="font-size:14px;line-height:1.8;color:#334155;max-height:400px;overflow-y:auto">
            {!! $setting->syarat_ketentuan !!}
        </div>
    </div>
    @endif
</div>
@endsection
