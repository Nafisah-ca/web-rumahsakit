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
use App\Models\Ulasan;
use App\Models\Faq;
use App\Models\Akreditasi;
use App\Models\WebsiteSetting;
use App\Models\PageBanner;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Spesialisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        // User CMS & admin yang pernah login (berdasarkan last_login)
        $pengunjungCms = \App\Models\User::whereIn('role', ['cms', 'admin'])
            ->where('status', 'aktif')
            ->whereNotNull('last_login')
            ->orderByDesc('last_activity')
            ->orderByDesc('last_login')
            ->limit(10)
            ->get();

        // Log login terbaru 20 entri
        $loginLogs = \App\Models\LoginLog::with('user')
            ->orderByDesc('login_at')
            ->limit(20)
            ->get();

        return view('cms.dashboard', compact('stats', 'recentArtikel', 'pesanBaru', 'pengunjungCms', 'loginLogs'));
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
        $data['dokter_id']  = null; // otomatis dari kategori → spesialisasi

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
        // dokter_id tidak diubah — tetap pakai sistem otomatis dari kategori

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
        $query = Promo::when($request->search, fn($q) => $q->where('judul', 'like', "%{$request->search}%"));

        // Filter status: 'kedaluwarsa' = aktif tapi tanggal sudah lewat
        if ($request->status === 'kedaluwarsa') {
            $query->where('status', 'aktif')
                  ->whereNotNull('tanggal_selesai')
                  ->where('tanggal_selesai', '<', today());
        } elseif ($request->status) {
            $query->where('status', $request->status);
        }

        $promos = $query->orderByDesc('tanggal_mulai')->paginate(15)->withQueryString();
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
        $layanans = Layanan::with('kategori')
            ->when($request->search, fn($q) => $q->where('nama_layanan', 'like', "%{$request->search}%"))
            ->when($request->kategori_id, fn($q) => $q->where('kategori_layanan_id', $request->kategori_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('kategori_layanan_id')->orderBy('id')
            ->paginate(15)->withQueryString();
        $kategoris = \App\Models\KategoriLayanan::aktif()->get();
        return view('cms.layanan.index', compact('layanans', 'kategoris'));
    }

    public function createLayanan()
    {
        $kategoris = \App\Models\KategoriLayanan::aktif()->get();
        return view('cms.layanan.create', compact('kategoris'));
    }

    public function storeLayanan(Request $request)
    {
        $request->validate([
            'nama_layanan'        => 'required|string|max:255',
            'kategori_layanan_id' => 'nullable|exists:kategori_layanan,id',
            'deskripsi'           => 'nullable|string',
            'status'              => 'nullable|in:aktif,nonaktif',
            'gambar'              => 'nullable|image|max:2048',
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

    public function editLayanan(Layanan $layanan)
    {
        $kategoris = \App\Models\KategoriLayanan::aktif()->get();
        return view('cms.layanan.edit', compact('layanan', 'kategoris'));
    }

    public function updateLayanan(Request $request, Layanan $layanan)
    {
        $request->validate([
            'nama_layanan'        => 'required|string|max:255',
            'kategori_layanan_id' => 'nullable|exists:kategori_layanan,id',
            'deskripsi'           => 'nullable|string',
            'status'              => 'nullable|in:aktif,nonaktif',
            'gambar'              => 'nullable|image|max:2048',
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
        if ($layanan->gambar) Storage::disk('public')->delete($layanan->gambar);
        $layanan->update(['deleted_by' => Auth::id()]);
        $layanan->delete();
        return back()->with('success', 'Layanan berhasil dihapus.');
    }

    // ─────────────────────────── KATEGORI LAYANAN ────────────────────

    public function kategoriLayanan(Request $request)
    {
        $query = \App\Models\KategoriLayanan::withCount(['layanans' => fn($q) => $q->where('status','aktif')])
            ->when($request->search, fn($q) => $q->where('nama_kategori', 'like', "%{$request->search}%"));

        // Pakai urutan hanya jika kolom sudah ada
        if (\Illuminate\Support\Facades\Schema::hasColumn('kategori_layanan', 'urutan')) {
            $query->orderBy('urutan');
        }
        $query->orderBy('nama_kategori');

        $kategoris = $query->paginate(20)->withQueryString();
        return view('cms.kategori-layanan.index', compact('kategoris'));
    }

    public function storeKategoriLayanan(Request $request)
    {
        $rules = [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_layanan', 'nama_kategori')->whereNull('deleted_tm'),
            ],
            'icon'   => 'nullable|string|max:50',
            'gambar' => 'nullable|image|max:2048',
        ];
        $hasUrutan = \Illuminate\Support\Facades\Schema::hasColumn('kategori_layanan', 'urutan');
        if ($hasUrutan) $rules['urutan'] = 'nullable|integer|min:0';

        $messages = [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah digunakan. Silakan gunakan nama lain.',
            'gambar.image'           => 'Berkas foto harus berupa gambar.',
            'gambar.max'             => 'Ukuran foto maksimal 2MB.',
        ];

        $request->validate($rules, $messages);

        $data = [
            'nama_kategori' => $request->nama_kategori,
            'icon'          => $request->icon ?? 'fa-hospital',
            'status'        => 'aktif',
            'created_by'    => Auth::id(),
        ];
        if ($hasUrutan)                          $data['urutan']    = $request->urutan ?? 0;
        if (\Illuminate\Support\Facades\Schema::hasColumn('kategori_layanan', 'deskripsi'))
                                                 $data['deskripsi'] = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('kategori-layanan', 'public');
        }

        \App\Models\KategoriLayanan::create($data);
        return back()->with('success', 'Kategori layanan berhasil disimpan.');
    }

    public function updateKategoriLayanan(Request $request, \App\Models\KategoriLayanan $kategoriLayanan)
    {
        $rules = [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori_layanan', 'nama_kategori')->whereNull('deleted_tm')->ignore($kategoriLayanan->id),
            ],
            'icon'   => 'nullable|string|max:50',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => 'nullable|image|max:2048',
        ];
        $hasUrutan = \Illuminate\Support\Facades\Schema::hasColumn('kategori_layanan', 'urutan');
        if ($hasUrutan) $rules['urutan'] = 'nullable|integer|min:0';

        $messages = [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique'   => 'Nama kategori sudah digunakan. Silakan gunakan nama lain.',
            'gambar.image'           => 'Berkas foto harus berupa gambar.',
            'gambar.max'             => 'Ukuran foto maksimal 2MB.',
        ];

        $request->validate($rules, $messages);

        $data = [
            'nama_kategori' => $request->nama_kategori,
            'icon'          => $request->icon ?? 'fa-hospital',
            'status'        => $request->status,
            'updated_by'    => Auth::id(),
        ];
        if ($hasUrutan)                          $data['urutan']    = $request->urutan ?? 0;
        if (\Illuminate\Support\Facades\Schema::hasColumn('kategori_layanan', 'deskripsi'))
                                                 $data['deskripsi'] = $request->deskripsi;

        if ($request->hasFile('gambar')) {
            if ($kategoriLayanan->gambar) {
                Storage::disk('public')->delete($kategoriLayanan->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('kategori-layanan', 'public');
        }

        $kategoriLayanan->update($data);
        return back()->with('success', 'Kategori layanan berhasil diperbarui.');
    }

    public function destroyKategoriLayanan(\App\Models\KategoriLayanan $kategoriLayanan)
    {
        // Null-kan foreign key di layanan yang pakai kategori ini sebelum hapus
        Layanan::where('kategori_layanan_id', $kategoriLayanan->id)
            ->update(['kategori_layanan_id' => null]);

        if ($kategoriLayanan->gambar) {
            Storage::disk('public')->delete($kategoriLayanan->gambar);
        }

        $kategoriLayanan->update(['deleted_by' => Auth::id()]);
        $kategoriLayanan->delete();
        return back()->with('success', 'Kategori layanan berhasil dihapus.');
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
            'jam_operasional', 'jumlah_spesialisasi', 'jumlah_mitra_asuransi',
            'footer', 'copyright', 'whatsapp',
            'privacy_policy', 'syarat_ketentuan',
        ]);

        // Jadwal sholat dihapus — tidak lagi diproses atau disimpan
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

    // ─────────────────────────── PENGATURAN (dipecah per bagian) ────────────────

    private function getSettingOrNew(): WebsiteSetting
    {
        return WebsiteSetting::first() ?? new WebsiteSetting();
    }

    private function saveSetting(array $data, WebsiteSetting $setting): void
    {
        $data['updated_by'] = Auth::id();
        if (!$setting->exists) $data['created_by'] = Auth::id();
        $setting->fill($data)->save();
    }

    // ── Identitas RS ──────────────────────────────────────────────────────────

    public function identitasRs()
    {
        $setting = $this->getSettingOrNew();
        return view('cms.pengaturan.identitas-rs', compact('setting'));
    }

    public function updateIdentitasRs(Request $request)
    {
        $request->validate([
            'nama_rumahsakit' => 'required|string|max:150',
            'nama_direktur'   => 'nullable|string|max:150',
            'foto_direktur'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        $setting = $this->getSettingOrNew();

        $data = $request->only([
            'nama_rumahsakit', 'motto', 'tentang_kami',
            'visi', 'misi', 'sejarah', 'sambutan_direktur', 'nama_direktur',
        ]);

        if ($request->hasFile('foto_direktur')) {
            if ($setting->foto_direktur) {
                Storage::disk('public')->delete($setting->foto_direktur);
            }
            $data['foto_direktur'] = $request->file('foto_direktur')->store('direktur', 'public');
        }

        // Hapus foto jika checkbox dicentang
        if ($request->hapus_foto_direktur && $setting->foto_direktur) {
            Storage::disk('public')->delete($setting->foto_direktur);
            $data['foto_direktur'] = null;
        }

        $this->saveSetting($data, $setting);
        return redirect()->route('cms.identitas-rs')->with('success', 'Identitas RS berhasil diperbarui.');
    }

    // ── Kontak & Lokasi ───────────────────────────────────────────────────────

    public function kontakLokasi()
    {
        $setting = $this->getSettingOrNew();
        return view('cms.pengaturan.kontak-lokasi', compact('setting'));
    }

    public function updateKontakLokasi(Request $request)
    {
        $request->validate([
            'email'   => 'nullable|email',
            'telepon' => 'nullable|string|max:20',
            'whatsapp'=> 'nullable|string|max:20',
        ]);
        $setting = $this->getSettingOrNew();
        $this->saveSetting($request->only([
            'email', 'telepon', 'whatsapp',
            'alamat', 'google_maps', 'jam_operasional',
        ]), $setting);
        return redirect()->route('cms.kontak-lokasi')->with('success', 'Kontak & Lokasi berhasil diperbarui.');
    }

    // ── Logo & Tampilan ───────────────────────────────────────────────────────

    public function logoTampilan()
    {
        $setting = $this->getSettingOrNew();
        return view('cms.pengaturan.logo-tampilan', compact('setting'));
    }

    public function updateLogoTampilan(Request $request)
    {
        $request->validate([
            'logo'    => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp,ico|max:512',
        ]);
        $setting = $this->getSettingOrNew();
        $data    = [];
        if ($request->hasFile('logo')) {
            if ($setting->logo) Storage::disk('public')->delete($setting->logo);
            $data['logo'] = $request->file('logo')->store('setting', 'public');
        }
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) Storage::disk('public')->delete($setting->favicon);
            $data['favicon'] = $request->file('favicon')->store('setting', 'public');
        }
        $this->saveSetting($data, $setting);
        return redirect()->route('cms.logo-tampilan')->with('success', 'Logo & Tampilan berhasil diperbarui.');
    }

    // ── Statistik ─────────────────────────────────────────────────────────────

    public function statistik()
    {
        $setting = $this->getSettingOrNew();
        return view('cms.pengaturan.statistik', compact('setting'));
    }

    public function updateStatistik(Request $request)
    {
        $request->validate([
            'jumlah_spesialisasi'   => 'nullable|integer|min:0|max:9999',
            'jumlah_mitra_asuransi' => 'nullable|integer|min:0|max:9999',
        ]);
        $setting = $this->getSettingOrNew();
        $this->saveSetting($request->only([
            'jumlah_spesialisasi', 'jumlah_mitra_asuransi',
        ]), $setting);
        return redirect()->route('cms.statistik')->with('success', 'Statistik berhasil diperbarui.');
    }

    // ── Sosial Media ──────────────────────────────────────────────────────────

    public function sosialMedia()
    {
        $setting = $this->getSettingOrNew();
        return view('cms.pengaturan.sosial-media', compact('setting'));
    }

    public function updateSosialMedia(Request $request)
    {
        $setting = $this->getSettingOrNew();
        $this->saveSetting($request->only([
            'facebook', 'instagram', 'youtube',
        ]), $setting);
        return redirect()->route('cms.sosial-media')->with('success', 'Sosial Media berhasil diperbarui.');
    }

    // ── Footer ────────────────────────────────────────────────────────────────

    public function footerSetting()
    {
        $setting = $this->getSettingOrNew();
        return view('cms.pengaturan.footer', compact('setting'));
    }

    public function updateFooterSetting(Request $request)
    {
        $setting = $this->getSettingOrNew();
        $this->saveSetting($request->only([
            'footer', 'copyright',
        ]), $setting);
        return redirect()->route('cms.footer')->with('success', 'Footer berhasil diperbarui.');
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
            'spesialis_id'  => 'nullable|exists:spesialis,id',
        ]);

        KategoriArtikel::create([
            'nama_kategori' => $request->nama_kategori ?? $request->nama,
            'deskripsi'     => $request->deskripsi,
            'spesialis_id'  => $request->spesialis_id ?: null,
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

    // ─────────────────────────── ULASAN PASIEN ───────────────────────

    public function ulasan(Request $request)
    {
        $query = Ulasan::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('isi', 'like', "%{$request->search}%")
                  ->orWhere('judul', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        $ulasans = $query->orderByDesc('created_tm')->paginate(20)->withQueryString();

        $stats = [
            'total'    => Ulasan::count(),
            'pending'  => Ulasan::pending()->count(),
            'approved' => Ulasan::approved()->count(),
            'rejected' => Ulasan::where('status', 'rejected')->count(),
            'avg'      => round(Ulasan::approved()->avg('rating'), 1),
        ];

        return view('cms.ulasan.index', compact('ulasans', 'stats'));
    }

    public function showUlasan(Ulasan $ulasan)
    {
        return view('cms.ulasan.show', compact('ulasan'));
    }

    public function markUlasan(Request $request, Ulasan $ulasan)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $ulasan->update([
            'status'     => $request->status,
            'updated_by' => Auth::id(),
        ]);

        $label = match ($request->status) {
            'approved' => 'ditampilkan',
            'rejected' => 'ditolak',
            default    => 'dikembalikan ke pending',
        };

        return back()->with('success', "Ulasan berhasil {$label}.");
    }

    public function destroyUlasan(Ulasan $ulasan)
    {
        $ulasan->update(['deleted_by' => Auth::id()]);
        $ulasan->delete();
        return back()->with('success', 'Ulasan berhasil dihapus.');
    }

    // ─────────────────────────── FAQ ─────────────────────────────────

    public function faq(Request $request)
    {
        $faqs = Faq::when($request->search, fn($q) => $q->where('pertanyaan', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('urutan')->orderBy('created_tm')
            ->paginate(20)->withQueryString();
        return view('cms.faq.index', compact('faqs'));
    }

    public function createFaq()
    {
        return view('cms.faq.create');
    }

    public function storeFaq(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:300',
            'jawaban'    => 'required|string',
            'urutan'     => 'nullable|integer|min:0',
            'status'     => 'required|in:aktif,nonaktif',
        ]);

        Faq::create([
            'pertanyaan' => $request->pertanyaan,
            'jawaban'    => $request->jawaban,
            'urutan'     => $request->urutan ?? 0,
            'status'     => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('cms.faq')->with('success', 'FAQ berhasil disimpan.');
    }

    public function editFaq(Faq $faq)
    {
        return view('cms.faq.edit', compact('faq'));
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:300',
            'jawaban'    => 'required|string',
            'urutan'     => 'nullable|integer|min:0',
            'status'     => 'required|in:aktif,nonaktif',
        ]);

        $faq->update([
            'pertanyaan' => $request->pertanyaan,
            'jawaban'    => $request->jawaban,
            'urutan'     => $request->urutan ?? 0,
            'status'     => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('cms.faq')->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->update(['deleted_by' => Auth::id()]);
        $faq->delete();
        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    // ─────────────────────────── AKREDITASI ──────────────────────────

    public function akreditasi(Request $request)
    {
        $akreditasis = Akreditasi::when(
                $request->search,
                fn($q) => $q->where('nama', 'like', "%{$request->search}%")
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderBy('urutan')
            ->paginate(20)
            ->withQueryString();

        return view('cms.akreditasi.index', compact('akreditasis'));
    }

    public function createAkreditasi()
    {
        return view('cms.akreditasi.create');
    }

    public function storeAkreditasi(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'tahun'     => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'deskripsi' => 'nullable|string|max:200',
            'urutan'    => 'nullable|integer|min:0',
            'status'    => 'required|in:aktif,nonaktif',
            'logo'      => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $data               = $request->only(['nama', 'tahun', 'deskripsi', 'urutan', 'status']);
        $data['urutan']     = $request->urutan ?? 0;
        $data['created_by'] = Auth::id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('akreditasi', 'public');
        }

        Akreditasi::create($data);
        return redirect()->route('cms.akreditasi')->with('success', 'Akreditasi berhasil disimpan.');
    }

    public function editAkreditasi(Akreditasi $akreditasi)
    {
        return view('cms.akreditasi.edit', compact('akreditasi'));
    }

    public function updateAkreditasi(Request $request, Akreditasi $akreditasi)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'tahun'     => 'nullable|digits:4|integer|min:1900|max:' . (date('Y') + 1),
            'deskripsi' => 'nullable|string|max:200',
            'urutan'    => 'nullable|integer|min:0',
            'status'    => 'required|in:aktif,nonaktif',
            'logo'      => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        $data               = $request->only(['nama', 'tahun', 'deskripsi', 'urutan', 'status']);
        $data['urutan']     = $request->urutan ?? $akreditasi->urutan;
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('logo')) {
            if ($akreditasi->logo) Storage::disk('public')->delete($akreditasi->logo);
            $data['logo'] = $request->file('logo')->store('akreditasi', 'public');
        }

        // Hapus logo jika checkbox "hapus logo" dicentang
        if ($request->hapus_logo && $akreditasi->logo) {
            Storage::disk('public')->delete($akreditasi->logo);
            $data['logo'] = null;
        }

        $akreditasi->update($data);
        return redirect()->route('cms.akreditasi')->with('success', 'Akreditasi berhasil diperbarui.');
    }

    public function destroyAkreditasi(Akreditasi $akreditasi)
    {
        if ($akreditasi->logo) Storage::disk('public')->delete($akreditasi->logo);
        $akreditasi->update(['deleted_by' => Auth::id()]);
        $akreditasi->delete();
        return back()->with('success', 'Akreditasi berhasil dihapus.');
    }

    // ─────────────────────────── DOKTER ──────────────────────────────

    public function dokter(Request $request)
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        $query = Dokter::with('spesialisasi');
        if ($request->search) $query->where('nama_dokter', 'like', "%{$request->search}%");
        if ($request->spesialis_id) $query->where('spesialis_id', $request->spesialis_id);
        $dokters = $query->orderBy('nama_dokter')->paginate(15)->withQueryString();
        return view('cms.dokter.index', compact('dokters', 'spesialisasis'));
    }

    public function createDokter()
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        return view('cms.dokter.create', compact('spesialisasis'));
    }

    public function storeDokter(Request $request)
    {
        $request->validate([
            'nama_dokter'   => 'required|string|max:150',
            'spesialis_id'  => 'required|exists:spesialisasi,id',
            'email'         => 'nullable|email',
            'no_hp'         => 'nullable|string|max:20',
            'status'        => 'required|in:aktif,nonaktif',
            'foto'          => 'nullable|image|max:2048',
        ]);
        $data = $request->only(['nama_dokter','spesialis_id','email','no_hp','status','bio','pendidikan','pengalaman']);
        $data['created_by'] = Auth::id();
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('dokter', 'public');
        }
        Dokter::create($data);
        return redirect()->route('cms.dokter')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function editDokter(Dokter $dokter)
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        return view('cms.dokter.edit', compact('dokter', 'spesialisasis'));
    }

    public function updateDokter(Request $request, Dokter $dokter)
    {
        $request->validate([
            'nama_dokter'  => 'required|string|max:150',
            'spesialis_id' => 'required|exists:spesialisasi,id',
            'email'        => 'nullable|email',
            'no_hp'        => 'nullable|string|max:20',
            'status'       => 'required|in:aktif,nonaktif',
            'foto'         => 'nullable|image|max:2048',
        ]);
        $data = $request->only(['nama_dokter','spesialis_id','email','no_hp','status','bio','pendidikan','pengalaman']);
        $data['updated_by'] = Auth::id();
        if ($request->hasFile('foto')) {
            if ($dokter->foto) Storage::disk('public')->delete($dokter->foto);
            $data['foto'] = $request->file('foto')->store('dokter', 'public');
        }
        $dokter->update($data);
        return redirect()->route('cms.dokter')->with('success', 'Dokter berhasil diperbarui.');
    }

    public function destroyDokter(Dokter $dokter)
    {
        if ($dokter->foto) Storage::disk('public')->delete($dokter->foto);
        $dokter->delete();
        return back()->with('success', 'Dokter berhasil dihapus.');
    }

    // ─────────────────────────── JADWAL DOKTER ───────────────────────

    public function jadwalDokter(Request $request)
    {
        $dokterList = Dokter::orderBy('nama_dokter')->get();
        $query = JadwalDokter::with(['dokter', 'spesialisasi']);
        if ($request->dokter_id) $query->where('dokter_id', $request->dokter_id);
        if ($request->hari)      $query->where('hari', $request->hari);
        $jadwals = $query->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
                         ->orderBy('jam_mulai')
                         ->paginate(20)->withQueryString();
        return view('cms.jadwal.index', compact('jadwals', 'dokterList'));
    }

    public function createJadwal()
    {
        $dokterList    = Dokter::where('status','aktif')->orderBy('nama_dokter')->get();
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        return view('cms.jadwal.create', compact('dokterList', 'spesialisasis'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'dokter_id'      => 'required|exists:dokter,id',
            'spesialis_id'   => 'required|exists:spesialisasi,id',
            'hari'           => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'      => 'required',
            'jam_selesai'    => 'required|after:jam_mulai',
            'kuota'          => 'required|integer|min:1|max:200',
            'status'         => 'required|in:aktif,nonaktif',
        ]);
        JadwalDokter::create($request->only(['dokter_id','spesialis_id','hari','jam_mulai','jam_selesai','kuota','status','tanggal_praktek']) + ['created_by' => Auth::id()]);
        return redirect()->route('cms.jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function editJadwal(JadwalDokter $jadwalDokter)
    {
        $dokterList    = Dokter::where('status','aktif')->orderBy('nama_dokter')->get();
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        return view('cms.jadwal.edit', compact('jadwalDokter', 'dokterList', 'spesialisasis'));
    }

    public function updateJadwal(Request $request, JadwalDokter $jadwalDokter)
    {
        $request->validate([
            'dokter_id'    => 'required|exists:dokter,id',
            'spesialis_id' => 'required|exists:spesialisasi,id',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'kuota'        => 'required|integer|min:1|max:200',
            'status'       => 'required|in:aktif,nonaktif',
        ]);
        $jadwalDokter->update($request->only(['dokter_id','spesialis_id','hari','jam_mulai','jam_selesai','kuota','status','tanggal_praktek']) + ['updated_by' => Auth::id()]);
        return redirect()->route('cms.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroyJadwal(JadwalDokter $jadwalDokter)
    {
        $jadwalDokter->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
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

    // ─────────────────────────── PAGE BANNER ─────────────────────────

    public function pageBanner()
    {
        $banners = PageBanner::orderBy('nama_halaman')->get();
        return view('cms.page-banner.index', compact('banners'));
    }

    public function editPageBanner(PageBanner $pageBanner)
    {
        $banner = $pageBanner;
        return view('cms.page-banner.edit', compact('banner'));
    }

    public function updatePageBanner(Request $request, PageBanner $pageBanner)
    {
        $request->validate([
            'judul'       => 'required|string|max:200',
            'label_atas'  => 'nullable|string|max:100',
            'subjudul'    => 'nullable|string|max:300',
            'warna_awal'  => 'nullable|string|max:20',
            'warna_akhir' => 'nullable|string|max:20',
            'status'      => 'required|in:aktif,nonaktif',
            'gambar'      => 'nullable|image|max:4096',
        ]);

        $data = $request->only(['judul', 'label_atas', 'subjudul', 'warna_awal', 'warna_akhir', 'status']);
        $data['updated_by'] = Auth::id();

        // Hapus gambar jika diminta
        if ($request->hapus_gambar && $pageBanner->gambar) {
            if ($pageBanner->gambar && !str_starts_with($pageBanner->gambar, 'data:')) {
                Storage::disk('public')->delete($pageBanner->gambar);
            }
            $data['gambar'] = null;
        }

        // Upload & simpan gambar langsung ke Database dalam format Base64 Data URL
        if ($request->hasFile('gambar')) {
            if ($pageBanner->gambar && !str_starts_with($pageBanner->gambar, 'data:')) {
                Storage::disk('public')->delete($pageBanner->gambar);
            }
            $data['gambar'] = $this->convertImageToBase64($request->file('gambar'));
        }

        $pageBanner->update($data);

        return redirect()->route('cms.page-banner')->with('success', 'Banner halaman berhasil diperbarui.');
    }

    /**
     * Konversi file gambar ke Base64 Data URL dengan kompresi GD (jika tersedia).
     * Gambar disimpan 100% di database sehingga ketika database di-export, gambar tetap ikut terbawa.
     */
    private function convertImageToBase64($file): string
    {
        $realPath = $file->getRealPath();
        $mime     = $file->getMimeType() ?: 'image/jpeg';

        if (extension_loaded('gd') && function_exists('imagecreatefromstring')) {
            try {
                $imageData = file_get_contents($realPath);
                if ($imageData !== false) {
                    $srcImage = @imagecreatefromstring($imageData);
                    if ($srcImage !== false) {
                        $origW = imagesx($srcImage);
                        $origH = imagesy($srcImage);

                        $maxW = 1600;
                        if ($origW > $maxW) {
                            $newW = $maxW;
                            $newH = (int) round(($origH / $origW) * $newW);
                            $dstImage = imagecreatetruecolor($newW, $newH);

                            if (in_array($mime, ['image/png', 'image/webp'])) {
                                imagealphablending($dstImage, false);
                                imagesavealpha($dstImage, true);
                            }

                            imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
                            imagedestroy($srcImage);
                            $srcImage = $dstImage;
                        }

                        ob_start();
                        if ($mime === 'image/png') {
                            imagepng($srcImage, null, 7);
                        } elseif ($mime === 'image/webp' && function_exists('imagewebp')) {
                            imagewebp($srcImage, null, 80);
                        } else {
                            imagejpeg($srcImage, null, 82);
                            $mime = 'image/jpeg';
                        }
                        $compressedData = ob_get_clean();
                        imagedestroy($srcImage);

                        if ($compressedData !== false && $compressedData !== '') {
                            return 'data:' . $mime . ';base64,' . base64_encode($compressedData);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Fallback jika GD bermasalah
            }
        }

        $fileData = file_get_contents($realPath);
        return 'data:' . $mime . ';base64,' . base64_encode($fileData);
    }
}

// NOTE: Method antrianPoli dan updateAntrianPoli ditambahkan sebagai extension
// karena file sudah lengkap. Gunakan trait atau pisahkan controller jika diperlukan.
