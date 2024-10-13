<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check apakah menggunakan email atau username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Coba autentikasi
        if (Auth::attempt([$loginType => $request->login, 'password' => $request->password])) {
            // Jika berhasil login, redirect ke dashboard
            return redirect()->route('dashboard');
        }

        // Jika gagal login
        return back()->withErrors([
            'login' => 'Login details are not valid',
        ])->withInput();
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
