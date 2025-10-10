<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPJ;
use Illuminate\Support\Facades\Auth;


class kasubagcontrol extends Controller
{
      public function index(Request $request)
    {
        $search = $request->input('search');

        // Ambil data SPJ dengan relasi user dan pesanan
        $query = Spj::with(['user', 'pesanan']);

        // Jika user login bukan admin, tampilkan hanya SPJ miliknya
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }

        // Filter pencarian (berdasarkan status, nomor surat, atau nama pembuat)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                    ->orWhereHas('pesanan', function ($pesananQuery) use ($search) {
                        $pesananQuery->where('no_surat', 'like', "%{$search}%")
                                     ->orWhere('surat_dibuat', 'like', "%{$search}%");
                    })
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        // Urutkan dan paginasi hasilnya
        $spjs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('superadmins.validasi', compact('spjs'));
    }
}
