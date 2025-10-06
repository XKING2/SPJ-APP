<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kwitansi;
use App\Models\SPJ;

class KwitansiControl extends Controller
{
    public function create($spj_id)
    {
        $spj = SPJ::findOrFail($spj_id);
        return view('users.create.createkwitansi', compact('spj'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'no_rekening' => 'required|string|max:255',
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'sub_kegiatan' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jumlah_nominal' => 'required|numeric',
            'uang_terbilang' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'nama_pt' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);

        $kwitansi = Kwitansi::create($validated);

        // Update relasi ke SPJ
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['kwitansi_id' => $kwitansi->id]);


        return redirect()
            ->route('pesanan.create', ['spj_id' => $validated['spj_id'], 'kwitansi_id' => $kwitansi->id])
            ->with('success', 'Kwitansi berhasil disimpan. Lanjut ke pesanan.');
    }
}
