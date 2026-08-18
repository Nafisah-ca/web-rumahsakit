@extends('layouts.app')
@section('content')

@php
    $penjamins = \App\Models\Penjamin::where('status','aktif')->with('tipePenjamin')->get();
    $activeTab = request('tab', 'profil');
@endphp

<div class="min-h-screen bg-gray-50 py-10 px-4">
    <div class="max-w-3xl mx-auto">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-green-600 to-green-800 rounded-2xl p-6 flex items-center gap-5 mb-6">
            <div class="flex-shrink-0">
                @if(Auth::user()->foto)
                    <img src="{{ Storage::url(Auth::user()->foto) }}"
                         alt="{{ Auth::user()->nama }}"
                         id="header-foto"
                         class="w-16 h-16 rounded-2xl object-cover border-2 border-white/40">
                @else
                    <div id="header-initial"
                         class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-white text-2xl font-black">
                        {{ strtoupper(substr(Auth::user()->nama ?? '?', 0, 1)) }}
                    </div>
                    <img id="header-foto" src="" alt=""
                         class="w-16 h-16 rounded-2xl object-cover border-2 border-white/40 hidden">
                @endif
            </div>
            <div>
                <p class="text-white font-extrabold text-lg">{{ Auth::user()->nama }}</p>
                <p class="text-green-200 text-sm">{{ Auth::user()->email }}</p>
                @if($pasien?->no_rekam_medis)
                <p class="text-green-100 text-xs font-semibold mt-1">No. RM: {{ $pasien->no_rekam_medis }}</p>
                @endif
            </div>
        </div>

        {{-- TAB NAVBAR --}}
        <div class="flex gap-2 mb-6 bg-white rounded-2xl border border-gray-100 shadow-sm p-1.5">
            <a href="{{ route('portal.profil') }}?tab=profil"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all
                      {{ $activeTab === 'profil' ? 'bg-green-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50' }}">
                <i class="fas fa-user text-xs"></i> Profil Saya
            </a>
            <a href="{{ route('portal.profil') }}?tab=riwayat"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all
                      {{ $activeTab === 'riwayat' ? 'bg-green-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50' }}">
                <i class="fas fa-calendar-check text-xs"></i> Riwayat Poliklinik
            </a>
            <a href="{{ route('portal.profil') }}?tab=penjamin"
               class="flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold transition-all
                      {{ $activeTab === 'penjamin' ? 'bg-green-600 text-white shadow' : 'text-gray-600 hover:bg-gray-50' }}">
                <i class="fas fa-shield-halved text-xs"></i> Penjamin & Asuransi
            </a>
        </div>

        {{-- Flash --}}
        @if(session('success'))
        <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-center gap-3 text-green-700 text-sm font-semibold">
            <i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-2xl text-red-700 text-sm">
            <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        {{-- ============================================================
             TAB: PROFIL
             ============================================================ --}}
        @if($activeTab === 'profil')
        <form method="POST" action="{{ route('portal.profil.update') }}" class="space-y-5" enctype="multipart/form-data" id="form-profil">
            @csrf @method('PUT')

            {{-- ===== EDIT PROFILE CARD (seperti referensi) ===== --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base mb-5">
                    <i class="fas fa-user-pen text-green-600"></i> Edit Profile
                </h3>

                {{-- Foto Profil — persis seperti referensi --}}
                <div class="flex items-center gap-5 p-4 bg-gray-50 rounded-2xl mb-2">
                    {{-- Preview foto --}}
                    <div class="flex-shrink-0" id="foto-preview-wrap">
                        @if(Auth::user()->foto)
                            <img id="foto-preview-img"
                                 src="{{ Storage::url(Auth::user()->foto) }}"
                                 alt="{{ Auth::user()->nama }}"
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                        @else
                            <div id="foto-preview-initial"
                                 class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center border-2 border-gray-200">
                                <span class="text-green-700 font-black text-2xl">
                                    {{ strtoupper(substr(Auth::user()->nama ?? '?', 0, 1)) }}
                                </span>
                            </div>
                            <img id="foto-preview-img" src="" alt=""
                                 class="w-20 h-20 rounded-full object-cover border-2 border-gray-200 hidden">
                        @endif
                    </div>

                    {{-- Teks + tombol --}}
                    <div>
                        <p class="text-sm font-bold text-gray-800 mb-1">Foto Profil</p>
                        <p class="text-xs text-gray-400 mb-3">JPG, PNG maksimal 2MB</p>
                        <label for="foto-input"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-600 cursor-pointer hover:border-green-500 hover:text-green-600 transition-all">
                            <i class="fas fa-upload text-xs"></i> Pilih Foto
                            <input type="file" name="foto" id="foto-input" accept="image/*" class="hidden">
                        </label>
                    </div>
                </div>
            </div>

            {{-- Data Pribadi --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base">
                    <i class="fas fa-user text-green-600"></i> Data Pribadi
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->nama) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                        <p class="text-xs text-gray-400 mt-1">Akan mengubah nama akun Anda.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">No. HP / WhatsApp</label>
                        <input type="text" name="telepon" value="{{ old('telepon', Auth::user()->no_hp) }}" maxlength="20"
                            placeholder="08xxxxxxxxxx"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">NIK (16 digit) <span class="text-red-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $pasien?->nik) }}" maxlength="16" required
                            placeholder="16 digit NIK KTP"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Pilih —</option>
                            <option value="L" {{ old('jenis_kelamin',$pasien?->jenis_kelamin)=='L'?'selected':'' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin',$pasien?->jenis_kelamin)=='P'?'selected':'' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pasien?->tempat_lahir) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien?->tanggal_lahir?->format('Y-m-d')) }}" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Golongan Darah</label>
                        <select name="golongan_darah" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Pilih —</option>
                            @foreach(['A','B','AB','O'] as $gb)
                            <option value="{{ $gb }}" {{ old('golongan_darah',$pasien?->golongan_darah)==$gb?'selected':'' }}>{{ $gb }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Agama</label>
                        <select name="agama" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Pilih —</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu','Lainnya'] as $ag)
                            <option value="{{ $ag }}" {{ old('agama',$pasien?->agama)==$ag?'selected':'' }}>{{ $ag }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $pasien?->pekerjaan) }}"
                            placeholder="Contoh: Pegawai Swasta"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none transition-all resize-none"
                        placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat', $pasien?->alamat) }}</textarea>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-extrabold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                <i class="fas fa-save"></i> Simpan Profil
            </button>
        </form>

        {{-- ============================================================
             TAB: RIWAYAT POLIKLINIK
             ============================================================ --}}
        @elseif($activeTab === 'riwayat')
        @php
            $bookings = \App\Models\JanjiTemu::with(['jadwalDokter.dokter.spesialisasi'])
                ->where('pasien_id', $pasien?->id ?? 0)
                ->orderByDesc('tanggal_booking')
                ->paginate(10);
        @endphp

        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500">Riwayat semua janji temu Anda</p>
            <a href="{{ route('portal.booking.create') }}"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all shadow-sm">
                <i class="fas fa-plus text-xs"></i> Buat Janji Baru
            </a>
        </div>

        @forelse($bookings as $b)
        @php
            $statusConf = [
                'pending'   => ['Menunggu',     'bg-amber-100 text-amber-700 border-amber-200'],
                'approved'  => ['Dikonfirmasi', 'bg-blue-100 text-blue-700 border-blue-200'],
                'completed' => ['Selesai',      'bg-green-100 text-green-700 border-green-200'],
                'cancelled' => ['Dibatalkan',   'bg-red-100 text-red-700 border-red-200'],
            ];
            [$statusLabel, $statusClass] = $statusConf[$b->status] ?? [$b->status, 'bg-slate-100 text-slate-600 border-slate-200'];
        @endphp
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-4 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                        <span class="font-mono text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-lg font-semibold">{{ $b->kode_booking }}</span>
                        <span class="badge border {{ $statusClass }} text-xs font-bold px-2.5 py-0.5 rounded-full">{{ $statusLabel }}</span>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">Dokter</p>
                            <p class="font-semibold text-gray-800">{{ $b->jadwalDokter?->dokter?->nama_dokter ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $b->jadwalDokter?->dokter?->spesialisasi?->nama_spesialis ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">Tanggal</p>
                            <p class="font-semibold text-gray-800">{{ $b->tanggal_booking?->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $b->jadwalDokter ? substr($b->jadwalDokter->jam_mulai,0,5).' WIB' : '' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">No. Antrian</p>
                            <p class="font-black text-green-700 text-xl">{{ $b->nomor_antrian ?? '-' }}</p>
                        </div>
                    </div>
                    @if($b->keluhan)
                    <div class="mt-3 p-2.5 bg-gray-50 rounded-xl">
                        <p class="text-xs text-gray-400 font-semibold">Keluhan:</p>
                        <p class="text-sm text-gray-700">{{ $b->keluhan }}</p>
                    </div>
                    @endif
                </div>
                @if(in_array($b->status, ['pending','approved']))
                <form method="POST" action="{{ route('portal.booking.cancel', $b) }}" onsubmit="return confirm('Batalkan janji temu ini?')">
                    @csrf
                    <button class="flex-shrink-0 text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 border border-red-200 px-3 py-2 rounded-xl transition-all">
                        <i class="fas fa-times mr-1"></i>Batalkan
                    </button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-14 text-center">
            <i class="fas fa-calendar-times text-4xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">Belum ada riwayat janji temu</p>
            <a href="{{ route('portal.booking.create') }}" class="inline-flex items-center gap-2 mt-4 bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-green-700 transition-all">
                <i class="fas fa-plus"></i> Buat Janji Temu
            </a>
        </div>
        @endforelse
        @if($bookings->hasPages())
        <div class="mt-4">{{ $bookings->appends(['tab' => 'riwayat'])->links() }}</div>
        @endif

        {{-- ============================================================
             TAB: PENJAMIN & ASURANSI
             ============================================================ --}}
        @elseif($activeTab === 'penjamin')
        <form method="POST" action="{{ route('portal.profil.update') }}" class="space-y-5">
            @csrf @method('PUT')

            {{-- Pass data profil wajib (hidden) agar validasi tidak gagal --}}
            <input type="hidden" name="nama_lengkap" value="{{ Auth::user()->nama }}">
            <input type="hidden" name="telepon" value="{{ Auth::user()->no_hp }}">
            <input type="hidden" name="nik" value="{{ $pasien?->nik ?? '0000000000000000' }}">
            <input type="hidden" name="jenis_kelamin" value="{{ $pasien?->jenis_kelamin ?? 'L' }}">
            <input type="hidden" name="tempat_lahir" value="{{ $pasien?->tempat_lahir ?? '-' }}">
            <input type="hidden" name="tanggal_lahir" value="{{ $pasien?->tanggal_lahir?->format('Y-m-d') ?? now()->subYears(20)->format('Y-m-d') }}">
            <input type="hidden" name="alamat" value="{{ $pasien?->alamat ?? '-' }}">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <h3 class="font-extrabold text-gray-900 flex items-center gap-2 text-base">
                    <i class="fas fa-shield-halved text-green-600"></i> Penjamin / Asuransi
                </h3>
                <p class="text-xs text-gray-500">
                    Isi jika Anda menggunakan BPJS Kesehatan atau asuransi. Data ini akan digunakan saat proses pembayaran.
                </p>

                @if($pasien?->penjamin)
                <div class="flex items-center gap-3 p-3 bg-green-50 border border-green-200 rounded-xl mb-2">
                    <i class="fas fa-circle-check text-green-600"></i>
                    <div>
                        <p class="text-xs font-bold text-green-800">Penjamin Aktif: {{ $pasien->penjamin->nama_penjamin }}</p>
                        @if($pasien->nomor_penjamin)
                        <p class="text-xs text-green-700">No. Kartu: {{ $pasien->nomor_penjamin }}</p>
                        @endif
                    </div>
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Penjamin</label>
                        <select name="penjamin_id" class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none bg-white">
                            <option value="">— Umum / Bayar Sendiri —</option>
                            @foreach($penjamins->groupBy('tipePenjamin.nama_tipe') as $tipe => $list)
                            <optgroup label="{{ $tipe }}">
                                @foreach($list as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('penjamin_id', $pasien?->penjamin_id) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_penjamin }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Pilih BPJS Kesehatan, Prudential, Allianz, dll.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nomor Kartu Penjamin</label>
                        <input type="text" name="nomor_penjamin"
                            value="{{ old('nomor_penjamin', $pasien?->nomor_penjamin) }}"
                            placeholder="Contoh: 0001234567890 (No. BPJS)"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm focus:border-green-500 outline-none transition-all">
                        <p class="text-xs text-gray-400 mt-1">Nomor kartu BPJS / nomor polis asuransi.</p>
                    </div>
                </div>
            </div>

            <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-2xl font-extrabold text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                <i class="fas fa-save"></i> Simpan Data Penjamin
            </button>
        </form>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview foto profil saat dipilih
document.getElementById('foto-input')?.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);

    // Update preview di card Edit Profile
    const previewImg  = document.getElementById('foto-preview-img');
    const previewInit = document.getElementById('foto-preview-initial');
    if (previewImg) {
        previewImg.src = url;
        previewImg.classList.remove('hidden');
    }
    if (previewInit) previewInit.classList.add('hidden');

    // Update juga foto di header atas
    const headerFoto  = document.getElementById('header-foto');
    const headerInit  = document.getElementById('header-initial');
    if (headerFoto) {
        headerFoto.src = url;
        headerFoto.classList.remove('hidden');
    }
    if (headerInit) headerInit.classList.add('hidden');
});
</script>
@endpush
