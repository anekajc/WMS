<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewMenu;
use App\Model\NewAksesMenu;
use App\Model\NewPeriode;
use App\Model\NewUsers;
use Illuminate\Support\Facades\DB;
use App\Model\vwOutBarang;
use App\Model\vwShowOutBrgNonSO;


class NewPermintaanPersiapanNonSOController extends Controller
{




  public function index () {
    $kodemenu = '0211';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    if(!$akses || !$akses->HASACCESS) {
       return redirect('/home');
    }

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();




    return view('newpermintaanpersiapannonso' , [
      "periode" => $periode,
      "menul0" => $menul0,
      "akses" => $akses
    ]);
  }

  public function loadAll () {

    $kodemenu = '0211';
    $akses = NewAksesMenu::where('USERID', \Auth::id())-> where('L1', $kodemenu)->first();
    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $menul0 = app('App\Http\Controllers\NewMenuController')->getMenuL0();

    $outstanding = DB::connection("SML")->select('exec sp_sosiapkirimnonso ?,?,?',[ $periode->bulan , $periode->tahun ,\Auth::user()->username ]);


    // $penerimaan = vwShowOutBrg::all()->where('Bulan' ,$periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');
    $penerimaan = vwShowOutBrgNonSO::all()->where('Bulan' ,$periode->bulan)->where('Tahun', $periode->tahun)->sortBy('URUT')->groupBy('NOBUKTI');

    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }


    return [
      "outstanding" => $outstanding,
      "penerimaan" => $tempPenerimaan,
      "akses" => $akses
    ];
  }

  public function detailPenerimaan (Request $req) {
    $penerimaan = vwShowOutBrgNonSO::all()->where('NOBUKTI' ,$req->input('nobukti'))->sortBy('URUT');
    $tempPenerimaan = [];
    foreach ($penerimaan as $p) {
      // code...
      array_push($tempPenerimaan, $p);
    }
    return $tempPenerimaan;
  }

  public function getNoBukti (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $inisial = DB::connection("SML")->select('select OBN from DBNOMOR');
    $username = \Auth::user()->username;
    $values = [
        $inisial[0]->OBN,
        $periode->bulan,
        $periode->tahun,
        $username
    ];
    $noBukti = DB::connection('SML')->select('exec SP_IsiNobukti ?,?,?,?',$values);
    return $noBukti;
  }

  public function spAdd (Request $req) {

    $periode = NewPeriode::where('user_id' , \Auth::id())->first();
    $username = \Auth::user()->username;
    $choice = $req->input('choice');
    $tanggal = $req->input('tanggal');
    $keterangan = $req->input('keterangan');
    $nourut = $req->input('nourut');
    $nobukti = $req->input('nobukti');
    $data = $req->input('tempData');

    // return [$data,$date,$nourut,$nosj,$norspb];

    $check = DB::connection('SML')->select('select * from DBOutBrg where nobukti = :nobukti',["nobukti" => $nobukti]);
    if ($check) {
      return 2;
    }




    foreach ($data as $d) {
      $values = [
        $choice,
        $nobukti,
        $nourut,
        $tanggal,
        '',
        '',
        '',
        $keterangan,
        0,
        $d['Kodebrg'],
        $d['namabrg'],
        $d['QntXz'],
        $d['nosat'],
        $d['satuan'],
        $d['isi'],
        $d['NoBukti'],
        $d['Urut'],
        $username,
        $req->input('tanggalInput'),
        '',
        $d['Tipe']
      ];
      DB::connection("SML")->statement('exec Sp_OutBrg ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);
    }
      return 1;


  }

  public function spDelete (Request $req) {

    $choice = $req->input('choice');
    $nobukti = $req->input('nobukti');
    $urut = $req->input('urut');


      $values = [
        $choice,
        $nobukti,
        '',
        '',
        '',
        '',
        '',
        '',
        $urut,
        '',
        '',
        0,
        0,
        '',
        0,
        '',
        0,
        '',
        '',
        ''
      ];
      DB::connection("SML")->statement('exec Sp_OutBrg ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',$values);

      return 1;


  }




}
