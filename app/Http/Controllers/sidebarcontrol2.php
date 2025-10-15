<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SPJ;
use App\Models\pemeriksaan;

class sidebarcontrol2 extends Controller
{
    public function showdashboard2()
    {
       // Hitung semua data SPJ
    $totalSPJs = Spj::count();

    // Hitung SPJ tervalidasi (status = valid & status2 = valid)
    $spjTervalidasis = Spj::where('status', 'valid')
                        ->count();


    // Hitung SPJ belum divalidasi
    $spjperludivalidasi = Spj::where('status', 'diajukan')
                        ->count();

    // Contoh laporan: bisa disesuaikan (misal total pemeriksaan, penerimaan, dsb.)
    $ditolak =  Spj::where('status', 'belum_valid')
                        ->count();

    return view('admins.admindashboard', compact('totalSPJs', 'spjTervalidasis', 'ditolak', 'spjperludivalidasi'));
    }



    public function showverivikasi(Request $request)
    {
        $search = $request->input('search');

        // Ambil semua data SPJ berstatus diajukan atau valid beserta relasi user dan pesanan
        $query = Spj::with(['user', 'pesanan'])
                    ->whereIn('status', ['diajukan', 'valid','belum_valid']); // ✅ tampilkan dua status

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

        // Urutkan berdasarkan tanggal dibuat (terbaru di atas)
        $spjs = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim data ke view
        return view('admins.verivikasi', compact('spjs'));
    }



    public function updateStatusbendahara(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);

        $request->merge([
            'status' => strtolower((string) $request->input('status'))
        ]);

        $validated = $request->validate([
            'status' => 'required|in:valid,draft,belum_valid',
            'komentar_bendahara' => 'nullable|string|max:1000',
        ]);

        if ($validated['status'] === 'belum_valid' && empty(trim($validated['komentar_bendahara'] ?? ''))) {
            return redirect()->back()->withInput()->withErrors([
                'komentar_bendahara' => 'Komentar wajib diisi jika SPJ tidak disetujui.'
            ]);
        }

        $spj->status = $validated['status'];
        $spj->komentar_bendahara = $validated['komentar_bendahara'] ?? null;
        $spj->save();

        return redirect()->route('verivikasi')->with('success', 'Status validasi Kasubag berhasil diperbarui!');
    }

    public function previewadmin($id)
    {
        $spj = Spj::with('pesanan')->findOrFail($id);

        // Lokasi file di storage
        $relativePath = "spj_preview_{$spj->id}.pdf";
        $pdfPath = storage_path("app/public/{$relativePath}");

        // Pastikan file-nya ada
        if (!file_exists($pdfPath)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }

        // Buat URL publik
        $fileUrl = asset("storage/{$relativePath}");

        return view('admins.preview2', compact('spj', 'fileUrl'));
    }

}
