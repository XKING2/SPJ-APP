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

            if ($user->role === 'superadmin') {
                return redirect()->route('super.dashboard');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'user') {
                return redirect()->route('user.dashboard');
            }
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
