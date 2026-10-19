<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWOutBRGPemakaian;


use App\Model\VWPenyerahanOut;


class NewPenyerahanGudangController extends Controller
{




  public function index () {

    $kodemenu = '032';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = VWOutBRGPemakaian::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }
    //
    // $penerimaan = VWTRansferOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $penerimaan = VWPenyerahanOut::all()->where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('newpenyerahangudang' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "outstandingArray" => $tempOutstanding,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ]);
  }

  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = VWOutBRGPemakaian::all()->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function getDetailPenerimaan (Request $req) {
    $detailPenerimaan = VWPenyerahanOut::all()->where('NOBUKTI',$req->input('NOBUKTI') )->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($detailPenerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function loadAll () {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = VWOutBRGPemakaian::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    $penerimaan = VWPenyerahanOut::all()->where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }

    return [
      "outstandingArray" => $tempOutstanding,
      "penerimaanArray" => $tempPenerimaan
    ];
  }

  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select pbg from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->pbg,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function getKoreksiAddList (Request $req) {
    $tempPenerimaan = VWPenyerahanOut::where('NOBUKTI' , $req->input('nopbg'))->select('KODEBRG')->get()->toArray();

    $tempAddList = VWOutBRGPemakaian::select()->where('NOBUKTI', $req->input('noout'))->whereNotIn('KODEBRG', $tempPenerimaan)->get();

    return $tempAddList;
  }

  public function addPenyerahanGudang (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $date = $req->input('inputDate');
    $username = \Auth::user()->username;
    $data = $req->input('tempData');
    $nourut = $req->input('nourut');
    $noout = $req->input('noout');
    $nopbg = $req->input('nopbg');

    $checkSaldo = 0;
    // return [$data,$date,$username,$nourut,$noout,$nopbg];
    DB::connection('SML')->statement('delete	TempOutstandingPO where IDUser = :idUser',['idUser' => $username ]);
    $check = DB::connection('SML')->select('select * from DBPenyerahanBhndet where NOBUKTI = :nobukti',["nobukti" => $nopbg]);
    if ($check) {
      return 2;
    }

    // DB::connection('SML')->statement('delete	TempOutstanding where IDUser = :idUser',['idUser' => $username ]);

    // return 1;

    foreach ($data as $d) {


      $check2 = DB::connection('SML')->select('exec Sp_CekStockAkhir ?,?,?,?',[$d['NOSAT'], $date, $d['Kodegdg'],  $d['KODEBRG']]);
      // array_push($checkSaldo, ["input" => (float)$d['inputQntTerima']    , "saldoqnt" => (float)$check2[0]->SALDOQNT ]);
      if ($check2) {
        if ((int)$d['inputQntTerima'] > (int)$check2[0]->SALDOQNT ) {
          // return 'a';
          $checkSaldo = 1;
        }
      } else {
        // return 'b';
        $checkSaldo = 1;
      }

      $values = [$username, $noout,$d['Tahun'],$d['Bulan'],$d['inputQntTerima'],$d['Urut']];
      DB::connection("SML")->statement('exec sp_RefreshTempPMKbhn ?,?,?,?,?,?',$values);
      if ($checkSaldo) {
        // code...
        return 3;
      }


    }
    $tempValues = [$nopbg, $nourut, $noout, $username, $date];
    DB::connection('SML')->statement('exec SP_InsertOutPmkBhn ?,?,?,?,?', $tempValues);
    return 1;



  }

  public function spKoreksi (Request $req) {

      $checkSaldo = 0;
    $check2 = DB::connection('SML')->select('exec Sp_CekStockAkhir ?,?,?,?',[$req->input('nosat'),   $req->input('inputDate'), $req->input('kodegdg'),  $req->input('kodebrg')]);
    if ($check2) {
      if ((int)$req['qntTerima'] > (int)$check2[0]->SALDOQNT ) {
        // return 'a';
        $checkSaldo = 1;
      }
    } else {
      // return 'b';
      $checkSaldo = 1;
    }

    if ($checkSaldo)  {
      // code...
      return 3;
    }



    $values = [
      $req->input('choice'),
      $req->input('nopbg'),
      $req->input('nourut'),
      $req->input('inputDate'),
      $req->input('kodegdg'),
      $req->input('urut'),
      $req->input('kodebrg'),
      $req->input('qntTerima'),
      $req->input('nosat'),
      $req->input('sat'),
      $req->input('isi'),
      $req->input('nobppb'),
      $req->input('qntTerima2'),
      $req->input('urutspk'),
      $req->input('nosatspk'),
      $req->input('issample'),
      $req->input('isbarang'),
      $req->input('keterangan'),
      $req->input('kddep'),
      $req->input('nopr'),
      $req->input('urutpr'),
    ];

    // @Choice varchar(1),
    // @NoBukti varchar(50),
    // @NoUrut varchar(10)='',
    // @Tanggal datetime='',
    // @Kodegdg varchar(15)='',
    // @Urut int=0,
    // @KodeBrg varchar(25)='',
    // @Qnt numeric(18,2)=0,
    // @NoSat tinyint=0,
    // @Sat varchar(5)='',
    // @Isi numeric(18,2)=0,
    // @NoBppB Varchar(50)='',
    // @Qnt2 numeric(18,2)=0,
    // @UrutSPK int=0,
    // @NoSatSPK tinyint=0,
    // @IsSampel smallint=0,
    // @IsBrg bit=0,
    // @Keterangan varchar(100)='',
    // @KDDEP VARCHAR(25)='',
    // @NOPR VARCHAR(30)='',
    // @URUTPR INTEGER=0

    DB::connection('SML')->statement('exec sp_PmkBhn ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?' ,$values);
    return 1;



  }




}
