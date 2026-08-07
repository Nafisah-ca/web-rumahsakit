<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Cms\CmsController;
use App\Http\Controllers\Admin\SpesialisasiController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Admin\PenjaminController;

// ─────────────────────────── PUBLIC PORTAL ───────────────────────────────────

Route::get('/',                [HospitalController::class, 'home'])->name('home');
Route::get('/tentang-kami',    [HospitalController::class, 'tentang'])->name('tentang');
Route::get('/pelayanan',       [HospitalController::class, 'layanan'])->name('layanan');
Route::get('/dokter',          [HospitalController::class, 'dokter'])->name('dokter');
Route::get('/dokter/online',   [HospitalController::class, 'dokterOnline'])->name('dokter.online');
Route::get('/dokter/{spSlug}', [HospitalController::class, 'dokterBySpesialis'])->name('dokter.by-spesialis');
Route::get('/fasilitas',       [HospitalController::class, 'fasilitas'])->name('fasilitas');
Route::get('/kontak',          [HospitalController::class, 'kontak'])->name('kontak');
Route::post('/kontak',         [HospitalController::class, 'storeKontak'])->name('kontak.store');
Route::get('/promo',              [HospitalController::class, 'promo'])->name('promo');
Route::get('/promo/{promo}',      [HospitalController::class, 'promoDetail'])->name('promo.detail');
Route::get('/event',              [HospitalController::class, 'event'])->name('event');
Route::get('/event/{event}',      [HospitalController::class, 'eventDetail'])->name('event.detail');
Route::get('/informasi',          [HospitalController::class, 'informasiPublic'])->name('informasi');
Route::get('/informasi/{informasi}', [HospitalController::class, 'informasiDetail'])->name('informasi.detail');
Route::get('/artikel',         [HospitalController::class, 'artikel'])->name('artikel');
Route::get('/artikel/{slug}',  [HospitalController::class, 'artikelDetail'])->name('artikel.detail');
Route::get('/medical-checkup', [HospitalController::class, 'mcu'])->name('mcu');
Route::get('/live-antrian',    [HospitalController::class, 'liveAntrian'])->name('live.antrian');

// ─────────────────────────── AUTH ─────────────────────────────────────────────

