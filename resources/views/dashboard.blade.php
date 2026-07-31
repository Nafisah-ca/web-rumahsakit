@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $role = $roleView ?? $user->role;

    $dashboards = [
        'admin' => [
            'title' => 'Dashboard Administrator',
            'subtitle' => 'Pantau aktivitas rumah sakit, user, dan operasional harian.',
            'accent' => 'red',
            'stats' => [
                ['fa-users', 'Total User', '128', 'bg-red-50 text-red-700', 'border-red-200'],
                ['fa-user-md', 'Dokter Aktif', '42', 'bg-green-50 text-green-700', 'border-green-200'],
                ['fa-calendar-check', 'Janji Hari Ini', '86', 'bg-blue-50 text-blue-700', 'border-blue-200'],
                ['fa-stethoscope', 'Spesialisasi', '16', 'bg-purple-50 text-purple-700', 'border-purple-200'],
            ],
            'quickLinks' => [
                ['fa-users-cog', 'Kelola User', 'dashboard', 'red'],
                ['fa-user-shield', 'Role & Akses', 'dashboard', 'orange'],
                ['fa-chart-line', 'Laporan', 'dashboard', 'blue'],
                ['fa-cogs', 'Pengaturan', 'dashboard', 'gray'],
            ],
            'activityTitle' => 'Aktivitas Admin',
            'activities' => [
                'Review pendaftaran pasien baru',
                'Validasi jadwal dokter minggu ini',
                'Cek status dokter dan layanan aktif',
            ],
        ],
        'cms' => [
            'title' => 'Dashboard CMS',
            'subtitle' => 'Kelola artikel, promo, event, dan konten website.',
            'accent' => 'blue',
            'stats' => [
                ['fa-newspaper', 'Artikel', '24', 'bg-blue-50 text-blue-700', 'border-blue-200'],
                ['fa-tags', 'Promo Aktif', '6', 'bg-green-50 text-green-700', 'border-green-200'],
                ['fa-calendar-alt', 'Event', '9', 'bg-purple-50 text-purple-700', 'border-purple-200'],
                ['fa-eye', 'Publikasi', '31', 'bg-orange-50 text-orange-700', 'border-orange-200'],
            ],
            'quickLinks' => [
                ['fa-pen-nib', 'Tulis Artikel', 'artikel', 'blue'],
                ['fa-tags', 'Kelola Promo', 'promo', 'green'],
                ['fa-calendar-plus', 'Kelola Event', 'event', 'purple'],
                ['fa-image', 'Media', 'dashboard', 'orange'],
            ],
            'activityTitle' => 'Agenda Konten',
            'activities' => [
                'Update promo medical check-up',
                'Siapkan artikel edukasi kesehatan',
                'Publikasikan jadwal kegiatan terbaru',
            ],
        ],
        'user' => [
            'title' => 'Portal Pasien',
            'subtitle' => 'Akses riwayat kesehatan, jadwal, dan layanan pasien.',
            'accent' => 'green',
            'stats' => [
                ['fa-calendar-check', 'Janji Temu', '3', 'bg-green-50 text-green-700', 'border-green-200'],
                ['fa-file-medical', 'Rekam Medis', '12', 'bg-blue-50 text-blue-700', 'border-blue-200'],
                ['fa-pills', 'Resep Aktif', '1', 'bg-orange-50 text-orange-700', 'border-orange-200'],
                ['fa-receipt', 'Tagihan', '0', 'bg-purple-50 text-purple-700', 'border-purple-200'],
            ],
            'quickLinks' => [
                ['fa-user-md', 'Cari Dokter', 'dokter', 'green'],
                ['fa-file-alt', 'Hasil Lab', 'live.antrian', 'blue'],
                ['fa-map-marker-alt', 'Hubungi Kami', 'kontak', 'purple'],
                ['fa-headset', 'Bantuan', 'kontak', 'teal'],
            ],
            'activityTitle' => 'Jadwal Mendatang',
            'activities' => [
                'Poli Jantung - 15 Jul 2026, 09.00 WIB',
                'Poli Anak - 17 Jul 2026, 10.30 WIB',
                'Medical check-up - 19 Jul 2026, 08.00 WIB',
            ],
        ],
    ];

    $config = $dashboards[$role] ?? $dashboards['user'];
