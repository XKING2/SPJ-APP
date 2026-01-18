<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authcontroller;
use App\Http\Controllers\sidebarcontrol;
use App\Http\Controllers\kwitansicontrol;
use App\Http\Controllers\pemeriksaancontrol;
use App\Http\Controllers\penerimaancontrol;
use App\Http\Controllers\pesanancontrol;
use App\Http\Controllers\sidebarcontrol3;
use App\Http\Controllers\sidebarcontrol2;
use App\Http\Controllers\SPJController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\bukticontrol;
use App\Http\Controllers\chatcontrol;
use App\Http\Controllers\KwitansiGuControl;
use App\Http\Controllers\KwitansiLsControl;
use App\Http\Controllers\KwitansiPoControl;
use App\Http\Controllers\PesananGuControl;
use App\Http\Controllers\PesananLsControl;
use App\Http\Controllers\serahcontrol;
use App\Http\Controllers\settingcontrol;
use App\Http\Controllers\spjresponcontrol;
use App\Http\Controllers\validasicontrol;
use App\Http\Controllers\verivycontrol;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard Routes
Route::middleware(['auth', 'session.timeout', 'role:Kasubag'])->group(function () {
    Route::get('/Superadmin/Dashboard', [sidebarcontrol3::class, 'showdashboard3'])->name('superdashboard');

    Route::get('/Superadmin/preview/{id}', [sidebarcontrol3::class, 'previewsuper'])->name('previewsuper');
    
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
    Route::get('/Superadmin/PihakKedua', [settingcontrol::class, 'showkedua'])->name('showkedua');
    Route::get('/Superadmin/PihakKedua/create', [settingcontrol::class, 'createkedua'])->name('createkedua');
    Route::post('PihakKedua', [settingcontrol::class, 'storekedua'])->name('kedua.store');
    Route::get('/PihakKedua/{id}/edit', [settingcontrol::class, 'editkedua'])->name('kedua.edit');
    Route::put('/PihakKedua/{id}', [settingcontrol::class, 'updatekedua'])->name('kedua.update');
    Route::DELETE('/PihakKedua/{id}', [settingcontrol::class, 'destroykedua'])->name('kedua.destroy');
    Route::get('/Superadmin/nosurat', [settingcontrol::class, 'shownosurat'])->name('shownosurat');
    Route::get('/Superadmin/nosurat/create', [settingcontrol::class, 'createnosurat'])->name('createnosurat');
    Route::post('nosurat', [settingcontrol::class, 'storenosurat'])->name('nosurat.store');
    Route::get('/nosurat/{id}/edit', [settingcontrol::class, 'editnosurat'])->name('nosurat.edit');
    Route::put('/nosurat/{id}', [settingcontrol::class, 'updatenosurat'])->name('nosurat.update');
    Route::DELETE('/nosurat/{id}', [settingcontrol::class, 'destroynosurat'])->name('nosurat.destroy');

    Route::post('/Superadmin/validasi/{id}/update-status', [sidebarcontrol3::class, 'updateStatusKasubag'])->name('updateStatusKasubag');
    Route::get('/Superadmin/Validasi', [sidebarcontrol3::class, 'showvalidasi'])->name('Validasi');
    Route::get('Validasi/SpjGu/Show/Gu', [validasicontrol::class, 'showvalidasiGU'])->name('validasigu');
    Route::get('Validasi/SpjPo/Show/Po', [validasicontrol::class, 'showvalidasiPO'])->name('validasipo');
    Route::get('Validasi/SpjLs/Show/Ls', [validasicontrol::class, 'showvalidasiLS'])->name('validasils');
});



Route::middleware(['auth', 'session.timeout', 'role:Bendahara'])->group(function () {
    Route::get('/admin/Dashboard', [sidebarcontrol2::class, 'showdashboard2'])->name('admindashboard');
    Route::get('/admin/preview/{id}', [sidebarcontrol2::class, 'previewadmin'])->name('previewadmin');
    Route::get('/admin/verivikasi', [sidebarcontrol2::class, 'Verivymain'])->name('verivikasi');
    Route::get('/SpjGu/Show/Gu', [verivycontrol::class, 'showverivikasiGU'])->name('verivygu');
    Route::get('/SpjPo/Show/Po', [verivycontrol::class, 'showverivikasiPO'])->name('verivypo');
    Route::get('/SpjLs/Show/Ls', [verivycontrol::class, 'showverivikasiLS'])->name('verivyls');
    Route::post('/admin/verivikasi/{id}/update-status', [sidebarcontrol2::class, 'updateStatusbendahara'])->name('updateStatusbendahara');

    Route::post('/spj/{id}/ajukan-kasubag', [SPJController::class, 'ajukanKasubag'])->name('ajukanKasubag');
});


    Route::post('/kegiatan-kwitansi/store-ajax',[KwitansiLsControl::class, 'storeAjax'])->name('kegiatan-kwitansi.store-ajax');
    Route::post('/spj/upload-bukti/{spj}',[bukticontrol::class, 'store'])->name('spj.bukti.store');
    Route::get('/spj/{spj}/bukti', [BuktiControl::class, 'list'])->name('spj.bukti.list');
    Route::get('/spj/{spj}/bukti', [BuktiControl::class, 'index']);
    Route::post('/spj/bukti/{id}/update', [BuktiControl::class, 'update']);
    Route::delete('/spj/bukti/{id}', [BuktiControl::class, 'destroy']);




