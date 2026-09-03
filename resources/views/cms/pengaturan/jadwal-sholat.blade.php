@extends('layouts.cms')
@php $pageTitle = 'Pengaturan Jadwal Sholat'; $breadcrumb = 'CMS / Pengaturan / Jadwal Sholat'; @endphp
@section('content')

@if(session('success'))
<div class="alert alert-success" style="margin:0 0 20px 0"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
@endif

@if($errors->any())
<div class="form-error" style="margin-bottom:16px">
    <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

{{-- Status Banner Realtime --}}
<div class="card" style="margin-bottom:20px;background:linear-gradient(135deg,#064e3b,#047857);color:#fff;border:none">
    <div style="padding:20px 24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px">
        <div style="display:flex;align-items:center;gap:16px">
            <div style="width:48px;height:48px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:22px">
                <i class="fas fa-kaaba"></i>
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:8px">
                    <h3 style="margin:0;font-size:18px;font-weight:800;color:#fff">Jadwal Sholat Hari Ini</h3>
                    @if($isApiUp)
                        <span class="badge" style="background:#10b981;color:#fff;font-size:10px;font-weight:700">
                            <i class="fas fa-circle" style="font-size:7px;margin-right:4px"></i> API Online
                        </span>
                    @else
                        <span class="badge" style="background:#f59e0b;color:#fff;font-size:10px;font-weight:700">
                            <i class="fas fa-exclamation-triangle" style="font-size:8px;margin-right:4px"></i> API Offline (Fallback Aktif)
                        </span>
                    @endif
                </div>
                <p style="margin:4px 0 0 0;font-size:12px;color:rgba(255,255,255,0.85)">
                    Lokasi: <strong>{{ $liveJadwal['lokasi'] }}</strong> • Tanggal: <strong>{{ $liveJadwal['tanggal_label'] }}</strong> • Sumber: <strong>{{ $liveJadwal['sumber_label'] }}</strong>
                </p>
            </div>
        </div>

        @if(!empty($liveJadwal['sholat_berikutnya']))
        <div style="background:rgba(255,255,255,0.12);padding:8px 16px;border-radius:10px;text-align:right">
            <span style="font-size:11px;text-transform:uppercase;letter-spacing:0.05em;opacity:0.85">Sholat Berikutnya</span>
            <div style="font-size:16px;font-weight:800;color:#fef08a">
                {{ $liveJadwal['sholat_berikutnya']['nama'] }} ({{ $liveJadwal['sholat_berikutnya']['jam'] }})
            </div>
            <span style="font-size:11px;opacity:0.85">{{ $liveJadwal['sholat_berikutnya']['countdown'] }}</span>
        </div>
        @endif
    </div>
</div>

<form method="POST" action="{{ route('cms.jadwal-sholat.update') }}" id="form-jadwal-sholat">
@csrf @method('PUT')

