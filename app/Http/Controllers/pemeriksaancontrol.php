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
            'tanggals_diterima' => 'required',
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

    public function edit($id)
    {
        $pemeriksaan = Pemeriksaan::with('spj')->findOrFail($id);
        $spj = $pemeriksaan->spj;
        $pesanan = $spj ? $spj->pesanan : null;

        return view('users.update.updatepemeriksaan', compact('pemeriksaan', 'spj', 'pesanan'));
    }

    // ============================================================
    // UPDATE PEMERIKSAAN
    // ============================================================
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'hari_diterima'      => 'required|string|max:50',
            'tanggals_diterima'  => 'required|string|max:50',
            'bulan_diterima'     => 'required|string|max:50',
            'tahun_diterima'     => 'required|string|max:50',
            'nama_pihak_kedua'   => 'required|string|max:255',
            'jabatan_pihak_kedua'=> 'required|string|max:255',
            'alamat_pihak_kedua' => 'required|string|max:255',
            'pekerjaan'          => 'required|string|max:255',
        ]);
        //dd($validated);

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->update($validated);

        // Regenerasi dokumen SPJ
        $spj = SPJ::where('pemeriksaan_id', $pemeriksaan->id)->first();
        if ($spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($spj->id);
        }

        return redirect()
            ->route('pemeriksaan', ['id' => $spj->id ?? null])
            ->with('success', 'Data pemeriksaan berhasil diperbarui dan dokumen SPJ diperbarui.');
    }

    
}
