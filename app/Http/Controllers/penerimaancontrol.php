<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Penerimaan;
use App\Models\Pemeriksaan;
use App\Models\penerimaan_details;

class PenerimaanControl extends Controller
{
    public function create(Request $request)
    {
        $pemeriksaan = Pemeriksaan::with('pesanan.items')->findOrFail($request->pemeriksaan);
        return view('users.create.createpenerimaan', compact('pemeriksaan'));
    }


    // simpan manual dari form
    public function store(Request $request)
    {
        // Simpan header penerimaan
        $penerimaan = Penerimaan::create([
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

        foreach($request->barang as $barang) {
            penerimaan_details::create([
                'penerimaan_id' => $penerimaan->id,
                'pesanan_item_id' => $barang['id'] ?? null,
                'nama_barang' => $barang['nama_barang'],
                'jumlah' => $barang['jumlah'],
                'satuan' => $barang['satuan'],
                'harga_satuan' => $barang['harga_satuan'],
                'total' => $barang['total'],
            ]);
        }
        return redirect()
            ->route('admindashboard')
            ->with('success', 'Data kwitansi berhasil disimpan! Silakan input data berikutnya.');
    }
    
}
