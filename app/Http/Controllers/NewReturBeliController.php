<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWOutRBeli;


use App\Model\VWRBeliOut;


class NewReturBeliController extends Controller
{




  public function index () {

    $kodemenu = '025';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = VWOutRBeli::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }
    //
    // $penerimaan = VWTRansferOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    // Dinonaktifkan 2026-10-21: memuat seluruh view lalu menyaring di PHP (lambat).
    // $penerimaan = VWRBeliOut::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $penerimaan = VWRBeliOut::query()->where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('newreturbeli' , [
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
    $inisial = DB::connection("SML")->select('select rpb from DBNOMOR');
    $username = \Auth::user()->username;
    // return [$periode->bulan,$inisial[0]->PBL,$username];
    $values = [
        $inisial[0]->rpb,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function getDetailOutstanding (Request $req) {

    // Dinonaktifkan 2026-10-21: memuat seluruh view lalu menyaring di PHP (lambat).
    // $detailOutstanding = VWOutRBeli::all()->where('NOBUKTI', $req->input('NOBUKTI'))->sortBy('URUT');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $detailOutstanding = VWOutRBeli::query()->whereRaw('NOBUKTI = cast(? as varchar(100))', [$req->input('NOBUKTI')])->get()->sortBy('URUT');
    $tempOutstanding = [];
    foreach ($detailOutstanding as $do) {
      // code...
      array_push($tempOutstanding,$do);
    }
    return $tempOutstanding;

  }

  public function getDetailPenerimaan (Request $req) {
    // Dinonaktifkan 2026-10-21: memuat seluruh view lalu menyaring di PHP (lambat).
    // $detailPenerimaan = VWRBeliOut::all()->where('NOBUKTI',$req->input('NOBUKTI') )->sortBy('URUT');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $detailPenerimaan = VWRBeliOut::query()->whereRaw('NOBUKTI = cast(? as varchar(100))', [$req->input('NOBUKTI')])->get()->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($detailPenerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function getKoreksiAddList (Request $req) {
    $tempPenerimaan = VWRBeliOut::where('NOBUKTI' , $req->input('norpb'))->select('KODEBRG')->get()->toArray();

    $tempAddList = VWOutRBeli::select()->where('NOBUKTI', $req->input('noout'))->whereNotIn('KODEBRG', $tempPenerimaan)->get();

    return $tempAddList;
  }

  public function spAdd (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $date = $req->input('inputDate');
    $username = \Auth::user()->username;
    $data = $req->input('tempData');
    $nourut = $req->input('nourut');
    $noout = $req->input('noout');
    $norpb = $req->input('norpb');

    $checkSaldo = 0;
    // return [$data,$date,$username,$nourut,$noout,$norpb];

    $check = DB::connection('SML')->select('select * from dbRBeli where NOBUKTI = :nobukti',["nobukti" => $norpb]);
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
    // [sp_RefreshTempOutRbeli]
    // --Declare
    // @IDUser varchar(30),
    // @NoBukti varchar(20),
    // @Tahun int,
    // @Bulan int,
    // @FlagTipe int,
    // @urut integer,
    // @qnt numeric(18,2)


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

      $values = [$username, $noout,$d['Tahun'],$d['Bulan'],0,$d['Urut'],$d['inputQntTerima']];
      // return [$values];
      DB::connection("SML")->statement('exec sp_RefreshTempOutRbeli ?,?,?,?,?,?,?',$values);

      if ($checkSaldo) {
        // code...
        return 3;
      }

    }
    // SP_InsertOutSerahSample
    // @Nobukti varchar(25),
    // @Nourut varchar(10),
    // @Nob varchar(25),
    // @IdUser varchar(20),
    // @Tanggal DateTime

    // [sp_InsertOutRbeliweb]
    // @NoBukti varchar(20),
    // @NoUrut varchar(10),
    // @Tanggal datetime,
    // @KodeGdg varchar(20),
    // @NoPO varchar(20),
    // @FakturSupp varchar(200),
    // @Keterangan Varchar(5000),
    // @pjasa Bit=0,
    // @JmlRecord Integer,
    // @iduser varchar(20)

                      // $data[0]['Kodegdg']
    $tempValues = [$norpb, $nourut,$date,$data[0]['Kodegdg'], $noout,"","",0,0, $username];
    // return [$tempValues];
    DB::connection('SML')->statement('exec sp_InsertOutRbeliweb ?,?,?,?,?,?,?,?,?,?', $tempValues);
    return 1;



  }

  public function loadAll () {

    $kodemenu = '025';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();

    $outstanding = VWOutRBeli::all()->sortBy('URUT')->groupBy('NOBUKTI');

    $tempOutstanding = [];
    foreach ($outstanding as $o) {
      // code...
      array_push($tempOutstanding, $o);
    }

    // Dinonaktifkan 2026-10-21: memuat seluruh view lalu menyaring di PHP (lambat).
    // $penerimaan = VWRBeliOut::all()->where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    // Pengganti: saringan yang sama langsung di query database (hasil sama, jauh lebih cepat).
    $penerimaan = VWRBeliOut::query()->where('Bulan', $periode->bulan)->where('Tahun', $periode->tahun)->get()->sortBy('URUT')->groupBy('NOBUKTI');
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
      $req->input('norpb'),
      $req->input('nourut'),
      $req->input('inputDate'),
      $req->input('kodesupp'),
      $req->input('kodegdg'),
      $req->input('noout'),
      $req->input('keterangan'),
      $req->input('faktursupp'),
      $req->input('urut'),
      $req->input('kodebrg'),
      $req->input('urutout'),
      $req->input('qntTerima'),
      $req->input('nosat'),
      $req->input('satuan'),
      $req->input('isi'),
      $req->input('qntTerima1'),
      $req->input('qntTerima2'),
      $req->input('flagtipe'),
      $req->input('nobatch'),
      $req->input('nolpb'),

    ];

    DB::connection('SML')->statement('exec sp_RBeliGudangweb ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?' ,$values);
    return 1;

    }






}
