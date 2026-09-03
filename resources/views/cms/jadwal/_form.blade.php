<div class="form-row">
    <div class="form-group">
        <label class="form-label">Dokter <span style="color:#ef4444">*</span></label>
        <select name="dokter_id" class="form-input" required id="sel-dokter" onchange="autoPoli(this)">
            <option value="">-- Pilih Dokter --</option>
            @foreach($dokterList as $d)
            <option value="{{ $d->id }}"
                    data-spesialis="{{ $d->spesialis_id }}"
                    {{ old('dokter_id', $jadwalDokter?->dokter_id) == $d->id ? 'selected' : '' }}>
                {{ $d->nama_dokter }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Poli / Spesialisasi <span style="color:#ef4444">*</span></label>
        <select name="spesialis_id" class="form-input" required id="sel-spesialis">
            <option value="">-- Pilih Poli --</option>
            @foreach($spesialisasis as $sp)
            <option value="{{ $sp->id }}"
                    {{ old('spesialis_id', $jadwalDokter?->spesialis_id) == $sp->id ? 'selected' : '' }}>
                {{ $sp->nama_spesialis }}
            </option>
            @endforeach
        </select>
        <p class="form-hint">Otomatis terisi saat pilih dokter, bisa diubah manual.</p>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Hari <span style="color:#ef4444">*</span></label>
        <select name="hari" class="form-input" required>
            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h)
            <option value="{{ $h }}" {{ old('hari', $jadwalDokter?->hari) === $h ? 'selected' : '' }}>{{ $h }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Kuota Pasien <span style="color:#ef4444">*</span></label>
        <input type="number" name="kuota" value="{{ old('kuota', $jadwalDokter?->kuota ?? 20) }}"
               class="form-input" required min="1" max="200">
        <p class="form-hint">Maksimal pasien yang bisa booking per sesi.</p>
    </div>
</div>

<div class="form-row">
    <div class="form-group">
        <label class="form-label">Jam Mulai <span style="color:#ef4444">*</span></label>
        <input type="time" name="jam_mulai" value="{{ old('jam_mulai', $jadwalDokter?->jam_mulai ? substr($jadwalDokter->jam_mulai,0,5) : '') }}"
               class="form-input" required>
    </div>
    <div class="form-group">
        <label class="form-label">Jam Selesai <span style="color:#ef4444">*</span></label>
        <input type="time" name="jam_selesai" value="{{ old('jam_selesai', $jadwalDokter?->jam_selesai ? substr($jadwalDokter->jam_selesai,0,5) : '') }}"
               class="form-input" required>
    </div>
</div>

<div class="form-group">
    <label class="form-label">Status <span style="color:#ef4444">*</span></label>
    <select name="status" class="form-input" required>
        <option value="aktif"    {{ old('status', $jadwalDokter?->status ?? 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ old('status', $jadwalDokter?->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    <p class="form-hint">Jadwal nonaktif tidak muncul di Live Antrian.</p>
</div>

@push('scripts')
<script>
// Auto-isi poli berdasarkan dokter yang dipilih
const dokterSel    = document.getElementById('sel-dokter');
const spesialisSel = document.getElementById('sel-spesialis');

function autoPoli(sel) {
    const spId = sel.options[sel.selectedIndex]?.dataset?.spesialis;
    if (!spId) return;
    // Cari option di sel-spesialis yang value-nya sama
    for (let opt of spesialisSel.options) {
        if (opt.value === spId) { opt.selected = true; break; }
    }
}
</script>
@endpush
