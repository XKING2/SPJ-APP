<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pesanan;
use App\Models\pesananitem;

class pesanancontrol extends Controller
{
    /**
     * Display a listing of the resource.
     */


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create.createpesanan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required',
            'nama_pt' => 'required',
            'alamat_pt' => 'required',
            'nomor_tlp_pt' => 'required',
        ]);

        $pesanan = Pesanan::create($request->only([
            'no_surat','nama_pt','alamat_pt','tanggal_diterima','surat_dibuat','nomor_tlp_pt'
        ]));

        // simpan items
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                PesananItem::create([
                    'pesanan_id' => $pesanan->id,
                    'nama_barang' => $item['nama_barang'],
                    'jumlah' => $item['jumlah'],
                ]);
            }
        }

        // redirect ke pemeriksaan/create dengan pesanan_id
        return redirect()->route('pemeriksaan.create', ['pesanan_id' => $pesanan->id]);
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
