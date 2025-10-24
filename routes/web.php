<?php

use App\Http\Controllers\personal\AnalysisController;
use App\Http\Controllers\personal\CatatanController;
use App\Http\Controllers\personal\ForgotPasswordController;
use App\Http\Controllers\personal\LoginController;
use App\Http\Controllers\personal\OtpVerificationController;
use App\Http\Controllers\personal\RegisterController;
use App\Http\Controllers\personal\RencanaController;
use App\Http\Controllers\personal\ResetPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\personal\GrupController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\group\CatatanGroupController;
use App\Http\Controllers\group\RencanaGroupController;
use App\Http\Controllers\personal\UserProfileController;
use App\Models\Grup;

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
    Route::post('rencana/{rencana}/toggle-pin', [RencanaController::class, 'togglePin'])->name('rencana.togglePin');

    Route::get('grup', [GrupController::class, 'index'])->name('grup.index');
    Route::post('grup', [GrupController::class, 'store'])->name('grup.store');
    Route::get('grup/find', [GrupController::class, 'findGrupByCode'])->name('grup.find');
    Route::post('grup/join', [GrupController::class, 'join'])->name('grup.join');

    Route::get('/profile', [UserProfileController::class, 'profile'])->name('profile.index');
    Route::get('/profile/data', [UserProfileController::class, 'getProfileData'])->name('profile.data');

    Route::prefix('settings')->name('settings.')->group(function() {
        // Pengaturan Akun
        Route::get('/account', [UserProfileController::class, 'account'])->name('account.index');

        Route::post('/account/email', [UserProfileController::class, 'updateEmail'])->name('account.updateEmail');
        // Route untuk update Password
        Route::post('/account/password', [UserProfileController::class, 'updatePassword'])->name('account.updatePassword');
        // Route untuk MENGIRIM / MENGIRIM ULANG OTP
        Route::post('/account/send-otp', [UserProfileController::class, 'sendEmailVerificationOtp'])->name('account.sendEmailOtp');
        Route::post('/account/resend-otp', [UserProfileController::class, 'resendEmailOtp'])->name('account.resendEmailOtp');
        // Route untuk MEMVERIFIKASI OTP
        Route::post('/account/verify-email', [UserProfileController::class, 'verifyEmail'])->name('account.verifyEmail');

        // Pengaturan Tampilan
        Route::get('/appearance', [UserProfileController::class, 'appearance'])->name('appearance.index');
        // Route::put('/tampilan', [UserProfileController::class, 'updateAppearance'])->name('appearance.update'); // Jika perlu simpan ke backend
    });

    Route::prefix('group/{grup}') // Menggunakan route model binding {grup}
        ->name('group.') // Nama route diawali 'grup.' (cth: grup.catatan.index)
        ->group(function () {

            Route::resource('catatan', CatatanGroupController::class);

            Route::resource('rencana', RencanaGroupController::class);

            Route::fallback(function (Grup $grup) {
                if (!$grup->users()->where('user_id', Auth::id())->exists()) {
                    abort(403, 'Anda bukan anggota grup ini.');
                }
                // Jika user ada di grup tapi route tidak ditemukan di dalam grup
                abort(404);
            });
        });
});

Route::fallback(function () {
    return redirect('/login'); // Atau tampilkan halaman 404
});