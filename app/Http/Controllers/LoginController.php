<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login')->with('loginPage', true);
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_name' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // amankan session
            return redirect()->intended('/warehouse'); // langsung ke dashboard
        }

        return back()->withErrors([
            'user_name' => 'Username atau password salah',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
    public function username()
    {
        return 'user_name';
    }
}
