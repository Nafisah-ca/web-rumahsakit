@extends('layouts.admin')
@php $pageTitle = 'Tambah Pasien'; $breadcrumb = 'Admin / Pasien / Tambah'; @endphp
@section('content')
<div style="max-width:720px">
    <div class="card card-body">
        <p style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:20px">Form Pendaftaran Pasien</p>
        <form method="POST" action="{{ route('admin.pasien.store') }}">
            @csrf
            @if($errors->any())
            <div class="form-error" style="margin-bottom:16px"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <div class="form-group">
                <label class="form-label">Akun User <span style="color:#ef4444">*</span></label>
                <select name="user_id" class="form-input" required>
                    <option value="">— Pilih Akun User Pasien —</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ old('user_id')==$u->id?'selected':'' }}>{{ $u->nama }} ({{ $u->email }})</option>
                    @endforeach
                </select>
                <p style="font-size:11px;color:#94a3b8;margin-top:4px">Hanya user role "Pasien" yang belum punya profil.</p>
            </div>

            <p style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:16px 0 12px">Data Medis</p>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">NIK (16 digit) <span style="color:#ef4444">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik') }}" class="form-input" maxlength="16" required placeholder="16 digit NIK">
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span style="color:#ef4444">*</span></label>
                    <select name="jenis_kelamin" class="form-input" required>
                        <option value="">— Pilih —</option>
                        <option value="L" {{ old('jenis_kelamin')=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin')=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tempat Lahir <span style="color:#ef4444">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Lahir <span style="color:#ef4444">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Golongan Darah</label>
                    <select name="golongan_darah" class="form-input">
                        <option value="">— Pilih —</option>
                        @foreach(['A','B','AB','O'] as $gb)
                        <option value="{{ $gb }}" {{ old('golongan_darah')==$gb?'selected':'' }}>{{ $gb }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Agama</label>
                    <select name="agama" class="form-input">
                        <option value="">— Pilih —</option>
                        @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $ag)
                        <option value="{{ $ag }}" {{ old('agama')==$ag?'selected':'' }}>{{ $ag }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" name="pekerjaan" value="{{ old('pekerjaan') }}" class="form-input" placeholder="PNS, Wiraswasta, dll">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Lengkap <span style="color:#ef4444">*</span></label>
                <textarea name="alamat" rows="2" class="form-input" required>{{ old('alamat') }}</textarea>
            </div>

            <p style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;margin:16px 0 12px">Penjamin / Asuransi</p>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Penjamin</label>
                    <select name="penjamin_id" class="form-input">
                        <option value="">— Umum / Bayar Sendiri —</option>
                        @foreach($penjamins->groupBy('tipePenjamin.nama_tipe') as $tipe => $list)
                        <optgroup label="{{ $tipe }}">
                            @foreach($list as $p)
                            <option value="{{ $p->id }}" {{ old('penjamin_id')==$p->id?'selected':'' }}>{{ $p->nama_penjamin }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor Kartu Penjamin</label>
                    <input type="text" name="nomor_penjamin" value="{{ old('nomor_penjamin') }}" class="form-input" placeholder="No. BPJS / No. Asuransi">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Daftarkan Pasien</button>
                <a href="{{ route('admin.pasien') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
