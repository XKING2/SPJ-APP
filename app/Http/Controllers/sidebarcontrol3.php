<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class sidebarcontrol3 extends Controller
{
    public function showdashboard3()
    {
        return view('superadmins.dashboard');
    }

    public function showvalidasi()
    {
        return view('superadmins.validasi');
    }
}
