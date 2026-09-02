<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\McuController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Cms\CmsController;
use App\Http\Controllers\Admin\SpesialisasiController;
use App\Http\Controllers\Admin\PenjaminController;
use App\Http\Controllers\EventBookingController;

// ─────────────────────────── PUBLIC PORTAL ───────────────────────────────────

Route::get('/',                [HospitalController::class, 'home'])->name('home');
Route::get('/tentang-kami',    [HospitalController::class, 'tentang'])->name('tentang');
Route::get('/pelayanan',          [HospitalController::class, 'layanan'])->name('layanan');
Route::get('/pelayanan/{id}',     [HospitalController::class, 'layananByKategori'])->name('layanan.by-kategori');
Route::get('/dokter',          [HospitalController::class, 'dokter'])->name('dokter');
Route::get('/dokter/online',   [HospitalController::class, 'dokterOnline'])->name('dokter.online');
Route::get('/dokter/{spSlug}', [HospitalController::class, 'dokterBySpesialis'])->name('dokter.by-spesialis');
Route::get('/fasilitas',       [HospitalController::class, 'fasilitas'])->name('fasilitas');
Route::get('/kontak',          [HospitalController::class, 'kontak'])->name('kontak');
Route::post('/kontak',         [HospitalController::class, 'storeKontak'])->name('kontak.store');
Route::post('/ulasan',         [HospitalController::class, 'storeUlasan'])->name('ulasan.store');
Route::get('/ulasan',          [HospitalController::class, 'ulasanPublic'])->name('ulasan.public');
Route::get('/promo',              [HospitalController::class, 'promo'])->name('promo');
Route::get('/promo/{promo}',      [HospitalController::class, 'promoDetail'])->name('promo.detail');
Route::get('/event',              [HospitalController::class, 'event'])->name('event');
Route::get('/event/{event}',      [HospitalController::class, 'eventDetail'])->name('event.detail');
Route::get('/informasi',          [HospitalController::class, 'informasiPublic'])->name('informasi');
Route::get('/informasi/{informasi}', [HospitalController::class, 'informasiDetail'])->name('informasi.detail');
Route::get('/artikel',         [HospitalController::class, 'artikel'])->name('artikel');
Route::get('/artikel/{slug}',  [HospitalController::class, 'artikelDetail'])->name('artikel.detail');
Route::get('/medical-checkup',        [HospitalController::class, 'mcu'])->name('mcu');
Route::get('/medical-checkup/daftar', [McuController::class, 'create'])->name('mcu.daftar');
Route::post('/medical-checkup/daftar',[McuController::class, 'store'])->name('mcu.store');
Route::get('/medical-checkup/sukses', [McuController::class, 'sukses'])->name('mcu.sukses');
Route::get('/live-antrian',    [HospitalController::class, 'liveAntrian'])->name('live.antrian');
Route::get('/api/live-antrian',[HospitalController::class, 'liveAntrianJson'])->name('live.antrian.json');

// Halaman Legal
Route::get('/kebijakan-privasi', [HospitalController::class, 'kebijakanPrivasi'])->name('kebijakan-privasi');
Route::get('/syarat-ketentuan',  [HospitalController::class, 'syaratKetentuan'])->name('syarat-ketentuan');
Route::get('/faq',               [HospitalController::class, 'faq'])->name('faq');

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

    // Booking Event
    Route::get('/booking-event',                             [EventBookingController::class, 'riwayat'])->name('booking-event.riwayat');
    Route::get('/booking-event/{event}',                     [EventBookingController::class, 'create'])->name('booking-event.create');
    Route::post('/booking-event/{event}',                    [EventBookingController::class, 'store'])->name('booking-event.store');
    Route::post('/booking-event/{bookingEvent}/batal',       [EventBookingController::class, 'cancel'])->name('booking-event.cancel');
});

