@extends('layouts.app')
@php $title = 'Syarat & Ketentuan'; @endphp
@section('content')

{{-- Hero --}}
@include('_partials.page-hero', ['banner' => \App\Models\PageBanner::getForPage('syarat-ketentuan'), 'breadcrumbs' => [
    ['label' => 'Beranda', 'url' => route('home')],
    ['label' => 'Syarat & Ketentuan'],
]])

<section class="py-14 bg-white">
    <div class="max-w-3xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 md:p-12">
            @if($setting->syarat_ketentuan)
                <div class="prose prose-green max-w-none text-gray-700 leading-relaxed" style="font-size:15px;line-height:1.8">
                    {!! $setting->syarat_ketentuan !!}
                </div>
            @else
                {{-- Konten default --}}
                <div class="prose max-w-none text-gray-700 leading-relaxed" style="font-size:15px;line-height:1.8">
                    <p class="text-sm text-gray-400 mb-6">Terakhir diperbarui: {{ date('d F Y') }}</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">1. Penerimaan Syarat</h2>
                    <p>Dengan mengakses dan menggunakan website serta layanan {{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }} ("Rumah Sakit", "kami"), Anda menyatakan telah membaca, memahami, dan menyetujui Syarat & Ketentuan ini. Jika Anda tidak menyetujui syarat-syarat ini, harap tidak menggunakan layanan kami.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">2. Layanan yang Kami Sediakan</h2>
                    <p>Kami menyediakan layanan kesehatan komprehensif termasuk namun tidak terbatas pada:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Pendaftaran dan penjadwalan janji temu dokter secara online.</li>
                        <li>Akses informasi kesehatan, artikel, dan promosi layanan rumah sakit.</li>
                        <li>Informasi jadwal dokter, spesialisasi, dan poliklinik.</li>
                        <li>Layanan konsultasi awal dan informasi medis umum.</li>
                        <li>Pemesanan kegiatan dan event kesehatan.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">3. Pendaftaran Akun</h2>
                    <p>Untuk menggunakan fitur tertentu, Anda perlu membuat akun dengan ketentuan:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Anda wajib memberikan informasi yang akurat, lengkap, dan terkini.</li>
                        <li>Anda bertanggung jawab untuk menjaga kerahasiaan kata sandi akun Anda.</li>
                        <li>Anda bertanggung jawab atas semua aktivitas yang terjadi di bawah akun Anda.</li>
                        <li>Anda wajib segera memberitahu kami jika terjadi penggunaan akun tanpa izin.</li>
                        <li>Satu orang hanya diperbolehkan memiliki satu akun aktif.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">4. Pemesanan dan Janji Temu</h2>
                    <p>Untuk pemesanan janji temu dokter, berlaku ketentuan berikut:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Pemesanan dilakukan untuk keperluan medis yang sah.</li>
                        <li>Anda wajib hadir tepat waktu sesuai jadwal yang telah ditentukan.</li>
                        <li>Pembatalan janji temu harus dilakukan minimal 2 jam sebelum jadwal.</li>
                        <li>Rumah Sakit berhak membatalkan atau mengubah jadwal bila dokter berhalangan hadir.</li>
                        <li>Pemesanan yang tidak dikonfirmasi dalam batas waktu yang ditentukan dapat dibatalkan otomatis.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">5. Pembayaran dan Biaya</h2>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Tarif layanan mengikuti ketentuan yang berlaku di Rumah Sakit dan dapat berubah sewaktu-waktu.</li>
                        <li>Informasi asuransi atau penjamin yang diberikan harus akurat dan valid.</li>
                        <li>Rumah Sakit berhak menolak klaim penjamin yang tidak sesuai ketentuan.</li>
                        <li>Pembayaran tunai, kartu debit/kredit, dan transfer bank diterima sesuai kebijakan kasir.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">6. Tanggung Jawab Pengguna</h2>
                    <p>Pengguna dilarang keras untuk:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Memberikan informasi medis atau identitas yang palsu atau menyesatkan.</li>
                        <li>Menggunakan layanan untuk tujuan yang melanggar hukum.</li>
                        <li>Mengunggah konten yang mengandung virus, malware, atau kode berbahaya.</li>
                        <li>Melakukan tindakan yang mengganggu atau merusak operasional website dan sistem kami.</li>
                        <li>Menyalin, mendistribusikan, atau mengeksploitasi konten website secara komersial tanpa izin.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">7. Informasi Kesehatan</h2>
                    <p>Konten informasi kesehatan di website kami disediakan hanya untuk tujuan edukasi umum dan bukan merupakan pengganti saran, diagnosis, atau pengobatan medis profesional. Selalu konsultasikan kondisi kesehatan Anda dengan tenaga medis yang berkualifikasi.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">8. Batasan Tanggung Jawab</h2>
                    <p>Rumah Sakit tidak bertanggung jawab atas:</p>
                    <ul class="list-disc pl-6 space-y-2">
                        <li>Kerugian yang timbul akibat ketidakakuratan informasi yang diberikan pengguna.</li>
                        <li>Gangguan layanan akibat force majeure (bencana alam, pemadaman listrik, gangguan server).</li>
                        <li>Keputusan medis yang diambil berdasarkan informasi umum dari website kami.</li>
                        <li>Kerugian tidak langsung atau insidental yang timbul dari penggunaan layanan.</li>
                    </ul>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">9. Kekayaan Intelektual</h2>
                    <p>Seluruh konten di website ini, termasuk teks, gambar, logo, desain, dan kode program, adalah milik {{ $setting->nama_rumahsakit ?? 'RS Sari Sehat' }} atau pemberi lisensi kami. Dilarang mereproduksi atau menggunakan konten ini tanpa izin tertulis dari kami.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">10. Penangguhan dan Penutupan Akun</h2>
                    <p>Kami berhak menangguhkan atau menutup akun Anda tanpa pemberitahuan sebelumnya jika Anda melanggar Syarat & Ketentuan ini, memberikan informasi palsu, atau melakukan tindakan yang merugikan pengguna lain atau Rumah Sakit.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">11. Perubahan Syarat & Ketentuan</h2>
                    <p>Kami berhak mengubah Syarat & Ketentuan ini kapan saja. Perubahan akan berlaku segera setelah dipublikasikan di website. Penggunaan layanan setelah perubahan dianggap sebagai penerimaan terhadap syarat yang diperbarui.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">12. Hukum yang Berlaku</h2>
                    <p>Syarat & Ketentuan ini diatur dan ditafsirkan berdasarkan hukum Republik Indonesia. Setiap sengketa akan diselesaikan melalui musyawarah mufakat, dan apabila tidak tercapai kesepakatan, akan diselesaikan melalui Pengadilan Negeri yang berwenang.</p>

                    <h2 class="text-xl font-bold text-gray-900 mt-8 mb-3">13. Hubungi Kami</h2>
                    <p>Pertanyaan mengenai Syarat & Ketentuan ini dapat disampaikan melalui:</p>
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
            <a href="{{ route('kebijakan-privasi') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-colors">
                <i class="fas fa-shield-halved"></i> Kebijakan Privasi
            </a>
            <a href="{{ route('kontak') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold text-sm transition-colors">
                <i class="fas fa-envelope"></i> Hubungi Kami
            </a>
        </div>
    </div>
</section>

@endsection
