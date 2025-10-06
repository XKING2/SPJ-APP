<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authcontroller;
use App\Http\Controllers\sidebarcontrol;
use App\Http\Controllers\kwitansicontrol;
use App\Http\Controllers\pemeriksaancontrol;
use App\Http\Controllers\penerimaancontrol;
use App\Http\Controllers\pesanancontrol;
use App\Http\Controllers\serahterimacontrol;
use App\Http\Controllers\SPJController;

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
Route::get('/ReviewSPJ', [SPJController::class, 'index'])->name('reviewSPJ');
Route::get('/CetakSPJ', [sidebarcontrol::class, 'showcetakSPJ'])->name('cetakSPJ');




Route::get('/spj', [SPJController::class, 'index'])->name('spj.index');
Route::get('/spj/create', [SPJController::class, 'create'])->name('spj.create');
Route::get('spj/review/{spj_id}', [SPJController::class, 'review'])->name('spj.review');
Route::get('/spj/preview/{spj_id}', [SPJController::class, 'preview'])->name('spj.preview');

// ========== KWITANSI ==========
Route::get('/kwitansi/create/{spj_id}', [KwitansiControl::class, 'create'])->name('kwitansi.create');

Route::post('/kwitansi/store', [KwitansiControl::class, 'store'])->name('kwitansis.store');

// ========== PESANAN ==========
Route::get('/pesanan/create', [PesananControl::class, 'create'])->name('pesanan.create');
Route::post('/pesanan/store', [PesananControl::class, 'store'])->name('pesanan.store');

// ========== PEMERIKSAAN ==========
Route::get('/pemeriksaan/create', [PemeriksaanControl::class, 'create'])->name('pemeriksaan.create');
Route::post('/pemeriksaan/store', [PemeriksaanControl::class, 'store'])->name('pemeriksaan.store');

// ========== PENERIMAAN ==========
Route::get('/penerimaan/create', [PenerimaanControl::class, 'create'])->name('penerimaan.create');
Route::post('/penerimaan/store', [PenerimaanControl::class, 'store'])->name('penerimaan.store');


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