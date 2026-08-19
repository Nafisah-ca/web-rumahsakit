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

@push('styles')
<style>
#read-progress { position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#16a34a,#22d3ee);z-index:9999;width:0%;transition:width .1s linear;border-radius:0 2px 2px 0; }
#toc-sidebar { position:sticky;top:100px;max-height:calc(100vh - 130px);overflow-y:auto; }
#toc-sidebar a { display:block;font-size:12px;color:#64748b;padding:5px 12px;border-left:2px solid #e2e8f0;margin-bottom:2px;transition:all .2s;text-decoration:none;line-height:1.4; }
#toc-sidebar a:hover, #toc-sidebar a.active { color:#16a34a;border-left-color:#16a34a;background:#f0fdf4;border-radius:0 6px 6px 0; }
.prose h2 { transition:color .3s; }
.prose h2.in-view { color:#15803d !important; }
.fade-item { opacity:0;transform:translateY(16px);transition:opacity .5s ease,transform .5s ease; }
.fade-item.visible { opacity:1;transform:translateY(0); }
.prose li { transition:background .2s;border-radius:6px;padding:2px 4px;margin-left:-4px; }
.prose li:hover { background:#f0fdf4; }
#back-top { position:fixed;bottom:100px;left:24px;width:40px;height:40px;background:#15803d;color:#fff;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(21,128,61,.35);opacity:0;transform:translateY(10px);transition:all .3s;z-index:50; }
#back-top.show { opacity:1;transform:translateY(0); }
#back-top:hover { background:#166534;transform:translateY(-2px); }
@keyframes ripple { to { transform:scale(4);opacity:0; } }
@media(max-width:768px) { #toc-sidebar { display:none; } }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // Reading Progress Bar
    const bar = document.createElement('div');
    bar.id = 'read-progress';
    document.body.prepend(bar);
    window.addEventListener('scroll', () => {
        const total = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.width = (total > 0 ? (window.scrollY / total) * 100 : 0) + '%';
    }, { passive: true });

    // Table of Contents
    const prose    = document.querySelector('.prose');
    const headings = prose ? prose.querySelectorAll('h2') : [];
    if (headings.length > 3) {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;gap:32px;align-items:flex-start;max-width:900px;margin:0 auto';
        const contentWrap = document.createElement('div');
        contentWrap.style.flex = '1';
        prose.parentNode.insertBefore(wrap, prose);
        wrap.appendChild(contentWrap);
        contentWrap.appendChild(prose);

        const toc = document.createElement('aside');
        toc.id    = 'toc-sidebar';
        toc.style.cssText = 'width:200px;flex-shrink:0;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:14px;';
        toc.innerHTML = '<p style="font-weight:700;color:#0f172a;font-size:11px;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;padding-left:12px">Daftar Isi</p>';

        headings.forEach((h, i) => {
            if (!h.id) h.id = 'section-' + i;
            const a = document.createElement('a');
            a.href = '#' + h.id;
            a.textContent = h.textContent;
            a.addEventListener('click', e => { e.preventDefault(); h.scrollIntoView({ behavior:'smooth' }); });
            toc.appendChild(a);
        });
        wrap.appendChild(toc);

        const tocLinks  = toc.querySelectorAll('a');
        const headingObs = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                const link = toc.querySelector(`a[href="#${entry.target.id}"]`);
                if (entry.isIntersecting) {
                    tocLinks.forEach(l => l.classList.remove('active'));
                    if (link) link.classList.add('active');
                    entry.target.classList.add('in-view');
                } else {
                    entry.target.classList.remove('in-view');
                }
            });
        }, { rootMargin: '-20% 0px -70% 0px' });
        headings.forEach(h => headingObs.observe(h));
    }

    // Scroll reveal
    const fadeObs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('visible'); fadeObs.unobserve(entry.target); }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.prose h2, .prose p, .prose ul, .prose li').forEach((el, i) => {
        el.classList.add('fade-item');
        el.style.transitionDelay = Math.min(i * 30, 300) + 'ms';
        fadeObs.observe(el);
    });

    // Back to top
    const backBtn = document.createElement('button');
    backBtn.id    = 'back-top';
    backBtn.innerHTML = '<i class="fas fa-arrow-up text-sm"></i>';
    document.body.appendChild(backBtn);
    window.addEventListener('scroll', () => backBtn.classList.toggle('show', window.scrollY > 400), { passive: true });
    backBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // Contact card hover
    const contactBox = document.querySelector('.bg-green-50.border-green-200');
    if (contactBox) {
        contactBox.style.transition = 'transform .3s, box-shadow .3s';
        contactBox.addEventListener('mouseenter', () => {
            contactBox.style.transform = 'translateY(-3px)';
            contactBox.style.boxShadow = '0 8px 24px rgba(21,128,61,.15)';
        });
        contactBox.addEventListener('mouseleave', () => {
            contactBox.style.transform = '';
            contactBox.style.boxShadow = '';
        });
    }

    // Button ripple
    document.querySelectorAll('.mt-8 a').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.addEventListener('click', function(e) {
            const r = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            r.style.cssText = `position:absolute;width:${size}px;height:${size}px;background:rgba(255,255,255,.3);border-radius:50%;transform:scale(0);animation:ripple .5s linear;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px;pointer-events:none`;
            this.appendChild(r);
            setTimeout(() => r.remove(), 600);
        });
    });

});
</script>
@endpush
