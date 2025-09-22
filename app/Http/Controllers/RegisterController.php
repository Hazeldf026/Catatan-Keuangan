<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'email.unique' => '*Email Sudah Terdaftar',
            'password.min' => '*Password minimal 8 karakter',
            'password.confirmed' => '*Konfirmasi password tidak sama',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);



        return redirect()->route('login')->with('success', 'Akun berhasil dibuat!');
    }
}
