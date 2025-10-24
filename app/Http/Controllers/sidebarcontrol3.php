<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SPJ;
use App\Models\User;



class sidebarcontrol3 extends Controller
{
    public function showdashboard3()
    {
       // Hitung semua data SPJ
    $totalSPJ =  Spj::count();

    // Hitung SPJ tervalidasi (status = valid & status2 = valid)
    $spjTervalidasi = Spj::where('status2', 'valid')
                        ->count();


    // Hitung SPJ belum divalidasi
    $spjBelumValid = Spj::where('status2', 'diajukan')
                        ->count();

    // Contoh laporan: bisa disesuaikan (misal total pemeriksaan, penerimaan, dsb.)
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
                        ->orWhere('jabatan', 'like', "%{$search}%")
                        ->orWhere('Alamat', 'like', "%{$search}%");
        })->orderBy('created_at', 'desc')->get();

        return view('superadmins.anggota', compact('anggotas', 'search'));
    }

    public function showvalidasi(Request $request)
    {
        $search = $request->input('search');

        // Ambil semua data SPJ beserta relasi user dan pesanan
        $query = Spj::with(['user', 'pesanan'])
                    ->whereIn('status', ['diajukan', 'valid']); // ✅ tampilkan dua status

        // Filter pencarian (berdasarkan status, nomor surat, atau nama pembuat)
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('status2', 'like', "%{$search}%")
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
        return view('superadmins.validasi', compact('spjs'));
    }

    public function previewsuper($id)
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

        return view('superadmins.preview3', compact('spj', 'fileUrl'));
    }

    public function updateStatusKasubag(Request $request, $id)
    {
        $spj = Spj::findOrFail($id);

        // normalisasi input -> paksa lowercase
        $request->merge([
            'status2' => strtolower((string) $request->input('status2'))
        ]);

        $validated = $request->validate([
            'status2' => 'required|in:valid,draft,belum_valid',
            'komentar_kasubag' => 'nullable|string|max:1000',
        ]);

        // jika tidak disetujui (belum_valid), komentar wajib
        if ($validated['status2'] === 'belum_valid' && empty(trim($validated['komentar_kasubag'] ?? ''))) {
            return redirect()->back()->withInput()->withErrors(['komentar_kasubag' => 'Komentar wajib diisi jika SPJ tidak disetujui.']);
        }

        // simpan
        $spj->status2 = $validated['status2'];
        $spj->komentar_kasubag = $validated['komentar_kasubag'] ?? null;
        $spj->save();

        return redirect()->route('Validasi')->with('success', 'Status validasi Kasubag berhasil diperbarui!');
    }

    


}
