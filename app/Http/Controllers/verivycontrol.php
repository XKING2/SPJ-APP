<?php

namespace App\Http\Controllers;

use App\Models\SPJ;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class verivycontrol extends Controller
{

    public function showverivikasiLS(Request $request)
    {
        $search = $request->input('search');

        // === AMBIL & KUNCI TAHUN (SAMA DENGAN showvalidasi) ===
        $tahunSekarang = Carbon::now()->year;

        $maxTahunDb = Spj::max('tahun');
        $minTahunDb = Spj::min('tahun');

        $tahunDipilih = $request->input('tahun', $tahunSekarang);

        if ($maxTahunDb && $tahunDipilih > $maxTahunDb) {
            $tahunDipilih = $maxTahunDb;
        }

        if ($minTahunDb && $tahunDipilih < $minTahunDb) {
            $tahunDipilih = $minTahunDb;
        }

        // === QUERY UTAMA ===
        $query = SPJ::with(['user', 'pesanan'])
            ->where('types', 'LS')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status2', ['diajukan', 'valid', 'belum_valid']);

        // SEARCH
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status2', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($pq) use ($search) {
                    $pq->where('no_surat', 'like', "%{$search}%")
                        ->orWhereDate('surat_dibuat', $search);
                })
                ->orWhereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $spjs = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString(); 

        return view('admins.verivikasils', compact('spjs'));
    }

    public function showverivikasiGU(Request $request)
    {
        $search = $request->input('search');

        // === AMBIL & KUNCI TAHUN (SAMA DENGAN showvalidasi) ===
        $tahunSekarang = Carbon::now()->year;

        $maxTahunDb = Spj::max('tahun');
        $minTahunDb = Spj::min('tahun');

        $tahunDipilih = $request->input('tahun', $tahunSekarang);

        if ($maxTahunDb && $tahunDipilih > $maxTahunDb) {
            $tahunDipilih = $maxTahunDb;
        }

        if ($minTahunDb && $tahunDipilih < $minTahunDb) {
            $tahunDipilih = $minTahunDb;
        }

        // === QUERY UTAMA ===
        $query = SPJ::with(['user', 'pesanan'])
            ->where('types', 'GU')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status', ['diajukan', 'valid', 'belum_valid']);

        // SEARCH
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($pq) use ($search) {
                    $pq->where('no_surat', 'like', "%{$search}%")
                        ->orWhereDate('surat_dibuat', $search);
                })
                ->orWhereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $spjs = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString(); 

        return view('admins.verivikasigu', compact('spjs'));
    }

    public function showverivikasiPO(Request $request)
    {
        $search = $request->input('search');

        // === AMBIL & KUNCI TAHUN (SAMA DENGAN showvalidasi) ===
        $tahunSekarang = Carbon::now()->year;

        $maxTahunDb = Spj::max('tahun');
        $minTahunDb = Spj::min('tahun');

        $tahunDipilih = $request->input('tahun', $tahunSekarang);

        if ($maxTahunDb && $tahunDipilih > $maxTahunDb) {
            $tahunDipilih = $maxTahunDb;
        }

        if ($minTahunDb && $tahunDipilih < $minTahunDb) {
            $tahunDipilih = $minTahunDb;
        }

        // === QUERY UTAMA ===
        $query = SPJ::with(['user', 'pesanan'])
            ->where('types', 'PO')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status', ['diajukan', 'valid', 'belum_valid']);

        // SEARCH
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                ->orWhereHas('pesanan', function ($pq) use ($search) {
                    $pq->where('no_surat', 'like', "%{$search}%")
                        ->orWhereDate('surat_dibuat', $search);
                })
                ->orWhereHas('user', function ($uq) use ($search) {
                    $uq->where('nama', 'like', "%{$search}%");
                });
            });
        }

        $spjs = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString(); 

        return view('admins.verivikasipo', compact('spjs'));
    }
    
}
