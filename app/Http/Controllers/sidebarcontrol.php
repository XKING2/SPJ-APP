<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sidebarcontrol extends Controller
{
    public function showdashboard()
    {
        return view('users.dashboard');
    }
    public function showkwitansi()
    {
        return view('users.kwitansi');
    }
    public function showpesanan()
    {
        return view('users.pesanan');
    }
    public function showserahterima()
    {
        return view('users.serahterima');
    }
    public function showpenerimaan()
    {
        return view('users.penerimaan');
    }
    public function showpemeriksaan()
    {
        return view('users.pemeriksaan');
    }
    public function showserahbarang()
    {
        return view('users.serahbarang');
    }
    public function showreviewSPJ()
    {
        return view('users.reviewSPJ');
    }
    public function showcetakSPJ()
    {
        return view('users.cetakSPJ');
    }
}
