<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWOutPicking;


use App\Model\VWQCPicking;


class QCPickingController extends Controller
{




  public function index () {

    $kodemenu = '023';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = VWOutPicking::all()->sortBy('urut');

    // $tempOutstanding = [];
    // foreach ($outstanding as $o) {
    //   // code...
    //   array_push($tempOutstanding, $o);
    // }
    //
    // $penerimaan = VWTRansferOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $penerimaan = VWQCPicking::all()->where('BUlan',$periode->bulan )->where('tahun', $periode->tahun)->sortBy('urut')->groupBy('NObukti');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('qcpicking' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "outstandingArray" => $outstanding,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ]);
  }


  // public function getNoBukti (Request $req) {
  //
  //   $periode = NewPeriode::where('user_id' , \Auth::id())->first();
  //   // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
  //   $inisial = DB::connection("SML")->select('select ssp from DBNOMOR');
  //   $username = \Auth::user()->username;
  //   // return [$periode->bulan,$inisial[0]->PBL,$username];
  //   $values = [
  //       $inisial[0]->ssp,
  //       $periode->bulan,
  //       $periode->tahun,
  //       $username
  //   ];
  //   $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
  //   return $noBukti;
  // }

  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = VWOutPicking::all()->where('NObukti', $req->input('NOBUKTI'))->sortBy('urut');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function getDetailPenerimaan (Request $req) {
    $detailPenerimaan = VWQCPicking::all()->where('NObukti',$req->input('NOBUKTI') )->sortBy('urut');
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

    $data = $req->input('tempData');
    $nobukti = $req->input('nobukti');
    $username = \Auth::user()->username;


    foreach ($data as $d) {

      DB::connection("SML")->update('UPDATE dboutbrgdet2 set Qnt= :qntTerima , QntReject = :qntReject , pQC = 1, UserQc = :username , TglQc = getDate() where NOBUKTI = :nobukti and URUT= :urut', ["qntTerima" => $d['inputQtyTerima'] , "qntReject" => $d['inputQtyReject'] , "username" => $username , "nobukti" =>  $req->input('nobukti') , "urut" => $d['urut']]);
      DB::connection("SML")->update('UPDATE dboutbrgdet set Qnt=:qntTerima where nobukti = :nobuktibeli and urut= :urutbeli', ["qntTerima" => $d['inputQtyTerima'] ,  "nobuktibeli" =>   $d['NoPickingList'] , "urutbeli" => $d['UrutPickingLIST']]);

      // UPDATE dboutbrgdet set Qnt==:qnt where nobukti = :nobuktibeli and urut= :urutbeli', ["qntReject" => $d['inputQtyReject'] , "username" => $username , "nobukti" =>  $req->input('nobukti') , "urut" => $d['urut']]
    }

    return 1;


  }




  public function loadAll () {
    $kodemenu = '023';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = VWOutPicking::all()->sortBy('urut');
    // ->groupBy('NObukti')
    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    $penerimaan = VWQCPicking::all()->where('BUlan',$periode->bulan )->where('tahun', $periode->tahun)->sortBy('urut')->groupBy('NObukti');
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
