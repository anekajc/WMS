<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangJadiController;
use App\Http\Controllers\BarcodeBarangController;
use App\Http\Controllers\DashboardOpnameHarianController;
use App\Http\Controllers\GantiPasswordController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\HitungStokController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KartuStockGudangController;
use App\Http\Controllers\LockPeriodeController;
use App\Http\Controllers\NewMenuController;
use App\Http\Controllers\NewOpnameGudangController;
use App\Http\Controllers\NewPOController;
use App\Http\Controllers\NewPenyerahanGudangController;
use App\Http\Controllers\NewPenyerahanKonsiController;
use App\Http\Controllers\NewPenyerahanSampleController;
use App\Http\Controllers\NewPermintaanPersiapanBooController;
use App\Http\Controllers\NewPermintaanPersiapanController;
use App\Http\Controllers\NewPermintaanPersiapanNonSOController;
use App\Http\Controllers\NewPickingController;
use App\Http\Controllers\NewPutAwayController;
use App\Http\Controllers\NewQCLPBController;
use App\Http\Controllers\NewReturBeliController;
use App\Http\Controllers\NewReturJualController;
use App\Http\Controllers\NewReturSuratJalanController;
use App\Http\Controllers\NewSetupPeriodeKerjaController;
use App\Http\Controllers\NewTransferBarangController;
use App\Http\Controllers\NewTransferGudangController;
use App\Http\Controllers\NewUsersController;
use App\Http\Controllers\OpnameGdg1Controller;
use App\Http\Controllers\OpnameGdgInspeksiController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\PermintaanPenyerahanGudangController;
use App\Http\Controllers\PermintaanUbahKemasanController;
use App\Http\Controllers\PersiapanSpbController;
use App\Http\Controllers\QCPickingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportKartuStokController;
use App\Http\Controllers\ReportOpnameHarianController;
use App\Http\Controllers\ReportOutDispatchController;
use App\Http\Controllers\ReportOutReceivingController;
use App\Http\Controllers\ReportPickSpbController;
use App\Http\Controllers\ReportPickingController;
use App\Http\Controllers\ReportPlPController;
use App\Http\Controllers\ReportPutawayController;
use App\Http\Controllers\ReportRelokasiController;
use App\Http\Controllers\ReportSoSpbController;
use App\Http\Controllers\ReportStokGudangController;
use App\Http\Controllers\ReportStokLokasiController;
use App\Http\Controllers\ReturSerahSampleController;
use App\Http\Controllers\SetPemakaiController;
use App\Http\Controllers\TerimaUbahKemasanController;
use App\Http\Controllers\TesController;
use App\Http\Controllers\TransferLokasiController;
use App\Http\Controllers\TransferLokasiInController;
use App\Http\Controllers\TransferLokasiOutController;
use App\Http\Controllers\TransferLokasiSiteController;
use App\Http\Controllers\UbahKemasanController;
use App\Http\Controllers\UbahKemasanFinalisasiController;
use App\Http\Controllers\UbahKemasanPickingController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();



Route::get('/cobacoba', [ReportController::class, 'coba']);
//Login
Route::get('/', [AuthController::class, 'index']);
Route::post('/checkLogin', [AuthController::class, 'authenticate']);
Route::post('/checkOnline', [UsersController::class, 'checkOnline']);

