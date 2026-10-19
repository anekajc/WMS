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
use App\Model\VWOutPRTFLOUt;
use App\Model\VWTFLOut;


class TransferLokasiOutController extends Controller
{




  public function index () {

    $kodemenu = '0290';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = VWOutPRTFLOUt::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }
    //
    // $penerimaan = VWTRansferOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $penerimaan = VWTFLOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('transferlokasiout' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "outstandingArray" => $tempOutstanding,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ]);
  }

  public function loadAll () {

    $kodemenu = '0290';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = VWOutPRTFLOUt::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    $penerimaan = VWTFLOut::all()->where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
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
    // return $req->get('nobukti');

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select FLK from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->FLK,
        $periode->bulan,
        $periode->tahun,
        $username,
        $req->get('nobukti')
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?,?',$values);
    return $noBukti;
  }

  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = VWOutPRTFLOUt::all()->where('NOBUKTI', $req->input('NoBukti'))->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function getDetailPenerimaan (Request $req) {
    $detailPenerimaan = VWTFLOut::all()->where('NOBUKTI',$req->input('NOBUKTI') )->sortBy('URUT');
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


    $checkSaldo = 0;

    DB::connection('SML')->statement('delete	TempOutstandingPO where IDUser = :idUser',['idUser' => $username ]);

    // $check = DB::connection('SML')->select('select * from DBTRANSFERDET where NOBUKTI = :nobukti',["nobukti" => $nossp]);
    // if ($check) {
    //   return 2;
    // }
    $check = DB::connection('SML')->select('select * from DBTRANSFER where NOBUKTI = :nobukti',["nobukti" => $nossp]);
    if ($check) {
      return 2;
    }


    foreach ($data as $d) {

      $check2 = DB::connection('SML')->select('exec Sp_CekStockAkhir ?,?,?,?',[$d['NOSAT'], $date, $d['Kodegdg'],  $d['KODEBRG']]);

      if ($check2) {
        if ((int)$d['inputQntTerima'] > (int)$check2[0]->SALDOQNT ) {
          // return 'a';
          $checkSaldo = 1;
        }
      } else {
        // return 'b';
        $checkSaldo = 1;
      }


      $values = [$username, $d['NOBUKTI'],$d['Tahun'],$d['Bulan'],$d['inputQntTerima'],$d['Urut']];
      DB::connection("SML")->statement('exec sp_RefreshOutTempTFLOut ?,?,?,?,?,?',$values);
    }


    if ($checkSaldo) {
      // code...
      return 3;
    }
    // return 1;
    // select * from DBTRANSFER where NOBUKTI = 'SML/FLK/00003/1123'



    $tempValues = [$nossp, $nourut, $noout, $username, $date];
    DB::connection('SML')->statement('exec SP_InsertOutTFLOUT ?,?,?,?,?', $tempValues);
    return 1;



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
    $req->input('nopenyerahan'),
    $req->input('nooutbrg'),
    $req->input('urutoutbrg'),
    $req->input('qntx'),
  ];



  DB::connection('SML')->statement('exec SP_TRANSFERWEB1 ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?' ,$values);
  return 1;

  }





}
