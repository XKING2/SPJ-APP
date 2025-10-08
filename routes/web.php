<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authcontroller;
use App\Http\Controllers\sidebarcontrol;
use App\Http\Controllers\kwitansicontrol;
use App\Http\Controllers\pemeriksaancontrol;
use App\Http\Controllers\penerimaancontrol;
use App\Http\Controllers\pesanancontrol;
use App\Http\Controllers\serahterimacontrol;
use App\Http\Controllers\sidebarcontrol3;
use App\Http\Controllers\SPJController;



Route::get('/User/Dashboard', [sidebarcontrol::class, 'showdashboard1'])->name('userdashboard');
Route::get('/Kwitansi', [sidebarcontrol::class, 'showkwitansi'])->name('kwitansi');
Route::get('/Pesanan', [sidebarcontrol::class, 'showpesanan'])->name('pesanan');
Route::get('/Serahterima', [sidebarcontrol::class, 'showserahterima'])->name('serahterima');
Route::get('/Penerimaan', [sidebarcontrol::class, 'showpenerimaan'])->name('penerimaan');
Route::get('/Pemeriksaan', [sidebarcontrol::class, 'showpemeriksaan'])->name('pemeriksaan');
Route::get('/Serahbarang', [sidebarcontrol::class, 'showserahbarang'])->name('serahbarang');
Route::get('/ReviewSPJ', [sidebarcontrol::class, 'showreviewSPJ'])->name('reviewSPJ');
Route::get('/CetakSPJ', [sidebarcontrol::class, 'showcetakSPJ'])->name('cetakSPJ');

Route::get('/Superadmin/Dashboard', [sidebarcontrol3::class, 'showdashboard3'])->name('superdashboard');
Route::get('/Superadmin/Validasi', [sidebarcontrol3::class, 'showvalidasi'])->name('Validasi');
Route::get('/Superadmin/preview/{id}', [sidebarcontrol3::class, 'previewsuper'])->name('previewsuper');
Route::post('/Superadmin/validasi/{id}/update-status', [sidebarcontrol3::class, 'updateStatusKasubag'])
    ->name('updateStatusKasubag');


Route::get('/spj', [SPJController::class, 'index'])->name('spj.index');
Route::get('/spj/create', [SPJController::class, 'create'])->name('spj.create');
// 🔹 Tampilkan preview SPJ (hasil generate PDF)
Route::get('/spj/preview/{id}', [SPJController::class, 'preview'])->name('spj.preview');




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


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 🔒 Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 🧭 Dashboard Routes
Route::middleware(['auth', 'session.timeout', 'role:superadmin'])->group(function () {
    Route::get('/super/dashboard', fn() => view('superadmins.dashboard'))->name('superadmins.dashboard');
});

Route::middleware(['auth', 'session.timeout', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admin.dashboard'))->name('admin.dashboard');
});

Route::middleware(['auth', 'session.timeout', 'role:user'])->group(function () {
    Route::get('/user/dashboard', fn() => view('users.dashboard'))->name('users.dashboard');
});