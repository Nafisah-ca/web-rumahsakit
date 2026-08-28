<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use Illuminate\Support\Facades\Route;

// ─── Auth ─────────────────────────────────────────────
Route::post('/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    // ─── Booking / Pendaftaran Pasien ──────────────────
    Route::get('/dokter', [BookingController::class, 'dokterList'])->name('api.dokter.index');
    Route::get('/booking/jadwal', [BookingController::class, 'jadwal'])->name('api.booking.jadwal');
    Route::get('/booking', [BookingController::class, 'riwayat'])->name('api.booking.riwayat');
    Route::post('/booking', [BookingController::class, 'store'])->name('api.booking.store');
    Route::post('/booking/{janjiTemu}/batal', [BookingController::class, 'cancel'])->name('api.booking.cancel');
});
