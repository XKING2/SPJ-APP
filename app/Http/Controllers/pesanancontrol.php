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
            'spj_id' => 'required|exists:spjs,id',
            'kwitansi_id' => 'required|exists:kwitansis,id',
            'no_surat' => 'required|string|max:255',
            'nama_pt' => 'required|string|max:255',
            'nomor_tlp_pt' => 'required|numeric',
            'surat_dibuat' => 'required|date',
            'tanggal_diterima' => 'required|date',
            'alamat_pt' => 'required|string|max:255',
            'items.*.nama_barang' => 'required|string',
            'items.*.jumlah' => 'required|numeric',
        ]);

        $pesanan = Pesanan::create([
            'spj_id' => $validated['spj_id'],
            'kwitansi_id' => $validated['kwitansi_id'],
            'no_surat' => $validated['no_surat'],
            'nama_pt' => $validated['nama_pt'],
            'alamat_pt' => $validated['alamat_pt'],
            'nomor_tlp_pt' => $validated['nomor_tlp_pt'],
            'tanggal_diterima' => $validated['tanggal_diterima'],
            'surat_dibuat' => $validated['surat_dibuat'],
        ]);
        $spj = SPJ::findOrFail($validated['spj_id']);
        $spj->update(['pesanan_id' => $pesanan->id]);

        foreach ($validated['items'] as $item) {
            PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'nama_barang' => $item['nama_barang'],
                'jumlah' => $item['jumlah'],
            ]);
        }

        return redirect()
            ->route('pemeriksaan.create', ['spj_id' => $validated['spj_id'], 'pesanan_id' => $pesanan->id])
            ->with('success', 'Pesanan berhasil disimpan. Lanjut ke pemeriksaan.');
    }
}
