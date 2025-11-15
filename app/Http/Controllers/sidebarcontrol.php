<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kwitansi;
use App\Models\Pesanan;
use App\Models\pemeriksaan;
use App\Models\penerimaan;
use App\Models\serahbarang;
use App\Models\SPJ;
use Illuminate\Support\Facades\Auth;

class sidebarcontrol extends Controller
{
    public function showdashboard1()
    {
        $user = Auth::user();
        $user_SPJ = Spj::where('user_id', $user->id)->count();

        $spjTervalidasikasubag = Spj::where('user_id', $user->id)
                            ->where('status2', 'valid')
                            ->count();

        $spjTervalidasibendahara = Spj::where('user_id', $user->id)
                            ->where('status', 'valid')
                            ->count();

        $laporan = \App\Models\Pemeriksaan::count() ?? 0;

        return view('users.dashboarduser', compact(
            'user_SPJ',
            'spjTervalidasikasubag',
            'spjTervalidasibendahara',
            'laporan',
            
        ));
    }





    public function showkwitansi(Request $request)
    {
        $search = $request->input('search');
        $query = Kwitansi::query();

        if ($search) {
            $query->where('pembayaran', 'like', "%{$search}%")
                ->orWhere('uang_terbilang', 'like', "%{$search}%")
                ->orWhere('nama_pt', 'like', "%{$search}%");
        }
        $kwitansis = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('users.kwitansi', compact('kwitansis', 'search'));
    
    }

    public function showpesanan(Request $request)
    {
        $search = $request->input('search');
        $pesanans = Pesanan::when($search, function ($query, $search) {
                $query->where('nama_pt', 'like', "%{$search}%")
                      ->orWhere('no_surat', 'like', "%{$search}%")
                      ->orWhere('alamat_pt', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('users.pesanan', compact('pesanans'));
    }

    public function showserahterima()
    {
        return view('users.serahterima');
    }


    public function showpemeriksaan(Request $request)
    {
        $search = $request->input('search');
        $query = Pemeriksaan::with('pesanan');
        if ($search) {
            $query->where('pekerjaan', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($q) use ($search) {
                    $q->where('no_surat', 'like', "%{$search}%")
                        ->orWhere('nama_pt', 'like', "%{$search}%")
                        ->orWhere('alamat_pt', 'like', "%{$search}%");
                });
        }

        $pemeriksaans = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('users.pemeriksaan', compact('pemeriksaans'));
    
    }


    public function showpenerimaan(Request $request)
    {
        $search = $request->input('search');
        $query = Penerimaan::with(['pemeriksaan', 'pesanan']);

        // Filter pencarian
        if ($search) {
            $query->where('nama_pihak_kedua', 'like', "%{$search}%")
                ->orWhere('pekerjaan', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($q) use ($search) {
                    $q->where('nama_pt', 'like', "%{$search}%")
                        ->orWhere('no_surat', 'like', "%{$search}%");
                });
        }
        $penerimaans = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('users.penerimaan', compact('penerimaans'));
    
    }
    
    public function showserahbarang(Request $request)
    {
        $search = $request->input('search');

        // Ambil data serah barang beserta relasi plt (pihak pertama) dan pihak kedua
        $query = Serahbarang::with(['plt', 'pihak_kedua']);

        // Filter pencarian
        if ($search) {
            $query->whereHas('plt', function ($q) use ($search) {
                    $q->where('nama_pihak_pertama', 'like', "%{$search}%");
                })
                ->orWhereHas('pihak_kedua', function ($q) use ($search) {
                    $q->where('nama_pihak_kedua', 'like', "%{$search}%");
                })
                ->orWhere('no_suratsss', 'like', "%{$search}%");
        }

        $serahbarangs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('users.serahbarang', compact('serahbarangs'));
    }

    public function showreviewSPJ(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id() ?? session('user_id');
        $query = Spj::with(['pesanan', 'feedbacks'])
                    ->where('user_id', $userId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status2', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($pesananQuery) use ($search) {
                    $pesananQuery->where('no_surat', 'like', "%{$search}%")
                                ->orWhere('surat_dibuat', 'like', "%{$search}%");
                });
            });
        }

        $spjs = $query->orderBy('created_at', 'desc')->paginate(10);
        $spjDitolak = $spjs->firstWhere('status2', 'belum_valid');

        return view('users.reviewSPJ', compact('spjs', 'spjDitolak'));
    }


    public function showcetakSPJ(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id() ?? session('user_id');

        $query = Spj::with(['user', 'pesanan'])
            ->where('user_id', $userId)
            ->where('status', 'valid')
            ->where('status2', 'valid');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                ->orWhere('status2', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($pesananQuery) use ($search) {
                    $pesananQuery->where('no_surat', 'like', "%{$search}%")
                                ->orWhere('surat_dibuat', 'like', "%{$search}%");
                });
            });
        }
        $spjs = $query->orderBy('created_at', 'desc')->paginate(10);

        $spjDitolak = $spjs->firstWhere('status2', 'belum_valid');

        return view('users.cetakSPJ', compact('spjs', 'spjDitolak'));
    }

}
