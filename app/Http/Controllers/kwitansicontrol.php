<?php

namespace App\Http\Controllers;

use App\Models\kegiatan;
use Illuminate\Http\Request;
use App\Models\Kwitansi;
use App\Models\plt;
use App\Models\SPJ;
use App\Models\pptk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class KwitansiControl extends Controller
{
    public function create($spj_id)
    {
        $pptks = Pptk::all();
        $spj = SPJ::findOrFail($spj_id);

        return view('users.create.createkwitansils', compact('spj', 'pptks'));
    }

    public function creategu($spj_id)
    {
        $pptks = Pptk::all();
        $spj = SPJ::findOrFail($spj_id);
        $plts = Plt::all();

        return view('users.creategu.createkwitansigu', compact('spj', 'pptks','plts'));
    }

    public function store(Request $request)
    {
        Log::info('📥 [KwitansiController@store] Mulai proses simpan kwitansi', [
            'input' => $request->all()
        ]);

        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'no_rekening' => 'required|string|max:255',   // ⬅️ tambahkan
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);

        // Ambil langsung dari hidden input (sudah ada titik dari JS)
        $validated['no_rekening'] = $request->no_rekening;

        // Simpan kwitansi
        $kwitansi = Kwitansi::create($validated);

        // Update SPJ
        $kegiatan = kegiatan::findOrFail($validated['id_kegiatan']);
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update([
            'kwitansi_id' => $kwitansi->id,
            'kegiatan_id' => $kegiatan->id,
        ]);

        return redirect()
            ->route('pesanan.create', [
                'spj_id' => $validated['spj_id'],
                'kwitansi_id' => $kwitansi->id
            ])
            ->with('success', 'Kwitansi berhasil disimpan dan kegiatan berhasil dihubungkan ke SPJ.');
    }

    public function storels(Request $request)
    {
        Log::info('📥 [KwitansiController@store] Mulai proses simpan kwitansi', [
            'input' => $request->all()
        ]);

        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'id_plt' => 'required|exists:plt,id',
            'no_rekening' => 'required|string|max:255',   // ⬅️ tambahkan
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);

        // Ambil langsung dari hidden input (sudah ada titik dari JS)
        $validated['no_rekening'] = $request->no_rekening;

        // Simpan kwitansi
        $kwitansi = Kwitansi::create($validated);

        // Update SPJ
        $kegiatan = kegiatan::findOrFail($validated['id_kegiatan']);
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update([
            'kwitansi_id' => $kwitansi->id,
            'kegiatan_id' => $kegiatan->id,
        ]);

        return redirect()
            ->route('pesananls.create', [
                'spj_id' => $validated['spj_id'],
                'kwitansi_id' => $kwitansi->id
            ])
            ->with('success', 'Kwitansi berhasil disimpan dan kegiatan berhasil dihubungkan ke SPJ.');
    }






    public function edit($id)
    {
        $pptks = pptk::all();
        $kegiatans = kegiatan::all();
        $kwitansi = Kwitansi::findOrFail($id);
        $spj = $kwitansi->spj;

        return view('users.update.updatekwitansils', compact('kwitansi','pptks','kegiatans','spj'));
    }

    public function editgu($id)
    {
        $pptks = pptk::all();
        $kegiatans = kegiatan::all();
        $kwitansi = Kwitansi::findOrFail($id);
        $spj = $kwitansi->spj;
        $plts = Plt::all();

        return view('users.updategu.updatekwitansigu', compact('kwitansi','pptks','kegiatans','spj','plts'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'id_plt' => 'required|exists:plt,id',
            'no_rekening' => 'required|string|max:255',   // ⬅️ tambahkan
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);


        Log::info('✏️ [KwitansiController@update] Mulai proses update kwitansi', [
            'kwitansi_id' => $id,
            'input' => $validated
        ]);
        $kwitansi = Kwitansi::findOrFail($id);
        $kwitansi->update($validated);

        // 🔹 Ambil kegiatan 
        $kegiatan = Kegiatan::findOrFail($validated['id_kegiatan']);

        // 🔹 Ambil data SPJ terkait
        $spj = Spj::findOrFail($validated['spj_id']);

        // 🔹 Perbarui relasi kegiatan dan kasubag di tabel SPJ
        $spj->update([
            'kegiatan_id' => $kegiatan->id,
            'kwitansi_id' => $kwitansi->id
        ]);

        // 🔹 Reset feedback & ubah status SPJ bila perlu
        $spj->feedbacks()->delete();
        if ($spj->status === 'belum_valid') $spj->status = 'draft';
        if ($spj->status2 === 'belum_valid') $spj->status2 = 'draft';
        $spj->save();

        // 🔹 Generate ulang dokumen SPJ
        app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($spj->id);

        return redirect()
            ->route('kwitansils', ['id' => $spj->id])
            ->with('success', 'Kwitansi dan data SPJ berhasil diperbarui serta dokumen SPJ diperbaharui.');
    }

    public function updatels(Request $request, $id)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'id_pptk' => 'required|exists:pptk,id',
            'id_kegiatan' => 'required|exists:kegiatan,id',
            'no_rekening' => 'required|string|max:255',   // ⬅️ tambahkan
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);


        Log::info('✏️ [KwitansiController@update] Mulai proses update kwitansi', [
            'kwitansi_id' => $id,
            'input' => $validated
        ]);
        $kwitansi = Kwitansi::findOrFail($id);
        $kwitansi->update($validated);

        // 🔹 Ambil kegiatan 
        $kegiatan = Kegiatan::findOrFail($validated['id_kegiatan']);

        // 🔹 Ambil data SPJ terkait
        $spj = Spj::findOrFail($validated['spj_id']);

        // 🔹 Perbarui relasi kegiatan dan kasubag di tabel SPJ
        $spj->update([
            'kegiatan_id' => $kegiatan->id,
            'kwitansi_id' => $kwitansi->id
        ]);

        // 🔹 Reset feedback & ubah status SPJ bila perlu
        $spj->feedbacks()->delete();
        if ($spj->status === 'belum_valid') $spj->status = 'draft';
        if ($spj->status2 === 'belum_valid') $spj->status2 = 'draft';
        $spj->save();

        // 🔹 Generate ulang dokumen SPJ
        app(\App\Http\Controllers\SPJController::class)->generateSPJDocumentls($spj->id);

        return redirect()
            ->route('kwitansigu', ['id' => $spj->id])
            ->with('success', 'Kwitansi dan data SPJ berhasil diperbarui serta dokumen SPJ diperbaharui.');
    }


        // 🔹 AJAX: Ambil daftar subkegiatan berdasarkan PPTK 
    public function getSubKegiatan($pptk_id) 
    { 
        $kegiatan = Kegiatan::where('id_pptk', $pptk_id) ->select('id', 'subkegiatan') 
        ->get(); return response()
        ->json($kegiatan); 
    }

    public function getNoRekSub($id)
    {
        $kegiatan = kegiatan::find($id);

        if (!$kegiatan) {
            return response()->json(['no_rek_sub' => null]);
        }

        return response()->json([
            'no_rek_sub' => $kegiatan->no_rek_sub
        ]);
    }



    public function showKwitansiLS(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id(); // lebih aman dan dikenali Intelephense

        $kwitansis = Kwitansi::with('spj')
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('types', 'ls')
                ->where('user_id', $userId); // filter hanya milik user
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('pembayaran', 'like', "%{$search}%")
                        ->orWhere('no_rekening', 'like', "%{$search}%")
                        ->orWhere('nama_pt', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.kwitansils', compact('kwitansis', 'search'));
    }






    public function showKwitansiGU(Request $request)
    {
        $search = $request->input('search');
        $userId = Auth::id(); // lebih aman dan dikenali Intelephense

        $kwitansis = Kwitansi::with('spj')
            ->whereHas('spj', function ($q) use ($userId) {
                $q->where('types', 'gu')
                ->where('user_id', $userId); // filter hanya milik user
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('pembayaran', 'like', "%{$search}%")
                        ->orWhere('no_rekening', 'like', "%{$search}%")
                        ->orWhere('nama_pt', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.kwitansigu', compact('kwitansis', 'search'));
    }

}
