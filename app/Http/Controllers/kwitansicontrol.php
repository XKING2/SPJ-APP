<?php

namespace App\Http\Controllers;

use App\Models\kegiatan;
use Illuminate\Http\Request;
use App\Models\Kwitansi;
use App\Models\SPJ;
use App\Models\pptk;
use Illuminate\Support\Facades\Log;

class KwitansiControl extends Controller
{
    public function create($spj_id)
    {
        $pptks = Pptk::all();
        $spj = SPJ::findOrFail($spj_id);

        return view('users.create.createkwitansi', compact('spj', 'pptks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'sub_kegiatan' => 'required|string|max:255',
            'no_rekening' => 'required|string|max:255',
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jumlah_nominal' => 'required|numeric',
            'uang_terbilang' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);

        $kwitansi = Kwitansi::create($validated);

        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['kwitansi_id' => $kwitansi->id]);

        return redirect()
            ->route('pesanan.create', [
                'spj_id' => $validated['spj_id'],
                'kwitansi_id' => $kwitansi->id
            ])
            ->with('success', 'Kwitansi berhasil disimpan. Lanjut ke pesanan.');
    }

    // 🔹 Fungsi tambahan untuk ambil sub kegiatan berdasarkan PPTK
    public function getSubKegiatan($pptk_id)
    {
        $kegiatan = kegiatan::where('id_pptk', $pptk_id)
            ->select('id', 'subkegiatan')
            ->get();

        return response()->json($kegiatan);
    }


    public function edit($id)
    {
        $pptks = pptk::all();
        $kwitansi = Kwitansi::findOrFail($id);

        return view('users.update.updatekwitansi', compact('kwitansi','pptks'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_rekening' => 'required|string|max:255',
            'id_pptk' => 'required|exists:pptk,id',
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'sub_kegiatan' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jumlah_nominal' => 'required|numeric',
            'uang_terbilang' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'pembayaran' => 'required|string',
        ]);

        $kwitansi = Kwitansi::findOrFail($id);
        $kwitansi->update($validated);

        $spj = Spj::find($kwitansi->spj_id);

        if (!$spj) {
            Log::error("SPJ dengan ID {$kwitansi->spj_id} tidak ditemukan saat update Kwitansi.");
            return redirect()->back()->with('error', 'Data SPJ tidak ditemukan.');
        }

        if ($spj) {
            $spj->feedbacks()->delete();
            if ($spj->status === 'belum_valid') $spj->status = 'draft';
            if ($spj->status2 === 'belum_valid') $spj->status2 = 'draft';
            $spj->save();
        }

        $spj->save();

        Log::info("✅ SPJ #{$spj->id} berhasil diubah ke status: {$spj->status} / {$spj->status2}");

        if ($spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($spj->id);
        }

        return redirect()
            ->route('kwitansi', ['id' => $spj->id ?? null])
            ->with('success', 'Data Kwitansi berhasil diperbarui dan dokumen SPJ Diperbaharui.');
    }
}
