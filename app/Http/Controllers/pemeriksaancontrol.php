<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pemeriksaan;
use App\Models\Pesanan;
use App\Http\Controllers\PenerimaanControl;


class pemeriksaancontrol extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pesanan = Pesanan::findOrFail($request->pesanan_id);
        return view('users.create.createpemeriksaan', compact('pesanan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'hari_diterima' => 'required',
            'tanggal_diterima' => 'required',
            'bulan_diterima' => 'required',
            'tahun_diterima' => 'required',
            'nama_pihak_kedua' => 'required',
            'jabatan_pihak_kedua' => 'required',
            'alamat_pihak_kedua' => 'required',
            'pekerjaan' => 'required',
        ]);

        $pemeriksaan = Pemeriksaan::create($request->all());
        return redirect()
            ->route('penerimaan.create', ['pemeriksaan' => $pemeriksaan->id])
            ->with('success', 'Pemeriksaan & Penerimaan berhasil dibuat otomatis, silakan lengkapi data di form penerimaan.');
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
