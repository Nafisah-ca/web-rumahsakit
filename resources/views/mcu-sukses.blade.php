@extends('layouts.app')
@php $title = 'Pendaftaran MCU Berhasil'; @endphp
@section('content')

<section class="py-20 bg-gray-50 min-h-screen">
    <div class="max-w-lg mx-auto px-4 text-center">

        {{-- Ikon Sukses --}}
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-circle-check text-green-600 text-5xl"></i>
        </div>

        <h1 class="font-extrabold text-gray-900 text-3xl mb-2">Pendaftaran Berhasil!</h1>
        <p class="text-gray-500 text-sm mb-8">Tim kami akan menghubungi Anda dalam 1×24 jam untuk konfirmasi jadwal pemeriksaan.</p>

        {{-- Kartu Info --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8 text-left">
            <div class="bg-green-600 px-6 py-4">
                <p class="text-green-100 text-xs font-bold uppercase tracking-wider">Kode Pendaftaran</p>
                <p class="text-white font-extrabold text-2xl tracking-wider">{{ $pendaftaran->kode_pendaftaran }}</p>
                <p class="text-green-100 text-xs mt-1">Simpan kode ini sebagai bukti pendaftaran Anda.</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach([
                    ['label' => 'Paket MCU',       'value' => 'Paket ' . $pendaftaran->paket_label],
                    ['label' => 'Nama',             'value' => $pendaftaran->nama_lengkap],
                    ['label' => 'No. HP',           'value' => $pendaftaran->no_hp],
                    ['label' => 'Tanggal Pilihan',  'value' => \Carbon\Carbon::parse($pendaftaran->tanggal_pilihan)->translatedFormat('d F Y')],
                    ['label' => 'Sesi',             'value' => $pendaftaran->sesi === 'pagi' ? 'Pagi (07.00–10.00)' : 'Siang (10.00–14.00)'],
                    ['label' => 'Status',           'value' => 'Menunggu Konfirmasi'],
                ] as $row)
                <div class="px-6 py-3 flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-semibold">{{ $row['label'] }}</span>
                    <span class="text-sm text-gray-900 font-bold">{{ $row['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('mcu') }}" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-all flex items-center justify-center gap-2">
                <i class="fas fa-clipboard-list"></i> Lihat Paket Lain
            </a>
        </div>

        {{-- Info tambahan --}}
        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-xl text-left">
            <p class="text-blue-800 font-bold text-sm mb-1"><i class="fas fa-circle-info mr-1"></i> Informasi Penting</p>
            <ul class="text-blue-700 text-xs space-y-1 list-disc list-inside">
                <li>Harap puasa 8–10 jam sebelum pemeriksaan (air putih boleh).</li>
                <li>Bawa KTP/identitas saat datang.</li>
                <li>Datang 15 menit sebelum sesi dimulai.</li>
                <li>Hubungi kami jika perlu mengubah jadwal.</li>
            </ul>
        </div>

        <div class="mt-6">
            @php
                $waRs = preg_replace('/[^0-9]/', '', $setting->telepon ?? '6289501895170');
                if (str_starts_with($waRs, '0')) $waRs = '62' . substr($waRs, 1);
                $waMsg = urlencode('Halo, saya sudah mendaftar MCU dengan kode ' . $pendaftaran->kode_pendaftaran . '. Mohon konfirmasinya. Terima kasih.');
            @endphp
            <a href="https://wa.me/{{ $waRs }}?text={{ $waMsg }}" target="_blank" rel="noopener"
               class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-semibold text-sm transition-all shadow-md">
                <i class="fab fa-whatsapp text-lg"></i> Konfirmasi via WhatsApp
            </a>
        </div>
    </div>
</section>

@endsection
