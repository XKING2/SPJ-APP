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
use App\Http\Controllers\sidebarcontrol2;
use App\Http\Controllers\SPJController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\settingcontrol;
use App\Http\Controllers\spjresponcontrol;


Route::get('/', function () {
    return redirect()->route('login');
});
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
Route::get('/superadmin/anggota', [sidebarcontrol3::class, 'showanggota'])->name('showanggota');
Route::get('anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
Route::post('anggota', [AnggotaController::class, 'store'])->name('anggota.store');
Route::get('/anggota/{anggotum}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
Route::put('anggota/{anggotum}', [AnggotaController::class, 'update'])->name('anggota.update');
Route::DELETE('anggota/{anggotum}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');

Route::get('/settings', [settingcontrol::class, 'index'])->name('settings.index');
Route::post('/settings', [settingcontrol::class, 'update'])->name('settings.update');
Route::get('/Superadmin/pptk', [settingcontrol::class, 'showpptk'])->name('showpptk');
Route::get('/Superadmin/pptk/create', [settingcontrol::class, 'createpptk'])->name('createpptk');
Route::post('pptks', [settingcontrol::class, 'store'])->name('pptk.store');
Route::get('/pptk/{id}/edit', [settingcontrol::class, 'editpptk'])->name('pptk.edit');
Route::put('/pptk/{id}', [settingcontrol::class, 'updatepptk'])->name('pptk.update');
Route::DELETE('/pptk/{id}', [settingcontrol::class, 'destroy'])->name('pptk.destroy');

Route::get('/Superadmin/plt', [settingcontrol::class, 'showplt'])->name('showplt');
Route::get('/Superadmin/plt/create', [settingcontrol::class, 'createplt'])->name('createplt');
Route::post('plt', [settingcontrol::class, 'storeplt'])->name('plt.store');
Route::get('/plt/{id}/edit', [settingcontrol::class, 'editplt'])->name('plt.edit');
Route::put('/plt/{id}', [settingcontrol::class, 'updateplt'])->name('plt.update');
Route::DELETE('/plt/{id}', [settingcontrol::class, 'destroyplt'])->name('plt.destroy');



Route::get('/admin/Dashboard', [sidebarcontrol2::class, 'showdashboard2'])->name('admindashboard');
Route::get('/admin/preview/{id}', [sidebarcontrol2::class, 'previewadmin'])->name('previewadmin');
Route::get('/admin/verivikasi', [sidebarcontrol2::class, 'showverivikasi'])->name('verivikasi');
Route::post('/admin/verivikasi/{id}/update-status', [sidebarcontrol2::class, 'updateStatusbendahara'])
    ->name('updateStatusbendahara');
Route::get('/spj', [SPJController::class, 'index'])->name('spj.index');
Route::get('/spj/create', [SPJController::class, 'create'])->name('spj.create');
// 🔹 Tampilkan preview SPJ (hasil generate PDF)
Route::get('/spj/preview/{id}', [SPJController::class, 'preview'])->name('spj.preview');
Route::post('/spj/{id}/submit-bendahara', [SpjController::class, 'submitToBendahara'])->name('spj.submitToBendahara');
Route::post('/spj/{id}/ajukan-kasubag', [SPJController::class, 'ajukanKasubag'])->name('ajukanKasubag');
Route::get('/spj/cetak/{id}', [SPJController::class, 'cetak'])->name('spj.cetak');
Route::DELETE('/spj/{id}', [SPJController::class, 'destroy'])->name('spj.destroy');



Route::post('/spj/{spjId}/revisi', [spjresponcontrol::class, 'store'])
    ->name('spj.revisi.store');





// ========== KWITANSI ==========
Route::get('/kwitansi/create/{spj_id}', [KwitansiControl::class, 'create'])->name('kwitansi.create');
Route::post('/kwitansi/store', [KwitansiControl::class, 'store'])->name('kwitansis.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/kwitansi/{id}/edit', [KwitansiControl::class, 'edit'])->name('kwitansi.edit');
    Route::put('/kwitansi/{id}', [KwitansiControl::class, 'update'])->name('kwitansi.update');
});


// ========== PESANAN ==========
Route::get('/pesanan/create', [PesananControl::class, 'create'])->name('pesanan.create');
Route::post('/pesanan/store', [PesananControl::class, 'store'])->name('pesanan.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan/{id}/edit', [pesanancontrol::class, 'edit'])->name('pesanan.edit');
    Route::put('/pesanan/{id}', [pesanancontrol::class, 'update'])->name('pesanan.update');
});

Route::delete('/pesanan/item/{item}', [PesananControl::class, 'destroyItem'])
    ->name('pesanan.item.destroy');

// ========== PEMERIKSAAN ==========
Route::get('/pemeriksaan/create', [PemeriksaanControl::class, 'create'])->name('pemeriksaan.create');
Route::post('/pemeriksaan/store', [PemeriksaanControl::class, 'store'])->name('pemeriksaan.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/pemeriksaan/{id}/edit', [PemeriksaanControl::class, 'edit'])->name('pemeriksaan.edit');
    Route::put('/pemeriksaan/{id}', [PemeriksaanControl::class, 'update'])->name('pemeriksaan.update');
});

// ========== PENERIMAAN ==========
Route::get('/penerimaan/create', [PenerimaanControl::class, 'create'])->name('penerimaan.create');
Route::post('/penerimaan/store', [PenerimaanControl::class, 'store'])->name('penerimaan.store');
Route::get('/penerimaan/{id}/edit', [PenerimaanControl::class, 'edit'])->name('penerimaan.edit');
Route::put('/penerimaan/{id}', [PenerimaanControl::class, 'update'])->name('penerimaan.update');
Route::delete('/pesanan/item/{item}', [PesananControl::class, 'destroyItem'])
    ->name('pesanan.item.destroy');




Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// 🔒 Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// 🧭 Dashboard Routes
Route::middleware(['auth', 'session.timeout', 'role:superadmin'])->group(function () {
    Route::get('/super/dashboard', fn() => view('superadmins.dashboard'))->name('superadmins.dashboard');
});

Route::middleware(['auth', 'session.timeout', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', fn() => view('admins.dashboard'))->name('admins.dashboard');
});

Route::middleware(['auth', 'session.timeout', 'role:user'])->group(function () {
    Route::get('/user/dashboard', fn() => view('users.dashboard'))->name('users.dashboard');
});

