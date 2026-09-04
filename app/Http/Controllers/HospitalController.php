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
        $kategoriList = KategoriLayanan::with(['layanansAktif.dokter'])
            ->aktif()->get();
        $banner = \App\Models\PageBanner::getForPage('pelayanan');

        return view('layanan', compact('kategoriList', 'banner') + [
            'aktifKategori' => null,
            'layanans'      => collect(),
            'aktifLayanan'  => null,
            'dokterTerkait' => null,
        ]);
    }

    public function layananByKategori(int $id)
    {
        $kategoriList  = KategoriLayanan::with(['layanansAktif.dokter'])->aktif()->get();
        $aktifKategori = KategoriLayanan::with(['layanansAktif.dokter'])->findOrFail($id);
        abort_if($aktifKategori->status !== 'aktif', 404);

        $layanans      = $aktifKategori->layanansAktif;
        $aktifLayanan  = $layanans->first();
        $dokterTerkait = $aktifLayanan?->resolveDokter();
        // Banner spesifik per kategori, fallback ke banner 'pelayanan'
        $banner = \App\Models\PageBanner::where('page_key', 'layanan-' . $id)
            ->where('status', 'aktif')->first()
            ?? \App\Models\PageBanner::getForPage('pelayanan');

        return view('layanan-kategori', compact(
            'kategoriList', 'aktifKategori', 'layanans', 'aktifLayanan', 'dokterTerkait', 'banner'
        ));
    }

    public function layananDetail(int $id, int $layanan)
    {
        $kategoriList  = KategoriLayanan::with(['layanansAktif.dokter'])->aktif()->get();
        $aktifKategori = KategoriLayanan::with(['layanansAktif.dokter'])->findOrFail($id);
        abort_if($aktifKategori->status !== 'aktif', 404);

        $layanans     = $aktifKategori->layanansAktif;
        $aktifLayanan = Layanan::aktif()
            ->where('kategori_layanan_id', $id)
            ->with('dokter')
            ->findOrFail($layanan);
        $dokterTerkait = $aktifLayanan->resolveDokter();
        // Banner spesifik per kategori, fallback ke banner 'pelayanan'
        $banner = \App\Models\PageBanner::where('page_key', 'layanan-' . $id)
            ->where('status', 'aktif')->first()
            ?? \App\Models\PageBanner::getForPage('pelayanan');

        return view('layanan-kategori', compact(
            'kategoriList', 'aktifKategori', 'layanans', 'aktifLayanan', 'dokterTerkait', 'banner'
        ));
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
        $promosAktif = Promo::aktif()->paginate(9, ['*'], 'page_a');
        $promosLewat = Promo::expired()->paginate(6, ['*'], 'page_l');
        $banner      = \App\Models\PageBanner::getForPage('promo');
        return view('promo', compact('promosAktif', 'promosLewat', 'banner'));
    }

    public function promoDetail(\App\Models\Promo $promo)
    {
        // Promo expired tetap bisa dibuka detailnya
        abort_if(!in_array($promo->status, ['aktif']), 404);
        $related = Promo::aktif()->where('id', '!=', $promo->id)->limit(3)->get();
        $banner  = \App\Models\PageBanner::getForPage('promo');
        return view('promo-detail', compact('promo', 'related', 'banner'));
    }

    public function event()
    {
        // Semua event mendatang dimuat sekaligus; pembatasan 6 awal dilakukan di frontend
        $eventsMendatang = Event::where('status','aktif')->where('tanggal_event','>=',today())->orderBy('tanggal_event','asc')->get();
        $eventsLewat     = Event::where('status','aktif')->where('tanggal_event','<',today())->orderBy('tanggal_event','desc')->get();
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

    public function artikel(Request $request)
    {
        $kategoriId = $request->get('kategori_id');
        $perPage    = 999; // Semua artikel di-load, pembatasan tampilan di frontend (6 dulu, sisanya hidden)

        $query = Artikel::published()->with(['kategori', 'penulis']);
        if ($kategoriId) {
            $query->where('kategori_artikel_id', $kategoriId);
        }
        $articles  = $query->orderByDesc('created_tm')->paginate($perPage);
        $kategoris = \App\Models\KategoriArtikel::withCount(['artikels' => fn($q) => $q->where('status','publish')])->aktif()->get();
        $banner    = \App\Models\PageBanner::getForPage('artikel');

        // AJAX → return JSON dengan HTML cards
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html'        => view('_partials.artikel-cards', compact('articles'))->render(),
                'hasMore'     => $articles->hasMorePages(),
                'currentPage' => $articles->currentPage(),
                'lastPage'    => $articles->lastPage(),
                'total'       => $articles->total(),
            ]);
        }

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
            $tanggal = $tanggalInput
                ? \Carbon\Carbon::parse($tanggalInput)->startOfDay()
                : now()->startOfDay();
        } catch (\Throwable) {
            $tanggal = now()->startOfDay();
        }
        $tanggalStr = $tanggal->toDateString();

        $hariMap  = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
        $namaHari = $hariMap[$tanggal->dayOfWeekIso] ?? 'Senin';

        // Ambil semua jadwal dokter yang aktif pada hari tersebut
        // beserta dokter, spesialisasi, dan janji temu pada tanggal itu
        $jadwals = \App\Models\JadwalDokter::with([
            'dokter.spesialisasi',
            'spesialisasi',
            'janjiTemus' => fn($q) => $q
                ->whereDate('tanggal_booking', $tanggalStr)
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->orderBy('nomor_antrian'),
        ])
        ->where('status', 'aktif')
        ->where('hari', $namaHari)
        ->get();

        // Satu dokter bisa punya beberapa jadwal di hari yang sama
        // Satukan per dokter_id + spesialis_id
        $dokterData = $jadwals->groupBy(fn($j) => $j->dokter_id . '-' . $j->spesialis_id)
            ->map(function ($kelompok) {
                $jadwal   = $kelompok->first();
                $dokter   = $jadwal->dokter;
                $sp       = $jadwal->spesialisasi;

                // Kumpulkan semua janji temu dari semua jadwal dokter ini hari ini
                $antrians = $kelompok->flatMap(fn($j) => $j->janjiTemus);

                $totalAntrian   = $antrians->whereIn('status', ['pending', 'approved'])->count();
                $nomorDipanggil = $antrians->where('status', 'completed')->max('nomor_antrian') ?? 0;
                $estimasiMenit  = $sp?->estimasi_menit ?? 15;

                return [
                    'dokter_id'       => $dokter?->id,
                    'nama_dokter'     => $dokter?->nama_dokter ?? '-',
                    'foto'            => $dokter?->foto,
                    'spesialis'       => $sp?->nama_spesialis ?? '-',
                    'jam_range'       => $kelompok->map(fn($j) => substr($j->jam_mulai,0,5).'–'.substr($j->jam_selesai,0,5))->unique()->join(', '),
                    'status'          => 'Buka',
                    'total_antrian'   => $totalAntrian,
                    'nomor_dipanggil' => $nomorDipanggil,
                    'estimasi'        => $totalAntrian > 0 ? "±{$estimasiMenit} mnt" : '-',
                    'warna'           => $sp?->warna ?? 'blue',
                ];
            })
            ->values()
            ->sortBy('nama_dokter');

        $banner     = \App\Models\PageBanner::getForPage('live-antrian');
        $tanggalObj = $tanggal;

        return view('live-antrian', compact(
            'dokterData', 'banner', 'interval', 'pesanTunggu', 'setting',
            'tanggalObj', 'tanggalStr'
        ));
    }

    /** JSON endpoint untuk auto-refresh live antrian */
    public function liveAntrianJson(Request $request)
    {
        $tanggalInput = $request->get('tanggal');
        try {
            $tanggal = $tanggalInput
                ? \Carbon\Carbon::parse($tanggalInput)->startOfDay()
                : now()->startOfDay();
        } catch (\Throwable) {
            $tanggal = now()->startOfDay();
        }
        $tanggalStr = $tanggal->toDateString();
        $hariMap    = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',7=>'Minggu'];
        $namaHari   = $hariMap[$tanggal->dayOfWeekIso] ?? 'Senin';

        $jadwals = \App\Models\JadwalDokter::with([
            'dokter.spesialisasi',
            'spesialisasi',
            'janjiTemus' => fn($q) => $q
                ->whereDate('tanggal_booking', $tanggalStr)
                ->whereIn('status', ['pending', 'approved', 'completed'])
                ->orderBy('nomor_antrian'),
        ])
        ->where('status', 'aktif')
        ->where('hari', $namaHari)
        ->get();

        $dokterData = $jadwals->groupBy(fn($j) => $j->dokter_id . '-' . $j->spesialis_id)
            ->map(function ($kelompok) {
                $jadwal  = $kelompok->first();
                $dokter  = $jadwal->dokter;
                $sp      = $jadwal->spesialisasi;
                $antrians = $kelompok->flatMap(fn($j) => $j->janjiTemus);

                return [
                    'dokter_id'       => $dokter?->id,
                    'nama_dokter'     => $dokter?->nama_dokter ?? '-',
                    'spesialis'       => $sp?->nama_spesialis ?? '-',
                    'jam_range'       => $kelompok->map(fn($j) => substr($j->jam_mulai,0,5).'–'.substr($j->jam_selesai,0,5))->unique()->join(', '),
                    'total_antrian'   => $antrians->whereIn('status', ['pending', 'approved'])->count(),
                    'nomor_dipanggil' => $antrians->where('status', 'completed')->max('nomor_antrian') ?? 0,
                ];
            })
            ->values()
            ->sortBy('nama_dokter')
            ->values();

        return response()->json([
            'tanggal'    => $tanggalStr,
            'updated_at' => now()->format('H:i:s'),
            'data'       => $dokterData,
        ]);
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
