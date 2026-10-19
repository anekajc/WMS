<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWOutTRansfer;
use App\Model\VWTerimaTRansfer;

class NewTransferGudangController extends Controller
{
  // VWOutTRansfer view outstanding
  // sp_TempOutTerimatransferWeb insert temp table add (masukin 1 1)=================
  // @IDUser varchar(30),
  // @NoBukti varchar(30),
  // @QntTerima numeric(18,2)
  // @Urut integer

  //[SP_InsertTERIMATRANSFER] ===================
  // @Nobukti varchar(25), generate
  // @Nourut varchar(10), generate
  // @Nob varchar(25), notrf
  // @IdUser varchar(20),
  // @Tanggal DateTime



  // nobukti = TRT SELECT trt,* FROM DBNOMOR

  public function index () {
    $kodemenu = '015';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();
    $outstanding = VWOutTRansfer::all()->sortBy('URUT')->groupBy('NOBUKTI');
    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    $penerimaan = VWTerimaTRansfer::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    return view('newtransfergudang' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "outstandingArray" => $tempOutstanding,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ]);

  }

  public function loadAll () {
    $kodemenu = '015';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();

      $periode = NewPeriode::where('user_id' , \Auth::id())->first();

      $outstanding = VWOutTRansfer::all()->sortBy('URUT')->groupBy('NOBUKTI');
      $tempOutstanding = [];
      foreach ($outstanding as $o) {
        // code...
        array_push($tempOutstanding, $o);
      }

      $penerimaan = VWTerimaTRansfer::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
      $tempPenerimaan = [];
      foreach ($penerimaan as $p) {
        // code...
        array_push($tempPenerimaan, $p);
      }

      return [
        "outstandingArray" => $tempOutstanding,
        "penerimaanArray" => $tempPenerimaan,
        "akses" => $akses
      ];
  }

  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select TRT from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->TRT,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function getDetailPenerimaan (Request $req) {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $penerimaan = VWTerimaTRansfer::all()->where('NOBUKTI', $req->input('NOBUKTI'))->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan,$p);
    }
    return $tempPenerimaan;
  }

  public function getKoreksiAddList (Request $req) {
    $tempJual = VWTerimaTRansfer::where('NOBUKTI' , $req->input('notrt'))->select('KODEBRG')->get()->toArray();

    $tempAddList = VWOutTRansfer::select()->where('NOBUKTI', $req->input('notrf'))->whereNotIn('KODEBRG', $tempJual)->get();

    return $tempAddList;
  }

  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = VWOutTRansfer::all()->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function addTransferGudang (Request $req) {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $date = $req->input('inputDate');
    $username = \Auth::user()->username;
    $data = $req->input('tempData');
    $nourut = $req->input('nourut');
    $notrt = $req->input('notrt');
    $notrf = $req->input('notrf');


    $check = DB::connection('SML')->select('
select * from DBTRANSFER where NOBUKTI = :nobukti',["nobukti" => $notrt]);
    if ($check) {
      return 2;
    }

    DB::connection('SML')->statement('delete	TempOutstanding where IDUser = :idUser',['idUser' => $username ]);
    // sp_TempOutTerimatransferWeb
    // @IDUser varchar(30),
    // @NoBukti varchar(30),
    // @QntTerima numeric(18,2)
    // @Urut integer



    foreach ($data as $d) {
      $values = [$username, $notrf,$d['inputQntTerima'],$d['URUT']];
      DB::connection("SML")->statement('exec sp_TempOutTerimatransferWeb ?,?,?,?',$values);
    }

    // SP_InsertTERIMATRANSFER
    // @Nobukti varchar(25), generate
    // @Nourut varchar(10), generate
    // @Nob varchar(25), notrf
    // @IdUser varchar(20),
    // @Tanggal DateTime

    $tempValues = [$notrt, $nourut, $notrf, $username, $date];
    DB::connection('SML')->statement('exec SP_InsertTERIMATRANSFER ?,?,?,?,?', $tempValues);
    return 1;
  }

  public function spKoreksi (Request $req) {
    $values = [
      $req->input('choice'),
      $req->input('notrt'),
      $req->input('nourut'),
      $req->input('tanggal'),
      $req->input('note'),
      $req->input('urut'),
      $req->input('kodebrg'),
      $req->input('gdgasal'),
      $req->input('gdgtujuan'),
      $req->input('sat_1'),
      $req->input('sat_2'),
      $req->input('qntTerima'),
      $req->input('qntTerima2'),
      $req->input('nosat'),
      $req->input('isi'),
      \Auth::user()->username,
      $req->input('notrf'),
      $req->input('uruttrf'),
    ];

    DB::connection('SML')->statement('exec SP_TRANSFERWEB ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $values);
    return 1;
  }









}
