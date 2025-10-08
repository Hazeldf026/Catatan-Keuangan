<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Termwind\Components\Raw;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm() 
    {
        return view('pages.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Generate and save OTP
        $otp = rand(100000, 999999);
        $user->verification_code = $otp;
        $user->verification_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect()->route('password.otp.form')->with(['email' => $user->email]);
    }

    public function showOtpForm()
    {
        $email = old('email', session('email'));
        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }
        return view('pages.auth.verify-password-otp', ['email' => $email]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6'
        ]);

        $user = User::where('email', $request->email)->first();

        $otpFromRequest = (int) $request->otp;

        if (!$user || (int) $user->verification_code !== $otpFromRequest || now()->gt($user->verification_code_expires_at)) {
            return back()->withInput()->withErrors(['otp' => 'OTP tidak valid atau telah kedaluwarsa.']);
        }

        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        // Clear OTP
        $token = app('auth.password.broker')->createToken($user);

        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email]);
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.otp.form')
                ->with(['email' => $request->email])
                ->withErrors(['otp' => 'We can\'t find a user with that email address.']);
        }

        // Generate and save OTP
        $otp = rand(100000, 999999);
        $user->verification_code = $otp;
        $user->verification_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect()->route('password.otp.form')
            ->with(['email' => $user->email])
            ->with('success', 'Kode OTP baru telah dikirim ulang ke email Anda.')
            ->with('otp_resent', true);
    }
}
