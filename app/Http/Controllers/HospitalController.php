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
        $setting = \App\Models\WebsiteSetting::getSetting();
        return view('tentang', compact('setting'));
    }

    public function layanan()
    {
        $layananList = Layanan::aktif()->get();
        return view('layanan', compact('layananList'));
    }

    public function dokter()
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();

        // Batas: max 3 dokter per spesialis
        $dokterList = $spesialisasis
            ->pluck('id')
            ->flatMap(function ($spId) {
                return Dokter::with(['spesialisasi', 'jadwalAktif'])
                    ->where('status', 'aktif')
                    ->where('spesialis_id', $spId)
                    ->orderBy('nama_dokter')
                    ->limit(3)
                    ->get();
            })
            ->values();

        return view('dokter', compact('dokterList', 'spesialisasis') + [
            'activeSpesialisSlug' => null,
            'activeSpesialisNama' => null,
        ]);
    }

    public function dokterOnline()
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();

        // Batas: max 3 dokter per spesialis (semua dokter aktif dianggap "online")
        $dokterList = $spesialisasis
            ->pluck('id')
            ->flatMap(function ($spId) {
                return Dokter::with(['spesialisasi', 'jadwalAktif'])
                    ->where('status', 'aktif')
                    ->where('spesialis_id', $spId)
                    ->orderBy('nama_dokter')
                    ->limit(3)
                    ->get();
            })
            ->values();

        return view('dokter', compact('dokterList', 'spesialisasis') + ['online' => true]);
    }

    public function dokterBySpesialis(string $spSlug)
    {
        // DB baru tidak punya kolom slug, gunakan ID sebagai parameter
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        $sp = Spesialisasi::find($spSlug); // spSlug sekarang adalah ID

        abort_if(!$sp, 404);

        $dokterList = Dokter::with(['spesialisasi', 'jadwalAktif'])
            ->where('status', 'aktif')
            ->where('spesialis_id', $sp->id)
            ->orderBy('nama_dokter')
            ->limit(3)
            ->get();

        return view('dokter', compact('dokterList', 'spesialisasis') + [
            'activeSpesialisSlug' => $sp->id,
            'activeSpesialisNama' => $sp->nama_spesialis,
        ]);
    }



    public function fasilitas()
    {
        return view('fasilitas');
    }

    public function kontak()
    {
        $setting      = \App\Models\WebsiteSetting::getSetting();
        $ulasanPublic = Ulasan::approved()->orderByDesc('created_tm')->limit(6)->get();
        return view('kontak', compact('setting', 'ulasanPublic'));
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
        $ratingCounts = Ulasan::approved()
            ->selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');
        $avgRating    = Ulasan::approved()->avg('rating');
        $totalUlasan  = Ulasan::approved()->count();
        return view('ulasan', compact('ulasans', 'ratingCounts', 'avgRating', 'totalUlasan', 'rating'));
    }

    public function promo()
    {
        $promos = Promo::aktif()->paginate(10);
        return view('promo', compact('promos'));
    }

    public function promoDetail(\App\Models\Promo $promo)
    {
        abort_if($promo->status !== 'aktif', 404);
        $related = Promo::aktif()
            ->where('id', '!=', $promo->id)
            ->limit(3)->get();
        return view('promo-detail', compact('promo', 'related'));
    }

    public function event()
    {
        // Event mendatang (aktif, tanggal >= hari ini) — urut terdekat dulu
        $eventsMendatang = Event::where('status', 'aktif')
            ->where('tanggal_event', '>=', today())
            ->orderBy('tanggal_event', 'asc')
            ->paginate(9, ['*'], 'page_m');

        // Event sudah lewat (aktif, tanggal < hari ini) — urut terbaru dulu
        $eventsLewat = Event::where('status', 'aktif')
            ->where('tanggal_event', '<', today())
            ->orderBy('tanggal_event', 'desc')
            ->paginate(6, ['*'], 'page_l');

        // Banner event: ambil event mendatang yg punya gambar, maks 5
        $bannerEvents = Event::where('status', 'aktif')
            ->where('tanggal_event', '>=', today())
            ->whereNotNull('gambar')
            ->orderBy('tanggal_event', 'asc')
            ->limit(5)
            ->get();

        return view('event', compact('eventsMendatang', 'eventsLewat', 'bannerEvents'));
    }

    public function eventDetail(\App\Models\Event $event)
    {
        abort_if($event->status !== 'aktif', 404);
        $related = Event::published()
            ->where('id', '!=', $event->id)
            ->limit(3)->get();
        return view('event-detail', compact('event', 'related'));
    }

    public function informasiPublic()
    {
        $informasis = \App\Models\Informasi::published()->paginate(12);
        return view('informasi', compact('informasis'));
    }

    public function informasiDetail(\App\Models\Informasi $informasi)
    {
        abort_if($informasi->status !== 'publish', 404);
        $related = \App\Models\Informasi::published()
            ->where('id', '!=', $informasi->id)
            ->limit(3)->get();
        return view('informasi-detail', compact('informasi', 'related'));
    }

    public function artikel()
    {
        $articles  = Artikel::published()->with(['kategori', 'penulis'])->paginate(9);
        $kategoris = \App\Models\KategoriArtikel::withCount(['artikels' => fn($q) => $q->where('status', 'publish')])->aktif()->get();
        return view('artikel', compact('articles', 'kategoris'));
    }

    public function artikelDetail(string $slug)
    {
        $artikel = Artikel::where('slug', $slug)->where('status', 'publish')->firstOrFail();
        
        $related = Artikel::where('status', 'publish')
                        ->where('kategori_artikel_id', $artikel->kategori_artikel_id)
                        ->where('id', '!=', $artikel->id)
                        ->limit(3)->get();
                        
        return view('artikel-detail', compact('artikel', 'related'));
    }

    public function mcu()
    {
        return view('mcu');
    }

    public function liveAntrian()
    {
        // Ambil antrian hari ini yang statusnya approved atau pending
        $antrians = JanjiTemu::with(['jadwalDokter.dokter.spesialisasi', 'pasien.user'])
            ->whereDate('tanggal_booking', today())
            ->whereIn('status', ['pending', 'approved'])
            ->orderBy('nomor_antrian')
            ->get();
            
        return view('live-antrian', compact('antrians'));
    }

    public function kebijakanPrivasi()
    {
        $setting = \App\Models\WebsiteSetting::getSetting();
        return view('kebijakan-privasi', compact('setting'));
    }

    public function syaratKetentuan()
    {
        $setting = \App\Models\WebsiteSetting::getSetting();
        return view('syarat-ketentuan', compact('setting'));
    }
}
