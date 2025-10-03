<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kwitansi;

class kwitansicontrol extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create.createkwitansi');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'no_rekening' => 'required|string|max:255',
            'no_rekening_tujuan' => 'required|string|max:255',
            'nama_bank' => 'required|string|max:255',
            'penerima_kwitansi' => 'required|string|max:255',
            'sub_kegiatan' => 'required|string|max:255',
            'telah_diterima_dari' => 'required|string|max:255',
            'jumlah_nominal' => 'required|numeric',
            'uang_terbilang' => 'required|string|max:255',
            'jabatan_penerima' => 'required|string|max:255',
            'npwp' => 'required|string|max:255',
            'nama_pt' => 'required|string|max:255',
            'pembayaran' => 'required|string',
        ]);

        // Simpan data
        Kwitansi::create($validated);

        // Redirect ke form create lagi, bawa pesan sukses
        return redirect()
            ->route('pesanan.create')
            ->with('success', 'Data kwitansi berhasil disimpan! Silakan input data berikutnya.');
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
