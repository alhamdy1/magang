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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on role
            $user = Auth::user();
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'operator' => redirect()->route('operator.dashboard'),
                'kasi' => redirect()->route('kasi.dashboard'),
                'kabid' => redirect()->route('kabid.dashboard'),
                default => redirect()->route('user.dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'Email atau password yang anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
