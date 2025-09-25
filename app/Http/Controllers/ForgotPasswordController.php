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
    public function showLinkRequestForm() 
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
        $user->verfication_code_expires_at = now()->addMinutes(10);
        $user->save();

        // Send OTP email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect()->action('password.otp.form')->with(['email' => $user->email]);
    }

    public function showOtpForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request');
        }
        return view('pages.auth.verify-password-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $user = User::where('email', session('email'))->first();

        if (!$user || $user-> verificaton_code !== $request->otp || now()->gt($user->verfication_code_exipires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Clear OTP
        $token = app('auth.password.broker')->createToken($user);

        session()->forget('email');

        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email]);
    }
}
