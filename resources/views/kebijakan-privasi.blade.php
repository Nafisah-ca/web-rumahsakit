@extends('layouts.app')
@php $title = 'Kebijakan Privasi'; @endphp
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => \App\Models\PageBanner::getForPage('kebijakan-privasi'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Kebijakan Privasi'],
]])

<section class="py-14 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12">
            @if($setting->privacy_policy)
                <div class="prose prose-green max-w-none text-gray-700 leading-relaxed" style="font-size:15px;line-height:1.8">
                    {!! $setting->privacy_policy !!}
                </div>
            @else
                {{-- Konten default --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed" style="font-size:15px;line-height:1.8">
                    <p class="text-sm text-gray-400 mb-6">Terakhir diperbarui: {{ date('d F Y') }}</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">1. Pendahuluan</h2>
                    <p>{{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }} ("kami", "kita", atau "Rumah Sakit") berkomitmen untuk melindungi privasi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, mengungkapkan, dan melindungi informasi pribadi Anda ketika Anda menggunakan layanan dan website kami.</p>
                    <p>Dengan menggunakan layanan kami, Anda menyetujui praktik yang dijelaskan dalam Kebijakan Privasi ini.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">2. Informasi yang Kami Kumpulkan</h2>
                    <p>Kami dapat mengumpulkan jenis informasi berikut:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li><strong>Informasi Identitas:</strong> nama lengkap, tanggal lahir, jenis kelamin, nomor identitas (KTP/SIM/Paspor).</li>
                        <li><strong>Informasi Kontak:</strong> alamat email, nomor telepon, alamat tempat tinggal.</li>
                        <li><strong>Informasi Medis:</strong> riwayat kesehatan, diagnosis, resep, dan data rekam medis lainnya yang Anda berikan kepada kami dalam konteks pelayanan kesehatan.</li>
                        <li><strong>Informasi Pembayaran:</strong> metode pembayaran, informasi asuransi kesehatan, atau data penjamin lainnya.</li>
                        <li><strong>Data Teknis:</strong> alamat IP, jenis browser, sistem operasi, halaman yang dikunjungi, waktu kunjungan.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">3. Cara Kami Menggunakan Informasi Anda</h2>
                    <p>Kami menggunakan informasi yang dikumpulkan untuk:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Memberikan layanan kesehatan dan medis kepada Anda.</li>
                        <li>Mengelola janji temu, pendaftaran, dan rekam medis.</li>
                        <li>Memproses pembayaran dan klaim asuransi.</li>
                        <li>Mengirimkan pengingat janji temu atau informasi kesehatan yang relevan.</li>
                        <li>Meningkatkan kualitas layanan dan pengalaman pengguna website kami.</li>
                        <li>Mematuhi kewajiban hukum dan regulasi yang berlaku di bidang kesehatan.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">4. Dasar Hukum Pemrosesan Data</h2>
                    <p>Pemrosesan data pribadi Anda dilakukan berdasarkan:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Pelaksanaan perjanjian layanan kesehatan antara Anda dan kami.</li>
                        <li>Kewajiban hukum yang kami tunduki, termasuk Undang-Undang Kesehatan dan peraturan terkait.</li>
                        <li>Kepentingan vital Anda atau pihak lain dalam kondisi darurat medis.</li>
                        <li>Persetujuan eksplisit yang Anda berikan kepada kami.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">5. Penyimpanan dan Keamanan Data</h2>
                    <p>Kami menyimpan data pribadi Anda selama diperlukan untuk memenuhi tujuan yang dijelaskan dalam kebijakan ini, atau sebagaimana diwajibkan oleh hukum. Data medis disimpan sesuai ketentuan rekam medis yang berlaku.</p>
                    <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang wajar untuk melindungi data Anda dari akses, pengungkapan, perubahan, atau penghancuran yang tidak sah.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">6. Berbagi Informasi dengan Pihak Ketiga</h2>
                    <p>Kami tidak menjual data pribadi Anda. Kami dapat berbagi data dengan:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Tenaga kesehatan lain yang terlibat dalam perawatan Anda.</li>
                        <li>Perusahaan asuransi atau penjamin sesuai permintaan Anda.</li>
                        <li>Penyedia layanan teknologi yang membantu operasional kami, dengan perjanjian kerahasiaan.</li>
                        <li>Instansi pemerintah atau penegak hukum bila diwajibkan oleh peraturan perundang-undangan.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">7. Hak-Hak Anda</h2>
                    <p>Sesuai dengan peraturan perlindungan data yang berlaku, Anda berhak untuk:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li><strong>Akses:</strong> meminta salinan data pribadi yang kami simpan tentang Anda.</li>
                        <li><strong>Koreksi:</strong> meminta perbaikan data yang tidak akurat atau tidak lengkap.</li>
                        <li><strong>Penghapusan:</strong> meminta penghapusan data dalam kondisi tertentu yang diizinkan hukum.</li>
                        <li><strong>Penarikan Persetujuan:</strong> menarik persetujuan yang sebelumnya diberikan kapan saja.</li>
                        <li><strong>Portabilitas:</strong> meminta data dalam format yang dapat dibaca mesin.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">8. Cookie dan Teknologi Pelacakan</h2>
                    <p>Website kami menggunakan cookie untuk meningkatkan pengalaman pengguna, menganalisis lalu lintas website, dan mengingat preferensi Anda. Anda dapat mengatur browser untuk menolak semua cookie atau memberi tahu Anda saat cookie dikirimkan.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">9. Perubahan Kebijakan Privasi</h2>
                    <p>Kami dapat memperbarui Kebijakan Privasi ini sewaktu-waktu. Perubahan signifikan akan kami beritahukan melalui website atau email. Penggunaan layanan kami setelah pembaruan dianggap sebagai penerimaan terhadap kebijakan yang diperbarui.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">10. Hubungi Kami</h2>
                    <p>Jika Anda memiliki pertanyaan mengenai Kebijakan Privasi ini atau ingin menggunakan hak-hak Anda, silakan hubungi:</p>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mt-3">
                        <p class="font-bold text-gray-900">{{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }}</p>
                        @if($setting->alamat)<p class="text-sm text-gray-600 mt-1">{{ $setting->alamat }}</p>@endif
                        @if($setting->telepon)<p class="text-sm text-gray-600">Telepon: {{ $setting->telepon }}</p>@endif
                        @if($setting->email)<p class="text-sm text-gray-600">Email: <a href="https://mail.google.com/mail/?view=cm&to={{ urlencode($setting->email) }}" target="_blank" rel="noopener" class="text-green-600 hover:underline">{{ $setting->email }}</a></p>@endif
                    </div>
                </div>
            @endif
        </div>

        <div class="mt-8 flex gap-4 justify-center flex-wrap">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl font-semibold text-sm transition-colors">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('syarat-ketentuan') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-colors">
                <i class="fas fa-file-contract"></i> Syarat & Ketentuan
            </a>
            <a href="{{ route('kontak') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-colors">
                <i class="fas fa-envelope"></i> Hubungi Kami
            </a>
        </div>
    </div>
</section>

@endsection
