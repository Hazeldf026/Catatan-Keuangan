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
        ]);

        $user = User::where('email', session('email'))->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'User not found']);
        }

        if ($user->verification_code !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP code']);
        }

        if (now()->gt($user->verification_code_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Mark user as verified
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        session()->forget('email');

        return redirect()->route('login')->with('status', 'Your account has been successfully verified. You can now log in.');
    }

}
