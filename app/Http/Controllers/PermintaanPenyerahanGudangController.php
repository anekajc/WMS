<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\VWMASTERPRPENYERAHANBHN;


class PermintaanPenyerahanGudangController extends Controller
{




  public function index () {
    // kalo nosat 1 qnt , qnt2 langsung dr input
    //kalo nosat 2/3 , qnt dikali isi qnt 2 dr input
    $kodemenu = '031';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $penerimaan = VWMASTERPRPENYERAHANBHN::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return view('permintaanpenyerahangudang' , [
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
    $inisial1 = 'PRP';
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
    $penerimaan = VWMASTERPRPENYERAHANBHN::all()->where('NOBUKTI' ,$req->input('nobukti'))->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }



  public function loadAll () {
    $kodemenu = '031';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();


    $penerimaan = VWMASTERPRPENYERAHANBHN::all()->where('Bulan',$periode->bulan )->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
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
    $nourut = $req->input('nourut');
    $tanggal = $req->input('tanggal');
    $kodebarang = $req->input('kodebrg');
    $kodegdg= $req->input('kodegdg');
    $qnt1 = $req->input('qnt1');
    $qnt2 = $req->input('qnt2');
    $qntx = $req->input('qntx');
    $nosat = $req->input('nosat');
    $urut = $req->input('urut');
    $jmlRecord = $req->input('jmlRecord');



    if ($jmlRecord == 0 && $choice == 'I') {
      // code...
      $check = DB::connection('SML')->select('select * from dbPRPenyerahanBhnDET where NOBUKTI = :nobukti',["nobukti" => $nobukti]);
      if ($check) {
        return 2;
      }
    }



      $values = [
        $choice,
        $nobukti,
        $nourut,
        $tanggal,
        $kodegdg,
        $urut,
        $kodebarang,
        $qnt1,
        $nosat,
        '',
        0,
        '',
        $qnt2,
        '',
        0,
        0,
        0,
        '',
        '',
        $qntx
      ];

      DB::connection("SML")->statement('exec SP_PRPenyerahanBhnSampleWEB ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);

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
