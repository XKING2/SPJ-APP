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

            // Simpan user_id di session
            session(['user_id' => $user->id]);

            // Arahkan ke dashboard sesuai role
            return match ($user->role) {
                'superadmin' => redirect()->route('superadmins.dashboard'),
                'admin'      => redirect()->route('admins.dashboard'),
                'user'       => redirect()->route('users.dashboard'),
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
