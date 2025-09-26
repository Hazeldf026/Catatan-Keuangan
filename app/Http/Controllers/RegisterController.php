<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegisterForm() 
    {
        return view('pages.register');
    }

    public function register(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => '*Password minimal 8 karakter',
            'password.confirmed' => '*Konfirmasi password tidak sama',
        ]);

        User::where('email', $request->email)->whereNull('email_verified_at')->delete();

        $request->validate([
            'email' => 'unique:users,email',
        ], [
            'email.unique' => '*Email Sudah Terdaftar'
        ]);

        $otp = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'verification_code' => $otp,
            'verification_code_expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new SendOtpMail($otp));

        return redirect()->route('otp.verification.form')->with(['email' => $user->email]);
    }
}
