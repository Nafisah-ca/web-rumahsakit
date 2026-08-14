@extends('layouts.app')
@php
    $title      = 'Daftar MCU — ' . ($pakets[$paket]['label'] ?? 'Medical Check-Up');
    $paketInfo  = $pakets[$paket];
    $colorMap   = [
        'green'  => ['bg' => 'bg-green-600',  'light' => 'bg-green-50',  'text' => 'text-green-600',  'border' => 'border-green-200',  'ring' => 'ring-green-500'],
        'blue'   => ['bg' => 'bg-blue-600',   'light' => 'bg-blue-50',   'text' => 'text-blue-600',   'border' => 'border-blue-200',   'ring' => 'ring-blue-500'],
        'purple' => ['bg' => 'bg-purple-600', 'light' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-200', 'ring' => 'ring-purple-500'],
        'orange' => ['bg' => 'bg-orange-600', 'light' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-200', 'ring' => 'ring-orange-500'],
    ];
    $c = $colorMap[$paketInfo['color']] ?? $colorMap['green'];
@endphp
@section('content')

{{-- Hero --}}
<div class="py-14" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Medical Check-Up</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Formulir Pendaftaran MCU</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Lengkapi data di bawah untuk mendaftar paket Medical Check-Up pilihan Anda.</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="{{ route('mcu') }}" class="hover:text-white">Medical Check-Up</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Daftar</span>
        </nav>
    </div>
</div>

<section class="py-14 bg-gray-50">
    <div class="max-w-3xl mx-auto px-4">

        {{-- Info Paket Terpilih --}}
        <div class="{{ $c['light'] }} {{ $c['border'] }} border rounded-2xl p-5 mb-8 flex items-center gap-4">
            <div class="{{ $c['bg'] }} w-14 h-14 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                <i class="fas {{ $paketInfo['icon'] }} text-white text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">Paket Terpilih</p>
                <h2 class="{{ $c['text'] }} font-extrabold text-xl">Paket {{ $paketInfo['label'] }}</h2>
                <p class="text-gray-600 text-sm font-semibold">{{ $paketInfo['harga'] }}</p>
            </div>
            <a href="{{ route('mcu') }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1 flex-shrink-0">
                <i class="fas fa-exchange-alt text-xs"></i> Ganti Paket
            </a>
        </div>

        {{-- Error Validation --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6">
            <p class="font-bold text-red-700 text-sm mb-2"><i class="fas fa-circle-exclamation mr-2"></i>Mohon periksa kembali:</p>
            <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('mcu.store') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            <input type="hidden" name="paket" value="{{ $paket }}">

            {{-- Data Diri --}}
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h3 class="font-extrabold text-gray-900 text-base mb-5 flex items-center gap-2">
                    <span class="{{ $c['bg'] }} w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-white text-xs"></i>
                    </span>
                    Data Diri
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent"
                               placeholder="Sesuai KTP" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">NIK <span class="text-gray-400">(opsional)</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent"
                               placeholder="16 digit NIK">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                               max="{{ now()->subDay()->format('Y-m-d') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Nomor HP / WhatsApp <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" maxlength="20"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent"
                               placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Email <span class="text-gray-400">(opsional)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent"
                               placeholder="email@contoh.com">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Alamat Lengkap <span class="text-gray-400">(opsional)</span></label>
                        <textarea name="alamat" rows="2"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent resize-none"
                                  placeholder="Jl. ...">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Jadwal Pemeriksaan --}}
            <div class="p-6 md:p-8 border-b border-gray-100">
                <h3 class="font-extrabold text-gray-900 text-base mb-5 flex items-center gap-2">
                    <span class="{{ $c['bg'] }} w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-white text-xs"></i>
                    </span>
                    Jadwal Pemeriksaan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Tanggal yang Diinginkan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pilihan" value="{{ old('tanggal_pilihan') }}"
                               min="{{ now()->format('Y-m-d') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent" required>
                        <p class="text-xs text-gray-400 mt-1">Tersedia Senin–Sabtu, 07.00–14.00</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Sesi <span class="text-red-500">*</span></label>
                        <select name="sesi" required
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent bg-white">
                            <option value="pagi"  {{ old('sesi','pagi') == 'pagi'  ? 'selected' : '' }}>Pagi (07.00 – 10.00)</option>
                            <option value="siang" {{ old('sesi') == 'siang' ? 'selected' : '' }}>Siang (10.00 – 14.00)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 mb-1.5">Catatan Tambahan <span class="text-gray-400">(opsional)</span></label>
                        <textarea name="catatan" rows="2" maxlength="500"
                                  class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 {{ $c['ring'] }} focus:border-transparent resize-none"
                                  placeholder="Riwayat penyakit, alergi, atau informasi lain yang perlu diketahui tim kami...">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="p-6 md:p-8 bg-gray-50 flex flex-col sm:flex-row gap-3 items-center justify-between">
                <p class="text-xs text-gray-400 flex items-center gap-1">
                    <i class="fas fa-shield-halved text-green-500"></i>
                    Data Anda aman dan hanya digunakan untuk keperluan pemeriksaan.
                </p>
                <div class="flex gap-3 w-full sm:w-auto">
                    <a href="{{ route('mcu') }}" class="flex-1 sm:flex-none text-center px-5 py-3 rounded-xl border border-gray-300 text-gray-600 font-semibold text-sm hover:bg-gray-100 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="{{ $c['bg'] }} text-white flex-1 sm:flex-none px-8 py-3 rounded-xl font-bold text-sm hover:opacity-90 transition-all shadow-md flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>

    </div>
</section>

@endsection
