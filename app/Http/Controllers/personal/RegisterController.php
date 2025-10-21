<?php

namespace App\Http\Controllers\personal;

use App\Http\Controllers\Controller;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegisterForm() 
    {
        return view('personal::auth.register');
    }

    public function register(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => '*Email Sudah Terdaftar',
            'password.min' => '*Password minimal 8 karakter',
            'password.confirmed' => '*Konfirmasi password tidak sama',
        ]);

        $otp = rand(100000, 999999);

        $registrationData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => $otp,
            'verification_code_expires_at' => now()->addMinutes(10),
        ];

        session(['registration_data' => $registrationData]);

        Mail::to($request->email)->send(new SendOtpMail($otp));

        return redirect()->route('otp.verification.form')->with(['email' => $request->email]);
    }
}
