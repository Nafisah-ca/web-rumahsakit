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

{{-- ===== PROFESSIONAL ENHANCEMENT JS ===== --}}
<script>
(function () {
    'use strict';

    /* ═══════════════════════════════════════════════════════════
       KEYFRAMES & GLOBAL STYLES
    ═══════════════════════════════════════════════════════════ */
    var css = `
        /* Scroll reveal initial state */
        .k-reveal {
            opacity: 0;
            transform: translateY(32px);
            transition: opacity .6s cubic-bezier(.22,1,.36,1), transform .6s cubic-bezier(.22,1,.36,1);
        }
        .k-reveal.k-visible { opacity: 1; transform: translateY(0); }

        /* Ripple */
        .k-ripple-host { position: relative; overflow: hidden; }
        .k-ripple {
            position: absolute; border-radius: 50%; transform: scale(0);
            background: rgba(255,255,255,.35);
            animation: kRipple .55s linear;
            pointer-events: none;
        }
        @keyframes kRipple { to { transform: scale(4); opacity: 0; } }

        /* Toast */
        #k-toast-wrap {
            position: fixed; bottom: 28px; left: 50%; transform: translateX(-50%);
            z-index: 9999; display: flex; flex-direction: column; align-items: center; gap: 8px;
            pointer-events: none;
        }
        .k-toast {
            display: flex; align-items: center; gap: 10px;
            background: #111827; color: #fff;
            padding: 10px 20px; border-radius: 100px;
            font-size: 13px; font-weight: 600;
            box-shadow: 0 8px 32px rgba(0,0,0,.22);
            opacity: 0; transform: translateY(14px) scale(.95);
            transition: opacity .28s cubic-bezier(.22,1,.36,1), transform .28s cubic-bezier(.22,1,.36,1);
            pointer-events: auto; max-width: 340px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .k-toast.k-toast-in { opacity: 1; transform: translateY(0) scale(1); }
        .k-toast.k-toast-out { opacity: 0; transform: translateY(-8px) scale(.95); }
        .k-toast-icon { flex-shrink: 0; width: 18px; height: 18px; }

        /* Alert progress bar */
        .k-alert-bar {
            position: absolute; bottom: 0; left: 0; height: 3px; width: 100%;
            background: linear-gradient(90deg, #16a34a, #4ade80);
            border-radius: 0 0 1rem 1rem;
            transform-origin: left;
            animation: kBar 5s linear forwards;
        }
        @keyframes kBar { to { transform: scaleX(0); } }

        /* Input focus glow line */
        .k-field { position: relative; }
        .k-field::after {
            content: '';
            position: absolute; bottom: 0; left: 50%; right: 50%;
            height: 2px; background: linear-gradient(90deg, #16a34a, #4ade80);
            border-radius: 2px;
            transition: left .28s cubic-bezier(.22,1,.36,1), right .28s cubic-bezier(.22,1,.36,1);
            pointer-events: none;
        }
        .k-field.k-field-active::after { left: 0; right: 0; }

        /* Char counter pill */
        .k-counter {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11px; font-weight: 600; color: #9ca3af;
            transition: color .2s;
        }
        .k-counter.k-counter-warn { color: #f97316; }
        .k-counter.k-counter-danger { color: #ef4444; }
        .k-counter-ring {
            width: 18px; height: 18px;
        }
        .k-counter-ring circle {
            transition: stroke-dashoffset .3s ease, stroke .3s ease;
        }

        /* Inline validation */
        .k-valid-msg {
            font-size: 11px; font-weight: 600; margin-top: 4px;
            display: flex; align-items: center; gap: 4px;
            opacity: 0; transform: translateY(-4px);
            transition: opacity .2s, transform .2s;
        }
        .k-valid-msg.k-show { opacity: 1; transform: translateY(0); }
        .k-valid-msg.k-ok { color: #16a34a; }
        .k-valid-msg.k-err { color: #ef4444; }

        /* Submit btn pulse ring on hover */
        .k-submit-ring {
            position: absolute; inset: -3px; border-radius: inherit;
            border: 2px solid transparent; pointer-events: none;
            transition: border-color .2s, inset .2s;
        }
        button[type=submit]:hover .k-submit-ring {
            border-color: rgba(22,163,74,.4); inset: -5px;
        }

        /* Contact card copy hint */
        .k-copy-card { transition: background .18s, transform .18s, box-shadow .18s; }
        .k-copy-card:hover {
            background: #f0fdf4 !important;
            transform: translateX(4px);
            box-shadow: 0 4px 18px rgba(22,163,74,.1);
        }
        .k-copy-badge {
            font-size: 10px; font-weight: 700; color: #16a34a;
            background: #dcfce7; border-radius: 6px;
            padding: 2px 7px; flex-shrink: 0;
            opacity: 0; transition: opacity .2s;
        }
        .k-copy-card:hover .k-copy-badge { opacity: 1; }
    `;
    var styleEl = document.createElement('style');
    styleEl.textContent = css;
    document.head.appendChild(styleEl);

    /* ═══════════════════════════════════════════════════════════
       TOAST SYSTEM
    ═══════════════════════════════════════════════════════════ */
    var toastWrap = document.createElement('div');
    toastWrap.id = 'k-toast-wrap';
    document.body.appendChild(toastWrap);

    function showToast(msg, type) {
        var icons = {
            success: '<svg class="k-toast-icon" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="9" fill="#16a34a"/><path d="M6 10.5l2.5 2.5 5-5" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
            copy:    '<svg class="k-toast-icon" viewBox="0 0 20 20" fill="none"><rect x="4" y="6" width="9" height="11" rx="2" stroke="#a78bfa" stroke-width="1.6"/><path d="M7 6V5a2 2 0 012-2h6a2 2 0 012 2v9a2 2 0 01-2 2h-1" stroke="#a78bfa" stroke-width="1.6"/></svg>',
            info:    '<svg class="k-toast-icon" viewBox="0 0 20 20" fill="none"><circle cx="10" cy="10" r="9" stroke="#60a5fa" stroke-width="1.6"/><path d="M10 9v5M10 7h.01" stroke="#60a5fa" stroke-width="1.8" stroke-linecap="round"/></svg>',
        };
        type = type || 'success';
        var t = document.createElement('div');
        t.className = 'k-toast';
        t.innerHTML = (icons[type] || icons.success) + '<span>' + msg + '</span>';
        toastWrap.appendChild(t);
        requestAnimationFrame(function () {
            requestAnimationFrame(function () { t.classList.add('k-toast-in'); });
        });
        setTimeout(function () {
            t.classList.remove('k-toast-in');
            t.classList.add('k-toast-out');
            setTimeout(function () { t.remove(); }, 320);
        }, 2600);
    }

    /* ═══════════════════════════════════════════════════════════
       1. STAGGERED SCROLL REVEAL — IntersectionObserver
    ═══════════════════════════════════════════════════════════ */
    var revealSelectors = [
        '.grid.grid-cols-1.md\\:grid-cols-2 > a',   // WA & email cards
        '#guestbook',
        '.space-y-3 > div.flex.gap-3',              // contact info rows
        '.ulasan-card-kontak',
        '#ulasan-form > div > div',
    ];

    var revealAll = document.querySelectorAll(revealSelectors.join(','));

    // group by parent to stagger siblings
    var groups = new Map();
    revealAll.forEach(function (el) {
        var p = el.parentElement;
        if (!groups.has(p)) groups.set(p, []);
        groups.get(p).push(el);
        el.classList.add('k-reveal');
    });

    var observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            var el = entry.target;
            var siblings = groups.get(el.parentElement) || [el];
            var idx = siblings.indexOf(el);
            setTimeout(function () { el.classList.add('k-visible'); }, idx * 80);
            observer.unobserve(el);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -48px 0px' });

    revealAll.forEach(function (el) { observer.observe(el); });

    /* ═══════════════════════════════════════════════════════════
       2. RIPPLE EFFECT on submit buttons
    ═══════════════════════════════════════════════════════════ */
    document.querySelectorAll('button[type="submit"]').forEach(function (btn) {
        btn.classList.add('k-ripple-host');
        btn.style.position = 'relative';

        // add ring element
        var ring = document.createElement('span');
        ring.className = 'k-submit-ring';
        btn.appendChild(ring);

        btn.addEventListener('click', function (e) {
            var circle = document.createElement('span');
            var diameter = Math.max(btn.clientWidth, btn.clientHeight);
            var rect = btn.getBoundingClientRect();
            circle.className = 'k-ripple';
            circle.style.cssText = [
                'width:' + diameter + 'px',
                'height:' + diameter + 'px',
                'left:' + (e.clientX - rect.left - diameter / 2) + 'px',
                'top:' + (e.clientY - rect.top - diameter / 2) + 'px',
            ].join(';');
            btn.appendChild(circle);
            setTimeout(function () { circle.remove(); }, 600);
        });
    });

    /* ═══════════════════════════════════════════════════════════
       3. PROFESSIONAL LOADING STATE on form submit
    ═══════════════════════════════════════════════════════════ */
    document.querySelectorAll('form').forEach(function (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type="submit"]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            btn.dataset.original = btn.innerHTML;
            btn.innerHTML = [
                '<svg class="animate-spin inline w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">',
                '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>',
                '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>',
                '</svg>',
                '<span>Mengirim Pesan...</span>',
            ].join('');
            btn.style.transition = 'opacity .2s';
            btn.style.opacity = '.85';
        });
    });

    /* ═══════════════════════════════════════════════════════════
       4. LIVE CHARACTER COUNTER with SVG ring
    ═══════════════════════════════════════════════════════════ */
    document.querySelectorAll('textarea[maxlength]').forEach(function (ta) {
        var max = parseInt(ta.getAttribute('maxlength'));
        var hint = ta.closest('div') ? ta.closest('div').querySelector('p.text-xs') : null;
        if (!hint) return;

        var R = 7, C = 2 * Math.PI * R;
        hint.innerHTML = [
            '<span class="k-counter" id="kc-' + max + '">',
            '<svg class="k-counter-ring" viewBox="0 0 20 20">',
            '<circle cx="10" cy="10" r="' + R + '" fill="none" stroke="#e5e7eb" stroke-width="2.4"/>',
            '<circle id="kc-arc-' + max + '" cx="10" cy="10" r="' + R + '" fill="none" stroke="#16a34a" stroke-width="2.4"',
            ' stroke-dasharray="' + C + '" stroke-dashoffset="' + C + '"',
            ' stroke-linecap="round" transform="rotate(-90 10 10)"/>',
            '</svg>',
            '<span id="kc-txt-' + max + '">' + max + ' karakter tersisa</span>',
            '</span>',
        ].join('');

        var arc  = document.getElementById('kc-arc-' + max);
        var txt  = document.getElementById('kc-txt-' + max);
        var pill = document.getElementById('kc-' + max);

        function update() {
            var used = ta.value.length;
            var remaining = max - used;
            var pct = used / max;
            arc.style.strokeDashoffset = C * (1 - pct);
            txt.textContent = remaining + ' karakter tersisa';
            if (pct >= 1) {
                arc.style.stroke = '#ef4444';
                pill.className = 'k-counter k-counter-danger';
            } else if (pct >= .85) {
                arc.style.stroke = '#f97316';
                pill.className = 'k-counter k-counter-warn';
            } else {
                arc.style.stroke = '#16a34a';
                pill.className = 'k-counter';
            }
        }

        ta.addEventListener('input', update);
        update();
    });

    /* ═══════════════════════════════════════════════════════════
       5. INLINE LIVE VALIDATION
    ═══════════════════════════════════════════════════════════ */
    var rules = {
        'input[name="nama"]':   { min: 2, msg: 'Nama minimal 2 karakter.' },
        'input[name="email"]':  { pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, msg: 'Format email tidak valid.' },
        'input[name="telepon"]':{ pattern: /^[0-9+\-\s()]{6,20}$/, msg: 'Format nomor tidak valid.' },
    };

    Object.keys(rules).forEach(function (sel) {
        var rule = rules[sel];
        document.querySelectorAll(sel).forEach(function (inp) {
            /* add wrapper class */
            var wrap = inp.closest('div');
            if (wrap) wrap.classList.add('k-field');

            /* create message el */
            var msg = document.createElement('p');
            msg.className = 'k-valid-msg';
            inp.insertAdjacentElement('afterend', msg);

            function validate() {
                var v = inp.value.trim();
                if (!v) { msg.classList.remove('k-show'); return; }
                var ok = rule.pattern ? rule.pattern.test(v) : (v.length >= (rule.min || 0));
                msg.innerHTML = ok
                    ? '<svg width="12" height="12" viewBox="0 0 12 12"><circle cx="6" cy="6" r="5" fill="#16a34a"/><path d="M3.5 6.2l1.5 1.5 3-3" stroke="#fff" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Terlihat bagus!'
                    : '<svg width="12" height="12" viewBox="0 0 12 12"><circle cx="6" cy="6" r="5" fill="#ef4444"/><path d="M4 4l4 4M8 4l-4 4" stroke="#fff" stroke-width="1.2" stroke-linecap="round"/></svg> ' + rule.msg;
                msg.className = 'k-valid-msg k-show ' + (ok ? 'k-ok' : 'k-err');
                inp.style.borderColor = ok ? '#86efac' : '#fca5a5';
            }

            inp.addEventListener('blur', validate);
            inp.addEventListener('input', function () {
                if (inp.value.length > 3) validate();
            });
        });
    });

    /* ═══════════════════════════════════════════════════════════
       6. INPUT FOCUS GLOW LINE
    ═══════════════════════════════════════════════════════════ */
    document.querySelectorAll('input, textarea').forEach(function (inp) {
        var wrap = inp.closest('div');
        if (!wrap) return;
        wrap.classList.add('k-field');
        inp.addEventListener('focus', function () { wrap.classList.add('k-field-active'); });
        inp.addEventListener('blur',  function () { wrap.classList.remove('k-field-active'); });
    });

    /* ═══════════════════════════════════════════════════════════
       7. COPY-TO-CLIPBOARD on contact info cards
    ═══════════════════════════════════════════════════════════ */
    document.querySelectorAll('.space-y-3 > div.flex.gap-3').forEach(function (card) {
        card.classList.add('k-copy-card');
        card.style.cursor = 'pointer';

        /* append "Salin" badge */
        var badge = document.createElement('span');
        badge.className = 'k-copy-badge';
        badge.textContent = 'Salin';
        card.appendChild(badge);

        card.addEventListener('click', function () {
            var textEl = this.querySelector('p.text-gray-500, a');
            if (!textEl) return;
            var text = textEl.textContent.trim();
            if (!text) return;

            var doToast = function () {
                showToast('Tersalin: ' + text.substring(0, 36) + (text.length > 36 ? '…' : ''), 'copy');
                /* brief flash */
                card.style.background = '#f0fdf4';
                setTimeout(function () { card.style.background = ''; }, 500);
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(doToast);
            } else {
                var ta = document.createElement('textarea');
                ta.value = text; ta.style.cssText = 'position:fixed;opacity:0';
                document.body.appendChild(ta); ta.select();
                document.execCommand('copy'); ta.remove();
                doToast();
            }
        });
    });

    /* ═══════════════════════════════════════════════════════════
       8. AUTO-DISMISS SUCCESS ALERTS with progress bar
    ═══════════════════════════════════════════════════════════ */
    document.querySelectorAll('.bg-green-50.border.border-green-200.rounded-2xl').forEach(function (alert) {
        alert.style.position = 'relative';
        alert.style.overflow = 'hidden';

        /* close button */
        var closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 14 14"><path d="M2 2l10 10M12 2L2 12" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
        closeBtn.style.cssText = 'position:absolute;top:10px;right:12px;color:#9ca3af;background:none;border:none;cursor:pointer;padding:2px;line-height:1;display:flex;align-items:center;justify-content:center;border-radius:4px;transition:color .15s';
        closeBtn.addEventListener('mouseenter', function () { this.style.color = '#374151'; });
        closeBtn.addEventListener('mouseleave', function () { this.style.color = '#9ca3af'; });
        alert.appendChild(closeBtn);

        var bar = document.createElement('div');
        bar.className = 'k-alert-bar';
        alert.appendChild(bar);

        function dismiss() {
            alert.style.transition = 'opacity .35s, max-height .4s ease, padding .3s, margin .3s';
            alert.style.maxHeight = alert.scrollHeight + 'px';
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    alert.style.opacity = '0';
                    alert.style.maxHeight = '0';
                    alert.style.padding = '0';
                    alert.style.marginBottom = '0';
                });
            });
            setTimeout(function () { alert.remove(); }, 450);
        }

        closeBtn.addEventListener('click', dismiss);
        setTimeout(dismiss, 5200);
    });

    /* ═══════════════════════════════════════════════════════════
       9. SMOOTH TEXTAREA AUTO-RESIZE
    ═══════════════════════════════════════════════════════════ */
    document.querySelectorAll('textarea.resize-none').forEach(function (ta) {
        ta.style.overflowY = 'hidden';
        function resize() {
            ta.style.height = 'auto';
            ta.style.height = (ta.scrollHeight) + 'px';
        }
        ta.addEventListener('input', resize);
        resize();
    });

    /* ═══════════════════════════════════════════════════════════
       10. FLOATING SCROLL-TO-TOP (appears after 300px scroll)
    ═══════════════════════════════════════════════════════════ */
    var fab = document.createElement('button');
    fab.type = 'button';
    fab.setAttribute('aria-label', 'Kembali ke atas');
    fab.innerHTML = '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M10 14V6M6 10l4-4 4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    fab.style.cssText = [
        'position:fixed;bottom:88px;right:24px;z-index:9000',
        'width:44px;height:44px;border-radius:14px',
        'background:#16a34a;color:#fff;border:none;cursor:pointer',
        'display:flex;align-items:center;justify-content:center',
        'box-shadow:0 4px 18px rgba(22,163,74,.35)',
        'opacity:0;transform:scale(.8) translateY(8px)',
        'transition:opacity .3s,transform .3s',
        'pointer-events:none',
    ].join(';');
    document.body.appendChild(fab);

    window.addEventListener('scroll', function () {
        var show = window.scrollY > 300;
        fab.style.opacity = show ? '1' : '0';
        fab.style.transform = show ? 'scale(1) translateY(0)' : 'scale(.8) translateY(8px)';
        fab.style.pointerEvents = show ? 'auto' : 'none';
    }, { passive: true });

    fab.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

})();
</script>
@endpush
