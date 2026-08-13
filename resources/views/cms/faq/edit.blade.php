@extends('layouts.cms')
@php $pageTitle = 'Edit FAQ'; $breadcrumb = 'CMS / FAQ / Edit'; @endphp
@section('content')
<div style="max-width:720px">
    <div style="margin-bottom:16px">
        <a href="{{ route('cms.faq') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-header"><h3>Edit FAQ</h3></div>
        <div class="card-body">

            @if($errors->any())
            <div class="form-error">
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('cms.faq.update', $faq) }}">
                @csrf @method('PUT')

                <div class="form-group">
                    <label class="form-label">Pertanyaan <span style="color:#dc2626">*</span></label>
                    <input type="text" name="pertanyaan" value="{{ old('pertanyaan', $faq->pertanyaan) }}"
                           class="form-input" placeholder="Tuliskan pertanyaan..." maxlength="300" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Jawaban <span style="color:#dc2626">*</span></label>
                    <textarea name="jawaban" rows="5" class="form-input"
                              placeholder="Tuliskan jawaban lengkap..." required>{{ old('jawaban', $faq->jawaban) }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Urutan Tampil</label>
                        <input type="number" name="urutan" value="{{ old('urutan', $faq->urutan) }}"
                               class="form-input" min="0" max="9999">
                        <p class="form-hint">Angka kecil tampil lebih dahulu.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span style="color:#dc2626">*</span></label>
                        <select name="status" class="form-input" required>
                            <option value="aktif"    {{ old('status', $faq->status)==='aktif'    ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $faq->status)==='nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex;gap:10px;margin-top:8px">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Perbarui FAQ
                    </button>
                    <a href="{{ route('cms.faq') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
