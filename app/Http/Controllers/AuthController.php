<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // para maiwasan ang session fixation attacks
            return redirect()->intended('/dashboard')
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email'); // mag clear ang password field para sa security
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); // para ma-clear lahat ng session data
        $request->session()->regenerateToken(); // para sa CSRF protection
        return redirect('/login')->with('success', 'You have been logged out.');
    }
}