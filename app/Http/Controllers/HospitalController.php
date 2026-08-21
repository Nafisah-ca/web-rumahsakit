<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Spesialisasi;
use App\Models\Promo;
use App\Models\Event;
use App\Models\Artikel;
use App\Models\Layanan;
use App\Models\Dokter;
use App\Models\JanjiTemu;
use App\Models\GuestBook;
use App\Models\Ulasan;
use App\Models\KategoriLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HospitalController extends Controller
{
    public function home()
    {
        $banners      = Banner::aktif()->get();
        $spesialisasi = Spesialisasi::orderBy('nama_spesialis')->get();
        $promos       = Promo::aktif()->limit(6)->get();
        $events       = Event::mendatang()->limit(6)->get();
        $articles     = Artikel::terbaru(6)->with('kategori')->get();
        $informasis   = \App\Models\Informasi::terbaru(6)->get();
        $setting_global = \App\Models\WebsiteSetting::getSetting();

        $totalInformasi = \App\Models\Informasi::published()->count();
        $totalArtikel   = Artikel::published()->count();
        $totalBanner    = Banner::aktif()->count();
        $ulasanHome     = Ulasan::approved()->orderByDesc('created_tm')->limit(6)->get();

        return view('home', compact(
            'banners', 'spesialisasi', 'promos', 'events',
            'articles', 'informasis', 'setting_global',
            'totalInformasi', 'totalArtikel', 'totalBanner',
            'ulasanHome'
        ) + ['setting' => $setting_global]);
    }

    public function tentang()
    {
        $setting    = \App\Models\WebsiteSetting::getSetting();
        $banner     = \App\Models\PageBanner::getForPage('tentang');
        $penghargaan = \App\Models\Akreditasi::aktif()->get();
        return view('tentang', compact('setting', 'banner', 'penghargaan'));
    }

    public function layanan()
    {
        $kategoriList = \App\Models\KategoriLayanan::with(['layanansAktif'])
            ->aktif()->get()
            ->filter(fn($k) => $k->layanansAktif->isNotEmpty());
        $layananTanpaKategori = Layanan::aktif()->whereNull('kategori_layanan_id')->get();
        $totalLayanan = Layanan::aktif()->count();
        $banner = \App\Models\PageBanner::getForPage('pelayanan');

        // Dokter spesialis untuk panel "50+ Dokter Spesialis"
        $dokterSpesialis = Dokter::with(['spesialisasi', 'jadwalAktif'])
            ->where('status', 'aktif')
            ->where('tipe_dokter', 'spesialis')
            ->orderBy('nama_dokter')
            ->get();

        return view('layanan', compact('kategoriList', 'layananTanpaKategori', 'totalLayanan', 'banner', 'dokterSpesialis'));
    }

    public function layananByKategori(int $id)
    {
        $kategoriList  = KategoriLayanan::aktif()->get();
        $aktifKategori = KategoriLayanan::findOrFail($id);
        abort_if($aktifKategori->status !== 'aktif', 404);
        $layanans = Layanan::aktif()->where('kategori_layanan_id', $id)->get();
        $banner   = \App\Models\PageBanner::getForPage('layanan-kategori');
        return view('layanan-kategori', compact('kategoriList', 'aktifKategori', 'layanans', 'banner'));
    }

    public function dokter()
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        // Profil Dokter: tampilkan SEMUA dokter aktif (spesialis + umum)
        $dokterList = Dokter::with(['spesialisasi', 'jadwalAktif'])
            ->where('status', 'aktif')
            ->orderByRaw("FIELD(tipe_dokter,'spesialis','umum','lainnya')")
            ->orderBy('nama_dokter')
            ->get();
        $banner = \App\Models\PageBanner::getForPage('dokter');
        return view('dokter', compact('dokterList', 'spesialisasis', 'banner') + [
            'activeSpesialisSlug' => null,
            'activeSpesialisNama' => null,
            'modeDaftar'          => false,
        ]);
    }

    public function dokterOnline()
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        $dokterList = Dokter::with(['spesialisasi', 'jadwalAktif'])
            ->where('status', 'aktif')
            ->orderBy('nama_dokter')
            ->get();
        $banner = \App\Models\PageBanner::getForPage('dokter');
        return view('dokter', compact('dokterList', 'spesialisasis', 'banner') + [
            'online'              => true,
            'activeSpesialisSlug' => null,
            'activeSpesialisNama' => null,
            'modeDaftar'          => false,
        ]);
    }

    public function dokterBySpesialis(string $spSlug)
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        $sp = Spesialisasi::find($spSlug);
        abort_if(!$sp, 404);
        // Daftar Poliklinik: HANYA dokter spesialis sesuai spesialisasi
        $dokterList = Dokter::with(['spesialisasi', 'jadwalAktif'])
            ->where('status', 'aktif')
            ->where('tipe_dokter', 'spesialis')
            ->where('spesialis_id', $sp->id)
            ->orderBy('nama_dokter')
            ->get();
        $banner = \App\Models\PageBanner::getForPage('dokter');
        return view('dokter', compact('dokterList', 'spesialisasis', 'banner') + [
            'activeSpesialisSlug' => $sp->id,
            'activeSpesialisNama' => $sp->nama_spesialis,
            'modeDaftar'          => true,
        ]);
    }

    public function fasilitas()
    {
        $banner = \App\Models\PageBanner::getForPage('fasilitas');
        return view('fasilitas', compact('banner'));
    }

    public function kontak()
    {
        $setting      = \App\Models\WebsiteSetting::getSetting();
        $ulasanPublic = Ulasan::approved()->orderByDesc('created_tm')->limit(6)->get();
        $banner       = \App\Models\PageBanner::getForPage('kontak');
        return view('kontak', compact('setting', 'ulasanPublic', 'banner'));
    }

    public function storeKontak(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'required|email',
            'pesan' => 'required|string|max:2000',
        ], [
            'nama.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'pesan.required' => 'Pesan wajib diisi.',
        ]);

        GuestBook::create([
            'nama'   => $request->nama,
            'email'  => $request->email,
            'no_hp'  => $request->telepon,
            'pesan'  => $request->pesan,
            'status' => 'baru',
        ]);

        return back()->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda dalam 1×24 jam.');
    }

    public function storeUlasan(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:150',
            'email'  => 'nullable|email|max:150',
            'rating' => 'required|integer|min:1|max:5',
            'judul'  => 'nullable|string|max:200',
            'isi'    => 'required|string|max:1000',
        ], [
            'nama.required'   => 'Nama wajib diisi.',
            'rating.required' => 'Rating wajib dipilih.',
            'rating.min'      => 'Rating minimal 1 bintang.',
            'rating.max'      => 'Rating maksimal 5 bintang.',
            'isi.required'    => 'Ulasan wajib diisi.',
        ]);

        Ulasan::create([
            'nama'   => $request->nama,
            'email'  => $request->email,
            'rating' => $request->rating,
            'judul'  => $request->judul,
            'isi'    => $request->isi,
            'status' => 'pending',
        ]);

        // Cek dari mana form disubmit — kontak atau halaman ulasan
        $referer = request()->headers->get('referer', '');
        $target  = str_contains($referer, '/ulasan')
            ? route('ulasan.public') . '#form-ulasan'
            : route('kontak') . '#ulasan-form';

        return redirect($target)
            ->with('success_ulasan', 'Terima kasih! Ulasan Anda sedang ditinjau dan akan segera ditampilkan.');
    }

    public function ulasanPublic(Request $request)
    {
        $rating   = $request->get('rating');
        $query    = Ulasan::approved()->orderByDesc('created_tm');
        if ($rating && in_array($rating, [1,2,3,4,5])) {
            $query->where('rating', $rating);
        }
        $ulasans      = $query->paginate(12)->withQueryString();
        $ratingCounts = Ulasan::approved()->selectRaw('rating, count(*) as total')->groupBy('rating')->pluck('total', 'rating');
        $avgRating    = Ulasan::approved()->avg('rating');
        $totalUlasan  = Ulasan::approved()->count();
        $banner       = \App\Models\PageBanner::getForPage('ulasan');
        return view('ulasan', compact('ulasans', 'ratingCounts', 'avgRating', 'totalUlasan', 'rating', 'banner'));
    }

    public function promo()
    {
        $promos = Promo::aktif()->paginate(10);
        $banner = \App\Models\PageBanner::getForPage('promo');
        return view('promo', compact('promos', 'banner'));
    }

    public function promoDetail(\App\Models\Promo $promo)
    {
        abort_if($promo->status !== 'aktif', 404);
        $related = Promo::aktif()->where('id', '!=', $promo->id)->limit(3)->get();
        $banner  = \App\Models\PageBanner::getForPage('promo');
        return view('promo-detail', compact('promo', 'related', 'banner'));
    }

    public function event()
    {
        $eventsMendatang = Event::where('status','aktif')->where('tanggal_event','>=',today())->orderBy('tanggal_event','asc')->paginate(9,['*'],'page_m');
        $eventsLewat     = Event::where('status','aktif')->where('tanggal_event','<',today())->orderBy('tanggal_event','desc')->paginate(6,['*'],'page_l');
        $bannerEvents    = Event::where('status','aktif')->where('tanggal_event','>=',today())->whereNotNull('gambar')->orderBy('tanggal_event','asc')->limit(5)->get();
        $banner          = \App\Models\PageBanner::getForPage('event');
        return view('event', compact('eventsMendatang', 'eventsLewat', 'bannerEvents', 'banner'));
    }

    public function eventDetail(\App\Models\Event $event)
    {
        abort_if($event->status !== 'aktif', 404);
        $related = Event::published()->where('id','!=',$event->id)->limit(3)->get();
        $banner  = \App\Models\PageBanner::getForPage('event');
        return view('event-detail', compact('event', 'related', 'banner'));
    }

    public function informasiPublic()
    {
        $informasis = \App\Models\Informasi::published()->paginate(12);
        $banner     = \App\Models\PageBanner::getForPage('informasi');
        return view('informasi', compact('informasis', 'banner'));
    }

    public function informasiDetail(\App\Models\Informasi $informasi)
    {
        abort_if($informasi->status !== 'publish', 404);
        $related = \App\Models\Informasi::published()->where('id','!=',$informasi->id)->limit(3)->get();
        $banner  = \App\Models\PageBanner::getForPage('informasi');
        return view('informasi-detail', compact('informasi', 'related', 'banner'));
    }

    public function artikel()
    {
        $articles  = Artikel::published()->with(['kategori', 'penulis'])->paginate(9);
        $kategoris = \App\Models\KategoriArtikel::withCount(['artikels' => fn($q) => $q->where('status','publish')])->aktif()->get();
        $banner    = \App\Models\PageBanner::getForPage('artikel');
        return view('artikel', compact('articles', 'kategoris', 'banner'));
    }

    public function artikelDetail(string $slug)
    {
        $artikel = Artikel::where('slug', $slug)
                        ->where('status', 'publish')
                        ->with([
                            'kategori.spesialisasi',
                            'dokter.spesialisasi',
                            'dokter.jadwalAktif',
                        ])
                        ->firstOrFail();

        // Cari dokter: prioritas 1 = dokter_id di artikel
        // Prioritas 2 = dokter spesialis dari kategori artikel
        $dokterJanji = $artikel->dokter;

        if (!$dokterJanji && $artikel->kategori?->spesialis_id) {
            $dokterJanji = \App\Models\Dokter::where('status', 'aktif')
                ->where('tipe_dokter', 'spesialis')
                ->where('spesialis_id', $artikel->kategori->spesialis_id)
                ->with(['spesialisasi', 'jadwalAktif'])
                ->first();
        }

        $related = Artikel::where('status', 'publish')
                        ->where('kategori_artikel_id', $artikel->kategori_artikel_id)
                        ->where('id', '!=', $artikel->id)
                        ->limit(3)->get();

        $banner = \App\Models\PageBanner::getForPage('artikel');

        return view('artikel-detail', compact('artikel', 'related', 'banner', 'dokterJanji'));
    }

    public function mcu()
    {
        $banner = \App\Models\PageBanner::getForPage('mcu');
        return view('mcu', compact('banner'));
    }

    public function liveAntrian(Request $request)
    {
        $setting     = \App\Models\WebsiteSetting::getSetting();
        $estimasi    = json_decode($setting->estimasi_antrian ?? '{}', true) ?? [];
        $interval    = (int) ($estimasi['interval_refresh'] ?? 30);
        $pesanTunggu = $estimasi['pesan_tunggu'] ?? 'Harap menunggu, nomor Anda akan segera dipanggil.';

        // Tanggal yang dipilih (default hari ini)
        $tanggalInput = $request->get('tanggal');
        try {
            $tanggal = $tanggalInput ? \Carbon\Carbon::parse($tanggalInput)->startOfDay() : now()->startOfDay();
        } catch (\Throwable) {
            $tanggal = now()->startOfDay();
        }
        $tanggalStr = $tanggal->toDateString();

        // Nama hari sesuai enum DB
        $hariMap  = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
        $namaHari = $hariMap[$tanggal->dayOfWeekIso] ?? 'Senin';

        // Semua spesialisasi dengan jadwal aktif pada hari tersebut
        $spesialisasis = Spesialisasi::with([
            'jadwalDokters' => fn($q) => $q->where('status', 'aktif')->where('hari', $namaHari),
        ])->orderBy('nama_spesialis')->get();

        // Semua janji temu pada tanggal yang dipilih
        $antriansHariIni = JanjiTemu::with(['jadwalDokter'])
            ->whereDate('tanggal_booking', $tanggalStr)
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->orderBy('nomor_antrian')
            ->get();

        // Kelompokkan per spesialis_id
        $antrianPerPoli = $antriansHariIni->groupBy(
            fn($jt) => $jt->jadwalDokter?->spesialis_id
        );

        // Bangun data poli — tampilkan SEMUA poli, bukan hanya yang buka
        $poliData = $spesialisasis->map(function ($sp) use ($antrianPerPoli) {
            $antrians       = $antrianPerPoli->get($sp->id, collect());
            $totalAntrian   = $antrians->whereIn('status', ['pending', 'approved'])->count();
            $nomorDipanggil = $antrians->where('status', 'completed')->max('nomor_antrian') ?? 0;
            $estimasiMenit  = $sp->estimasi_menit ?? 15;
            $estimasiTunggu = $totalAntrian > 0 ? "±{$estimasiMenit} menit" : '-';
            $buka           = $sp->jadwalDokters->isNotEmpty();

            return [
                'id'              => $sp->id,
                'nama'            => 'Poli ' . $sp->nama_spesialis,
                'icon'            => $sp->icon ?? 'fa-stethoscope',
                'warna'           => $sp->warna ?? 'blue',
                'status'          => $buka ? 'Buka' : 'Tutup',
                'total_antrian'   => $totalAntrian,
                'nomor_dipanggil' => $nomorDipanggil,
                'estimasi'        => $buka ? $estimasiTunggu : '-',
            ];
        });

        $banner     = \App\Models\PageBanner::getForPage('live-antrian');
        $tanggalObj = $tanggal;

        return view('live-antrian', compact(
            'poliData', 'banner', 'interval', 'pesanTunggu', 'setting',
            'tanggalObj', 'tanggalStr'
        ));
    }

    public function kebijakanPrivasi()
    {
        $setting = \App\Models\WebsiteSetting::getSetting();
        $banner  = \App\Models\PageBanner::getForPage('kebijakan-privasi');
        return view('kebijakan-privasi', compact('setting', 'banner'));
    }

    public function syaratKetentuan()
    {
        $setting = \App\Models\WebsiteSetting::getSetting();
        $banner  = \App\Models\PageBanner::getForPage('syarat-ketentuan');
        return view('syarat-ketentuan', compact('setting', 'banner'));
    }

    public function faq()
    {
        $faqs   = \App\Models\Faq::aktif()->get();
        $banner = \App\Models\PageBanner::getForPage('faq');
        return view('faq', compact('faqs', 'banner'));
    }
}