// ─────────────────────────── ADMIN ────────────────────────────────────────────

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Profile & Setting akun admin
    Route::get('/profile',                   [AdminController::class, 'profile'])->name('profile');
    Route::put('/profile',                   [AdminController::class, 'updateProfile'])->name('profile.update');
    Route::get('/setting/password',          [AdminController::class, 'settingPassword'])->name('setting.password');
    Route::put('/setting/password',          [AdminController::class, 'updatePassword'])->name('setting.password.update');

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

    // Portal CMS (halaman perantara)
    Route::get('/cms-portal', function () {
        return view('admin.cms-portal');
    })->name('cms-portal');

    // CMS Auth — login verifikasi dari dalam admin
    Route::get('/cms-login',  [\App\Http\Controllers\Admin\CmsAuthController::class, 'showLogin'])->name('cms-login');
    Route::post('/cms-login', [\App\Http\Controllers\Admin\CmsAuthController::class, 'login'])->name('cms-login.post');
    Route::post('/cms-logout',[\App\Http\Controllers\Admin\CmsAuthController::class, 'logout'])->name('cms-logout');

    // Statistik Pengunjung
    Route::get('/pengunjung', [AdminController::class, 'pengunjung'])->name('pengunjung');

    // Medical Check-Up
    Route::get('/mcu',                    [AdminController::class, 'mcu'])->name('mcu');
    Route::put('/mcu/{id}/status',        [AdminController::class, 'updateStatusMcu'])->name('mcu.status');

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
// Admin juga bisa akses CMS (role:admin,cms)
Route::middleware(['auth', 'cms.verified', 'last.activity'])->prefix('cms')->name('cms.')->group(function () {

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
    Route::get('/event/{event}/peserta',    [CmsController::class, 'pesertaEvent'])->name('event.peserta');
    Route::put('/event/{event}/peserta/{bookingEvent}', [CmsController::class, 'updateStatusPeserta'])->name('event.peserta.status');

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

    // Kategori Layanan
    Route::get('/kategori-layanan',                              [CmsController::class, 'kategoriLayanan'])->name('kategori-layanan');
    Route::post('/kategori-layanan',                             [CmsController::class, 'storeKategoriLayanan'])->name('kategori-layanan.store');
    Route::put('/kategori-layanan/{kategoriLayanan}',            [CmsController::class, 'updateKategoriLayanan'])->name('kategori-layanan.update');
    Route::delete('/kategori-layanan/{kategoriLayanan}',         [CmsController::class, 'destroyKategoriLayanan'])->name('kategori-layanan.destroy');

    // Kategori Artikel
    Route::get('/kategori-artikel',                       [CmsController::class, 'kategoriArtikel'])->name('kategori-artikel');
    Route::post('/kategori-artikel',                      [CmsController::class, 'storeKategoriArtikel'])->name('kategori-artikel.store');
    Route::delete('/kategori-artikel/{kategoriArtikel}',  [CmsController::class, 'destroyKategoriArtikel'])->name('kategori-artikel.destroy');

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

    // Ulasan Pasien
    Route::get('/ulasan',                     [CmsController::class, 'ulasan'])->name('ulasan');
    Route::get('/ulasan/{ulasan}',            [CmsController::class, 'showUlasan'])->name('ulasan.show');
    Route::put('/ulasan/{ulasan}/mark',       [CmsController::class, 'markUlasan'])->name('ulasan.mark');
    Route::delete('/ulasan/{ulasan}',         [CmsController::class, 'destroyUlasan'])->name('ulasan.destroy'); 

    // Website Setting (redirect ke identitas-rs untuk backward compatibility)
    Route::get('/website-setting', fn() => redirect()->route('cms.identitas-rs'))->name('website-setting');

    // Pengaturan — dipecah per bagian
    Route::get('/identitas-rs',          [CmsController::class, 'identitasRs'])->name('identitas-rs');
    Route::put('/identitas-rs',          [CmsController::class, 'updateIdentitasRs'])->name('identitas-rs.update');

    Route::get('/kontak-lokasi',         [CmsController::class, 'kontakLokasi'])->name('kontak-lokasi');
    Route::put('/kontak-lokasi',         [CmsController::class, 'updateKontakLokasi'])->name('kontak-lokasi.update');

    Route::get('/logo-tampilan',         [CmsController::class, 'logoTampilan'])->name('logo-tampilan');
    Route::put('/logo-tampilan',         [CmsController::class, 'updateLogoTampilan'])->name('logo-tampilan.update');

    Route::get('/statistik',             [CmsController::class, 'statistik'])->name('statistik');
    Route::put('/statistik',             [CmsController::class, 'updateStatistik'])->name('statistik.update');

    Route::get('/sosial-media',          [CmsController::class, 'sosialMedia'])->name('sosial-media');
    Route::put('/sosial-media',          [CmsController::class, 'updateSosialMedia'])->name('sosial-media.update');

    Route::get('/footer',                [CmsController::class, 'footerSetting'])->name('footer');
    Route::put('/footer',                [CmsController::class, 'updateFooterSetting'])->name('footer.update');

    // Kebijakan Privasi (CMS editor)
    Route::get('/kebijakan-privasi',        [CmsController::class, 'privacyPolicyEditor'])->name('privacy-policy');
    Route::put('/kebijakan-privasi',        [CmsController::class, 'updatePrivacyPolicy'])->name('privacy-policy.update');

    // Syarat & Ketentuan (CMS editor)
    Route::get('/syarat-ketentuan',         [CmsController::class, 'syaratKetentuanEditor'])->name('syarat-ketentuan');
    Route::put('/syarat-ketentuan',         [CmsController::class, 'updateSyaratKetentuan'])->name('syarat-ketentuan.update');

    // FAQ
    Route::get('/faq',                      [CmsController::class, 'faq'])->name('faq');
    Route::get('/faq/create',               [CmsController::class, 'createFaq'])->name('faq.create');
    Route::post('/faq',                     [CmsController::class, 'storeFaq'])->name('faq.store');
    Route::get('/faq/{faq}/edit',           [CmsController::class, 'editFaq'])->name('faq.edit');
    Route::put('/faq/{faq}',                [CmsController::class, 'updateFaq'])->name('faq.update');
    Route::delete('/faq/{faq}',             [CmsController::class, 'destroyFaq'])->name('faq.destroy');

    // Akreditasi
    Route::get('/akreditasi',                       [CmsController::class, 'akreditasi'])->name('akreditasi');
    Route::get('/akreditasi/create',                [CmsController::class, 'createAkreditasi'])->name('akreditasi.create');
    Route::post('/akreditasi',                      [CmsController::class, 'storeAkreditasi'])->name('akreditasi.store');
    Route::get('/akreditasi/{akreditasi}/edit',     [CmsController::class, 'editAkreditasi'])->name('akreditasi.edit');
    Route::put('/akreditasi/{akreditasi}',          [CmsController::class, 'updateAkreditasi'])->name('akreditasi.update');
    Route::delete('/akreditasi/{akreditasi}',       [CmsController::class, 'destroyAkreditasi'])->name('akreditasi.destroy');

    // Antrian Poli
    Route::get('/antrian-poli',  [\App\Http\Controllers\Cms\AntrianPoliController::class, 'index'])->name('antrian-poli');
    Route::put('/antrian-poli',  [\App\Http\Controllers\Cms\AntrianPoliController::class, 'update'])->name('antrian-poli.update');

    // Profile & Password
    Route::get('/profile',                  [CmsController::class, 'profile'])->name('profile');
    Route::put('/profile',                  [CmsController::class, 'updateProfile'])->name('profile.update');
    Route::get('/setting/password',         [CmsController::class, 'settingPassword'])->name('setting.password');
    Route::put('/setting/password',         [CmsController::class, 'updatePassword'])->name('setting.password.update');

    // Page Banner
    Route::get('/page-banner',                      [CmsController::class, 'pageBanner'])->name('page-banner');
    Route::get('/page-banner/{pageBanner}/edit',    [CmsController::class, 'editPageBanner'])->name('page-banner.edit');
    Route::put('/page-banner/{pageBanner}',         [CmsController::class, 'updatePageBanner'])->name('page-banner.update');
});
