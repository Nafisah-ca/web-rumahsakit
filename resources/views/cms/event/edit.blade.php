@extends('layouts.cms')
@php $pageTitle = 'Edit Event'; $breadcrumb = 'CMS / Event / Edit'; @endphp
@section('content')
<div class="max-w-3xl">
    <div class="card card-body">
        @if($errors->any())
        <div class="form-error mb-4"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <form method="POST" action="{{ route('cms.event.update',$event) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-row">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Judul Event <span style="color:#ef4444">*</span></label>
                    <input type="text" name="judul" value="{{ old('judul',$event->judul) }}" class="form-input" required maxlength="200">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Gambar Utama Baru <span style="font-size:11px;color:#94a3b8">(kosongkan jika tidak diubah)</span></label>
                    @if($event->gambar)
                    <div style="margin-bottom:8px"><img src="{{ Storage::url($event->gambar) }}" style="max-height:100px;border-radius:8px"></div>
                    @endif
                    <input type="file" name="gambar" class="form-input" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Event <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_event" value="{{ old('tanggal_event',$event->tanggal_event?->format('Y-m-d')) }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Waktu Event <span style="color:#ef4444">*</span></label>
                    <input type="time" name="waktu_event" value="{{ old('waktu_event', substr($event->waktu_event??'',0,5)) }}" class="form-input" required>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="lokasi" value="{{ old('lokasi',$event->lokasi) }}" class="form-input" maxlength="255">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi <span style="color:#ef4444">*</span></label>
                    <textarea name="deskripsi" rows="6" class="form-input" required>{{ old('deskripsi',$event->deskripsi) }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="nonaktif" {{ old('status',$event->status)=='nonaktif'?'selected':'' }}>Nonaktif</option>
                        <option value="aktif"    {{ old('status',$event->status)=='aktif'?'selected':'' }}>Aktif</option>
                    </select>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('cms.event') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
