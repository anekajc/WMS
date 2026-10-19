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


class NewPenyerahanSampleController extends Controller
{




  public function index () {

    $kodemenu = '027';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = VWOutSerahSample::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }
    //
    // $penerimaan = VWTRansferOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $penerimaan = VWSerahSampleOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('newpenyerahansample' , [
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
    $inisial = DB::connection("SML")->select('select ssp from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->ssp,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = VWOutSerahSample::all()->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function getDetailPenerimaan (Request $req) {
    $detailPenerimaan = VWSerahSampleOut::all()->where('NOBUKTI',$req->input('NOBUKTI') )->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($detailPenerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function getKoreksiAddList (Request $req) {
    $tempPenerimaan = VWSerahSampleOut::where('NOBUKTI' , $req->input('nossp'))->select('KODEBRG')->get()->toArray();

    $tempAddList = VWOutSerahSample::select()->where('NOBUKTI', $req->input('noout'))->whereNotIn('KODEBRG', $tempPenerimaan)->get();

    return $tempAddList;
  }

  public function spAdd (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $date = $req->input('inputDate');
    $username = \Auth::user()->username;
    $data = $req->input('tempData');
    $nourut = $req->input('nourut');
    $noout = $req->input('noout');
    $nossp = $req->input('nossp');


    // return [$data,$date,$username,$nourut,$noout,$nopbg];
    // select * from DBSERAHSAMPLE where NOBUKTI = 'SML/SSP/00007/0523'
    $check = DB::connection('SML')->select('select * from DBSERAHSAMPLE where NOBUKTI = :nobukti',["nobukti" => $nossp]);
    if ($check) {
      return 2;
    }
    DB::connection('SML')->statement('delete	TempOutstandingPO where IDUser = :idUser',['idUser' => $username ]);


    // DB::connection('SML')->statement('delete	TempOutstanding where IDUser = :idUser',['idUser' => $username ]);

    // return 1;

    // sp_RefreshOutTempSerahSample
    // @IDUser varchar(30),
    // @NoBukti varchar(20),
    // @Tahun int,
    // @Bulan int,
    // @Quantity numeric(18,2),
    // @urut int


    foreach ($data as $d) {
      $values = [$username, $noout,$d['Tahun'],$d['Bulan'],$d['inputQntTerima'],$d['Urut']];
      DB::connection("SML")->statement('exec sp_RefreshOutTempSerahSample ?,?,?,?,?,?',$values);
    }

    // SP_InsertOutSerahSample
    // @Nobukti varchar(25),
    // @Nourut varchar(10),
    // @Nob varchar(25),
    // @IdUser varchar(20),
    // @Tanggal DateTime

    $tempValues = [$nossp, $nourut, $noout, $username, $date];
    DB::connection('SML')->statement('exec SP_InsertOutSerahSample ?,?,?,?,?', $tempValues);
    return 1;



  }

  public function loadAll () {
    $kodemenu = '027';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = VWOutSerahSample::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    $penerimaan = VWSerahSampleOut::all()->where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
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

  public function spKoreksi (Request $req) {


  $values = [
    $req->input('choice'),
    $req->input('nossp'),
    $req->input('nourut'),
    $req->input('inputDate'),
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
    $req->input('kodecustsupp'),
    $req->input('kodesls'),
    $req->input('pbonus'),
    $req->input('maxol'),
    $req->input('nopr'),
    $req->input('urutpr'),
    $req->input('pkonsi'),
    $req->input('nooutbrg'),
    $req->input('urutoutbrg'),
  ];

  DB::connection('SML')->statement('exec SP_SerahSampleWEB ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?' ,$values);
  return 1;

  }





}
