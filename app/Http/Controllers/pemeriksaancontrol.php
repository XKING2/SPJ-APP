<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemeriksaan;
use App\Models\SPJ;
use App\Models\Pesanan;

class PemeriksaanControl extends Controller
{
    public function create(Request $request)
    {
        $spj = SPJ::findOrFail($request->spj_id);
        $pesanan = Pesanan::findOrFail($request->pesanan_id);
        return view('users.create.createpemeriksaan', compact('spj', 'pesanan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'pesanan_id' => 'required|exists:pesanans,id',
            'hari_diterima' => 'required',
            'tanggal_diterima' => 'required',
            'bulan_diterima' => 'required',
            'tahun_diterima' => 'required',
            'hari_diterima' => 'required|string|max:50',
            'nama_pihak_kedua' => 'required|string|max:255',
            'jabatan_pihak_kedua' => 'required|string|max:255',
            'alamat_pihak_kedua' => 'required',
            'pekerjaan' => 'required',
        ]);

        $pemeriksaan = Pemeriksaan::create($validated);

        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['pemeriksaan_id' => $pemeriksaan->id]);

        return redirect()
            ->route('penerimaan.create', ['spj_id' => $validated['spj_id'], 'pemeriksaan_id' => $pemeriksaan->id])
            ->with('success', 'Pemeriksaan berhasil. Lanjut ke penerimaan.');
    }
}
