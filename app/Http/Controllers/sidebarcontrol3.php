<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPJ;
use App\Models\User;
use App\Models\Kasubag;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class sidebarcontrol3 extends Controller
{
    public function showdashboard3()
    {
    $totalSPJ =  Spj::count();
    $spjTervalidasi = Spj::where('status2', 'valid')
                        ->count();

    $spjBelumValid = Spj::where('status2', 'diajukan')
                        ->count();

    $ditolax =  Spj::where('status2', 'belum_valid')
                        ->count();

    return view('superadmins.dashboard', compact('totalSPJ', 'spjTervalidasi', 'ditolax', 'spjBelumValid'));
    }

    public function showanggota(Request $request)
    {
        $search = $request->input('search');
        
        $anggotas = User::when($search, function($query) use ($search) {
            return $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('NIP', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'asc')
        ->paginate(10); // 🔹 tampilkan 10 per halaman

        // Pastikan query search ikut terbawa di pagination
        $anggotas->appends(['search' => $search]);

        return view('superadmins.anggota', compact('anggotas', 'search'));
    }

    public function showvalidasi(Request $request)
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
            ->whereIn('status2', ['diajukan'])
            ->count();

        $notifLS = Spj::where('types', 'LS')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status2', ['diajukan'])
            ->count();

        $notifPO = Spj::where('types', 'PO')
            ->where('tahun', $tahunDipilih)
            ->whereIn('status2', ['diajukan'])
            ->count();

        return view('superadmins.validasi.validasi', compact(
            'tahunDipilih',
            'tahunSekarang',
            'minTahunDb',
            'maxTahunDb',
            'notifGU',
            'notifLS',
            'notifPO'
        ));
    }


    public function previewsuper($id)
    {
        $spj = Spj::with('pesanan')->findOrFail($id);
        $relativePath = "spj_preview_{$spj->id}.pdf";
        $pdfPath = storage_path("app/public/{$relativePath}");
        if (!file_exists($pdfPath)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        $fileUrl = asset("storage/{$relativePath}");

        return view('superadmins.preview3', compact('spj', 'fileUrl'));
    }

    public function updateStatusKasubag(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);
        $request->merge([
            'status2' => strtolower((string) $request->input('status2'))
        ]);

        $validated = $request->validate([
            'status2' => 'required|in:valid,draft,belum_valid',
        ]);

        $spj->status2 = $validated['status2'];
        $spj->save();

        return redirect()->route('Validasi')->with('success', 'Status validasi Kasubag berhasil diperbarui!');
    }

    


}
