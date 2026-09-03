@extends('layouts.app')
@section('content')

<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-extrabold text-gray-900">Buat Janji Temu</h1>
            <p class="text-gray-500 text-sm mt-1">Isi form berikut untuk mendaftarkan jadwal kunjungan Anda</p>
        </div>

        @if(!$pasien)
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-amber-500 mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-bold text-amber-800">Profil pasien belum lengkap</p>
                <p class="text-amber-700 text-sm mt-1">Lengkapi profil Anda terlebih dahulu sebelum membuat janji temu.</p>
                <a href="{{ route('portal.profil') }}" class="inline-flex items-center gap-1 mt-2 text-sm font-bold text-amber-700 underline">
                    Lengkapi Profil →
                </a>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3 text-red-700 text-sm">
            <i class="fas fa-circle-exclamation mt-0.5 flex-shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <form method="POST" action="{{ route('portal.booking.store') }}" class="space-y-6">
            @csrf

            {{-- Pilih Dokter --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-black">1</span>
                    Pilih Dokter
                </h3>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Dokter <span class="text-red-500">*</span></label>
                    <select name="dokter_id" id="dokter_id"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all" required>

                        <option value="">— Pilih Dokter —</option>
                        @foreach($dokters as $d)
                        <option value="{{ $d->id }}" {{ (old('dokter_id', $dokter?->id) == $d->id) ? 'selected' : '' }}>
                            {{ $d->nama_dokter }} — {{ $d->spesialisasi?->nama_spesialis }}
                        </option>
                        @endforeach

                    </select>
                    {{-- Hidden input sebagai fallback saat dokter sudah dipilih dari halaman dokter --}}
                    @if($dokter)
                    <input type="hidden" name="dokter_id" value="{{ $dokter->id }}">
                    @endif
                </div>
            </div>

            {{-- Pilih Jadwal --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-black">2</span>
                    Pilih Jadwal
                </h3>

                <div id="jadwal-container">
                    <p class="text-gray-400 text-sm italic">Pilih dokter terlebih dahulu untuk melihat jadwal tersedia.</p>
                </div>

                {{-- Hidden fields yang dikirim ke server --}}
                <input type="hidden" name="jadwal_dokter_id" id="jadwal_dokter_id">
                <input type="hidden" name="tanggal_kunjungan" id="tanggal_kunjungan">
            </div>

            {{-- Detail Kunjungan --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-extrabold text-gray-900 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-black">3</span>
                    Detail Kunjungan
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Keluhan Utama <span class="text-red-500">*</span></label>
                        <textarea name="keluhan" rows="3" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all"
                            placeholder="Deskripsikan keluhan Anda secara singkat...">{{ old('keluhan') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Wajib diisi minimal 3 karakter.</p>
                    </div>
                </div>
            </div>

            {{-- Info Pasien --}}
            @if($pasien)
            <div class="bg-green-50 border border-green-200 rounded-2xl p-4 flex items-center gap-4">
                {{-- Avatar: foto jika ada, icon jika belum --}}
                @if(Auth::user()->foto)
                    <img src="{{ Storage::url(Auth::user()->foto) }}"
                         alt="{{ Auth::user()->nama }}"
                         class="w-10 h-10 rounded-full object-cover flex-shrink-0 border-2 border-green-300">
                @else
                    <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-green-900">{{ $pasien->nama_lengkap }}</p>
                    <p class="text-green-700 text-xs">No. RM: {{ $pasien->no_rm ?? '-' }} &nbsp;|&nbsp; {{ $pasien->jenis_kelamin_label }} &nbsp;|&nbsp; {{ $pasien->user?->no_hp ?? '-' }}</p>
                </div>
                <a href="{{ route('portal.profil') }}" class="text-green-600 hover:text-green-800 text-xs font-bold">Edit Profil</a>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <button type="submit" id="btn-submit" {{ !$pasien ? 'disabled' : '' }}
                    class="flex-1 bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white py-4 rounded-2xl font-extrabold text-sm transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-calendar-check"></i> Buat Janji Temu
                </button>
                <a href="{{ route('portal.booking.riwayat') }}" class="px-6 py-4 rounded-2xl border border-gray-200 text-gray-600 font-bold text-sm hover:bg-gray-50 transition-all">
                    Riwayat
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dokterSel       = document.getElementById('dokter_id');
const dokterNilaiAwal = dokterSel?.value;
const jadwalCont      = document.getElementById('jadwal-container');
const jadwalHid       = document.getElementById('jadwal_dokter_id');
const tanggalHid      = document.getElementById('tanggal_kunjungan');

function loadJadwal() {
    const dokterId  = dokterSel?.value;
    const btnSubmit = document.getElementById('btn-submit');

    if (!dokterId) {
        jadwalCont.innerHTML = '<p class="text-gray-400 text-sm italic">Pilih dokter terlebih dahulu untuk melihat jadwal tersedia.</p>';
        jadwalHid.value  = '';
        tanggalHid.value = '';
        if (btnSubmit) btnSubmit.disabled = true;
        return;
    }

    jadwalCont.innerHTML = '<p class="text-gray-400 text-sm"><i class="fas fa-spinner fa-spin mr-1"></i>Memuat jadwal...</p>';
    if (btnSubmit) btnSubmit.disabled = true;
    jadwalHid.value  = '';
    tanggalHid.value = '';

    fetch(`{{ route('portal.booking.jadwal') }}?dokter_id=${dokterId}`)
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                jadwalCont.innerHTML = `<div class="p-3 bg-blue-50 border border-blue-200 rounded-xl text-blue-700 text-sm">
                    <i class="fas fa-calendar-xmark mr-1"></i>
                    Dokter ini belum memiliki jadwal aktif.
                </div>`;
                return;
            }

            /* ── Header tabel ── */
            let html = `
            <div style="overflow:hidden;border-radius:14px;border:1px solid #e5e7eb">
                <table style="width:100%;border-collapse:collapse">
                    <thead>
                        <tr style="background:#00521f">
                            <th style="padding:11px 16px;text-align:left;color:#fff;font-size:12px;font-weight:700;letter-spacing:.05em">Hari</th>
                            <th style="padding:11px 16px;text-align:left;color:#fff;font-size:12px;font-weight:700;letter-spacing:.05em">Jam Praktek</th>
                            <th style="padding:11px 16px;text-align:left;color:#fff;font-size:12px;font-weight:700;letter-spacing:.05em">Tanggal Terdekat</th>
                            <th style="padding:11px 16px;text-align:left;color:#fff;font-size:12px;font-weight:700;letter-spacing:.05em">Kuota</th>
                            <th style="padding:11px 16px;text-align:center;color:#fff;font-size:12px;font-weight:700;letter-spacing:.05em">Pilih</th>
                        </tr>
                    </thead>
                    <tbody id="jadwal-tbody">`;

            data.forEach((j, idx) => {
                const disabled  = j.sudah_selesai || j.sisa_kuota <= 0;
                const rowBg     = idx % 2 === 0 ? '#fff' : '#f9fafb';

                let kuotaHtml;
                if (j.sudah_selesai) {
                    kuotaHtml = `<span style="font-size:11px;color:#9ca3af;font-weight:600"><i class="fas fa-clock mr-1"></i>Jam selesai</span>`;
                } else if (j.sisa_kuota <= 0) {
                    kuotaHtml = `<span style="font-size:11px;color:#ef4444;font-weight:700"><i class="fas fa-users-slash mr-1"></i>Penuh</span>`;
                } else {
                    kuotaHtml = `<span style="font-size:11px;color:#16a34a;font-weight:700"><i class="fas fa-circle-check mr-1"></i>Sisa ${j.sisa_kuota}</span>`;
                }

                let btnHtml;
                if (disabled) {
                    btnHtml = `<button type="button" disabled
                        style="padding:7px 16px;border-radius:8px;font-size:12px;font-weight:700;background:#f1f5f9;color:#9ca3af;border:none;cursor:not-allowed">
                        Tidak Tersedia
                    </button>`;
                } else {
                    btnHtml = `<button type="button"
                        onclick="pilihJadwal(${j.id},'${j.tanggal}','${j.hari}','${j.jam_mulai}','${j.jam_selesai}',this)"
                        class="jadwal-pick-btn"
                        data-id="${j.id}"
                        style="padding:7px 16px;border-radius:8px;font-size:12px;font-weight:700;background:#00521f;color:#fff;border:none;cursor:pointer;transition:all .15s;display:inline-flex;align-items:center;gap:6px">
                        <i class="fas fa-calendar-check" style="font-size:11px"></i> Daftar Poliklinik
                    </button>`;
                }

                html += `
                <tr style="background:${rowBg};border-top:1px solid #f3f4f6;transition:background .1s"
                    onmouseover="if(!this.classList.contains('selected'))this.style.background='#f0fdf4'"
                    onmouseout="if(!this.classList.contains('selected'))this.style.background='${rowBg}'"
                    id="row-${j.id}">
                    <td style="padding:12px 16px;font-size:13px;font-weight:700;color:#111">${j.hari}</td>
                    <td style="padding:12px 16px;font-size:13px;color:#374151">${j.jam_mulai} – ${j.jam_selesai}</td>
                    <td style="padding:12px 16px">
                        <span style="font-size:12px;font-weight:600;color:#374151">${j.tanggal_label}</span>
                    </td>
                    <td style="padding:12px 16px">${kuotaHtml}</td>
                    <td style="padding:12px 16px;text-align:center">${btnHtml}</td>
                </tr>`;
            });

            html += `</tbody></table></div>`;

            /* Info jadwal terpilih */
            html += `<div id="jadwal-selected-info" style="display:none;margin-top:12px;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;font-size:13px;font-weight:600;color:#166534">
                <i class="fas fa-circle-check mr-2"></i>
                <span id="jadwal-selected-text"></span>
            </div>`;

            jadwalCont.innerHTML = html;
        })
        .catch(() => {
            jadwalCont.innerHTML = '<p class="text-red-500 text-sm">Gagal memuat jadwal. Coba lagi.</p>';
        });
}

/* Saat pasien klik "Daftar Poliklinik" di salah satu baris */
function pilihJadwal(id, tanggal, hari, jamMulai, jamSelesai, btnEl) {
    /* Reset semua baris */
    document.querySelectorAll('.jadwal-pick-btn').forEach(function(b) {
        b.style.background = '#00521f';
        b.innerHTML = '<i class="fas fa-calendar-check" style="font-size:11px"></i> Daftar Poliklinik';
        document.getElementById('row-' + b.dataset.id)?.classList.remove('selected');
        document.getElementById('row-' + b.dataset.id).style.background = '';
    });

    /* Tandai baris terpilih */
    btnEl.style.background = '#166534';
    btnEl.innerHTML = '<i class="fas fa-check-circle" style="font-size:11px"></i> Dipilih';
    const row = document.getElementById('row-' + id);
    if (row) { row.classList.add('selected'); row.style.background = '#dcfce7'; }

    /* Isi hidden inputs */
    jadwalHid.value  = id;
    tanggalHid.value = tanggal;

    /* Tampilkan info terpilih */
    const info = document.getElementById('jadwal-selected-info');
    const text = document.getElementById('jadwal-selected-text');
    if (info && text) {
        text.textContent = `Jadwal dipilih: ${hari}, ${tanggal} pukul ${jamMulai}–${jamSelesai}`;
        info.style.display = 'block';
    }

    /* Aktifkan tombol submit */
    const btn = document.getElementById('btn-submit');
    if (btn) btn.disabled = false;
}

/* Init */
document.addEventListener('DOMContentLoaded', function () {
    if (dokterSel) dokterSel.addEventListener('change', loadJadwal);
    if (dokterNilaiAwal) loadJadwal();
});
</script>
@endpush
