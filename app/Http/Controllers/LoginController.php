<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only(
            'email', 
            'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)){
            $request->session()->regenerate();
            return redirect()->intended('/catatan');
        }

        $user = User::where('email', $request->email)->first();

        if(!$user)
        {
            return back()->withErrors([
                'email' => '*Email tidak ditemukan.',
            ])->withInput();
        }

        if(!Hash::check($request->password, $user->password))
        {
            return back()->withErrors([
                'password' => '*Password salah.',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->with('success', 'Anda sudah keluar dari akun Anda.');
    }
}
