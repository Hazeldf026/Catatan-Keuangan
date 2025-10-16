<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\RencanaController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rute untuk Tamu (Guest) - Pengguna yang BELUM Login
Route::middleware('guest')->group(function () {
    // Halaman Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Halaman Register
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Verifikasi OTP untuk Registrasi
    Route::get('/verify-otp', [OtpVerificationController::class, 'showVerificationForm'])->name('otp.verification.form');
    Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
    Route::post('/resend-otp', [OtpVerificationController::class, 'resendOtp'])->name('otp.resend');

    // Lupa Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');

    // Verifikasi OTP untuk Reset Password
    Route::get('/verify-password-otp', [ForgotPasswordController::class, 'showOtpForm'])->name('password.otp.form');
    Route::post('/verify-password-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('password.otp.verify');
    Route::post('/resend-password-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.otp.resend');

    // Halaman Reset Password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});


// Rute untuk Pengguna yang SUDAH Login
Route::middleware('auth')->group(function () {
    // Halaman utama akan dialihkan ke halaman catatan
    Route::get('/', function () {
        return redirect()->route('catatan.index');
    });

    // Proses Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Resource untuk Catatan (CRUD)
    Route::resource('catatan', CatatanController::class);

    // == RUTE BARU UNTUK HALAMAN ANALISIS ==
    Route::get('/analysis', [AnalysisController::class, 'showAnalysisPage'])->name('analysis.show');
    Route::get('/analysis/data', [AnalysisController::class, 'getChartData'])->name('analysis.data');

    Route::resource('rencana', RencanaController::class);
    Route::post('rencana/{rencana}/cancel', [RencanaController::class, 'cancel'])->name('rencana.cancel');
});
