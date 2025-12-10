<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kwitansi;
use App\Models\Pesanan;
use App\Models\pemeriksaan;
use App\Models\penerimaan;
use App\Models\serahbarang;
use App\Models\SPJ;
use App\Helpers\SpjFieldGroups;
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
        return view('users.pesanan', compact('pesanans','search'));
    }

    public function showserahterima()
    {
        return view('users.serahterima');
    }


    public function showpemeriksaan(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id();

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

        return view('users.pemeriksaan', compact('pemeriksaans', 'search'));
    }



    public function showpenerimaan(Request $request)
    {

        $search = $request->input('search');
        $userId = Auth::id();

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
        return view('users.penerimaan', compact('penerimaans','search'));
    
    }
    
    public function showserahbarang(Request $request)
    {
        $search = $request->input('search');

        $search = $request->input('search');
        $userId = Auth::id();

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

        return view('users.serahbarang', compact('serahbarangs','search'));
    }

    public function showreviewSPJ(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id() ?? session('user_id');

        // === Eager load relasi yang benar ===
        $query = Spj::with([
            'pesanans',
            'spj_feedbacks',
            'spj_feedbacks.pesanans',
            'spj_feedbacks.pemeriksaans'
        ])->where('user_id', $userId);

        // === Filter pencarian ===
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status2', 'like', "%{$search}%")
                ->orWhereHas('pesanans', function ($pesananQuery) use ($search) {
                    $pesananQuery->where('no_surat', 'like', "%{$search}%")
                                ->orWhere('surat_dibuat', 'like', "%{$search}%");
                });
            });
        }

        // === Pagination ===
        $spjs = $query->orderBy('created_at', 'desc')->paginate(10);

        // === Proses feedback (disiapkan untuk Blade/JS) ===
        foreach ($spjs as $spj) {

            $feedbackArray = [];
            foreach ($spj->spj_feedbacks as $f) {

                $feedbackArray[] = [
                    'field'      => $f->field_name,
                    'message'    => $f->message,
                    'role'       => $f->role,
                    'created_at' => $f->created_at->format('d-m-Y H:i'),

                    // Nomor SPJ dari relasi yang BENAR
                    'pesanan'       => $f->pesanans ? [
                        'no_surat' => $f->pesanans->no_surat
                    ] : null,

                    'pemeriksaan'   => $f->pemeriksaans ? [
                        'no_surat' => $f->pemeriksaans->no_surat
                    ] : null,
                ];
            }

            $spj->feedbackArray = $feedbackArray;
            $spj->status1 = $spj->status;
            $spj->status2 = $spj->status2;
        }

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
