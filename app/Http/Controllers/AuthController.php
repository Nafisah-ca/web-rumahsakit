<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Pasien;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        $user = Auth::user();

        // Update: is_active → status
        if ($user->status !== 'aktif') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda dinonaktifkan. Hubungi administrator.']);
        }

        // Update last_login
        $user->update(['last_login' => now()]);

        // Catat log login untuk admin & cms
        if (in_array($user->role, ['admin', 'cms'])) {
            \App\Models\LoginLog::create([
                'user_id'    => $user->id,
                'ip_address' => $request->ip(),
                'login_at'   => now(),
            ]);
        }

        // Sinkronisasi lokasi jadwal sholat jika pasien memiliki alamat di profilnya
        if ($user->role === 'pasien' && $user->pasien?->alamat) {
            $matchedCity = \App\Services\JadwalSholatService::detectCityFromAddress($user->pasien->alamat);
            if ($matchedCity) {
                session(['user_sholat_city' => $matchedCity]);
            }
        }

        $request->session()->regenerate();

        return $this->redirectByRole($user);
    }

    /** Halaman registrasi pasien */
    public function showRegister()
    {
        if (Auth::check()) return $this->redirectByRole(Auth::user());
        return view('auth.register');
    }

    /** Proses registrasi pasien */
    public function register(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:6|confirmed',
            'telepon'       => 'nullable|string|max:20',
            'nik'           => 'required|string|digits:16|unique:pasien,nik',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir'  => 'required|string|max:100',
            'alamat'        => 'required|string',
        ], [
            'name.required'         => 'Nama lengkap wajib diisi.',
            'email.unique'          => 'Email sudah terdaftar. Silakan masuk atau gunakan email lain.',
            'nik.required'          => 'NIK wajib diisi.',
            'nik.digits'            => 'NIK harus 16 digit angka.',
            'nik.unique'            => 'NIK sudah terdaftar.',
            'jenis_kelamin.required'=> 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required'=> 'Tanggal lahir wajib diisi.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'alamat.required'       => 'Alamat wajib diisi.',
        ]);

        // Generate username unik dari nama + angka random
        $baseUsername = Str::slug(explode(' ', $request->name)[0], '');
        $username = $baseUsername . rand(100, 9999);
        // Pastikan username unik
        while (\App\Models\User::where('username', $username)->exists()) {
            $username = $baseUsername . rand(100, 9999);
        }

        $user = User::create([
            'username'  => $username,
            'nama'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'no_hp'     => $request->telepon,
            'role'      => 'pasien',
            'status'    => 'aktif',
        ]);

        Pasien::create([
            'user_id'        => $user->id,
            'no_rekam_medis' => Pasien::generateNoRekamMedis(),
            'nik'            => $request->nik,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'alamat'         => $request->alamat,
            'created_by'     => $user->id,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('portal.profil')
            ->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->nama);
    }

    public function logout(Request $request)
    {
        // Set last_activity null saat logout → langsung offline
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'cms'])) {
            Auth::user()->update(['last_activity' => null]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda berhasil keluar.');
    }

    public function dashboard()
    {
        return $this->redirectByRole(Auth::user());
    }

    /** Profil pasien (portal) */
    public function portalProfil()
    {
        $pasien = Auth::user()->pasien()->with('penjamin')->first();
        return view('portal.profil', compact('pasien'));
    }

    public function updateProfil(Request $request)
    {
        $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'telepon'       => 'nullable|string|max:20',
            'nik'           => 'required|string|max:16',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir'  => 'required|string|max:100',
            'alamat'        => 'required|string|max:500',
            'jenis_kelamin' => 'required|in:L,P',
            'penjamin_id'   => 'nullable|exists:penjamin,id',
            'nomor_penjamin'=> 'nullable|string|max:100',
            'foto'          => 'nullable|image|max:2048',
        ]);

        $user   = Auth::user();
        $pasien = $user->pasien;

        // Update foto profil jika ada
        if ($request->hasFile('foto')) {
            if ($user->foto) \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto);
            $user->foto = $request->file('foto')->store('profile', 'public');
        }

        // Update user data
        $user->nama  = $request->nama_lengkap;
        $user->no_hp = $request->telepon;
        $user->save();

        $pasienData = [
            'nik'            => $request->nik,
            'tempat_lahir'   => $request->tempat_lahir,
            'tanggal_lahir'  => $request->tanggal_lahir,
            'alamat'         => $request->alamat,
            'jenis_kelamin'  => $request->jenis_kelamin,
            'agama'          => $request->agama,
            'pekerjaan'      => $request->pekerjaan,
            'golongan_darah' => $request->golongan_darah,
            'penjamin_id'    => $request->penjamin_id ?: null,
            'nomor_penjamin' => $request->nomor_penjamin,
        ];

        if ($pasien) {
            $pasien->update(array_merge($pasienData, ['updated_by' => $user->id]));
        } else {
            Pasien::create(array_merge($pasienData, [
                'user_id'        => $user->id,
                'no_rekam_medis' => Pasien::generateNoRekamMedis(),
                'created_by'     => $user->id,
            ]));
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function redirectByRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'cms'   => redirect()->route('cms.dashboard'),
            default => redirect()->route('portal.profil'),
        };
    }
}
