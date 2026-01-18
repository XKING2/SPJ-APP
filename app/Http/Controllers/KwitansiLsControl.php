<?php

namespace App\Http\Controllers;

use App\Models\kegiatan;
use App\Models\kwitansi;
use App\Models\kwitansi_kegiatan;
use App\Models\pekerjaans;
use App\Models\pptk;
use App\Models\SPJ;
use Illuminate\Http\Request;

class KwitansiLsControl extends Controller
{
       //KWITANSI\\
    public function create($spj_id)
    {
        $pptks = pptk::all();
        $kegiatanKwitansis = kwitansi_kegiatan::all();
        $spj = SPJ::findOrFail($spj_id);

        return view('users.SpjLS.create.createkwitansils', compact('spj', 'pptks','kegiatanKwitansis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'kwitansi_keg_id' => 'nullable|exists:kegiatan_kwitansis,id',
            'no_rekening' => 'required',
            'penerima_kwitansi' => 'required',
            'telah_diterima_dari' => 'required',
            'jabatan_penerima' => 'required',
        ]);

        $kwitansi = Kwitansi::create($validated);

        $spj = SPJ::findOrFail($validated['spj_id']);

        $spj->update([
            'kwitansi_id' => $kwitansi->id,
            'kegiatan_id' => $validated['id_kegiatan'],
        ]);

        pekerjaans::where('spj_id', $validated['spj_id'])
            ->update([
                'kegiatan_id' => $validated['kwitansi_keg_id']
            ]);

        if ($kwitansi->spj) {
            app(SPJController::class)
                ->generateSPJDocumentLs($kwitansi->spj->id);
        }

        return redirect()
            ->route('reviewSPJ')
            ->with('success', 'Data penerimaan berhasil disimpan dan SPJ telah digenerate otomatis.');
    }

    public function storeAjax(Request $request)
    {
        $data = $request->validate([
            'nama_kegiatan' => 'required|string'
        ]);

        $kegiatan = kwitansi_kegiatan::create($data);

        return response()->json([
            'success' => true,
            'id' => $kegiatan->id, // 🔥 INI KUNCI
            'nama_kegiatan' => $kegiatan->nama_kegiatan,
            'message' => 'Kegiatan berhasil ditambahkan'
        ]);
    }


    public function edit($id)
    {
        $pptks = pptk::all();
        $kegiatans = kegiatan::all();
        $kwitansi = Kwitansi::findOrFail($id);
        $spj = $kwitansi->spj;

        return view('users.update.updatekwitansils', compact('kwitansi','pptks','kegiatans','spj'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'no_rekening' => 'required|string|max:255', 
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);

       
        $kwitansi = Kwitansi::findOrFail($id);
        $kwitansi->update($validated);
 
        $kegiatan = Kegiatan::findOrFail($validated['id_kegiatan']);

        $spj = Spj::findOrFail($validated['spj_id']);

        $spj->update([
            'kegiatan_id' => $kegiatan->id,
            'kwitansi_id' => $kwitansi->id
        ]);

        $spj->feedbacks()->delete();

        if ($spj->status !== 'valid') {
            // hanya ubah jika BUKAN valid
            if ($spj->status === 'belum_valid') {
                $spj->status = 'draft';
            }
        }

        if ($spj->status2 !== 'valid') {
            if ($spj->status2 === 'belum_valid') {
                $spj->status2 = 'draft';
            }
        }

        $spj->resetNotifications();

        $spj->save();

        app(\App\Http\Controllers\SPJController::class)->generateSPJDocumentLs($spj->id);

        return redirect()
            ->route('kwitansils', ['id' => $spj->id])
            ->with('success', 'Kwitansi dan data SPJ berhasil diperbarui serta dokumen SPJ diperbaharui.');
    }
}