<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start">

    {{-- Kolom Kiri: Konfigurasi Mode & Lokasi & Manual --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- 1. Mode Sumber Data --}}
        <div class="card card-body">
            <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px">
                <i class="fas fa-sliders" style="color:#059669"></i> Mode Pengambilan Data
            </h4>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px">
                <label style="border:2px solid {{ ($config['mode'] ?? 'api') === 'api' ? '#059669' : '#e2e8f0' }};
                              background:{{ ($config['mode'] ?? 'api') === 'api' ? '#ecfdf5' : '#fff' }};
                              border-radius:12px;padding:14px;cursor:pointer;display:block;transition:all .15s"
                       id="label-mode-api">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                        <input type="radio" name="mode" value="api" {{ ($config['mode'] ?? 'api') === 'api' ? 'checked' : '' }} onchange="toggleMode('api')">
                        <strong style="font-size:13px;color:#065f46">Otomatis via API Publik</strong>
                    </div>
                    <p style="font-size:11px;color:#64748b;margin:0;line-height:1.4">
                        Mengambil jadwal sholat harian dari <strong>MyQuran API (Kemenag RI)</strong>. Jika API down/offline, sistem <strong>otomatis fallback</strong> menggunakan data manual di bawah.
                    </p>
                </label>

                <label style="border:2px solid {{ ($config['mode'] ?? '') === 'manual' ? '#059669' : '#e2e8f0' }};
                              background:{{ ($config['mode'] ?? '') === 'manual' ? '#ecfdf5' : '#fff' }};
                              border-radius:12px;padding:14px;cursor:pointer;display:block;transition:all .15s"
                       id="label-mode-manual">
                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                        <input type="radio" name="mode" value="manual" {{ ($config['mode'] ?? '') === 'manual' ? 'checked' : '' }} onchange="toggleMode('manual')">
                        <strong style="font-size:13px;color:#0f172a">Manual / Database Saja</strong>
                    </div>
                    <p style="font-size:11px;color:#64748b;margin:0;line-height:1.4">
                        Hanya menggunakan jadwal sholat yang diinputkan secara manual di database tanpa memanggil API publik.
                    </p>
                </label>
            </div>
        </div>

        {{-- 2. Lokasi / Kota API --}}
        <div class="card card-body" id="section-lokasi-api">
            <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:14px;display:flex;align-items:center;gap:8px">
                <i class="fas fa-location-dot" style="color:#059669"></i> Lokasi Kota / Kabupaten (Untuk API)
            </h4>

            <div class="form-group" style="position:relative">
                <label class="form-label">Cari Kota / Kabupaten <span style="color:#ef4444">*</span></label>
                <div style="display:flex;gap:8px">
                    <div style="position:relative;flex:1">
                        <input type="text" id="input-cari-kota" class="form-input" placeholder="Ketik nama kota, misal: Padang, Jakarta, Surabaya..." autocomplete="off">
                        <div id="kota-spinner" style="display:none;position:absolute;right:10px;top:10px;color:#94a3b8">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" onclick="doSearchKota()"><i class="fas fa-search"></i> Cari</button>
                </div>

                {{-- Hasil Dropdown Pencarian --}}
                <div id="kota-dropdown" style="display:none;position:absolute;left:0;right:0;top:100%;z-index:100;background:#fff;border:1px solid #cbd5e1;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.15);max-height:220px;overflow-y:auto;margin-top:4px"></div>
            </div>

            <div style="display:grid;grid-template-columns:140px 1fr;gap:12px;margin-top:12px">
                <div class="form-group">
                    <label class="form-label">ID Kota (API)</label>
                    <input type="text" name="kota_id" id="input-kota-id" value="{{ old('kota_id', $config['kota_id'] ?? '1301') }}" class="form-input" readonly style="background:#f8fafc;font-family:monospace;font-weight:700">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Kota Terpilih</label>
                    <input type="text" name="kota_nama" id="input-kota-nama" value="{{ old('kota_nama', $config['kota_nama'] ?? 'KOTA JAKARTA') }}" class="form-input" readonly style="background:#f8fafc;font-weight:700;color:#065f46">
                </div>
            </div>
            <p style="font-size:11px;color:#64748b;margin:4px 0 0 0">
                <i class="fas fa-info-circle text-blue-500"></i> Jadwal sholat harian akan ditarik otomatis dari server Kemenag RI berdasarkan kota ini.
            </p>
        </div>

        {{-- 3. Jam Sholat Manual (Fallback Database) --}}
        <div class="card card-body">
            <h4 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:6px;display:flex;align-items:center;gap:8px">
                <i class="fas fa-clock" style="color:#059669"></i> Jam Sholat Manual (Fallback Database)
            </h4>
            <p style="font-size:12px;color:#64748b;margin-bottom:16px">
                Waktu di bawah ini akan digunakan sebagai cadangan jika <strong>API offline / tidak terjangkau</strong>, atau jika mode Manual dipilih.
            </p>

            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px">
                <div class="form-group">
                    <label class="form-label">Imsak</label>
                    <input type="time" name="manual[imsak]" value="{{ old('manual.imsak', $config['manual']['imsak'] ?? '04:30') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Subuh <span style="color:#ef4444">*</span></label>
                    <input type="time" name="manual[subuh]" value="{{ old('manual.subuh', $config['manual']['subuh'] ?? '04:40') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Terbit</label>
                    <input type="time" name="manual[terbit]" value="{{ old('manual.terbit', $config['manual']['terbit'] ?? '05:52') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Dhuha</label>
                    <input type="time" name="manual[dhuha]" value="{{ old('manual.dhuha', $config['manual']['dhuha'] ?? '06:18') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Dzuhur <span style="color:#ef4444">*</span></label>
                    <input type="time" name="manual[dzuhur]" value="{{ old('manual.dzuhur', $config['manual']['dzuhur'] ?? '12:00') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Ashar <span style="color:#ef4444">*</span></label>
                    <input type="time" name="manual[ashar]" value="{{ old('manual.ashar', $config['manual']['ashar'] ?? '15:15') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Maghrib <span style="color:#ef4444">*</span></label>
                    <input type="time" name="manual[maghrib]" value="{{ old('manual.maghrib', $config['manual']['maghrib'] ?? '17:58') }}" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Isya <span style="color:#ef4444">*</span></label>
                    <input type="time" name="manual[isya]" value="{{ old('manual.isya', $config['manual']['isya'] ?? '19:08') }}" class="form-input" required>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn btn-primary" style="padding:12px 24px;font-size:14px">
                <i class="fas fa-save"></i> Simpan Pengaturan Jadwal Sholat
            </button>
        </div>

    </div>

    {{-- Kolom Kanan: Preview Jadwal Sholat & Info --}}
    <div style="display:flex;flex-direction:column;gap:16px">

        <div class="card card-body">
            <h4 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px;padding-bottom:10px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:6px">
                <i class="fas fa-mosque" style="color:#059669"></i> Preview Jadwal Aktif
            </h4>

            <div style="display:flex;flex-direction:column;gap:8px">
                @php
                    $prayerIcons = [
                        'imsak'   => 'fa-moon',
                        'subuh'   => 'fa-cloud-sun',
                        'terbit'  => 'fa-sun',
                        'dhuha'   => 'fa-sun',
                        'dzuhur'  => 'fa-sun',
                        'ashar'   => 'fa-cloud-sun',
                        'maghrib' => 'fa-moon',
                        'isya'    => 'fa-star-and-crescent',
                    ];
                @endphp

                @foreach($liveJadwal['times'] as $waktu => $jam)
                @php
                    $isNext = ($liveJadwal['sholat_berikutnya']['nama'] ?? '') === ucfirst($waktu);
                @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;border-radius:10px;
                            background:{{ $isNext ? '#ecfdf5' : '#f8fafc' }};
                            border:1px solid {{ $isNext ? '#a7f3d0' : '#f1f5f9' }}">
                    <div style="display:flex;align-items:center;gap:10px">
                        <span style="width:26px;height:26px;border-radius:6px;background:{{ $isNext ? '#059669' : '#e2e8f0' }};
                                     color:{{ $isNext ? '#fff' : '#64748b' }};display:flex;align-items:center;justify-content:center;font-size:11px">
                            <i class="fas {{ $prayerIcons[$waktu] ?? 'fa-clock' }}"></i>
                        </span>
                        <span style="font-weight:700;font-size:13px;color:{{ $isNext ? '#065f46' : '#334155' }};text-transform:capitalize">
                            {{ $waktu }}
                        </span>
                    </div>
                    <div style="display:flex;align-items:center;gap:6px">
                        <span style="font-weight:800;font-size:14px;color:{{ $isNext ? '#059669' : '#0f172a' }};font-family:monospace">
                            {{ $jam }} WIB
                        </span>
                        @if($isNext)
                        <span class="badge" style="background:#059669;color:#fff;font-size:9px;padding:2px 6px">Berikutnya</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Info Box --}}
        <div class="card card-body" style="background:#f0fdf4;border:1px solid #bbf7d0">
            <h5 style="font-size:12px;font-weight:700;color:#15803d;margin-bottom:8px">
                <i class="fas fa-shield-halved"></i> Cara Kerja Sistem
            </h5>
            <ul style="font-size:12px;color:#166534;margin:0;padding-left:16px;line-height:1.7">
                <li>Jadwal sholat diperbarui <strong>setiap hari otomatis</strong> dari API sesuai kalender.</li>
                <li>Dilengkapi <strong>Cache 6 jam</strong> agar performa website tetap instan.</li>
                <li>Jika API mengalami gangguan atau server offline, website akan <strong>otomatis memakai jam manual database</strong> tanpa henti.</li>
            </ul>
        </div>

    </div>