Route::middleware('guest')->group(function () {
    Route::get('/sign-in',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/sign-in', [AuthController::class, 'login'])->name('login.post');
    Route::get('/daftar',   [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar',  [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/sign-out', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

// ─────────────────────────── PORTAL PASIEN (role: pasien) ─────────────────────

Route::middleware(['auth', 'role:pasien'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/profil',                                    [AuthController::class,  'portalProfil'])->name('profil');
    Route::put('/profil',                                    [AuthController::class,  'updateProfil'])->name('profil.update');
    Route::get('/booking',                                   [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking',                                  [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/riwayat',                           [BookingController::class, 'riwayat'])->name('booking.riwayat');
    Route::post('/booking/{janjiTemu}/batal',                [BookingController::class, 'cancel'])->name('booking.cancel');
    Route::get('/booking/jadwal',                            [BookingController::class, 'jadwal'])->name('booking.jadwal');
});

// ─────────────────────────── ADMIN ────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Users
    Route::get('/users',                    [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',             [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',                   [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',        [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',             [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}',          [AdminController::class, 'destroyUser'])->name('users.destroy');

    // Pasien
    Route::get('/pasien',                   [AdminController::class, 'pasien'])->name('pasien');
    Route::get('/pasien/create',            [AdminController::class, 'createPasien'])->name('pasien.create');
    Route::post('/pasien',                  [AdminController::class, 'storePasien'])->name('pasien.store');
    Route::get('/pasien/{pasien}',          [AdminController::class, 'showPasien'])->name('pasien.show');
    Route::get('/pasien/{pasien}/edit',     [AdminController::class, 'editPasien'])->name('pasien.edit');
    Route::put('/pasien/{pasien}',          [AdminController::class, 'updatePasien'])->name('pasien.update');
    Route::delete('/pasien/{pasien}',       [AdminController::class, 'destroyPasien'])->name('pasien.destroy');
    Route::patch('/pasien/{pasien}/restore',[AdminController::class, 'restorePasien'])->name('pasien.restore');

    // Booking / Janji Temu
    Route::get('/booking',                   [AdminController::class, 'janjiTemu'])->name('booking');
    Route::get('/booking/{janjiTemu}',       [AdminController::class, 'showBooking'])->name('booking.show');
    Route::put('/booking/{janjiTemu}/status',[AdminController::class, 'updateStatusBooking'])->name('booking.status');
    Route::delete('/booking/{janjiTemu}',    [AdminController::class, 'destroyBooking'])->name('booking.destroy');

    // Dokter
    Route::get('/dokter',                   [AdminController::class, 'dokter'])->name('dokter');
    Route::get('/dokter/create',            [AdminController::class, 'createDokter'])->name('dokter.create');
    Route::post('/dokter',                  [AdminController::class, 'storeDokter'])->name('dokter.store');
    Route::get('/dokter/{dokter}/edit',     [AdminController::class, 'editDokter'])->name('dokter.edit');
    Route::put('/dokter/{dokter}',          [AdminController::class, 'updateDokter'])->name('dokter.update');
    Route::delete('/dokter/{dokter}',       [AdminController::class, 'destroyDokter'])->name('dokter.destroy');

    // Jadwal Dokter
    Route::get('/jadwal',                         [AdminController::class, 'jadwalDokter'])->name('jadwal');
    Route::get('/jadwal/create',                  [AdminController::class, 'createJadwal'])->name('jadwal.create');
    Route::post('/jadwal',                        [AdminController::class, 'storeJadwal'])->name('jadwal.store');
    Route::get('/jadwal/{jadwalDokter}/edit',     [AdminController::class, 'editJadwal'])->name('jadwal.edit');
    Route::put('/jadwal/{jadwalDokter}',          [AdminController::class, 'updateJadwal'])->name('jadwal.update');
    Route::delete('/jadwal/{jadwalDokter}',       [AdminController::class, 'destroyJadwal'])->name('jadwal.destroy');

    // Spesialisasi
    Route::get('/spesialisasi',                        [SpesialisasiController::class, 'index'])->name('spesialisasi');
    Route::get('/spesialisasi/create',                 [SpesialisasiController::class, 'create'])->name('spesialisasi.create');
    Route::post('/spesialisasi',                       [SpesialisasiController::class, 'store'])->name('spesialisasi.store');
    Route::get('/spesialisasi/{spesialisasi}/edit',    [SpesialisasiController::class, 'edit'])->name('spesialisasi.edit');
    Route::put('/spesialisasi/{spesialisasi}',         [SpesialisasiController::class, 'update'])->name('spesialisasi.update');
    Route::delete('/spesialisasi/{spesialisasi}',      [SpesialisasiController::class, 'destroy'])->name('spesialisasi.destroy');

    // Laporan
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');

    // Transaksi
    Route::get('/transaksi',                      [TransaksiController::class, 'index'])->name('transaksi');
    Route::get('/transaksi/create',               [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi',                     [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('/transaksi/{transaksi}',          [TransaksiController::class, 'show'])->name('transaksi.show');
    Route::put('/transaksi/{transaksi}/status',   [TransaksiController::class, 'updateStatus'])->name('transaksi.status');
    Route::delete('/transaksi/{transaksi}',       [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    // Penjamin
    Route::get('/penjamin',                       [PenjaminController::class, 'index'])->name('penjamin');
    Route::post('/penjamin',                      [PenjaminController::class, 'store'])->name('penjamin.store');
    Route::put('/penjamin/{penjamin}',            [PenjaminController::class, 'update'])->name('penjamin.update');
    Route::delete('/penjamin/{penjamin}',         [PenjaminController::class, 'destroy'])->name('penjamin.destroy');

    // Tipe Penjamin
    Route::get('/tipe-penjamin',                  [PenjaminController::class, 'tipePenjamin'])->name('tipe-penjamin');
    Route::post('/tipe-penjamin',                 [PenjaminController::class, 'storeTipePenjamin'])->name('tipe-penjamin.store');
    Route::delete('/tipe-penjamin/{tipePenjamin}',[PenjaminController::class, 'destroyTipePenjamin'])->name('tipe-penjamin.destroy');
});

// ─────────────────────────── CMS ──────────────────────────────────────────────

Route::middleware(['auth', 'role:cms'])->prefix('cms')->name('cms.')->group(function () {

    Route::get('/dashboard', [CmsController::class, 'dashboard'])->name('dashboard');

    // Artikel
    Route::get('/artikel',                  [CmsController::class, 'artikel'])->name('artikel');
    Route::get('/artikel/create',           [CmsController::class, 'createArtikel'])->name('artikel.create');
    Route::post('/artikel',                 [CmsController::class, 'storeArtikel'])->name('artikel.store');
    Route::get('/artikel/{artikel}/edit',   [CmsController::class, 'editArtikel'])->name('artikel.edit');
    Route::put('/artikel/{artikel}',        [CmsController::class, 'updateArtikel'])->name('artikel.update');
    Route::delete('/artikel/{artikel}',     [CmsController::class, 'destroyArtikel'])->name('artikel.destroy');

    // Promo
    Route::get('/promo',                    [CmsController::class, 'promo'])->name('promo');
    Route::get('/promo/create',             [CmsController::class, 'createPromo'])->name('promo.create');
    Route::post('/promo',                   [CmsController::class, 'storePromo'])->name('promo.store');
    Route::get('/promo/{promo}/edit',       [CmsController::class, 'editPromo'])->name('promo.edit');
    Route::put('/promo/{promo}',            [CmsController::class, 'updatePromo'])->name('promo.update');
    Route::delete('/promo/{promo}',         [CmsController::class, 'destroyPromo'])->name('promo.destroy');

    // Event
    Route::get('/event',                    [CmsController::class, 'event'])->name('event');
    Route::get('/event/create',             [CmsController::class, 'createEvent'])->name('event.create');
    Route::post('/event',                   [CmsController::class, 'storeEvent'])->name('event.store');
    Route::get('/event/{event}/edit',       [CmsController::class, 'editEvent'])->name('event.edit');
    Route::put('/event/{event}',            [CmsController::class, 'updateEvent'])->name('event.update');
    Route::delete('/event/{event}',         [CmsController::class, 'destroyEvent'])->name('event.destroy');

    // Banner
    Route::get('/banner',                   [CmsController::class, 'banner'])->name('banner');
    Route::get('/banner/create',            [CmsController::class, 'createBanner'])->name('banner.create');
    Route::post('/banner',                  [CmsController::class, 'storeBanner'])->name('banner.store');
    Route::get('/banner/{banner}/edit',     [CmsController::class, 'editBanner'])->name('banner.edit');
    Route::put('/banner/{banner}',          [CmsController::class, 'updateBanner'])->name('banner.update');
    Route::delete('/banner/{banner}',       [CmsController::class, 'destroyBanner'])->name('banner.destroy');

    // Layanan
    Route::get('/layanan',                  [CmsController::class, 'layanan'])->name('layanan');
    Route::get('/layanan/create',           [CmsController::class, 'createLayanan'])->name('layanan.create');
    Route::post('/layanan',                 [CmsController::class, 'storeLayanan'])->name('layanan.store');
    Route::get('/layanan/{layanan}/edit',   [CmsController::class, 'editLayanan'])->name('layanan.edit');
    Route::put('/layanan/{layanan}',        [CmsController::class, 'updateLayanan'])->name('layanan.update');
    Route::delete('/layanan/{layanan}',     [CmsController::class, 'destroyLayanan'])->name('layanan.destroy');

    // Galeri
    Route::get('/galeri',                       [CmsController::class, 'galeri'])->name('galeri');
    Route::post('/galeri',                      [CmsController::class, 'storeGaleri'])->name('galeri.store');
    Route::delete('/galeri/{galeri}',           [CmsController::class, 'destroyGaleri'])->name('galeri.destroy');

    // Kategori Artikel
    Route::get('/kategori-artikel',                       [CmsController::class, 'kategoriArtikel'])->name('kategori-artikel');
    Route::post('/kategori-artikel',                      [CmsController::class, 'storeKategoriArtikel'])->name('kategori-artikel.store');
    Route::delete('/kategori-artikel/{kategoriArtikel}',  [CmsController::class, 'destroyKategoriArtikel'])->name('kategori-artikel.destroy');

    // Kategori Galeri
    Route::get('/kategori-galeri',                        [CmsController::class, 'kategoriGaleri'])->name('kategori-galeri');
    Route::post('/kategori-galeri',                       [CmsController::class, 'storeKategoriGaleri'])->name('kategori-galeri.store');
    Route::delete('/kategori-galeri/{kategoriGaleri}',    [CmsController::class, 'destroyKategoriGaleri'])->name('kategori-galeri.destroy');

    // Informasi Terkini
    Route::get('/informasi',                    [CmsController::class, 'informasi'])->name('informasi');
    Route::get('/informasi/create',             [CmsController::class, 'createInformasi'])->name('informasi.create');
    Route::post('/informasi',                   [CmsController::class, 'storeInformasi'])->name('informasi.store');
    Route::get('/informasi/{informasi}/edit',   [CmsController::class, 'editInformasi'])->name('informasi.edit');
    Route::put('/informasi/{informasi}',        [CmsController::class, 'updateInformasi'])->name('informasi.update');
    Route::delete('/informasi/{informasi}',     [CmsController::class, 'destroyInformasi'])->name('informasi.destroy');

    // Guest Book
    Route::get('/guest-book',                           [CmsController::class, 'guestBook'])->name('guest-book');
    Route::get('/guest-book/{guestBook}',               [CmsController::class, 'showGuestBook'])->name('guest-book.show');
    Route::put('/guest-book/{guestBook}/mark',          [CmsController::class, 'markGuestBook'])->name('guest-book.mark');
    Route::delete('/guest-book/{guestBook}',            [CmsController::class, 'destroyGuestBook'])->name('guest-book.destroy');

    // Website Setting
    Route::get('/website-setting',          [CmsController::class, 'websiteSetting'])->name('website-setting');
    Route::put('/website-setting',          [CmsController::class, 'updateWebsiteSetting'])->name('website-setting.update');
});
