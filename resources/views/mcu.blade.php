@extends('layouts.app')
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => $banner ?? \App\Models\PageBanner::getForPage('mcu'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Medical Check-Up'],
]])

<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-12 fade-up">
            <span class="section-label">Pilih Paket</span>
            <h2 class="section-title">Paket Medical <span>Check-Up</span></h2>
            <p class="text-gray-500 text-sm mt-2 max-w-lg mx-auto">Semua paket mencakup konsultasi dokter, hasil pemeriksaan lengkap, dan rekomendasi kesehatan personal.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                [
                    'nama'=>'Basic','harga'=>'Rp 450.000','color'=>'green','icon'=>'fa-leaf',
                    'items'=>['Pemeriksaan fisik umum','Darah lengkap','Urin lengkap','Gula darah puasa','Kolesterol total','Konsultasi dokter'],
                    'best'=>false,
                ],
                [
                    'nama'=>'Standard','harga'=>'Rp 850.000','color'=>'blue','icon'=>'fa-star',
                    'items'=>['Semua paket Basic','Fungsi hati (SGOT/SGPT)','Fungsi ginjal','Asam urat','Rontgen thorax','EKG / Rekam jantung'],
                    'best'=>false,
                ],
                [
                    'nama'=>'Executive','harga'=>'Rp 1.750.000','color'=>'purple','icon'=>'fa-crown',
                    'items'=>['Semua paket Standard','USG abdomen','Thyroid (TSH, T3, T4)','Tumor marker','Spirometri','Audiometri','Visus mata'],
                    'best'=>true,
                ],
                [
                    'nama'=>'Corporate','harga'=>'Custom','color'=>'orange','icon'=>'fa-building',
                    'items'=>['Disesuaikan kebutuhan','Kunjungan ke perusahaan','Laporan kolektif','Konsultasi HR','Sertifikat kesehatan','Harga volume'],
                    'best'=>false,
                ],
            ] as $p)
            <div class="card-base overflow-hidden relative {{ $p['best'] ? 'ring-2 ring-purple-500' : '' }} fade-up">
                @if($p['best'])
                <div class="absolute top-0 left-0 right-0 text-center bg-purple-600 text-white text-xs font-bold py-1 tracking-wider">
                    ⭐ TERPOPULER
                </div>
                @endif
                <div class="p-6 {{ $p['best'] ? 'pt-10' : '' }}">
                    <div class="w-12 h-12 rounded-xl bg-{{ $p['color'] }}-100 flex items-center justify-center mb-4">
                        <i class="fas {{ $p['icon'] }} text-{{ $p['color'] }}-600 text-xl"></i>
                    </div>
                    <h3 class="font-extrabold text-gray-900 text-xl mb-1">{{ $p['nama'] }}</h3>
                    <div class="text-2xl font-black text-{{ $p['color'] }}-600 mb-5">{{ $p['harga'] }}</div>
                    <ul class="space-y-2 mb-6">
                        @foreach($p['items'] as $item)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <i class="fas fa-check-circle text-{{ $p['color'] }}-500 mt-0.5 flex-shrink-0 text-xs"></i>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('portal.booking.create') }}" class="block w-full text-center py-2.5 rounded-xl font-bold text-sm transition-all
                        {{ $p['best'] ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Daftar Sekarang
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="rounded-2xl p-8 md:p-12 text-center" style="background: linear-gradient(135deg, #1e3a5f, #0f4c81);">
            <i class="fas fa-clipboard-check text-blue-300 text-4xl mb-4 block"></i>
            <h2 class="text-white font-extrabold text-2xl mb-3">Daftar MCU Sekarang</h2>
            <p class="text-blue-100 text-sm mb-6 max-w-lg mx-auto">Hubungi kami atau datang langsung ke RS Sari Sehat. Tersedia setiap hari Senin–Sabtu, pukul 07.00–14.00.</p>
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('portal.booking.create') }}" class="flex items-center gap-2 bg-white text-blue-800 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-50 transition-all shadow-lg">
                    <i class="fas fa-calendar-check"></i> Buat Janji Temu
                </a>
                <a href="tel:02150943838" class="flex items-center gap-2 border-2 border-white/60 text-white px-6 py-3 rounded-xl font-semibold text-sm hover:border-white transition-all">
                    <i class="fas fa-phone-alt"></i> (021) 5094-3838
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
