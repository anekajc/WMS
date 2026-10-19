<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\vwOUtPOWMS;
use App\Model\VWtampilbeli;
use App\Model\NEWDBBELI;
use App\Model\VWOUtPrRJUal;
use App\Model\VwSpbRjual;



// use App\Http\Controllers\NewMenuController;

class NewReturJualController extends Controller
{


  public function index () {
    // VwSpbRjual
    $kodemenu = '014';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = VWOUtPrRJUal::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }
    $returGudang = VwSpbRjual::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempReturGudang = [];
    foreach ($returGudang as $o) {
      // code...
      array_push($tempReturGudang, $o);
    }
    $gudang = DB::connection("SML")->select('select * from DBGUDANG where KODEGDG <> :id', ['id' => 'GTC']);

    return view('newreturjual' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "gudang" => $gudang,
      "outstandingArray" => $tempOutstanding,
      "returGudangArray" => $tempReturGudang,
      "akses" => $akses
    ]);


  }

  public function loadAll () {
    $kodemenu = '014';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = VWOUtPrRJUal::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }
    $returGudang = VwSpbRjual::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempReturGudang = [];
    foreach ($returGudang as $o) {
      // code...
      array_push($tempReturGudang, $o);
    }

    return [
      "outstandingArray" => $tempOutstanding,
      "returGudangArray" => $tempReturGudang,
      "akses" => $akses
    ];
  }

  public function getDetailReturGudang (Request $req) {
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $detailReturGudang = VwSpbRjual::all()->where('NOBUKTI', $req->input('NOBUKTI'))->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT');
    $tempReturGudang = [];
    foreach ($detailReturGudang as $dr) {
      // code...
      array_push($tempReturGudang,$dr);
    }
    return $tempReturGudang;
  }

  public function spKoreksi (Request $req) {
    // return $req->input('noprj');
    $values = [
      $req->input('choice'),
      $req->input('nospr'),
      $req->input('nourut'),
      $req->input('tanggal'),
      $req->input('noprj'),
      $req->input('kodecustsupp'),
      $req->input('nopolkend'),
      $req->input('container'),
      $req->input('nocontainer'),
      $req->input('noseal'),
      $req->input('catatan'),
      $req->input('urut'),
      $req->input('urutrpj'),
      $req->input('kodebrg'),
      $req->input('inputQty'),
      $req->input('inputQty1'),
      $req->input('inputQty2'),
      $req->input('sat_1'),
      $req->input('nosat'),
      $req->input('isi'),
      $req->input('netw'),
      $req->input('grossw'),
      \Auth::user()->username,
      $req->input('isempty'),
      $req->input('namabarang'),
      $req->input('sopir'),
      $req->input('flagmenu'),
      $req->input('kodegdg'),
      $req->input('flagtipe'),
      $req->input('tipePPN'),
      $req->input('noSO'),
      $req->input('noBatch'),
      $req->input('flagKembali'),
      $req->input('qntReject'),
      $req->input('qntReject1'),
      $req->input('qntReject2'),
      $req->input('sat_2')
    ];

    DB::connection('SML')->statement('exec sp_TransSPBRJUAL ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?', $values);
    return 1;


// @Choice varchar(1),
// @NOBUKTI varchar(30),
// @NoUrut varchar(7),
// @TANGGAL datetime,
// @NoRPJ varchar(30), 5
// @KODECUSTSUPP varchar(15),
// @NoPolKend varchar(50), ""
// @Container varchar(50), ""
// @NoContainer varchar(50), ""
// @NoSeal varchar (500), "" 10
// @Catatan varchar(4000), ""
// @URUT int, urutspr ? urut dari edit, dari add 0
// @UrutRPJ int, ururpj
// @KODEBRG varchar(25),
// @QNT numeric(18,2), 15
// @QNT1 numeric(18,2),
// @QNT2 numeric(18,2),
// @SAT_1 varchar(5),
// @NoSat tinyint,
// @ISI numeric(18,2),
// @NetW numeric(18,2), 0
// @GrossW numeric(18,2), 0
// @IDUser varchar(30),
// @IsEmpty tinyint, 0
// @Namabrg varchar(200), 25
// @Sopir varchar(100), ""
// @Flagmenu tinyint, 1
// @kodegdg varchar(15),
// @FlagTipe tinyint, 1
// @TipePPN tinyint, 0 30
// @NOSO varchar(30), '0'
// @NoBatch varchar(50), '0'
// @FlagKembali Tinyint, 0
// @QntReject Numeric(18,2), 0
// @Qnt1Reject Numeric(18,2), 0
// @Qnt2Reject Numeric(18,2), 0
// @Sat_2 varchar(10)='' 37

}

  public function getDetailOutstanding (Request $req) {

    $detailOutstanding = VWOUtPrRJUal::all()->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function getKoreksiAddList (Request $req) {
    $tempJual = VwSpbRjual::where('NOBUKTI' , $req->input('nospr'))->select('KODEBRG')->get()->toArray();

    $tempAddList = VWOUtPrRJUal::select()->where('NOBUKTI', $req->input('noprj'))->whereNotIn('KODEBRG', $tempJual)->get();

    return $tempAddList;
  }



  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    // $inisial = DB::connection("SML")->select('select PBL from DBNOMOR');
    $inisial = DB::connection("SML")->select('select SPR from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->SPR,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }



  public function addReturJual (Request $req) {


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $date = $req->input('inputDate');
    $username = \Auth::user()->username;
    $data = $req->input('tempData');
    $nourut = $req->input('nourut');
    $noprj = $req->input('noprj');
    $nospr = $req->input('nospr');

    // return [$date, $username, $nourut, $nospr, $noprj];

    $check = DB::connection('SML')->select('select * from dbSPBRJual where NOBUKTI = :nobukti',["nobukti" => $nospr]);
    if ($check) {
      return 2;
    }

    DB::connection('SML')->statement('delete	TempOutstanding where IDUser = :idUser',['idUser' => $username ]);
    foreach ($data as $d) {
      $values = [$username,$noprj,'',$d['URUT'],$d['inputQntTerima'],true];
      DB::connection("SML")->statement('exec sp_ReTempRJualGdgWEb ?,?,?,?,?,?',$values);

    }


    $tempValues = [$nospr, $nourut,$noprj, $username, 1, $date, ''];
    DB::connection('SML')->statement('exec SP_InsertPRRSPB ?,?,?,?,?,?,?', $tempValues);
    return 1;
  }

}
