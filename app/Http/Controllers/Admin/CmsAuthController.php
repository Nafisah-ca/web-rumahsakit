<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CmsAuthController extends Controller
{
    /** Tampilkan form login CMS */
    public function showLogin()
    {
        // Kalau sudah verified, langsung ke CMS
        if (session('cms_verified')) {
            return redirect()->route('cms.dashboard');
        }
        return view('admin.cms-login');
    }

    /** Verifikasi kredensial akun CMS */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email CMS wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password CMS wajib diisi.',
        ]);

        // Cari user dengan role cms yang emailnya cocok
        $cmsUser = User::where('email', $request->email)
            ->where('role', 'cms')
            ->where('status', 'aktif')
            ->first();

        if (!$cmsUser || !Hash::check($request->password, $cmsUser->password)) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password CMS tidak valid.']);
        }

        // Simpan flag di session — admin tetap login sebagai admin
        session([
            'cms_verified'      => true,
            'cms_user_id'       => $cmsUser->id,
            'cms_user_nama'     => $cmsUser->nama,
            'cms_user_email'    => $cmsUser->email,
        ]);

        return redirect()->route('cms.dashboard')
            ->with('success', 'Berhasil masuk ke CMS sebagai ' . $cmsUser->nama . '.');
    }

    /** Keluar dari CMS (hapus flag, tetap login sebagai admin) */
    public function logout()
    {
        session()->forget(['cms_verified', 'cms_user_id', 'cms_user_nama', 'cms_user_email']);
        return redirect()->route('admin.cms-login')
            ->with('success', 'Anda telah keluar dari CMS.');
    }
}
