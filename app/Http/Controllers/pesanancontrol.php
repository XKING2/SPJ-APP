<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\SPJ;
use App\Models\Kwitansi;

class PesananControl extends Controller
{
    public function create(Request $request)
    {
        $spj = SPJ::findOrFail($request->spj_id);
        $kwitansi = Kwitansi::findOrFail($request->kwitansi_id);
        return view('users.create.createpesanan', compact('spj', 'kwitansi'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'spj_id'             => 'required|exists:spjs,id',
            'kwitansi_id'        => 'required|exists:kwitansis,id',
            'no_surat'           => 'required|string|max:255',
            'nama_pt'            => 'required|string|max:255',
            'nomor_tlp_pt'       => 'required|numeric',
            'surat_dibuat'       => 'required|date',
            'tanggal_diterima'   => 'required|date',
            'alamat_pt'          => 'required|string|max:255',
            'items'              => 'required|array|min:1',
            'items.*.nama_barang'=> 'required|string',
            'items.*.jumlah'     => 'required|numeric',
        ]);

        // ✅ Buat pesanan utama
        $pesanan = Pesanan::create([
            'spj_id'          => $validated['spj_id'],
            'kwitansi_id'     => $validated['kwitansi_id'],
            'no_surat'        => $validated['no_surat'],
            'nama_pt'         => $validated['nama_pt'],
            'alamat_pt'       => $validated['alamat_pt'],
            'nomor_tlp_pt'    => $validated['nomor_tlp_pt'],
            'tanggal_diterima'=> $validated['tanggal_diterima'],
            'surat_dibuat'    => $validated['surat_dibuat'],
        ]);

        // ✅ Hubungkan pesanan ke SPJ
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['pesanan_id' => $pesanan->id]);

        // ✅ Simpan item menggunakan relasi
        foreach ($validated['items'] as $item) {
            $pesanan->items()->create([
                'nama_barang' => $item['nama_barang'],
                'jumlah'      => $item['jumlah'],
            ]);
        }

        return redirect()
            ->route('pemeriksaan.create', [
                'spj_id'     => $validated['spj_id'],
                'pesanan_id' => $pesanan->id
            ])
            ->with('success', 'Pesanan berhasil disimpan. Lanjut ke pemeriksaan.');
    }

    public function edit($id)
    {
        $pesanan = Pesanan::with(['items', 'spj'])->findOrFail($id);
        

        return view('users.update.updatepesanan', [
            'pesanan' => $pesanan,
            'spj'     => $pesanan->spj,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_surat'             => 'required|string|max:255',
            'nama_pt'              => 'required|string|max:255',
            'nomor_tlp_pt'         => 'required|numeric',
            'alamat_pt'            => 'required|string|max:255',
            'surat_dibuat'         => 'required|date',
            'tanggal_diterima'     => 'required|date',
            'items'                => 'required|array|min:1',
            'items.*.nama_barang'  => 'required|string|max:255',
            'items.*.jumlah'       => 'required|numeric|min:1',
        ]);

        // ✅ Ambil model Pesanan dengan relasi
        $pesanan = Pesanan::with(['items', 'spj'])->findOrFail($id);

        // ✅ Update data utama
        $pesanan->update([
            'no_surat'         => $validated['no_surat'],
            'nama_pt'          => $validated['nama_pt'],
            'nomor_tlp_pt'     => $validated['nomor_tlp_pt'],
            'alamat_pt'        => $validated['alamat_pt'],
            'surat_dibuat'     => $validated['surat_dibuat'],
            'tanggal_diterima' => $validated['tanggal_diterima'],
        ]);

        // ✅ Hapus items lama & buat ulang
        $pesanan->items()->delete();
        foreach ($validated['items'] as $itemData) {
            $pesanan->items()->create($itemData);
        }

        // ✅ Ambil ulang SPJ biar data fresh (sangat penting)
        $spj = \App\Models\Spj::with(['kwitansi', 'penerimaan', 'pesanan.items'])
            ->where('id', $pesanan->spj_id)
            ->first();

        // ✅ Update data di SPJ (sinkronisasi)
        if ($spj) {
            $spj->update([
                'nama_pt'   => $pesanan->nama_pt,
                'no_surat'  => $pesanan->no_surat,
            ]);

            // ✅ Regenerasi dokumen SPJ dengan data terbaru
            app(\App\Http\Controllers\SPJController::class)
                ->generateSPJDocument($spj->id);
        }

        // ✅ Redirect ke halaman preview SPJ
        return redirect()
            ->route('pesanan', ['id' => $pesanan->spj_id])
            ->with('success', 'Pesanan dan dokumen SPJ berhasil diperbarui.');
    }

}
