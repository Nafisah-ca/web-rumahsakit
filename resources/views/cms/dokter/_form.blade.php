<div class="form-row">
    <div class="form-group">
        <label class="form-label">Nama Dokter <span style="color:#ef4444">*</span></label>
        <input type="text" name="nama_dokter" value="{{ old('nama_dokter', $dokter?->nama_dokter) }}"
               class="form-input" required placeholder="dr. Nama Lengkap, Sp.X">
    </div>
    <div class="form-group">
        <label class="form-label">Spesialisasi <span style="color:#ef4444">*</span></label>
        <select name="spesialis_id" class="form-input" required>
            <option value="">-- Pilih Spesialisasi --</option>
            @foreach($spesialisasis as $sp)
            <option value="{{ $sp->id }}" {{ old('spesialis_id', $dokter?->spesialis_id) == $sp->id ? 'selected' : '' }}>
                {{ $sp->nama_spesialis }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $dokter?->email) }}"
               class="form-input" placeholder="dokter@email.com">
    </div>
    <div class="form-group">
        <label class="form-label">No. HP / WhatsApp</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $dokter?->no_hp) }}"
               class="form-input" placeholder="08xxxxxxxxxx">
    </div>
</div>

<div class="form-group">
    <label class="form-label">Pendidikan / Gelar</label>
    <input type="text" name="pendidikan" value="{{ old('pendidikan', $dokter?->pendidikan) }}"
           class="form-input" placeholder="Dokter Umum / Spesialis Anak">
</div>

<div class="form-group">
    <label class="form-label">Bio Singkat</label>
    <textarea name="bio" rows="3" class="form-input"
              placeholder="Deskripsi singkat tentang dokter...">{{ old('bio', $dokter?->bio) }}</textarea>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Foto Dokter</label>
        @if($dokter?->foto)
        <div style="margin-bottom:8px">
            <img src="{{ Storage::url($dokter->foto) }}"
                 style="height:80px;width:70px;border-radius:10px;object-fit:cover;object-position:top;border:1px solid #e2e8f0">
        </div>
        @endif
        <input type="file" name="foto" accept="image/*" class="form-input">
        <p class="form-hint">Format JPG/PNG. Maksimal 2MB. Gunakan foto portrait/wajah dokter.</p>
    </div>
    <div class="form-group">
        <label class="form-label">Status <span style="color:#ef4444">*</span></label>
        <select name="status" class="form-input" required>
            <option value="aktif"    {{ old('status', $dokter?->status ?? 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ old('status', $dokter?->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <p class="form-hint">Dokter nonaktif tidak muncul di live antrian dan halaman dokter.</p>
    </div>
</div>
