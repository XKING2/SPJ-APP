<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Penerimaan;
use App\Models\setting;
use App\Models\SPJ;
use App\Models\Kwitansi;
use App\Models\nosurat;
use App\Models\plt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PesananControl extends Controller
{

    public function showpesananGU(Request $request)
    {

        $search = $request->input('search');
        $userId = Auth::id();

        $pesanans = Pesanan::with('spj')
             ->whereHas('spj', function ($q) use ($userId) {
                $q->where('types', 'GU')
                ->where('user_id', $userId); // filter hanya milik user
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_pt', 'like', "%{$search}%")
                        ->orWhere('no_surat', 'like', "%{$search}%")
                        ->orWhere('alamat_pt', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.SpjGu.pesanangu', compact('pesanans', 'search'));
    }
        


    public function showpesananLS(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id();

        $pesanans = Pesanan::with('spj')
             ->whereHas('spj', function ($q) use ($userId) {
                $q->where('types', 'LS')
                ->where('user_id', $userId); // filter hanya milik user
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_pt', 'like', "%{$search}%")
                        ->orWhere('no_surat', 'like', "%{$search}%")
                        ->orWhere('alamat_pt', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.SpjLs.pesananls', compact('pesanans', 'search'));
    }






    
}
