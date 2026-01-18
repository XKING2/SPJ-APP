<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\kwitansi;
use App\Models\kwitansi_kegiatan;
use App\Models\pekerjaans;
use App\Models\plt;
use App\Models\pptk;
use App\Models\SPJ;
use Illuminate\Support\Facades\DB;



class KwitansiPoControl extends Controller
{
    public function createkwitansiPo($spj_id)
    {
        $pptks = pptk::all();
        $spj = SPJ::findOrFail($spj_id);
        $kegiatanKwitansis = kwitansi_kegiatan::all();
        $plts = plt::all();

        return view('users.SpjPo.createPo.createkwitansipo', compact('spj', 'pptks','plts','kegiatanKwitansis'));
    }

    

    public function storekwitansigu(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'kwitansi_keg_id' => 'required|exists:kegiatan_kwitansis,id',
            'id_plt' => 'required|exists:plt,id',
            'no_rekening' => 'required|string',
            'penerima_kwitansi' => 'required|string',
            'telah_diterima_dari' => 'required|string',
            'jabatan_penerima' => 'required|string',
            'pekerjaan' => 'required|string',
        ]);

        DB::transaction(function () use ($validated) {

            Kwitansi::create([
                'spj_id' => $validated['spj_id'],
                'id_pptk' => $validated['id_pptk'],
                'id_kegiatan' => $validated['id_kegiatan'],
                'kwitansi_keg_id' => $validated['kwitansi_keg_id'],
                'id_plt' => $validated['id_plt'],
                'no_rekening' => $validated['no_rekening'],
                'penerima_kwitansi' => $validated['penerima_kwitansi'],
                'telah_diterima_dari' => $validated['telah_diterima_dari'],
                'jabatan_penerima' => $validated['jabatan_penerima'],
            ]);

            SPJ::where('id', $validated['spj_id'])
                ->update([
                    'kegiatan_id' => $validated['id_kegiatan'],
                ]);

            pekerjaans::create([
                'spj_id' => $validated['spj_id'],
                'kegiatan_id' => $validated['kwitansi_keg_id'],
                'pekerjaan' => trim($validated['pekerjaan']),
            ]);


        });

        app(SpjController::class)
            ->generateSPJDocumentls($validated['spj_id']);

        return redirect()->route('reviewSPJ')
            ->with('success', 'Kwitansi berhasil disimpan');
    }
}