Route::get('/updateIdle', [AuthController::class, 'updateIdle']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Tes

Route::get('/newlogin' , function(){
  return view('newmaster');
});

Route::get('/newtes' , function(){
  return view('newtes');
});

Route::get('/home', [HomeController::class, 'newIndex'])->name('home'); // Tes New Home

Route::get('/tesnewpassword', [UsersController::class, 'generateNewPassword']);

Route::get('/testesprint' , function(){
  return view('cetak-spb');
});
Route::get('/testespicking' , [NewPickingController::class, 'testestes']);

// Route::get('/newmenu', 'NewMenuController@index');
Route::get('/tablemenu', [TesController::class, 'menu']);
Route::post('/addnewmenu', [NewMenuController::class, 'addMenu']);
Route::post('/editnewmenu' , [NewMenuController::class, 'editMenu']);
Route::post('/deletenewmenu' , [NewMenuController::class, 'deleteMenu']);
// Route::get('/newmenu1', 'TesController@index1');
Route::get('/newpo' , [NewPOController::class, 'index'])->middleware('auth');
Route::post('/addDBBeli', [NewPOController::class, 'addDBBeli'])->middleware('auth');

Route::post('/detailPO', [NewPOController::class, 'getDetailPO'])->middleware('auth');
Route::post('/detailPembelian', [NewPOController::class, 'getDetailPembelian'])->middleware('auth');
Route::get('/getNoBukti' , [NewPOController::class, 'getNoBukti'])->middleware('auth');
Route::get('/getAksesNewPO' , [NewPOController::class, 'getAkses'])->middleware('auth');
Route::get('/getAllPO' , [NewPOController::class, 'getAllPO'])->middleware('auth');
Route::get('/getAllPembelian' , [NewPOController::class, 'getAllPembelian'])->middleware('auth');
Route::post('/sp_beligudang', [NewPOController::class, 'spBeliGudang'])->middleware('auth');



Route::get('/newsetupperiodekerja', [NewSetupPeriodeKerjaController::class, 'index'])->middleware('auth');
Route::post('/newsetupperiodekerjaupdate' ,[NewSetupPeriodeKerjaController::class, 'updatePeriodeKerja'])->middleware('auth');


Route::post('/lockperiodecheck' , [LockPeriodeController::class, 'check'])->middleware('auth');




//USERS

Route::get('/users', [NewUsersController::class, 'index'])->middleware('auth');
Route::post('/usersadd', [NewUsersController::class, 'add'])->middleware('auth');
Route::get('/usersloadall', [NewUsersController::class, 'loadAll'])->middleware('auth');
Route::post('/usersdetail', [NewUsersController::class, 'detail'])->middleware('auth');
Route::post('/usersgantipassword', [NewUsersController::class, 'gantiPassword'])->middleware('auth');
Route::post('/usersdelete', [NewUsersController::class, 'delete'])->middleware('auth');
Route::post('/usersedit', [NewUsersController::class, 'edit'])->middleware('auth');

// SET PEMAKAI
// Route::get('/setpemakai', 'SetPemakaiController@index')->middleware('auth');
Route::post('/setpemakailistakses', [SetPemakaiController::class, 'listAkses'])->middleware('auth');
Route::post('/setpemakailistaksesreport', [SetPemakaiController::class, 'listAksesReport'])->middleware('auth');
Route::post('/setpemakaiupdateakses', [SetPemakaiController::class, 'spUpdateAkses'])->middleware('auth');
Route::post('/setpemakaiupdateaksesheader', [SetPemakaiController::class, 'spUpdateAksesHeader'])->middleware('auth');
Route::post('/setpemakaiupdateaksesreport', [SetPemakaiController::class, 'spUpdateAksesReport'])->middleware('auth');
Route::post('/setpemakaiupdateaksesreportheader', [SetPemakaiController::class, 'spUpdateAksesReportHeader'])->middleware('auth');


// PERMINTAAN PERSIAPAN
Route::get('/newpermintaanpersiapan' , [NewPermintaanPersiapanController::class, 'index'])->middleware('auth');
Route::get('/newpermintaanpersiapanloadall' , [NewPermintaanPersiapanController::class, 'loadAll'])->middleware('auth');
Route::get('/newpermintaanpersiapangetnobukti' , [NewPermintaanPersiapanController::class, 'getNoBukti'])->middleware('auth');
Route::post('/newpermintaanpersiapanspadd' , [NewPermintaanPersiapanController::class, 'spAdd'])->middleware('auth');
Route::get('/newpermintaanpersiapandetailpenerimaan' , [NewPermintaanPersiapanController::class, 'detailPenerimaan'])->middleware('auth');
Route::post('/newpermintaanpersiapanspdelete' , [NewPermintaanPersiapanController::class, 'spDelete'])->middleware('auth');
Route::get('/newpermintaanpersiapandetailpenerimaanperiod' , [NewPermintaanPersiapanController::class, 'detailPenerimaanperiod'])->middleware('auth');
Route::post('/newpermintaanpersiapanspcheckreguler' , [NewPermintaanPersiapanController::class, 'spCheckReguler'])->middleware('auth');



// PERMINTAAN PERSIAPAN booking
Route::get('/newpermintaanpersiapanboo' , [NewPermintaanPersiapanBooController::class, 'index'])->middleware('auth');
Route::get('/newpermintaanpersiapanbooloadall' , [NewPermintaanPersiapanBooController::class, 'loadAll'])->middleware('auth');
Route::get('/newpermintaanpersiapanboogetnobukti' , [NewPermintaanPersiapanBooController::class, 'getNoBukti'])->middleware('auth');
Route::post('/newpermintaanpersiapanboospadd' , [NewPermintaanPersiapanBooController::class, 'spAdd'])->middleware('auth');
Route::get('/newpermintaanpersiapanboodetailpenerimaan' , [NewPermintaanPersiapanBooController::class, 'detailPenerimaan'])->middleware('auth');
Route::post('/newpermintaanpersiapanboospdelete' , [NewPermintaanPersiapanBooController::class, 'spDelete'])->middleware('auth');
Route::get('/newpermintaanpersiapanboodetailpenerimaanperiod' , [NewPermintaanPersiapanBooController::class, 'detailPenerimaanperiod'])->middleware('auth');
Route::post('/newpermintaanpersiapanboospcheckreguler' , [NewPermintaanPersiapanBooController::class, 'spCheckReguler'])->middleware('auth');

// PERMINTAAN PERSIAPAN NON SO
Route::get('/newpermintaanpersiapannonso' ,[NewPermintaanPersiapanNonSOController::class, 'index'])->middleware('auth');
Route::get('/newpermintaanpersiapannonsoloadall' ,[NewPermintaanPersiapanNonSOController::class, 'loadAll'])->middleware('auth');
Route::get('/newpermintaanpersiapannonsogetnobukti' , [NewPermintaanPersiapanNonSOController::class, 'getNoBukti'])->middleware('auth');
Route::get('/newpermintaanpersiapannonsodetailpenerimaan' , [NewPermintaanPersiapanNonSOController::class, 'detailPenerimaan'])->middleware('auth');
Route::post('/newpermintaanpersiapannonsospadd' , [NewPermintaanPersiapanNonSOController::class, 'spAdd'])->middleware('auth');


// QC LPB
Route::get('/qclpb', [NewQCLPBController::class, 'index'])->middleware('auth');
Route::post('/detailLPB', [NewQCLPBController::class, 'detailLPB'])->middleware('auth');
Route::post('/detailQC', [NewQCLPBController::class, 'detailQC'])->middleware('auth');
Route::post('/submitLPB', [NewQCLPBController::class, 'submitLPB'])->middleware('auth');
Route::get('/getAksesQCLPB' , [NewQCLPBController::class, 'getAkses'])->middleware('auth');
Route::get('/getAllLPB', [NewQCLPBController::class, 'getAllLPB'])->middleware('auth');
Route::get('/getAllQC', [NewQCLPBController::class, 'getAllQC'])->middleware('auth');
// RETUR JUAL
Route::get('/newreturjual' , [NewReturJualController::class, 'index'])->middleware('auth');
Route::get('/loadallreturjual' , [NewReturJualController::class, 'loadall'])->middleware('auth');
Route::post('/detailoutstandingreturjual' , [NewReturJualController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/detailreturgudang' , [NewReturJualController::class, 'getDetailReturGudang'])->middleware('auth');
Route::get('/getNoBuktiReturJual', [NewReturJualController::class, 'getNoBukti'])->middleware('auth');
Route::post('/addReturJual' , [NewReturJualController::class, 'addReturJual'])->middleware('auth');
Route::post('/koreksiReturJual' , [NewReturJualController::class, 'spKoreksi'])->middleware('auth');
Route::post('/returjualkoreksiaddlist' , [NewReturJualController::class, 'getKoreksiAddList'])->middleware('auth');
// TRANSFER GUDANG
Route::get('/transfergudang' , [NewTransferGudangController::class, 'index'])->middleware('auth');
Route::get('/getnobuktitransfergudang', [NewTransferGudangController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingtransfergudang' , [NewTransferGudangController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/detailpenerimaantransfergudang' , [NewTransferGudangController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/addTransferGudang' , [NewTransferGudangController::class, 'addTransferGudang'])->middleware('auth');
Route::post('/transfergudangkoreksiaddlist', [NewTransferGudangController::class, 'getKoreksiAddList'])->middleware('auth');
Route::get('/loadalltransfergudang' , [NewTransferGudangController::class, 'loadAll'])->middleware('auth');
Route::post('/koreksiTransferGudang', [NewTransferGudangController::class, 'spKoreksi'])->middleware('auth');
// RETUR SURAT JALAN
Route::get('/newretursuratjalan' , [NewReturSuratJalanController::class, 'index'])->middleware('auth');
Route::get('/getnobuktiretursuratjalan' , [NewReturSuratJalanController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingretursuratjalan' , [NewReturSuratJalanController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/addSuratJalan' , [NewReturSuratJalanController::class, 'addSuratJalan'])->middleware('auth');
Route::post('/detailpenerimaanretursuratjalan' , [NewReturSuratJalanController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/suratjalankoreksiaddlist', [NewReturSuratJalanController::class, 'getKoreksiAddList'])->middleware('auth');
Route::post('/koreksiSuratJalan' , [NewReturSuratJalanController::class, 'spKoreksi'])->middleware('auth');
Route::get('/loadallsuratjalan' , [NewReturSuratJalanController::class, 'loadAll'])->middleware('auth');
// TRANSFER BARANG
Route::get('/newtransferbarang' , [NewTransferBarangController::class, 'index'])->middleware('auth');
Route::get('/getnobuktitransferbarang' , [NewTransferBarangController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingtransferbarang', [NewTransferBarangController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/newAddTransferBarang', [NewTransferBarangController::class, 'addTransferBarang'])->middleware('auth');
Route::post('/detailpenerimaantransferbarang', [NewTransferBarangController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/transferbarangkoreksiaddlist' , [NewTransferBarangController::class, 'getKoreksiAddList'])->middleware('auth');
Route::post('/koreksiTransferBarang' , [NewTransferBarangController::class, 'spKoreksi'])->middleware('auth');
Route::get('/loadalltransferbarang' , [NewTransferBarangController::class, 'loadAll'])->middleware('auth');

// PENYERAHAN GUDANG
Route::get('/penyerahangudang' , [NewPenyerahanGudangController::class, 'index'])->middleware('auth');
Route::get('/getnobuktipenyerahangudang', [NewPenyerahanGudangController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingpenyerahangudang', [NewPenyerahanGudangController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/addpenyerahangudang' ,[NewPenyerahanGudangController::class, 'addPenyerahanGudang'])->middleware('auth');
Route::post('/detailpenerimaanpenyerahangudang' , [NewPenyerahanGudangController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::get('/loadallpenyerahangudang', [NewPenyerahanGudangController::class, 'loadAll'])->middleware('auth');
Route::post('/penyerahangudangkoreksiaddlist' , [NewPenyerahanGudangController::class, 'getKoreksiAddList'])->middleware('auth');
Route::post('/koreksipenyerahangudang', [NewPenyerahanGudangController::class, 'spKoreksi'])->middleware('auth');

//PENYERAHAN SAMPLE
Route::get('/penyerahansample', [NewPenyerahanSampleController::class, 'index'])->middleware('auth');
Route::get('/getnobuktipenyerahansample' , [NewPenyerahanSampleController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingpenyerahansample' , [NewPenyerahanSampleController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/addpenyerahansample' , [NewPenyerahanSampleController::class, 'spAdd'])->middleware('auth');
Route::get('/loadallpenyerahansample', [NewPenyerahanSampleController::class, 'loadAll'])->middleware('auth');
Route::post('/detailpenerimaanpenyerahansample', [NewPenyerahanSampleController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/penyerahansamplekoreksiaddlist', [NewPenyerahanSampleController::class, 'getKoreksiAddList'])->middleware('auth');
Route::post('/koreksipenyerahansample', [NewPenyerahanSampleController::class, 'spKoreksi'])->middleware('auth');
Route::post('/PrintSample' , [NewPenyerahanSampleController::class, 'getDetailPenerimaan'])->middleware('auth');

//PENYERAHAN KONSI
Route::get('/penyerahankonsi', [NewPenyerahanKonsiController::class, 'index'])->middleware('auth');
Route::get('/getnobuktipenyerahankonsi' , [NewPenyerahanKonsiController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingpenyerahankonsi' , [NewPenyerahanKonsiController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/addpenyerahankonsi' , [NewPenyerahanKonsiController::class, 'spAdd'])->middleware('auth');
Route::get('/loadallpenyerahankonsi', [NewPenyerahanKonsiController::class, 'loadAll'])->middleware('auth');
Route::post('/detailpenerimaanpenyerahankonsi', [NewPenyerahanKonsiController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/penyerahankonsikoreksiaddlist', [NewPenyerahanKonsiController::class, 'getKoreksiAddList'])->middleware('auth');
Route::post('/koreksipenyerahankonsi', [NewPenyerahanKonsiController::class, 'spKoreksi'])->middleware('auth');
Route::post('/PrintKonsi' , [NewPenyerahanKonsiController::class, 'getDetailPenerimaan'])->middleware('auth');


// UBAH KEMASAN
// Route::get('/ubahkemasan', 'UbahKemasanController@index')->middleware('auth');
Route::get('/getnobuktiubahkemasan' , [UbahKemasanController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingubahkemasan' , [UbahKemasanController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/detailpenerimaanubahkemasan', [UbahKemasanController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/addubahkemasan' , [UbahKemasanController::class, 'spAdd'])->middleware('auth');
Route::get('/loadallubahkemasan', [UbahKemasanController::class, 'loadAll'])->middleware('auth');
Route::post('/koreksiubahkemasan', [UbahKemasanController::class, 'spKoreksi'])->middleware('auth');



// PERMINTAAN UBAH KEMASAN

Route::get('/permintaanubahkemasan' , [PermintaanUbahKemasanController::class, 'index'])->middleware('auth');
Route::get('/getnobuktipermintaanubahkemasan' , [PermintaanUbahKemasanController::class, 'getNoBukti'])->middleware('auth');
Route::post('/permintaanubahkemasandetailpenerimaan' , [PermintaanUbahKemasanController::class, 'detailPenerimaan'])->middleware('auth');
Route::post('/permintaanubahkemasandetailpenerimaanprint' , [PermintaanUbahKemasanController::class, 'detailPenerimaanPrint'])->middleware('auth');
Route::get('/loadallpermintaanubahkemasan' , [PermintaanUbahKemasanController::class, 'loadAll'])->middleware('auth');
Route::post('/permintaanubahkemasanspadd' , [PermintaanUbahKemasanController::class, 'spAdd'])->middleware('auth');

// FINALISASI UBAH KEMASAN
Route::get('/ubahkemasanfinalisasi', [UbahKemasanFinalisasiController::class, 'index'])->middleware('auth');
Route::get('/getnobuktiubahkemasanfinalisasi' , [UbahKemasanFinalisasiController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingubahkemasanfinalisasi' , [UbahKemasanFinalisasiController::class, 'getDetailOutstanding'])->middleware('auth');
Route::get('/loadallubahkemasanfinalisasi', [UbahKemasanFinalisasiController::class, 'loadAll'])->middleware('auth');
Route::post('/addubahkemasanfinalisasi' , [UbahKemasanFinalisasiController::class, 'spAdd'])->middleware('auth');

// UBAH KEMASAN PICKING
Route::get('/pickinguk', [UbahKemasanPickingController::class, 'index'])->middleware('auth');
Route::post('/detailoutstandingubahkemasanpicking' , [UbahKemasanPickingController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/detailpenerimaanubahkemasanpicking' , [UbahKemasanPickingController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/detailoutstandingubahkemasanpickinginfo' , [UbahKemasanPickingController::class, 'getDetailOutstandingInfo'])->middleware('auth');
Route::get('/loadallubahkemasanpicking', [UbahKemasanPickingController::class, 'loadAll'])->middleware('auth');
Route::post('/ubahkemasanpickingspcekadd', [UbahKemasanPickingController::class, 'spCheckAdd'])->middleware('auth');
Route::post('/ubahkemasanpickingspadd', [UbahKemasanPickingController::class, 'spAdd'])->middleware('auth');

//TERIMA UBAH KEMASAN
Route::get('/terimaubahkemasan', [TerimaUbahKemasanController::class, 'index'])->middleware('auth');
Route::post('/detailoutstandingterimaubahkemasan' , [TerimaUbahKemasanController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/detailpenerimaanterimaubahkemasan', [TerimaUbahKemasanController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::get('/loadallterimaubahkemasan', [TerimaUbahKemasanController::class, 'loadAll'])->middleware('auth');
Route::post('/addterimaubahkemasan' , [TerimaUbahKemasanController::class, 'spAdd'])->middleware('auth');
Route::post('/koreksiterimaubahkemasan' , [TerimaUbahKemasanController::class, 'spKoreksi'])->middleware('auth');
// Ditambah 2026-10-20: nomor bukti Terima Ubah Kemasan = KMBJ milik PR (dulu memakai getnobuktiubahkemasan)
Route::get('/getnobuktiterimaubahkemasan' , [TerimaUbahKemasanController::class, 'getNoBuktiTerima'])->middleware('auth');

// TRANSFER LOKASI SITE

Route::get('/transferlokasisite' , [TransferLokasiSiteController::class, 'index'])->middleware('auth');
Route::post('/transferlokasisiteceklokasiawal' , [TransferLokasiSiteController::class, 'cekLokasiAwal'])->middleware('auth');
Route::post('/transferlokasisiteceklokasitujuan' , [TransferLokasiSiteController::class, 'cekLokasiTujuan'])->middleware('auth');
Route::post('/transferlokasisitecekkodegdg' , [TransferLokasiSiteController::class, 'cekKodeGdg'])->middleware('auth');
Route::get('/getnobuktitransferlokasisite' , [TransferLokasiSiteController::class, 'getNoBukti'])->middleware('auth');
Route::post('/transferlokasisitespadd' , [TransferLokasiSiteController::class, 'spAdd'])->middleware('auth');





//TRANSFER LOKASI IN
Route::get('/transferlokasiin', [TransferLokasiInController::class, 'index'])->middleware('auth');
Route::get('/loadalltransferlokasiin', [TransferLokasiInController::class, 'loadAll'])->middleware('auth');
Route::post('/detailoutstandingtransferlokasiin' , [TransferLokasiInController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/detailpenerimaantransferlokasiin' , [TransferLokasiInController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/addtransferlokasiin' , [TransferLokasiInController::class, 'spAdd'])->middleware('auth');
Route::get('/getnobuktitransferlokasiin' , [TransferLokasiInController::class, 'getNoBukti'])->middleware('auth');
Route::post('/koreksitransferlokasiin' , [TransferLokasiInController::class, 'spKoreksi'])->middleware('auth');

//TRANSFER LOKASI
Route::get('/transferlokasi', [TransferLokasiController::class, 'index'])->middleware('auth');
Route::get('/transferlokasilistbarang' , [TransferLokasiController::class, 'listBarang'])->middleware('auth');
Route::get('/transferlokasilistlokasi' , [TransferLokasiController::class, 'listLokasi'])->middleware('auth');
Route::get('/getnobuktitransferlokasi' , [TransferLokasiController::class, 'getNoBukti'])->middleware('auth');
Route::post('/transferlokasispadd' , [TransferLokasiController::class, 'spAdd'])->middleware('auth');
Route::post('/transferlokasidetailpenerimaan' , [TransferLokasiController::class, 'detailPenerimaan'])->middleware('auth');
Route::get('/loadalltransferlokasi' , [TransferLokasiController::class, 'loadAll'])->middleware('auth');

//TRANSFER LOKASI OUT
Route::get('/transferlokasiout', [TransferLokasiOutController::class, 'index'])->middleware('auth');
Route::get('/loadalltransferlokasiout', [TransferLokasiOutController::class, 'loadAll'])->middleware('auth');
Route::get('/getnobuktitransferlokasiout' , [TransferLokasiOutController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingtransferlokasiout' , [TransferLokasiOutController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/addtransferlokasiout' , [TransferLokasiOutController::class, 'spAdd'])->middleware('auth');
Route::post('/detailpenerimaantransferlokasiout' , [TransferLokasiOutController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/koreksitransferlokasiout' , [TransferLokasiOutController::class, 'spKoreksi'])->middleware('auth');


// GANTI PASSWORD
Route::get('/gantipassword' , [GantiPasswordController::class, 'index'])->middleware('auth');
Route::post('/gantipassworduser' , [GantiPasswordController::class, 'gantiPassword'])->middleware('auth');

// PERMINTAAN PENYERAHAN GUDANG

Route::get('/permintaanpenyerahangudang' , [PermintaanPenyerahanGudangController::class, 'index'])->middleware('auth');
Route::get('/getnobuktipermintaanpenyerahangudang' , [PermintaanPenyerahanGudangController::class, 'getNoBukti'])->middleware('auth');
Route::get('/permintaanpenyerahangudanglistbarang' , [PermintaanPenyerahanGudangController::class, 'listBarang'])->middleware('auth');
Route::get('/permintaanpenyerahangudanglistgudang' , [PermintaanPenyerahanGudangController::class, 'listGudang'])->middleware('auth');
Route::post('/permintaanpenyerahangudangdetailpenerimaan' , [PermintaanPenyerahanGudangController::class, 'detailPenerimaan'])->middleware('auth');
Route::get('/loadallpermintaanpenyerahangudang' , [PermintaanPenyerahanGudangController::class, 'loadAll'])->middleware('auth');
Route::post('/permintaanpenyerahangudangspadd' , [PermintaanPenyerahanGudangController::class, 'spAdd'])->middleware('auth');


// RETUR Beli
Route::get('/returbeli', [NewReturBeliController::class, 'index'])->middleware('auth');
Route::post('/detailoutstandingreturbeli', [NewReturBeliController::class, 'getDetailOutstanding'])->middleware('auth');
Route::get('/getnobuktireturbeli', [NewReturBeliController::class, 'getNoBukti'])->middleware('auth');
Route::post('/addreturbeli', [NewReturBeliController::class, 'spAdd'])->middleware('auth');
Route::post('/detailpenerimaanreturbeli' , [NewReturBeliController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/returbelikoreksiaddlist', [NewReturBeliController::class, 'getKoreksiAddList'])->middleware('auth');
Route::get('/loadallreturbeli', [NewReturBeliController::class, 'loadall'])->middleware('auth');
Route::post('/koreksireturbeli', [NewReturBeliController::class, 'spKoreksi'])->middleware('auth');
// KOREKSI STOCK GDG
Route::get('/opnamegudang', [NewOpnameGudangController::class, 'index'])->middleware('auth');
Route::post('/opnamegudangspcheck', [NewOpnameGudangController::class, 'spcheck'])->middleware('auth');
Route::post('/detailitemopnamegudang', [NewOpnameGudangController::class, 'getDetailItem'])->middleware('auth');
Route::get('/getnobuktiopnamegudang', [NewOpnameGudangController::class, 'getNoBukti'])->middleware('auth');
Route::post('/addformopnamegudang', [NewOpnameGudangController::class, 'addForm'])->middleware('auth');
Route::get('/loadallopnamegudang', [NewOpnameGudangController::class, 'loadAll'])->middleware('auth');
Route::post('/getdetailpenerimaanopnamegudang', [NewOpnameGudangController::class, 'getDetailPenerimaan'])->middleware('auth');
//PUT AWAY
Route::get('/putaway', [NewPutAwayController::class, 'index'])->middleware('auth');
Route::post('/addputaway', [NewPutAwayController::class, 'spAdd'])->middleware('auth');
Route::post('/checkaddputaway', [NewPutAwayController::class, 'spCheckAdd'])->middleware('auth');
Route::post('/detailpenerimaanputaway' , [NewPutAwayController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/koreksiputaway',[NewPutAwayController::class, 'spKoreksi'])->middleware('auth');
Route::get('/loadallputaway', [NewPutAwayController::class, 'loadAll'])->middleware('auth');

// NEW PERMINTAAN VwPersiapan
Route::get('/newpicking', [NewPickingController::class, 'index'])->middleware('auth');
Route::post('/detailpenerimaanpicking' , [NewPickingController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/addpicking', [NewPickingController::class, 'spAdd'])->middleware('auth');
Route::post('/addpickinglpb', [NewPickingController::class, 'spAddLPB'])->middleware('auth');
Route::post('/checkaddpicking', [NewPickingController::class, 'spCheckAdd'])->middleware('auth');
Route::post('/checkaddpickinglpb', [NewPickingController::class, 'spCheckAddLPB'])->middleware('auth');
Route::post('/koreksipicking',[NewPickingController::class, 'spKoreksi'])->middleware('auth');
Route::get('/loadallpicking', [NewPickingController::class, 'loadAll'])->middleware('auth');





// packing
Route::get('/packing', [PackingController::class, 'index'])->middleware('auth');
Route::get('/loadallpacking' , [PackingController::class, 'loadAll'])->middleware('auth');
Route::post('/detailoutpacking' , [PackingController::class, 'getDetailOutstanding'])->middleware('auth');
Route::get('/getnobuktipacking' , [PackingController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingpacking' , [PackingController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/packingcheckadd' , [PackingController::class, 'checkAdd'])->middleware('auth');

// Route::post('/detailoutstandingpackingkolli' , 'PackingController@getDetailOutstandingkolli')->middleware('auth');
Route::post('/addpacking' , [PackingController::class, 'addPacking'])->middleware('auth');
Route::post('/detailpacking' , [PackingController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/koreksiPacking' , [PackingController::class, 'spKoreksi'])->middleware('auth');


// PERSIAPAN SPB
Route::get('/persiapanspb', [PersiapanSpbController::class, 'index'])->middleware('auth');
Route::get('/loadallpersiapanspb' , [PersiapanSpbController::class, 'loadAll'])->middleware('auth');
Route::post('/detailoutpersiapanspb' , [PersiapanSpbController::class, 'getDetailOutstanding'])->middleware('auth');
Route::get('/getnobuktipersiapanspb' , [PersiapanSpbController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingpersiapanspb' , [PersiapanSpbController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/addpersiapanspb' , [PersiapanSpbController::class, 'addSuratJalan'])->middleware('auth');
Route::post('/detailpersiapanspb' , [PersiapanSpbController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/detailtransaksispb' , [PersiapanSpbController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/spbkoreksiaddlist', [PersiapanSpbController::class, 'getKoreksiAddList'])->middleware('auth');
Route::post('/koreksiPersiapanSPB' , [PersiapanSpbController::class, 'spKoreksi'])->middleware('auth');
Route::post('/persiapanspbspotorisasi' , [PersiapanSpbController::class, 'spOtorisasi1'])->middleware('auth');
Route::post('/PrintSPB' , [PersiapanSpbController::class, 'getDetailPenerimaancetak'])->middleware('auth');
Route::post('/PrintKolli' , [PersiapanSpbController::class, 'getDetailOutstandingkolli'])->middleware('auth');
Route::post('/persiapanspbupdatecetakkolli' , [PersiapanSpbController::class, 'updateCetakKolli'])->middleware('auth');
Route::post('/persiapanspbupdatecetakspb' , [PersiapanSpbController::class, 'updateCetakspb'])->middleware('auth');




// RETUR SERAH SAMPLE
Route::get('/returserahsample' , [ReturSerahSampleController::class, 'index'])->middleware('auth');
Route::get('/getnobuktireturserahsample' , [ReturSerahSampleController::class, 'getNoBukti'])->middleware('auth');
Route::post('/detailoutstandingreturserahsample' , [ReturSerahSampleController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/addreturserahsample' , [ReturSerahSampleController::class, 'addSuratJalan'])->middleware('auth');
Route::post('/detailpenerimaanreturserahsample' , [ReturSerahSampleController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::post('/returserahsamplekoreksiaddlist', [ReturSerahSampleController::class, 'getKoreksiAddList'])->middleware('auth');
Route::post('/koreksireturserahsample' , [ReturSerahSampleController::class, 'spKoreksi'])->middleware('auth');
Route::get('/loadallreturserahsample' , [ReturSerahSampleController::class, 'loadAll'])->middleware('auth');


// REPORT KARTU STOK
Route::get('/reportkartustok', [ReportKartuStokController::class, 'index'])->middleware('auth');
Route::get('/reportkartustoklistlokasi', [ReportKartuStokController::class, 'listLokasi'])->middleware('auth');
Route::get('/reportkartustoklistbarang', [ReportKartuStokController::class, 'listBarang'])->middleware('auth');
Route::get('/reportkartustokspreport', [ReportKartuStokController::class, 'spReport'])->middleware('auth');

// REPORT STOK LOKASI
Route::get('/reportstoklokasi' , [ReportStokLokasiController::class, 'index'])->middleware('auth');
Route::post('/reportstoklokasispfilter' , [ReportStokLokasiController::class, 'spFilter'])->middleware('auth');

// REPORT STOK Gudang
Route::get('/reportstokgudang' , [ReportStokGudangController::class, 'index'])->middleware('auth');
Route::post('/reportstokgudangspfilter' , [ReportStokGudangController::class, 'spFilter'])->middleware('auth');

// REPORT  PUTAWAY
Route::get('/reportputaway' , [ReportPutawayController::class, 'index'])->middleware('auth');
Route::post('/reportputawayspfilter' , [ReportPutawayController::class, 'spFilter'])->middleware('auth');

// REPORT  PICKING
Route::get('/reportpicking' , [ReportPickingController::class, 'index'])->middleware('auth');
Route::post('/reportpickingspfilter' , [ReportPickingController::class, 'spFilter'])->middleware('auth');



// QC PICKING
Route::get('/qcpicking', [QCPickingController::class, 'index'])->middleware('auth');
Route::post('/qcpickingdetailoutstanding', [QCPickingController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/qcpickingdetailpenerimaan', [QCPickingController::class, 'getDetailPenerimaan'])->middleware('auth');
Route::get('/qcpickingloadall', [QCPickingController::class, 'loadAll'])->middleware('auth');
Route::post('/qcpickingspadd', [QCPickingController::class, 'spAdd'])->middleware('auth');


// OPNAME GUDANG
Route::get('/opnamegdg', [OpnameGdg1Controller::class, 'index'])->middleware('auth');
Route::post('/opnamegdgcekbarang' , [OpnameGdg1Controller::class, 'cekBarang'])->middleware('auth');
Route::post('/opnamegdgceklokasi' , [OpnameGdg1Controller::class, 'cekLokasi'])->middleware('auth');
Route::get('/getnobuktiopnamegdg' , [OpnameGdg1Controller::class, 'getNoBukti'])->middleware('auth');
Route::post('/opnamegdgspadd', [OpnameGdg1Controller::class, 'spAdd'])->middleware('auth');
Route::get('/opnamegdgloadall', [OpnameGdg1Controller::class, 'loadAll'])->middleware('auth');
Route::post('/opnamegdgdetailoutstanding' , [OpnameGdg1Controller::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/opnamegdgspkoreksi', [OpnameGdg1Controller::class, 'spKoreksi'])->middleware('auth');
Route::post('/opnamegdgspdelete', [OpnameGdg1Controller::class, 'spDelete'])->middleware('auth');
Route::post('/opnamegdgspverifikasi', [OpnameGdg1Controller::class, 'spVerifikasi'])->middleware('auth');

// OPNAME GUDANG INSPEKSI
Route::get('/opnamegdginspeksi', [OpnameGdgInspeksiController::class, 'index'])->middleware('auth');
Route::get('/opnamegdginspeksiloadall', [OpnameGdgInspeksiController::class, 'loadAll'])->middleware('auth');
Route::post('/opnamegdginspeksidetailoutstanding' , [OpnameGdgInspeksiController::class, 'getDetailOutstanding'])->middleware('auth');
Route::post('/opnamegdginspeksispkoreksi', [OpnameGdgInspeksiController::class, 'spKoreksi'])->middleware('auth');
Route::post('/opnamegdginspeksispdelete', [OpnameGdgInspeksiController::class, 'spDelete'])->middleware('auth');
Route::post('/opnamegdginspeksispverifikasiaudit', [OpnameGdgInspeksiController::class, 'spVerifikasiAudit'])->middleware('auth');




// REPORT  OUT DISPATCH
Route::get('/reportoutdispatch' , [ReportOutDispatchController::class, 'index'])->middleware('auth');
Route::post('/reportoutdispatchspfilter' , [ReportOutDispatchController::class, 'spFilter'])->middleware('auth');

// REPORT  SO-SPB
Route::get('/reportsospb' , [ReportSoSpbController::class, 'index'])->middleware('auth');
Route::post('/reportsospbspfilter' , [ReportSoSpbController::class, 'spFilter'])->middleware('auth');


// REPORT  OPNAME HARIAN
Route::get('/reportopnameharian' , [ReportOpnameHarianController::class, 'index'])->middleware('auth');
Route::post('/reportopnameharianspfilter' , [ReportOpnameHarianController::class, 'spFilter'])->middleware('auth');


// REPORT  OUT Receiving
Route::get('/reportoutreceiving' , [ReportOutReceivingController::class, 'index'])->middleware('auth');
Route::post('/reportoutreceivingspfilter' , [ReportOutReceivingController::class, 'spFilter'])->middleware('auth');


// REPORT  PickingList-SPB
Route::get('/reportpickspb' , [ReportPickSpbController::class, 'index'])->middleware('auth');
Route::post('/reportpickspbspfilter' , [ReportPickSpbController::class, 'spFilter'])->middleware('auth');


// REPORT  PICKINGLIST-PICKING
Route::get('/reportplp' , [ReportPlPController::class, 'index'])->middleware('auth');
Route::post('/reportplpspfilter' , [ReportPlPController::class, 'spFilter'])->middleware('auth');



// REPORT  RELOKASI
Route::get('/reportrelokasi' , [ReportRelokasiController::class, 'index'])->middleware('auth');
Route::post('/reportrelokasispfilter' , [ReportRelokasiController::class, 'spFilter'])->middleware('auth');


// REPORT KARTU STOK
Route::get('/kartustockgudang', [KartuStockGudangController::class, 'index'])->middleware('auth');
Route::get('/kartustockgudanglistlokasi', [KartuStockGudangController::class, 'listLokasi'])->middleware('auth');
Route::get('/kartustockgudanglistbarang', [KartuStockGudangController::class, 'listBarang'])->middleware('auth');
Route::get('/kartustockgudangspreport', [KartuStockGudangController::class, 'spReport'])->middleware('auth');


// barcode barang
Route::get('/barcodebarang', [BarcodeBarangController::class, 'index'])->middleware('auth');
Route::get('/loadAllbarcodebarang', [BarcodeBarangController::class, 'loadAll'])->middleware('auth');
Route::post('/barcodebarangdata', [BarcodeBarangController::class, 'dataTabel'])->middleware('auth');


// dashboardopnameharian
//Route::get('/dashboardopnameharian', 'DashboardOpnameHarianController@index')->middleware('auth');
//Route::get('/dashboardopnamehariansp', 'DashboardOpnameHarianController@spDashboard')->middleware('auth');

Route::get('/dashboardopnameharian', [DashboardOpnameHarianController::class, 'index'])->middleware('auth');
Route::get('/dashboardopnamehariansp', [DashboardOpnameHarianController::class, 'spDashboard'])->middleware('auth');
Route::post('/dashboardopnamehariansplistbarang', [DashboardOpnameHarianController::class, 'spListBarang'])->middleware('auth');


// Route::get('/persiapanspb', 'PersiapanSpbController@index');
// Route::get('/loadallpersiapanspb' , 'PersiapanSpbController@loadAll');
// Route::post('/detailoutpersiapanspb' , 'PersiapanSpbController@getDetailOutstanding');
// Route::get('/getnobuktipersiapanspb' , 'PersiapanSpbController@getNoBukti');
// Route::post('/detailoutstandingpersiapanspb' , 'PersiapanSpbController@getDetailOutstanding');
// Route::post('/addpersiapanspb' , 'PersiapanSpbController@addSuratJalan');
// Route::post('/detailpersiapanspb' , 'PersiapanSpbController@getDetailPenerimaan');
// Route::post('/detailtransaksispb' , 'PersiapanSpbController@getDetailPenerimaan');
// Route::post('/spbkoreksiaddlist', 'PersiapanSpbController@getKoreksiAddList');

// Dashboard
// Route::get('/dashboard', 'DashboardController@index')->middleware('auth');
// Route::get('/home', 'HomeController@index')->name('home')->middleware('auth'); //Home

// // User
// Route::get('/setpemakai', 'UsersController@index')->middleware('auth');
// Route::get('/loadAllUsers', 'UsersController@loadAll')->middleware('auth');
// Route::post('/addUser', 'UsersController@add')->middleware('auth');
// Route::post('/editUser', 'UsersController@edit')->middleware('auth');
// Route::post('/eraseUser', 'UsersController@erase')->middleware('auth');
// Route::post('/offlineUser', 'UsersController@offline')->middleware('auth');
// Route::post('/getAksesMenu', 'UsersController@getAkses')->middleware('auth');
// Route::post('/getAksesGudang', 'UsersController@getAksesGudang')->middleware('auth');
// Route::post('/getAksesPerkiraan', 'UsersController@getAksesPerkiraan')->middleware('auth');
// Route::post('/getAksesLaporanPerkiraan', 'UsersController@getAksesLaporanPerkiraan')->middleware('auth');
// Route::post('/editAksesMenu', 'UsersController@editAkses')->middleware('auth');
// Route::post('/editAksesGudang', 'UsersController@editAksesGudang')->middleware('auth');
// Route::post('/editAksesPerkiraan', 'UsersController@editAksesPerkiraan')->middleware('auth');
// Route::post('/editAksesLaporanPerkiraan', 'UsersController@editAksesLaporanPerkiraan')->middleware('auth');
// Route::post('/getAksesByMenu', 'UsersController@getAksesByMenu')->middleware('auth');
//
// // Set Transaksi dan Perusahaan
// Route::get('/settransaksiperusahaan', 'SetTransaksiPerusahaanController@index')->middleware('auth');
// Route::post('/editPerusahaan', 'SetTransaksiPerusahaanController@editPerusahaan')->middleware('auth');
// Route::post('/editNomor', 'SetTransaksiPerusahaanController@editNomor')->middleware('auth');
//
// // Periode
// Route::get('/setupperiodekerja', 'PeriodeController@index')->middleware('auth');
// Route::post('/editPeriode', 'PeriodeController@edit')->middleware('auth');
//
// // Valas
// Route::get('/valas', 'ValasController@index')->middleware('auth');
// Route::get('/loadAllValas', 'ValasController@loadAll')->middleware('auth');
// Route::get('/showKurs', 'ValasController@showKurs')->middleware('auth');
// Route::post('/showKursByValas', 'ValasController@showKursByValas')->middleware('auth');
// Route::post('/addValas', 'ValasController@add')->middleware('auth');
// Route::post('/editValas', 'ValasController@edit')->middleware('auth');
// Route::post('/eraseValas', 'ValasController@erase')->middleware('auth');
// Route::post('/editKurs', 'ValasController@editKurs')->middleware('auth');
//
// // Devisi
// Route::get('/devisi', 'DevisiController@index')->middleware('auth');
// Route::get('/loadAllDevisi', 'DevisiController@loadAll')->middleware('auth');
// Route::post('/addDevisi', 'DevisiController@add')->middleware('auth');
// Route::post('/editDevisi', 'DevisiController@edit')->middleware('auth');
// Route::post('/eraseDevisi', 'DevisiController@erase')->middleware('auth');
// Route::get('/getDevisiGudang', 'DevisiController@getGudang')->middleware('auth');
// Route::post('/getDetailDevisi', 'DevisiController@getDetail')->middleware('auth');
// Route::post('/addDetailDevisi', 'DevisiController@addDetail')->middleware('auth');
// Route::post('/deleteDetailDevisi', 'DevisiController@deleteDetail')->middleware('auth');
//
// // Perkiraan
// Route::get('/perkiraan', 'PerkiraanController@index')->middleware('auth');
// Route::get('/loadAllPerkiraan2', 'PerkiraanController@loadAllPerkiraan')->middleware('auth');
// Route::post('/loadAllPerkiraan', 'PerkiraanController@loadAll')->middleware('auth');
// Route::post('/addPerkiraan', 'PerkiraanController@add')->middleware('auth');
// Route::post('/showPerkiraan', 'PerkiraanController@show')->middleware('auth');
// Route::post('/showPerkiraanKode', 'PerkiraanController@showKode')->middleware('auth');
// Route::post('/editPerkiraan', 'PerkiraanController@edit')->middleware('auth');
// Route::post('/erasePerkiraan', 'PerkiraanController@erase')->middleware('auth');
// Route::post('/getSaldoAwalPerkiraan', 'PerkiraanController@getSaldoAwal')->middleware('auth');
// Route::post('/setSaldoAwalPerkiraan', 'PerkiraanController@setSaldoAwal')->middleware('auth');
//
// // Aktiva
// Route::get('/aktiva', 'AktivaController@index')->middleware('auth');
// Route::get('/loadAllAktiva', 'AktivaController@loadAll')->middleware('auth');
// Route::post('/showAktiva', 'AktivaController@show')->middleware('auth');
// Route::post('/showAktivaNo', 'AktivaController@showNoAktiva')->middleware('auth');
// Route::post('/showDetailAktiva', 'AktivaController@showDetail')->middleware('auth');
// Route::post('/getUrutAktiva', 'AktivaController@getUrut')->middleware('auth');
// Route::post('/loadPostingAktiva', 'AktivaController@loadPosting')->middleware('auth');
// Route::post('/addAktiva','AktivaController@add')->middleware('auth');
// Route::post('/editAktiva','AktivaController@edit')->middleware('auth');
// Route::post('/eraseAktiva','AktivaController@erase')->middleware('auth');
// Route::post('/loadAwalAktiva','AktivaController@loadAwalAktiva')->middleware('auth');
// Route::post('/setAwalAktiva','AktivaController@setAwalAktiva')->middleware('auth');
// Route::post('/loadAktivaKodeAkumulasi','AktivaController@loadAktivaKodeAkumulasi')->middleware('auth');
//
// // Posting
// Route::get('/posting', 'PostingController@index')->middleware('auth');
// Route::get('/showBiaya','PostingController@showBiaya')->middleware('auth');
// Route::get('/showPenyusutan','PostingController@showPenyusutan')->middleware('auth');
// Route::post('/showPosting', 'PostingController@show')->middleware('auth');
// Route::post('/showPostingByID', 'PostingController@showByID')->middleware('auth');
// Route::post('/getPostingDPHDPP', 'PostingController@getDPHDPP')->middleware('auth');
// Route::post('/addPosting','PostingController@add')->middleware('auth');
// Route::post('/editPosting','PostingController@edit')->middleware('auth');
// Route::post('/erasePosting','PostingController@erase')->middleware('auth');
// Route::post('/showPostingAktiva', 'PostingController@showAA')->middleware('auth');
//
// // Giro
// Route::get('/giro', 'GiroController@index')->middleware('auth');
// Route::post('/loadGiro', 'GiroController@loadAll')->middleware('auth');
// Route::post('/showGiro', 'GiroController@show')->middleware('auth');
// Route::post('/addGiro', 'GiroController@add')->middleware('auth');
// Route::post('/editGiro', 'GiroController@edit')->middleware('auth');
// Route::post('/eraseGiro', 'GiroController@erase')->middleware('auth');
//
// // Laba Rugi
// Route::get('/lrhpp', 'LabaRugiController@index')->middleware('auth');
// Route::get('/loadAllLabaRugi', 'LabaRugiController@loadAll')->middleware('auth');
// Route::post('/showLabaRugi', 'LabaRugiController@show')->middleware('auth');
// Route::post('/addLabaRugi', 'LabaRugiController@add')->middleware('auth');
// Route::post('/editLabaRugi', 'LabaRugiController@edit')->middleware('auth');
// Route::post('/eraseLabaRugi', 'LabaRugiController@erase')->middleware('auth');
//
// // Neraca
// Route::get('/neraca', 'NeracaController@index')->middleware('auth');
// Route::get('/loadAllNeraca', 'NeracaController@loadAll')->middleware('auth');
// Route::post('/editNeraca', 'NeracaController@edit')->middleware('auth');
//
// // Area
// Route::get('/area', 'AreaController@index')->middleware('auth');
// Route::get('/loadAllArea', 'AreaController@loadAll')->middleware('auth');
// Route::post('/addArea', 'AreaController@add')->middleware('auth');
// Route::post('/editArea', 'AreaController@edit')->middleware('auth');
// Route::post('/eraseArea', 'AreaController@erase')->middleware('auth');
//
// // Kota
// Route::get('/kota', 'KotaController@index')->middleware('auth');
// Route::get('/loadAllKota', 'KotaController@loadAll')->middleware('auth');
// Route::post('/addKota', 'KotaController@add')->middleware('auth');
// Route::post('/editKota', 'KotaController@edit')->middleware('auth');
// Route::post('/eraseKota', 'KotaController@erase')->middleware('auth');
//
// // Jenis Customer
// Route::get('/jeniscustomer', 'JenisCustomerController@index')->middleware('auth');
// Route::get('/loadAllJenisCustomer', 'JenisCustomerController@loadAll')->middleware('auth');
// Route::post('/addJenisCustomer', 'JenisCustomerController@add')->middleware('auth');
// Route::post('/editJenisCustomer', 'JenisCustomerController@edit')->middleware('auth');
// Route::post('/eraseJenisCustomer', 'JenisCustomerController@erase')->middleware('auth');
//
// // Supplier
// Route::get('/supplier', 'SupplierController@index')->middleware('auth');
// Route::get('/loadAllSupplier', 'SupplierController@loadAll')->middleware('auth');
// Route::post('/loadAllSupplierPage', 'SupplierController@loadAllPage')->middleware('auth');
// Route::post('/getSupplierAkun', 'SupplierController@getSupplierAkun')->middleware('auth');
// Route::post('/addSupplier', 'SupplierController@add')->middleware('auth');
// Route::post('/showSupplier', 'SupplierController@show')->middleware('auth');
// Route::post('/editSupplier', 'SupplierController@edit')->middleware('auth');
// Route::post('/eraseSupplier', 'SupplierController@erase')->middleware('auth');
// Route::post('/getDetailSupplier', 'SupplierController@getDetail')->middleware('auth');
// Route::post('/addDetailSupplier', 'SupplierController@addDetail')->middleware('auth');
// Route::post('/editDetailSupplier', 'SupplierController@editDetail')->middleware('auth');
// Route::post('/deleteDetailSupplier', 'SupplierController@deleteDetail')->middleware('auth');
// Route::post('/getDetailAlamatSupplier', 'SupplierController@getDetailAlamat')->middleware('auth');
// Route::post('/addDetailAlamatSupplier', 'SupplierController@addDetailAlamat')->middleware('auth');
// Route::post('/editDetailAlamatSupplier', 'SupplierController@editDetailAlamat')->middleware('auth');
// Route::post('/deleteDetailAlamatSupplier', 'SupplierController@deleteDetailAlamat')->middleware('auth');
// Route::post('/showPPNSupplier', 'SupplierController@showPPN')->middleware('auth');
// Route::post('/showHariNotaSupplier', 'SupplierController@showHariNota')->middleware('auth');
//
// // Ekspedisi
// Route::get('/ekspedisi', 'EkspedisiController@index')->middleware('auth');
// Route::get('/loadAllEkspedisi', 'EkspedisiController@loadAll')->middleware('auth');
// Route::post('/addEkspedisi', 'EkspedisiController@add')->middleware('auth');
// Route::post('/showEkspedisi', 'EkspedisiController@show')->middleware('auth');
// Route::post('/editEkspedisi', 'EkspedisiController@edit')->middleware('auth');
// Route::post('/eraseEkspedisi', 'EkspedisiController@erase')->middleware('auth');
// Route::post('/getDetailEkspedisi', 'EkspedisiController@getDetail')->middleware('auth');
// Route::post('/addDetailEkspedisi', 'EkspedisiController@addDetail')->middleware('auth');
// Route::post('/editDetailEkspedisi', 'EkspedisiController@editDetail')->middleware('auth');
// Route::post('/deleteDetailEkspedisi', 'EkspedisiController@deleteDetail')->middleware('auth');
// Route::post('/getDetailAlamatEkspedisi', 'EkspedisiController@getDetailAlamat')->middleware('auth');
// Route::post('/addDetailAlamatEkspedisi', 'EkspedisiController@addDetailAlamat')->middleware('auth');
// Route::post('/editDetailAlamatEkspedisi', 'EkspedisiController@editDetailAlamat')->middleware('auth');
// Route::post('/deleteDetailAlamatEkspedisi', 'EkspedisiController@deleteDetailAlamat')->middleware('auth');
//
// // Customer
// Route::get('/customer', 'CustomerController@index')->middleware('auth');
// Route::get('/loadAllCustomer', 'CustomerController@loadAll')->middleware('auth');
// Route::post('/loadAllCustomerPage', 'CustomerController@loadAllPage')->middleware('auth');
// Route::post('/getCustomerAkun', 'CustomerController@getCustomerAkun')->middleware('auth');
// Route::post('/addCustomer', 'CustomerController@add')->middleware('auth');
// Route::post('/showCustomer', 'CustomerController@show')->middleware('auth');
// Route::post('/editCustomer', 'CustomerController@edit')->middleware('auth');
// Route::post('/eraseCustomer', 'CustomerController@erase')->middleware('auth');
// Route::post('/getDetailCustomer', 'CustomerController@getDetail')->middleware('auth');
// Route::post('/addDetailCustomer', 'CustomerController@addDetail')->middleware('auth');
// Route::post('/editDetailCustomer', 'CustomerController@editDetail')->middleware('auth');
// Route::post('/deleteDetailCustomer', 'CustomerController@deleteDetail')->middleware('auth');
// Route::post('/getDetailAlamatCustomer', 'CustomerController@getDetailAlamat')->middleware('auth');
// Route::post('/addDetailAlamatCustomer', 'CustomerController@addDetailAlamat')->middleware('auth');
// Route::post('/editDetailAlamatCustomer', 'CustomerController@editDetailAlamat')->middleware('auth');
// Route::post('/deleteDetailAlamatCustomer', 'CustomerController@deleteDetailAlamat')->middleware('auth');
// Route::post('/showPPNCustomer', 'CustomerController@showPPN')->middleware('auth');
// Route::post('/showHariNotaCustomer', 'CustomerController@showHariNota')->middleware('auth');
// Route::post('/showAlamatCustomer', 'CustomerController@showAlamat')->middleware('auth');
// Route::post('/loadPiutangCustomer', 'CustomerController@loadPiutangCustomer')->middleware('auth');
// Route::post('/loadPiutangCustomerNoBukti', 'CustomerController@loadPiutangCustomerNoBukti')->middleware('auth');
// Route::post('/cekCustomerAkun', 'CustomerController@cekCustomerAkun')->middleware('auth');
//
// // Hutang
// Route::get('/hutang', 'HutangController@index')->middleware('auth');
// Route::post('/loadAllHutang', 'HutangController@loadAll')->middleware('auth');
// Route::post('/addHutang', 'HutangController@add')->middleware('auth');
// Route::post('/showHutang', 'HutangController@show')->middleware('auth');
// Route::post('/editHutang', 'HutangController@edit')->middleware('auth');
// Route::post('/eraseHutang', 'HutangController@erase')->middleware('auth');
//
// // Piutang
// Route::get('/piutang', 'PiutangController@index')->middleware('auth');
// Route::post('/loadAllPiutang', 'PiutangController@loadAll')->middleware('auth');
// Route::post('/addPiutang', 'PiutangController@add')->middleware('auth');
// Route::post('/showPiutang', 'PiutangController@show')->middleware('auth');
// Route::post('/editPiutang', 'PiutangController@edit')->middleware('auth');
// Route::post('/erasePiutang', 'PiutangController@erase')->middleware('auth');
//
// Gudang
Route::get('/gudang', [GudangController::class, 'index'])->middleware('auth');
Route::get('/loadAllGudang', [GudangController::class, 'loadAll'])->middleware('auth');
Route::post('/addGudang', [GudangController::class, 'add'])->middleware('auth');
Route::post('/editGudang', [GudangController::class, 'edit'])->middleware('auth');
Route::post('/eraseGudang', [GudangController::class, 'erase'])->middleware('auth');
Route::post('/showRak', [GudangController::class, 'showRak'])->middleware('auth');
Route::post('/addRak', [GudangController::class, 'addRak'])->middleware('auth');
Route::post('/showLokasi', [GudangController::class, 'showLokasi'])->middleware('auth');
Route::post('/addLokasi', [GudangController::class, 'addLokasi'])->middleware('auth');
//
// // Grup
// Route::get('/kategori', 'GrupController@index')->middleware('auth');
// Route::get('/loadAllGrup', 'GrupController@loadAll')->middleware('auth');
// Route::post('/getGrup', 'GrupController@get')->middleware('auth');
// Route::post('/addGrup', 'GrupController@add')->middleware('auth');
// Route::post('/editGrup', 'GrupController@edit')->middleware('auth');
// Route::post('/eraseGrup', 'GrupController@erase')->middleware('auth');
//
// // Kategori
// Route::get('/grupbaku', 'KategoriController@index')->middleware('auth');
// Route::get('/loadAllKategori', 'KategoriController@loadAll')->middleware('auth');
// Route::post('/addKategori', 'KategoriController@add')->middleware('auth');
// Route::post('/editKategori', 'KategoriController@edit')->middleware('auth');
// Route::post('/eraseKategori', 'KategoriController@erase')->middleware('auth');
//
// // Model
// Route::get('/model', 'ModelController@index')->middleware('auth');
// Route::get('/loadAllModel', 'ModelController@loadAll')->middleware('auth');
// Route::post('/addModel', 'ModelController@add')->middleware('auth');
// Route::post('/editModel', 'ModelController@edit')->middleware('auth');
// Route::post('/eraseModel', 'ModelController@erase')->middleware('auth');
//
// // Merk Baku
// Route::get('/merkbaku', 'MerkBakuController@index')->middleware('auth');
// Route::get('/loadAllMerkBaku', 'MerkBakuController@loadAll')->middleware('auth');
// Route::post('/addMerkBaku', 'MerkBakuController@add')->middleware('auth');
// Route::post('/editMerkBaku', 'MerkBakuController@edit')->middleware('auth');
// Route::post('/eraseMerkBaku', 'MerkBakuController@erase')->middleware('auth');
//
// // Warna Baku
// Route::get('/warnabaku', 'WarnaBakuController@index')->middleware('auth');
// Route::get('/loadAllWarnaBaku', 'WarnaBakuController@loadAll')->middleware('auth');
// Route::post('/addWarnaBaku', 'WarnaBakuController@add')->middleware('auth');
// Route::post('/editWarnaBaku', 'WarnaBakuController@edit')->middleware('auth');
// Route::post('/eraseWarnaBaku', 'WarnaBakuController@erase')->middleware('auth');
//
// // Ukuran
// Route::get('/ukuran', 'UkuranController@index')->middleware('auth');
// Route::get('/loadAllUkuran', 'UkuranController@loadAll')->middleware('auth');
// Route::post('/addUkuran', 'UkuranController@add')->middleware('auth');
// Route::post('/editUkuran', 'UkuranController@edit')->middleware('auth');
// Route::post('/eraseUkuran', 'UkuranController@erase')->middleware('auth');
//
// // Merk Jadi
// Route::get('/merkjadi', 'MerkJadiController@index')->middleware('auth');
// Route::get('/loadAllMerkJadi', 'MerkJadiController@loadAll')->middleware('auth');
// Route::post('/getMerkJadi', 'MerkJadiController@get')->middleware('auth');
// Route::post('/addMerkJadi', 'MerkJadiController@add')->middleware('auth');
// Route::post('/editMerkJadi', 'MerkJadiController@edit')->middleware('auth');
// Route::post('/eraseMerkJadi', 'MerkJadiController@erase')->middleware('auth');
//
// // Design
// Route::get('/grupjadi', 'GrupJadiController@index')->middleware('auth');
// Route::get('/loadAllGrupJadi', 'GrupJadiController@loadAll')->middleware('auth');
// Route::post('/addGrupJadi', 'GrupJadiController@add')->middleware('auth');
// Route::post('/editGrupJadi', 'GrupJadiController@edit')->middleware('auth');
// Route::post('/eraseGrupJadi', 'GrupJadiController@erase')->middleware('auth');
//
// // Keterangan
// Route::get('/subgrupjadi', 'SubgrupJadiController@index')->middleware('auth');
// Route::get('/loadAllSubgrup', 'SubgrupJadiController@loadAll')->middleware('auth');
// Route::post('/addSubgrup', 'SubgrupJadiController@add')->middleware('auth');
// Route::post('/editSubgrup', 'SubgrupJadiController@edit')->middleware('auth');
// Route::post('/eraseSubgrup', 'SubgrupJadiController@erase')->middleware('auth');
// Route::post('/getSubgrup', 'SubgrupJadiController@getSubgrup')->middleware('auth');
//
// // Dimensi
// Route::get('/dimensi', 'DimensiController@index')->middleware('auth');
// Route::get('/loadAllDimensi', 'DimensiController@loadAll')->middleware('auth');
// Route::post('/addDimensi', 'DimensiController@add')->middleware('auth');
// Route::post('/editDimensi', 'DimensiController@edit')->middleware('auth');
// Route::post('/eraseDimensi', 'DimensiController@erase')->middleware('auth');
//
// // Kombinasi
// Route::get('/kombinasi', 'KombinasiController@index')->middleware('auth');
// Route::get('/loadAllKombinasi', 'KombinasiController@loadAll')->middleware('auth');
// Route::post('/addKombinasi', 'KombinasiController@add')->middleware('auth');
// Route::post('/editKombinasi', 'KombinasiController@edit')->middleware('auth');
// Route::post('/eraseKombinasi', 'KombinasiController@erase')->middleware('auth');
//
// // Warna Jadi
// Route::get('/warnajadi', 'WarnaJadiController@index')->middleware('auth');
// Route::get('/loadAllWarnaJadi', 'WarnaJadiController@loadAll')->middleware('auth');
// Route::post('/addWarnaJadi', 'WarnaJadiController@add')->middleware('auth');
// Route::post('/editWarnaJadi', 'WarnaJadiController@edit')->middleware('auth');
// Route::post('/eraseWarnaJadi', 'WarnaJadiController@erase')->middleware('auth');
//
// // Bahan Baku
// Route::get('/bahanbaku', 'BahanBakuController@index')->middleware('auth');
// Route::post('/loadAllBahanBaku', 'BahanBakuController@loadAll')->middleware('auth');
// Route::post('/addBahanBaku', 'BahanBakuController@add')->middleware('auth');
// Route::post('/showBahanBaku', 'BahanBakuController@show')->middleware('auth');
// Route::post('/editBahanBaku', 'BahanBakuController@edit')->middleware('auth');
// Route::post('/eraseBahanBaku', 'BahanBakuController@erase')->middleware('auth');
//

// Barang Jadi
Route::get('/barangjadi', [BarangJadiController::class, 'index'])->middleware('auth');
Route::post('/loadAllBarangJadi', [BarangJadiController::class, 'loadAll'])->middleware('auth');
Route::post('/barangjadidata', [BarangJadiController::class, 'dataTabel'])->middleware('auth');
Route::post('/addBarangJadi', [BarangJadiController::class, 'add'])->middleware('auth');
Route::post('/showBarangJadi', [BarangJadiController::class, 'show'])->middleware('auth');
Route::post('/editBarangJadi', [BarangJadiController::class, 'edit'])->middleware('auth');
Route::post('/eraseBarangJadi', [BarangJadiController::class, 'erase'])->middleware('auth');
Route::post('/showBarangLokasi', [BarangJadiController::class, 'showBarangLokasi'])->middleware('auth');
Route::post('/addBarangLokasi', [BarangJadiController::class, 'addBarangLokasi'])->middleware('auth');
Route::post('/brgonchangeheader' , [BarangJadiController::class, 'onChangeHeader'])->middleware('auth');
Route::post('/loadkodebrg', [BarangJadiController::class, 'loadkodebrg'])->middleware('auth');
//
// // Bahan SparePart
// Route::get('/bahansparepart', 'BahanSparePartController@index')->middleware('auth');
// Route::post('/loadAllBahanSparePart', 'BahanSparePartController@loadAll')->middleware('auth');
// Route::post('/addBahanSparePart', 'BahanSparePartController@add')->middleware('auth');
// Route::post('/showBahanSparePart', 'BahanSparePartController@show')->middleware('auth');
// Route::post('/editBahanSparePart', 'BahanSparePartController@edit')->middleware('auth');
// Route::post('/eraseBahanSparePart', 'BahanSparePartController@erase')->middleware('auth');
//
// // Jasa
// Route::get('/jasa', 'JasaController@index')->middleware('auth');
// Route::post('/loadAllJasa', 'JasaController@loadAll')->middleware('auth');
// Route::post('/addJasa', 'JasaController@add')->middleware('auth');
// Route::post('/showJasa', 'JasaController@show')->middleware('auth');
// Route::post('/editJasa', 'JasaController@edit')->middleware('auth');
// Route::post('/eraseJasa', 'JasaController@erase')->middleware('auth');
//
// // Promo
// Route::get('/promo', 'PromoController@index')->middleware('auth');
// Route::get('/loadAllPromo', 'PromoController@loadAll')->middleware('auth');
// Route::post('/getPromo', 'PromoController@getDisk')->middleware('auth');
// Route::post('/getPromoBarang', 'PromoController@getDiskBarang')->middleware('auth');
// Route::post('/addPromo', 'PromoController@add')->middleware('auth');
// Route::post('/editPromo', 'PromoController@edit')->middleware('auth');
// Route::post('/erasePromo', 'PromoController@erase')->middleware('auth');
//
// // Proses
// Route::get('/masterproses', 'ProsesController@index')->middleware('auth');
// Route::get('/loadAllProses', 'ProsesController@loadAll')->middleware('auth');
// Route::post('/addProses', 'ProsesController@add')->middleware('auth');
// Route::post('/showProses', 'ProsesController@show')->middleware('auth');
// Route::post('/editProses', 'ProsesController@edit')->middleware('auth');
// Route::post('/eraseProses', 'ProsesController@erase')->middleware('auth');
//
// // Sales
// Route::get('/sales', 'SalesController@index')->middleware('auth');
// Route::get('/loadAllSales', 'SalesController@loadAll')->middleware('auth');
// Route::post('/addSales', 'SalesController@add')->middleware('auth');
// Route::post('/showSales', 'SalesController@show')->middleware('auth');
// Route::post('/editSales', 'SalesController@edit')->middleware('auth');
// Route::post('/eraseSales', 'SalesController@erase')->middleware('auth');
// Route::post('/getSalesCust', 'SalesController@getSC')->middleware('auth');
// Route::post('/addSalesCust', 'SalesController@addSC')->middleware('auth');
// Route::post('/editSalesCust', 'SalesController@editSC')->middleware('auth');
// Route::post('/deleteSalesCust', 'SalesController@deleteSC')->middleware('auth');
// Route::post('/getTarget', 'SalesController@getT')->middleware('auth');
// Route::post('/addTarget', 'SalesController@addT')->middleware('auth');
// Route::post('/editTarget', 'SalesController@editT')->middleware('auth');
// Route::post('/showTarget', 'SalesController@showT')->middleware('auth');
// Route::post('/deleteTarget', 'SalesController@deleteT')->middleware('auth');
// Route::post('/getForecast', 'SalesController@getF')->middleware('auth');
// Route::post('/addForecast', 'SalesController@addF')->middleware('auth');
// Route::post('/editForecast', 'SalesController@editF')->middleware('auth');
// Route::post('/showForecast', 'SalesController@showF')->middleware('auth');
// Route::post('/deleteForecast', 'SalesController@deleteF')->middleware('auth');
//
// // Departemen
// Route::get('/departemen', 'DepartemenController@index')->middleware('auth');
// Route::get('/loadAllDepartemen', 'DepartemenController@loadAll')->middleware('auth');
// Route::post('/addDepartemen', 'DepartemenController@add')->middleware('auth');
// Route::post('/showDepartemen', 'DepartemenController@show')->middleware('auth');
// Route::post('/editDepartemen', 'DepartemenController@edit')->middleware('auth');
// Route::post('/eraseDepartemen', 'DepartemenController@erase')->middleware('auth');
//
// // Jabatan
// Route::get('/jabatan', 'JabatanController@index')->middleware('auth');
// Route::get('/loadAllJabatan', 'JabatanController@loadAll')->middleware('auth');
// Route::post('/addJabatan', 'JabatanController@add')->middleware('auth');
// Route::post('/showJabatan', 'JabatanController@show')->middleware('auth');
// Route::post('/editJabatan', 'JabatanController@edit')->middleware('auth');
// Route::post('/eraseJabatan', 'JabatanController@erase')->middleware('auth');
//
// // Biaya
// Route::get('/biaya', 'BiayaController@index')->middleware('auth');
// Route::get('/loadAllBiaya', 'BiayaController@loadAll')->middleware('auth');
// Route::post('/addBiaya', 'BiayaController@add')->middleware('auth');
// Route::post('/editBiaya', 'BiayaController@edit')->middleware('auth');
// Route::post('/eraseBiaya', 'BiayaController@erase')->middleware('auth');
//
// // Faktur Pajak
// Route::get('/fakturpajak', 'FakturPajakController@index')->middleware('auth');
// Route::get('/loadAllFaktur', 'FakturPajakController@loadAll')->middleware('auth');
// Route::post('/showFaktur', 'FakturPajakController@show')->middleware('auth');
// Route::post('/addFaktur', 'FakturPajakController@add')->middleware('auth');
// Route::post('/editFaktur', 'FakturPajakController@edit')->middleware('auth');
// Route::post('/eraseFaktur', 'FakturPajakController@erase')->middleware('auth');
//
// // Sopir
// Route::get('/sopir', 'SopirController@index')->middleware('auth');
// Route::get('/loadAllSopir', 'SopirController@loadAll')->middleware('auth');
// Route::post('/addSopir', 'SopirController@add')->middleware('auth');
// Route::post('/showSopir', 'SopirController@show')->middleware('auth');
// Route::post('/editSopir', 'SopirController@edit')->middleware('auth');
// Route::post('/eraseSopir', 'SopirController@erase')->middleware('auth');
//
// // Costing
// Route::get('/costing', 'CostingController@index')->middleware('auth');
// Route::get('/loadAllCosting', 'CostingController@loadAll')->middleware('auth');
// Route::post('/addCosting', 'CostingController@add')->middleware('auth');
// Route::post('/showCosting', 'CostingController@show')->middleware('auth');
// Route::post('/editCosting', 'CostingController@edit')->middleware('auth');
// Route::post('/eraseCosting', 'CostingController@erase')->middleware('auth');
// Route::post('/showPerkiraanCosting', 'CostingController@showPerkiraan')->middleware('auth');
// Route::post('/addPerkiraanCosting', 'CostingController@addPerkiraan')->middleware('auth');
// Route::post('/editPerkiraanCosting', 'CostingController@editPerkiraan')->middleware('auth');
// Route::post('/erasePerkiraanCosting', 'CostingController@erasePerkiraan')->middleware('auth');
// Route::post('/showSubCosting', 'CostingController@showDetail')->middleware('auth');
// Route::post('/addSubCosting', 'CostingController@addDetail')->middleware('auth');
// Route::post('/editSubCosting', 'CostingController@editDetail')->middleware('auth');
// Route::post('/eraseSubCosting', 'CostingController@eraseDetail')->middleware('auth');
//
// // Bon
// Route::get('/bon', 'BonController@index')->middleware('auth');
// Route::get('/generateNomorBuktiBon', 'BonController@generateNomorBuktiBon')->middleware('auth');
// Route::get('/generateNomorUrutBon', 'BonController@generateNomorUrutBon')->middleware('auth');
// Route::post('/loadAllBon', 'BonController@loadAllBon')->middleware('auth');
// Route::post('/addBon', 'BonController@addBon')->middleware('auth');
// Route::post('/showBon', 'BonController@showBon')->middleware('auth');
// Route::post('/eraseBon', 'BonController@eraseBon')->middleware('auth');
// Route::post('/editBon', 'BonController@editBon')->middleware('auth');
// Route::get('/loadAllOutBon', 'BonController@loadAllOutBon')->middleware('auth');
// Route::post('/getBonNoBukti', 'BonController@getBonNoBukti')->middleware('auth');
// Route::post('/getBonNoBukti2', 'BonController@getBonNoBukti2')->middleware('auth');
//
// // Kas
// Route::get('/kas', 'KasController@index')->middleware('auth');
// Route::post('/getAllPerkiraan', 'KasController@getAllPerkiraan')->middleware('auth');
// Route::post('/getCosting', 'KasController@getCosting')->middleware('auth');
// Route::post('/getDetailCosting', 'KasController@getDetailCosting')->middleware('auth');
// Route::post('/generateNomorBuktiKas', 'KasController@generateNomorBuktiKas')->middleware('auth');
// Route::post('/generateNomorUrutKas', 'KasController@generateNomorUrutKas')->middleware('auth');
// Route::post('/addKas', 'KasController@addKas')->middleware('auth');
// Route::post('/showKas', 'KasController@showKas')->middleware('auth');
// Route::post('/showDetKas', 'KasController@showDetKas')->middleware('auth');
// Route::post('/showDetKasEdit', 'KasController@showDetKasEdit')->middleware('auth');
// Route::post('/eraseKas', 'KasController@eraseKas')->middleware('auth');
// Route::post('/authKas', 'KasController@authKas')->middleware('auth');
// Route::post('/batalKas', 'KasController@batalKas')->middleware('auth');
// Route::post('/editKas', 'KasController@editKas')->middleware('auth');
// Route::get('/loadAllKas', 'KasController@loadAllKas')->middleware('auth');
// Route::post('/loadDPH', 'KasController@loadDPH')->middleware('auth');
// Route::post('/loadDetailDPH', 'KasController@loadDetailDPH')->middleware('auth');
// Route::post('/loadDetailDPHSisa', 'KasController@loadDetailDPHSisa')->middleware('auth');
// Route::post('/getHutangKas', 'KasController@getHutangKas')->middleware('auth');
// Route::get('/getPerkiraanPTS', 'KasController@getPerkiraanPTS')->middleware('auth');
// Route::get('/getPerkiraanHutang', 'KasController@getPerkiraanHutang')->middleware('auth');
// Route::get('/getPerkiraanHutangDPH', 'KasController@getPerkiraanHutangDPH')->middleware('auth');
// Route::post('/getKodeSupplierHutang', 'KasController@getKodeSupplierHutang')->middleware('auth');
// Route::post('/loadDetailDPHLebih', 'KasController@loadDetailDPHLebih')->middleware('auth');
// Route::post('/getSupplierAkun', 'KasController@getSupplierAkun')->middleware('auth');
// Route::post('/loadHutangSupplierKas', 'KasController@loadHutangSupplierKas')->middleware('auth');
// Route::post('/getNamaPerkiraan', 'KasController@getNamaPerkiraan')->middleware('auth');
// Route::get('/getPerkiraanAktiva', 'KasController@getPerkiraanAktiva')->middleware('auth');
// Route::post('/getBon', 'KasController@getBon')->middleware('auth');
// Route::post('/checkAmbilBayar', 'KasController@checkAmbilBayar')->middleware('auth');
// Route::post('/getAllBon', 'KasController@getAllBon')->middleware('auth');
// Route::post('/getBonNoBuktiEdit', 'KasController@getBonNoBuktiEdit')->middleware('auth');
// Route::post('/getAmbilBayar', 'KasController@getAmbilBayar')->middleware('auth');
// Route::post('/getDetailCostingCust', 'KasController@getDetailCostingCust')->middleware('auth');
// Route::post('/getDetailCostingByIDCust', 'KasController@getDetailCostingByIDCust')->middleware('auth');
//
// // Bank
// Route::get('/bank', 'BankController@index')->middleware('auth');
// Route::post('/getAllPerkiraanBank', 'BankController@getAllPerkiraan')->middleware('auth');
// Route::post('/generateNomorBuktiBank', 'BankController@generateNomorBuktiBank')->middleware('auth');
// Route::post('/generateNomorUrutBank', 'BankController@generateNomorUrutBank')->middleware('auth');
// Route::post('/addBank', 'BankController@addBank')->middleware('auth');
// Route::post('/showBank', 'BankController@showBank')->middleware('auth');
// Route::post('/showDetBank', 'BankController@showDetBank')->middleware('auth');
// Route::post('/showDetBankEdit', 'BankController@showDetBankEdit')->middleware('auth');
// Route::post('/eraseBank', 'BankController@eraseBank')->middleware('auth');
// Route::post('/authBank', 'BankController@authBank')->middleware('auth');
// Route::post('/batalBank', 'BankController@batalBank')->middleware('auth');
// Route::post('/editBank', 'BankController@editBank')->middleware('auth');
// Route::get('/loadAllBank', 'BankController@loadAllBank')->middleware('auth');
// Route::post('/getHutangBank', 'BankController@getHutangBank')->middleware('auth');
// Route::post('/loadHutangSupplierBank', 'BankController@loadHutangSupplierBank')->middleware('auth');
//
// // Memorial / Koreksi
// Route::get('/memorialkoreksi', 'MemorialKoreksiController@index')->middleware('auth');
// Route::get('/loadAllMemorialKoreksi', 'MemorialKoreksiController@loadAll')->middleware('auth');
// Route::post('/generateNomorBuktiMemorialKoreksi', 'MemorialKoreksiController@generateNomorBukti')->middleware('auth');
// Route::post('/generateNomorUrutMemorialKoreksi', 'MemorialKoreksiController@generateNomorUrut')->middleware('auth');
// Route::post('/cekPostingHutangPiutang', 'MemorialKoreksiController@cekPostingHutangPiutang')->middleware('auth');
// Route::post('/addMemorialKoreksi', 'MemorialKoreksiController@add')->middleware('auth');
// Route::post('/showMemorialKoreksi', 'MemorialKoreksiController@show')->middleware('auth');
// Route::post('/showDetMemorialKoreksi', 'MemorialKoreksiController@showDet')->middleware('auth');
// Route::post('/getHutPiutMK', 'MemorialKoreksiController@getHutPiutMK')->middleware('auth');
// Route::post('/editMemorialKoreksi', 'MemorialKoreksiController@edit')->middleware('auth');
// Route::post('/eraseMemorialKoreksi', 'MemorialKoreksiController@erase')->middleware('auth');
// Route::post('/authMemorialKoreksi', 'MemorialKoreksiController@changeAuth')->middleware('auth');
// Route::post('/batalMemorialKoreksi', 'MemorialKoreksiController@changeBatal')->middleware('auth');
//
// // DPH
// Route::get('/dph', 'DPHController@index')->middleware('auth');
// Route::get('/generateNomorUrutDPH', 'DPHController@generateNomorUrutDPH')->middleware('auth');
// Route::get('/generateNomorBuktiDPH', 'DPHController@generateNomorBuktiDPH')->middleware('auth');
// Route::post('/searchHutang', 'DPHController@searchHutang')->middleware('auth');
// Route::post('/addDPH', 'DPHController@addDPH')->middleware('auth');
// Route::post('/searchNominalHutang', 'DPHController@searchNominalHutang')->middleware('auth');
// Route::post('/authDPH', 'DPHController@authDPH')->middleware('auth');
// Route::post('/batalDPH', 'DPHController@batalDPH')->middleware('auth');
// Route::get('/loadAllDPH', 'DPHController@loadAllDPH')->middleware('auth');
// Route::post('/showDPH', 'DPHController@showDPH')->middleware('auth');
// Route::post('/showDPHNoBukti', 'DPHController@showDPHNoBukti')->middleware('auth');
// Route::post('/eraseDPH', 'DPHController@eraseDPH')->middleware('auth');
// Route::post('/getHutang', 'DPHController@getHutang')->middleware('auth');
// Route::post('/editDPH', 'DPHController@editDPH')->middleware('auth');
// Route::post('/getNominalHutang', 'DPHController@getNominalHutang')->middleware('auth');
// Route::post('/showDetDPH', 'DPHController@showDetDPH')->middleware('auth');
// Route::post('/searchHutangLuar', 'DPHController@searchHutangLuar')->middleware('auth');
// Route::post('/showDetPerkiraan', 'DPHController@showDetPerkiraan')->middleware('auth');
// Route::post('/getThisHutang', 'DPHController@getThisHutang')->middleware('auth');
// Route::get('/loadPerkiraanPosting', 'DPHController@loadPerkiraanPosting')->middleware('auth');
//
// // Bukti Giro Terima
// Route::get('/buktigiroterima', 'BuktiGiroTerimaController@index')->middleware('auth');
// Route::get('/loadAllBGT', 'BuktiGiroTerimaController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiBuktiGiroTerima', 'BuktiGiroTerimaController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutBuktiGiroTerima', 'BuktiGiroTerimaController@generateNomorUrut')->middleware('auth');
// Route::post('/addBuktiGiroTerima', 'BuktiGiroTerimaController@add')->middleware('auth');
// Route::post('/showBuktiGiroTerima', 'BuktiGiroTerimaController@show')->middleware('auth');
// Route::post('/showDetBuktiGiroTerima', 'BuktiGiroTerimaController@showDet')->middleware('auth');
// Route::post('/showDet2BuktiGiroTerima', 'BuktiGiroTerimaController@showDet2')->middleware('auth');
// Route::post('/editBuktiGiroTerima', 'BuktiGiroTerimaController@edit')->middleware('auth');
// Route::post('/eraseBuktiGiroTerima', 'BuktiGiroTerimaController@erase')->middleware('auth');
// Route::post('/authBuktiGiroTerima', 'BuktiGiroTerimaController@changeAuth')->middleware('auth');
// Route::post('/batalBuktiGiroTerima', 'BuktiGiroTerimaController@changeBatal')->middleware('auth');
// Route::post('/getPiutangGiro', 'BuktiGiroTerimaController@getPiutangGiro')->middleware('auth');
//
// // Bukti Giro Cair
// Route::get('/buktigirocair', 'BuktiGiroCairController@index')->middleware('auth');
// Route::get('/loadAllBGC', 'BuktiGiroCairController@loadAll')->middleware('auth');
// Route::get('/getOutstandingGiro', 'BuktiGiroCairController@loadOutstandingGiro')->middleware('auth');
// Route::get('/getOutstandingDev', 'BuktiGiroCairController@loadOutstandingDev')->middleware('auth');
// Route::get('/generateNomorBuktiBuktiGiroCair', 'BuktiGiroCairController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutBuktiGiroCair', 'BuktiGiroCairController@generateNomorUrut')->middleware('auth');
// Route::post('/addBuktiGiroCair', 'BuktiGiroCairController@add')->middleware('auth');
// Route::post('/showBuktiGiroCair', 'BuktiGiroCairController@show')->middleware('auth');
// Route::post('/showDetBuktiGiroCair', 'BuktiGiroCairController@showDet')->middleware('auth');
// Route::post('/showDet2BuktiGiroCair', 'BuktiGiroCairController@showDet2')->middleware('auth');
// Route::post('/editBuktiGiroCair', 'BuktiGiroCairController@edit')->middleware('auth');
// Route::post('/eraseBuktiGiroCair', 'BuktiGiroCairController@erase')->middleware('auth');
// Route::post('/authBuktiGiroCair', 'BuktiGiroCairController@changeAuth')->middleware('auth');
// Route::post('/batalBuktiGiroCair', 'BuktiGiroCairController@changeBatal')->middleware('auth');
//
// // Bukti Buka Giro
// Route::get('/buktibukagiro', 'BuktiBukaGiroController@index')->middleware('auth');
// Route::get('/loadAllBBG', 'BuktiBukaGiroController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiBuktiBukaGiro', 'BuktiBukaGiroController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutBuktiBukaGiro', 'BuktiBukaGiroController@generateNomorUrut')->middleware('auth');
// Route::post('/addBuktiBukaGiro', 'BuktiBukaGiroController@add')->middleware('auth');
// Route::post('/showBuktiBukaGiro', 'BuktiBukaGiroController@show')->middleware('auth');
// Route::post('/showDetBuktiBukaGiro', 'BuktiBukaGiroController@showDet')->middleware('auth');
// Route::post('/showDet2BuktiBukaGiro', 'BuktiBukaGiroController@showDet2')->middleware('auth');
// Route::post('/editBuktiBukaGiro', 'BuktiBukaGiroController@edit')->middleware('auth');
// Route::post('/eraseBuktiBukaGiro', 'BuktiBukaGiroController@erase')->middleware('auth');
// Route::post('/authBuktiBukaGiro', 'BuktiBukaGiroController@changeAuth')->middleware('auth');
// Route::post('/batalBuktiBukaGiro', 'BuktiBukaGiroController@changeBatal')->middleware('auth');
// Route::get('/getDPHGiro', 'BuktiBukaGiroController@getDPHGiro')->middleware('auth');
// Route::post('/getDPHGiro2', 'BuktiBukaGiroController@getDPHGiro2')->middleware('auth');
// Route::post('/getSumDPP', 'BuktiBukaGiroController@getSumDPP')->middleware('auth');
// Route::post('/getKursDPH', 'BuktiBukaGiroController@getKursDPH')->middleware('auth');
// Route::post('/getDPHLB', 'BuktiBukaGiroController@getDPHLB')->middleware('auth');
// Route::post('/getDPHKB', 'BuktiBukaGiroController@getDPHKB')->middleware('auth');
// Route::post('/loadHutangSupplier', 'BuktiBukaGiroController@loadHutangSupplier')->middleware('auth');
// Route::post('/loadHutangSupplierNoBukti', 'BuktiBukaGiroController@loadHutangSupplierNoBukti')->middleware('auth');
// Route::post('/getHutangGiro', 'BuktiBukaGiroController@getHutangGiro')->middleware('auth');
// Route::post('/getPerkiraanDPH', 'BuktiBukaGiroController@getPerkiraanDPH')->middleware('auth');
//
// // Bukti Cair Giro
// Route::get('/bukticairgiro', 'BuktiCairGiroController@index')->middleware('auth');
// Route::get('/loadAllBCG', 'BuktiCairGiroController@loadAll')->middleware('auth');
// Route::post('/getOutstandingGiro2', 'BuktiCairGiroController@loadOutstandingGiro')->middleware('auth');
// Route::get('/getOutstandingDev2', 'BuktiCairGiroController@loadOutstandingDev')->middleware('auth');
// Route::get('/generateNomorBuktiBuktiCairGiro', 'BuktiCairGiroController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutBuktiCairGiro', 'BuktiCairGiroController@generateNomorUrut')->middleware('auth');
// Route::post('/addBuktiCairGiro', 'BuktiCairGiroController@add')->middleware('auth');
// Route::post('/showBuktiCairGiro', 'BuktiCairGiroController@show')->middleware('auth');
// Route::post('/showDetBuktiCairGiro', 'BuktiCairGiroController@showDet')->middleware('auth');
// Route::post('/showDet2BuktiCairGiro', 'BuktiCairGiroController@showDet2')->middleware('auth');
// Route::post('/editBuktiCairGiro', 'BuktiCairGiroController@edit')->middleware('auth');
// Route::post('/eraseBuktiCairGiro', 'BuktiCairGiroController@erase')->middleware('auth');
// Route::post('/authBuktiCairGiro', 'BuktiCairGiroController@changeAuth')->middleware('auth');
// Route::post('/batalBuktiCairGiro', 'BuktiCairGiroController@changeBatal')->middleware('auth');
//
// // Pelunasan DPP
// Route::get('/pelunasandpp', 'PelunasanDPPController@index')->middleware('auth');
// Route::get('/loadAllPLD', 'PelunasanDPPController@loadAll')->middleware('auth');
// Route::get('/loadOutstandingPelunasan', 'PelunasanDPPController@loadOutstandingPelunasan')->middleware('auth');
// Route::post('/loadOutstandingPelunasanNoBukti', 'PelunasanDPPController@loadOutstandingPelunasanNoBukti')->middleware('auth');
// Route::post('/loadOutstandingPiutang', 'PelunasanDPPController@loadOutstandingPiutangCust')->middleware('auth');
// Route::post('/loadOutstandingPiutangNoBukti', 'PelunasanDPPController@loadOutstandingPiutangCustNoBukti')->middleware('auth');
// Route::post('/loadPerkiraanPiutangCust', 'PelunasanDPPController@loadPerkiraanPiutangCust')->middleware('auth');
// Route::get('/generateNomorBuktiPelunasanDPP', 'PelunasanDPPController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPelunasanDPP', 'PelunasanDPPController@generateNomorUrut')->middleware('auth');
// Route::post('/getTransDPP', 'PelunasanDPPController@getTrans')->middleware('auth');
// Route::post('/addPLD', 'PelunasanDPPController@add')->middleware('auth');
// Route::post('/showPLD', 'PelunasanDPPController@show')->middleware('auth');
// Route::post('/showDetPLD', 'PelunasanDPPController@showDet')->middleware('auth');
// Route::post('/editPLD', 'PelunasanDPPController@edit')->middleware('auth');
// Route::post('/erasePLD', 'PelunasanDPPController@erase')->middleware('auth');
// Route::post('/authPelunasanDPP', 'PelunasanDPPController@changeAuth')->middleware('auth');
// Route::post('/batalPelunasanDPP', 'PelunasanDPPController@changeBatal')->middleware('auth');
//
// // Batch
// Route::post('/getBatchNoBukti', 'BatchController@getBatchNoBukti')->middleware('auth');
// Route::post('/getBatchNoBuktiJual', 'BatchController@getBatchNoBuktiJual')->middleware('auth');
// Route::post('/getBatchNoBuktiBarang', 'BatchController@getBatchNoBuktiBarang')->middleware('auth');
// Route::post('/getBatchBarang', 'BatchController@getBatchBarang')->middleware('auth');
// Route::post('/getBatchBarangBAP', 'BatchController@getBatchBarangBAP')->middleware('auth');
//
// // PR
// Route::get('/pr', 'PRController@index')->middleware('auth');
// Route::get('/loadAllPR', 'PRController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiPR', 'PRController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPR', 'PRController@generateNomorUrut')->middleware('auth');
// //Route::post('/getBarangByKodePR', 'PRController@getBarangByKodePR')->middleware('auth');
// Route::post('/getBarangByKode', 'PRController@getBarangByKode')->middleware('auth');
// Route::post('/getBarangByGrup', 'PRController@getBarangByGrup')->middleware('auth');
// Route::post('/getBarangByGrupGdg', 'PRController@getBarangByGrupGdg')->middleware('auth');
// Route::post('/getBarangByGrupGdgPB', 'PRController@getBarangByGrupGdgPB')->middleware('auth');
// Route::post('/getBarangByGrupGdgNoSo', 'PRController@getBarangByGrupGdgNoSo')->middleware('auth');
// Route::post('/addPR', 'PRController@add')->middleware('auth');
// Route::post('/showPR', 'PRController@showPR')->middleware('auth');
// Route::post('/showDetPR', 'PRController@showDetPR')->middleware('auth');
// Route::post('/editPR', 'PRController@edit')->middleware('auth');
// Route::post('/erasePR', 'PRController@erase')->middleware('auth');
// Route::post('/authPR', 'PRController@changeAuth')->middleware('auth');
// Route::post('/batalPR', 'PRController@changeBatal')->middleware('auth');
//
// // PO
// Route::get('/po', 'POController@index')->middleware('auth');
// Route::get('/loadOutstandingPR', 'POController@loadAllPR')->middleware('auth');
// Route::get('/loadAllPO', 'POController@loadAllPO')->middleware('auth');
// Route::get('/loadBarangPO', 'POController@loadBarangPO')->middleware('auth');
// Route::post('/generateNomorUrutPO', 'POController@generateNomorUrut')->middleware('auth');
// Route::post('/generateNomorBuktiPO', 'POController@generateNomorBukti')->middleware('auth');
// Route::post('/cekHargaPO', 'POController@getHarga')->middleware('auth');
// Route::post('/addPO', 'POController@add')->middleware('auth');
// Route::post('/showPO', 'POController@showPO')->middleware('auth');
// Route::post('/showPObyNomorBukti', 'POController@showPObyNomorBukti')->middleware('auth');
// Route::post('/showDetPO', 'POController@showDetPO')->middleware('auth');
// Route::post('/editPO', 'POController@edit')->middleware('auth');
// Route::post('/erasePO', 'POController@erase')->middleware('auth');
// Route::post('/authPO', 'POController@changeAuth')->middleware('auth');
// Route::post('/batalPO', 'POController@changeBatal')->middleware('auth');
//
// // Closing PO
// Route::get('/closingpo', 'ClosingPOController@index')->middleware('auth');
// Route::get('/loadAllOutstandingPO', 'ClosingPOController@loadAllOutstandingPO')->middleware('auth');
// Route::get('/loadAllClosingPO', 'ClosingPOController@loadAllClosingPO')->middleware('auth');
// Route::post('/closePO', 'ClosingPOController@close')->middleware('auth');
// Route::post('/batalclosePO', 'ClosingPOController@batalclose')->middleware('auth');
//
// // Uang Muka Beli
// Route::get('/uangmukabeli', 'UangMukaBeliController@index')->middleware('auth');
// Route::get('/loadAllUangMukaBeli', 'UangMukaBeliController@loadAll')->middleware('auth');
// Route::get('/loadAllAuthPO', 'UangMukaBeliController@loadAllPO')->middleware('auth');
// Route::get('/generateNomorUrutUangMukaBeli', 'UangMukaBeliController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiUangMukaBeli', 'UangMukaBeliController@generateNomorBukti')->middleware('auth');
// Route::post('/getTotalUangMukaBeli', 'UangMukaBeliController@getTotal')->middleware('auth');
// Route::post('/addUangMukaBeli', 'UangMukaBeliController@add')->middleware('auth');
// Route::post('/showUangMukaBeli', 'UangMukaBeliController@showUMB')->middleware('auth');
// Route::post('/editUangMukaBeli', 'UangMukaBeliController@edit')->middleware('auth');
// Route::post('/eraseUangMukaBeli', 'UangMukaBeliController@erase')->middleware('auth');
// Route::post('/authUangMukaBeli', 'UangMukaBeliController@changeAuth')->middleware('auth');
// Route::post('/batalUangMukaBeli', 'UangMukaBeliController@changeBatal')->middleware('auth');
//
// // Penerimaan
// Route::get('/penerimaan', 'PenerimaanController@index')->middleware('auth');
// Route::get('/loadOutstandingPO', 'PenerimaanController@loadAllPO')->middleware('auth');
// Route::get('/loadAllPenerimaan', 'PenerimaanController@loadAllPenerimaan')->middleware('auth');
// Route::post('/generateNomorUrutPenerimaan', 'PenerimaanController@generateNomorUrut')->middleware('auth');
// Route::post('/generateNomorBuktiPenerimaan', 'PenerimaanController@generateNomorBukti')->middleware('auth');
// Route::post('/addPenerimaan', 'PenerimaanController@add')->middleware('auth');
// Route::post('/showPenerimaan', 'PenerimaanController@showPenerimaan')->middleware('auth');
// Route::post('/showDetPenerimaan', 'PenerimaanController@showDetPenerimaan')->middleware('auth');
// Route::post('/editPenerimaan', 'PenerimaanController@edit')->middleware('auth');
// Route::post('/erasePenerimaan', 'PenerimaanController@erase')->middleware('auth');
// Route::post('/authPenerimaan', 'PenerimaanController@changeAuth')->middleware('auth');
// Route::post('/batalPenerimaan', 'PenerimaanController@changeBatal')->middleware('auth');
// Route::post('/getBatchPenerimaan', 'PenerimaanController@getBatch')->middleware('auth');
//
// // Stock Barang
// Route::get('/stockbrg', 'StockBarangController@index')->middleware('auth');
// Route::post('/getDetailStockBarang', 'StockBarangController@getDetail')->middleware('auth');
// Route::post('/getSaldoAwalStockBarang', 'StockBarangController@getSaldoAwal')->middleware('auth');
// Route::post('/getStockBarang', 'StockBarangController@getStockBarang')->middleware('auth');
// Route::post('/getJumlahStock', 'StockBarangController@getJumlahStock')->middleware('auth');
//
// // Inspeksi Penerimaan
// Route::get('/inspeksipenerimaan', 'InspeksiPenerimaanController@index')->middleware('auth');
//
// // Penerimaan ACC
// Route::get('/penerimaanacc', 'PenerimaanACCController@index')->middleware('auth');
// Route::post('/showBiayaKirimPenerimaan', 'PenerimaanACCController@showBiayaKirim')->middleware('auth');
// Route::post('/getDPPO', 'PenerimaanACCController@getDPPO')->middleware('auth');
// Route::post('/editPenerimaanACC', 'PenerimaanACCController@edit')->middleware('auth');
//
// // Invoice Pembelian
// Route::get('/invoicepembelian', 'InvoicePembelianController@index')->middleware('auth');
// Route::get('/loadAllInvoicePembelian', 'InvoicePembelianController@loadAll')->middleware('auth');
// Route::get('/loadAllOutstandingPenerimaan', 'InvoicePembelianController@loadAllOutstandingPenerimaan')->middleware('auth');
// Route::get('/generateNomorUrutInvoicePembelian', 'InvoicePembelianController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiInvoicePembelian', 'InvoicePembelianController@generateNomorBukti')->middleware('auth');
// Route::post('/loadPenerimaanByPO', 'InvoicePembelianController@loadPenerimaanByPO')->middleware('auth');
// Route::post('/setDetailInvoicePembelian', 'InvoicePembelianController@setDetail')->middleware('auth');
// Route::post('/addInvoicePembelian', 'InvoicePembelianController@add')->middleware('auth');
// Route::post('/authInvoicePembelian', 'InvoicePembelianController@changeAuth')->middleware('auth');
// Route::post('/batalInvoicePembelian', 'InvoicePembelianController@changeBatal')->middleware('auth');
// Route::post('/showInvoicePembelian', 'InvoicePembelianController@show')->middleware('auth');
// Route::post('/showDetInvoicePembelian', 'InvoicePembelianController@showDet')->middleware('auth');
// Route::post('/editInvoicePembelian', 'InvoicePembelianController@edit')->middleware('auth');
// Route::post('/eraseInvoicePembelian', 'InvoicePembelianController@erase')->middleware('auth');
//
// // Perintah Retur Pembelian
// Route::get('/perintahreturpembelian', 'PerintahReturPembelianController@index')->middleware('auth');
// Route::get('/loadAllPerintahReturPembelian', 'PerintahReturPembelianController@loadAll')->middleware('auth');
// Route::get('/loadAllPenerimaanInv', 'PerintahReturPembelianController@loadAllPenerimaan')->middleware('auth');
// Route::get('/generateNomorUrutPerintahReturPembelian', 'PerintahReturPembelianController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiPerintahReturPembelian', 'PerintahReturPembelianController@generateNomorBukti')->middleware('auth');
// Route::post('/addPerintahReturPembelian', 'PerintahReturPembelianController@add')->middleware('auth');
// Route::post('/authPerintahReturPembelian', 'PerintahReturPembelianController@changeAuth')->middleware('auth');
// Route::post('/batalPerintahReturPembelian', 'PerintahReturPembelianController@changeBatal')->middleware('auth');
// Route::post('/showPerintahReturPembelian', 'PerintahReturPembelianController@show')->middleware('auth');
// Route::post('/showDetPerintahReturPembelian', 'PerintahReturPembelianController@showDet')->middleware('auth');
// Route::post('/getDetailPenerimaanByIDKode', 'PerintahReturPembelianController@getDetailPenerimaanByIDKode')->middleware('auth');
// Route::post('/editPerintahReturPembelian', 'PerintahReturPembelianController@edit')->middleware('auth');
// Route::post('/erasePerintahReturPembelian', 'PerintahReturPembelianController@erase')->middleware('auth');
//
// // Retur Pembelian
// Route::get('/returpembelian', 'ReturPembelianController@index')->middleware('auth');
// Route::get('/loadAllReturPembelian', 'ReturPembelianController@loadAll')->middleware('auth');
// Route::get('/loadAllOutstandingRetur', 'ReturPembelianController@loadOutstandingRetur')->middleware('auth');
// Route::get('/loadAllOutstandingPilih', 'ReturPembelianController@loadOutstandingPilih')->middleware('auth');
// Route::post('/loadBarangReturBySupp', 'ReturPembelianController@loadBarangReturBySupp')->middleware('auth');
// Route::get('/generateNomorBuktiReturPembelian', 'ReturPembelianController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutReturPembelian', 'ReturPembelianController@generateNomorUrut')->middleware('auth');
// Route::post('/addReturPembelian', 'ReturPembelianController@add')->middleware('auth');
// Route::post('/showReturPembelian', 'ReturPembelianController@show')->middleware('auth');
// Route::post('/showDetReturPembelian', 'ReturPembelianController@showDet')->middleware('auth');
// Route::post('/editReturPembelian', 'ReturPembelianController@edit')->middleware('auth');
// Route::post('/eraseReturPembelian', 'ReturPembelianController@erase')->middleware('auth');
// Route::post('/getBatchReturPembelian', 'ReturPembelianController@getBatch')->middleware('auth');
// Route::post('/getBatchByBuktiReturPembelian', 'ReturPembelianController@getBatchByBukti')->middleware('auth');
//
// // Invoice Retur Pembelian
// Route::get('/invoicereturpembelian', 'InvoiceReturPembelianController@index')->middleware('auth');
// Route::get('/loadInvoiceReturPembelian', 'InvoiceReturPembelianController@loadAll')->middleware('auth');
// Route::post('/authInvoiceReturPembelian', 'InvoiceReturPembelianController@changeAuth')->middleware('auth');
// Route::post('/batalInvoiceReturPembelian', 'InvoiceReturPembelianController@changeBatal')->middleware('auth');
// Route::post('/editInvoiceReturPembelian', 'InvoiceReturPembelianController@edit')->middleware('auth');
//
// // Debet Note
// Route::get('/debetnote', 'DebetNoteController@index')->middleware('auth');
// Route::get('/loadAllDN', 'DebetNoteController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiDN', 'DebetNoteController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutDN', 'DebetNoteController@generateNomorUrut')->middleware('auth');
// Route::post('/getOutstandingDN', 'DebetNoteController@getOutstanding')->middleware('auth');
// Route::post('/getOutstandingNoBuktiDN', 'DebetNoteController@getOutstandingNoBukti')->middleware('auth');
// Route::post('/addDN', 'DebetNoteController@add')->middleware('auth');
// Route::post('/showDN', 'DebetNoteController@show')->middleware('auth');
// Route::post('/showDetDN', 'DebetNoteController@showDet')->middleware('auth');
// Route::post('/editDN', 'DebetNoteController@edit')->middleware('auth');
// Route::post('/eraseDN', 'DebetNoteController@erase')->middleware('auth');
// Route::post('/authDN', 'DebetNoteController@changeAuth')->middleware('auth');
// Route::post('/batalDN', 'DebetNoteController@changeBatal')->middleware('auth');
//
// // SO
// Route::get('/so', 'SOController@index')->middleware('auth');
// Route::get('/loadAllSO', 'SOController@loadAllSO')->middleware('auth');
// Route::get('/generateNomorUrutSO', 'SOController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiSO', 'SOController@generateNomorBukti')->middleware('auth');
// Route::post('/addSO', 'SOController@add')->middleware('auth');
// Route::post('/showSO', 'SOController@showSO')->middleware('auth');
// Route::post('/showSObyNomorBukti', 'SOController@showSObyNomorBukti')->middleware('auth');
// Route::post('/showDetSO', 'SOController@showDetSO')->middleware('auth');
// Route::post('/showDetSONoBukti', 'SOController@showDetSONoBukti')->middleware('auth');
// Route::post('/showRowDetSO', 'SOController@showRowDet')->middleware('auth');
// Route::post('/editSO', 'SOController@edit')->middleware('auth');
// Route::post('/eraseSO', 'SOController@erase')->middleware('auth');
// Route::post('/authSO', 'SOController@changeAuth')->middleware('auth');
// Route::post('/batalSO', 'SOController@changeBatal')->middleware('auth');
//
// // Closing SO
// Route::get('/closingso', 'ClosingSOController@index')->middleware('auth');
// Route::get('/loadAllOutstandingClosingSO', 'ClosingSOController@loadAllOutstandingSO')->middleware('auth');
// Route::get('/loadAllClosingSO', 'ClosingSOController@loadAllClosingSO')->middleware('auth');
// Route::post('/closeSO', 'ClosingSOController@close')->middleware('auth');
// Route::post('/batalcloseSO', 'ClosingSOController@batalclose')->middleware('auth');
//
// // Uang Muka Jual
// Route::get('/uangmukajual', 'UangMukaJualController@index')->middleware('auth');
// Route::get('/loadAllUangMukaJual', 'UangMukaJualController@loadAll')->middleware('auth');
// Route::get('/loadAllAuthSO', 'UangMukaJualController@loadAllSO')->middleware('auth');
// Route::get('/generateNomorUrutUangMukaJual', 'UangMukaJualController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiUangMukaJual', 'UangMukaJualController@generateNomorBukti')->middleware('auth');
// Route::post('/getTotalUangMukaJual', 'UangMukaJualController@getTotal')->middleware('auth');
// Route::post('/addUangMukaJual', 'UangMukaJualController@add')->middleware('auth');
// Route::post('/showUangMukaJual', 'UangMukaJualController@showUMJ')->middleware('auth');
// Route::post('/editUangMukaJual', 'UangMukaJualController@edit')->middleware('auth');
// Route::post('/eraseUangMukaJual', 'UangMukaJualController@erase')->middleware('auth');
// Route::post('/authUangMukaJual', 'UangMukaJualController@changeAuth')->middleware('auth');
// Route::post('/batalUangMukaJual', 'UangMukaJualController@changeBatal')->middleware('auth');
//
// // Surat Jalan
// Route::get('/suratjalan', 'SuratJalanController@index')->middleware('auth');
// Route::get('/getSOSJ', 'SuratJalanController@getSO')->middleware('auth');
// Route::get('/loadAllOutstandingSO', 'SuratJalanController@loadAllSO')->middleware('auth');
// Route::get('/loadAllSJ', 'SuratJalanController@loadAll')->middleware('auth');
// Route::post('/loadAllOutstandingSOByNoBukti', 'SuratJalanController@loadAllSOByNoBukti')->middleware('auth');
// Route::post('/getSumQtyBrg', 'SuratJalanController@getSumQty')->middleware('auth');
// Route::get('/generateNomorUrutSJ', 'SuratJalanController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiSJ', 'SuratJalanController@generateNomorBukti')->middleware('auth');
// Route::post('/addSJ', 'SuratJalanController@add')->middleware('auth');
// Route::post('/showSJ', 'SuratJalanController@show')->middleware('auth');
// Route::post('/showSJbyNoBukti', 'SuratJalanController@showByNoBukti')->middleware('auth');
// Route::post('/showDetailSJ', 'SuratJalanController@showDet')->middleware('auth');
// Route::post('/showDetailSJByNoBukti', 'SuratJalanController@showDetByNoBukti')->middleware('auth');
// Route::post('/authSJ', 'SuratJalanController@changeAuth')->middleware('auth');
// Route::post('/batalSJ', 'SuratJalanController@changeBatal')->middleware('auth');
// Route::post('/editSJ', 'SuratJalanController@edit')->middleware('auth');
// Route::post('/eraseSJ', 'SuratJalanController@erase')->middleware('auth');
//
// // Retur Surat jalan
// Route::get('/retursuratjalan', 'ReturSuratJalanController@index')->middleware('auth');
// Route::get('/loadAllRSJ', 'ReturSuratJalanController@loadAll')->middleware('auth');
// Route::post('/editRSJ', 'ReturSuratJalanController@edit')->middleware('auth');
//
// // Invoice Penjualan
// Route::get('/invoicepenjualan', 'InvoicePenjualanController@index')->middleware('auth');
// Route::get('/loadAllIP', 'InvoicePenjualanController@loadAll')->middleware('auth');
// Route::get('/loadAllISJ', 'InvoicePenjualanController@loadAllSJ')->middleware('auth');
// Route::get('/loadAllSO_Invoice', 'InvoicePenjualanController@loadAllSO_Invoice')->middleware('auth');
// Route::post('/getSJbySO', 'InvoicePenjualanController@getSJbySO')->middleware('auth');
// Route::post('/getDPSO', 'InvoicePenjualanController@getDPSO')->middleware('auth');
// Route::get('/generateNomorUrutInvoicePenjualan', 'InvoicePenjualanController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiInvoicePenjualan', 'InvoicePenjualanController@generateNomorBukti')->middleware('auth');
// Route::post('/addInvoicePenjualan', 'InvoicePenjualanController@add')->middleware('auth');
// Route::post('/showInvoicePenjualan', 'InvoicePenjualanController@show')->middleware('auth');
// Route::post('/showDetailInvoicePenjualan', 'InvoicePenjualanController@showDet')->middleware('auth');
// Route::post('/authInvoicePenjualan', 'InvoicePenjualanController@changeAuth')->middleware('auth');
// Route::post('/batalInvoicePenjualan', 'InvoicePenjualanController@changeBatal')->middleware('auth');
// Route::post('/editInvoicePenjualan', 'InvoicePenjualanController@edit')->middleware('auth');
// Route::post('/eraseInvoicePenjualan', 'InvoicePenjualanController@erase')->middleware('auth');
//
// // Invoice Jasa
// Route::get('/invoicejasa', 'InvoiceJasaController@index')->middleware('auth');
// Route::get('/loadAllIJ', 'InvoiceJasaController@loadAll')->middleware('auth');
// Route::get('/generateNomorUrutInvoiceJasa', 'InvoiceJasaController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiInvoiceJasa', 'InvoiceJasaController@generateNomorBukti')->middleware('auth');
// Route::post('/addInvoiceJasa', 'InvoiceJasaController@add')->middleware('auth');
// Route::post('/showInvoiceJasa', 'InvoiceJasaController@show')->middleware('auth');
// Route::post('/showDetailInvoiceJasa', 'InvoiceJasaController@showDet')->middleware('auth');
// Route::post('/authInvoiceJasa', 'InvoiceJasaController@changeAuth')->middleware('auth');
// Route::post('/batalInvoiceJasa', 'InvoiceJasaController@changeBatal')->middleware('auth');
// Route::post('/editInvoiceJasa', 'InvoiceJasaController@edit')->middleware('auth');
// Route::post('/eraseInvoiceJasa', 'InvoiceJasaController@erase')->middleware('auth');
//
// // Invoice Faktur Pajak
// Route::get('/invoicefakturpajak', 'InvoiceFakturPajakController@index')->middleware('auth');
// Route::post('/editInvoiceFakturPajak', 'InvoiceFakturPajakController@edit')->middleware('auth');
//
// // Perintah Retur Penjualan
// Route::get('/perintahreturpenjualan', 'PerintahReturPenjualanController@index')->middleware('auth');
// Route::get('/loadAllPerintahReturPenjualan', 'PerintahReturPenjualanController@loadAll')->middleware('auth');
// Route::get('/loadAllPenerimaanInvJual', 'PerintahReturPenjualanController@loadAllIP')->middleware('auth');
// Route::get('/generateNomorUrutPerintahReturPenjualan', 'PerintahReturPenjualanController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiPerintahReturPenjualan', 'PerintahReturPenjualanController@generateNomorBukti')->middleware('auth');
// Route::post('/getDetailInvoicePenjualanByIDKode', 'PerintahReturPenjualanController@getDetailInvoicePenjualanByIDKode')->middleware('auth');
// Route::post('/addPerintahReturPenjualan', 'PerintahReturPenjualanController@add')->middleware('auth');
// Route::post('/showPerintahReturPenjualan', 'PerintahReturPenjualanController@show')->middleware('auth');
// Route::post('/showDetailPerintahReturPenjualan', 'PerintahReturPenjualanController@showDet')->middleware('auth');
// Route::post('/authPerintahReturPenjualan', 'PerintahReturPenjualanController@changeAuth')->middleware('auth');
// Route::post('/batalPerintahReturPenjualan', 'PerintahReturPenjualanController@changeBatal')->middleware('auth');
// Route::post('/editPerintahReturPenjualan', 'PerintahReturPenjualanController@edit')->middleware('auth');
// Route::post('/erasePerintahReturPenjualan', 'PerintahReturPenjualanController@erase')->middleware('auth');
//
// // Retur Penjualan
// Route::get('/returpenjualan', 'ReturPenjualanController@index')->middleware('auth');
// Route::get('/loadAllReturPenjualan', 'ReturPenjualanController@loadAll')->middleware('auth');
// Route::get('/loadAllOutstandingReturPenjualan', 'ReturPenjualanController@loadOutstandingRetur')->middleware('auth');
// Route::get('/loadAllOutstandingPilihPenjualan', 'ReturPenjualanController@loadOutstandingPilih')->middleware('auth');
// Route::post('/loadBarangReturBySuppReturPenjualan', 'ReturPenjualanController@loadBarangReturBySupp')->middleware('auth');
// Route::get('/generateNomorBuktiReturPenjualan', 'ReturPenjualanController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutReturPenjualan', 'ReturPenjualanController@generateNomorUrut')->middleware('auth');
// Route::post('/addReturPenjualan', 'ReturPenjualanController@add')->middleware('auth');
// Route::post('/showReturPenjualan', 'ReturPenjualanController@show')->middleware('auth');
// Route::post('/showDetReturPenjualan', 'ReturPenjualanController@showDet')->middleware('auth');
// Route::post('/editReturPenjualan', 'ReturPenjualanController@edit')->middleware('auth');
// Route::post('/eraseReturPenjualan', 'ReturPenjualanController@erase')->middleware('auth');
// Route::post('/getBatchReturPenjualan', 'ReturPenjualanController@getBatch')->middleware('auth');
// Route::post('/getBatchByBuktiReturPenjualan', 'ReturPenjualanController@getBatchByBukti')->middleware('auth');
//
// // Invoice Retur Penjualan
// Route::get('/invoicereturpenjualan', 'InvoiceReturPenjualanController@index')->middleware('auth');
// Route::get('/loadInvoiceReturPenjualan', 'InvoiceReturPenjualanController@loadAll')->middleware('auth');
// Route::post('/authInvoiceReturPenjualan', 'InvoiceReturPenjualanController@changeAuth')->middleware('auth');
// Route::post('/batalInvoiceReturPenjualan', 'InvoiceReturPenjualanController@changeBatal')->middleware('auth');
// Route::post('/editInvoiceReturPenjualan', 'InvoiceReturPenjualanController@edit')->middleware('auth');
//
// // Kredit Note
// Route::get('/kreditnote', 'KreditNoteController@index')->middleware('auth');
// Route::get('/loadAllKN', 'KreditNoteController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiKN', 'KreditNoteController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutKN', 'KreditNoteController@generateNomorUrut')->middleware('auth');
// Route::post('/getOutstandingKN', 'KreditNoteController@getOutstanding')->middleware('auth');
// Route::post('/getOutstandingNoBuktiKN', 'KreditNoteController@getOutstandingNoBukti')->middleware('auth');
// Route::post('/addKN', 'KreditNoteController@add')->middleware('auth');
// Route::post('/showKN', 'KreditNoteController@show')->middleware('auth');
// Route::post('/showDetKN', 'KreditNoteController@showDet')->middleware('auth');
// Route::post('/editKN', 'KreditNoteController@edit')->middleware('auth');
// Route::post('/eraseKN', 'KreditNoteController@erase')->middleware('auth');
// Route::post('/authKN', 'KreditNoteController@changeAuth')->middleware('auth');
// Route::post('/batalKN', 'KreditNoteController@changeBatal')->middleware('auth');
//
// // BOM
// Route::get('/bom', 'BOMController@index')->middleware('auth');
// Route::get('/loadAllBOM', 'BOMController@loadAll')->middleware('auth');
// Route::post('/generateKodeBOM', 'BOMController@generateKode')->middleware('auth');
// Route::post('/addBOM', 'BOMController@add')->middleware('auth');
// Route::post('/showBOMBarang', 'BOMController@showBOMBarang')->middleware('auth');
// Route::post('/showProsesBarang', 'BOMController@showProsesBarang')->middleware('auth');
// Route::post('/showBahanBarang', 'BOMController@showBahanBarang')->middleware('auth');
// Route::post('/showBOM', 'BOMController@show')->middleware('auth');
// Route::post('/showDetBOM', 'BOMController@showDet')->middleware('auth');
// Route::post('/showDetBahanBOM', 'BOMController@showDetBahan')->middleware('auth');
// Route::post('/editBOM', 'BOMController@edit')->middleware('auth');
// Route::post('/eraseBOM', 'BOMController@erase')->middleware('auth');
// Route::post('/authBOM', 'BOMController@changeAuth')->middleware('auth');
// Route::post('/batalBOM', 'BOMController@changeBatal')->middleware('auth');
//
// // Permintaan Pemakaian
// Route::get('/permintaanpemakaian', 'PermintaanPemakaianController@index')->middleware('auth');
// Route::get('/loadAllPermintaanPemakaianBarang', 'PermintaanPemakaianController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiPermintaanPemakaian', 'PermintaanPemakaianController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPermintaanPemakaian', 'PermintaanPemakaianController@generateNomorUrut')->middleware('auth');
// Route::post('/addPermintaanPemakaian', 'PermintaanPemakaianController@add')->middleware('auth');
// Route::post('/showPermintaanPemakaian', 'PermintaanPemakaianController@showPermintaanPemakaian')->middleware('auth');
// Route::post('/showDetPermintaanPemakaian', 'PermintaanPemakaianController@showDetPermintaanPemakaian')->middleware('auth');
// Route::post('/showDetPermintaanPemakaianByNoBukti', 'PermintaanPemakaianController@showDetPermintaanPemakaianByNoBukti')->middleware('auth');
// Route::post('/editPermintaanPemakaian', 'PermintaanPemakaianController@edit')->middleware('auth');
// Route::post('/erasePermintaanPemakaian', 'PermintaanPemakaianController@erase')->middleware('auth');
// Route::post('/authPermintaanPemakaian', 'PermintaanPemakaianController@changeAuth')->middleware('auth');
// Route::post('/batalPermintaanPemakaian', 'PermintaanPemakaianController@changeBatal')->middleware('auth');
//
// // Pemakaian Barang
// Route::get('/pemakaianbarang', 'PemakaianBarangController@index')->middleware('auth');
// Route::get('/loadAllPemakaianBarang', 'PemakaianBarangController@loadAll')->middleware('auth');
// Route::get('/loadAllOutstandingPermintaan', 'PemakaianBarangController@loadAllOutstandingPermintaan')->middleware('auth');
// Route::get('/loadAllPermintaanPemakaian', 'PemakaianBarangController@loadPermintaan')->middleware('auth');
// Route::get('/generateNomorBuktiPemakaianBarang', 'PemakaianBarangController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPemakaianBarang', 'PemakaianBarangController@generateNomorUrut')->middleware('auth');
// Route::post('/addPemakaianBarang', 'PemakaianBarangController@add')->middleware('auth');
// Route::post('/showPemakaianBarang', 'PemakaianBarangController@show')->middleware('auth');
// Route::post('/showDetPemakaianBarang', 'PemakaianBarangController@showDet')->middleware('auth');
// Route::post('/editPemakaianBarang', 'PemakaianBarangController@edit')->middleware('auth');
// Route::post('/erasePemakaianBarang', 'PemakaianBarangController@erase')->middleware('auth');
// Route::post('/authPemakaianBarang', 'PemakaianBarangController@changeAuth')->middleware('auth');
// Route::post('/batalPemakaianBarang', 'PemakaianBarangController@changeBatal')->middleware('auth');
//
// // Opname Barang
// Route::get('/opnamebrg', 'OpnameBrgController@index')->middleware('auth');
// Route::get('/loadAllopnamebrg', 'OpnameBrgController@loadAll')->middleware('auth');
// Route::get('/loadAllOutPenerimaan', 'OpnameBrgController@loadAllOutPenerimaan')->middleware('auth');
// Route::get('/generateNomorBuktiopnamebrg', 'OpnameBrgController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutopnamebrg', 'OpnameBrgController@generateNomorUrut')->middleware('auth');
// Route::post('/addopnamebrg', 'OpnameBrgController@add')->middleware('auth');
// Route::post('/showOpnameBrg', 'OpnameBrgController@show')->middleware('auth');
// Route::post('/showStkBrg', 'OpnameBrgController@showDet')->middleware('auth');
// Route::post('/showDetOpnameBrg', 'OpnameBrgController@showDetOpnameBrg')->middleware('auth');
// Route::post('/showDetPenerimaanOpnameBrg', 'OpnameBrgController@showDetPenerimaanOpnameBrg')->middleware('auth');
// // Route::post('/showPilihRak', 'OpnameBrgController@showPilihRak')->middleware('auth');
// // Route::post('/showPilihLokasi', 'OpnameBrgController@showPilihLokasi')->middleware('auth');
// // Route::post('/showPilihBarcode', 'OpnameBrgController@showPilihBarcode')->middleware('auth');
// Route::post('/editopnamebrg', 'OpnameBrgController@edit')->middleware('auth');
// Route::post('/eraseOpnameBrg', 'OpnameBrgController@add')->middleware('auth');
// Route::post('/authOpnameBrg', 'OpnameBrgController@changeAuth')->middleware('auth');
// Route::post('/batalOpnameBrg', 'OpnameBrgController@changeBatal')->middleware('auth');
//
// // Alokasi Barang
// Route::get('/alokasibrg', 'AlokasiBarangController@index')->middleware('auth');
// Route::get('/loadAllAlokasiBrg', 'AlokasiBarangController@loadAll')->middleware('auth');
// Route::get('/loadAllOutPenerimaan', 'AlokasiBarangController@loadAllOutPpb')->middleware('auth');
// Route::get('/generateNomorBuktiAlokasiBrg', 'AlokasiBarangController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutAlokasiBrg', 'AlokasiBarangController@generateNomorUrut')->middleware('auth');
// Route::post('/addAlokasiBrg', 'AlokasiBarangController@add')->middleware('auth');
// Route::post('/showAlokasiBrg', 'AlokasiBarangController@show')->middleware('auth');
// Route::post('/showDetOutPenerimaan', 'AlokasiBarangController@showDet')->middleware('auth');
// Route::post('/showDetAlokasiBrg', 'AlokasiBarangController@showDetOutBrg')->middleware('auth');
// Route::post('/showDetailBrgLoc', 'AlokasiBarangController@showDetailBrgLoc')->middleware('auth');
// Route::post('/showDetOutTransaksiOutBrg', 'AlokasiBarangController@showDetOutTransaksiOutBrg')->middleware('auth');
// Route::post('/editalokasibrg', 'AlokasiBarangController@edit')->middleware('auth');
// Route::post('/eraseAlokasiBrg', 'AlokasiBarangController@add')->middleware('auth');
// Route::post('/authAlokasiBrg', 'AlokasiBarangController@changeAuth')->middleware('auth');
// Route::post('/batalAlokasiBrg', 'AlokasiBarangController@changeBatal')->middleware('auth');
// Route::post('/showPilihRak', 'AlokasiBarangController@showPilihRak')->middleware('auth');
// Route::post('/showPilihLokasi', 'AlokasiBarangController@showPilihLokasi')->middleware('auth');
// Route::post('/showPilihBarcode', 'AlokasiBarangController@showPilihBarcode')->middleware('auth');
//
// // Permintaan Out Barang
// Route::get('/permintaanpersiapanso', 'PermintaanOutBarangController@indexso')->middleware('auth');
// Route::get('/permintaanpersiapan', 'PermintaanOutBarangController@index')->middleware('auth');
// Route::get('/loadAllPpBrg', 'PermintaanOutBarangController@loadAll')->middleware('auth');
// Route::get('/loadAllOutTransaksi', 'PermintaanOutBarangController@loadAllOutTransaksi')->middleware('auth');
// Route::post('/generateNomorBuktiPpBrg', 'PermintaanOutBarangController@generateNomorBukti')->middleware('auth');
// Route::post('/generateNomorUrutPpBrg', 'PermintaanOutBarangController@generateNomorUrut')->middleware('auth');
// Route::post('/addPpBrg', 'PermintaanOutBarangController@add')->middleware('auth');
// Route::post('/showPpBrg', 'PermintaanOutBarangController@show')->middleware('auth');
// Route::post('/showDetOutTransaksi', 'PermintaanOutBarangController@showDet')->middleware('auth');
// Route::post('/showDetPpBrg', 'PermintaanOutBarangController@showDetPpBrg')->middleware('auth');
// Route::post('/showDetOutTransaksiPpBrg', 'PermintaanOutBarangController@showDetOutTransaksiPpBrg')->middleware('auth');
// Route::post('/editPpBrg', 'PermintaanOutBarangController@edit')->middleware('auth');
// Route::post('/erasePpBrg', 'PermintaanOutBarangController@add')->middleware('auth');
// Route::post('/authPpBrg', 'PermintaanOutBarangController@changeAuth')->middleware('auth');
// Route::post('/batalPpBrg', 'PermintaanOutBarangController@changeBatal')->middleware('auth');
// Route::post('/showDetOutTransaksiSo', 'PermintaanOutBarangController@showDetSo')->middleware('auth');
//
//
//
// // Out Barang
// Route::get('/outbrg', 'OutBarangController@index')->middleware('auth');
// Route::get('/loadAllOutBrg', 'OutBarangController@loadAll')->middleware('auth');
// Route::get('/loadAllOutPpb', 'OutBarangController@loadAllOutPpb')->middleware('auth');
// Route::get('/generateNomorBuktiOutBrg', 'OutBarangController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutOutBrg', 'OutBarangController@generateNomorUrut')->middleware('auth');
// Route::post('/addOutBrg', 'OutBarangController@add')->middleware('auth');
// Route::post('/showOutBrg', 'OutBarangController@show')->middleware('auth');
// Route::post('/showDetOutPpb', 'OutBarangController@showDet')->middleware('auth');
// Route::post('/showDetOutBrg', 'OutBarangController@showDetOutBrg')->middleware('auth');
// Route::post('/showDetStkBrg', 'OutBarangController@showDetStkBrg')->middleware('auth');
// Route::post('/showDetOutTransaksiOutBrg', 'OutBarangController@showDetOutTransaksiOutBrg')->middleware('auth');
// // Route::post('/showPilihRak', 'OutBarangController@showPilihRak')->middleware('auth');
// // Route::post('/showPilihLokasi', 'OutBarangController@showPilihLokasi')->middleware('auth');
// // Route::post('/showPilihBarcode', 'OutBarangController@showPilihBarcode')->middleware('auth');
// Route::post('/editOutBrg', 'OutBarangController@edit')->middleware('auth');
// Route::post('/eraseOutBrg', 'OutBarangController@add')->middleware('auth');
// Route::post('/authOutBrg', 'OutBarangController@changeAuth')->middleware('auth');
// Route::post('/batalOutBrg', 'OutBarangController@changeBatal')->middleware('auth');
//
// // Surat Jalan Barang
// Route::get('/suratjalanbrg', 'SuratJalanBrgController@index')->middleware('auth');
// Route::get('/getSOSJ', 'SuratJalanBrgController@getSO')->middleware('auth');
// Route::get('/loadAllOutstandingSO', 'SuratJalanBrgController@loadAllSO')->middleware('auth');
// Route::get('/loadAllSJ', 'SuratJalanBrgController@loadAll')->middleware('auth');
// Route::post('/loadAllOutstandingSOByNoBukti', 'SuratJalanBrgController@loadAllSOByNoBukti')->middleware('auth');
// Route::post('/getSumQtyBrg', 'SuratJalanBrgController@getSumQty')->middleware('auth');
// Route::get('/generateNomorUrutSJ', 'SuratJalanBrgController@generateNomorUrut')->middleware('auth');
// Route::get('/generateNomorBuktiSJ', 'SuratJalanBrgController@generateNomorBukti')->middleware('auth');
// Route::post('/addSJ', 'SuratJalanBrgController@add')->middleware('auth');
// Route::post('/showSJ', 'SuratJalanBrgController@show')->middleware('auth');
// Route::post('/showSJbyNoBukti', 'SuratJalanBrgController@showByNoBukti')->middleware('auth');
// Route::post('/showDetailSJ', 'SuratJalanBrgController@showDet')->middleware('auth');
// Route::post('/showDetailSJByNoBukti', 'SuratJalanBrgController@showDetByNoBukti')->middleware('auth');
// Route::post('/authSJ', 'SuratJalanBrgController@changeAuth')->middleware('auth');
// Route::post('/batalSJ', 'SuratJalanBrgController@changeBatal')->middleware('auth');
// Route::post('/editSJ', 'SuratJalanBrgController@edit')->middleware('auth');
// Route::post('/eraseSJ', 'SuratJalanBrgController@erase')->middleware('auth');
//
// // Pemakaian Barang ACC
// Route::get('/pemakaianbarangacc', 'PemakaianBarangACCController@index')->middleware('auth');
// Route::post('/editPemakaianBarangACC', 'PemakaianBarangACCController@edit')->middleware('auth');
//
// // Ubah Kemasan
// Route::get('/ubahkemasanbarang', 'UbahKemasanController@index')->middleware('auth');
// Route::get('/loadAllUbahKemasan', 'UbahKemasanController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiUbahKemasan', 'UbahKemasanController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutUbahKemasan', 'UbahKemasanController@generateNomorUrut')->middleware('auth');
// Route::post('/addUbahKemasan', 'UbahKemasanController@add')->middleware('auth');
// Route::post('/showUbahKemasan', 'UbahKemasanController@show')->middleware('auth');
// Route::post('/showDetUbahKemasan', 'UbahKemasanController@showDet')->middleware('auth');
// Route::post('/editUbahKemasan', 'UbahKemasanController@edit')->middleware('auth');
// Route::post('/eraseUbahKemasan', 'UbahKemasanController@erase')->middleware('auth');
// Route::post('/authUbahKemasan', 'UbahKemasanController@changeAuth')->middleware('auth');
// Route::post('/batalUbahKemasan', 'UbahKemasanController@changeBatal')->middleware('auth');
//
// // Perintah Opname
// Route::get('/perintahopname', 'PerintahOpnameController@index')->middleware('auth');
// Route::get('/loadAllPerintahOpname', 'PerintahOpnameController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiPerintahOpname', 'PerintahOpnameController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPerintahOpname', 'PerintahOpnameController@generateNomorUrut')->middleware('auth');
// Route::post('/getBarangGudang', 'PerintahOpnameController@getBarangGudang')->middleware('auth');
// Route::post('/addPerintahOpname', 'PerintahOpnameController@add')->middleware('auth');
// Route::post('/showPerintahOpname', 'PerintahOpnameController@show')->middleware('auth');
// Route::post('/showDetPerintahOpname', 'PerintahOpnameController@showDet')->middleware('auth');
// Route::post('/showDetPerintahOpnameNoBukti', 'PerintahOpnameController@showDetNoBukti')->middleware('auth');
// Route::post('/editPerintahOpname', 'PerintahOpnameController@edit')->middleware('auth');
// Route::post('/erasePerintahOpname', 'PerintahOpnameController@erase')->middleware('auth');
// Route::post('/authPerintahOpname', 'PerintahOpnameController@changeAuth')->middleware('auth');
// Route::post('/batalPerintahOpname', 'PerintahOpnameController@changeBatal')->middleware('auth');
//
// // Berita Acara Opname
// Route::get('/beritaacaraopname', 'BeritaAcaraOpnameController@index')->middleware('auth');
// Route::get('/loadAllBeritaAcaraOpname', 'BeritaAcaraOpnameController@loadAll')->middleware('auth');
// Route::get('/loadOutstandingPerintahOpname', 'BeritaAcaraOpnameController@loadOutstandingPerintah')->middleware('auth');
// Route::get('/generateNomorBuktiBeritaAcaraOpname', 'BeritaAcaraOpnameController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutBeritaAcaraOpname', 'BeritaAcaraOpnameController@generateNomorUrut')->middleware('auth');
// Route::post('/addBeritaAcaraOpname', 'BeritaAcaraOpnameController@add')->middleware('auth');
// Route::post('/showBeritaAcaraOpname', 'BeritaAcaraOpnameController@show')->middleware('auth');
// Route::post('/showDetBeritaAcaraOpname', 'BeritaAcaraOpnameController@showDet')->middleware('auth');
// Route::post('/editBeritaAcaraOpname', 'BeritaAcaraOpnameController@edit')->middleware('auth');
// Route::post('/eraseBeritaAcaraOpname', 'BeritaAcaraOpnameController@erase')->middleware('auth');
// Route::post('/authBeritaAcaraOpname', 'BeritaAcaraOpnameController@changeAuth')->middleware('auth');
// Route::post('/batalBeritaAcaraOpname', 'BeritaAcaraOpnameController@changeBatal')->middleware('auth');
//
// // Opname Barang
// Route::get('/opnamebarang', 'OpnameBarangController@index')->middleware('auth');
// Route::get('/loadAllOpnameBarang', 'OpnameBarangController@loadAll')->middleware('auth');
// Route::post('/showOpnameBarang', 'OpnameBarangController@show')->middleware('auth');
// Route::post('/showDetOpnameBarang', 'OpnameBarangController@showDet')->middleware('auth');
//
// // Koreksi Stock
// Route::get('/koreksistock', 'KoreksiStockController@index')->middleware('auth');
// Route::get('/loadAllKoreksiStock', 'KoreksiStockController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiKoreksiStock', 'KoreksiStockController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutKoreksiStock', 'KoreksiStockController@generateNomorUrut')->middleware('auth');
// Route::post('/addKoreksiStock', 'KoreksiStockController@add')->middleware('auth');
// Route::post('/showKoreksiStock', 'KoreksiStockController@show')->middleware('auth');
// Route::post('/showDetKoreksiStock', 'KoreksiStockController@showDet')->middleware('auth');
// Route::post('/editKoreksiStock', 'KoreksiStockController@edit')->middleware('auth');
// Route::post('/eraseKoreksiStock', 'KoreksiStockController@erase')->middleware('auth');
// Route::post('/authKoreksiStock', 'KoreksiStockController@changeAuth')->middleware('auth');
// Route::post('/batalKoreksiStock', 'KoreksiStockController@changeBatal')->middleware('auth');
//
// // Permintaan Transfer Barang
// Route::get('/permintaantransferbarang', 'PermintaanTransferBarangController@index')->middleware('auth');
// Route::get('/loadAllPermintaanTransferBarang', 'PermintaanTransferBarangController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiPermintaanTransferBarang', 'PermintaanTransferBarangController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPermintaanTransferBarang', 'PermintaanTransferBarangController@generateNomorUrut')->middleware('auth');
// Route::post('/addPermintaanTransferBarang', 'PermintaanTransferBarangController@add')->middleware('auth');
// Route::post('/showPermintaanTransferBarang', 'PermintaanTransferBarangController@show')->middleware('auth');
// Route::post('/showDetPermintaanTransferBarang', 'PermintaanTransferBarangController@showDet')->middleware('auth');
// Route::post('/editPermintaanTransferBarang', 'PermintaanTransferBarangController@edit')->middleware('auth');
// Route::post('/erasePermintaanTransferBarang', 'PermintaanTransferBarangController@erase')->middleware('auth');
// Route::post('/authPermintaanTransferBarang', 'PermintaanTransferBarangController@changeAuth')->middleware('auth');
// Route::post('/batalPermintaanTransferBarang', 'PermintaanTransferBarangController@changeBatal')->middleware('auth');
//
// // Transfer Barang
// Route::get('/transferbarang', 'TransferBarangController@index')->middleware('auth');
// Route::get('/loadAllTransferBarang', 'TransferBarangController@loadAll')->middleware('auth');
// Route::get('/loadAllOutstandingPermintaanTransfer', 'TransferBarangController@loadAllOutstandingPermintaan')->middleware('auth');
// Route::get('/loadAllPermintaan', 'TransferBarangController@loadPermintaan')->middleware('auth');
// Route::get('/loadAllOutstandingTransferBarang', 'TransferBarangController@loadAllOutstandingTransfer')->middleware('auth');
// Route::get('/generateNomorBuktiTransferBarang', 'TransferBarangController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutTransferBarang', 'TransferBarangController@generateNomorUrut')->middleware('auth');
// Route::post('/addTransferBarang', 'TransferBarangController@add')->middleware('auth');
// Route::post('/showTransferBarang', 'TransferBarangController@show')->middleware('auth');
// Route::post('/showTransferBarangNoBukti', 'TransferBarangController@showNoBukti')->middleware('auth');
// Route::post('/showDetTransferBarang', 'TransferBarangController@showDet')->middleware('auth');
// Route::post('/showDetTransferBarangNoBukti', 'TransferBarangController@showDetNoBukti')->middleware('auth');
// Route::post('/editTransferBarang', 'TransferBarangController@edit')->middleware('auth');
// Route::post('/eraseTransferBarang', 'TransferBarangController@erase')->middleware('auth');
// Route::post('/authTransferBarang', 'TransferBarangController@changeAuth')->middleware('auth');
// Route::post('/batalTransferBarang', 'TransferBarangController@changeBatal')->middleware('auth');
//
// // Penerimaan Transfer Barang
// Route::get('/penerimaantransferbarang', 'PenerimaanTransferBarangController@index')->middleware('auth');
// Route::get('/loadAllPenerimaanTransferBarang', 'PenerimaanTransferBarangController@loadAll')->middleware('auth');
// Route::get('/loadAllOutstandingTransferBarang2', 'PenerimaanTransferBarangController@loadAllOutstandingTransfer')->middleware('auth');
// Route::get('/generateNomorBuktiPenerimaanTransferBarang', 'PenerimaanTransferBarangController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPenerimaanTransferBarang', 'PenerimaanTransferBarangController@generateNomorUrut')->middleware('auth');
// Route::post('/addPenerimaanTransferBarang', 'PenerimaanTransferBarangController@add')->middleware('auth');
// Route::post('/showPenerimaanTransferBarang', 'PenerimaanTransferBarangController@show')->middleware('auth');
// Route::post('/showDetPenerimaanTransferBarang', 'PenerimaanTransferBarangController@showDet')->middleware('auth');
// Route::post('/editPenerimaanTransferBarang', 'PenerimaanTransferBarangController@edit')->middleware('auth');
// Route::post('/erasePenerimaanTransferBarang', 'PenerimaanTransferBarangController@erase')->middleware('auth');
// Route::post('/authPenerimaanTransferBarang', 'PenerimaanTransferBarangController@changeAuth')->middleware('auth');
// Route::post('/batalPenerimaanTransferBarang', 'PenerimaanTransferBarangController@changeBatal')->middleware('auth');
//
// Hitung Stok
Route::get('/hitungstok', [HitungStokController::class, 'index'])->middleware('auth');
Route::post('/getBarangGudangStok', [HitungStokController::class, 'getBarangGudangStok'])->middleware('auth');
Route::post('/proseshitungstok', [HitungStokController::class, 'hitungStok'])->middleware('auth');
Route::post('/proseshitungstok2', [HitungStokController::class, 'hitungStok2'])->middleware('auth');
//
// // Hitung HPP Bulanan
// Route::get('/hitunghppbulanan', 'HPPController@index1')->middleware('auth');
// Route::post('/getBarangGudang', 'HPPController@getBarangGudang')->middleware('auth');
// Route::post('/proseshitunghppbulanan', 'HPPController@hitungHPPBulanan')->middleware('auth');
// Route::post('/proseshitunghppbulanan2', 'HPPController@hitungHPPBulanan2')->middleware('auth');
//
// // Hitung HPP Bergerak
// Route::get('/hitunghppbergerak', 'HPPController@index2')->middleware('auth');
// Route::post('/getBarangGudang2', 'HPPController@getBarangGudang2')->middleware('auth');
// Route::post('/proseshitunghppbergerak', 'HPPController@hitungHPPBergerak')->middleware('auth');
// Route::post('/proseshitunghppbergerak2', 'HPPController@hitungHPPBergerak2')->middleware('auth');
//
// // Tutup Buku
// Route::get('/tutupbuku', 'TutupBukuController@index')->middleware('auth');
// Route::post('/prosesAktiva', 'TutupBukuController@prosesAktiva')->middleware('auth');
// Route::post('/getProsesAktiva', 'TutupBukuController@getProsesAktiva')->middleware('auth');
// Route::post('/prosesNeraca', 'TutupBukuController@prosesNeraca')->middleware('auth');
// Route::get('/getProsesNeraca', 'TutupBukuController@getProsesNeraca')->middleware('auth');
// Route::post('/prosesLRHPP', 'TutupBukuController@prosesLRHPP')->middleware('auth');
// Route::get('/getProsesLRHPP', 'TutupBukuController@getProsesLRHPP')->middleware('auth');
//
// // Posting Ulang
// Route::get('/postingulang', 'PostingUlangController@index')->middleware('auth');
// Route::post('/postingulang', 'PostingUlangController@postingUlang')->middleware('auth');
// Route::post('/getTransPosting', 'PostingUlangController@getTransaksi')->middleware('auth');
//
// // Jurnal
// Route::get('/jurnal', 'JurnalController@index')->middleware('auth');
// Route::post('/loadAllJurnal', 'JurnalController@loadAll')->middleware('auth');
//
// // Aktivitas Pemakai
// Route::get('/aktivitaspemakai', 'AktivitasPemakaiController@index')->middleware('auth');
// Route::post('/loadAllAktivitas', 'AktivitasPemakaiController@loadAll')->middleware('auth');
//
// // Transaksi diedit
// Route::get('/transaksidiedit', 'TransaksiEditController@index')->middleware('auth');
// Route::get('/loadAllTransEdit', 'TransaksiEditController@loadAll')->middleware('auth');
//
// // Report
// Route::post('/cetakLaporan', 'ReportController@cetakLaporan')->middleware('auth');
// Route::post('/exportPDF', 'ReportController@PDF')->middleware('auth');
// Route::post('/exportEXCEL', 'ReportController@Excel')->middleware('auth');
//
// // // Laporan Valas
// // Route::get('/laporanvalas', 'LaporanValasController@index')->middleware('auth');
// // Route::post('/loadAllLValas', 'LaporanValasController@loadAll')->middleware('auth');
// //
// // // Laporan Devisi
// // Route::get('/laporandevisi', 'LaporanDevisiController@index')->middleware('auth');
// // Route::post('/loadAllLDevisi', 'LaporanDevisiController@loadAll')->middleware('auth');
//
// // Laporan Perkiraan
// Route::get('/laporanperkiraan', 'LaporanPerkiraanController@index')->middleware('auth');
// Route::post('/loadAllLPerkiraan', 'LaporanPerkiraanController@loadAll')->middleware('auth');
//
// // Laporan Aktiva
// Route::get('/laporanmasteraktiva', 'LaporanMasterAktivaController@index')->middleware('auth');
// Route::post('/loadAllLAktiva', 'LaporanMasterAktivaController@loadAll')->middleware('auth');
//
// // Laporan Supplier
// Route::get('/laporansupplier', 'LaporanSupplierController@index')->middleware('auth');
// Route::post('/loadAllLSupplier', 'LaporanSupplierController@loadAll')->middleware('auth');
//
// // Laporan Customer
// Route::get('/laporancustomer', 'LaporanCustomerController@index')->middleware('auth');
// Route::post('/loadAllLCustomer', 'LaporanCustomerController@loadAll')->middleware('auth');
//
// // Laporan Gudang
// Route::get('/laporangudang', 'LaporanGudangController@index')->middleware('auth');
// Route::post('/loadAllLGudang', 'LaporanGudangController@loadAll')->middleware('auth');
//
// // Laporan Kategori
// Route::get('/laporankategori', 'LaporanKategoriController@index')->middleware('auth');
// Route::post('/loadAllLKategori', 'LaporanKategoriController@loadAll')->middleware('auth');
//
// // Laporan Grup Baku
// Route::get('/laporangrupbaku', 'LaporanGrupBakuController@index')->middleware('auth');
// Route::post('/loadAllLGrupBahanBaku', 'LaporanGrupBakuController@loadAll')->middleware('auth');
//
// // Laporan Model Bahan Baku
// Route::get('/laporanmodel', 'LaporanModelController@index')->middleware('auth');
// Route::post('/loadAllLModelBahanBaku', 'LaporanModelController@loadAll')->middleware('auth');
//
// // Laporan Merk Bahan Baku
// Route::get('/laporanmerkbaku', 'LaporanMerkBakuController@index')->middleware('auth');
// Route::post('/loadAllLMerkBahanBaku', 'LaporanMerkBakuController@loadAll')->middleware('auth');
//
// // Laporan Warna Bahan Baku
// Route::get('/laporanwarnabaku', 'LaporanWarnaBakuController@index')->middleware('auth');
// Route::post('/loadAllLWarnaBahanBaku', 'LaporanWarnaBakuController@loadAll')->middleware('auth');
//
// // Laporan Ukuran Bahan Baku
// Route::get('/laporanukuran', 'LaporanUkuranBakuController@index')->middleware('auth');
// Route::post('/loadAllLUkuranBahanBaku', 'LaporanUkuranBakuController@loadAll')->middleware('auth');
//
// // Laporan Grup Barang Jadi
// Route::get('/laporangrupjadi', 'LaporanGrupJadiController@index')->middleware('auth');
// Route::post('/loadAllLGrupBarangJadi', 'LaporanGrupJadiController@loadAll')->middleware('auth');
//
// // Laporan Subgrup Barang Jadi
// Route::get('/laporansubgrupjadi', 'LaporanSubgrupJadiController@index')->middleware('auth');
// Route::post('/loadAllLSubgrupBarangJadi', 'LaporanSubgrupJadiController@loadAll')->middleware('auth');
//
// // Laporan Warna Barang Jadi
// Route::get('/laporanwarnajadi', 'LaporanWarnaJadiController@index')->middleware('auth');
// Route::post('/loadAllLWarnaBarangJadi', 'LaporanWarnaJadiController@loadAll')->middleware('auth');
//
// // Laporan Merk Barang Jadi
// Route::get('/laporanmerkjadi', 'LaporanMerkJadiController@index')->middleware('auth');
// Route::post('/loadAllLMerkBarangJadi', 'LaporanMerkJadiController@loadAll')->middleware('auth');
//
// // Laporan Dimensi Barang Jadi
// Route::get('/laporandimensi', 'LaporanDimensiJadiController@index')->middleware('auth');
// Route::post('/loadAllLDimensiBarangJadi', 'LaporanDimensiJadiController@loadAll')->middleware('auth');
//
// // Laporan Bahan Baku
// Route::get('/laporanbahanbaku', 'LaporanBahanBakuController@index')->middleware('auth');
// Route::post('/loadAllLBahanBaku', 'LaporanBahanBakuController@loadAll')->middleware('auth');
//
// // Laporan Barang Jadi
// Route::get('/laporanbarangjadi', 'LaporanBarangJadiController@index')->middleware('auth');
// Route::post('/loadAllLBarangJadi', 'LaporanBarangJadiController@loadAll')->middleware('auth');
//
// // Laporan Barang Sparepart
// Route::get('/laporanbahansparepart', 'LaporanBahanSparepartController@index')->middleware('auth');
// Route::post('/loadAllLBahanSparepart', 'LaporanBahanSparepartController@loadAll')->middleware('auth');
//
// // Laporan Jasa
// Route::get('/laporanjasa', 'LaporanJasaController@index')->middleware('auth');
// Route::post('/loadAllLJasa', 'LaporanJasaController@loadAll')->middleware('auth');
//
// // Laporan Area
// Route::get('/laporanarea', 'LaporanAreaController@index')->middleware('auth');
// Route::post('/loadAllLArea', 'LaporanAreaController@loadAll')->middleware('auth');
//
// // Laporan Kota
// Route::get('/laporankota', 'LaporanKotaController@index')->middleware('auth');
// Route::post('/loadAllLKota', 'LaporanKotaController@loadAll')->middleware('auth');
//
// // Laporan JenisCustomer
// Route::get('/laporanjeniscustomer', 'LaporanJenisCustomerController@index')->middleware('auth');
// Route::post('/loadAllLJenisCustomer', 'LaporanJenisCustomerController@loadAll')->middleware('auth');
//
// // Laporan Departemen
// Route::get('/laporandepartemen', 'LaporanDepartemenController@index')->middleware('auth');
// Route::post('/loadAllLDepartemen', 'LaporanDepartemenController@loadAll')->middleware('auth');
//
// // Laporan Jabatan
// Route::get('/laporanjabatan', 'LaporanJabatanController@index')->middleware('auth');
// Route::post('/loadAllLJabatan', 'LaporanJabatanController@loadAll')->middleware('auth');
//
// // Laporan Sales
// Route::get('/laporansales', 'LaporanSalesController@index')->middleware('auth');
// Route::post('/loadAllLSales', 'LaporanSalesController@loadAll')->middleware('auth');
//
// // Laporan Biaya
// // Route::get('/laporanbiaya', 'LaporanBiayaController@index')->middleware('auth');
// // Route::post('/loadAllLBiaya', 'LaporanBiayaController@loadAll')->middleware('auth');
//
// // Laporan Sopir
// Route::get('/laporansopir', 'LaporanSopirController@index')->middleware('auth');
// Route::post('/loadAllLSopir', 'LaporanSopirController@loadAll')->middleware('auth');
//
// // Laporan PR
// Route::get('/laporanpr', 'LaporanPRController@index')->middleware('auth');
// Route::post('/loadAllLPR', 'LaporanPRController@loadAll')->middleware('auth');
//
// // Laporan PO
// Route::get('/laporanpo', 'LaporanPOController@index')->middleware('auth');
// Route::post('/loadAllLPO', 'LaporanPOController@loadAll')->middleware('auth');
//
// // Laporan Uang Muka Beli
// Route::get('/laporanuangmukabeli', 'LaporanUangMukaBeliController@index')->middleware('auth');
// Route::post('/loadAllLUangMukaBeli', 'LaporanUangMukaBeliController@loadAll')->middleware('auth');
//
// // Laporan Penerimaan
// Route::get('/laporanpenerimaan', 'LaporanPenerimaanController@index')->middleware('auth');
// Route::post('/loadAllLPenerimaan', 'LaporanPenerimaanController@loadAll')->middleware('auth');
//
// // Laporan PenerimaanACC
// Route::get('/laporanpenerimaanacc', 'LaporanPenerimaanACCController@index')->middleware('auth');
// Route::post('/loadAllLPenerimaanACC', 'LaporanPenerimaanACCController@loadAll')->middleware('auth');
//
// // Laporan Invoice Pembelian
// Route::get('/laporaninvoicepembelian', 'LaporanInvoicePembelianController@index')->middleware('auth');
// Route::post('/loadAllLInvoicePembelian', 'LaporanInvoicePembelianController@loadAll')->middleware('auth');
//
// // Laporan Perintah Retur Pembelian
// Route::get('/laporanperintahreturpembelian', 'LaporanPerintahReturPembelianController@index')->middleware('auth');
// Route::post('/loadAllLPerintahReturPembelian', 'LaporanPerintahReturPembelianController@loadAll')->middleware('auth');
//
// // Laporan Retur Pembelian
// Route::get('/laporanreturpembelian', 'LaporanReturPembelianController@index')->middleware('auth');
// Route::post('/loadAllLReturPembelian', 'LaporanReturPembelianController@loadAll')->middleware('auth');
//
// // Laporan Invoice Retur Pembelian
// Route::get('/laporaninvoicereturpembelian', 'LaporanInvoiceReturPembelianController@index')->middleware('auth');
// Route::post('/loadAllLInvoiceReturPembelian', 'LaporanInvoiceReturPembelianController@loadAll')->middleware('auth');
//
// // Laporan Debet Note
// Route::get('/laporandebetnote', 'LaporanDebetNoteController@index')->middleware('auth');
// Route::post('/loadAllLDebetNote', 'LaporanDebetNoteController@loadAll')->middleware('auth');
//
// // Laporan SO
// Route::get('/laporanso', 'LaporanSOController@index')->middleware('auth');
// Route::post('/loadAllLSO', 'LaporanSOController@loadAll')->middleware('auth');
//
// // Laporan Uang Muka Jual
// Route::get('/laporanuangmukajual', 'LaporanUangMukaJualController@index')->middleware('auth');
// Route::post('/loadAllLUangMukaJual', 'LaporanUangMukaJualController@loadAll')->middleware('auth');
//
// // Laporan Surat Jalan
// Route::get('/laporansuratjalan', 'LaporanSuratJalanController@index')->middleware('auth');
// Route::post('/loadAllLSuratJalan', 'LaporanSuratJalanController@loadAll')->middleware('auth');
//
// // Laporan Invoice Penjualan
// Route::get('/laporaninvoicepenjualan', 'LaporanInvoicePenjualanController@index')->middleware('auth');
// Route::post('/loadAllLInvoicePenjualan', 'LaporanInvoicePenjualanController@loadAll')->middleware('auth');
//
// // Laporan Laba Kotor
// Route::get('/laporanlabakotor', 'LaporanLabaKotorController@index')->middleware('auth');
// Route::post('/loadAllLLabaKotor', 'LaporanLabaKotorController@loadAll')->middleware('auth');
//
// // Laporan Invoice Jasa
// Route::get('/laporaninvoicejasa', 'LaporanInvoiceJasaController@index')->middleware('auth');
// Route::post('/loadAllLInvoiceJasa', 'LaporanInvoiceJasaController@loadAll')->middleware('auth');
//
// // Laporan Perintah Retur Penjualan
// Route::get('/laporanperintahreturpenjualan', 'LaporanPerintahReturPenjualanController@index')->middleware('auth');
// Route::post('/loadAllLPerintahReturPenjualan', 'LaporanPerintahReturPenjualanController@loadAll')->middleware('auth');
//
// // Laporan Retur Penjualan
// Route::get('/laporanreturpenjualan', 'LaporanReturPenjualanController@index')->middleware('auth');
// Route::post('/loadAllLReturPenjualan', 'LaporanReturPenjualanController@loadAll')->middleware('auth');
//
// // Laporan Invoice Retur Penjualan
// Route::get('/laporaninvoicereturpenjualan', 'LaporanInvoiceReturPenjualanController@index')->middleware('auth');
// Route::post('/loadAllLInvoiceReturPenjualan', 'LaporanInvoiceReturPenjualanController@loadAll')->middleware('auth');
//
// // Laporan Kredit Note
// Route::get('/laporankreditnote', 'LaporanKreditNoteController@index')->middleware('auth');
// Route::post('/loadAllLKreditNote', 'LaporanKreditNoteController@loadAll')->middleware('auth');
//
// // Laporan Permintaan Pemakaian
// Route::get('/laporanpermintaanpemakaian', 'LaporanPermintaanPemakaianController@index')->middleware('auth');
// Route::post('/loadAllLPermintaanPemakaian', 'LaporanPermintaanPemakaianController@loadAll')->middleware('auth');
//
// // Laporan Pemakaian Barang
// Route::get('/laporanpemakaianbarang', 'LaporanPemakaianBarangController@index')->middleware('auth');
// Route::post('/loadAllLPemakaianBarang', 'LaporanPemakaianBarangController@loadAll')->middleware('auth');
//
// // Laporan Pemakaian Barang ACC
// Route::get('/laporanpemakaianbarangacc', 'LaporanPemakaianBarangACCController@index')->middleware('auth');
// Route::post('/loadAllLPemakaianBarangACC', 'LaporanPemakaianBarangACCController@loadAll')->middleware('auth');
//
// // Laporan Ubah Kemasan Barang
// Route::get('/laporanubahkemasanbarang', 'LaporanUbahKemasanBarangController@index')->middleware('auth');
// Route::post('/loadAllLUbahKemasanBarang', 'LaporanUbahKemasanBarangController@loadAll')->middleware('auth');
//
// // Laporan Perintah Opname
// Route::get('/laporanperintahopname', 'LaporanPerintahOpnameController@index')->middleware('auth');
// Route::post('/loadAllLPerintahOpname', 'LaporanPerintahOpnameController@loadAll')->middleware('auth');
//
// // Laporan Berita Acara Opname
// Route::get('/laporanberitaacaraopname', 'LaporanBeritaAcaraOpnameController@index')->middleware('auth');
// Route::post('/loadAllLBeritaAcaraOpname', 'LaporanBeritaAcaraOpnameController@loadAll')->middleware('auth');
//
// // Laporan Opname Barang
// Route::get('/laporanopnamebarang', 'LaporanOpnameBarangController@index')->middleware('auth');
// Route::post('/loadAllLOpnameBarang', 'LaporanOpnameBarangController@loadAll')->middleware('auth');
//
// // Laporan Koreksi Stock
// Route::get('/laporankoreksistock', 'LaporanKoreksiStockController@index')->middleware('auth');
// Route::post('/loadAllLKoreksiStock', 'LaporanKoreksiStockController@loadAll')->middleware('auth');
//
// // Laporan Permintaan Transfer Barang
// Route::get('/laporanpermintaantransferbarang', 'LaporanPermintaanTransferBarangController@index')->middleware('auth');
// Route::post('/loadAllLPermintaanTransferBarang', 'LaporanPermintaanTransferBarangController@loadAll')->middleware('auth');
//
// // Laporan Transfer Barang
// Route::get('/laporantransferbarang', 'LaporanTransferBarangController@index')->middleware('auth');
// Route::post('/loadAllLTransferBarang', 'LaporanTransferBarangController@loadAll')->middleware('auth');
//
// // Laporan Penerimaan Transfer Barang
// Route::get('/laporanpenerimaantransferbarang', 'LaporanPenerimaanTransferBarangController@index')->middleware('auth');
// Route::post('/loadAllLPenerimaanTransferBarang', 'LaporanPenerimaanTransferBarangController@loadAll')->middleware('auth');
//
// // Laporan Kartu Stock 1
// Route::get('/laporankartustockq', 'LaporanKartuStockController@index1')->middleware('auth');
// Route::post('/loadAllLKartuStock1', 'LaporanKartuStockController@loadAll1')->middleware('auth');
//
// // Laporan Kartu Stock 2
// Route::get('/laporankartustockqr', 'LaporanKartuStockController@index2')->middleware('auth');
// Route::post('/loadAllLKartuStock2', 'LaporanKartuStockController@loadAll2')->middleware('auth');
//
// // Laporan Stock 1
// Route::get('/laporanstockq', 'LaporanStockController@index1')->middleware('auth');
// Route::post('/loadAllLStock1', 'LaporanStockController@loadAll1')->middleware('auth');
//
// // Laporan Stock 2
// Route::get('/laporanstockqr', 'LaporanStockController@index2')->middleware('auth');
// Route::post('/loadAllLStock2', 'LaporanStockController@loadAll2')->middleware('auth');
//
// // Laporan Biaya Akun
// Route::get('/laporanbiayaakun', 'LaporanBiayaAkunController@index')->middleware('auth');
// Route::post('/loadAllLBiaya', 'LaporanBiayaAkunController@loadAll')->middleware('auth');
//
// // Laporan Penerimaan Kas
// Route::get('/laporanpenerimaankas', 'LaporanPenerimaanKasController@index')->middleware('auth');
// Route::post('/loadAllLPenerimaanKas', 'LaporanPenerimaanKasController@loadAll')->middleware('auth');
//
// // Laporan Pengeluaran Kas
// Route::get('/laporanpengeluarankas', 'LaporanPengeluaranKasController@index')->middleware('auth');
// Route::post('/loadAllLPengeluaranKas', 'LaporanPengeluaranKasController@loadAll')->middleware('auth');
//
// // Laporan Penerimaan Bank
// Route::get('/laporanpenerimaanbank', 'LaporanPenerimaanBankController@index')->middleware('auth');
// Route::post('/loadAllLPenerimaanBank', 'LaporanPenerimaanBankController@loadAll')->middleware('auth');
//
// // Laporan Pengeluaran Bank
// Route::get('/laporanpengeluaranbank', 'LaporanPengeluaranBankController@index')->middleware('auth');
// Route::post('/loadAllLPengeluaranBank', 'LaporanPengeluaranBankController@loadAll')->middleware('auth');
//
// // Laporan Memorial
// Route::get('/laporanmemorial', 'LaporanMemorialController@index')->middleware('auth');
// Route::post('/loadAllLMemorial', 'LaporanMemorialController@loadAll')->middleware('auth');
//
// // Laporan Koreksi
// Route::get('/laporankoreksi', 'LaporanKoreksiController@index')->middleware('auth');
// Route::post('/loadAllLKoreksi', 'LaporanKoreksiController@loadAll')->middleware('auth');
//
// // Laporan Buku Besar
// Route::get('/laporanbukubesar', 'LaporanBukuBesarController@index')->middleware('auth');
// Route::post('/loadAllLBukuBesar', 'LaporanBukuBesarController@loadAll')->middleware('auth');
//
// // Laporan Costing
// Route::get('/laporancosting', 'LaporanCostingController@index')->middleware('auth');
// Route::post('/loadAllLCosting', 'LaporanCostingController@loadAll')->middleware('auth');
//
// // Laporan Aktiva
// Route::get('/laporanaktiva', 'LaporanAktivaController@index')->middleware('auth');
// Route::post('/loadAllLAktiva', 'LaporanAktivaController@loadAll')->middleware('auth');
//
// // Laporan Kas Harian
// Route::get('/laporankasharian', 'LaporanKasHarianController@index')->middleware('auth');
// Route::post('/loadAllLKasHarian', 'LaporanKasHarianController@loadAll')->middleware('auth');
//
// // Laporan Bank Harian
// Route::get('/laporanbankharian', 'LaporanBankHarianController@index')->middleware('auth');
// Route::post('/loadAllLBankHarian', 'LaporanBankHarianController@loadAll')->middleware('auth');
//
// // Laporan Bon Sementara
// Route::get('/laporanbonsementara', 'LaporanBonSementaraController@index')->middleware('auth');
// Route::post('/loadAllLBonSementara', 'LaporanBonSementaraController@loadAll')->middleware('auth');
//
// // Laporan Mutasi Kas
// Route::get('/laporanmutasikas', 'LaporanMutasiKasController@index')->middleware('auth');
// Route::post('/loadAllLMutasiKas', 'LaporanMutasiKasController@loadAll')->middleware('auth');
//
// // Laporan Giro Buka
// Route::get('/laporangirobuka', 'LaporanGiroBukaController@index')->middleware('auth');
// Route::post('/loadAllLGiroBuka', 'LaporanGiroBukaController@loadAll')->middleware('auth');
//
// // Laporan Giro Terima
// Route::get('/laporangiroterima', 'LaporanGiroTerimaController@index')->middleware('auth');
// Route::post('/loadAllLGiroTerima', 'LaporanGiroTerimaController@loadAll')->middleware('auth');
//
// // Laporan Saldo Hutang
// Route::get('/laporansaldohutang', 'LaporanSaldoHutangController@index')->middleware('auth');
// Route::post('/loadAllLSaldoHutang', 'LaporanSaldoHutangController@loadAll')->middleware('auth');
//
// // Laporan Saldo Piutang
// Route::get('/laporansaldopiutang', 'LaporanSaldoPiutangController@index')->middleware('auth');
// Route::post('/loadAllLSaldoPiutang', 'LaporanSaldoPiutangController@loadAll')->middleware('auth');
//
// // Laporan Kartu Hutang
// Route::get('/laporankartuhutang', 'LaporanKartuHutangController@index')->middleware('auth');
// Route::post('/loadAllLKartuHutang', 'LaporanKartuHutangController@loadAll')->middleware('auth');
//
// // Laporan Kartu Piutang
// Route::get('/laporankartupiutang', 'LaporanKartuPiutangController@index')->middleware('auth');
// Route::post('/loadAllLKartuPiutang', 'LaporanKartuPiutangController@loadAll')->middleware('auth');
//
// // Laporan Pelunasan Hutang
// Route::get('/laporanpelunasanhutang', 'LaporanPelunasanHutangController@index')->middleware('auth');
// Route::post('/loadAllLPelunasanHutang', 'LaporanPelunasanHutangController@loadAll')->middleware('auth');
//
// // Laporan Pelunasan Piutang
// Route::get('/laporanpelunasanpiutang', 'LaporanPelunasanPiutangController@index')->middleware('auth');
// Route::post('/loadAllLPelunasanPiutang', 'LaporanPelunasanPiutangController@loadAll')->middleware('auth');
//
// // Laporan Sisa Hutang
// Route::get('/laporansisahutang', 'LaporanSisaHutangController@index')->middleware('auth');
// Route::post('/loadAllLSisaHutang', 'LaporanSisaHutangController@loadAll')->middleware('auth');
//
// // Laporan Sisa Piutang
// Route::get('/laporansisapiutang', 'LaporanSisaPiutangController@index')->middleware('auth');
// Route::post('/loadAllLSisaPiutang', 'LaporanSisaPiutangController@loadAll')->middleware('auth');
//
// // Laporan Umur Hutang
// Route::get('/laporanumurhutang', 'LaporanUmurHutangController@index')->middleware('auth');
// Route::post('/loadAllLUmurHutang', 'LaporanUmurHutangController@loadAll')->middleware('auth');
//
// // Laporan Umur Piutang
// Route::get('/laporanumurpiutang', 'LaporanUmurPiutangController@index')->middleware('auth');
// Route::post('/loadAllLUmurPiutang', 'LaporanUmurPiutangController@loadAll')->middleware('auth');
//
// // Laporan Mutasi
// Route::get('/laporanmutasi', 'LaporanMutasiController@index')->middleware('auth');
// Route::post('/loadAllLMutasi', 'LaporanMutasiController@loadAll')->middleware('auth');
//
// // Laporan Neraca Lajur
// Route::get('/laporanneracalajur', 'LaporanNeracaLajurController@index')->middleware('auth');
// Route::post('/loadAllLNeracaLajur', 'LaporanNeracaLajurController@loadAll')->middleware('auth');
//
// // Laporan Laba/Rugi
// Route::get('/laporanlabarugi', 'LaporanLabaRugiController@index')->middleware('auth');
// Route::post('/loadAllLLabaRugi', 'LaporanLabaRugiController@loadAll')->middleware('auth');
//
// // Laporan Neraca
// Route::get('/laporanneraca', 'LaporanNeracaController@index')->middleware('auth');
// Route::post('/loadAllLNeraca', 'LaporanNeracaController@loadAll')->middleware('auth');
//
// // Laporan Neraca Penunjang
// Route::get('/laporanneracapenunjang', 'LaporanNeracaPenunjangController@index')->middleware('auth');
// Route::post('/loadAllLNeracaPenunjang', 'LaporanNeracaPenunjangController@loadAll')->middleware('auth');
//
// // POS
// Route::get('/pos', 'POSController@index')->middleware('auth');
// Route::get('/loadAllPOS', 'POSController@loadAll')->middleware('auth');
// Route::get('/generateNomorBuktiPOS', 'POSController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPOS', 'POSController@generateNomorUrut')->middleware('auth');
// Route::post('/addPOS', 'POSController@add')->middleware('auth');
// Route::post('/showPOS', 'POSController@show')->middleware('auth');
// Route::post('/showDetPOS', 'POSController@showDet')->middleware('auth');
// Route::post('/editPOS', 'POSController@edit')->middleware('auth');
// Route::post('/erasePOS', 'POSController@erase')->middleware('auth');
// Route::post('/authPOS', 'POSController@changeAuth')->middleware('auth');
// Route::post('/batalPOS', 'POSController@changeBatal')->middleware('auth');
//
// // Search
// Route::get('/search', 'SearchController@index')->middleware('auth');
// Route::get('/searchBarang', 'SearchController@searchBarang')->middleware('auth');
// Route::get('/searchBarangGudang', 'SearchController@searchBarangGudang')->middleware('auth');
// Route::get('/searchBarangProduksi', 'SearchController@searchBarangProduksi')->middleware('auth');
//
// // Transaksi
// Route::post('/checkHold', 'TransaksiController@checkHold')->middleware('auth');
// Route::post('/hold', 'TransaksiController@hold')->middleware('auth');
// Route::post('/release', 'TransaksiController@release')->middleware('auth');
// Route::get('/releaseAll', 'TransaksiController@releaseAll')->middleware('auth');
//
// // Cetak
// Route::post('/cetakKasBank', 'CetakController@cetakKasBank')->middleware('auth');
// Route::post('/cetakMemorialKoreksi', 'CetakController@cetakMemorialKoreksi')->middleware('auth');
// Route::post('/cetakGiro', 'CetakController@cetakGiro')->middleware('auth');
//
// // settingmenu
// Route::get('/settingmenu', 'SettingMenuController@index')->name('settingmenu')->middleware('auth');
// Route::get('/loadAllUsersUtama', 'SettingMenuController@loadAll')->middleware('auth');
// Route::post('/addUserUtama', 'SettingMenuController@add')->middleware('auth');
// Route::post('/editUserUtama', 'SettingMenuController@edit')->middleware('auth');
// Route::post('/eraseUserUtama', 'SettingMenuController@erase')->middleware('auth');
// Route::post('/offlineUserUtama', 'SettingMenuController@offline')->middleware('auth');
// Route::post('/getAksesMenuUtama', 'SettingMenuController@getAkses')->middleware('auth');
// Route::post('/getAksesMenuUtamaPilih', 'SettingMenuController@getAksesPilih')->middleware('auth');
// Route::post('/editAksesMenuUtama', 'SettingMenuController@editAkses')->middleware('auth');
// Route::post('/getAksesByMenuUtama', 'SettingMenuController@getAksesByMenu')->middleware('auth');
// Route::post('/editSetMenuUtama', 'SettingMenuController@editSetMenu')->middleware('auth');
//

// // Penawaran
// Route::get('/penawaran', 'PenawaranController@index')->middleware('auth');
// Route::get('/loadAllPenawaran', 'PenawaranController@loadAll')->middleware('auth');
// Route::post('/loadAllPenawaranPage', 'PenawaranController@loadAllPage')->middleware('auth');
// Route::post('/showPenawaran', 'PenawaranController@show')->middleware('auth');
// Route::post('/addPenawaran', 'PenawaranController@add')->middleware('auth');
// Route::post('/editPenawaran', 'PenawaranController@edit')->middleware('auth');
// Route::post('/erasePenawaran', 'PenawaranController@erase')->middleware('auth');
// Route::get('/generateNomorBuktiPenawaran', 'PenawaranController@generateNomorBukti')->middleware('auth');
// Route::get('/generateNomorUrutPenawaran', 'PenawaranController@generateNomorUrut')->middleware('auth');
//
// // Detail Penawaran
// Route::get('/detailpenawaran', 'DetailPenawaranController@index')->middleware('auth');
// Route::post('/addDetPenawaran', 'DetailPenawaranController@add')->middleware('auth');
// Route::post('/showDetPenawaran', 'DetailPenawaranController@showDetPenawaran')->middleware('auth');
// Route::post('/showDetPenawaranbyNomorBukti', 'DetailPenawaranController@showDetPenawaranbyNomorBukti')->middleware('auth');
// Route::post('/showDetPenawaranNoBukti', 'DetailPenawaranController@showDetPenawaranNoBukti')->middleware('auth');
// Route::post('/showRowDetPenawaran', 'DetailPenawaranController@showRowDetPenawaran')->middleware('auth');
// Route::post('/editDetPenawaran', 'DetailPenawaranController@edit')->middleware('auth');
// Route::post('/eraseDetPenawaran', 'DetailPenawaranController@erase')->middleware('auth');
// Route::post('/authDetPenawaran', 'DetailPenawaranController@changeAuth')->middleware('auth');
// Route::post('/batalDetPenawaran', 'DetailPenawaranController@changeBatal')->middleware('auth');
