<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWMASTERPRUBAHKEMASANWEB;


class PermintaanUbahKemasanController extends Controller
{




  public function index () {
    // kalo nosat 1 qnt , qnt2 langsung dr input
    //kalo nosat 2/3 , qnt dikali isi qnt 2 dr input
    $kodemenu = '029101';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $penerimaan = VWMASTERPRUBAHKEMASANWEB::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('permintaanubahkemasan' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "penerimaanArray" => $tempPenerimaan,
      "akses" => $akses
    ]);
  }

  public function listBarang (Request $req) {
    $listBarang = DB::connection('SML')->select("select KODEBRG,NAMABRG,SAT1,SAT2,SAT3, ISI1,ISI2,ISI3 from DBBARANG where ISAKTIF = 1");
    return $listBarang;
  }

  public function listGudang (Request $req) {
    $listLokasi = DB::connection('SML')->select("select KODEGDG, NAMA from DBGUDANG");
    return $listLokasi;
  }

  // public function getNoBukti (Request $req) {
  //   $kodeInisial = "TFL";
  //
  //   $periode = NewPeriode::where('user_id' , \Auth::id())->first();
  //   $inisial = DB::connection("SML")->select('select ? from DBNOMOR',[$kodeInisial] );
  //   $username = \Auth::user()->username;
  //   // $tesInisial = $inisial[0];
  //   $values = [
  //       $inisial[0]->TFL,
  //       $periode->bulan,
  //       $periode->tahun,
  //       $username
  //   ];
  //   $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
  //   return $noBukti;
  // }

  public function getNoBukti (Request $req) {


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $inisial1 = 'PRU';
    $query = 'select '.$inisial1.' from DBNOMOR';
    $inisial = DB::connection("SML")->select($query);
    $username = \Auth::user()->username;
    $values = [
        $inisial[0]->$inisial1,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function detailPenerimaan (Request $req) {
    $penerimaan = VWMASTERPRUBAHKEMASANWEB::all()->where('NOBUKTI' ,$req->input('nobukti'))->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function detailPenerimaanPrint (Request $req) {
    $penerimaan = VWMASTERPRUBAHKEMASANWEB::all()->where('NOBUKTI' ,$req->input('nobukti'))->where('pDebet', 0)->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }



  public function loadAll () {
    $kodemenu = '029101';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();


    $penerimaan = VWMASTERPRUBAHKEMASANWEB::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return [
      "penerimaan" => $tempPenerimaan,
      "akses" => $akses
    ];
  }
  //
  // public function detailPenerimaan (Request $req) {
  //   $penerimaan = vwShowOutBrgNonSO::all()->where('NOBUKTI' ,$req->input('nobukti'))->sortBy('URUT');
  //   $tempPenerimaan = [];
  //   foreach ($penerimaan as $p) {
  //     // code...
  //     array_push($tempPenerimaan, $p);
  //   }
  //   return $tempPenerimaan;
  // }
  //
  // public function getNoBukti (Request $req) {
  //
  //   $periode = NewPeriode::where('user_id' , \Auth::id())->first();
  //   $inisial = DB::connection("SML")->select('select OBN from DBNOMOR');
  //   $username = \Auth::user()->username;
  //   $values = [
  //       $inisial[0]->OBN,
  //       $periode->bulan,
  //       $periode->tahun,
  //       $username
  //   ];
  //   $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
  //   return $noBukti;
  // }
  //


  public function spAdd (Request $req) {


    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $username = \Auth::user()->username;
    $choice = $req->input('choice');
    $nobukti = $req->input('nobukti');
    $tanggal = $req->input('tanggal');
    $note = $req->input('note');
    $urut = $req->input('urut');
    $kodebarang = $req->input('kodebrg');
    $kodegdg= $req->input('kodegdg');
    $SAT = $req->input('SAT');
    $ISI = $req->input('ISI');
    $nosat = $req->input('nosat');
    $qntDB = $req->input('qntDB');
    $qntCR = $req->input('qntCR');
    $harga = $req->input('harga');
    $nourut = $req->input('nourut');
    $flagtipe = $req->input('flagtipe');
    $biaya = $req->input('biaya');
    $hpp = $req->input('hpp');
    $jmlRecord = $req->input('jmlRecord');
    $qnt = $req->input('qnt');
    $pDebet = $req->input('pDebet');
    $tanggalInput = date("Y-m-d");
    $namabrg = $req->input('namabrg');



    if ($jmlRecord == 0 && $choice == 'I') {
      // code...
      $check = DB::connection('SML')->select('select * from dboutbrgdet where NOBUKTI = :nobukti',["nobukti" => $nobukti]);
      if ($check) {
        return 2;
      }
    }

    if ($choice != 'I') {
      $check2 = DB::connection('SML')->select('select * from dboutbrgdet2 where NoBeli = :nobukti',["nobukti" => $nobukti]);
      if ($check2) {
        return 3;
      }
    }



      $values = [
        $choice,
        $nobukti ,
        $tanggal ,
        $note ,
        $urut ,
        $kodebarang ,
        $kodegdg,
        $SAT ,
        $nosat ,
        $ISI,
        $qnt ,
        $harga ,
        $nourut ,
        $flagtipe ,
        $biaya ,
        $hpp,
        $pDebet,
        $username,
        'PRU',
        $tanggalInput,
        $namabrg
      ];

      DB::connection("SML")->statement('exec Sp_OutBrgUKWEB ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);

      return 1;


  }


  // public function spDelete (Request $req) {
  //
  //   $choice = $req->input('choice');
  //   $nobukti = $req->input('nobukti');
  //   $urut = $req->input('urut');
  //
  //
  //     $values = [
  //       $choice,
  //       $nobukti,
  //       '',
  //       '',
  //       '',
  //       '',
  //       '',
  //       '',
  //       $urut,
  //       '',
  //       '',
  //       0,
  //       0,
  //       '',
  //       0,
  //       '',
  //       0,
  //       '',
  //       '',
  //       ''
  //     ];
  //     DB::connection("SML")->statement('exec Sp_OutBrg ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);
  //
  //     return 1;
  //
  //
  // }




}
