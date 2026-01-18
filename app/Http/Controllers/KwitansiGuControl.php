<?php

namespace App\Http\Controllers;

use App\Models\kegiatan;
use App\Models\kwitansi;
use App\Models\kwitansi_kegiatan;
use App\Models\pekerjaans;
use App\Models\plt;
use App\Models\pptk;
use App\Models\SPJ;
use App\Models\spj_bukti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KwitansiGuControl extends Controller
{
    public function createkwitansigu($spj_id)
    {
        $pptks = pptk::all();
        $spj = SPJ::findOrFail($spj_id);
        $kegiatanKwitansis = kwitansi_kegiatan::all();
        $plts = plt::all();

        return view('users.SpjGu.creategu.createkwitansigu', compact('spj', 'pptks','plts','kegiatanKwitansis'));
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



    public function editkwitansigu($id)
    {
        $pptks = pptk::all();
        $kegiatans = kegiatan::all();
        $kwitansi = Kwitansi::findOrFail($id);
        $spj = $kwitansi->spj;
        $plts = Plt::all();

        return view('users.updategu.updatekwitansigu', compact('kwitansi','pptks','kegiatans','spj','plts'));
    }

    

    public function updatekwitansigu(Request $request, $id)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'id_plt' => 'required|exists:plt,id',
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

        app(\App\Http\Controllers\SPJController::class)->generateSPJDocumentGu($spj->id);

        return redirect()
            ->route('kwitansigu', ['id' => $spj->id])
            ->with('success', 'Kwitansi dan data SPJ berhasil diperbarui serta dokumen SPJ diperbaharui.');
    }
}
