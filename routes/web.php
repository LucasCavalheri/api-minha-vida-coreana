<?php

use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Password Reset
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/forgot-password', [PasswordResetController::class, 'forgotPasswordPost'])->middleware('guest')->name('forgot.password.post');
Route::post('/reset-password', [PasswordResetController::class, 'resetPasswordPost'])->middleware('guest')->name('reset.password.post');

Route::prefix('email')->group(function () {
    Route::get('/verify', function () {
        return view('auth.verify-email');
    })->middleware('auth:sanctum')->name('verification.notice');
    Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
    })->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
    Route::post('/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
    })->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
});
