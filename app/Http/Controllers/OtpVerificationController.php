<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class OtpVerificationController extends Controller
{
    public function showVerificationForm()
    {
        if (!session('email')) {
            return redirect()->route('register');
        }
        return view('pages.auth.verify-otp');
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

        return redirect()->route('login')->with('status', 'Your account has been successfully verified. You can now log in.');
    }

}
