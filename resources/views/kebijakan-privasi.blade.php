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

@push('styles')
<style>
/* Reading progress bar */
#read-progress { position:fixed;top:0;left:0;height:3px;background:linear-gradient(90deg,#16a34a,#22d3ee);z-index:9999;width:0%;transition:width .1s linear;border-radius:0 2px 2px 0; }

/* TOC */
#toc-sidebar { position:sticky;top:100px;max-height:calc(100vh - 130px);overflow-y:auto; }
#toc-sidebar a { display:block;font-size:12px;color:#64748b;padding:5px 12px;border-left:2px solid #e2e8f0;margin-bottom:2px;transition:all .2s;text-decoration:none;line-height:1.4; }
#toc-sidebar a:hover, #toc-sidebar a.active { color:#16a34a;border-left-color:#16a34a;background:#f0fdf4;border-radius:0 6px 6px 0; }

/* Section headings animate */
.prose h2 { transition:color .3s; }
.prose h2.in-view { color:#15803d !important; }

/* Fade in items */
.fade-item { opacity:0;transform:translateY(16px);transition:opacity .5s ease,transform .5s ease; }
.fade-item.visible { opacity:1;transform:translateY(0); }

/* Smooth highlight on hover */
.prose li { transition:background .2s;border-radius:6px;padding:2px 4px;margin-left:-4px; }
.prose li:hover { background:#f0fdf4; }

/* Back to top button */
#back-top { position:fixed;bottom:100px;left:24px;width:40px;height:40px;background:#15803d;color:#fff;border:none;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 12px rgba(21,128,61,.35);opacity:0;transform:translateY(10px);transition:all .3s;z-index:50; }
#back-top.show { opacity:1;transform:translateY(0); }
#back-top:hover { background:#166534;transform:translateY(-2px); }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // ── 1. READING PROGRESS BAR ──────────────────────────────────────
    const bar = document.createElement('div');
    bar.id = 'read-progress';
    document.body.prepend(bar);
    window.addEventListener('scroll', () => {
        const total   = document.documentElement.scrollHeight - window.innerHeight;
        const current = window.scrollY;
        bar.style.width = (total > 0 ? (current / total) * 100 : 0) + '%';
    }, { passive: true });

    // ── 2. TABLE OF CONTENTS ─────────────────────────────────────────
    const prose     = document.querySelector('.prose');
    const headings  = prose ? prose.querySelectorAll('h2') : [];
    if (headings.length > 3) {
        const wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;gap:32px;align-items:flex-start;max-width:900px;margin:0 auto';

        // Bungkus konten existing
        const contentWrap = document.createElement('div');
        contentWrap.style.flex = '1';
        prose.parentNode.insertBefore(wrap, prose);
        wrap.appendChild(contentWrap);
        contentWrap.appendChild(prose);

        // TOC sidebar
        const toc = document.createElement('aside');
        toc.id    = 'toc-sidebar';
        toc.style.cssText = 'width:200px;flex-shrink:0;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:14px;font-size:12px;';
        toc.innerHTML = '<p style="font-weight:700;color:#0f172a;font-size:11px;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;padding-left:12px">Daftar Isi</p>';

        headings.forEach((h, i) => {
            if (!h.id) h.id = 'section-' + i;
            const a = document.createElement('a');
            a.href        = '#' + h.id;
            a.textContent = h.textContent;
            a.addEventListener('click', e => {
                e.preventDefault();
                h.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
            toc.appendChild(a);
        });
        wrap.appendChild(toc);

        // Highlight active TOC item on scroll
        const tocLinks = toc.querySelectorAll('a');
        const headingObs = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                const id = entry.target.id;
                const link = toc.querySelector(`a[href="#${id}"]`);
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

    // ── 3. SCROLL REVEAL FADE ────────────────────────────────────────
    const fadeObs = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                fadeObs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.prose h2, .prose p, .prose ul, .prose li').forEach((el, i) => {
        el.classList.add('fade-item');
        el.style.transitionDelay = Math.min(i * 30, 300) + 'ms';
        fadeObs.observe(el);
    });

    // ── 4. BACK TO TOP BUTTON ────────────────────────────────────────
    const backBtn = document.createElement('button');
    backBtn.id    = 'back-top';
    backBtn.innerHTML = '<i class="fas fa-arrow-up text-sm"></i>';
    backBtn.title = 'Kembali ke atas';
    document.body.appendChild(backBtn);
    window.addEventListener('scroll', () => {
        backBtn.classList.toggle('show', window.scrollY > 400);
    }, { passive: true });
    backBtn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

    // ── 5. CONTACT CARD HIGHLIGHT ────────────────────────────────────
    const contactBox = document.querySelector('.bg-green-50.border-green-200');
    if (contactBox) {
        contactBox.style.transition = 'transform .3s, box-shadow .3s';
        contactBox.addEventListener('mouseenter', () => {
            contactBox.style.transform  = 'translateY(-3px)';
            contactBox.style.boxShadow  = '0 8px 24px rgba(21,128,61,.15)';
        });
        contactBox.addEventListener('mouseleave', () => {
            contactBox.style.transform  = '';
            contactBox.style.boxShadow  = '';
        });
    }

    // ── 6. BOTTOM BUTTONS RIPPLE ─────────────────────────────────────
    document.querySelectorAll('.mt-8 a').forEach(btn => {
        btn.style.position = 'relative';
        btn.style.overflow = 'hidden';
        btn.addEventListener('click', function(e) {
            const r    = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            r.style.cssText = `position:absolute;width:${size}px;height:${size}px;background:rgba(255,255,255,.3);border-radius:50%;transform:scale(0);animation:ripple .5s linear;left:${e.clientX-rect.left-size/2}px;top:${e.clientY-rect.top-size/2}px;pointer-events:none`;
            this.appendChild(r);
            setTimeout(() => r.remove(), 600);
        });
    });

});
</script>
<style>
@keyframes ripple { to { transform: scale(4); opacity: 0; } }
@media(max-width:768px) { #toc-sidebar { display:none; } }
</style>
@endpush
