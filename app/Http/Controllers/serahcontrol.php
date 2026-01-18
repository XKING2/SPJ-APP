<?php

namespace App\Http\Controllers;
use App\Models\Pemeriksaan;
use App\Models\SPJ;
use App\Models\pihakkedua;
use App\Models\Plt;
use App\Models\nosurat;
use App\Models\serahbarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class serahcontrol extends Controller
{
    public function create(Request $request)
    {
    
        $spj = SPJ::findOrFail($request->spj_id);
        $plts = Plt::all();
        $nosurat = Nosurat::orderBy('id', 'desc')->get();
        $keduas = pihakkedua::all();
        $pemeriksaan = Pemeriksaan::findOrFail($request->pemeriksaan_id);

        return view('users.SpjLs.create.createserahbarang', compact(
            'spj',
            'plts',
            'keduas',
            'nosurat',
            'pemeriksaan'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_plt' => 'required|exists:plt,id',
            'id_pihak_kedua' => 'required|exists:pihak_kedua,id',
            'id_pemeriksaan' => 'required|exists:pemeriksaans,id',
            'no_suratsss' => 'required|string',
        ]);

        $serahbarang = serahbarang::create($validated);

        return redirect()->route('penerimaan.create', [
        'spj_id' => $validated['spj_id'],
        'pemeriksaan_id' => $validated['id_pemeriksaan'],
        'id_serahbarang' => $serahbarang->id
        ]);
    }

    public function edit($id,Request $request)
    {

        $serahbarangs = serahbarang::findOrFail($id);
        $plts = Plt::all();
        $spj = $serahbarangs->spj;
        $nosurat = nosurat::latest()->first();
        $keduas = pihakkedua::all();

        return view('users.update.updateserahbarang', compact(
            'plts',
            'keduas',
            'nosurat',
            'serahbarangs',
            'spj'
        ));
    }



    public function update(Request $request, $id)
    {
        $validated = $request->validate([

            'no_suratsss' => 'required|string',
        ]);

        $serahbarang = serahbarang::findOrFail($id);
        $serahbarang->update($validated);

        $spj = SPJ::find($serahbarang->spj_id);

        if (!$spj) {
            Log::error("SPJ dengan ID {$serahbarang->spj_id} tidak ditemukan saat update Kwitansi.");
            return redirect()->back()->with('error', 'Data SPJ tidak ditemukan.');
        }

        if ($spj->status === 'belum_valid') {
            $spj->status = 'draft';
        }
        if ($spj->status2 === 'belum_valid') {
            $spj->status2 = 'draft';
        }

        $spj->save();

        Log::info("✅ SPJ #{$spj->id} berhasil diubah ke status: {$spj->status} / {$spj->status2}");
        if ($spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocumentLs($spj->id);
        }

        return redirect()
            ->route('serahbarang')
            ->with('success', 'Data pemeriksaan berhasil diperbarui dan dokumen SPJ telah Perbaharui.');
    }
}
