<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kwitansi;
use App\Models\Pesanan;
use App\Models\pemeriksaan;
use App\Models\penerimaan;
use App\Models\serahbarang;
use App\Models\SPJ;
use App\Models\spj_feedbacks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class sidebarcontrol extends Controller
{
    public function showdashboard1()
    {   
        $user = Auth::user();

        // Hitung total SPJ user ini
        $user_SPJ = Spj::where('user_id', $user->id)->count();

        // SPJ tervalidasi oleh Kasubag
        $spjTervalidasikasubag = Spj::where('user_id', $user->id)
            ->where('status2', 'valid')
            ->count();

        // SPJ tervalidasi oleh Bendahara
        $spjTervalidasibendahara = Spj::where('user_id', $user->id)
            ->where('status', 'valid')
            ->count();

        // Total laporan pemeriksaan
        $laporan = \App\Models\Pemeriksaan::count() ?? 0;

        // 🔥 Ambil semua feedback untuk semua SPJ user ini
        $feedbackCount = spj_feedbacks::whereIn('spj_id', function ($q) use ($user) {
                $q->select('id')->from('spjs')->where('user_id', $user->id);
            })
            ->select('section', DB::raw('COUNT(*) as total'))
            ->groupBy('section')
            ->pluck('total', 'section');

        return view('users.dashboarduser', compact(
            'user_SPJ',
            'spjTervalidasikasubag',
            'spjTervalidasibendahara',
            'laporan',
            'feedbackCount'
        ));
    }





    public function showkwitansi(Request $request)
    {

        $user = Auth::user();
        $search = $request->input('search');
        $query = Kwitansi::query();


        if ($search) {
            $query->where('pembayaran', 'like', "%{$search}%")
                ->orWhere('uang_terbilang', 'like', "%{$search}%")
                ->orWhere('nama_pt', 'like', "%{$search}%");
        }
        $kwitansis = $query->orderBy('created_at', 'desc')->paginate(10);
       
        $feedbackCount = spj_feedbacks::select('section')
            ->groupBy('section')
            ->pluck('section')
            ->mapWithKeys(function ($section) {
                return [$section => 1];
        });

        $feedbackKwitansi = spj_feedbacks::where('section', 'kwitansi')
            ->pluck('spj_id')
            ->unique();

        // Default notif
        $notifGU = 0;
        $notifLS = 0;

        if ($feedbackKwitansi->isNotEmpty()) {

            // Ambil type spj untuk semua spj_id tersebut
            $spjTypes = Spj::whereIn('id', $feedbackKwitansi)
                ->pluck('types'); // misal: [GU, LS, GU]

            // Jika ada SPJ type GU yang salah → notifGU = 1
            if ($spjTypes->contains('gu')) {
                $notifGU = 1;
            }

            // Jika ada SPJ type LS yang salah → notifLS = 1
            if ($spjTypes->contains('ls')) {
                $notifLS = 1;
            }
        }
        return view('users.kwitansi', compact('kwitansis', 'search','feedbackCount','feedbackKwitansi','notifGU','notifLS'));
    
    }

    public function showpesanan(Request $request)
    {

        $user = Auth::user();
        $search = $request->input('search');
        $pesanans = Pesanan::when($search, function ($query, $search) {
                $query->where('nama_pt', 'like', "%{$search}%")
                      ->orWhere('no_surat', 'like', "%{$search}%")
                      ->orWhere('alamat_pt', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $feedbackCount = spj_feedbacks::select('section')
            ->groupBy('section')
            ->pluck('section')
            ->mapWithKeys(function ($section) {
                return [$section => 1];
        });

        $feedbackPesanan = spj_feedbacks::where('section', 'pesanan')
            ->pluck('spj_id')
            ->unique();

        // Default notif
        $notifGU = 0;
        $notifLS = 0;

        if ($feedbackPesanan->isNotEmpty()) {

            $spjTypes = Spj::whereIn('id', $feedbackPesanan)
                ->pluck('types');

            if ($spjTypes->contains('gu')) {
                $notifGU = 1;
            }

            if ($spjTypes->contains('ls')) {
                $notifLS = 1;
            }
        }



        return view('users.pesanan', compact('pesanans','search','feedbackCount','feedbackPesanan','notifGU','notifLS'));
    }

    public function showserahterima()
    {
        return view('users.serahterima');
    }


    public function showpemeriksaan(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id();
        $user = Auth::user();

        $pemeriksaans = Pemeriksaan::with('spj')
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('pekerjaan', 'like', "%{$search}%")
                        ->orWhere('no_suratssss', 'like', "%{$search}%")
                        ->orWhere('tanggals_diterima', 'like', "%{$search}%")
                        ->orWhere('hari_diterima', 'like', "%{$search}%")
                        ->orWhere('bulan_diterima', 'like', "%{$search}%")
                        ->orWhere('tahun_diterima', 'like', "%{$search}%")
                        ->orWhere('nama_pihak_kedua', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $feedbackCount = spj_feedbacks::select('section')
            ->groupBy('section')
            ->pluck('section')
            ->mapWithKeys(function ($section) {
                return [$section => 1];
        });

        return view('users.pemeriksaan', compact('pemeriksaans', 'search','feedbackCount'));
    }



    public function showpenerimaan(Request $request)
    {

        $search = $request->input('search');
        $userId = Auth::id();
        $user = Auth::user();

        $penerimaans = Penerimaan::with(['pemeriksaan', 'pesanan'])
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama_pihak_kedua', 'like', "%{$search}%")
                        ->orWhere('pekerjaan', 'like', "%{$search}%")
                        ->orWhere('surat_dibuat', 'like', "%{$search}%")
                        ->orWhere('no_surat', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $feedbackCount = spj_feedbacks::select('section')
            ->groupBy('section')
            ->pluck('section')
            ->mapWithKeys(function ($section) {
                return [$section => 1];
        });

        return view('users.penerimaan', compact('penerimaans','search','feedbackCount'));
    
    }
    
    public function showserahbarang(Request $request)
    {

        $search = $request->input('search');
        $userId = Auth::id();
        $user = Auth::user();

         $query = Serahbarang::with(['plt', 'pihak_kedua'])
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('no_suratsss', 'like', "%{$search}%");
                });
            });

        $serahbarangs = $query->orderBy('created_at', 'desc')->paginate(10);

        $feedbackCount = spj_feedbacks::select('section')
            ->groupBy('section')
            ->pluck('section')
            ->mapWithKeys(function ($section) {
                return [$section => 1];
        });

        return view('users.serahbarang', compact('serahbarangs','search','feedbackCount'));
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

        return view('users.reviewSPJ', compact('spjs','search'));
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

        return view('users.cetakSPJ', compact('spjs','search'));
    }

}
