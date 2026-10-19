<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWSerahSampleOut;


use App\Model\VWOutSerahSample;
use App\Model\VWOutPRUKO;
use App\Model\VWUbahKemasanOut;


class UbahKemasanFinalisasiController extends Controller
{




  public function index () {

    $kodemenu = '029103';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();


    $outstanding = VWOutPRUKO::all()->sortBy('Urut')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }
    //
    // $penerimaan = VWTRansferOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $penerimaan = VWUbahKemasanOut::all()->where('Bulan',$periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('ubahkemasanfinalisasi' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "outstandingArray" => $tempOutstanding,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ]);
  }


  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select KMBJ from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->KMBJ,
        $periode->bulan,
        $periode->tahun,
        $username,
        ''
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?,?',$values);
    return $noBukti;
  }

  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = VWOutPRUKO::all()->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('Urut');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }



  public function spAdd (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $date = $req->input('inputDate');
    $username = \Auth::user()->username;
    $data = $req->input('tempData');
    $nourut = $req->input('nourut');
    $noout = $req->input('noout');
    $nossp = $req->input('nossp');


    DB::connection('SML')->statement('delete	TempOutstandingPO where IDUser = :idUser',['idUser' => $username ]);
    $check = DB::connection('SML')->select('select * from DBUBAHKEMASAN where NOBUKTI = :nobukti',["nobukti" => $nossp]);
    if ($check) {
      return 2;
    }



    foreach ($data as $d) {
      $values = [$username, $d['NOBUKTI'],$d['Tahun'],$d['Bulan'],$d['inputQntTerima'],$d['Urut']];
      DB::connection("SML")->statement('exec sp_RefreshOutTempUKO ?,?,?,?,?,?',$values);
    }

    //
    //
    $tempValues = [$nossp, $nourut, $noout, $username, $date];
    DB::connection('SML')->statement('exec SP_InsertOutUKOUKI ?,?,?,?,?', $tempValues);
    return 1;



  }

  public function loadAll () {

    $kodemenu = '029103';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = VWOutPRUKO::all()->sortBy('Urut')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    $penerimaan = VWUbahKemasanOut::all()->where('Bulan',$periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
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







}
