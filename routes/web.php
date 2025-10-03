<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authcontroller;
use App\Http\Controllers\sidebarcontrol;
use App\Http\Controllers\kwitansicontrol;
use App\Http\Controllers\pemeriksaancontrol;
use App\Http\Controllers\penerimaancontrol;
use App\Http\Controllers\pesanancontrol;
use App\Http\Controllers\serahterimacontrol;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/Dashboard', [sidebarcontrol::class, 'showdashboard'])->name('admindashboard');
Route::get('/Kwitansi', [sidebarcontrol::class, 'showkwitansi'])->name('kwitansi');
Route::get('/Pesanan', [sidebarcontrol::class, 'showpesanan'])->name('pesanan');
Route::get('/Serahterima', [sidebarcontrol::class, 'showserahterima'])->name('serahterima');
Route::get('/Penerimaan', [sidebarcontrol::class, 'showpenerimaan'])->name('penerimaan');
Route::get('/Pemeriksaan', [sidebarcontrol::class, 'showpemeriksaan'])->name('pemeriksaan');
Route::get('/Serahbarang', [sidebarcontrol::class, 'showserahbarang'])->name('serahbarang');
Route::get('/ReviewSPJ', [sidebarcontrol::class, 'showreviewSPJ'])->name('reviewSPJ');
Route::get('/CetakSPJ', [sidebarcontrol::class, 'showcetakSPJ'])->name('cetakSPJ');

Route::get('/kwitansi/create', [kwitansicontrol::class, 'create'])->name('kwitansi.create');
Route::get('/pesanan/create', [pesanancontrol::class, 'create'])->name('pesanan.create');
Route::get('/pemeriksaan/create', [PemeriksaanControl::class, 'create'])->name('pemeriksaan.create');

Route::get('/penerimaan/create', [PenerimaanControl::class, 'create'])->name('penerimaan.create');
Route::post('/penerimaan', [PenerimaanControl::class, 'store'])->name('penerimaan.store');
Route::get('/serahterima/create', [serahterimacontrol::class, 'create'])->name('serahterima.create');

Route::resource('kwitansis', kwitansicontrol::class);
Route::resource('pesanans', pesanancontrol::class);
Route::resource('pemeriksaan', pemeriksaancontrol::class);

// Dashboard untuk Superuser
Route::get('/super/dashboard', function () {
    return "Halo Superuser!";
})->middleware('role:superadmin')->name('super.dashboard');

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware('role:admin')->name('admin.dashboard');

Route::get('/user/dashboard', function () {
    return view('users.dashboard');
})->middleware('role:user')->name('user.dashboard');