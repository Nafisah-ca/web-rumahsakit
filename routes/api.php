<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ForgotPasswordController;

Route::post('/forgot-password', [
    ForgotPasswordController::class,
    'forgotPassword'
]);

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);