Route::middleware(['auth', 'session.timeout', 'role:users'])->group(function () {
    Route::get('/User/Dashboard', [sidebarcontrol::class, 'showdashboard1'])->name('userdashboard');
    Route::get('/Kwitansi', [sidebarcontrol::class, 'showkwitansi'])->name('kwitansi');
    Route::get('/Pesanan', [sidebarcontrol::class, 'showpesanan'])->name('pesanan');
    Route::get('/Serahterima', [sidebarcontrol::class, 'showserahterima'])->name('serahterima');
    Route::get('/Penerimaan', [sidebarcontrol::class, 'showpenerimaan'])->name('penerimaan');
    Route::get('/Pemeriksaan', [sidebarcontrol::class, 'showpemeriksaan'])->name('pemeriksaan');
    Route::get('/Serahbarang', [sidebarcontrol::class, 'showserahbarang'])->name('serahbarang');
    Route::get('/ReviewSPJ', [sidebarcontrol::class, 'showreviewSPJ'])->name('reviewSPJ');
    Route::get('/CetakSPJ', [sidebarcontrol::class, 'showcetakSPJ'])->name('cetakSPJ');


    // ========== KWITANSI GU ==========
    Route::get('/kwitansiGu/Show/gu', [KwitansiControl::class, 'showKwitansiGU'])->name('Kwitansigu');
    Route::get('/kwitansiGu/create/{spj_id}', [KwitansiGuControl::class, 'createkwitansigu'])->name('kwitansigu.create');
    Route::post('/kwitansiGu/store', [KwitansiGuControl::class, 'storekwitansigu'])->name('kwitansiGu.store');
    Route::get('/kwitansiGu/{id}/edit', [KwitansiGuControl::class, 'editkwitansigu'])->name('kwitansiGu.edit');
    Route::put('/kwitansiGu/{id}', [KwitansiGuControl::class, 'updatekwitansigu'])->name('kwitansiGu.update');
    

    // ========== KWITANSI LS ==========
    Route::get('/kwitansiLs/show/ls', [KwitansiControl::class, 'showKwitansiLS'])->name('kwitansils');
    Route::get('/kwitansiLs/create/{spj_id}', [KwitansiLsControl::class, 'create'])->name('kwitansils.create');
    Route::post('/kwitansiLs/store', [KwitansiLsControl::class, 'store'])->name('kwitansisls.store');
    Route::get('/kwitansiLs/{id}/edit', [KwitansiLsControl::class, 'edit'])->name('kwitansils.edit');
    Route::put('/kwitansiLs/{id}', [KwitansiLsControl::class, 'updatels'])->name('kwitansils.update');


    Route::get('/kwitansi/Show/Po', [KwitansiControl::class, 'showKwitansiPO'])->name('kwitansipo');
    Route::get('/kwitansiPo/create/{spj_id}', [KwitansiPoControl::class, 'createkwitansipo'])->name('kwitansipo.create');
    Route::post('/kwitansiPo/store', [KwitansiPoControl::class, 'storekwitansipo'])->name('kwitansipo.store');
    Route::get('/kwitansiPo/{id}/edit', [KwitansiPoControl::class, 'editkwitansipo'])->name('kwitansipo.edit');
    Route::put('/kwitansiPo/{id}', [KwitansiPoControl::class, 'updatekwitansipo'])->name('kwitansipo.update');


    Route::get('/get-norek-sub/{id}', [kwitansicontrol::class, 'getNoRekSub']);
    Route::get('/get-subkegiatan/{pptk_id}', [kwitansicontrol::class, 'getSubKegiatan'])->name('get.subkegiatan');




    // ========== PESANAN GU ==========
    Route::get('/pesananGu/show/Gu', [pesanancontrol::class, 'showpesananGU'])->name('pesanangu');
    Route::get('/pesananGu/create/{spj_id}', [PesananGuControl::class, 'creategu'])->name('pesanangu.create');
    Route::post('/pesananGu/store', [PesananGuControl::class, 'storeGu'])->name('pesanangu.store');
    Route::get('/pesananGu/{id}/edit', [PesananGuControl::class, 'editGu'])->name('pesanangu.edit');
    Route::put('/pesananGu/{id}', [PesananGuControl::class, 'updateGu'])->name('pesanangu.update');
    

    // ========== PESANAN LS ==========
    Route::get('/pesananLS/Show/ls', [pesanancontrol::class, 'showpesananLS'])->name('pesananls');
    Route::get('/pesananLS/create/{spj_id}', [PesananLsControl::class, 'create'])->name('pesananls.create');
    Route::post('/pesananLS/store', [PesananLsControl::class, 'store'])->name('pesananls.store');
    Route::get('/pesananLS/{id}/edit', [PesananLsControl::class, 'edit'])->name('pesananls.edit');
    Route::put('/pesananLS/{id}', [PesananLsControl::class, 'update'])->name('pesananls.update');
    
    
    Route::delete('/pesanan/item/{item}', [PesananControl::class, 'destroyItem'])->name('pesanan.item.destroy');

    // ========== PEMERIKSAAN ==========
    Route::get('/pemeriksaan/create', [PemeriksaanControl::class, 'create'])->name('pemeriksaan.create');
    Route::post('/pemeriksaan/store', [PemeriksaanControl::class, 'store'])->name('pemeriksaan.store');
    Route::get('/pemeriksaan/{id}/edit', [PemeriksaanControl::class, 'edit'])->name('pemeriksaan.edit');
    Route::put('/pemeriksaan/{id}', [PemeriksaanControl::class, 'update'])->name('pemeriksaan.update');


    // ========== PEMERIKSAAN ==========
    Route::get('/serahbarang/create', [serahcontrol::class, 'create'])->name('serahbarang.create');
    Route::post('/serahbarang/store', [serahcontrol::class, 'store'])->name('serahbarang.store');
    Route::get('/serahbarang/{id}/edit', [serahcontrol::class, 'edit'])->name('serahbarang.edit');
    Route::put('/serahbarang/{id}', [serahcontrol::class, 'update'])->name('serahbarang.update');

    Route::get('/penerimaan/create/{spj_id}/{pemeriksaan_id}/{id_serahbarang}', [PenerimaanControl::class, 'create'])->name('penerimaan.create');
    Route::post('/penerimaan/store', [PenerimaanControl::class, 'store'])->name('penerimaan.store');
    Route::get('/penerimaan/{id}/edit', [PenerimaanControl::class, 'edit'])->name('penerimaan.edit');
    Route::put('/penerimaan/{id}', [PenerimaanControl::class, 'update'])->name('penerimaan.update');



    Route::get('/spj/preview/{id}', [SPJController::class, 'preview'])->name('spj.preview');
    Route::post('/spj/{id}/submit-bendahara', [SpjController::class, 'submitToBendahara'])->name('spj.submitToBendahara');
    
    Route::get('/spj/cetak/{id}', [SPJController::class, 'cetak'])->name('spj.cetak');
    Route::DELETE('/spj/{id}', [SPJController::class, 'destroy'])->name('spj.destroy');
    Route::post('/spj/store', [SPJController::class, 'store'])->name('spj.store');
});

    
Route::get('/spj', [SPJController::class, 'index'])->name('spj.index');
    
Route::get('/spj/{id}/record/{section}', [spjresponcontrol::class, 'getRecord']) ->name('spj.record');
Route::post('/spj/{spjId}/revisi', [spjresponcontrol::class, 'store'])->name('spj.revisi.store');
Route::post('/spj-notif-read/{id}/{role}', [SPJController::class, 'markNotifRead'])->name('spj.notif.read');



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');




Route::middleware('auth')->group(function () {
    Route::get('/chat/contacts', [chatcontrol::class, 'getContacts']);
    Route::get('/chat/messages/{user}', [chatcontrol::class, 'getMessages']);
    Route::post('/chat/messages/send', [chatcontrol::class, 'sendMessage']);
    Route::post('/chat/messages/read/{user}', [chatcontrol::class, 'markAsRead']);
});


Route::get('/test', function () {
    return view('users.testchat');
});

// Route untuk trigger event
Route::get('/send-event', function () {
    broadcast(new \App\Events\TestEvent('Halo dari Laravel!'));
    return 'Event dikirim!';
});