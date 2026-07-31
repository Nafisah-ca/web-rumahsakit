@extends('layouts.app')
@section('content')

<div class="py-20" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Sarana & Prasarana</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Fasilitas</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Peralatan medis modern dan fasilitas berstandar tinggi untuk menunjang pelayanan kesehatan terbaik.</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Fasilitas</span>
        </nav>
    </div>
</div>

{{-- Fasilitas Unggulan --}}
<section class="py-14 bg-white">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-10 fade-up">
            <span class="section-label">Unggulan</span>
            <h2 class="section-title">Fasilitas <span>Medis Terkini</span></h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            @foreach([
                ['fa-procedures','ICU & ICCU Modern','Tersedia 40 bed ICU dan 20 bed ICCU dengan monitor hemodinamik, ventilator terbaru, dan tim intensivis 24 jam berpengalaman.','#ef4444','60 Bed'],
                ['fa-cut','Kamar Operasi','12 kamar operasi termasuk 2 OR Hybrid dengan sistem navigasi intraoperatif dan robot bedah untuk tindakan minimal invasif.','#00b04f','12 OK'],
                ['fa-baby','NICU Tingkat III','20 inkubator servo-controlled, ventilator neonatal, dan tim dokter anak subspesialis neonatologi 24 jam.','#be185d','20 Inkubator'],
                ['fa-x-ray','Radiologi Digital','MRI, CT-Scan, USG 4D, Mammografi digital, dan Rontgen digital terintegrasi untuk diagnosis akurat dan cepat.','#0f4c81','Multi-Modalitas'],
            ] as [$ico,$title,$desc,$color,$badge])
            <div class="flex gap-5 p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all fade-up bg-white group">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform"
                     style="background: {{ $color }}18;">
                    <i class="fas {{ $ico }} text-xl" style="color: {{ $color }};"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1.5">
                        <h3 class="font-extrabold text-gray-900">{{ $title }}</h3>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full text-white" style="background: {{ $color }};">{{ $badge }}</span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Kelas Kamar --}}
        <div class="text-center mb-10 fade-up">
            <span class="section-label">Kenyamanan Pasien</span>
            <h2 class="section-title">Kelas Kamar <span>Rawat Inap</span></h2>
            <p class="text-gray-500 text-sm mt-2">Pilih kamar yang sesuai kebutuhan dan kenyamanan Anda</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 fade-up">
            @foreach([
                ['kelas'=>'VIP','harga'=>'Rp 1.200.000','icon'=>'fa-star','color'=>'blue',
                 'features'=>['Kamar privat 35m²','Extra bed keluarga','Smart TV 43"','Kulkas mini','Kamar mandi dalam','Wi-Fi tersedia'],'popular'=>true],
                ['kelas'=>'Kelas I','harga'=>'Rp 600.000','icon'=>'fa-bed','color'=>'green',
                 'features'=>['Kamar 2 bed','1 pendamping','TV 32"','Kamar mandi bersama','Nurse call'],'popular'=>false],
                ['kelas'=>'Kelas II','harga'=>'Rp 350.000','icon'=>'fa-users','color'=>'teal',
                 'features'=>['Kamar 3–4 bed','Ventilasi baik','Nurse call','Makan pasien'],'popular'=>false],
                ['kelas'=>'Kelas III','harga'=>'Rp 150.000','icon'=>'fa-hospital','color'=>'gray',
                 'features'=>['Kamar 5–6 bed','BPJS diterima','Fasilitas standar','Layanan medis sama'],'popular'=>false],
            ] as $k)
            <div class="card-base overflow-hidden {{ $k['popular'] ? 'ring-2 ring-blue-500' : '' }} relative">
                @if($k['popular'])
                <div class="absolute top-0 left-0 right-0 bg-blue-600 text-white text-xs font-bold py-1 text-center tracking-wider">⭐ TERPOPULER</div>
                @endif
                <div class="p-5 {{ $k['popular'] ? 'pt-9' : '' }}">
                    <div class="w-10 h-10 rounded-xl bg-{{ $k['color'] }}-100 flex items-center justify-center mb-3">
                        <i class="fas {{ $k['icon'] }} text-{{ $k['color'] }}-600"></i>
                    </div>
                    <h3 class="font-extrabold text-gray-900 text-lg">{{ $k['kelas'] }}</h3>
                    <div class="text-xl font-black text-{{ $k['color'] }}-600 mb-4">{{ $k['harga'] }}<span class="text-xs text-gray-400 font-normal">/malam</span></div>
                    <ul class="space-y-2 mb-5">
                        @foreach($k['features'] as $f)
                        <li class="flex items-center gap-2 text-xs text-gray-600">
                            <i class="fas fa-check-circle text-green-500 flex-shrink-0"></i> {{ $f }}
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('portal.booking.create') }}" class="block w-full text-center py-2.5 rounded-xl text-xs font-bold transition-all
                        {{ $k['popular'] ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-green-600 hover:text-white' }}">
                        Informasi & Booking
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Peralatan Teknologi --}}
<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        <div class="text-center mb-8 fade-up">
            <span class="section-label">Teknologi Medis</span>
            <h2 class="section-title">Peralatan <span>Canggih</span></h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 fade-up">
            @foreach(['MRI 3 Tesla','CT-Scan 128 Slice','USG 4D','Digital Mammografi','Cath Lab','Endoskopi 4K','Hemodialisa','Radioterapi LINAC','ECMO','Laser Mata','Spirometri','Audiometri'] as $alat)
            <div class="bg-white rounded-xl p-4 text-center shadow-sm border border-gray-100 hover:border-green-300 hover:shadow-md transition-all group cursor-default">
                <i class="fas fa-microscope text-green-500 text-xl mb-2 block group-hover:scale-110 transition-transform"></i>
                <span class="text-xs font-semibold text-gray-700 leading-tight block">{{ $alat }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
