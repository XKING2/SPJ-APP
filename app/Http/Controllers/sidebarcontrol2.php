<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SPJ;
use App\Models\pemeriksaan;
use Illuminate\Support\Carbon;

class sidebarcontrol2 extends Controller
{
    public function showdashboard2()
    {
    $totalSPJs = Spj::count();

    $spjTervalidasis = Spj::where('status', 'valid')
                        ->count();

    $spjperludivalidasi = Spj::where('status', 'diajukan')
                        ->count();

    $ditolak =  Spj::where('status', 'belum_valid')
                        ->count();

    return view('admins.admindashboard', compact('totalSPJs', 'spjTervalidasis', 'ditolak', 'spjperludivalidasi'));
    }



    public function Verivymain(Request $request)
    {
        $tahunSekarang = Carbon::now()->year;

        $maxTahunDb = Spj::max('tahun'); // contoh: 2027
        $minTahunDb = Spj::min('tahun');

        // Default tahun
        $tahunDipilih = $request->input('tahun', $tahunSekarang);

        // HARD LIMIT (anti manipulasi URL)
        if ($maxTahunDb && $tahunDipilih > $maxTahunDb) {
            $tahunDipilih = $maxTahunDb;
        }

        if ($minTahunDb && $tahunDipilih < $minTahunDb) {
            $tahunDipilih = $minTahunDb;
        }

        $notifGU = Spj::where('types', 'GU')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status', ['diajukan'])
            ->count();

        $notifLS = Spj::where('types', 'LS')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status', ['diajukan'])
            ->count();

        $notifPO = Spj::where('types', 'PO')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status', ['diajukan'])
            ->count();

        return view('admins.verivmain', compact(
            'tahunDipilih',
            'tahunSekarang',
            'minTahunDb',
            'maxTahunDb',
            'notifGU',
            'notifLS',
            'notifPO'
        ));
    }



    public function updateStatusbendahara(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);

        $request->merge([
            'status' => strtolower((string) $request->input('status'))
        ]);

        $validated = $request->validate([
            'status' => 'required|in:valid,draft,belum_valid',
        ]);

        $spj->status = $validated['status'];
        $spj->save();

        return redirect()->route('verivikasi')->with('success', 'Status validasi Kasubag berhasil diperbarui!');
    }

    public function previewadmin($id)
    {
        $spj = Spj::with('pesanan')->findOrFail($id);
        $relativePath = "spj_preview_{$spj->id}.pdf";
        $pdfPath = storage_path("app/public/{$relativePath}");
        if (!file_exists($pdfPath)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        $fileUrl = asset("storage/{$relativePath}");

        return view('admins.preview2', compact('spj', 'fileUrl'));
    }

}
