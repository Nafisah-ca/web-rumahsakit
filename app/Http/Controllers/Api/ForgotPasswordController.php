<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirim ke email Anda.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email tidak ditemukan.',
        ], 404);
    }

    public function resetPassword(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'token' => ['required'],
        'password' => ['required', 'min:8', 'confirmed'],
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        }
    );

    if ($status === Password::PASSWORD_RESET) {
        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.',
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => __($status),
    ], 400);
}
}