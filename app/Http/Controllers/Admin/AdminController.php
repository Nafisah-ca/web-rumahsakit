<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pasien;
use App\Models\JanjiTemu;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Spesialisasi;
use App\Models\Layanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ─────────────────────────── DASHBOARD ───────────────────────────

    public function dashboard()
    {
        $stats = [
            'total_pasien'       => Pasien::count(),
            'total_dokter'       => Dokter::where('status', 'aktif')->count(),
            'booking_hari_ini'   => JanjiTemu::whereDate('tanggal_booking', today())->count(),
            'booking_menunggu'   => JanjiTemu::where('status', 'pending')->count(),
            'total_spesialisasi' => Spesialisasi::count(),
            'booking_bulan_ini'  => JanjiTemu::whereMonth('tanggal_booking', now()->month)
                                             ->whereYear('tanggal_booking', now()->year)->count(),
        ];

        // Grafik booking 7 hari terakhir
        $chartData   = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date          = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[]   = JanjiTemu::whereDate('tanggal_booking', $date)->count();
        }

        // Booking terbaru
        $recentBookings = JanjiTemu::with(['pasien.user', 'jadwalDokter.dokter'])
            ->orderByDesc('created_tm')
            ->limit(10)
            ->get();

        // Booking hari ini
        $bookingHariIni = JanjiTemu::with(['pasien.user', 'jadwalDokter.dokter'])
            ->whereDate('tanggal_booking', today())
            ->orderByDesc('created_tm')
            ->get();

        // Dokter aktif
        $doktersAktif = Dokter::with('spesialisasi')
            ->where('status', 'aktif')
            ->orderBy('nama_dokter')
            ->limit(6)
            ->get();

        // Status distribusi
        $statusCounts = JanjiTemu::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats', 'chartData', 'chartLabels', 'recentBookings', 'bookingHariIni', 'statusCounts', 'doktersAktif'
        ));
    }

    // ─────────────────────────── PROFILE & SETTING ───────────────────

    public function profile()
    {
        $user = Auth::user();
        return view('admin.setting.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama'  => 'required|string|max:150',
            'email' => 'required|email|max:150|unique:users,email,' . $user->id,
            'no_hp' => 'nullable|string|max:20',
            'foto'  => 'nullable|image|max:2048',
        ], [
            'nama.required'  => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan oleh akun lain.',
            'foto.image'     => 'File harus berupa gambar.',
            'foto.max'       => 'Ukuran foto maksimal 2MB.',
        ]);

        $data = [
            'nama'       => $request->nama,
            'email'      => $request->email,
            'no_hp'      => $request->no_hp,
            'updated_by' => $user->id,
        ];

        if ($request->hasFile('foto')) {
            if ($user->foto) Storage::disk('public')->delete($user->foto);
            $data['foto'] = $request->file('foto')->store('profile', 'public');
        }

        $user->update($data);

        return back()->with('success', 'Profile berhasil diperbarui.');
    }

    public function settingPassword()
    {
        return view('admin.setting.password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password_lama'              => 'required',
            'password_baru'              => 'required|min:8|confirmed',
            'password_baru_confirmation' => 'required',
        ], [
            'password_lama.required'              => 'Password lama wajib diisi.',
            'password_baru.required'              => 'Password baru wajib diisi.',
            'password_baru.min'                   => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed'             => 'Konfirmasi password baru tidak cocok.',
            'password_baru_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        if (!Hash::check($request->password_lama, $user->password)) {
            return back()->withErrors(['password_lama' => 'Password lama yang Anda masukkan salah.'])->withInput();
        }

        $user->update([
            'password'   => Hash::make($request->password_baru),
            'updated_by' => $user->id,
        ]);

        return back()->with('success', 'Password berhasil diperbarui. Silakan login ulang jika diperlukan.');
    }

    // ─────────────────────────── USERS ───────────────────────────────

    public function users(Request $request)
    {
        $query = User::query();
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%");
            });
        }
        if ($request->role) {
            $query->where('role', $request->role);
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }
        $users = $query->orderByDesc('created_tm')->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|in:admin,cms,pasien',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'nama'       => $request->nama,
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => $request->role,
            'no_hp'      => $request->no_hp,
            'status'     => $request->status ?? 'aktif',
            'created_by' => Auth::id(),
        ]);

        // Buat pasien otomatis jika role pasien
        if ($request->role === 'pasien') {
            Pasien::create([
                'user_id'        => $user->id,
                'no_rekam_medis' => Pasien::generateNoRekamMedis(),
                'nik'            => $request->nik ?? '0000000000000000',
                'jenis_kelamin'  => $request->jenis_kelamin ?? 'L',
                'tempat_lahir'   => $request->tempat_lahir ?? '-',
                'tanggal_lahir'  => $request->tanggal_lahir ?? now()->subYears(20)->toDateString(),
                'alamat'         => $request->alamat ?? '-',
                'created_by'     => Auth::id(),
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:admin,cms,pasien',
            'password' => 'nullable|min:6|confirmed',
            'no_hp'    => 'nullable|string|max:20',
        ]);

        $data = [
            'nama'       => $request->nama,
            'username'   => $request->username,
            'email'      => $request->email,
            'role'       => $request->role,
            'no_hp'      => $request->no_hp,
            'status'     => $request->status ?? 'aktif',
            'updated_by' => Auth::id(),
        ];
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->update(['deleted_by' => Auth::id()]);
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }

    // ─────────────────────────── PASIEN ──────────────────────────────

    public function pasien(Request $request)
    {
        $status = $request->get('status', ''); // '' | 'aktif' | 'nonaktif'

        // withTrashed() agar bisa query semua data termasuk yang soft-deleted
        $query = Pasien::withTrashed()->with('user');

        // Filter status via dropdown
        if ($status === 'aktif') {
            $query->whereNull('deleted_tm');
        } elseif ($status === 'nonaktif') {
            $query->whereNotNull('deleted_tm');
        }
        // Jika '' (Semua Status) — tidak ada filter tambahan

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('no_rekam_medis', 'like', "%{$request->search}%")
                  ->orWhere('nik', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('nama', 'like', "%{$request->search}%")
                                                    ->orWhere('no_hp', 'like', "%{$request->search}%"));
            });
        }

        $pasiens = $query->orderByDesc('created_tm')->paginate(15)->withQueryString();
        return view('admin.pasien.index', compact('pasiens', 'status'));
    }

    public function createPasien()
    {
        $users     = User::where('role', 'pasien')->whereDoesntHave('pasien')->get();
        $penjamins = \App\Models\Penjamin::where('status','aktif')->with('tipePenjamin')->get();
        return view('admin.pasien.create', compact('users', 'penjamins'));
    }

    public function storePasien(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'nik'           => 'required|string|size:16|unique:pasien,nik',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|string',
        ]);

        Pasien::create(array_merge(
            $request->only(['user_id','nik','jenis_kelamin','tempat_lahir','tanggal_lahir',
                           'alamat','golongan_darah','agama','pekerjaan','penjamin_id','nomor_penjamin']),
            ['no_rekam_medis' => Pasien::generateNoRekamMedis(), 'created_by' => Auth::id()]
        ));
        return redirect()->route('admin.pasien')->with('success', 'Data pasien berhasil disimpan.');
    }

    public function editPasien(Pasien $pasien)
    {
        $penjamins = \App\Models\Penjamin::where('status','aktif')->with('tipePenjamin')->get();
        return view('admin.pasien.edit', compact('pasien', 'penjamins'));
    }

    public function updatePasien(Request $request, Pasien $pasien)
    {
        $request->validate([
            'nik'           => 'required|string|size:16|unique:pasien,nik,' . $pasien->id,
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir'  => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat'        => 'required|string',
        ]);

        $pasien->update(array_merge(
            $request->only(['nik','jenis_kelamin','tempat_lahir','tanggal_lahir',
                           'alamat','golongan_darah','agama','pekerjaan','penjamin_id','nomor_penjamin']),
            ['updated_by' => Auth::id()]
        ));
        return redirect()->route('admin.pasien')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function showPasien(Pasien $pasien)
    {
        $pasien->load(['user', 'janjiTemus.jadwalDokter.dokter', 'penjamin.tipePenjamin']);
        return view('admin.pasien.show', compact('pasien'));
    }

    public function destroyPasien(Pasien $pasien)
    {
        $pasien->update(['deleted_by' => Auth::id()]);
        $pasien->delete();
        // Nonaktifkan akun user terkait
        if ($pasien->user) {
            $pasien->user->update(['status' => 'nonaktif']);
        }
        return back()->with('success', 'Data pasien berhasil dinonaktifkan.');
    }

    public function restorePasien(int $id)
    {
        $pasien = Pasien::withTrashed()->findOrFail($id);
        $pasien->restore();
        $pasien->update(['deleted_by' => null, 'updated_by' => Auth::id()]);
        // Aktifkan kembali akun user terkait
        if ($pasien->user) {
            $pasien->user->update(['status' => 'aktif']);
        }
        return back()->with('success', 'Data pasien berhasil dipulihkan.');
    }

    // ─────────────────────────── JANJI TEMU ──────────────────────────

    public function janjiTemu(Request $request)
    {
        $query = JanjiTemu::with(['pasien.user', 'jadwalDokter.dokter.spesialisasi']);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('pasien.user', fn($u) => $u->where('nama', 'like', "%{$request->search}%"))
                  ->orWhere('kode_booking', 'like', "%{$request->search}%")
                  ->orWhere('id', 'like', "%{$request->search}%");
            });
        }
        if ($request->status)    $query->where('status', $request->status);
        if ($request->dokter_id) $query->whereHas('jadwalDokter', fn($j) => $j->where('dokter_id', $request->dokter_id));
        if ($request->tanggal)   $query->whereDate('tanggal_booking', $request->tanggal);

        $bookings = $query->orderByDesc('tanggal_booking')->paginate(15)->withQueryString();
        $dokters  = Dokter::where('status', 'aktif')->orderBy('nama_dokter')->get();
        return view('admin.booking.index', compact('bookings', 'dokters'));
    }

    public function showBooking(JanjiTemu $janjiTemu)
    {
        $janjiTemu->load(['pasien.user', 'jadwalDokter.dokter.spesialisasi']);
        return view('admin.booking.show', compact('janjiTemu'));
    }

    public function updateStatusBooking(Request $request, JanjiTemu $janjiTemu)
    {
        $rules = ['status' => 'required|in:pending,approved,completed,cancelled'];
        if ($request->status === 'cancelled') {
            $rules['alasan_pembatalan'] = 'required|string|min:3|max:500';
        }
        $request->validate($rules, [
            'alasan_pembatalan.required' => 'Alasan pembatalan wajib diisi ketika membatalkan booking.',
            'alasan_pembatalan.min'      => 'Alasan minimal 3 karakter.',
        ]);

        $data = [
            'status'     => $request->status,
            'updated_by' => Auth::id(),
        ];

        if ($request->status === 'cancelled') {
            $data['alasan_pembatalan']  = $request->alasan_pembatalan;
            $data['tanggal_pembatalan'] = now();
            $data['dibatalkan_oleh']    = 'admin';
        }

        $janjiTemu->update($data);
        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function destroyBooking(JanjiTemu $janjiTemu)
    {
        $janjiTemu->deleted_by = Auth::id();
        $janjiTemu->save();
        $janjiTemu->delete();
        return back()->with('success', 'Data booking berhasil dihapus.');
    }

    // ─────────────────────────── DOKTER ──────────────────────────────

    public function dokter(Request $request)
    {
        $query = Dokter::with('spesialisasi');
        if ($request->search) {
            $query->where('nama_dokter', 'like', "%{$request->search}%");
        }
        if ($request->spesialis_id) {
            $query->where('spesialis_id', $request->spesialis_id);
        }
        $dokters       = $query->orderBy('nama_dokter')->paginate(15)->withQueryString();
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        return view('admin.dokter.index', compact('dokters', 'spesialisasis'));
    }

    public function createDokter()
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        return view('admin.dokter.create', compact('spesialisasis'));
    }

    public function storeDokter(Request $request)
    {
        $request->validate([
            'nama_dokter'  => 'required|string|max:100',
            'spesialis_id' => 'required|exists:spesialis,id',
            'tipe_dokter'  => 'required|in:spesialis,umum,lainnya',
            'sip'          => 'required|string|max:100|unique:dokter,sip',
            'email'        => 'required|email|unique:dokter,email',
            'no_hp'        => 'required|string|max:20',
            'foto'         => 'nullable|image|max:2048',
            'foto_banner'  => 'nullable|image|max:3072',
            'status'       => 'nullable|in:aktif,nonaktif',
        ]);

        $data = $request->except(['_token', 'foto', 'foto_banner']);
        $data['status']     = $request->status ?? 'aktif';
        $data['created_by'] = Auth::id();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('dokter', 'public');
        }
        if ($request->hasFile('foto_banner')) {
            $data['foto_banner'] = $request->file('foto_banner')->store('dokter/banner', 'public');
        }
        Dokter::create($data);
        return redirect()->route('admin.dokter')->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function editDokter(Dokter $dokter)
    {
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        return view('admin.dokter.edit', compact('dokter', 'spesialisasis'));
    }

    public function updateDokter(Request $request, Dokter $dokter)
    {
        $request->validate([
            'nama_dokter'  => 'required|string|max:100',
            'spesialis_id' => 'required|exists:spesialis,id',
            'tipe_dokter'  => 'required|in:spesialis,umum,lainnya',
            'sip'          => 'required|string|max:100|unique:dokter,sip,' . $dokter->id,
            'email'        => 'required|email|unique:dokter,email,' . $dokter->id,
            'no_hp'        => 'required|string|max:20',
            'foto'         => 'nullable|image|max:2048',
            'foto_banner'  => 'nullable|image|max:3072',
            'status'       => 'nullable|in:aktif,nonaktif',
        ]);

        $data = $request->except(['_token', '_method', 'foto', 'foto_banner']);
        $data['updated_by'] = Auth::id();
        if ($request->hasFile('foto')) {
            if ($dokter->foto && !str_starts_with($dokter->foto, 'images/')) {
                Storage::disk('public')->delete($dokter->foto);
            }
            $data['foto'] = $request->file('foto')->store('dokter', 'public');
        }
        if ($request->hasFile('foto_banner')) {
            if ($dokter->foto_banner) Storage::disk('public')->delete($dokter->foto_banner);
            $data['foto_banner'] = $request->file('foto_banner')->store('dokter/banner', 'public');
        }
        $dokter->update($data);
        return redirect()->route('admin.dokter')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroyDokter(Dokter $dokter)
    {
        $dokter->update(['deleted_by' => Auth::id()]);
        $dokter->delete();
        return back()->with('success', 'Dokter berhasil dihapus.');
    }

    // ─────────────────────────── JADWAL DOKTER ───────────────────────

    public function jadwalDokter(Request $request)
    {
        // Hanya jadwal mingguan (tanggal_praktek NULL), tidak include soft-deleted
        $query = JadwalDokter::with(['dokter.spesialisasi', 'spesialisasi'])
            ->whereNull('tanggal_praktek'); // hanya mingguan

        if ($request->dokter_id) {
            $query->where('dokter_id', $request->dokter_id);
        }

        $query->orderByRaw("FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')")
              ->orderBy('jam_mulai');

        $totalMendatang = JadwalDokter::whereNull('tanggal_praktek')->count();
        $totalRiwayat   = 0;
        $totalSemua     = JadwalDokter::whereNull('tanggal_praktek')->count();

        $jadwals = $query->paginate(50)->withQueryString();
        $dokters = Dokter::where('status', 'aktif')->orderBy('nama_dokter')->get();

        return view('admin.jadwal.index', compact(
            'jadwals', 'dokters', 'totalMendatang', 'totalRiwayat', 'totalSemua'
        ));
    }

    public function createJadwal()
    {
        $dokters       = Dokter::where('status', 'aktif')->orderBy('nama_dokter')->get();
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        $hariOptions   = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('admin.jadwal.create', compact('dokters', 'spesialisasis', 'hariOptions'));
    }

    public function storeJadwal(Request $request)
    {
        $request->validate([
            'dokter_id'    => 'required|exists:dokter,id',
            'spesialis_id' => 'required|exists:spesialis,id',
            'hari'         => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'    => 'required',
            'jam_selesai'  => 'required|after:jam_mulai',
            'kuota'        => 'required|integer|min:1|max:200',
            'status'       => 'nullable|in:aktif,nonaktif',
        ]);

        // Cegah duplikat: dokter + hari yang sama sudah ada
        $sudahAda = JadwalDokter::whereNull('tanggal_praktek')
            ->where('dokter_id', $request->dokter_id)
            ->where('hari', $request->hari)
            ->exists();

        if ($sudahAda) {
            return back()->withInput()
                ->withErrors(['hari' => 'Dokter ini sudah memiliki jadwal mingguan untuk hari ' . $request->hari . '.']);
        }

        JadwalDokter::create([
            'dokter_id'       => $request->dokter_id,
            'spesialis_id'    => $request->spesialis_id,
            'tanggal_praktek' => null, // selalu mingguan
            'hari'            => $request->hari,
            'jam_mulai'       => $request->jam_mulai,
            'jam_selesai'     => $request->jam_selesai,
            'kuota'           => $request->kuota,
            'status'          => $request->status ?? 'aktif',
            'created_by'      => Auth::id(),
        ]);

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal mingguan berhasil ditambahkan.');
    }

    public function editJadwal(JadwalDokter $jadwalDokter)
    {
        $dokters       = Dokter::where('status', 'aktif')->orderBy('nama_dokter')->get();
        $spesialisasis = Spesialisasi::orderBy('nama_spesialis')->get();
        $hariOptions   = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        return view('admin.jadwal.edit', compact('jadwalDokter', 'dokters', 'spesialisasis', 'hariOptions'));
    }

    public function updateJadwal(Request $request, JadwalDokter $jadwalDokter)
    {
        $request->validate([
            'dokter_id'       => 'required|exists:dokter,id',
            'spesialis_id'    => 'required|exists:spesialis,id',
            'tanggal_praktek' => 'nullable|date',
            'hari'            => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'       => 'required',
            'jam_selesai'     => 'required|after:jam_mulai',
            'kuota'           => 'required|integer|min:1|max:200',
            'status'          => 'nullable|in:aktif,nonaktif',
        ]);

        $jadwalDokter->update(array_merge(
            $request->except(['_token', '_method']),
            [
                'tanggal_praktek' => $request->tanggal_praktek ?: null,
                'updated_by'      => Auth::id(),
            ]
        ));

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroyJadwal(JadwalDokter $jadwalDokter)
    {
        $jadwalDokter->update(['deleted_by' => Auth::id()]);
        $jadwalDokter->delete();
        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    // ─────────────────────────── LAPORAN ─────────────────────────────

    public function laporan(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $bookingPerStatus = JanjiTemu::selectRaw('status, count(*) as total')
            ->whereMonth('tanggal_booking', $bulan)
            ->whereYear('tanggal_booking', $tahun)
            ->groupBy('status')
            ->pluck('total', 'status');

        $bookingPerDokter = JanjiTemu::with('jadwalDokter.dokter')
            ->selectRaw('jadwal_dokter_id, count(*) as total')
            ->whereMonth('tanggal_booking', $bulan)
            ->whereYear('tanggal_booking', $tahun)
            ->groupBy('jadwal_dokter_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Daily chart for selected month
        $daysInMonth = Carbon::create($tahun, $bulan)->daysInMonth;
        $dailyData   = [];
        $dailyLabels = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $dailyLabels[] = $d;
            $dailyData[]   = JanjiTemu::whereDate('tanggal_booking', Carbon::create($tahun, $bulan, $d))->count();
        }

        return view('admin.laporan', compact(
            'bookingPerStatus', 'bookingPerDokter',
            'dailyData', 'dailyLabels', 'bulan', 'tahun'
        ));
    }

    // ─────────────────────────── MCU ─────────────────────────────────

    public function mcu(Request $request)
    {
        $query = \App\Models\PendaftaranMcu::query();

        if ($request->paket)  $query->where('paket', $request->paket);
        if ($request->status) $query->where('status', $request->status);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%")
                  ->orWhere('kode_pendaftaran', 'like', "%{$request->search}%")
                  ->orWhere('no_hp', 'like', "%{$request->search}%");
            });
        }

        $pendaftarans  = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $totalAll      = \App\Models\PendaftaranMcu::count();
        $totalMenunggu = \App\Models\PendaftaranMcu::where('status', 'menunggu')->count();
        $totalKonfirm  = \App\Models\PendaftaranMcu::where('status', 'dikonfirmasi')->count();
        $totalSelesai  = \App\Models\PendaftaranMcu::where('status', 'selesai')->count();

        return view('admin.mcu.index', compact(
            'pendaftarans', 'totalAll', 'totalMenunggu', 'totalKonfirm', 'totalSelesai'
        ));
    }

    public function updateStatusMcu(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:menunggu,dikonfirmasi,selesai,dibatalkan']);
        $mcu = \App\Models\PendaftaranMcu::findOrFail($id);
        $mcu->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    // ─────────────────────────── PENGUNJUNG ──────────────────────────

    public function pengunjung(Request $request)
    {
        $query = \App\Models\PageVisit::query();

        if ($request->tanggal) {
            $query->whereDate('visited_at', $request->tanggal);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('ip_address', 'like', "%{$request->search}%")
                  ->orWhere('page_url', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%");
            });
        }

        $visits      = $query->orderByDesc('visited_at')->paginate(30)->withQueryString();
        $totalToday  = \App\Models\PageVisit::whereDate('visited_at', today())->count();
        $totalAll    = \App\Models\PageVisit::count();
        $uniqueToday = \App\Models\PageVisit::whereDate('visited_at', today())
                            ->distinct('ip_address')->count('ip_address');

        // Jumlah kunjungan yang sudah terdeteksi lokasinya
        $totalWithLocation = \App\Models\PageVisit::whereNotNull('latitude')->count();

        // Top 10 halaman terbanyak dikunjungi (30 hari terakhir)
        $topPages = \App\Models\PageVisit::selectRaw('page_url, count(*) as total')
            ->where('visited_at', '>=', now()->subDays(30))
            ->groupBy('page_url')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top kota pengunjung (30 hari terakhir)
        $topCities = \App\Models\PageVisit::selectRaw('city, count(*) as total')
            ->whereNotNull('city')
            ->where('visited_at', '>=', now()->subDays(30))
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Kunjungan dengan lokasi untuk peta (maks 200 titik terbaru)
        $locationPoints = \App\Models\PageVisit::select('latitude', 'longitude', 'city', 'page_url', 'visited_at')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderByDesc('visited_at')
            ->limit(200)
            ->get();

        // Grafik kunjungan 14 hari terakhir
        $chartLabels = [];
        $chartData   = [];
        for ($i = 13; $i >= 0; $i--) {
            $day           = now()->subDays($i);
            $chartLabels[] = $day->format('d/m');
            $chartData[]   = \App\Models\PageVisit::whereDate('visited_at', $day)->count();
        }

        return view('admin.pengunjung', compact(
            'visits', 'totalToday', 'totalAll', 'uniqueToday', 'totalWithLocation',
            'topPages', 'topCities', 'locationPoints',
            'chartLabels', 'chartData'
        ));
    }
}
