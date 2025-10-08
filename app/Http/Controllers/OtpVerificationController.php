<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    public function showVerificationForm()
    {
        $email = session('email');

        if (!$email) {
            return redirect()->route('register');
        }
        return view('pages.auth.verify-otp', ['email' => $email]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            'email' => 'required|email'
        ]);

        $registrationData = session('registration_data');

        // Cek jika data session tidak ada atau email tidak cocok
        if (!$registrationData || $registrationData['email'] !== $request->email) {
            return back()->withErrors(['otp' => 'Sesi pendaftaran tidak valid. Silakan coba daftar kembali.']);
        }

        // Cek jika OTP salah
        if ($registrationData['verification_code'] != $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // Cek jika OTP sudah kedaluwarsa
        if (now()->gt($registrationData['verification_code_expires_at'])) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan coba daftar kembali.']);
        }

        User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => $registrationData['password'],
            'email_verified_at' => now(),
            'verification_code' => $registrationData['verification_code'],
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);

        session()->forget('registration_data');
        session()->forget('email');

        return redirect()->route('login')->with('success', 'Akun Anda telah berhasil diverifikasi. Anda sekarang dapat masuk.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $registrationData = session('registration_data');

        if (!$registrationData || $registrationData['email'] !== $request->email) {
            return redirect()->route('otp.verification.form')
                ->with(['email' => $request->email])
                ->withErrors(['otp' => 'Sesi pendaftaran tidak valid. Silakan coba daftar kembali.']);
        }

        $otp = rand(100000, 999999);
        $registrationData['verification_code'] = $otp;
        $registrationData['verification_code_expires_at'] = now()->addMinutes(10);

        session(['registration_data' => $registrationData]);
        session(['email' => $registrationData['email']]);

        Mail::to($registrationData['email'])->send(new SendOtpMail($otp));

        return redirect()->route('otp.verification.form')
            ->with('success', 'Kode OTP baru telah dikirim ulang ke email Anda.')
            ->with('otp_resent', true);
    }
}
