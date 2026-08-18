@extends('layouts.admin')
@php $pageTitle = 'Edit Spesialisasi'; $breadcrumb = 'Admin / Spesialisasi / Edit'; @endphp
@section('content')
<div style="max-width:560px">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Edit Spesialisasi</p>
        <form method="POST" action="{{ route('admin.spesialisasi.update',$spesialisasi) }}">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <div class="form-group">
                <label class="form-label">Nama Spesialisasi <span style="color:#ef4444">*</span></label>
                <input type="text" name="nama_spesialis" value="{{ old('nama_spesialis',$spesialisasi->nama_spesialis) }}" class="form-input" required maxlength="100">
                @error('nama_spesialis')<p style="color:#ef4444;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="form-input">{{ old('deskripsi',$spesialisasi->deskripsi) }}</textarea>
            </div>

            {{-- ── Live Antrian Settings ── --}}
            <p style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin:20px 0 12px;padding-top:16px;border-top:1px solid #f1f5f9">
                <i class="fas fa-list-ol" style="color:#2563eb;margin-right:6px"></i>Pengaturan Live Antrian
            </p>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
                <div class="form-group">
                    <label class="form-label">Icon <span style="font-size:11px;color:#94a3b8">(FontAwesome)</span></label>
                    <div style="display:flex;gap:8px;align-items:center">
                        <span id="icon-preview" style="width:32px;height:32px;background:#dbeafe;color:#1d4ed8;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0">
                            <i class="fas {{ old('icon',$spesialisasi->icon ?? 'fa-stethoscope') }}"></i>
                        </span>
                        <input type="text" name="icon" id="icon-input" value="{{ old('icon',$spesialisasi->icon ?? 'fa-stethoscope') }}" class="form-input" maxlength="60" placeholder="fa-stethoscope">
                    </div>
                    <p style="font-size:11px;color:#94a3b8;margin-top:4px">Kelas FA, cth: fa-heartbeat, fa-brain, fa-baby, fa-eye</p>
                </div>

                <div class="form-group">
                    <label class="form-label">Warna Poli</label>
                    <select name="warna" id="warna-select" class="form-input">
                        @foreach(\App\Models\Spesialisasi::WARNA_OPTIONS as $val => $label)
                            <option value="{{ $val }}" {{ old('warna',$spesialisasi->warna ?? 'blue') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Estimasi Waktu Tunggu per Pasien <span style="color:#ef4444">*</span></label>
                <div style="display:flex;align-items:center;gap:10px">
                    <input type="number" name="estimasi_menit" value="{{ old('estimasi_menit',$spesialisasi->estimasi_menit ?? 15) }}" class="form-input" min="1" max="300" style="width:120px">
                    <span style="font-size:13px;color:#64748b">menit / pasien</span>
                </div>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">Digunakan untuk menghitung estimasi tunggu di tampilan Live Antrian</p>
                @error('estimasi_menit')<p style="color:#ef4444;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.spesialisasi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.getElementById('icon-input').addEventListener('input', function () {
    const preview = document.getElementById('icon-preview');
    preview.innerHTML = '<i class="fas ' + this.value + '"></i>';
});
</script>
@endpush