@endphp

<div class="min-h-screen bg-gray-50 py-10">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white border border-gray-200 text-xs font-bold text-gray-600 mb-3">
                    <i class="fas fa-shield-alt text-{{ $config['accent'] }}-600"></i>
                    {{ $user->role_label }}
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900">{{ $config['title'] }}</h1>
                <p class="text-gray-500 text-sm mt-1">{{ $config['subtitle'] }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 bg-red-50 text-red-600 border border-red-200 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-600 hover:text-white transition-all">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
            @foreach($config['stats'] as [$ico, $lbl, $val, $cls, $border])
            <div class="bg-white rounded-2xl p-5 border {{ $border }} shadow-sm">
                <div class="w-10 h-10 rounded-xl {{ $cls }} flex items-center justify-center mb-3">
                    <i class="fas {{ $ico }} text-sm"></i>
                </div>
                <div class="text-2xl font-black text-gray-900">{{ $val }}</div>
                <div class="text-gray-500 text-xs font-medium">{{ $lbl }}</div>
            </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="font-extrabold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-user-circle text-{{ $config['accent'] }}-600"></i> Profil Akun
                </h3>
                <div class="space-y-3">
                    @foreach([
                        ['fa-user', 'Nama', $user->name],
                        ['fa-envelope', 'Email', $user->email],
                        ['fa-id-card', 'Role', $user->role_label],
                        ['fa-calendar', 'Bergabung', $user->created_at->format('d M Y')],
                    ] as [$ico, $lbl, $val])
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50">
                        <div class="w-8 h-8 rounded-lg bg-{{ $config['accent'] }}-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $ico }} text-{{ $config['accent'] }}-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs font-semibold">{{ $lbl }}</p>
                            <p class="text-gray-800 text-sm font-semibold">{{ $val }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="font-extrabold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-list-check text-{{ $config['accent'] }}-600"></i> {{ $config['activityTitle'] }}
                </h3>
                <div class="space-y-3">
                    @foreach($config['activities'] as $activity)
                    <div class="p-3 rounded-xl border border-gray-100 bg-gray-50 flex items-start gap-3">
                        <span class="mt-1 w-2 h-2 rounded-full bg-{{ $config['accent'] }}-500 flex-shrink-0"></span>
                        <p class="text-gray-700 text-sm font-semibold">{{ $activity }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="font-extrabold text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fas fa-th text-{{ $config['accent'] }}-600"></i> Akses Cepat
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($config['quickLinks'] as [$ico, $lbl, $rt, $color])
                    <a href="{{ route($rt) }}"
                       class="flex flex-col items-center gap-2 p-4 rounded-xl bg-{{ $color }}-50 border border-{{ $color }}-100 hover:bg-{{ $color }}-100 transition-all group text-center">
                        <div class="w-9 h-9 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="fas {{ $ico }} text-{{ $color }}-600 text-sm"></i>
                        </div>
                        <span class="text-{{ $color }}-800 text-xs font-bold">{{ $lbl }}</span>
                    </a>
                    @endforeach
                </div>

                <div class="mt-5 p-3 bg-{{ $config['accent'] }}-50 rounded-xl border border-{{ $config['accent'] }}-200 text-center">
                    <i class="fas fa-check-circle text-{{ $config['accent'] }}-600 mb-1 block"></i>
                    <p class="text-{{ $config['accent'] }}-800 text-xs font-bold">Akun Aktif</p>
                    <p class="text-{{ $config['accent'] }}-600 text-xs">RS Sari Sehat Group</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
