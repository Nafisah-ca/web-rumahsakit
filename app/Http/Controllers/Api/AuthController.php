<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login pasien via API, mengembalikan Sanctum token.
     * Endpoint: POST /api/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
            ->orWhere('email', $request->username)
            ->first();

        if (!$user || !Auth::getProvider()->validateCredentials($user, ['password' => $request->password])) {
            throw ValidationException::withMessages([
                'username' => ['Username/email atau password salah.'],
            ]);
        }

        if (!$user->isPasien()) {
            throw ValidationException::withMessages([
                'username' => ['Akun ini tidak memiliki akses ke API pasien.'],
            ]);
        }

        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'username' => ['Akun Anda tidak aktif. Hubungi admin.'],
            ]);
        }

        // Hapus token lama dengan nama sama supaya tidak menumpuk (opsional)
        $user->tokens()->where('name', 'api-token')->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'token'   => $token,
            'user'    => [
                'id'       => $user->id,
                'nama'     => $user->nama,
                'username' => $user->username,
                'email'    => $user->email,
                'role'     => $user->role,
                'has_pasien_profile' => $user->pasien()->exists(),
            ],
        ]);
    }

    /**
     * Logout (hapus token yang sedang dipakai).
     * Endpoint: POST /api/logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout berhasil.']);
    }
}
