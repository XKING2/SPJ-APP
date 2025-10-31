<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('NIP', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            session(['user_id' => $user->id]);

            session()->flash('success', 'Login berhasil! Selamat datang ' . $user->nama);

            return match ($user->role) {
                'Kasubag' => redirect()->route('superdashboard'),
                'Bendahara'      => redirect()->route('admindashboard'),
                'user'       => redirect()->route('userdashboard'),
                default      => redirect()->route('login'),
            };
        }

        return back()->withErrors([
            'NIP' => 'Nama atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
