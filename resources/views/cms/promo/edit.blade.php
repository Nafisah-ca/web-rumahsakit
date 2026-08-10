@extends('layouts.cms')
@php $pageTitle = 'Edit Promo'; $breadcrumb = 'CMS / Promo / Edit'; @endphp
@section('content')
<div class="max-w-3xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.promo.update',$promo) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul Promo <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul',$promo->judul) }}" class="form-input" required maxlength="200">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Gambar Utama Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    @if($promo->gambar)
                    <div style="margin-bottom:8px"><img src="{{ Storage::url($promo->gambar) }}" style="max-height:100px;border-radius:8px"></div>
                    @endif
                    <input type="file" name="gambar" class="form-input" accept="image/*">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi <span style="color:#ef4444">*</span></label>
                    <textarea name="deskripsi" rows="6" class="form-input" required>{{ old('deskripsi',$promo->deskripsi) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai',$promo->tanggal_mulai?->format('Y-m-d')) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai',$promo->tanggal_selesai?->format('Y-m-d')) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="nonaktif" {{ old('status',$promo->status)=='nonaktif'?'selected':'' }}>Nonaktif</option>
                        <option value="aktif"    {{ old('status',$promo->status)=='aktif'?'selected':'' }}>Aktif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('cms.promo') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
