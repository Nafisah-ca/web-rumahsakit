<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\BookingEvent;
use App\Models\KategoriArtikel;
use App\Models\Promo;
use App\Models\Event;
use App\Models\Banner;
use App\Models\Layanan;
use App\Models\Informasi;
use App\Models\GuestBook;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CmsController extends Controller
{
    // ─────────────────────────── DASHBOARD ───────────────────────────

    public function dashboard()
    {
        $stats = [
            'total_artikel'    => Artikel::count(),
            'artikel_publish'  => Artikel::where('status', 'publish')->count(),
            'total_promo'      => Promo::count(),
            'promo_aktif'      => Promo::where('status', 'aktif')->count(),
            'total_event'      => Event::count(),
            'event_mendatang'  => Event::where('status', 'aktif')->where('tanggal_event', '>=', now())->count(),
            'total_banner'     => Banner::count(),
            'banner_aktif'     => Banner::where('status', 'aktif')->count(),
            'total_informasi'  => Informasi::count(),
            'pesan_baru'       => GuestBook::where('status', 'baru')->count(),
        ];
        $recentArtikel = Artikel::with('kategori')->orderByDesc('created_tm')->limit(5)->get();
        $pesanBaru     = GuestBook::where('status', 'baru')->orderByDesc('created_tm')->limit(5)->get();
        return view('cms.dashboard', compact('stats', 'recentArtikel', 'pesanBaru'));
    }

    // ─────────────────────────── ARTIKEL ─────────────────────────────

    public function artikel(Request $request)
    {
        $query = Artikel::with('kategori');
        if ($request->search) $query->where('judul', 'like', "%{$request->search}%");
        if ($request->status) $query->where('status', $request->status);
        if ($request->kategori_id) $query->where('kategori_artikel_id', $request->kategori_id);
        $artikels  = $query->orderByDesc('created_tm')->paginate(15)->withQueryString();
        $kategoris = KategoriArtikel::aktif()->orderBy('nama_kategori')->get();
        return view('cms.artikel.index', compact('artikels', 'kategoris'));
    }

    public function createArtikel()
    {
        $kategoris = KategoriArtikel::aktif()->orderBy('nama_kategori')->get();
        return view('cms.artikel.create', compact('kategoris'));
    }

    public function storeArtikel(Request $request)
    {
        $request->validate([
            'judul'               => 'required|string|max:200',
            'kategori_artikel_id' => 'required|exists:kategori_artikel,id',
            'isi'                 => 'required|string',
            'status'              => 'required|in:draft,publish',
            'gambar'              => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', 'gambar', 'thumbnail']);
        $data['slug']       = Str::slug($request->judul) . '-' . Str::random(4);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('artikel', 'public');
        }

        Artikel::create($data);
        return redirect()->route('cms.artikel')->with('success', 'Artikel berhasil disimpan.');
    }

    public function editArtikel(Artikel $artikel)
    {
        $kategoris = KategoriArtikel::aktif()->orderBy('nama_kategori')->get();
        return view('cms.artikel.edit', compact('artikel', 'kategoris'));
    }

    public function updateArtikel(Request $request, Artikel $artikel)
    {
        $request->validate([
            'judul'               => 'required|string|max:200',
            'kategori_artikel_id' => 'required|exists:kategori_artikel,id',
            'isi'                 => 'required|string',
            'status'              => 'required|in:draft,publish',
            'gambar'              => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', '_method', 'gambar', 'thumbnail']);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            if ($artikel->gambar) Storage::disk('public')->delete($artikel->gambar);
            $data['gambar'] = $request->file('gambar')->store('artikel', 'public');
        }

        $artikel->update($data);
        return redirect()->route('cms.artikel')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroyArtikel(Artikel $artikel)
    {
        if ($artikel->gambar) Storage::disk('public')->delete($artikel->gambar);
        $artikel->update(['deleted_by' => Auth::id()]);
        $artikel->delete();
        return back()->with('success', 'Artikel berhasil dihapus.');
    }

    // ─────────────────────────── PROMO ───────────────────────────────

    public function promo(Request $request)
    {
        $promos = Promo::when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('tanggal_mulai')->paginate(15)->withQueryString();
        return view('cms.promo.index', compact('promos'));
    }

    public function createPromo() { return view('cms.promo.create'); }

    public function storePromo(Request $request)
    {
        $request->validate([
            'judul'           => 'required|string|max:200',
            'deskripsi'       => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|in:aktif,nonaktif',
            'gambar'          => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', 'gambar', 'thumbnail']);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('promo', 'public');
        }

        Promo::create($data);
        return redirect()->route('cms.promo')->with('success', 'Promo berhasil disimpan.');
    }

    public function editPromo(Promo $promo) { return view('cms.promo.edit', compact('promo')); }

    public function updatePromo(Request $request, Promo $promo)
    {
        $request->validate([
            'judul'           => 'required|string|max:200',
            'deskripsi'       => 'required|string',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status'          => 'required|in:aktif,nonaktif',
            'gambar'          => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', '_method', 'gambar', 'thumbnail']);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            if ($promo->gambar) Storage::disk('public')->delete($promo->gambar);
            $data['gambar'] = $request->file('gambar')->store('promo', 'public');
        }

        $promo->update($data);
        return redirect()->route('cms.promo')->with('success', 'Promo berhasil diperbarui.');
    }

    public function destroyPromo(Promo $promo)
    {
        if ($promo->gambar) Storage::disk('public')->delete($promo->gambar);
        $promo->update(['deleted_by' => Auth::id()]);
        $promo->delete();
        return back()->with('success', 'Promo berhasil dihapus.');
    }

    // ─────────────────────────── EVENT ───────────────────────────────

    public function event(Request $request)
    {
        $events = Event::when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('tanggal_event')->paginate(15)->withQueryString();
        return view('cms.event.index', compact('events'));
    }

    public function createEvent() { return view('cms.event.create'); }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'judul'         => 'required|string|max:200',
            'deskripsi'     => 'required|string',
            'tanggal_event' => 'required|date',
            'waktu_event'   => 'required',
            'lokasi'        => 'nullable|string|max:255',
            'kuota'         => 'nullable|integer|min:1',
            'status'        => 'required|in:aktif,nonaktif',
            'gambar'        => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', 'gambar', 'thumbnail']);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('event', 'public');
        }

        Event::create($data);
        return redirect()->route('cms.event')->with('success', 'Event berhasil disimpan.');
    }

    public function editEvent(Event $event) { return view('cms.event.edit', compact('event')); }

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'judul'         => 'required|string|max:200',
            'deskripsi'     => 'required|string',
            'tanggal_event' => 'required|date',
            'waktu_event'   => 'required',
            'lokasi'        => 'nullable|string|max:255',
            'kuota'         => 'nullable|integer|min:1',
            'status'        => 'required|in:aktif,nonaktif',
            'gambar'        => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', '_method', 'gambar', 'thumbnail']);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            if ($event->gambar) Storage::disk('public')->delete($event->gambar);
            $data['gambar'] = $request->file('gambar')->store('event', 'public');
        }

        $event->update($data);
        return redirect()->route('cms.event')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroyEvent(Event $event)
    {
        if ($event->gambar) Storage::disk('public')->delete($event->gambar);
        $event->update(['deleted_by' => Auth::id()]);
        $event->delete();
        return back()->with('success', 'Event berhasil dihapus.');
    }

    public function pesertaEvent(Request $request, Event $event)
    {
        $query = BookingEvent::with(['pasien.user'])
            ->where('event_id', $event->id);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_booking', 'like', "%{$search}%")
                  ->orWhereHas('pasien.user', function ($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        $peserta   = $query->orderByDesc('created_tm')->paginate(20)->withQueryString();
        $total     = BookingEvent::where('event_id', $event->id)->count();
        $confirmed = BookingEvent::where('event_id', $event->id)->where('status', 'confirmed')->count();
        $pending   = BookingEvent::where('event_id', $event->id)->where('status', 'pending')->count();

        return view('cms.event.peserta', compact('event', 'peserta', 'total', 'confirmed', 'pending'));
    }

    public function updateStatusPeserta(Request $request, Event $event, BookingEvent $bookingEvent)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,cancelled']);
        abort_unless($bookingEvent->event_id === $event->id, 404);

        $bookingEvent->update([
            'status'     => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Status peserta berhasil diperbarui.');
    }

    // ─────────────────────────── BANNER ──────────────────────────────

    public function banner(Request $request)
    {
        $banners = Banner::when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
            ->orderBy('id')->paginate(15)->withQueryString();
        return view('cms.banner.index', compact('banners'));
    }

    public function createBanner() { return view('cms.banner.create'); }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'gambar' => 'required|image|max:3072',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $data = $request->except(['_token', 'gambar']);
        $data['status']     = $request->status ?? 'aktif';
        $data['created_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('banner', 'public');
        }

        Banner::create($data);
        return redirect()->route('cms.banner')->with('success', 'Banner berhasil disimpan.');
    }

    public function editBanner(Banner $banner) { return view('cms.banner.edit', compact('banner')); }

    public function updateBanner(Request $request, Banner $banner)
    {
        $request->validate([
            'judul'  => 'required|string|max:255',
            'gambar' => 'nullable|image|max:3072',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $data = $request->except(['_token', '_method', 'gambar']);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            if ($banner->gambar) Storage::disk('public')->delete($banner->gambar);
            $data['gambar'] = $request->file('gambar')->store('banner', 'public');
        }

        $banner->update($data);
        return redirect()->route('cms.banner')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroyBanner(Banner $banner)
    {
        if ($banner->gambar) Storage::disk('public')->delete($banner->gambar);
        $banner->update(['deleted_by' => Auth::id()]);
        $banner->delete();
        return back()->with('success', 'Banner berhasil dihapus.');
    }

    // ─────────────────────────── LAYANAN ─────────────────────────────

    public function layanan(Request $request)
    {
        $layanans = Layanan::when($request->search, fn($q) => $q->where('nama_layanan', 'like', "%{$request->search}%"))
            ->orderBy('id')->paginate(15)->withQueryString();
        return view('cms.layanan.index', compact('layanans'));
    }

    public function createLayanan() { return view('cms.layanan.create'); }

    public function storeLayanan(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'status'       => 'nullable|in:aktif,nonaktif',
            'gambar'       => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['_token', 'gambar']);
        $data['status']     = $request->status ?? 'aktif';
        $data['created_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('layanan', 'public');
        }

        Layanan::create($data);
        return redirect()->route('cms.layanan')->with('success', 'Layanan berhasil disimpan.');
    }

    public function editLayanan(Layanan $layanan) { return view('cms.layanan.edit', compact('layanan')); }

    public function updateLayanan(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'status'       => 'nullable|in:aktif,nonaktif',
            'gambar'       => 'nullable|image|max:2048',
        ]);

        $data = $request->except(['_token', '_method', 'gambar']);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            if ($layanan->gambar) Storage::disk('public')->delete($layanan->gambar);
            $data['gambar'] = $request->file('gambar')->store('layanan', 'public');
        }

        $layanan->update($data);
        return redirect()->route('cms.layanan')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroyLayanan(Layanan $layanan)
    {
        $layanan->update(['deleted_by' => Auth::id()]);
        $layanan->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    // ─────────────────────────── WEBSITE SETTING ────────────────────

    public function websiteSetting()
    {
        $setting = WebsiteSetting::first() ?? new WebsiteSetting();
        return view('cms.website-setting.edit', compact('setting'));
    }

    public function updateWebsiteSetting(Request $request)
    {
        $request->validate([
            'nama_rumahsakit' => 'required|string|max:150',
            'email'           => 'nullable|email',
            'telepon'         => 'nullable|string|max:20',
        ]);

        $setting = WebsiteSetting::first() ?? new WebsiteSetting();
        $data    = $request->only([
            'nama_rumahsakit', 'tentang_kami', 'visi', 'misi', 'sejarah',
            'motto', 'sambutan_direktur', 'alamat', 'telepon', 'email',
            'google_maps', 'facebook', 'instagram', 'youtube',
            'jam_operasional', 'footer', 'copyright',
        ]);
        $data['updated_by'] = Auth::id();
        if (!$setting->exists) $data['created_by'] = Auth::id();

        if ($request->hasFile('logo')) {
            if ($setting->logo) Storage::disk('public')->delete($setting->logo);
            $data['logo'] = $request->file('logo')->store('setting', 'public');
        }
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) Storage::disk('public')->delete($setting->favicon);
            $data['favicon'] = $request->file('favicon')->store('setting', 'public');
        }

        $setting->fill($data)->save();
        return redirect()->route('cms.website-setting')->with('success', 'Pengaturan website berhasil diperbarui.');
    }

    // ─────────────────────────── KEBIJAKAN PRIVASI ───────────────────

    public function privacyPolicyEditor()
    {
        $setting = WebsiteSetting::first() ?? new WebsiteSetting();
        return view('cms.kebijakan-privasi.edit', compact('setting'));
    }

    public function updatePrivacyPolicy(Request $request)
    {
        $request->validate(['privacy_policy' => 'nullable|string']);
        $setting = WebsiteSetting::firstOrNew([]);
        $setting->privacy_policy = $request->privacy_policy;
        $setting->updated_by     = Auth::id();
        if (!$setting->exists) $setting->created_by = Auth::id();
        $setting->save();
        return back()->with('success', 'Kebijakan Privasi berhasil diperbarui.');
    }

    // ─────────────────────────── SYARAT & KETENTUAN ──────────────────

    public function syaratKetentuanEditor()
    {
        $setting = WebsiteSetting::first() ?? new WebsiteSetting();
        return view('cms.syarat-ketentuan.edit', compact('setting'));
    }

    public function updateSyaratKetentuan(Request $request)
    {
        $request->validate(['syarat_ketentuan' => 'nullable|string']);
        $setting = WebsiteSetting::firstOrNew([]);
        $setting->syarat_ketentuan = $request->syarat_ketentuan;
        $setting->updated_by       = Auth::id();
        if (!$setting->exists) $setting->created_by = Auth::id();
        $setting->save();
        return back()->with('success', 'Syarat & Ketentuan berhasil diperbarui.');
    }

    public function kategoriArtikel(Request $request)
    {
        $kategoris = KategoriArtikel::withCount('artikels')
            ->when($request->search, fn($q) => $q->where('nama_kategori', 'like', "%{$request->search}%"))
            ->aktif()
            ->orderBy('nama_kategori')
            ->paginate(20)
            ->withQueryString();
        return view('cms.kategori-artikel.index', compact('kategoris'));
    }

    public function storeKategoriArtikel(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_artikel,nama_kategori',
        ]);

        KategoriArtikel::create([
            'nama_kategori' => $request->nama_kategori,
            'deskripsi'     => $request->deskripsi,
            'status'        => 'aktif',
            'created_by'    => Auth::id(),
        ]);

        return back()->with('success', 'Kategori berhasil disimpan.');
    }

    public function destroyKategoriArtikel(KategoriArtikel $kategoriArtikel)
    {
        $kategoriArtikel->update(['deleted_by' => Auth::id()]);
        $kategoriArtikel->delete();
        return back()->with('success', 'Kategori berhasil dihapus.');
    }

    // ─────────────────────────── INFORMASI ───────────────────────────

    public function informasi(Request $request)
    {
        $informasis = Informasi::when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_tm')->paginate(15)->withQueryString();
        return view('cms.informasi.index', compact('informasis'));
    }

    public function createInformasi()
    {
        return view('cms.informasi.create');
    }

    public function storeInformasi(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string|max:200',
            'isi'       => 'required|string',
            'status'    => 'required|in:draft,publish',
            'gambar'    => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', 'gambar', 'thumbnail']);
        $data['slug']       = Str::slug($request->judul) . '-' . Str::random(4);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('informasi', 'public');
        }

        Informasi::create($data);
        return redirect()->route('cms.informasi')->with('success', 'Informasi berhasil disimpan.');
    }

    public function editInformasi(Informasi $informasi)
    {
        return view('cms.informasi.edit', compact('informasi'));
    }

    public function updateInformasi(Request $request, Informasi $informasi)
    {
        $request->validate([
            'judul'     => 'required|string|max:200',
            'isi'       => 'required|string',
            'status'    => 'required|in:draft,publish',
            'gambar'    => 'nullable|image|max:3072',
        ]);

        $data = $request->except(['_token', '_method', 'gambar', 'thumbnail']);
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('gambar')) {
            if ($informasi->gambar) Storage::disk('public')->delete($informasi->gambar);
            $data['gambar'] = $request->file('gambar')->store('informasi', 'public');
        }

        $informasi->update($data);
        return redirect()->route('cms.informasi')->with('success', 'Informasi berhasil diperbarui.');
    }

    public function destroyInformasi(Informasi $informasi)
    {
        if ($informasi->gambar) Storage::disk('public')->delete($informasi->gambar);
        $informasi->update(['deleted_by' => Auth::id()]);
        $informasi->delete();
        return back()->with('success', 'Informasi berhasil dihapus.');
    }

    // ─────────────────────────── GUEST BOOK ──────────────────────────

    public function guestBook(Request $request)
    {
        $query = GuestBook::query();
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) $query->where(function ($q) use ($request) {
            $q->where('nama', 'like', "%{$request->search}%")
              ->orWhere('email', 'like', "%{$request->search}%")
              ->orWhere('pesan', 'like', "%{$request->search}%");
        });
        $pesans    = $query->orderByDesc('created_tm')->paginate(20)->withQueryString();
        $pesanBaru = GuestBook::where('status', 'baru')->count();
        return view('cms.guest-book.index', compact('pesans', 'pesanBaru'));
    }

    public function showGuestBook(GuestBook $guestBook)
    {
        // Tandai sebagai dibaca otomatis saat dilihat
        if ($guestBook->status === 'baru') {
            $guestBook->update(['status' => 'dibaca', 'updated_by' => Auth::id()]);
        }
        return view('cms.guest-book.show', compact('guestBook'));
    }

    public function markGuestBook(Request $request, GuestBook $guestBook)
    {
        $request->validate(['status' => 'required|in:baru,dibaca,selesai']);
        $guestBook->update(['status' => $request->status, 'updated_by' => Auth::id()]);
        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroyGuestBook(GuestBook $guestBook)
    {
        $guestBook->update(['deleted_by' => Auth::id()]);
        $guestBook->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    // ─────────────────────────── PROFILE & PASSWORD ──────────────────

    public function profile()
    {
        $user = Auth::user();
        return view('cms.setting.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'  => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'foto'  => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama', 'email', 'no_hp']);
        $data['updated_by'] = $user->id;

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $data['foto'] = $request->file('foto')->store('profile', 'public');
        }

        $user->update($data);
        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function settingPassword()
    {
        return view('cms.setting.password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password_lama'              => 'required',
            'password_baru'              => 'required|min:8|confirmed',
            'password_baru_confirmation' => 'required',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama yang Anda masukkan salah.'])->withInput();
        }

        $user->update([
            'password'   => \Illuminate\Support\Facades\Hash::make($request->password_baru),
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
