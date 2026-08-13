@extends('layouts.app')
@section('content')

@push('styles')
<style>
.bounce-btn {
    transition: transform .15s cubic-bezier(.34,1.56,.64,1), box-shadow .15s ease;
}
.bounce-btn:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,.1);
}
.bounce-btn:active {
    transform: translateY(-1px) scale(.97);
    box-shadow: 0 2px 6px rgba(0,0,0,.08);
}
.ulasan-card-kontak {
    transition: transform .2s ease, box-shadow .2s ease;
}
.ulasan-card-kontak:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,.09);
}
</style>
@endpush

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('kontak'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Hubungi Kami'],
]])

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
            @php
                $email = $setting->email ?? 'rssarisehat@gmail.com';
                $gmailCompose = 'https://mail.google.com/mail/?view=cm&to=' . urlencode($email);
            @endphp
            <a href="{{ $gmailCompose }}" target="_blank" rel="noopener"
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
                            <a href="https://mail.google.com/mail/?view=cm&to={{ urlencode($setting->email) }}"
                               target="_blank" rel="noopener"
                               class="text-blue-600 hover:underline text-xs mt-0.5 block">{{ $setting->email }}</a>
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

{{-- ===== FORM ULASAN ===== --}}
<section id="ulasan-form" class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            {{-- Form tulis ulasan --}}
            <div>
                <span class="section-label">Testimoni</span>
                <h2 class="section-title mb-6">Tulis <span>Ulasan</span></h2>

                @if(session('success_ulasan'))
                <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-start gap-3">
                    <i class="fas fa-circle-check text-green-600 text-lg flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-bold text-green-800 text-sm">Ulasan Terkirim!</p>
                        <p class="text-green-600 text-xs mt-0.5">{{ session('success_ulasan') }}</p>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('ulasan.store') }}"
                      class="space-y-4 bg-white rounded-2xl p-7 shadow-sm border border-gray-100">
                    @csrf

                    {{-- Rating bintang --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-2">Rating <span class="text-red-500">*</span></label>
                        <div class="flex gap-2" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="rating" value="{{ $i }}" class="sr-only"
                                       {{ old('rating') == $i ? 'checked' : '' }}>
                                <i class="fas fa-star text-2xl transition-colors {{ old('rating') >= $i && old('rating') ? 'text-yellow-400' : 'text-gray-200' }}"
                                   data-star="{{ $i }}"></i>
                            </label>
                            @endfor
                        </div>
                        @error('rating')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required maxlength="150"
                               placeholder="Nama Anda"
                               class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                        @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Email <span class="text-gray-400 text-[10px] font-normal">(opsional)</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" maxlength="150"
                               placeholder="email@contoh.com"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>

                    {{-- Judul --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Judul Ulasan <span class="text-gray-400 text-[10px] font-normal">(opsional)</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}" maxlength="200"
                               placeholder="Ringkasan pengalaman Anda"
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all">
                    </div>

                    {{-- Isi ulasan --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Ulasan <span class="text-red-500">*</span></label>
                        <textarea name="isi" rows="4" required maxlength="1000"
                                  placeholder="Ceritakan pengalaman Anda di RS kami..."
                                  class="w-full px-4 py-2.5 rounded-xl border {{ $errors->has('isi') ? 'border-red-400 bg-red-50' : 'border-gray-200' }} text-sm focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all resize-none">{{ old('isi') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Maksimal 1000 karakter</p>
                        @error('isi')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3.5 rounded-xl font-extrabold text-sm transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-star"></i> Kirim Ulasan
                    </button>
                    <p class="text-center text-xs text-gray-400">Ulasan akan ditinjau sebelum ditampilkan.</p>
                </form>
            </div>

            {{-- Ulasan terbaru --}}
            <div>
                <span class="section-label">Yang Sudah Berbagi</span>
                <h2 class="section-title mb-6">Ulasan <span>Terkini</span></h2>

                @if($ulasanPublic->count() > 0)
                <div class="space-y-4">
                    @foreach($ulasanPublic as $u)
                    <div class="ulasan-card-kontak bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-green-700 font-black text-sm">{{ strtoupper(substr($u->nama, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-sm">{{ $u->nama }}</p>
                                    <p class="text-gray-400 text-xs">{{ $u->created_tm?->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $u->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                        </div>
                        @if($u->judul)
                        <p class="font-bold text-gray-800 text-sm mb-1">{{ $u->judul }}</p>
                        @endif
                        <p class="text-gray-600 text-xs leading-relaxed line-clamp-3">{{ $u->isi }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ route('ulasan.public') }}" class="bounce-btn btn-outline-green inline-flex">
                        Lihat Semua Ulasan <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                @else
                <div class="bg-white rounded-2xl p-10 text-center border border-gray-100">
                    <i class="fas fa-star text-4xl text-gray-200 block mb-3"></i>
                    <p class="text-gray-400 text-sm">Belum ada ulasan. Jadilah yang pertama!</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// Auto scroll ke form ulasan jika ada notif sukses
@if(session('success_ulasan'))
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('ulasan-form');
    if (el) {
        setTimeout(function () {
            el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }
});
@endif

// Star rating interaction
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('#star-rating label');
    stars.forEach(function (label, idx) {
        const icon = label.querySelector('i');
        const input = label.querySelector('input');

        label.addEventListener('mouseenter', function () {
            stars.forEach(function (l, i) {
                l.querySelector('i').classList.toggle('text-yellow-400', i <= idx);
                l.querySelector('i').classList.toggle('text-gray-200', i > idx);
            });
        });

        label.addEventListener('mouseleave', function () {
            const checked = document.querySelector('#star-rating input:checked');
            const checkedVal = checked ? parseInt(checked.value) : 0;
            stars.forEach(function (l, i) {
                l.querySelector('i').classList.toggle('text-yellow-400', i < checkedVal);
                l.querySelector('i').classList.toggle('text-gray-200', i >= checkedVal);
            });
        });

        input.addEventListener('change', function () {
            const val = parseInt(this.value);
            stars.forEach(function (l, i) {
                l.querySelector('i').classList.toggle('text-yellow-400', i < val);
                l.querySelector('i').classList.toggle('text-gray-200', i >= val);
            });
        });
    });
});
</script>
@endpush
