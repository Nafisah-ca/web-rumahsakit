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
                    Pilih Tanggal & Jadwal
                </h3>
                {{-- Tanggal selalu tampil --}}
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_kunjungan" id="tanggal_kunjungan"
                        min="{{ date('Y-m-d') }}" value="{{ old('tanggal_kunjungan', date('Y-m-d')) }}"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none" required>
                    <p class="text-xs text-gray-400 mt-1">
                        Pilih dokter terlebih dahulu, lalu pilih tanggal untuk melihat jadwal yang tersedia.
                    </p>
                    <p class="text-xs font-semibold text-green-700 mt-1" id="hari-label"></p>
                </div>
                {{-- Jadwal muncul setelah dokter dipilih --}}
                <div id="jadwal-container">
                    <p class="text-gray-400 text-sm italic">Pilih dokter terlebih dahulu untuk melihat jadwal.</p>
                </div>
                <input type="hidden" name="jadwal_dokter_id" id="jadwal_dokter_id">
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
                <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-green-900">{{ $pasien->nama_lengkap }}</p>
                <p class="text-green-700 text-xs">No. RM: {{ $pasien->no_rm ?? '-' }} &nbsp;|&nbsp; {{ $pasien->jenis_kelamin_label }} &nbsp;|&nbsp; {{ $pasien->user?->no_hp ?? '-' }}</p>
                </div>
                <a href="{{ route('portal.profil') }}" class="text-green-600 hover:text-green-800 text-xs font-bold">Edit Profil</a>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <button type="submit" {{ !$pasien ? 'disabled' : '' }}
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
const tglCont         = document.getElementById('tanggal-container');
const jadwalHid       = document.getElementById('jadwal_dokter_id');

function loadJadwal() {
    const dokterId = dokterSel.value;
    if (!dokterId) {
        jadwalCont.innerHTML = '<p class="text-gray-400 text-sm italic">Pilih dokter terlebih dahulu.</p>';
        tglCont.classList.add('hidden');
        jadwalHid.value = '';
        return;
    }

    jadwalCont.innerHTML = '<p class="text-gray-400 text-sm"><i class="fas fa-spinner fa-spin mr-1"></i>Memuat jadwal...</p>';

    const tglInput = document.getElementById('tanggal_kunjungan');
    const tanggal  = tglInput ? tglInput.value : '{{ date("Y-m-d") }}';

    // Nama hari Indonesia dari tanggal
    const hariId = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const tglDate  = new Date(tanggal);
    const dayName  = hariId[tglDate.getDay()];
    const tglDisplay = tglDate.toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'});

    fetch(`{{ route('portal.booking.jadwal') }}?dokter_id=${dokterId}&tanggal_kunjungan=${tanggal}`)
        .then(r => r.json())
        .then(data => {
            if (!data.length) {
                jadwalCont.innerHTML = '<p class="text-amber-600 text-sm font-semibold"><i class="fas fa-info-circle mr-1"></i>Tidak ada jadwal tersedia untuk hari <strong>' + dayName + '</strong> (' + tglDisplay + '). Coba pilih tanggal lain.</p>';
                return;
            }

            let html = '<div class="grid grid-cols-2 sm:grid-cols-3 gap-3">';
            data.forEach(j => {
                // DB baru: j.hari sudah string langsung ("Senin", "Selasa", dll)
                const hariLabel = j.hari;
                const isFullStr = j.sisa_kuota <= 0
                    ? '<p class="text-xs text-red-500 font-semibold mt-1">Penuh</p>'
                    : `<p class="text-xs text-green-600 font-semibold mt-1">Sisa: ${j.sisa_kuota} kuota</p>`;

                html += `
                <label class="cursor-pointer ${j.sisa_kuota <= 0 ? 'opacity-50 pointer-events-none' : ''}">
                    <input type="radio" name="_jadwal_pick" value="${j.id}" class="sr-only peer"
                        onchange="pickJadwal(${j.id})" ${j.sisa_kuota <= 0 ? 'disabled' : ''}>
                    <div class="p-3 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300 transition-all text-center">
                        <p class="font-bold text-sm text-gray-800">${hariLabel}</p>
                        <p class="text-xs text-gray-500">${j.jam_mulai} – ${j.jam_selesai}</p>
                        ${isFullStr}
                    </div>
                </label>`;
            });
            html += '</div>';
            jadwalCont.innerHTML = html;
        })
        .catch(() => {
            jadwalCont.innerHTML = '<p class="text-red-500 text-sm">Gagal memuat jadwal. Coba lagi.</p>';
        });
}

function pickJadwal(id) {
    jadwalHid.value = id;
}

// Saat tanggal berubah, reload jadwal
document.addEventListener('DOMContentLoaded', function () {
    const tglInput = document.getElementById('tanggal_kunjungan');

    function updateHariLabel() {
        if (!tglInput || !tglInput.value) return;
        const hariId = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        const d = new Date(tglInput.value);
        const hari = hariId[d.getDay()];
        const label = document.getElementById('hari-label');
        if (label) label.textContent = '📅 ' + hari + ', ' + d.toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'});
    }

    if (tglInput) {
        tglInput.addEventListener('change', function() { updateHariLabel(); loadJadwal(); });
        updateHariLabel();
    }
    if (dokterSel && dokterNilaiAwal) loadJadwal();
    if (dokterSel) dokterSel.addEventListener('change', loadJadwal);
});
</script>
@endpush
