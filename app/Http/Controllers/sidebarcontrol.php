<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kwitansi;
use App\Models\Pesanan;
use App\Models\pemeriksaan;
use App\Models\penerimaan;
use App\Models\SPJ;

class sidebarcontrol extends Controller
{
    public function showdashboard()
    {
        return view('users.dashboard');
    }
    public function showkwitansi(Request $request)
    {
        $search = $request->input('search');

        // Query data kwitansi
        $query = Kwitansi::query();

        if ($search) {
            $query->where('pembayaran', 'like', "%{$search}%")
                ->orWhere('uang_terbilang', 'like', "%{$search}%")
                ->orWhere('nama_pt', 'like', "%{$search}%");
        }

        // Pagination 10 data per halaman
        $kwitansis = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim data ke view
        return view('users.kwitansi', compact('kwitansis', 'search'));
    
    }







    public function showpesanan(Request $request)
    {
        $search = $request->input('search');

        // Query dengan filter pencarian
        $pesanans = Pesanan::when($search, function ($query, $search) {
                $query->where('nama_pt', 'like', "%{$search}%")
                      ->orWhere('no_surat', 'like', "%{$search}%")
                      ->orWhere('alamat_pt', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Kirim ke view
        return view('users.pesanan', compact('pesanans'));
    }

    public function showserahterima()
    {
        return view('users.serahterima');
    }


    public function showpemeriksaan(Request $request)
    {
        $search = $request->input('search');

        // Ambil data pemeriksaan dan relasi pesanan
        $query = Pemeriksaan::with('pesanan');

        // 🔍 Filter pencarian (berdasarkan kolom pemeriksaan & pesanan)
        if ($search) {
            $query->where('pekerjaan', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($q) use ($search) {
                    $q->where('no_surat', 'like', "%{$search}%")
                        ->orWhere('nama_pt', 'like', "%{$search}%")
                        ->orWhere('alamat_pt', 'like', "%{$search}%");
                });
        }

        // 🔢 Pagination (10 per halaman)
        $pemeriksaans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('users.pemeriksaan', compact('pemeriksaans'));
    
    }


    public function showpenerimaan(Request $request)
    {
        $search = $request->input('search');

        // Ambil data penerimaan dan relasi pemeriksaan + pesanan
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

        // Pagination (10 per halaman)
        $penerimaans = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('users.penerimaan', compact('penerimaans'));
    
    }
    public function showserahbarang()
    {
        return view('users.serahbarang');
    }
    public function showreviewSPJ(Request $request)
    {
        $search = $request->input('search');

        // Ambil data SPJ beserta relasi Pesanan
        $query = Spj::with('pesanan');

        // Filter pencarian
        if ($search) {
            $query->where('status', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($q) use ($search) {
                    $q->where('no_surat', 'like', "%{$search}%")
                        ->orWhere('surat_dibuat', 'like', "%{$search}%");
                });
        }

        // Pagination
        $spjs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('users.reviewSPJ', compact('spjs'));
    }
    public function showcetakSPJ()
    {
        return view('users.cetakSPJ');
    }
}
