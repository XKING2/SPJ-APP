<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penerimaan;
use App\Models\penerimaan_details;
use App\Models\Pemeriksaan;
use App\Models\SPJ;
use App\Http\Controllers\SPJController; 

class PenerimaanControl extends Controller
{
    public function create(Request $request)
    {
        $spj = SPJ::findOrFail($request->spj_id);
        $pemeriksaan = Pemeriksaan::findOrFail($request->pemeriksaan_id);

        return view('users.create.createpenerimaan', compact('spj', 'pemeriksaan'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id' => 'required|exists:spjs,id',
            'pemeriksaan_id' => 'required|exists:pemeriksaans,id',
            'no_surat' => 'required|string|max:255',
            'surat_dibuat' => 'required|date',
            'subtotal' => 'required|numeric',
            'ppn' => 'nullable|numeric',
            'grandtotal' => 'required|numeric',
            'dibulatkan' => 'nullable|numeric',
            'terbilang' => 'required|string|max:255',
            'barang.*.nama_barang' => 'required|string',
            'barang.*.jumlah' => 'required|numeric',
            'barang.*.satuan' => 'required|string',
            'barang.*.harga_satuan' => 'required|numeric',
            'barang.*.total' => 'required|numeric',
        ]);

        // 🔹 1. Simpan data penerimaan
        $penerimaan = Penerimaan::create([
            'spj_id' => $request->spj_id,
            'pemeriksaan_id' => $request->pemeriksaan_id,
            'pesanan_id' => $request->pesanan_id,
            'pekerjaan' => $request->pekerjaan,
            'no_surat' => $request->no_surat,
            'surat_dibuat' => $request->surat_dibuat,
            'nama_pihak_kedua' => $request->nama_pihak_kedua,
            'jabatan_pihak_kedua' => $request->jabatan_pihak_kedua,
            'subtotal' => $request->subtotal,
            'ppn' => $request->ppn,
            'grandtotal' => $request->grandtotal,
            'dibulatkan' => $request->dibulatkan,
            'terbilang' => $request->terbilang,
        ]);

        // 🔹 2. Update SPJ agar punya penerimaan_id
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['penerimaan_id' => $penerimaan->id]);

        // 🔹 3. Simpan detail barang
        // 🔹 3. Simpan detail barang
        foreach ($validated['barang'] as $item) {
            penerimaan_details::create([
                'penerimaan_id' => $penerimaan->id,
                'nama_barang' => $item['nama_barang'],
                'jumlah' => $item['jumlah'],
                'satuan' => $item['satuan'],
                'harga_satuan' => $item['harga_satuan'],
                'total' => $item['total'],
            ]);
        }

        // 🔹 4. Panggil fungsi generate dokumen SPJ otomatis dari SPJController
        $spjController = new SPJController();
        $spjController->generateSPJDocument($spj->id);

        // 🔹 5. Redirect ke daftar SPJ
        return redirect()
            ->route('reviewSPJ')
            ->with('success', 'Data penerimaan berhasil disimpan dan SPJ telah digenerate otomatis.');

    }

    public function edit($id)
    {
        $penerimaan = Penerimaan::with(['spj', 'details'])->findOrFail($id);
        $spj = $penerimaan->spj;
        $pemeriksaan = $spj ? $spj->pemeriksaan : null;

        return view('users.update.updatepenerimaan', compact('penerimaan', 'spj', 'pemeriksaan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_surat' => 'required|string|max:255',
            'surat_dibuat' => 'required|date',
            'subtotal' => 'required|numeric',
            'ppn' => 'nullable|numeric',
            'grandtotal' => 'required|numeric',
            'dibulatkan' => 'nullable|numeric',
            'terbilang' => 'required|string|max:255',
            'barang' => 'required|array|min:1',
            'barang.*.nama_barang' => 'required|string|max:255',
            'barang.*.jumlah' => 'required|numeric|min:1',
            'barang.*.satuan' => 'required|string|max:50',
            'barang.*.harga_satuan' => 'required|numeric|min:0',
            'barang.*.total' => 'required|numeric|min:0',
        ]);

        // 🔹 Ambil data penerimaan beserta detailnya
        $penerimaan = Penerimaan::with(['spj', 'details'])->findOrFail($id);

        // 🔹 Update data utama
        $penerimaan->update([
            'no_surat'     => $validated['no_surat'],
            'surat_dibuat' => $validated['surat_dibuat'],
            'subtotal'     => $validated['subtotal'],
            'ppn'          => $validated['ppn'],
            'grandtotal'   => $validated['grandtotal'],
            'dibulatkan'   => $validated['dibulatkan'],
            'terbilang'    => $validated['terbilang'],
        ]);

        // 🔹 Hapus detail lama, buat ulang yang baru
        $penerimaan->details()->delete();

        foreach ($validated['barang'] as $item) {
            $penerimaan->details()->create([
                'nama_barang'   => $item['nama_barang'],
                'jumlah'        => $item['jumlah'],
                'satuan'        => $item['satuan'],
                'harga_satuan'  => $item['harga_satuan'],
                'total'         => $item['total'],
            ]);
        }

        // 🔹 Regenerasi SPJ
        $spj = $penerimaan->spj;
        if ($spj) {
            app(\App\Http\Controllers\SPJController::class)->generateSPJDocument($spj->id);
        }

        // 🔹 Redirect sukses
        return redirect()
            ->route('penerimaan', ['id' => $spj->id ?? null])
            ->with('success', 'Data penerimaan berhasil diperbarui dan dokumen SPJ telah diregenerasi.');
    }

}
