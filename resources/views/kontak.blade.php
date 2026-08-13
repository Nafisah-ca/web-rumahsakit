@extends('layouts.app')
@section('content')

<div class="py-20" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Hubungi Kami</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Kontak</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Kami siap membantu Anda. Hubungi kami atau buat janji temu dengan dokter spesialis pilihan Anda.</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Kontak</span>
        </nav>
    </div>
</div>

<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-12">
            {{-- Data kontak dari website_setting --}}

            {{-- WhatsApp / Telepon --}}
            @php
                $waNumber = $setting->whatsapp ?? $setting->telepon ?? '089501895170';
                $waClean  = preg_replace('/[^0-9]/', '', $waNumber);
                if (str_starts_with($waClean, '0')) $waClean = '62' . substr($waClean, 1);
                if (empty($waClean)) $waClean = '6289501895170';
                $waDisplay = $setting->whatsapp ?? $setting->telepon ?? '+62 895-0189-5170';
            @endphp
            <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener"
               class="block p-6 rounded-2xl text-center border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group bg-green-50">
                <div class="w-16 h-16 rounded-2xl bg-green-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-md">
                    <i class="fab fa-whatsapp text-white text-3xl"></i>
                </div>
                <div class="font-extrabold text-gray-900 text-base mb-1">{{ $waDisplay }}</div>
                <div class="font-semibold text-green-700 text-sm mb-1">WhatsApp & Telepon</div>
                <div class="text-gray-500 text-xs">Tersedia 24 Jam</div>
            </a>

            {{-- Email --}}
            @php $email = $setting->email ?? 'info@sarisehat.id'; @endphp
            <a href="mailto:{{ $email }}"
               class="block p-6 rounded-2xl text-center border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all group bg-blue-50">
                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform shadow-md">
                    <i class="fas fa-envelope text-white text-2xl"></i>
                </div>
                <div class="font-extrabold text-gray-900 text-base mb-1">{{ $email }}</div>
                <div class="font-semibold text-blue-700 text-sm mb-1">Email Kami</div>
                <div class="text-gray-500 text-xs">Balas dalam 1×24 jam</div>
            </a>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            {{-- Form Guest Book --}}
            <div id="guestbook">
                <span class="section-label">Kirim Pesan</span>
                <h2 class="section-title mb-6">Buku <span>Tamu</span></h2>

                @if(session('success'))
                <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-start gap-3">
                    <i class="fas fa-circle-check text-green-600 text-lg flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-green-800 text-sm">Pesan Terkirim!</p>
                        <p class="text-green-600 text-xs mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-2xl">
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('kontak.store') }}"
                      class="space-y-4 bg-white rounded-2xl p-7 shadow-sm border border-gray-100">
                    @csrf
                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="255"
                               placeholder="Masukkan nama lengkap Anda"
                               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>

                    {{-- Email & No HP --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="email@contoh.com"
                                   class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5">No. HP / WhatsApp</label>
                            <input type="text" name="telepon" value="{{ old('telepon') }}" maxlength="20"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Pesan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                        <textarea name="pesan" rows="5" required maxlength="2000"
                                  placeholder="Tulis pertanyaan, saran, atau pesan Anda di sini..."
                                  class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('pesan') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all resize-none">{{ old('pesan') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Maksimal 2000 karakter</p>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3.5 rounded-xl font-extrabold text-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Kirim Pesan
                    </button>
                    <p class="text-center text-xs text-gray-400">
                        Kami akan membalas pesan Anda dalam 1×24 jam pada hari kerja.
                    </p>
                </form>
            </div>

            {{-- Info & Peta --}}
            <div>
                <span class="section-label">Temukan Kami</span>
                <h2 class="section-title mb-6">Lokasi <span>Rumah Sakit</span></h2>

                {{-- Google Maps Embed --}}
                <div class="rounded-2xl overflow-hidden border border-gray-200 shadow-sm mb-5" style="height:280px">
                    @if($setting->google_maps)
                    <iframe src="{{ $setting->google_maps }}"
                        width="100%" height="280" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi {{ $setting->nama_rumahsakit }}">
                    </iframe>
                    @else
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.6!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zUlMgU2FyaSBTZWhhdA!5e0!3m2!1sid!2sid!4v1"
                        width="100%" height="280" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Peta Lokasi RS Sari Sehat">
                    </iframe>
                    @endif
                </div>

                {{-- Info Kontak dari website_setting --}}
                <div class="space-y-3">
                    @if($setting->alamat)
                    <div class="flex gap-3 items-start p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm">{{ $setting->nama_rumahsakit }}</p>
                            <p class="text-gray-500 text-xs mt-0.5">{{ $setting->alamat }}</p>
                        </div>
                    </div>
                    @endif

                    @if($setting->jam_operasional)
                    <div class="flex gap-3 items-start p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm">Jam Operasional</p>
                            <p class="text-gray-500 text-xs mt-0.5">{!! nl2br(e($setting->jam_operasional)) !!}</p>
                        </div>
                    </div>
                    @endif

                    @if($setting->telepon)
                    <div class="flex gap-3 items-start p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-green-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm">Telepon</p>
                            <p class="text-gray-500 text-xs mt-0.5">{{ $setting->telepon }}</p>
                        </div>
                    </div>
                    @endif

                    @if($setting->email)
                    <div class="flex gap-3 items-start p-4 rounded-xl bg-gray-50 border border-gray-100">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm">Email</p>
                            <p class="text-gray-500 text-xs mt-0.5">{{ $setting->email }}</p>
                        </div>
                    </div>
                    @endif

                    <a href="https://www.google.com/maps/search/{{ urlencode($setting->alamat ?? 'RS Sari Sehat') }}" target="_blank" rel="noopener"
                       class="flex items-center justify-center gap-2 w-full py-3 rounded-xl bg-green-600 hover:bg-green-700 text-white font-bold text-sm transition-colors">
                        <i class="fas fa-directions"></i> Buka di Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