</div>

</form>

@endsection

@push('scripts')
<script>
function toggleMode(mode) {
    const lblApi    = document.getElementById('label-mode-api');
    const lblManual = document.getElementById('label-mode-manual');

    if (mode === 'api') {
        lblApi.style.borderColor = '#059669';
        lblApi.style.background  = '#ecfdf5';
        lblManual.style.borderColor = '#e2e8f0';
        lblManual.style.background  = '#fff';
    } else {
        lblManual.style.borderColor = '#059669';
        lblManual.style.background  = '#ecfdf5';
        lblApi.style.borderColor = '#e2e8f0';
        lblApi.style.background  = '#fff';
    }
}

const inputCari   = document.getElementById('input-cari-kota');
const spinner     = document.getElementById('kota-spinner');
const dropdown    = document.getElementById('kota-dropdown');
const inputKotaId = document.getElementById('input-kota-id');
const inputKotaNm = document.getElementById('input-kota-nama');
let debounceTimer;

function doSearchKota() {
    const q = inputCari.value.trim();
    if (q.length < 2) {
        dropdown.style.display = 'none';
        return;
    }

    spinner.style.display = 'block';

    fetch(`{{ route('cms.jadwal-sholat.cari-kota') }}?q=${encodeURIComponent(q)}`)
        .then(res => res.json())
        .then(data => {
            spinner.style.display = 'none';
            if (Array.isArray(data) && data.length > 0) {
                let html = '';
                data.forEach(item => {
                    html += `
                        <div style="padding:10px 14px;cursor:pointer;border-bottom:1px solid #f1f5f9;font-size:13px;display:flex;justify-content:space-between;align-items:center"
                             onmouseover="this.style.background='#f0fdf4'"
                             onmouseout="this.style.background='#fff'"
                             onclick="pilihKota('${item.id}', '${item.lokasi}')">
                            <span style="font-weight:600;color:#0f172a">${item.lokasi}</span>
                            <span style="font-size:11px;color:#94a3b8;font-family:monospace">ID: ${item.id}</span>
                        </div>
                    `;
                });
                dropdown.innerHTML = html;
                dropdown.style.display = 'block';
            } else {
                dropdown.innerHTML = '<div style="padding:12px;color:#94a3b8;font-size:12px;text-align:center">Kota tidak ditemukan</div>';
                dropdown.style.display = 'block';
            }
        })
        .catch(err => {
            spinner.style.display = 'none';
            dropdown.style.display = 'none';
        });
}

function pilihKota(id, nama) {
    inputKotaId.value = id;
    inputKotaNm.value = nama;
    inputCari.value   = nama;
    dropdown.style.display = 'none';
}

if (inputCari) {
    inputCari.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(doSearchKota, 350);
    });

    document.addEventListener('click', function (e) {
        if (!inputCari.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = 'none';
        }
    });
}
</script>
@endpush
