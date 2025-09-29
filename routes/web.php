<?php

use App\Http\Controllers\CatatanController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

//Register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// OTP Verification for Registration
Route::get('/verify-otp', [OtpVerificationController::class, 'showVerificationForm'])->name('otp.verification.form');
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');

//Forgot Password
Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');

// OTP Verification for Password Reset
Route::get('/verify-password-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp.form');
Route::post('/verify-password-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');

//Reset Password
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('catatan.index');
})->middleware('auth');

Route::resource('catatan', CatatanController::class)->middleware('auth');
