@extends('layouts.app')
@section('content')

<div class="py-20" style="background: linear-gradient(135deg, #00521f, #00b04f);">
    <div class="max-w-screen-xl mx-auto px-4 text-center">
        <span class="text-green-300 text-xs font-black uppercase tracking-widest block mb-2">Pantau Antrian</span>
        <h1 class="text-white font-extrabold text-4xl mb-3">Live Antrian</h1>
        <p class="text-green-100 text-sm max-w-xl mx-auto">Pantau antrian poliklinik secara real-time di RS Sari Sehat.</p>
        <nav class="flex items-center justify-center gap-2 mt-5 text-sm text-green-200">
            <a href="{{ route('home') }}" class="hover:text-white">Beranda</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white font-semibold">Live Antrian</span>
        </nav>
    </div>
</div>

<section class="py-14 bg-gray-50">
    <div class="max-w-screen-xl mx-auto px-4">
        {{-- Cabang dinonaktifkan (menghindari tampilan cabang setelah data cabang dihapus) --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-8">
            <h2 class="font-extrabold text-gray-900 text-lg mb-2 flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-green-600"></i> Live Antrian
            </h2>

        </div>


        {{-- Antrian Grid --}}
        <div id="antrian-content">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @php
                $poliklinik = [
                    ['nama'=>'Poli Umum',          'icon'=>'fa-stethoscope', 'antrian'=>12, 'dipanggil'=>8,  'estimasi'=>'±15 menit', 'color'=>'green',  'status'=>'Buka'],
                    ['nama'=>'Poli Anak',           'icon'=>'fa-baby',        'antrian'=>7,  'dipanggil'=>5,  'estimasi'=>'±10 menit', 'color'=>'blue',   'status'=>'Buka'],
                    ['nama'=>'Poli Jantung',        'icon'=>'fa-heartbeat',   'antrian'=>19, 'dipanggil'=>14, 'estimasi'=>'±25 menit', 'color'=>'red',    'status'=>'Buka'],
                    ['nama'=>'Poli Kandungan',      'icon'=>'fa-female',      'antrian'=>9,  'dipanggil'=>6,  'estimasi'=>'±15 menit', 'color'=>'pink',   'status'=>'Buka'],
                    ['nama'=>'Poli Penyakit Dalam', 'icon'=>'fa-user-md',     'antrian'=>15, 'dipanggil'=>11, 'estimasi'=>'±20 menit', 'color'=>'indigo', 'status'=>'Buka'],
                    ['nama'=>'Poli Syaraf',         'icon'=>'fa-brain',       'antrian'=>0,  'dipanggil'=>0,  'estimasi'=>'-',          'color'=>'gray',   'status'=>'Tutup'],
                    ['nama'=>'Poli Ortopedi',       'icon'=>'fa-bone',        'antrian'=>6,  'dipanggil'=>4,  'estimasi'=>'±10 menit', 'color'=>'orange', 'status'=>'Buka'],
                    ['nama'=>'Poli Mata',           'icon'=>'fa-eye',         'antrian'=>11, 'dipanggil'=>8,  'estimasi'=>'±18 menit', 'color'=>'teal',   'status'=>'Buka'],
                    ['nama'=>'Poli THT',            'icon'=>'fa-deaf',        'antrian'=>4,  'dipanggil'=>3,  'estimasi'=>'±8 menit',  'color'=>'purple', 'status'=>'Buka'],
                ];
                @endphp
                @foreach($poliklinik as $p)
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-{{ $p['color'] }}-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas {{ $p['icon'] }} text-{{ $p['color'] }}-600 text-sm"></i>
                            </div>
                            <h3 class="font-bold text-gray-900 text-sm">{{ $p['nama'] }}</h3>
                        </div>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $p['status']==='Buka' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $p['status'] }}
                        </span>
                    </div>
                    @if($p['status']==='Buka')
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="bg-gray-50 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-green-700">{{ $p['antrian'] }}</div>
                            <div class="text-xs text-gray-500 font-medium">Total Antrian</div>
                        </div>
                        <div class="bg-green-50 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-green-600">{{ $p['dipanggil'] }}</div>
                            <div class="text-xs text-gray-500 font-medium">Nomor Dipanggil</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 bg-yellow-50 rounded-lg px-3 py-2 border border-yellow-100">
                        <i class="fas fa-clock text-yellow-500"></i>
                        <span>Estimasi tunggu: <strong class="text-yellow-700">{{ $p['estimasi'] }}</strong></span>
                    </div>
                    @else
                    <div class="bg-gray-50 rounded-xl p-4 text-center">
                        <i class="fas fa-door-closed text-gray-300 text-2xl mb-2 block"></i>
                        <p class="text-gray-400 text-xs">Poliklinik sedang tutup</p>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Info Update --}}
        <div class="mt-6 flex items-center justify-between bg-white rounded-xl px-5 py-3 border border-gray-100 shadow-sm">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse inline-block"></span>
                Data diperbarui secara berkala
            </div>
            <button onclick="location.reload()" class="flex items-center gap-1.5 text-green-600 text-xs font-bold hover:text-green-800 transition-colors">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
</section>

@endsection
@push('scripts')
<script>

</script>
@endpush
